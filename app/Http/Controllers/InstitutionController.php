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
                'address' => $validatedData['address'],
                'cityOrMunicipality' => $validatedData['cityOrMunicipality'],
                'cityOrProvince' => $validatedData['cityOrProvince'],
                'zipCode' => $validatedData['zipCode'],
            ]);
        }
        if ($programs) {
            foreach ($programs as $id) {
                InstitutionProgramModel::create([
                    'institutionId' => $institution->id,
                    'programId' => $id,
                ]);
            }
        }

        return redirect()->route('admin.hei.list')->with('success', $validatedData['name'].' has been registered successfully.');
    }

    public function view(InstitutionModel $institution)
    {
        $row = InstitutionModel::where('id', $institution->id)->with('institutionProgram.program')->first();

        return Inertia::render('Progmes/Admin/HEI-View', [
            'institution' => $row,
        ]);
        
    }

    public function edit(InstitutionModel $institution) {
        return Inertia::render('Progmes/Admin/HEI-Edit', [
            'institution' => InstitutionModel::where('id', $institution->id)->with('institutionProgram.program')->first(),
            'program_list' => ProgramModel::all(),
        ]);
    }

    public function update(InstitutionValidationRequest $request) {
        $validatedData = $request->validated();
    }

    
}
