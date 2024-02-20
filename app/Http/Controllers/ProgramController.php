<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProgramModel;
use App\Models\DisciplineModel;
use App\Http\Requests\ProgramRequest;

class ProgramController extends Controller
{
    public function index()
    {
        return Inertia::render('Progmes/Admin/Program',[
            'program_list' => ProgramModel::orderBy('program', 'asc')->paginate(20)
                ->through(fn($program) => [
                    'id' => $program->id,
                    'discipline' => DisciplineModel::where('id', $program->disciplineId)->value('discipline'),
                    'program' => $program->program,
                    'major' => $program->major,
                ]),
            ]);
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

    public function createDiscipline() {
        return Inertia::render('Progmes/Admin/Program-Create-Discipline');
    }

    public function storeDiscipline(Request $request) {
        $validated = $request->validate([
            'discipline' => 'required',
        ], [
            'discipline.required' => 'The discipline field is required.'
        ]);

        DisciplineModel::create([
            'discipline' => $validated['discipline'],
        ]);

        return redirect()->route('admin.program.list')->with('success', 'Discipline successfully added.');
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
