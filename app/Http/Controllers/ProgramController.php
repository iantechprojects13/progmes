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
        ->with('discipline')
        ->orderBy('program');

        $count = $programlist->count();

        $programlist = $programlist
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/Program', [
            'program_list' => $programlist,
            'count' => $count,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search']),
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

    public function delete(Request $request) {

        $program = ProgramModel::where('id', $request->id)->first();

        if($program) {
            ProgramModel::destroy($request->id);
            return redirect()->back()->with('success', 'Deleted successfully.');
        }

        return redirect()->back()->with('failed', 'Failed to delete program.');

        
    }

    
}
