<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleModel;
use App\Models\InstitutionProgramModel;
use App\Models\InstitutionModel;
use App\Models\EvaluationFormModel;
use App\Models\ProgramModel;
use App\Models\AdminSettingsModel;
use Inertia\Inertia;
use Carbon\Carbon;
use Throwable;
use Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\ComplianceEmail;

class EvaluationController extends Controller
{
    public function index() {
        $user = Auth::user();

        if ($user->type == 'CHED') {
            return redirect()->route('evaluation.ched');
        }

        if ($user->role == 'Program Head') {
            return redirect()->route('evaluation.ph');
        }

        if ($user->type == 'HEI') {
            return redirect()->route('evaluation.hei');
        }
    }


    public function EvaluationForCHED(Request $request) {
        $user = Auth::user();
        $show = $request->query('show') ? $request->query('show') : 25;
        $acadYear = $request->query('academicYear') ? $request->query('academicYear') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        $canEvaluate = false;
        $canEmail = false;

        if ($user->role == 'Super Admin' || $user->role == 'Education Supervisor') {
            $canEvaluate = true;
            $canEmail = true;
        }

        if ($user->role == 'Admin') {
            $canEmail = true;
        }
        

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
            ->where(function ($query) {
                $query->whereNot('status', 'Deployed')->whereNot('status', 'Monitored');
            });
        })
        ->when($user->role == 'Education Supervisor', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->where('effectivity', $acadYear)
        ->where(function ($query) {
            $query->whereNot('status', 'Deployed')->whereNot('status', 'Monitored');
        })
        ->when($request->query('status'), function ($statusQuery) use ($request) {
            $statusQuery->where('status', $request->query('status'));
        })
        ->with('institution_program.program', 'institution_program.institution', 'item', 'complied', 'not_complied', 'not_applicable')
        ->paginate($show)
        ->through(fn($item) => [
            'id' => $item->id,
            'submissionDate' => $item->submissionDate,
            'status' => $item->status,
            'institution' => $item->institution_program->institution->name,
            'program' => $item->institution_program->program->program,
            'progress' => intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count())/$item->item->count())*100)),
            'isLocked' => $item->status == 'Locked' || $item->status == 'Submitted' ? true : false,
            'canBeArchived' =>$item->status == 'Submitted' ? true : false,
        ])
        ->withQueryString();

        return Inertia::render('Progmes/Evaluation/CHED-Evaluation-Select', [
            'complianceTools' => $complianceTools,
            'filters' => $request->only(['search', 'status']) + ['show' => $show, 'academicYear' => $acadYear ],
            'canEvaluate' => $canEvaluate,
            'canEmail' => $canEmail,
        ]);
    }

    public function monitoredForCHED(Request $request) {
        $user = Auth::user();
        $show = $request->query('show') ? $request->query('show') : 25;
        $acadYear = $request->query('academicYear') ? $request->query('academicYear') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');

        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();

        $canEvaluate = false;

        if ($user->role == 'Super Admin' || $user->role == 'Education Supervisor') {
            $canEvaluate = true;
        }

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
            ->where(function ($query) {
                $query->where('status', 'Monitored');
            });
        })
        ->when($user->role == 'Education Supervisor', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->where('effectivity', $acadYear)
        ->where(function ($query) {
            $query->where('status', 'Monitored');
        })
        ->with('institution_program.program', 'institution_program.institution', 'item', 'complied', 'not_complied', 'not_applicable')
        ->paginate($show)
        ->through(fn($item) => [
            'id' => $item->id,
            'submissionDate' => $item->submissionDate,
            'archivedDate' => Carbon::createFromFormat('Y-m-d', $item->archivedDate)->format('M d, Y'),
            'evaluationDate' => Carbon::createFromFormat('Y-m-d', $item->evaluationDate)->format('M d, Y'),
            'status' => $item->status,
            'institution' => $item->institution_program->institution->name,
            'program' => $item->institution_program->program->program,
        ])
        ->withQueryString();

        return Inertia::render('Progmes/Evaluation/CHED-Evaluation-Monitored', [
            'complianceTools' => $complianceTools,
            'filters' => $request->only(['search']) + ['show' => $show, 'academicYear' => $acadYear ],
            'canEvaluate' => $canEvaluate,
        ]);
    }


    public function evaluationForProgramHead() {

        $role = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->first();
        
        $institutionProgram = InstitutionProgramModel::where([
            'institutionId' => $role->institutionId,
            'programId' => $role->programId,
        ])->with(['institution', 'program', 'evaluationForm' => function ($evalFormQuery) {
            $evalFormQuery->orderBy('effectivity', 'asc');
        }, 'evaluationForm.item'])->first();
        
        return Inertia::render('Progmes/Evaluation/HEI-Evaluation-PH-Select', [
            'program' => $institutionProgram,
        ]);
    }



    public function evaluationForHEI(Request $request) {
        
        $user = Auth::user();
        $show = $request->query('show') ? $request->query('show') : 25;
        $acadYear = $request->query('academicYear') ? $request->query('academicYear') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        $institution = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->value('institutionId');
        $institutionName = InstitutionModel::where('id', $institution)->value('name');

        $complianceTools = EvaluationFormModel::query()
        ->when($request->query('search'), function ($query) use ($request, $disciplineIds) {
            $query->where(function ($q) use ($request, $disciplineIds){
                $q->whereHas('institution_program.program', function ($searchQuery) use ($request, $disciplineIds) {
                    $searchQuery->where('program', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->where(function ($query) {
                $query->whereNot('status', 'Deployed')->whereNot('status', 'Monitored');
            });
        })
        ->when($user->role == 'Dean', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->where('effectivity', $acadYear)
        ->whereHas('institution_program.institution', function($query) use ($institution) {
            $query->where('id', $institution);
        })
        ->where(function ($query) {
            $query->whereNot('status', 'Deployed')->whereNot('status', 'Monitored');
        })
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

        return Inertia::render('Progmes/Evaluation/HEI-Evaluation-Select', [
            'complianceTools' => $complianceTools,
            'filters' => $request->only(['search']) + ['show' => $show, 'academicYear' => $acadYear ],
            'institution' => $institutionName,
        ]);
    }


    public function archivedForHEI(Request $request) {
        $user = Auth::user();
        $show = $request->query('show') ? $request->query('show') : 25;
        $acadYear = $request->query('academicYear') ? $request->query('academicYear') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        $institution = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->value('institutionId');
        $institutionName = InstitutionModel::where('id', $institution)->value('name');

        $complianceTools = EvaluationFormModel::query()
        ->when($request->query('search'), function ($query) use ($request, $disciplineIds) {
            $query->where(function ($q) use ($request, $disciplineIds){
                $q->whereHas('institution_program.program', function ($searchQuery) use ($request, $disciplineIds) {
                    $searchQuery->where('program', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->where(function ($query) {
                $query->where('status', 'Monitored');
            });
        })
        ->when($user->role == 'Dean', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->where('effectivity', $acadYear)
        ->whereHas('institution_program.institution', function($query) use ($institution) {
            $query->where('id', $institution);
        })
        ->where(function ($query) {
            $query->where('status', 'Monitored');
        })
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

        return Inertia::render('Progmes/Evaluation/HEI-Evaluation-Archive', [
            'complianceTools' => $complianceTools,
            'filters' => $request->only(['search']) + ['show' => $show, 'academicYear' => $acadYear ],
            'institution' => $institutionName,
        ]);
    }


    public function sendEmail(Request $request) {

        try {
            $tool = EvaluationFormModel::where('id', $request->toolId)->first();
            $institution = $tool->institution_program->institutionId;
            $program = $tool->institution_program->programId;

            $userId = RoleModel::where('institutionId', $institution)->where('programId', $program)->where('isActive', 1)->value('userId');

            // dd($user);

            $user = User::where('id', $userId)->first();
            $email = $user->email;
            
            Mail::to($email)->send(new ComplianceEmail($user->name, $request->body));

            return redirect()->back()->with('success', 'Email sent successfully.');
            
        } catch(Throwable $thr) {
            return redirect()->back()->with('failed', 'Failed to send email.');
        }


        
    }
    
}
