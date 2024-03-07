<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\EvaluationFormModel;
use App\Models\EvaluationItemModel;
use App\Models\CMOModel;
use Inertia\Inertia;
use Auth;

class CHEDFormController extends Controller
{

    public function generateKeyBeforeEdit($id) {
        $randomKey = $this->generateKey();
        return redirect('/ched/evaluation/'. $id . '/evaluate/'.$randomKey);
    }
    
    public function evaluate($evaluation, $key=123) {
        $tool = EvaluationFormModel::where('id', $evaluation)->with('institution_program.institution', 'institution_program.program')->first();
        $items = EvaluationItemModel::where('evaluationFormId', $evaluation)->with('criteria', 'evidence')->get();
        
        if($tool->status == 'Submitted') {
            return Inertia::render('Progmes/Evaluation/CHED-Evaluation-Edit', [
                'evaluation' => $tool,
                'items' => $items->map(fn($item) => [
                    'id' => $item->id,
                    'itemNo' => $item->criteria->itemNo,
                    'area' => $item->criteria->area,
                    'minimumRequirement' => $item->criteria->minimumRequirement,
                    'actualSituation' => $item->actualSituation,
                    'selfEvaluationStatus' => $item->selfEvaluationStatus,
                    'evaluationStatus' => $item->evaluationStatus,
                    'findings' => $item->findings,
                    'recommendations' => $item->recommendations,
                    'evidence' => $item->evidence,
                ]),
            ]);
        } else {
            return redirect()->back()->with('failed', 'This tool can\'t be accessed.');
        }
    }

    public function update(Request $request) {
        foreach ($request->items as $item) {
            $evaluationItem = EvaluationItemModel::find($item['id']);

            if ($evaluationItem) {
                $evaluationItem->update([
                    'findings' => $item['findings'],
                    'recommendations' => $item['recommendations'],
                    'evaluationStatus' => $item['evaluationStatus'],
                ]);
            }
        }

        $randomKey = $this->generateKey();
        return redirect('/ched/evaluation/'. $request->id . '/evaluate/'.$randomKey);
    }

    function generateKey($length = 10) {
        return Str::random($length);
    }
}
