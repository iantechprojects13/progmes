<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EvaluationFormModel;
use App\Models\EvaluationItemModel;
use App\Models\CMOModel;
use App\Models\CriteriaModel;
use App\Models\EvidenceModel;

use Illuminate\Support\Str;

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

    public function generateKeyBeforeEdit($id) {
        $randomKey = $this->generateKey();
        return redirect('/hei/evaluation/'. $id . '/edit/'.$randomKey);
    }

    public function edit($evaluation, $key=123) {
        $tool = EvaluationFormModel::where('id', $evaluation)->with('institution_program.institution', 'institution_program.program')->first();
        $items = EvaluationItemModel::where('evaluationFormId', $evaluation)->with('criteria', 'evidence')->get();

        if($tool->status == 'In progress') {
            return Inertia::render('Progmes/Evaluation/Evaluation-HEI-Edit', [
                'evaluation' => $tool,
                'items' => $items->map(fn($item) => [
                    'id' => $item->id,
                    'itemNo' => $item->criteria->itemNo,
                    'area' => $item->criteria->area,
                    'minimumRequirement' => $item->criteria->minimumRequirement,
                    'actualSituation' => $item->actualSituation,
                    'selfEvaluationStatus' => $item->selfEvaluationStatus,
                    'evidence' => $item->evidence,
                ]),
            ]);
        } else {
            return redirect()->back()->with('failed', 'This form can\'t be accessed.');
        }
    }


    // public function evaluationInput($tool) {
    //     return Inertia::render('Progmes/Evaluation/HEI-Evaluation-PH-Edit', [
    //         'list' => User::all(),
    //     ]);
    // }

    // public function updateTool(Request $request) {
    //     if ($request->id % 2 == 0) {
    //         return redirect()->back()->with('success', 'Success.');
    //     } else {
    //         return redirect()->back()->with('error', 'Error.');
    //     }
    // }

    public function update(Request $request) {
        // dd($request->items);

        foreach ($request->items as $item) {
            $evaluationItem = EvaluationItemModel::find($item['id']);

            if ($evaluationItem) {
                $evaluationItem->update([
                    'actualSituation' => $item['selfEvaluationStatus'] == 'Not applicable' ? null : $item['actualSituation'],
                    'selfEvaluationStatus' => $item['selfEvaluationStatus'],
                ]);
            }
        }

        $randomKey = $this->generateKey();
        return redirect('/hei/evaluation/'. $request->id . '/edit/'.$randomKey);
    }

    public function upload(Request $request) {
        $validated = $request->validate([
            'file' => 'required',
        ], [
            'file.required' => 'Please select a file.',
        ]);

        dd(request()->file('file'));

        // $excelContent = [];
        // $fileName ='';

        // if (request()->file('cmo_file')) {
        //     $excelContent = Excel::toArray(new CMOImport, request()->file('cmo_file'));
        //     $fileName = request()->file('cmo_file')->getClientOriginalName();
        // } else {
        //     $excelContent = null;
        //     return redirect()->route('admin.cmo.list')->with('failed', 'Import failed!');
        // }

        // $newCMO = CMOModel::create([
        //     'status' => 'draft',
        //     'isActive' => 0,
        // ]);


    }

    public function submitLink(Request $request) {
        $validated = $request->validate([
            'evidenceLink.itemId' => 'nullable',
            'evidenceLink.link' => 'required|url',
        ], [
            'evidenceLink.link.required' => 'URL for the link is required.',
            'evidenceLink.link.url' => 'Invalid URL format.',
        ]);

        // dd($validated['evidenceLink']['itemId']);

        $evidenceItem = EvidenceModel::create([
            'itemId' => $validated['evidenceLink']['itemId'],
            'type' => "link",
            'url' => $validated['evidenceLink']['link'],
        ]);

        $randomKey = $this->generateKey();
        return redirect('/hei/evaluation/'. $request->id . '/edit/'.$randomKey);
    }


    function generateKey($length = 10) {
        return Str::random($length);
    }

}
