<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EvaluationFormModel;
use App\Models\EvaluationItemModel;
use App\Models\CMOModel;
use App\Models\CriteriaModel;

class HEIFormController extends Controller
{


    public function store($id) {

        $tool = EvaluationFormModel::find($id);
        $cmo = CMOModel::where('id', $tool->cmoId)->with('criteria')->first();
        // $criteria = CriteriaModel::where('cmoId', $cmo->id)->orderBy('itemNo', 'asc')->get();

        // dd($cmo->criteria);

        foreach($cmo->criteria as $item) {
            EvaluationItemModel::create([
                'evaluationFormId' => $tool->id,
                'criteriaId' => $item->id,
                'selfEvaluationStatus' => 'Not complied',
                'evaluationStatus' => 'Not complied',
            ]);
        }

        $tool->update([
            'status' => 'In progress'
        ]);

        $tool->save();
        
        return redirect('/hei/evaluation/'. $id . '/edit');
    }

    public function edit($evaluation) {
        $tool = EvaluationFormModel::where('id', $evaluation)->with('institution_program.institution', 'institution_program.program')->first();
        $items = EvaluationItemModel::where('evaluationFormId', $evaluation)->with('criteria')->get();
        
        if($tool->status == 'In progress') {
            return Inertia::render('Progmes/Evaluation/Evaluation-HEI-Edit', [
                'evaluation' => $tool,
                'items' => $items,
            ]);
        } else {
            return redirect()->back()->with('failed', 'This form can\'t be accessed.');
        }

        
    }

    public function update(Request $request) {

        foreach ($request->items as $item) {
            $evaluationItem = EvaluationItemModel::find($item['id']);

            if ($evaluationItem) {
                $evaluationItem->update([
                    'actualSituation' => $item['selfEvaluationStatus'] == 'Not applicable' ? null : $item['actualSituation'],
                    'selfEvaluationStatus' => $item['selfEvaluationStatus'],
                ]);
            }
        }

        return redirect()->back()->with('message', 'Changes saved.');
    }

    public function upload(Request $request) {
        dd($request);
    }
}
