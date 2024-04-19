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
use Throwable;


class HEIFormController extends Controller
{
    public function store(Request $request) {
        $tool = EvaluationFormModel::find($request->id);
        $cmo = CMOModel::where('id', $tool->cmoId)->with('criteria')->first();

        foreach($cmo->criteria as $item) {
            EvaluationItemModel::create([
                'evaluationFormId' => $tool->id,
                'criteriaId' => $item->id,
                'selfEvaluationStatus' => null,
                'evaluationStatus' => null,
            ]);
        }

        $tool->update([
            'status' => 'In progress'
        ]);

        $tool->save();
        
        return redirect('/hei/evaluation/'. $request->id . '/edit');
    }

    public function view($tool) {
        $evaluationTool = EvaluationFormModel::where('id', $tool)->with('institution_program.program', 'institution_program.institution', 'complied', 'not_complied', 'not_applicable', 'item', 'item.criteria', 'item.evidence')->first();
        $showEvaluation = false;
        
        if (!$evaluationTool) {
            return redirect('/hei/evaluation')->with('failed', 'Evaluation tool not found.');
        }

        if ($evaluationTool->status != 'In progress') {
            $showEvaluation = true;
        }

        $compliedCount = $evaluationTool->complied->count();
        $notCompliedCount = $evaluationTool->not_complied->count();
        $notApplicableCount = $evaluationTool->not_applicable->count();
        $percentage = intval(round((($compliedCount + $notCompliedCount + $notApplicableCount)/$evaluationTool->item->count())*100));
        $progress = [$compliedCount, $notCompliedCount, $notApplicableCount, $percentage];
        

        return Inertia::render('Progmes/Evaluation/HEI-Evaluation-View', [
            'evaluation_tool' => $evaluationTool,
            'progress' => $progress,
            'showEvaluation' => $showEvaluation,
        ]);
    }

    public function edit($evaluation) {

        $tool = EvaluationFormModel::where('id', $evaluation)->with('institution_program.institution', 'institution_program.program')->first();

        if(!$tool) {
            return redirect('/hei/evaluation')->with('failed', 'Evaluation tool not found.');
        }

        
        $items = EvaluationItemModel::where('evaluationFormId', $evaluation)->with('criteria', 'evidence')->get();
        $totalItems = $items->count();
        $complied = $items->where('selfEvaluationStatus', 'Complied')->count();
        $notComplied = $items->where('selfEvaluationStatus', 'Not complied')->count();
        $notApplicable = $items->where('selfEvaluationStatus', 'Not applicable')->count();
        $totalItems = $totalItems - $notApplicable;
        $percentage = intval(round((($complied + $notComplied + $notApplicable)/$items->count())*100));
        $progress = [ $complied, $notComplied, $notApplicable, $percentage];

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
                'progress' => $progress,
            ]);
        } else {
            return redirect('/hei/ph/evaluation')->with('failed', 'This tool has been locked and can\'t be accessed.');
        }
    }


    public function conforme(Request $request) {
        $tool = EvaluationFormModel::where('id', $request->id)->first();

        if (!$tool) {
            redirect()->back()->with('failed', 'Failed to update conforme.');
        }

        $tool->update([
            'conforme' => $request->name,
            'conformeTitle' => $request->title,
        ]);

        $tool->save();

        redirect()->back()->with('success', 'Updated successfully.');
    }


    public function update(Request $request) {
        foreach ($request->items as $item) {

            if (in_array($item['id'], $request->rows)) {
                
                $evaluationItem = EvaluationItemModel::find($item['id']);

                if ($evaluationItem) {
                    $evaluationItem->update([
                        'actualSituation' => $item['actualSituation'],
                        'selfEvaluationStatus' => $item['selfEvaluationStatus'],
                    ]);
                }
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

        $formModel = EvaluationFormModel::where('id', $request->id)->with('institution_program.institution', 'institution_program.program')->first();
        $institution = $formModel->institution_program->institution->name;
        $program = $formModel->institution_program->program->program;
        $acadYear = $formModel->effectivity;

        if($request->hasFile('file')) {
            $evidenceFile = $request->file('file');
            $itemId = $request->itemId;
            $toolId = $request->id;
            $filename = $acadYear.'/'.$institution.'/'.$program.'/'.$itemId.'/'.$evidenceFile->getClientOriginalName();
            $user = Auth::user();
            $effectivity = EvaluationFormModel::where('id', $toolId)->value('effectivity');

            $exist = EvidenceModel::where('text', $filename)->first();

            if ($exist) {
                return redirect()->back()->with('failed', 'File already exist.');
            }

            try {
                $storage = Firebase::storage();

                if(!$storage) {
                    return redirect()->back()->with('failed', 'Failed to upload file.');
                }

                $bucket = $storage->getBucket();

                if(!$bucket) {
                    return redirect()->back()->with('failed', 'Failed to upload file.');
                }

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
            } catch (Throwable $thr) {
                return redirect()->back()->with('failed', 'Failed to upload file.');
            }
        }

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
        
        try {
            $evidenceItem = EvidenceModel::create([
                'itemId' => $validated['id'],
                'type' => "link",
                'text' => $validated['link'],
                'url' => $validated['link'],
            ]);

            return redirect()->back()->with('uploaded', 'Link successfully posted.');
        } catch (Throwable $thr) {
            return redirect()->back()->with('failed', 'Failed to post link.');
        }
    }


    public function deleteLink(Request $request) {

        $evidenceModel = EvidenceModel::where('id', $request->item)->first();

        if ($evidenceModel->type == 'file') {

            $storage = Firebase::storage();
            $bucket = $storage->getBucket();
            $file = $bucket->object($evidenceModel->text);

            if ($file->exists()) {
                $file->delete();
            }
        }

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

        return redirect('/hei/ph/evaluation')->with('success', "The evaluation tool has been successfully submitted.");
    }

    
    function generateKey($length = 10) {
        return Str::random($length);
    }
    

}
