<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EvaluationFormModel;
use App\Models\EvaluationItemModel;

class HEIFormController extends Controller
{
    public function edit($evaluation) {
        $form = EvaluationFormModel::where('id', $evaluation)->first();
        $items = EvaluationItemModel::where('evaluationFormId', $evaluation)->with('criteria')->get();

        if($form->status === 'In progress') {
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
