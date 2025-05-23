<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RoleModel;
use App\Models\InstitutionModel;
use App\Models\LibCriteriaModel;
use App\Models\LibEvaluationFormModel;
use App\Models\LibEvaluationItemModel;
use App\Models\LibEvidenceModel;
use App\Models\AdminSettingsModel;
use Auth;

class HEILibraryEvaluationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = RoleModel::where('userId', $user->id)->where('isActive', 1)->with('institution')->first();
        $canEdit = false;

        if ($user->role == 'Librarian') {
            $canEdit = true;
        }

        $institution = InstitutionModel::where('id', $role->institution->id)->with('evaluationForm',)->first();
        
        return Inertia::render('Evaluation/HEI-Evaluation-Library-List', [
            'institution' => $institution,
            'canEdit' => $canEdit,
        ]);
    }

    public function store(Request $request) {
        $tool = LibEvaluationFormModel::find($request->id);
        $cmo_items = LibCriteriaModel::where('isActive', 1)->orderBy('no', 'asc')->get();

        foreach($cmo_items as $item) {
            LibEvaluationItemModel::create([
                'libEvaluationFormId' => $tool->id,
                'libCriteriaId' => $item->id,
                'selfEvaluationStatus' => null,
                'evaluationStatus' => null,
            ]);
        }

        $tool->update([
            'status' => 'In progress'
        ]);

        $tool->save();
        
        return redirect('/hei/library/'. $request->id . '/edit');
    }

    public function edit($evaluation, Request $request) {
        
        $user = Auth::user();
        $tool = LibEvaluationFormModel::where('id', $evaluation)->with('institution')->first();

        if(!$tool) {
            return redirect('/hei/library/evaluation')->with('failed', 'Evaluation tool not found.');
        }

        if ($tool->status != 'In progress') {
            return redirect('/hei/library/evaluation')->with('failed', 'Evaluation tool can\'t be edited.');
        }

        $role = RoleModel::where('userId', $user->id)->where('isActive', 1)->first();

        if ($role) {
            $institution = InstitutionModel::where('id', $role->institutionId)->first();
        } else {
            return redirect('/unauthorized');
        }
        
        if (!($tool->institution->id == $institution->id)) {
            return redirect('/unauthorized');
        } else {

            $complianceTool = LibEvaluationItemModel::query()
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
            ->where('libEvaluationFormId', $evaluation)
            ->with('criteria', 'evidence')
            ->paginate(1000)
            ->withQueryString();

            if($tool->status == 'In progress') {
                return Inertia::render('Evaluation/HEI-Evaluation-Library-Edit', [
                    'evaluation' => $tool,
                    'items' => $complianceTool,
                    'cmo' => AdminSettingsModel::where('setting_key', 'library_cmo')->value('setting_value'),
                    'filters' => $request->only(['search', 'selfEvaluationStatus', 'evaluationStatus']),
                ]);
            } else {
                return redirect('/hei/ph/evaluation')->with('failed', 'This tool has been locked and can\'t be accessed.');
            }
        }
        
    }

    public function update(Request $request) {
        // Get all items
        $allItems = $request->items;
        
        // Get the IDs of rows that were updated
        $updatedRowIds = collect($request->rows)->pluck('id')->toArray();
        
        // Only update the items that are in the updatedRowIds array
        foreach ($allItems as $item) {
            if (in_array($item['id'], $updatedRowIds)) {
                $evaluationItem = LibEvaluationItemModel::find($item['id']);
                
                if ($evaluationItem) {
                    $evaluationItem->update([
                        'actualSituation' => $item['actualSituation'] ?? null,
                        'selfEvaluationStatus' => $item['selfEvaluationStatus'] ?? null,
                    ]);
                }
            }
        }

        $libEvaluationTool = LibEvaluationFormModel::where('id', $request->id)->with('complied', 'not_complied', 'not_applicable', 'item')->first();
        $progress = getProgress($libEvaluationTool);
        return response()->json($progress);
    }

    public function submitLink(Request $request) {

        if ($request->rows) {
            foreach ($request->items['data'] as $item) {
                if (in_array($item['id'], $request->rows)) {
                    
                    $evaluationItem = LibEvaluationItemModel::find($item['id']);

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
            $evidenceItem = LibEvidenceModel::create([
                'itemId' => $validated['id'],
                'type' => "link",
                'text' => $validated['link'],
                'url' => $validated['link'],
            ]);

            return redirect()->back()->with('success', 'Link successfully posted.');
        } catch (Throwable $thr) {
            return redirect()->back()->with('failed', 'Failed to post link.');
        }
    }


    public function deleteLink(Request $request) {

        $evidenceModel = LibEvidenceModel::where('id', $request->item)->first();

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
        $evaluation = LibEvaluationFormModel::find($request->id);
        
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

        return redirect('/hei/library/evaluation')->with('success', "The evaluation tool has been successfully submitted.");
    }

    public function view($tool) {
        $user = Auth::user();
        $evaluationTool = LibEvaluationFormModel::where('id', $tool)->with('institution', 'complied', 'not_complied', 'not_applicable', 'item', 'item.criteria', 'item.evidence')->first();
        $showEvaluation = false;
        
        if (!$evaluationTool) {
            return redirect('/hei/library/evaluation')->with('failed', 'Evaluation tool not found.');
        }

        $role = RoleModel::where('userId', $user->id)->where('isActive', 1)->first();

        if ($role->role == 'Librarian' || $role->role == 'Vice-President for Academic Affairs') {
            $institution = InstitutionModel::where('id', $role->institutionId)->first();
            if (!($evaluationTool->institutionId == $institution->id)) {
                return redirect('/unauthorized');
            }
        }

        if ($evaluationTool->status != 'In progress' || $evaluationTool->evaluationDate != null) {
            $showEvaluation = true;
        }
        
        return Inertia::render('Evaluation/HEI-Evaluation-Library-View', [
            'evaluation' => $evaluationTool,
            'cmo' => AdminSettingsModel::where('setting_key', 'library_cmo')->value('setting_value'),
            'showEvaluation' => $showEvaluation,
        ]);
        
    }

    public function getToolProgress($id) {
        $evaluationTool = LibEvaluationFormModel::where('id', $id)->with('complied', 'not_complied', 'not_applicable', 'item')->first();
        $progress = getProgress($evaluationTool);
        return response()->json($progress);
    }
}
