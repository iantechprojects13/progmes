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

        if ($role == 'Super Admin') {
            $canEdit = true;
        }

        $programlist = ProgramModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where('program', 'like', '%' . $request->query('search') . '%');
        })
        ->when($request->query('sort'), function ($query) use ($request) {
            $query->orderBy($request->query('sort'), 'asc');
        })
        ->with('discipline')
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/Program', [
            'program_list' => $programlist,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search', 'sort', 'type']),
        ]);

        // return Inertia::render('Progmes/Admin/Program',[
        //     'program_list' => ProgramModel::orderBy('program', 'asc')->paginate(20)
        //         ->through(fn($program) => [
        //             'id' => $program->id,
        //             'discipline' => DisciplineModel::where('id', $program->disciplineId)->value('discipline'),
        //             'program' => $program->program,
        //             'major' => $program->major,
        //         ]),
        //     ]);
    }

    public function create() {
        return Inertia::render('Progmes/Admin/Program-Create', [
            'discipline_list' => DisciplineModel::all(),
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
        return Inertia::render('Progmes/Admin/Program-Edit', [
            'discipline_list' => DisciplineModel::all(),
            'program' => $programModel,
        ]);
    }

    public function update(ProgramRequest $request) {
        $programModel = ProgramModel::find($request->id);

        $validatedData = $request->validated();

        $programModel->update([
            'disciplineId' => $validatedData['discipline'],
            'program' => $validatedData['program'],
            'major' => $validatedData['major'],
        ]);

        $programModel->save();

        return redirect()->route('admin.program.list')->with('success', 'Program successfully edited.');
    }
    

    public function addDiscipline(Request $request) {
        $validated = $request->validate([
            'value' => 'required',
        ]);

        DisciplineModel::create([
            'discipline' => $validated['value'],
        ]);

        return redirect()->back()->with('success', $validated['value'] .' has been added to the discipline list.');
    }


    public function deleteDiscipline(Request $request) {

        DisciplineModel::destroy($request->id);

        return redirect()->back()->with('success', 'Deleted successfully.');
    }
}
