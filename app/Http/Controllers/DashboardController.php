<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\AdminSettingsModel;
use App\Models\InstitutionProgramModel;
use App\Models\EvaluationFormModel;
use App\Models\EvaluationItemModel;
use App\Models\RoleModel;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $userRole = Auth::user()->role;

        if (
            $userRole == 'Super Admin' ||
            $userRole == 'Admin' ||
            $userRole == 'Regional Director' ||
            $userRole == 'Chief Education Program Specialist' ||
            $userRole == 'Supervising Education Program Specialist'
            ) {
                return redirect()->route('dashboard.admin');
        }
        else if ($userRole == 'Education Supervisor') {
            return Inertia::render('Progmes/Dashboard/CHED-ES-Dashboard');
        } else {
            return redirect()->route('dashboard.hei');
        }
    }

    public function DashboardForAdmin() {
        return Inertia::render('Progmes/Dashboard/Dashboard-Admin');
    }

    public function DashboardForCHED() {
        return Inertia::render('Progmes/Dashboard/Dashboard-CHED');
    }

    public function DashboardForHEI() {

        return Inertia::render('Progmes/Dashboard/Dashboard-HEI');
        
    }


    




    // functions

    public function getPercentage($int1, $int2) {
        $result = $int1 / $int2 * 100;
        $resultFormat = number_format($result, 2, '.', '');
        return $resultFormat;
    }

    public function getHEIRole($id) {
        $result = RoleModel::where('userId', $id)->first();
        return $result;
    }

    public function getEvaluationForm($institutionId, $programId) {
        $currentAcademicYear = AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        $institutionProgram = InstitutionProgramModel::where(['institutionId' => $institutionId, 'programId' => $programId])->first();
        $evaluationForm = EvaluationFormModel::where(['institutionProgramId' => $institutionProgram->id, 'effectivity' => $currentAcademicYear])->with('item')->first();

        return $evaluationForm;
    }

    // public function getEvaluationItems($id) {
    // }


}
