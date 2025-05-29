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
use App\Models\ProgramAssignmentModel;
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

        if ($user->role == 'Program Coordinator') {
            return redirect()->route('evaluation.pc');
        }

        if ($user->type == 'HEI') {
            return redirect()->route('evaluation.hei');
        }
    }

    public function library()
    {
        $user = Auth::user();
        
        if ($user->type == 'HEI') {
            return redirect()->route('hei.library.evaluation.list');
        }

        if ($user->type == 'CHED') {
            return redirect()->route('ched.library.evaluation.list');
        }
    }

    
    public function EvaluationForCHED(Request $request) {
        $user = Auth::user();
        $show = sanitizePerPage($request->query('show'), $user->id);
        $acadYear = getAcademicYear($request->query('academicYear'), $user->id);
        $canEvaluate = false;
        
        if ($user->role == 'Super Admin' || $user->role == 'Education Supervisor') {
            $canEvaluate = true;
        }
        
        $assignedProgramIds = getUserAssignedProgramIds($user->id);
        
        $query = EvaluationFormModel::query()
            ->when($request->query('search'), function ($query) use ($request) {
                applyProgramAndInstitutionSearch(
                    $query, 
                    $request->query('search'), 
                    'institution_program.program', 
                    'institution_program.institution'
                );
            })
            ->when($user->role == 'Education Supervisor', function ($query) use ($assignedProgramIds) {
                $query->whereHas('institution_program', function ($q) use ($assignedProgramIds) {
                    $q->whereIn('programId', $assignedProgramIds);
                });
            })
            ->when($request->query('institution'), function ($query) use ($request) {
                $query->whereHas('institution_program.institution', function ($institutionQuery) use ($request) {
                    $institutionQuery->where('id', $request->query('institution'));
                });
            })
            ->when($request->query('program'), function ($query) use ($request) {
                $query->whereHas('institution_program.program', function ($programQuery) use ($request) {
                    $programQuery->where('id', $request->query('program'));
                });
            })
            ->where('effectivity', $acadYear)
            ->where(function ($query) {
                $query->whereNot('status', 'Deployed')->whereNot('status', 'Monitored');
            })
            ->when($request->query('status'), function ($statusQuery) use ($request) {
                $statusQuery->where('status', $request->query('status'));
            })
            ->with('institution_program.program', 'institution_program.institution', 'item', 'complied', 'not_complied', 'not_applicable');
        
        // Get all results without pagination first
        $allResults = $query->get();
        
        // Transform and calculate complianceRate for each item
        $transformedResults = $allResults->map(function($item) {
            return [
                'id' => $item->id,
                'submissionDate' => $item->submissionDate,
                'status' => $item->status,
                'institution' => $item->institution_program->institution->name,
                'program' => $item->institution_program->program->program,
                'major' => $item->institution_program->program->major,
                'progress' => $item->item->count() > 0 
                    ? intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count()) / $item->item->count()) * 100))
                    : 0,
                'complianceRate' => $item->complied->count() + $item->not_complied->count() > 0
                    ? intval(round(($item->complied->count() / ($item->complied->count() + $item->not_complied->count())) * 100))
                    : 0,
                'isLocked' => $item->status == 'Locked' || $item->status == 'For re-evaluation' || $item->status == 'Submitted' ? true : false,
                'canBeArchived' => $item->status == 'Submitted' ? true : false,
            ];
        });
        
        // Sort by complianceRate if requested
        $sortField = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');
        
        if ($sortField === 'complianceRate') {
            $transformedResults = $sortDirection === 'asc' 
                ? $transformedResults->sortBy('complianceRate')
                : $transformedResults->sortByDesc('complianceRate');
        } elseif ($sortField === 'progress') {
            $transformedResults = $sortDirection === 'asc' 
                ? $transformedResults->sortBy('progress')
                : $transformedResults->sortByDesc('progress');
        } else {
            $transformedResults = $sortDirection === 'desc' 
                ? $transformedResults->sortBy('progress')
                : $transformedResults->sortByDesc('progress');
        }
        
        // Manually paginate the results
        $page = $request->query('page', 1);
        $perPage = $show;
        $total = $transformedResults->count();
        
        $paginatedResults = $transformedResults->slice(($page - 1) * $perPage, $perPage)->values();
        
        // Create a custom paginator
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedResults,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        return Inertia::render('Evaluation/CHED-Evaluation-List', [
            'complianceTools' => $paginator,
            'institution_list' => InstitutionModel::orderBy('name', 'asc')->get(),
            'program_list' => ProgramModel::query()
                ->when($user->role == 'Education Supervisor', function ($query) use ($assignedProgramIds) {
                    $query->whereIn('id', $assignedProgramIds);
                })->orderBy('program', 'asc')->get(),
            'filters' => $request->only(['search', 'status', 'institution', 'program', 'sort', 'direction']) + ['show' => $show, 'academicYear' => $acadYear],
            'canEvaluate' => $canEvaluate,
        ]);
    }

    public function monitoredForCHED(Request $request) {
        $user = Auth::user();
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        $acadYear = getAcademicYear($request->query('academicYear'),Auth::user()->id);
        $programIds = getUserAssignedProgramIds($user->id);
        
        // $programFilter = $request->query('program') != null ? $request->query('program') : null;
        // $institutionFilter = $request->query('institution') != null ? $request->query('institution') : null;

        $complianceTools = EvaluationFormModel::query()
            ->when($request->query('search'), function ($query) use ($request) {
                applyProgramAndInstitutionSearch($query, $request->query('search'), 'institution_program.program', 'institution_program.institution');
            })
            ->when($request->query('institution'), function($query) use ($request) {
                $query->whereHas('institution_program.institution', function ($institutionQuery) use ($request) {
                    $institutionQuery->where('id', $request->query('institution'));
                });
            })
            ->when($request->query('program'), function($query) use ($request) {
                $query->whereHas('institution_program.program', function ($programQuery) use ($request) {
                    $programQuery->where('id', $request->query('program'));
                });
            })
            ->when($user->role == 'Education Supervisor', supervisorAssignedPrograms('institution_program.program', $programIds))
            ->where('effectivity', $acadYear)
            ->where('status', 'Monitored')
            ->orderBy('evaluationDate', 'desc')
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

        return Inertia::render('Evaluation/CHED-Evaluation-Monitored', [
            'complianceTools' => $complianceTools,
            'institution_list' => InstitutionModel::orderBy('name', 'asc')->get(),
            'program_list' => ProgramModel::query()
                ->when($user->role == 'Education Supervisor', function ($query) use ($programIds) {
                    $query->whereIn('id', $programIds);
                })->orderBy('program', 'asc')->get(),
            'filters' => $request->only(['search', 'institution', 'program']) + ['show' => $show, 'academicYear' => $acadYear ],
        ]);
    }


    public function evaluationForProgramHead(Request $request) {
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        $acadYear = $request->query('academicYear') != null ? $request->query('academicYear') : null;

        $role = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->first();
        
        $institutionProgram = InstitutionProgramModel::where([
            'institutionId' => $role->institutionId,
            'programId' => $role->programId,
        ])->with('program')->first();

        $program = $institutionProgram->program->program;
        
        $institution = $institutionProgram->institution->id;
        $allProg = ProgramModel::where('program', $program)->pluck('id');

        $programs = InstitutionProgramModel::whereIn('programId', $allProg)->where('institutionId', $institution)->pluck('id');
        
        $evaluationTools = EvaluationFormModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            applyProgramSearch($query, $request->query('search'), 'institution_program.program');
        })
        ->when($request->query('academicYear'), function ($query) use ($request) {
            $query->where('effectivity', $request->query('academicYear'));
        })
        ->when($request->query('status'), function ($query) use ($request) {
            $query->where('status', $request->query('status'));
        })
        ->whereIn('institutionProgramId', $programs)
        ->orderBy('effectivity', 'desc')
        ->with('institution_program.program')
        ->paginate($show)
        ->withQueryString();
        
        return Inertia::render('Evaluation/HEI-Evaluation-PH-List', [
            'program' => $institutionProgram,
            'evaluationTools' => $evaluationTools->through(function ($tool) {
            return [
                'id' => $tool->id,
                'effectivity' => $tool->effectivity,
                'status' => $tool->status,
                'program' => $tool->institution_program->program->program,
                'major' => $tool->institution_program->program?->major,
            ];
            }),
            'filters' => $request->only(['search', 'status']) + ['show' => $show, 'academicYear' => $acadYear ],
        ]);
    }

    public function evaluationForProgramCoordinator() {

        $role = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->first();
        
        $institutionProgram = InstitutionProgramModel::where([
            'institutionId' => $role->institutionId,
            'programId' => $role->programId,
        ])->with(['institution', 'program', 'evaluationForm' => function ($evalFormQuery) {
            $evalFormQuery->orderBy('effectivity', 'desc');
        }, 'evaluationForm.item'])->first();
        
        return Inertia::render('Evaluation/HEI-Evaluation-PC-List', [
            'program' => $institutionProgram,
        ]);
    }



    public function evaluationForHEI(Request $request) {
        
        $user = Auth::user();
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        $acadYear = getAcademicYear($request->query('academicYear'),Auth::user()->id);
        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        $institution = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->value('institutionId');
        $institutionName = InstitutionModel::where('id', $institution)->value('name');
        $canEdit = false;

        if ($user->role == 'Quality Assurance Officer') {
            $canEdit = true;
        }

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
            'major' => $item->institution_program->program->major,
            'progress' => intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count())/$item->item->count())*100)),
        ])
        ->withQueryString();

        return Inertia::render('Evaluation/HEI-Evaluation-List', [
            'complianceTools' => $complianceTools,
            'filters' => $request->only(['search', 'status']) + ['show' => $show, 'academicYear' => $acadYear ],
            'institution' => $institutionName,
            'canEdit' => $canEdit,
        ]);
    }


    public function monitoredForHEI(Request $request) {
        $user = Auth::user();
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        $acadYear = getAcademicYear($request->query('academicYear'), Auth::user()->id);
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
            'archivedDate' => Carbon::createFromFormat('Y-m-d', $item->archivedDate)->format('M d, Y'),
            'evaluationDate' => Carbon::createFromFormat('Y-m-d', $item->evaluationDate)->format('M d, Y'),
            'status' => $item->status,
            'institution' => $item->institution_program->institution->name,
            'program' => $item->institution_program->program->program,
            'progress' => intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count())/$item->item->count())*100)),
        ])
        ->withQueryString();

        return Inertia::render('Evaluation/HEI-Evaluation-Monitored', [
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

            $user = User::where('id', $userId)->first();
            $email = $user->email;
            
            Mail::to($email)->send(new ComplianceEmail($user->name, $request->body));

            return redirect()->back()->with('success', 'Email sent successfully.');
            
        } catch(Throwable $thr) {
            return redirect()->back()->with('failed', 'Failed to send email.');
        }


        
    }
    
}
