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
        ->orderBy('name')
        ->paginate($show)
        ->withQueryString();
        
        return Inertia::render('Admin/hei/HEI-List', [
            'institution_list' => $institutionlist,
            'canEdit' => $canEdit,
            'canAdd' => $canAdd,
            'canDelete' => $canDelete,
            'filters' => $request->only(['search']) + ['show' => $show ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/hei/HEI-Create', [
            'program_list' => ProgramModel::orderBy('program', 'asc')->orderBy('major', 'asc')->get(),
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
                'isActive' => 1,
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
        $institutionModel = InstitutionModel::query()->where('id', $institution->id)
        ->with('active_program.program')
        ->first();

        return Inertia::render('Admin/hei/HEI-View', [
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

        return Inertia::render('Admin/hei/HEI-Edit', [
            'institution' => $institutionModel,
            'programsOffered' => $programsOffered,
            'program_list' => ProgramModel::orderBy('program', 'asc')->orderBy('major', 'asc')->get(),
        ]);
    }

    public function update(Request $request) {

        $validated = $request->validate([
            'id' => 'required',
            'code' => 'required',
            'name' => 'required',
            'programs' => 'nullable',
        ], [
            'code.required' => 'The institutional code field is required.',
            'name.required' => 'The HEI name field is required.'
        ]);
        
        $institutionModel = InstitutionModel::where('id', $validated['id'])->with('institution_program')->first();

        $currentProgramList = [];

        if (!$institutionModel) {
            return redirect()->back()->with('failed', 'Update failed.');
        }

        foreach($institutionModel->institution_program as $program) {
            array_push($currentProgramList, $program->programId);
        }

        foreach($validated['programs'] as $program) {
            $updatedRow = InstitutionProgramModel::updateOrCreate(
                [
                    'institutionId' => $validated['id'],
                    'programId' => $program,
                ],
            [
                'institutionId' => $validated['id'],
                'programId' => $program,
                'isActive' => 1,
            ]);
        }
        
        foreach($currentProgramList as $program) {
            if(!in_array($program, $validated['programs'])) {
                $inactiveProgram = InstitutionProgramModel::where('institutionId', $validated['id'])
                ->where('programId', $program)->update([
                    'isActive' => 0,
                ]);
            }
        }

        return redirect('/admin/higher-education-institutions')->with('success', 'HEI updated successfully.');
    }

    public function delete($institution) {

        $institutionModel = InstitutionModel::where( 'id', $institution)->with('institution_program', 'institution_program.evaluationForm', 'role')->first();
        
        if (!$institutionModel) {
            return redirect()->back()->with('failed', 'HEI not found.');
        }


        $canBeDeleted = true;

        foreach ($institutionModel->institution_program as $program) {
            if (count($program->evaluationForm) != 0) {
                $canBeDeleted = false;
                break;
            }
        }

        foreach ($institutionModel->role as $role) {
            if ($role != null) {
                $canBeDeleted = false;
                break;
            }
        }

        if ($canBeDeleted) {

        $institutionModel->institution_program()->delete();
        $institutionModel->delete();
            
            return redirect()->back()->with('success', 'HEI deleted successfully.');
        }
        

        return redirect()->back()->with('failed', 'This record cannot be deleted because it is associated with another records.');
        
    }

}
