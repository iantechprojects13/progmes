<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModel;
use App\Models\InstitutionProgramModel;
use App\Models\ProgramModel;
use Inertia\Inertia;
use Auth;

class EvaluationController extends Controller
{
    public function index() {

        $userRole = RoleModel::where('userId', Auth::user()->id)->value('role');

        if (
            $userRole == 'Super Admin' ||
            $userRole == 'Regional Director' ||
            $userRole == 'Chief Education Program Specialist' ||
            $userRole == 'Supervising Education Program Specialist'
        ) {
            return redirect()->route('evaluation.admin');
        } else if ($userRole == 'Education Supervisor') {
            return redirect()->route('evaluation.ched');
        } else {
            return redirect()->route('evaluation.hei');
        }




        if ($userType == 'CHED') {
            return redirect()->route('evaluation.ched');
        } else {
            return redirect()->route('evaluation.hei');
        }
    }

    public function EvaluationForAdmin() {
        $role = RoleModel::where('userId', Auth::user()->id)->value('role');

        return Inertia::render('Progmes/Evaluation/Evaluation-Admin', [
            'role' => $role,
        ]);
    }

    public function EvaluationForCHED() {
        $user = Auth::user();
        $role = RoleModel::where('userId', Auth::user()->id)->with('discipline')->get();

        if( $user->type == 'CHED' && $role[0]->role == 'Education Supervisor') {
            return Inertia::render('Progmes/Evaluation/Evaluation-CHED', [
                'role' => $role,
            ]);
        }
    }

    public function EvaluationForHEI() {
        $role = RoleModel::where('userId', Auth::user()->id)->with('institution', 'program', 'discipline')->first();
        $evaluation = [];

        if($role->institution && $role->program ) {
            $evaluation = InstitutionProgramModel::where([
                'institutionId' => $role->institution->id,
                'programId' => $role->program->id,
            ])->with('evaluationForm')->first();
            
            return Inertia::render('Progmes/Evaluation/Evaluation-HEI', [
                'role' => $role,
                'evaluation' => $evaluation,
            ]);
        }
        
        return Inertia::render('Progmes/Evaluation/Evaluation-HEI', [
            'role' => $role,
            'discipline' => $discipline,
            'program' => $program,
        ]);
    }
}
