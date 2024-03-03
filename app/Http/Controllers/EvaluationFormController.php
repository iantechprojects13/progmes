<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationFormModel;
use App\Models\EvaluationItemModel;
use App\Models\InstitutionProgramModel;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\CMOModel;
use App\Models\CriteriaModel;
use App\Http\Requests\EvaluationFormRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class EvaluationFormController extends Controller
{
    // public function index() {
    //     return Inertia::render('Progmes/Admin/EvaluationForm-Create', [
    //         'list' => EvaluationFormModel::where('effectivity', '2023-2024')->with('institution_program', 'cmo', 'institution_program.institution', 'institution_program.program')
    //         ->paginate(10)
    //         ->through(fn($item) => [
    //             'id' => $item->id,
    //             'cmo' => $item->cmo,
    //             'institutionProgram' => $item->institution_program,
    //         ]),
    //     ]);
    // }

    public function create(EvaluationFormRequest $request) {
        // $validatedData = $request->validated();

        // if ($validatedData) {
        //     $evaluationForm = EvaluationFormModel::create([
        //         'institutionProgramId' => $validatedData['institutionProgram'],
        //         'cmoId' => $validatedData['cmo'],
        //         'effectivity' => $validatedData['effectivity'],
        //     ]);
        // }

        $criteria = CriteriaModel::where('cmoId', $validatedData['cmo'])->orderBy('itemNo', 'asc')->get();


        foreach($criteria as $item) {
            EvaluationItemModel::create([
                'evaluationFormId' => $evaluationForm->id,
                'criteriaId' => $item->id,
                'selfEvaluationStatus' => 'Not Complied',
                'evaluationStatus' => 'Not Complied',
            ]);
        }


        return redirect()->route('admin.form.view', [
            'evaluation' => $evaluationForm,
            'criteria' => CriteriaModel::where('evaluationFormId',  $evaluationForm->id),
        ]);
    }

    public function view(EvaluationFormModel $evaluation) {
        $evaluationForm = EvaluationFormModel::where('id', $evaluation->id)->first();
        return Inertia::render('Progmes/Admin/EvaluationForm-View', [
            'evaluationForm' => $evaluationForm,
            'items' => EvaluationItemModel::where('evaluationFormId',  $evaluation->id)->with('criteria')->get(),
        ]);
    }

    public function deploy(Request $request) {
        $validatedData = $request->validate([
            'program' => 'required',
            'cmo' => 'required',
            'effectivity' => 'required',
        ], [
            'cmo.required' => 'Active CMO is required.',
        ]);

        $institutionProgram = InstitutionProgramModel::where('programId', $validatedData['program']['id'])
        ->whereNotExists(function ($query) use ($validatedData) {
            $query->select(DB::raw(1))
                ->from('evaluation_form')
                ->whereRaw('evaluation_form.institutionProgramId = institution_program.id')
                ->where('effectivity', $validatedData['effectivity']);
        })->get();

        $cmo = $validatedData['cmo']['id'];
        $discipline = $validatedData['program']['disciplineId'];

        foreach($institutionProgram as $item) {
            EvaluationFormModel::create([
                'institutionProgramId' => $item->id,
                'disciplineId' => $discipline,
                'cmoId' => $cmo,
                'effectivity' => $validatedData['effectivity'],
                'status' => 'deployed',
            ]);
        }

        return redirect()->back()->with('success', 'Compliant tool for ' . $validatedData['program']['program'] . ' programs has been deployed.');
    }

}
