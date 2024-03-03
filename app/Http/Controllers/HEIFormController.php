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
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Str;
use Auth;


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
        $canSubmit = true;

        foreach ($items as $item) {
            if ($item->selfEvaluationStatus == 'Not complied') {
                $canSubmit = false;
                break;
            }
            if ($item->selfEvaluationStatus == 'Complied') {
                if ($item->actualSituation != null || count($item->evidence) > 0) {
                    continue;
                } else {
                    $canSubmit = false;
                    break;
                }
            }

        }
        
        if($tool->status == 'In progress') {
            return Inertia::render('Progmes/Evaluation/HEI-Evaluation-Edit', [
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
                'canSubmit' => $canSubmit,
            ]);
        } else {
            return redirect()->back()->with('failed', 'This tool can\'t be accessed.');
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
                    'actualSituation' => $item['actualSituation'],
                    'selfEvaluationStatus' => $item['selfEvaluationStatus'],
                ]);
            }
        }

        $randomKey = $this->generateKey();
        return redirect('/hei/evaluation/'. $request->id . '/edit/'.$randomKey);
    }

    public function upload(Request $request) {
        $request->validate([
            'id' => 'required',
            'evidence.itemId' => 'required',
            'evidence.file' => 'required',
        ], [
            'evidence.file.required' => 'Please select a file.',
        ]);

        $evidenceFile = $request->file('evidence.file');
        $itemId = $request->evidence['itemId'];
        $toolId = $request->id;
        $filename = $evidenceFile->getClientOriginalName();
        $user = Auth::user();
        $effectivity = EvaluationFormModel::where('id', $toolId)->value('effectivity');

        // dd($effectivity);

        

        $path = 'evidences/'.$effectivity.'/'.$user->name.'/'.$evidenceFile->getClientOriginalName();
        $storage = Firebase::storage();

        $fileUrl = $storage->getBucket()->upload(
            $evidenceFile,
            [
                'name' => $path,
                'metadata' => [
                    'contentType' => $evidenceFile->getMimeType(),
                ],
            ]
        )->signedUrl(now()->addMinutes(60));

        EvidenceModel::create([
            'itemId' => $itemId,
            'type' => "file",
            'text' => $filename,
            'url' => $fileUrl,
        ]);

        $randomKey = $this->generateKey();
        return redirect('/hei/evaluation/'. $request->id . '/edit/'.$randomKey);
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
        return redirect('/hei/evaluation/'. $request->id . '/edit/'.$randomKey)->with('success', 'Successful link upload. :)');
    }

    public function deleteLink(Request $request) {
        $evidenceModel = EvidenceModel::where('id', $request->evidenceLink)->first();
        
        $evidenceModel->delete();

        $randomKey = $this->generateKey();
        return redirect('/hei/evaluation/'. $request->id . '/edit/'.$randomKey);
    }


    public function readyForVisit(Request $request) {
        $evaluation = EvaluationFormModel::find($request->id);

        $evaluation->update([
            'status' => 'submitted',
            'submissionDate' => now(),
        ]);
        $evaluation->save();

        return redirect('/hei/evaluation')->with('success', "The evaluation tool was successfully submitted.");
    }

    
    function generateKey($length = 10) {
        return Str::random($length);
    }


}
