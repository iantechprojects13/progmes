<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModel;
use App\Models\InstitutionProgramModel;
use App\Models\EvaluationFormModel;
use App\Models\ProgramModel;
use Inertia\Inertia;
use Auth;

class EvaluationController extends Controller
{
    public function index() {
        $userRole = Auth::user()->role;
        if (
            $userRole == 'Super Admin' ||
            $userRole == 'Regional Director' ||
            $userRole == 'Chief Education Program Specialist' ||
            $userRole == 'Supervising Education Program Specialist'
        ) {
            return redirect()->route('evaluation.admin');
        } else if ($userRole == 'Education Supervisor') {
            return redirect()->route('evaluation.ched');
        } else if ($userRole == 'Program Head') {
            return redirect()->route('evaluation.hei.programhead');
        }
    }

    public function EvaluationForAdmin() {
        $role = Auth::user()->role;

        return Inertia::render('Progmes/Evaluation/CHED-Evaluation-Admin-Select', [
            'data' => "Hello World",
            'role' => $role,
        ]);
    }

    public function EvaluationForCHED() {
        $user = Auth::user();
        $disciplines = RoleModel::where('userId', Auth::user()->id)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();

        $tools = EvaluationFormModel::whereIn('disciplineId', $disciplineIds)->where('status', 'submitted')
            ->with('institution_program.program', 'institution_program.institution')
            ->get();

        if ($user->role == 'Education Supervisor') {
            return Inertia::render('Progmes/Evaluation/CHED-Evaluation-ES-Select', [
                'data' => "Hello World",
                'role' => $user->role,
                'disciplines' => $disciplines,
                'tools' => $tools,
            ]);
        }
    }




    public function evaluationForProgramHead() {

        $role = RoleModel::where('userId', Auth::user()->id)->first();
        
        $institutionProgram = InstitutionProgramModel::where([
            'institutionId' => $role->institutionId,
            'programId' => $role->programId,
        ])->with('institution', 'program', 'evaluationForm')->first();
        

        return Inertia::render('Progmes/Evaluation/HEI-Evaluation-PH-Select', [
            'program' => $institutionProgram,
        ]);
    }
}
