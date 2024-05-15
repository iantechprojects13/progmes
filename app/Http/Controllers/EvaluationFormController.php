<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationFormModel;
use App\Models\EvaluationItemModel;
use App\Models\InstitutionProgramModel;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\CMOModel;
use App\Models\RoleModel;
use App\Models\CriteriaModel;
use App\Http\Requests\EvaluationFormRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\AdminSettingsModel;
use Auth;

class EvaluationFormController extends Controller
{
    public function index(Request $request) {

        $program_list = ProgramModel::with('active_cmo')->orderBy('program', 'asc')->get();
        $role = Auth::user()->role;
        $canEdit = false;
        $defaultAcademicYear = '';
        $disciplineList = [];

        $show = $request->query('show') ? $request->query('show') : 25;

        if ($request->query('ay')) {
            $defaultAcademicYear = $request->query('ay');
        } else {
            $defaultAcademicYear = AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        }
        
        if ($role == 'Super Admin' || $role == 'Admin') {
            $canEdit = true;
        }

        if ($role == 'Education Supervisor') {
            $canEdit = true;
            $discipline = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->get();
            foreach($discipline as $item) {
                array_push($disciplineList, $item->disciplineId);
            }
            $program_list = ProgramModel::query()
            ->whereIn('disciplineId', $disciplineList)
            ->orderBy('program', 'asc')
            ->with('active_cmo')->get();
        }

        $allinstitutionprograms = InstitutionProgramModel::query()
        ->when($request->query('search'), function ($query) use ($request, $defaultAcademicYear) {
            $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->query('search') . '%')
                ->orWhereHas('program', function ($programQuery) use ($request) {
                    $programQuery->where('program', 'like', '%' . $request->query('search') . '%');
                })
                ->orWhereHas('institution', function ($programQuery) use ($request) {
                    $programQuery->where('name', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->whereHas('evaluationForm', function ($query) use ($defaultAcademicYear) {
                $query->where('effectivity', $defaultAcademicYear);
            });
            
        })
        ->with(['program', 'institution', 'evaluationForm' => function ($evalFormQuery) use ($defaultAcademicYear) {
            $evalFormQuery->where('effectivity', $defaultAcademicYear);
        }, 'evaluationForm.cmo'])
        ->where('isActive', 1)
        ->whereHas('evaluationForm', function ($query) use ($defaultAcademicYear) {
            $query->where('effectivity', $defaultAcademicYear);
        })
        ->when($role == 'Education Supervisor', function ($query) use ($disciplineList) {
            $query->whereHas('evaluationForm', function ($q) use ($disciplineList) {
                $q->whereIn('disciplineId', $disciplineList);
            });
        })
        ->paginate($show)
        ->withQueryString();
        
        return Inertia::render('Progmes/Admin/EvaluationForm', [
            'program_list' => $program_list,
            'effectivity' => $defaultAcademicYear,
            'academicYear' => $defaultAcademicYear,
            'institutionProgramList' => $allinstitutionprograms,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search']) + ['academicYear' => $defaultAcademicYear ]
            + ['show' => $show ],
        ]);
    }
    
    public function create(EvaluationFormRequest $request) {

        $criteria = CriteriaModel::where('cmoId', $validatedData['cmo'])->orderBy('itemNo', 'asc')->get();

        foreach($criteria as $item) {
            EvaluationItemModel::create([
                'evaluationFormId' => $evaluationForm->id,
                'criteriaId' => $item->id,
                'selfEvaluationStatus' => null,
                'evaluationStatus' => null,
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

        $institutionProgram = InstitutionProgramModel::query()
        ->where('programId', $validatedData['program']['id'])
        ->where('isActive', 1)
        ->whereNotExists(function ($query) use ($validatedData) {
            $query->select(DB::raw(1))
                ->from('evaluation_form')
                ->whereRaw('evaluation_form.institutionProgramId = institution_program.id')
                ->where('effectivity', $validatedData['effectivity']);
        })
        ->whereHas('institution', function ($query){
            $query->where('isActive', 1);
        })
        ->with('institution')
        ->get();

        $cmo = $validatedData['cmo']['id'];
        $discipline = $validatedData['program']['disciplineId'];
        
        foreach($institutionProgram as $item) {
            EvaluationFormModel::create([
                'institutionProgramId' => $item->id,
                'disciplineId' => $discipline,
                'cmoId' => $cmo,
                'effectivity' => $validatedData['effectivity'],
                'status' => 'Deployed',
            ]);
        }

        return redirect()->back()->with('success', 'Compliance evaluation tool for ' . $validatedData['program']['program'] . ' program has been deployed.');
    }

}
