<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModel;
use App\Models\InstitutionProgramModel;
use App\Models\InstitutionModel;
use App\Models\EvaluationFormModel;
use App\Models\ProgramModel;
use Inertia\Inertia;
use Auth;

class EvaluationController extends Controller
{
    public function index() {
        $role = Auth::user()->role;
        if (
            $role == 'Super Admin' ||
            $role == 'Regional Director' ||
            $role == 'Chief Education Program Specialist' ||
            $role == 'Supervising Education Program Specialist' ||
            $role == 'Admin'
        ) {
            return redirect()->route('evaluation.admin');
        } else if ($role == 'Education Supervisor') {
            return redirect()->route('evaluation.ched');
        } else if ($role == 'Program Head') {
            return redirect()->route('evaluation.hei');
        } else if ($role == 'Dean') {
            return redirect()->route('evaluation.dean');
        }
    }

    public function EvaluationForAdmin() {
        $role = Auth::user()->role;

        return Inertia::render('Progmes/Evaluation/CHED-Evaluation-Admin-Select', [
            'data' => "Hello World",
            'role' => $role,
        ]);
    }

    public function EvaluationForES(Request $request) {
        $user = Auth::user();
        $show = $request->query('show') ? $request->query('show') : 25;
        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();

        $complianceTools = EvaluationFormModel::query()
        ->when($request->query('search'), function ($query) use ($request, $disciplineIds) {
            $query->where(function ($q) use ($request, $disciplineIds){
                $q->whereHas('institution_program.program', function ($searchQuery) use ($request, $disciplineIds) {
                    $searchQuery->where('program', 'like', '%' . $request->query('search') . '%');
                })
                ->orWhereHas('institution_program.institution', function ($searchQuery) use ($request, $disciplineIds) {
                    $searchQuery->where('name', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->whereIn('disciplineId', $disciplineIds)
            ->where(function ($query) {
                $query->whereNot('status', 'Deployed');
            });
        })
        ->where(function ($query) {
            $query->whereNot('status', 'Deployed');
        })
        ->whereIn('disciplineId', $disciplineIds)
        ->with('institution_program.program', 'institution_program.institution', 'item', 'complied', 'not_complied', 'not_applicable')
        ->paginate($show)
        ->through(fn($item) => [
            'id' => $item->id,
            'submissionDate' => $item->submissionDate,
            'status' => $item->status,
            'institution' => $item->institution_program->institution->name,
            'program' => $item->institution_program->program->program,
            'progress' => intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count())/$item->item->count())*100)),
        ])
        ->withQueryString();

        if ($user->role == 'Education Supervisor') {
            return Inertia::render('Progmes/Evaluation/CHED-Evaluation-ES-Select', [
                'role' => $user->role,
                'disciplines' => $disciplines,
                'complianceTools' => $complianceTools,
                'filters' => $request->only(['search']) + ['show' => $show ],
            ]);
        }
    }


    public function evaluationForProgramHead() {

        $role = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->first();
        
        $institutionProgram = InstitutionProgramModel::where([
            'institutionId' => $role->institutionId,
            'programId' => $role->programId,
        ])->with('institution', 'program', 'evaluationForm', 'evaluationForm.item')->first();
        
        return Inertia::render('Progmes/Evaluation/HEI-Evaluation-PH-Select', [
            'program' => $institutionProgram,
        ]);
    }



    public function evaluationForDean(Request $request) {
        $user = Auth::user();
        $disciplines = RoleModel::where('userId', $user->id)->where('isActive', 1)->with('discipline')->get();
        $institution = RoleModel::where('userId', $user->id)->where('isActive', 1)->value('institutionId');

        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        $show = $request->query('show') ? $request->query('show') : 25;

        $complianceTools = EvaluationFormModel::query()
        ->when($request->query('search'), function ($query) use ($request, $disciplineIds, $institution) {
            $query->where(function ($q) use ($request, $disciplineIds, $institution){
                $q->whereHas('institution_program.program', function ($searchQuery) use ($request, $disciplineIds) {
                    $searchQuery->where('program', 'like', '%' . $request->query('search') . '%');
                });
                $q->whereHas('institution_program.institution', function ($searchQuery) use ($request, $institution) {
                    $searchQuery->where('id', $institution);
                });
            })
            ->whereIn('disciplineId', $disciplineIds)
            ->where(function ($query) {
                $query->whereNot('status', 'Deployed');
            });
        })
        ->where(function ($query) {
            $query->whereNot('status', 'Deployed');
        })
        ->whereHas('institution_program.institution', function ($searchQuery) use ($institution) {
            $searchQuery->where('id', $institution);
        })
        ->whereIn('disciplineId', $disciplineIds)
        ->with('institution_program.institution', 'institution_program.program', 'item', 'complied', 'not_complied', 'not_applicable')
        ->paginate($show)
        ->through(fn($item) => [
            'id' => $item->id,
            'submissionDate' => $item->submissionDate,
            'status' => $item->status,
            'program' => $item->institution_program->program->program,
            'progress' => intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count())/$item->item->count())*100)),
        ])
        ->withQueryString();

        return Inertia::render('Progmes/Evaluation/HEI-Evaluation-Dean-Select', [
            'evaluation_tool' => $complianceTools,
            'role' => $disciplines,
            'filters' => $request->only(['search']) + ['show' => $show ],
        ]);
    }
    
}
