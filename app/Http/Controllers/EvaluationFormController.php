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
        $institution_list = InstitutionModel::orderBy('name', 'asc')->get();
        $role = Auth::user()->role;
        $canEdit = false;
        $canDelete = false;
        $acadYear = '';
        $assignedProgramIds = [];

        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        $acadYear = getAcademicYear($request->query('ay'), Auth::user()->id);
        $programFilter = $request->query('program') != null ? $request->query('program') : null;
        $institutionFilter = $request->query('institution') != null ? $request->query('institution') : null;
        
        if ($role == 'Super Admin' || $role == 'Admin') {
            $canEdit = true;
            $canDelete = true;
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
            applyProgramAndInstitutionSearch($query, $request->query('search'), 'program', 'institution');
        })
        ->when($programFilter, function ($query) use ($programFilter) {
            $query->whereHas('program', function ($query) use ($programFilter) {
                $query->where('id', $programFilter);
            });
        })
        ->when($institutionFilter, function ($query) use ($institutionFilter) {
            $query->whereHas('institution', function ($query) use ($institutionFilter) {
                $query->where('id', $institutionFilter);
            });
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
            'institution_list' => $institution_list,
            'effectivity' => $acadYear,
            'academicYear' => $acadYear,
            'institutionProgramList' => $allinstitutionprograms,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'filters' => $request->only(['search', 'status']) + ['academicYear' => $acadYear, 'show' => $show, 'program' => $programFilter, 'institution' => $institutionFilter],
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
            foreach ($tool->item as $item) {
                $item->evidence()->delete();
                $item->delete();
            }
            $tool->delete();
            return redirect()->back()->with('success', 'Evaluation tool has been deleted successfully.');
        }

        return redirect()->back()->with('failed', 'Evaluation tool not found.');
    }
}
