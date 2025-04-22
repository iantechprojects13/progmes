<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EvaluationFormModel;
use App\Models\RoleModel;
use App\Models\EvaluationItemModel;
use App\Models\CMOModel;
use App\Models\InstitutionProgramModel;
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
use Carbon\Carbon;


class HEIFormController extends Controller
{
    public function store(Request $request) {

        $tool = EvaluationFormModel::where('id', $request->id)->with('item')->first();

        if ($tool->item->isNotEmpty()) {
            return redirect('/hei/evaluation/'. $request->id . '/edit');
        }

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
        $user = Auth::user();
        $evaluationTool = EvaluationFormModel::where('id', $tool)->with('institution_program.program', 'institution_program.institution', 'complied', 'not_complied', 'not_applicable', 'item', 'item.criteria', 'item.evidence')->first();
        $showEvaluation = false;

        $role = RoleModel::where('userId', $user->id)->where('isActive', 1)->with('program')->first();

        if ($role->role == 'Program Coordinator' || $role->role == 'Program Head') {
            $institutionProgram = InstitutionProgramModel::where('institutionId', $role->institutionId)->where('programId', $role->programId)->first();
        }

        if ($role->role == 'Program Head') {
            if (!($role->program->program == $evaluationTool->institution_program->program->program));
        } else if ($role->role == 'Program Coordinator') {
            if (!($evaluationTool->institutionProgramId == $institutionProgram->id)) {
                return redirect('/unauthorized');
            }
        } else if ($role->role == 'Vice-President for Academic Affairs') {
            $institutionProgram = InstitutionProgramModel::where('institutionId', $role->institutionId)->get();
            $institutionProgramIds = $institutionProgram->pluck('id')->toArray();

            if (!in_array($evaluationTool->institutionProgramId, $institutionProgramIds)) {
                return redirect('/unauthorized');
            }
        } else if ($role->role == 'Dean') {
            if ($role->disciplineId != $evaluationTool->disciplineId) {
                return redirect('/unauthorized');
            } else if ($evaluationTool->institution_program->institution->id != $role->institutionId) {
                return redirect('/unauthorized');
            }
        } else {
            return redirect('/unauthorized');
        }


        if ($evaluationTool->status != 'In progress' || $evaluationTool->evaluationDate != null) {
            $showEvaluation = true;
        }
        
        $compliedCount = $evaluationTool->complied->count();
        $notCompliedCount = $evaluationTool->not_complied->count();
        $notApplicableCount = $evaluationTool->not_applicable->count();
        $percentage = intval(round((($compliedCount + $notCompliedCount + $notApplicableCount)/$evaluationTool->item->count())*100));
        $progress = [$compliedCount, $notCompliedCount, $notApplicableCount, $percentage];
        

        return Inertia::render('Evaluation/HEI-Evaluation-View', [
            'evaluation_tool' => $evaluationTool,
            'progress' => $progress,
            'showEvaluation' => $showEvaluation,
        ]);
    }


    public function edit($evaluation, Request $request) {
        $user = Auth::user();
        $tool = EvaluationFormModel::where('id', $evaluation)->with('institution_program.institution', 'institution_program.program')->first();
        $canEdit = false;
        $redirectPath = '';

        $role = RoleModel::where('userId', $user->id)->where('isActive', 1)->with('program')->first();

        if ($role) {
            $institutionProgram = InstitutionProgramModel::where('institutionId', $role->institutionId)->where('programId', $role->programId)->first();
        } else {
            return redirect('/unauthorized');
        }

        if ($role->role == 'Quality Assurance Officer')
        {
            $canEdit = $tool->institution_program->institution->id == $role->institutionId;
            $redirectPath = '/hei/evaluation';
        }
        
        else if ($role->role == 'Program Coordinator')
        {
            $canEdit = $tool->institutionProgramId == $institutionProgram->id;
            $redirectPath = '/hei/pc/evaluation';
        }
        
        else if ($role->role == 'Program Head')
        {
            $canEdit = $role->program->program == $tool->institution_program->program->program;
            $redirectPath = '/hei/ph/evaluation';
        }

        if (!$canEdit) {
            return redirect('/unauthorized');
        } else {
            $items = EvaluationItemModel::where('evaluationFormId', $evaluation)->with('criteria', 'evidence')->get();

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

            

            $totalItems = $items->count();
            $complied = $items->where('selfEvaluationStatus', 'Complied')->count();
            $notComplied = $items->where('selfEvaluationStatus', 'Not complied')->count();
            $notApplicable = $items->where('selfEvaluationStatus', 'Not applicable')->count();
            $totalItems = $totalItems - $notApplicable;
            $percentage = intval(round((($complied + $notComplied + $notApplicable)/$items->count())*100));
            $progress = [ $complied, $notComplied, $notApplicable, $percentage];

            if($tool->status == 'In progress') {
                return Inertia::render('Evaluation/HEI-Evaluation-Edit', [
                    'evaluation' => $tool,
                    'items' => $complianceTool,
                    'progress' => $progress,
                    'filters' => $request->only(['search', 'selfEvaluationStatus', 'evaluationStatus']),
                ]);
            } else {
                return redirect($redirectPath)->with('failed', 'This tool has been locked and can\'t be accessed.');
            }
        }
        
    }
    

    public function update(Request $request) {
        
        foreach ($request->items as $item) {

            $evaluationItem = EvaluationItemModel::find($item['id']);

            if ($evaluationItem) {
                $evaluationItem->update([
                    'actualSituation' => $item['actualSituation'] ?? null,
                    'selfEvaluationStatus' => $item['selfEvaluationStatus'] ?? null,
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'All changes saved.');
    }

    public function upload(Request $request) {

        if ($request->rows) {
            foreach ($request->items['data'] as $item) {
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
        }


        $request->validate([
            'id' => 'required',
            'itemId' => 'required',
            'file' => 'required|file|max:10240|mimes:xlsx,csv,pdf,doc,docx,png,jpeg,jpg',
        ], [
            'file.required' => 'Please select a file.',
            'file.max' => 'The file may not be greater than 10MB.',
            'file.mimes' => 'The file must be a type of: Excel, CSV, PDF, Word, PNG, JPEG, JPG.',
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

        if ($request->rows) {
            foreach ($request->items['data'] as $item) {
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
        }

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

        return redirect()->back()->with('deleted', 'Link deleted successfully.');
    }


    public function completed(Request $request) {
        $user = Auth::user();
        $redirectPath = '';

        if($user->role == 'Program Head') {
            $redirectPath = '/hei/ph/evaluation';
        } else if ($user->role == 'Program Coordinator') {
            $redirectPath = '/hei/pc/evaluation';
        } else {
            return redirect()->back()->with('failed', "You are not authorized to submit this tool.");
        }

        $evaluation = EvaluationFormModel::find($request->id);
        
        if ($evaluation->evaluationDate != null) {
            $evaluation->update([
                'status' => 'For re-evaluation',
                'submissionDate' => now(),
            ]);

        } else {
            $evaluation->update([
                'status' => 'Submitted',
                'submissionDate' => now(),
            ]);
        }

        
        $evaluation->save();

        return redirect($redirectPath)->with('success', "The evaluation tool has been successfully submitted.");
    }

    
    function generateKey($length = 10) {
        return Str::random($length);
    }
    

}
