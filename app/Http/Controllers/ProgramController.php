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
        
        $show = $request->query('show') ? $request->query('show') : 25;

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
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/Program', [
            'program_list' => $programlist,
            'canEdit' => $canEdit,
            'canAdd' => $canAdd,
            'canDelete' => $canDelete,
            'filters' => $request->only(['search']) + ['show' => $show ],
        ]);
    }

    public function create() {
        return Inertia::render('Progmes/Admin/Program-Create', [
            'discipline_list' => DisciplineModel::orderBy('discipline', 'asc')->get(),
        ]);
    }

    public function store(ProgramRequest $request) {
        $validatedData = $request->validated();
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
            return Inertia::render('Progmes/Admin/Program-Edit', [
                'discipline_list' => DisciplineModel::all(),
                'program' => $programModel,
            ]);
        }

        return redirect('/admin/program')->with('failed', 'No program found.');
    }

    public function update(ProgramRequest $request) {
        
        $validatedData = $request->validated();

        $programModel = ProgramModel::find($request->id);

        $programModel->update([
            'disciplineId' => $validatedData['discipline'],
            'program' => $validatedData['program'],
            'major' => $validatedData['major'],
        ]);

        $programModel->save();

        return redirect()->route('admin.program.list')->with('success', 'Program successfully edited.');
    }

    public function delete($program) {

        $programModel = ProgramModel::find($program);

        if(!$programModel) {
            return redirect()->back()->with('failed', 'Failed to delete program.');
        }

        $programModel->institutionProgram()->delete();
        $programModel->delete();

        return redirect()->back()->with('success', 'Program successfully deleted.');
        
    }

    
}
