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
        $userType = Auth::user()->type;
        if ($userType == 'CHED') {
            return redirect()->route('evaluation.ched');
        } else {
            return redirect()->route('evaluation.hei');
        }
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
