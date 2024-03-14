<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModel;
use App\Models\InstitutionProgramModel;
use App\Models\EvaluationFormModel;
use App\Models\ProgramModel;
use Inertia\Inertia;
use Auth;

class EvaluationController extends Controller
{
    public function index() {
        $userRole = Auth::user()->role;
        if (
            $userRole == 'Super Admin' ||
            $userRole == 'Regional Director' ||
            $userRole == 'Chief Education Program Specialist' ||
            $userRole == 'Supervising Education Program Specialist'
        ) {
            return redirect()->route('evaluation.admin');
        } else if ($userRole == 'Education Supervisor') {
            return redirect()->route('evaluation.ched');
        } else if ($userRole == 'Program Head') {
            return redirect()->route('evaluation.hei.programhead');
        }
    }

    public function EvaluationForAdmin() {
        $role = Auth::user()->role;

        return Inertia::render('Progmes/Evaluation/CHED-Evaluation-Admin-Select', [
            'data' => "Hello World",
            'role' => $role,
        ]);
    }

    public function EvaluationForCHED(Request $request) {
        $user = Auth::user();
        $disciplines = RoleModel::where('userId', Auth::user()->id)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();

        $complianceTools = EvaluationFormModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->whereHas('institution_program.program', function ($searchQuery) use ($request) {
                $searchQuery->where('program', 'like', '%' . $request->query('search') . '%')
               ->where('status', 'Submitted')->orWhere('status', 'In progress');
            })
            ->orWhereHas('institution_program.institution', function ($searchQuery) use ($request) {
                $searchQuery->where('name', 'like', '%' . $request->query('search') . '%')
               ->where('status', 'Submitted')->orWhere('status', 'In progress');
            });
        })
        ->whereIn('disciplineId', $disciplineIds)
        ->where('status', 'Submitted')
        ->orWhere('status', 'In progress')
        ->with('institution_program.program', 'institution_program.institution', 'item', 'complied', 'not_applicable');

        $count = $complianceTools->count();

        $complianceTools = $complianceTools
        ->paginate(10)
        ->through(fn($item) => [
            'id' => $item->id,
            'submissionDate' => $item->submissionDate,
            'status' => $item->status,
            'institution' => $item->institution_program->institution->name,
            'program' => $item->institution_program->program->program,
            'itemComplied' => $item->complied->count(),
            'applicableItems' => $item->item->count() - $item->not_applicable->count(),
        ])
        ->withQueryString();

        if ($user->role == 'Education Supervisor') {
            return Inertia::render('Progmes/Evaluation/CHED-Evaluation-ES-Select', [
                'role' => $user->role,
                'disciplines' => $disciplines,
                'complianceTools' => $complianceTools,
                'count' => $count,
                'filters' => $request->only(['search']),
            ]);
        }
    }


    public function evaluationForProgramHead() {

        $role = RoleModel::where('userId', Auth::user()->id)->first();
        
        $institutionProgram = InstitutionProgramModel::where([
            'institutionId' => $role->institutionId,
            'programId' => $role->programId,
        ])->with('institution', 'program', 'evaluationForm')->first();
        
        return Inertia::render('Progmes/Evaluation/HEI-Evaluation-PH-Select', [
            'program' => $institutionProgram,
        ]);
    }
}
