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

        $institutionlist = InstitutionModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->query('search') . '%');
        })
        ->paginate($show)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/HEI', [
            'institution_list' => $institutionlist,
            'canEdit' => $canEdit,
            'canAdd' => $canAdd,
            'canDelete' => $canDelete,
            'filters' => $request->only(['search']) + ['show' => $show ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Progmes/Admin/HEI-Create', [
            'program_list' => ProgramModel::orderBy('program', 'asc')->orderBy('program', 'asc')->get(),
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
        $institutionModel = InstitutionModel::where('id', $institution->id)->with('institution_program.program')->first();

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
            'program_list' => ProgramModel::orderBy('program', 'asc')->orderBy('program', 'asc')->get(),
        ]);
    }

    public function update(InstitutionValidationRequest $request) {
        $validatedData = $request->validated();

        $institutionModel = InstitutionModel::where('id', $validatedData['id'])->with('institution_program')->first();

        $currentProgramList = [];

        if (!$institutionModel) {
            return redirect()->back()->with('failed', 'Update failed.');
        }

        foreach($institutionModel->institution_program as $program) {
            array_push($currentProgramList, $program->programId);
        }

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

        foreach($currentProgramList as $program) {
            if(!in_array($program, $validatedData['programs'])) {
                $inactiveProgram = InstitutionProgramModel::where('institutionId', $validatedData['id'])
                ->where('programId', $program)->update([
                    'isActive' => 0,
                ]);
            }
        }

        return redirect('/admin/higher-education-institutions')->with('success', 'HEI updated successfully.');
    }

    public function delete($institution) {
        $institutionModel = InstitutionModel::find($institution);

        if(!$institutionModel) {
            return redirect()->back()->with('failed', 'Failed to delete HEI.');
        }

        $institutionModel->institution_program()->delete();
        $institutionModel->delete();

        return redirect()->back()->with('success', 'HEI deleted successfully.');
    }
}
