<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Inertia\Inertia;
use App\Models\CMOModel;
use App\Models\RoleModel;
use App\Models\CriteriaModel;
use App\Models\DisciplineModel;
use App\Models\ProgramModel;
use App\Models\EvaluationFormModel;
use App\Http\Requests\CMORequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

class CMOController extends Controller
{
    public function index(Request $request) {
        
        $role = Auth::user()->role;
        $canImport = false;
        $canDraft = false;
        $canEdit = false;
        $assignedProgramIds = [];
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        $sort = $request->query('sort');

        if ($role == 'Super Admin' || $role == 'Education Supervisor') {
            $canImport = true;
            $canDraft = true;
            $canEdit = true;
        }

        if ($role == 'Admin') {
            $canEdit = true;
        }

        if ($role == 'Education Supervisor') {
            $assignedProgramIds = getUserAssignedProgramIds(Auth::user()->id);
        }

        $activelist = CMOModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('number', 'like', '%' . $request->query('search') . '%')
                ->orWhere('series', 'like', '%' . $request->query('search') . '%')
                ->orWhere('version', 'like', '%' . $request->query('search') . '%');
                applyProgramSearch($query, $request->query('search'), 'program', true);
            });
        })
        ->when($role == 'Education Supervisor', supervisorAssignedPrograms('program', $assignedProgramIds))
        ->where('status', 'Published')
        ->when($request->query('isActive') != null, function ($query) use ($request) {
            $query->where('isActive', $request->query('isActive'));
        })
        ->orderBy('isActive', 'desc')
        ->orderBy('number', 'asc')
        ->orderBy('series', 'desc')
        ->with('program')
        ->paginate($show)
        ->withQueryString();

        return Inertia::render('Admin/cmo/CMO-List', [
            'cmo_list' => $activelist,
            'canImport' => $canImport,
            'canDraft' => $canDraft,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search', 'status', 'sort']) + ['show' => $show ],
        ]);
    }

    public function draft(Request $request)
    {
        $role = Auth::user()->role;
        $canEdit = false;
        $assignedProgramIds = [];
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        
        if ($role == 'Super Admin' || $role == 'Education Supervisor') {
            $canEdit = true;
        }
        
        if ($role == 'Education Supervisor') {
            $assignedProgramIds = getUserAssignedProgramIds(Auth::user()->id);
        }
        
        $draftlist = CMOModel::query()
            ->when($request->query('search'), function ($query) use ($request, $role) {
                $query->where(function ($query) use ($request) {
                    $query->where('number', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('series', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('version', 'like', '%' . $request->query('search') . '%');
                        applyProgramSearch($query, $request->query('search'), 'program', true);
                });
            })
            ->when($role == 'Education Supervisor', supervisorAssignedPrograms('program', $assignedProgramIds))
            ->where([
                'status' => 'Draft',
            ])
            ->orderBy('number', 'asc')
            ->with('program')
            ->paginate($show)
            ->withQueryString();
        
        return Inertia::render('Admin/cmo/CMO-Draft', [
            'cmo_list' => $draftlist,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search']) + ['show' => $show],
        ]);
    }

    public function view(CMOModel $cmo) {
        $cmoModel = CMOModel::where('id', $cmo->id)->with('discipline', 'program', 'criteria')->first();
        return Inertia::render('Admin/cmo/CMO-View',[
            'cmo' => $cmoModel,
        ]);
    }
    
    public function edit(Request $request) {
        $user = Auth::user();
        $cmo = CMOModel::where('id', $request->id)->with('criteria')->first();

        $evaluationFormExists = EvaluationFormModel::where('cmoId', $request->id)->exists();

        if ($cmo->status == 'Published' && $user->role != 'Super Admin') {
            return redirect('/admin/CMOs')->with('failed', 'This CMO can\'t be edited.');
        }
        
        $user = Auth::user();
        $disciplineList = [];
        $disciplineArray = [];

        if ($user->role == 'Education Supervisor') {
            $disciplines = RoleModel::where('userId', $user->id)->where('isActive', 1)->with('discipline')->get();
            foreach($disciplines as $discipline) {
                array_push($disciplineArray, $discipline->discipline->id);
            }
            $disciplineList = DisciplineModel::whereIn('id', $disciplineArray)->orderBy('discipline', 'asc')->get();
            $programList = ProgramModel::whereIn('disciplineId', $disciplineArray)->orderBy('program', 'asc')->get();

        } else {
            $disciplineList = DisciplineModel::orderBy('discipline')->get();
            $programList = ProgramModel::orderBy('program')->get();
        }

        return Inertia::render('Admin/cmo/CMO-Edit', [
            'cmo' => $cmo,
            'discipline_list' => $disciplineList,
            'program_list' => $programList,
            'hasTool' => $evaluationFormExists,
        ]);
    }

    public function saveAndPublish(CMORequest $request) {

        $validated = $request->validated();

        CMOModel::where('id', $request->id)->update([
            'disciplineId' => $validated['discipline'],
            'programId' => $validated['program'],
            'number' => $validated['number'],
            'series' => $validated['series'],
            'version' => $validated['version'],
            'status' => 'Published',
        ]);

        $criteria = CriteriaModel::where('cmoId', $request->id)->get();

        foreach ($criteria as $index => $item) {
            $criteriaModel = CriteriaModel::where('cmoId', $request->id)
                ->where('itemNo', $index + 1)
                ->first();

            if ($criteriaModel) {
                $criteriaModel->update([
                    'area' => $request->area[$index],
                    'minimumRequirement' => $request->minReq[$index],
                ]);
            }
        }


        return redirect()->route('admin.cmo.list')->with('success', 'CMO successfully published.');
    }

    // Updates the changes in the CMO edit
    public function update(Request $request) 
    {
        
        $cmo = CMOModel::find($request->id);

        
        if ($cmo) {
            $cmo->update([
                'disciplineId' => $request->discipline,
                'programId' => $request->program,
                'number' => $request->number,
                'series' => $request->series,
                'version' => $request->version,
                'status' => 'draft',
            ]);
        }

        
        $criteria = CriteriaModel::where('cmoId', $request->id)->get();

        foreach ($criteria as $index => $item) {
            $criteriaModel = CriteriaModel::where('cmoId', $request->id)
                ->where('itemNo', $index + 1)
                ->first();

            if ($criteriaModel) {
                $criteriaModel->update([
                    'area' => $request->area[$index],
                    'minimumRequirement' => $request->minReq[$index],
                ]);
            }
        }

        return redirect()->back()->with('success', 'All changes saved.');
    }



    public function saveAsDraft(Request $request) {

        CMOModel::where('id', $request->id)->update([
            'disciplineId' => $request->discipline,
            'programId' => $request->program,
            'number' => $request->number,
            'series' => $request->series,
            'version' => $request->version,
            'status' => 'draft',
        ]);

        $criteria = CriteriaModel::where('cmoId', $request->id)->get();

        foreach ($criteria as $index => $item) {
            $criteriaModel = CriteriaModel::where('cmoId', $request->id)
                ->where('itemNo', $index + 1)
                ->first();

            if ($criteriaModel) {
                $criteriaModel->update([
                    'area' => $request->area[$index],
                    'minimumRequirement' => $request->minReq[$index],
                ]);
            }
        }


        return redirect()->route('admin.cmo.draft')->with('success', 'CMO saved as draft.');

    }


    public function publish($cmo) {
        $cmoModel = CMOModel::find($cmo);

        if (!$cmoModel) {
            return redirect()->back()->with('failed', 'CMO not found.');
        }

        if (
            !$cmoModel->disciplineId ||
            !$cmoModel->programId ||
            !$cmoModel->number ||
            !$cmoModel->series ||
            !$cmoModel->version
        ) {
            return redirect()->back()->with('failed', 'Incomplete CMO details. Please provide required information to proceed.');
        }

        $cmoModel->update([
            'status' => 'Published',
        ]);

        $cmoModel->save();

        return redirect()->back()->with('success', 'CMO successfully published.');
    }

    public function activate($id) {
        $cmo = CMOModel::find($id);

        if (!$cmo) {
            return redirect()->back()->with('failed', "Failed to activate.");
        }

        $active = CMOModel::whereNotIn('id', [$cmo->id])
            ->where('programId', $cmo->programId)
            ->where('isActive', 1)
            ->first();

        if($active) {
            $active->update([
                'isActive' => 0,
            ]);
            $active->save();
        }

        $cmo->update([
            'isActive' => 1,
        ]);
        $cmo->save();
        
        return redirect()->back()->with('success', "CMO activation successful.");

        
    }

    public function deactivate($id) {
        $cmo = CMOModel::find($id);

        if (!$cmo) {
            return redirect()->back()->with('failed', "Failed to activate.");
        }

        $cmo->update([
            'isActive' => 0,
        ]);
        $cmo->save();
        
        return redirect()->back()->with('success', "CMO deactivation successful.");
    }

    public function destroy($id) {
        $cmoModel = CMOModel::where('id', $id)->with('evaluationForm')->first();

        if (!$cmoModel) {
            return redirect()->back()->with('failed', 'CMO not found.');
        }

        $canBeDeleted = true;

        foreach ($cmoModel->evaluationForm as $tool) {
            if ($tool != null) {
                $canBeDeleted = false;
                break;
            }
        }

        if ($canBeDeleted) {
            $cmoModel->criteria()->delete();
            $cmoModel->delete();
            
            return redirect()->back()->with('success', 'CMO deleted successfully.');
        }

        return redirect()->back()->with('failed', 'This record cannot be deleted because it is associated with another records.');
    }
}
