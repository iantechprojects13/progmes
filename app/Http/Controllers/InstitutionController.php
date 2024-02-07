<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\InstitutionProgramModel;
use Inertia\Inertia;
use App\Http\Requests\InstitutionValidationRequest;

class InstitutionController extends Controller
{

    public function index()
    {
        return Inertia::render('Progmes/Admin/HEI',[
            'institution_list' => InstitutionModel::orderBy('name', 'asc')->get(),
            ]);
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
        $programs = $request->programs;

        if ($validatedData) {
            $institution = InstitutionModel::create([
                'code' => $validatedData['code'],
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'cityOrMunicipality' => $validatedData['cityOrMunicipality'],
                'cityOrProvince' => $validatedData['cityOrProvince'],
                'zipCode' => $validatedData['zip'],
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
        $row = InstitutionModel::where('id', $institution->id)->first();

        return Inertia::render('Progmes/Admin/HEI-View', [
            'institution' => $row,
            'canEdit' => false,
        ]);
        
    }

    
}
