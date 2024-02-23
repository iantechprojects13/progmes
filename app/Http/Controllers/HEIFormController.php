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
                'selfEvaluationStatus' => 'Not Complied',
                'evaluationStatus' => 'Not Complied',
            ]);
        }

        $tool->update([
            'status' => 'In progress'
        ]);

        $tool->save();
        
        return redirect('/hei/evaluation/'. $id);
    }

    public function edit($evaluation) {
        $form = EvaluationFormModel::where('id', $evaluation)->first();
        $items = EvaluationItemModel::where('evaluationFormId', $evaluation)->with('criteria')->get();
        
        if($form->status == 'In progress') {
            return Inertia::render('Progmes/Evaluation/Evaluation-HEI-Edit', [
                'evaluation' => $form,
                'items' => $items,
            ]);
        } else {
            return redirect()->back()->with('failed', 'This form can\'t be accessed.');
        }

        
    }

    public function update(Request $request) {
        
        // dd($request);
        // $affected = EvaluationItemModel::where('id', $request->id)
        //             ->update([
        //                 'actualSituation' => $request->actualSituation
        //             ]);
        $row = EvaluationItemModel::find($request->id);

        $row->actualSituation = $request->actualSituation;
        $row->selfEvaluationStatus = $request->selfEvaluationStatus;
        $row->save();

        if ($row) {
            return redirect()->back()->with('success', ' Edit successful! Changes saved.');
        }
    }
}
