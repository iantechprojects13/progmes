<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibEvaluationFormModel;
use App\Models\LibEvaluationItemModel;
use App\Models\AdminSettingsModel;
use Inertia\Inertia;
use Auth;

class CHEDLibraryEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        $acadYear = getAcademicYear($request->query('ay'),Auth::user()->id);
        
        $complianceTools = LibEvaluationFormModel::query()
            ->when($request->query('search'), function ($searchQuery) use ($request) {
                $searchQuery->whereHas('institution', function ($institutionQuery) use ($request) {
                    $institutionQuery->where('name', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->where('effectivity', $acadYear)
            ->when($request->query('status'), function ($statusQuery) use ($request) {
                $statusQuery->where('status', $request->query('status'));
            })
            ->with(['institution' => function($query) {
                $query->orderBy('name', 'asc');
            }])
            ->whereHas('institution')
            ->join('institution', 'lib_evaluation_form.institutionId', '=', 'institution.id')
            ->orderBy('institution.name', 'asc')
            ->select('lib_evaluation_form.*')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/library/Library-Evaluation-List', [
            'evaluation_list' => $complianceTools,
            'filters' => $request->only(['search', 'status']) + ['show' => $show, 'academicYear' => $acadYear ],
        ]);
    }

    public function libraryEvaluationList(Request $request) {
        $user = Auth::user();
        $show = sanitizePerPage($request->query('show'), $user->id);
        $acadYear = getAcademicYear($request->query('academicyear'), $user->id);
        $canEvaluate = false;
        
        if ($user->role == 'Super Admin' || $user->role == 'Education Supervisor') {
            $canEvaluate = true;
            $canEmail = true;
        }
        
        $query = LibEvaluationFormModel::query()
            ->when($request->query('search'), function ($query) use ($request) {
                applyInstitutionSearch(
                    $query, 
                    $request->query('search'), 
                    'institution',
                );
            })
            ->where('effectivity', $acadYear)
            ->where(function ($query) {
                $query->whereNot('status', 'Deployed');
            })
            ->when($request->query('status'), function ($statusQuery) use ($request) {
                $statusQuery->where('status', $request->query('status'));
            })
            ->with('institution', 'item', 'complied', 'not_complied', 'not_applicable');
        
        // Get all results without pagination first
        $allResults = $query->get();
        
        // Transform and calculate complianceRate for each item
        $transformedResults = $allResults->map(function($item) {
            return [
                'id' => $item->id,
                'submissionDate' => $item->submissionDate,
                'status' => $item->status,
                'institution' => $item->institution->name,
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
        
        return Inertia::render('Evaluation/CHED-Evaluation-Library-List', [
            'evaluation_list' => $paginator,
            'filters' => $request->only(['search', 'status', 'sort', 'direction']) + ['show' => $show, 'academicYear' => $acadYear],
            'canEvaluate' => $canEvaluate,
        ]);
    }

    // public function libraryEvaluationList(Request $request)
    // {
    //     $user = Auth::user();
    //     $show = sanitizePerPage($request->query('show'), Auth::user()->id);
    //     $acadYear = getAcademicYear($request->query('ay'),Auth::user()->id);
    //     $canEvaluate = $user->role == 'Super Admin' || $user->role == 'Education Supervisor' ? true: false;
        

    //     $complianceTools = LibEvaluationFormModel::query()
    //         ->when($request->query('search'), function ($searchQuery) use ($request) {
    //             $searchQuery->whereHas('institution', function ($institutionQuery) use ($request) {
    //                 $institutionQuery->where('name', 'like', '%' . $request->query('search') . '%');
    //             });
    //         })
    //         ->where('effectivity', $acadYear)
    //         ->whereNot('status', 'Deployed')
    //         ->when($request->query('status'), function ($statusQuery) use ($request) {
    //             $statusQuery->where('status', $request->query('status'));
    //         })
    //         ->with(['institution' => function($query) {
    //             $query->orderBy('name', 'asc');
    //         }])
    //         ->whereHas('institution')
    //         ->join('institution', 'lib_evaluation_form.institutionId', '=', 'institution.id')
    //         ->orderBy('institution.name', 'asc')
    //         ->select('lib_evaluation_form.*')
    //         ->paginate($show)
    //         ->withQueryString();

    //     return Inertia::render('Evaluation/CHED-Evaluation-Library-List', [
    //         'evaluation_list' => $complianceTools,
    //         'filters' => $request->only(['search', 'status']) + ['show' => $show, 'academicYear' => $acadYear ],
    //         'canEvaluate' => $canEvaluate,
    //     ]);
    // }

    public function view($tool) {
        $user = Auth::user();

        $evaluationTool = LibEvaluationFormModel::where('id', $tool)->with('institution', 'complied', 'not_complied', 'not_applicable', 'item', 'item.criteria', 'item.evidence')->first();
        $showEvaluation = false;
        
        if (!$evaluationTool) {
            return redirect('/ched/library/evaluation')->with('failed', 'Evaluation tool not found.');
        }
        
        if ($evaluationTool->status != 'In progress') {
            $showEvaluation = true;
        }

        $compliedCount = $evaluationTool->complied->count();
        $notCompliedCount = $evaluationTool->not_complied->count();
        $notApplicableCount = $evaluationTool->not_applicable->count();
        $percentage = intval(round((($compliedCount + $notCompliedCount + $notApplicableCount)/$evaluationTool->item->count())*100));
        $progress = [$compliedCount, $notCompliedCount, $notApplicableCount, $percentage];

        return Inertia::render('Evaluation/CHED-Evaluation-Library-View', [
            'evaluation' => $evaluationTool,
            'progress' => $progress,
            'showEvaluation' => $showEvaluation,
        ]);
    }

    public function evaluate($evaluation, Request $request) {
        $user = Auth::user();

        $tool = LibEvaluationFormModel::where('id', $evaluation)->with('institution')->first();

        if (!$tool) {
            return redirect('/ched/library/evaluation')->with('failed', 'Evaluation tool not found.');
        }

        if (!($user->role == 'Super Admin' || $user->role == 'Education Supervisor')) {
            return redirect('/ched/library/evaluation')->with('failed', 'You are not authorized to evaluate this tool.');
        }

        $complianceTool = LibEvaluationItemModel::query()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('actualSituation', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('findings', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('recommendations', 'like', '%' . $request->query('search') . '%')
                        ->orWhereHas('criteria', function ($criteriaQuery) use ($request) {
                            $criteriaQuery->where('area', 'like', '%' . $request->query('search') . '%')
                            ->orWhere('minimumRequirement', 'like', '%' . $request->query('search') . '%');
                        });
                });
            })
            ->when($request->query('selfEvaluationStatus'), function ($query) use ($request){
                if ($request->query('selfEvaluationStatus') == 'No Status') {
                    $query->where(function ($query) use ($request) {
                        $query->where('selfEvaluationStatus', null);
                    });
                }
                else {
                        $query->where(function ($query) use ($request) {
                        $query->where('selfEvaluationStatus', $request->query('selfEvaluationStatus'));
                    });
                }
            })
            ->when($request->query('evaluationStatus'), function ($query) use ($request){
                if ($request->query('evaluationStatus') == 'No Status') {
                    $query->where(function ($query) use ($request) {
                        $query->where('evaluationStatus', null);
                    });
                }
                else {
                        $query->where(function ($query) use ($request) {
                        $query->where('evaluationStatus', $request->query('evaluationStatus'));
                    });
                }
            })
            ->where('libEvaluationFormId', $evaluation)
            ->with('criteria', 'evidence')
            ->paginate(1000)
            ->withQueryString();

        if($tool->status == 'Submitted' || $tool->status == 'For re-evaluation' || $tool->status == 'Locked') {
            return Inertia::render('Evaluation/CHED-Evaluation-Library-Edit', [
                'evaluation' => $tool,
                'items' => $complianceTool,
                'filters' => $request->only(['search', 'selfEvaluationStatus', 'evaluationStatus']),
            ]);
        } else if ($tool->status == 'In progress') {
            return redirect()->back()->with('failed', 'This compliance evaluation tool can\'t be evaluated yet.');
        } else {
            return redirect()->back()->with('failed', 'This compliance evaluation tool can\'t be accessed.');
        }
    }

    public function lock(Request $request) {
        $tool = LibEvaluationFormModel::where('id', $request->id)->first();

        if (!$tool) {
            return redirect('/ched/library/evaluation')->with('failed', 'No library compliance evaluation tool found.');
        }

        $tool->update([
            'status' => 'Locked',
        ]);
        $tool->save();

        return redirect()->back()->with('success', 'Library compliance evaluation tool has been locked.');

    }

    public function unlock(Request $request) {
        $tool = LibEvaluationFormModel::where('id', $request->id)->first();

        if (!$tool) {
            return redirect('/ched/library/evaluation/')->with('failed', 'No library compliance evaluation tool found.');
        }

        $tool->update([
            'status' => 'In progress',
        ]);

        $tool->save();

        return redirect()->back()->with('success', 'Library compliance evaluation tool has been unlocked.');
    }


    public function update(Request $request) {

        foreach ($request->items as $item) {

            $evaluationItem = LibEvaluationItemModel::find($item['id']);

            if ($evaluationItem) {
                $evaluationItem->update([
                    'findings' => $item['findings'] ?? null,
                    'recommendations' => $item['recommendations'] ?? null,
                    'evaluationStatus' => $item['evaluationStatus'] ?? null,
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'All changes saved.');
    }

    public function destroy($id)
    {

        $tool = LibEvaluationFormModel::find($id);
        
        if ($tool) {
            foreach ($tool->item as $item) {
                $item->evidence()->delete();
                $item->delete();
            }
            $tool->delete();
            return redirect()->back()->with('success', 'Evaluation tool has been deleted successfully.');
        }

        return redirect()->back()->with('failed', 'Evaluation tool not found.');
    }

    public function report($tool) {
        $evaluationTool = LibEvaluationFormModel::where('id', $tool)->with('institution')->first();
        $signatories = AdminSettingsModel::where('setting_category', 'signatories')->get();

        if($evaluationTool->notedBy == null || $evaluationTool->notedBy == '') {
            $evaluationTool->notedBy = $signatories->where('setting_key', 'notedby')->value('setting_value');
            $evaluationTool->notedByTitle = $signatories->where('setting_key', 'notedby_title')->value('setting_value');
            $evaluationTool->save();
        }

        if($evaluationTool->reviewedBy == null || $evaluationTool->reviewedBy == '') {
            $evaluationTool->reviewedBy = $signatories->where('setting_key', 'reviewedby')->value('setting_value');
            $evaluationTool->reviewedByTitle = $signatories->where('setting_key', 'reviewedby_title')->value('setting_value');
            $evaluationTool->save();
        }
        
        return Inertia::render('Evaluation/CHED-Evaluation-Library-Report', [
            'tool' => $evaluationTool,
            'signatories' => [
                'reviewedBy' => $signatories->where('setting_key', 'reviewedby')->value('setting_value'),
                'reviewedByTitle' => $signatories->where('setting_key', 'reviewedby_title')->value('setting_value'),
                'notedBy' => $signatories->where('setting_key', 'notedby')->value('setting_value'),
                'notedByTitle' => $signatories->where('setting_key', 'notedby_title')->value('setting_value'),
            ],
        ]);
    }
    
}
