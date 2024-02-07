<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProgramModel;
use App\Models\DisciplineModel;

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
