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
        $acadYear = '';
        $assignedProgramIds = [];

        $show = sanitizePerPage($request->query('show'), Auth::user()->id);

        $acadYear = getAcademicYear($request->query('ay'), Auth::user()->id);
        
        if ($role == 'Super Admin' || $role == 'Admin') {
            $canEdit = true;
        }
        
        if ($role == 'Education Supervisor') {
            $canEdit = true;
            $assignedProgramIds = getUserAssignedProgramIds(Auth::user()->id);
            $program_list = ProgramModel::query()
            ->whereIn('id', $assignedProgramIds)
            ->orderBy('program', 'asc')
            ->with('active_cmo')->get();
        }

        $allinstitutionprograms = InstitutionProgramModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            // $query->where(function ($query) use ($request) {
            //     $query->where('id', 'like', '%' . $request->query('search') . '%')
            //     ->orWhereHas('program', function ($programQuery) use ($request) {
            //         $programQuery->where('program', 'like', '%' . $request->query('search') . '%');
            //     })
            //     ->orWhereHas('institution', function ($programQuery) use ($request) {
            //         $programQuery->where('name', 'like', '%' . $request->query('search') . '%');
            //     });
            // });
            applyProgramAndInstitutionSearch($query, $request->query('search'), 'program', 'institution');
        })
        ->with(['program', 'institution', 'evaluationForm' => function ($evalFormQuery) use ($acadYear) {
            $evalFormQuery->where('effectivity', $acadYear);
        }, 'evaluationForm.cmo'])
        ->where('isActive', 1)
        ->whereHas('evaluationForm', function ($query) use ($acadYear, $request) {
            $query->where('effectivity', $acadYear)
                ->when($request->query('status'), function ($query) use ($request) {
                    $query->where('status', $request->query('status'));
                });
        })
        ->when($role == 'Education Supervisor', supervisorAssignedPrograms('program', $assignedProgramIds))
        ->paginate($show)
        ->withQueryString();
        
        return Inertia::render('Admin/tool/ComplianceTool-List', [
            'program_list' => $program_list,
            'effectivity' => $acadYear,
            'academicYear' => $acadYear,
            'institutionProgramList' => $allinstitutionprograms,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search', 'status']) + ['academicYear' => $acadYear ]
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
        return Inertia::render('Admin/EvaluationForm-View', [
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

    public function destroy($evaluation)
    {
        $tool = EvaluationFormModel::find($evaluation);
        
        if ($tool) {
            $tool->delete();
            return redirect()->back()->with('success', 'Evaluation tool has been deleted successfully.');
        }

        return redirect()->back()->with('failed', 'Evaluation tool not found.');
    }
}
