<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProgramModel;
use App\Models\DisciplineModel;
use App\Http\Requests\ProgramRequest;
use Auth;

class ProgramController extends Controller
{
    public function index(Request $request)
    {

        $role = Auth::user()->role;
        $canEdit = false;
        $canDelete = false;
        $canAdd = false;
        
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);

        if ($role == 'Super Admin') {
            $canEdit = true;
            $canDelete = true;
            $canAdd = true;
        }

        if ($role == 'Admin') {
            $canEdit = true;
            $canAdd = true;
        }

        $programlist = ProgramModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where('program', 'like', '%' . $request->query('search') . '%')
            ->orWhere('major', 'like', '%' . $request->query('search') . '%')
            ->orWhereHas('discipline', function ($programQuery) use ($request) {
                $programQuery->where('discipline', 'like', '%' . $request->query('search') . '%');
            });
        })
        ->with('discipline')
        ->orderBy('program')
        ->paginate($show)
        ->withQueryString();

        return Inertia::render('Admin/program/Program-List', [
            'program_list' => $programlist,
            'canEdit' => $canEdit,
            'canAdd' => $canAdd,
            'canDelete' => $canDelete,
            'filters' => $request->only(['search']) + ['show' => $show ],
        ]);
    }

    public function create() {
        return Inertia::render('Admin/program/Program-Create', [
            'discipline_list' => DisciplineModel::orderBy('discipline', 'asc')->get(),
        ]);
    }

    public function store(ProgramRequest $request) {
        $validatedData = $request->validated();
        $programExist = false;

        $programExist = ProgramModel::where('program', $validatedData['program'])
        ->where('major', $validatedData['major'] ? $validatedData['major'] : null)
        ->exists();

        if ($programExist) {
            return redirect()->back()->with('failed', 'This program has already been registered.');
        }

        ProgramModel::create([
            'disciplineId' => $validatedData['discipline'],
            'program' => $validatedData['program'],
            'major' => $validatedData['major'],
        ]);

        return redirect()->route('admin.program.list')->with('success', 'Program successfully added.');

    }

    public function edit($program) {
        
        $programModel = ProgramModel::find($program);

        if($programModel) {
            return Inertia::render('Admin/program/Program-Edit', [
                'discipline_list' => DisciplineModel::all(),
                'program' => $programModel,
            ]);
        }

        return redirect('/admin/program')->with('failed', 'No program found.');
    }

    public function update(ProgramRequest $request) {
        
        $validatedData = $request->validated();

        $programModel = ProgramModel::find($request->id);


        $programExist = ProgramModel::where('id', '<>', $request->id)->where('program', $validatedData['program'])
        ->where('major', $validatedData['major'] ? $validatedData['major'] : null)
        ->exists();

        if ($programExist) {
            return redirect()->back()->with('failed', 'This program has already been registered.');
        }

        $programModel->update([
            'disciplineId' => $validatedData['discipline'],
            'program' => $validatedData['program'],
            'major' => $validatedData['major'],
        ]);

        $programModel->save();

        return redirect()->route('admin.program.list')->with('success', 'Program successfully updated.');
    }

    public function delete($program) {

        $programModel = ProgramModel::where( 'id', $program)
        ->with('institutionProgram', 'institutionProgram.evaluationForm', 'role', 'cmo')
        ->first();


        if (!$programModel) {
            return redirect()->back()->with('failed', 'Program not found.');
        }

        $canBeDeleted = true;

        foreach ($programModel->institutionProgram as $program) {
            if ($program->isActive == 1) {
                $canBeDeleted = false;
                break;
            }

            if (count($program->evaluationForm) != 0) {
                $canBeDeleted = false;
                break;
            }
        }

        foreach ($programModel->role as $role) {
            if ($role != null) {
                $canBeDeleted = false;
                break;
            }
        }

        foreach ($programModel->cmo as $cmo) {
            if ($cmo != null) {
                $canBeDeleted = false;
                break;
            }
        }

        if ($canBeDeleted) {
            $programModel->delete();
            
            return redirect()->back()->with('success', 'Program successfully deleted.');
        }
        
        return redirect()->back()->with('failed', 'This record cannot be deleted because it is associated with another records.');
        
    }

}
