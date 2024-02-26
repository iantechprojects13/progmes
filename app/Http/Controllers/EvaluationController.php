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

    public function evaluationForProgramHead() {

        $role = RoleModel::where('userId', Auth::user()->id)->first();

        $institutionProgram = InstitutionProgramModel::where([
            'institutionId' => $role->institutionId,
            'programId' => $role->programId,
        ])->with('institution', 'program', 'evaluationForm')->first();

        return Inertia::render('Progmes/Evaluation/ProgramHead-Select', [
            'program' => $institutionProgram,
        ]);
    }
}
