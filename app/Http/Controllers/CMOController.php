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
        $disciplineList = [];

        if ($role == 'Super Admin' || $role == 'Education Supervisor') {
            $canImport = true;
            $canDraft = true;
            $canEdit = true;
        }

        if ($role == 'Admin') {
            $canEdit = true;
        }

        if ($role == 'Education Supervisor') {

            $discipline = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->get();
            foreach($discipline as $item) {
                array_push($disciplineList, $item->disciplineId);
            }
        }

        $activelist = CMOModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('number', 'like', '%' . $request->query('search') . '%')
                ->orWhere('series', 'like', '%' . $request->query('search') . '%')
                ->orWhere('version', 'like', '%' . $request->query('search') . '%')
                ->orWhereHas('program', function ($programQuery) use ($request) {
                    $programQuery->where('program', 'like', '%' . $request->query('search') . '%');
                });
            });
        })
        ->where([
            'status' => 'Published',
        ])
        ->orderBy('number', 'asc')
        ->when($role == 'Education Supervisor', function ($query) use ($disciplineList) {
            $query->whereHas('program', function ($q) use ($disciplineList) {
                $q->whereIn('disciplineId', $disciplineList);
            });
        })
        ->with('program')
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/CMO', [
            'cmo_list' => $activelist,
            'canImport' => $canImport,
            'canDraft' => $canDraft,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search']),
        ]);
    }

    public function draft(Request $request) {

        $role = Auth::user()->role;
        $canEdit = false;
        $disciplineList = [];

        if ($role == 'Super Admin' || $role == 'Education Supervisor') {
            $canEdit = true;
        }

        if ($role == 'Education Supervisor') {
            $discipline = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->get();
            foreach($discipline as $item) {
                array_push($disciplineList, $item->disciplineId);
            }
        }

        $draftlist = CMOModel::query()
        ->when($request->query('search'), function ($query) use ($request, $role) {
            $query->where(function ($query) use ($request, $role) {
                 $query->where('number', 'like', '%' . $request->query('search') . '%')
                ->orWhere('series', 'like', '%' . $request->query('search') . '%')
                ->orWhere('version', 'like', '%' . $request->query('search') . '%')
                ->orWhereHas('program', function ($programQuery) use ($request) {
                    $programQuery->where('program', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->when($role == 'Education Supervisor', function ($query) {
                $query->where('createdBy', Auth::user()->id);
            });
        })
        ->when($role == 'Education Supervisor', function ($query) {
            $query->where('createdBy', Auth::user()->id);
        })
        ->where([
            'status' => 'Draft',
        ])
        ->orderBy('number', 'asc')
        ->with('program', 'created_by')
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('Progmes/Admin/CMO-Draft', [
            'cmo_list' => $draftlist,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search']),
        ]);
    }


    public function create() {
        return Inertia::render('Progmes/Admin/CMO-Create');
    }

    public function view(CMOModel $cmo) {
        $cmoModel = CMOModel::where('id', $cmo->id)->with('discipline', 'program', 'criteria')->first();
        return Inertia::render('Progmes/Admin/CMO-View',[
            'cmo' => $cmoModel,
        ]);
    }

    public function edit(Request $request) {
        
        $user = Auth::user();
        $disciplineList = [];

        if ($user->role == 'Education Supervisor') {
            $disciplines = RoleModel::where('userId', $user->id)->with('discipline')->get();
            foreach($disciplines as $discipline) {
                array_push($disciplineList, $discipline->discipline);
            }
        } else {
            $disciplineList = DisciplineModel::all();
        }


        return Inertia::render('Progmes/Admin/CMO-Edit', [
            'cmo' => CMOModel::where('id', $request->id)->with('criteria')->first(),
            'discipline_list' => $disciplineList,
            'program_list' => ProgramModel::all(),
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
            return redirect()->back()->with('failed', 'Failed: Incomplete CMO details. Please provide required information to proceed.');
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
        $cmoModel = CMOModel::find($id);
        
        if (!$cmoModel) {
            return redirect()->back()->with('failed', 'Failed to delete CMO.');
        }

        $cmoModel->criteria()->delete();
        $cmoModel->delete();

        return redirect()->back()->with('success', 'CMO deleted successfully.');
    }
}
