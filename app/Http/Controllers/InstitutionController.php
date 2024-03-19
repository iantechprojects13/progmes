<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\InstitutionProgramModel;
use Inertia\Inertia;
use App\Http\Requests\InstitutionValidationRequest;
use Auth;

class InstitutionController extends Controller
{

    // public function index()
    // {
    //     return Inertia::render('Progmes/Admin/HEI',[
    //         'institution_list' => InstitutionModel::orderBy('name', 'asc')->get(),
    //         ]);
    // }

    public function index(Request $request)
    {

        $role = Auth::user()->role;
        $canEdit = false;

        if ($role == 'Super Admin') {
            $canEdit = true;
        }

        $institutionlist = InstitutionModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->query('search') . '%');
        })
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/HEI', [
            'institution_list' => $institutionlist,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search']),
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

    public function create()
    {
        return Inertia::render('Progmes/Admin/HEI-Create', [
            'program_list' => ProgramModel::all(),
        ]);
    }


    public function store(InstitutionValidationRequest $request)
    {   
        $validatedData = $request->validated();
        $programs = $validatedData['programs'];

        if ($validatedData) {
            $institution = InstitutionModel::create([
                'code' => $validatedData['code'],
                'name' => $validatedData['name'],
            ]);
        }
        if ($programs) {
            foreach ($programs as $id) {
                InstitutionProgramModel::create([
                    'institutionId' => $institution->id,
                    'programId' => $id,
                    'isActive' => 1,
                ]);
            }
        }

        return redirect()->route('admin.hei.list')->with('success', $validatedData['name'].' has been registered successfully.');
    }

    public function view(InstitutionModel $institution)
    {
        $institutionModel = InstitutionModel::where('id', $institution->id)->with('institutionProgram.program')->first();

        return Inertia::render('Progmes/Admin/HEI-View', [
            'institution' => $institutionModel,
        ]);
        
    }

    public function edit(InstitutionModel $institution) {

        $institutionModel = InstitutionModel::where('id', $institution->id)->with('institution_program.program')->first();
        $programsOffered = [];

        foreach($institutionModel->institution_program as $program) {
            if($program->isActive == 1) {
                array_push($programsOffered, $program->programId);
            }
        }

        // dd($programsOffered);

        return Inertia::render('Progmes/Admin/HEI-Edit', [
            'institution' => $institutionModel,
            'programsOffered' => $programsOffered,
            'program_list' => ProgramModel::all(),
        ]);
    }

    public function update(InstitutionValidationRequest $request) {
        $validatedData = $request->validated();

        $institutionModel = InstitutionModel::where('id', $validatedData['id'])->with('institution_program')->first();

        $currentProgramList = [];

        foreach($institutionModel->institution_program as $program) {
            array_push($currentProgramList, $program->programId);
        }

        // dd($currentProgramList);

        foreach($validatedData['programs'] as $program) {
            $updatedRow = InstitutionProgramModel::updateOrCreate(
                [
                    'institutionId' => $validatedData['id'],
                    'programId' => $program,
                ],
            [
                'institutionId' => $validatedData['id'],
                'programId' => $program,
                'isActive' => 1,
            ]);
        }

        // $inactiveProg = [];

        foreach($currentProgramList as $program) {
            if(!in_array($program, $validatedData['programs'])) {
                $inactiveProgram = InstitutionProgramModel::where('institutionId', $validatedData['id'])
                ->where('programId', $program)->update([
                    'isActive' => 0,
                ]);
            }
        }

        
    }

    public function delete(Request $request) {

        $institution = InstitutionModel::find($request->id);

        if($institution) {

            $institution->institution_program()->delete();

            InstitutionModel::destroy($request->id);

            return redirect()->back()->with('success', 'Deleted successfully.');
        }

        return redirect()->back()->with('failed', 'Failed to delete program.');

        
    }

    
}
