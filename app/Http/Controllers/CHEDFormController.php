<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\EvaluationFormModel;
use App\Models\User;
use App\Models\EvaluationItemModel;
use App\Models\CMOModel;
use App\Models\RoleModel;
use App\Models\AdminSettingsModel;
use Inertia\Inertia;
use Auth;



class CHEDFormController extends Controller
{
    public function view($tool) {
        $user = Auth::user();

        $evaluationTool = EvaluationFormModel::where('id', $tool)->with('institution_program.program', 'institution_program.institution','complied', 'not_complied', 'not_applicable', 'item', 'item.criteria', 'item.evidence')->first();
        $showEvaluation = false;
        
        if (!$evaluationTool) {
            return redirect('/ched/evaluation')->with('failed', 'Evaluation tool not found.');
        }

        if ($user->role == 'Education Supervisor') {
            $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
            $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
            if (!in_array($evaluationTool->disciplineId, $disciplineIds)) {
                return redirect('/unauthorized');
            }
        }
        

        if ($evaluationTool->status != 'In progress') {
            $showEvaluation = true;
        }

        $compliedCount = $evaluationTool->complied->count();
        $notCompliedCount = $evaluationTool->not_complied->count();
        $notApplicableCount = $evaluationTool->not_applicable->count();
        $percentage = intval(round((($compliedCount + $notCompliedCount + $notApplicableCount)/$evaluationTool->item->count())*100));
        $progress = [$compliedCount, $notCompliedCount, $notApplicableCount, $percentage];

        return Inertia::render('Evaluation/CHED-Evaluation-View', [
            'evaluation' => $evaluationTool,
            'progress' => $progress,
            'showEvaluation' => $showEvaluation,
        ]);
    }
    
    public function evaluate($evaluation, Request $request) {
        $user = Auth::user();

        $tool = EvaluationFormModel::where('id', $evaluation)->with('institution_program.institution', 'institution_program.program')->first();

        if (!$tool) {
            return redirect('/ched/evaluation')->with('failed', 'Evaluation tool not found.');
        }

        if ($user->role == 'Education Supervisor') {
            $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
            $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
            if (!in_array($tool->disciplineId, $disciplineIds)) {
                return redirect('/unauthorized');
            }
        }

        $complianceTool = EvaluationItemModel::query()
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
            ->where('evaluationFormId', $evaluation)
            ->with('criteria', 'evidence')
            ->paginate(1000)
            ->withQueryString();

        if($tool->status == 'Submitted' || $tool->status == 'For re-evaluation' || $tool->status == 'Locked') {
            return Inertia::render('Evaluation/CHED-Evaluation-Edit', [
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


    public function update(Request $request) {

        foreach ($request->items as $item) {

            $evaluationItem = EvaluationItemModel::find($item['id']);

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
    


    public function monitored(Request $request) {

        $tool = EvaluationFormModel::where('id', $request->id)->first();

        if ($tool->status == 'In progress') {
            return redirect()->back()->with('failed', 'You cannot mark the compliance evaluation tool as monitored while it is in progress.');
        }

        if ($tool->evaluationDate == null) {
            return redirect()->back()->with('failed', 'Please provide a valid evaluation date before marking it as monitored.');
        }

        if($tool->status == 'Submitted' || $tool->status == 'Locked') {
            $tool->update([
                'status' => 'Monitored',
                'archivedDate' => now(),
            ]);
            $tool->save();
            
            return redirect()->back()->with('success', 'Compliance evaluation tool successfully marked as monitored.');
        }
        
        return redirect()->back()->with('failed', 'Failed! Please complete the evaluation before marking it as monitored.');
    }

    public function lock(Request $request) {
        $tool = EvaluationFormModel::where('id', $request->id)->first();

        if (!$tool) {
            return redirect('/ched/evaluation/')->with('error', 'No compliance evaluation tool found.');
        }

        $tool->update([
            'status' => 'Locked',
        ]);
        $tool->save();

        return redirect()->back()->with('success', 'Compliance evaluation tool has been locked.');

    }

    public function unlock(Request $request) {
        $tool = EvaluationFormModel::where('id', $request->id)->first();

        if (!$tool) {
            return redirect('/ched/evaluation/')->with('error', 'No compliance evaluation tool found.');
        }

        $tool->update([
            'status' => 'In progress',
        ]);

        $tool->save();

        return redirect()->back()->with('success', 'Compliance evaluation tool has been unlocked.');
    }
    
    public function report($tool) {
        $evaluationTool = EvaluationFormModel::where('id', $tool)->with('institution_program.institution', 'institution_program.program')->first();
        $signatories = AdminSettingsModel::where('setting_category', 'signatories')->get();

        return Inertia::render('Evaluation/CHED-Evaluation-Report', [
            'tool' => $evaluationTool,
            'signatories' => [
                'reviewedBy' => $signatories->where('setting_key', 'signatory_default_reviewedby')->value('setting_value'),
                'reviewedByTitle' => $signatories->where('setting_key', 'signatory_default_reviewedby_title')->value('setting_value'),
                'notedBy' => $signatories->where('setting_key', 'signatory_default_notedby')->value('setting_value'),
                'notedByTitle' => $signatories->where('setting_key', 'signatory_default_notedby_title')->value('setting_value'),
            ],
        ]);
    }
}
