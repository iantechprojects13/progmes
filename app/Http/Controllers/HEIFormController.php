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
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Kreait\Firebase\Factory;


class HEIFormController extends Controller
{
    public function store($id) {

        $tool = EvaluationFormModel::find($id);
        $cmo = CMOModel::where('id', $tool->cmoId)->with('criteria')->first();

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

        if(!$tool) {
            return redirect('/hei/evaluation')->with('failed', 'Evaluation tool not found.');
        }

        
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

    public function update(Request $request) {
        
        foreach ($request->items as $item) {
            $evaluationItem = EvaluationItemModel::find($item['id']);

            if ($evaluationItem) {
                $evaluationItem->update([
                    'actualSituation' => $item['actualSituation'],
                    'selfEvaluationStatus' => $item['selfEvaluationStatus'],
                ]);
            }
        }

        return redirect()->back()->with('updated', 'All changes saved.');
        
    }

    public function upload(Request $request) {
        $request->validate([
            'id' => 'required',
            'itemId' => 'required',
            'file' => 'required',
        ], [
            'file.required' => 'Please select a file.',
        ]);

        if($request->hasFile('file')) {
            $evidenceFile = $request->file('file');
            $itemId = $request->itemId;
            $toolId = $request->id;
            $filename = $evidenceFile->getClientOriginalName();
            $user = Auth::user();
            $effectivity = EvaluationFormModel::where('id', $toolId)->value('effectivity');


            $storage = Firebase::storage();
            $bucket = $storage->getBucket();

            $object = $bucket->upload(file_get_contents($evidenceFile->path()), [
                'name' => $filename,
            ]);

            $url = $object->signedUrl(new \DateTime('tomorrow'));

            EvidenceModel::create([
                'itemId' => $itemId,
                'type' => "file",
                'text' => $filename,
                'url' => $url,
            ]);
        }

        // $path = 'evidences/'.$effectivity.'/'.$user->name.'/'.$evidenceFile->getClientOriginalName();
        // $storage = Firebase::storage();

        // $fileUrl = $storage->getBucket()->upload(
        //     $evidenceFile,
        //     [
        //         'name' => $path,
        //         'metadata' => [
        //             'contentType' => $evidenceFile->getMimeType(),
        //         ],
        //     ]
        // )->signedUrl(now()->addMinutes(60));

        // EvidenceModel::create([
        //     'itemId' => $itemId,
        //     'type' => "file",
        //     'text' => $filename,
        //     'url' => $fileUrl,
        // ]);

        return redirect()->back()->with('uploaded', 'Evidence successfully uploaded.');
    }

    public function submitLink(Request $request) {

        $validated = $request->validate([
            'id' => 'required',
            'link' => 'required|url',
        ], [
            'link.required' => 'URL for the link is required.',
            'link.url' => 'Invalid URL format.',
        ]);

        $evidenceItem = EvidenceModel::create([
            'itemId' => $validated['id'],
            'type' => "link",
            'text' => $validated['link'],
            'url' => $validated['link'],
        ]);

        return redirect()->back()->with('uploaded', 'Link successfully posted.');
    }

    public function deleteLink(Request $request) {

        $evidenceModel = EvidenceModel::where('id', $request->item)->first();
        
        $evidenceModel->delete();

        return redirect()->back()->with('deleted', 'File/Link deleted successfully.');
    }


    public function readyForVisit(Request $request) {
        $evaluation = EvaluationFormModel::find($request->id);

        $evaluation->update([
            'status' => 'Submitted',
            'submissionDate' => now(),
        ]);
        $evaluation->save();

        return redirect('/hei/evaluation')->with('success', "The evaluation tool was successfully submitted.");
    }

    
    function generateKey($length = 10) {
        return Str::random($length);
    }


}
