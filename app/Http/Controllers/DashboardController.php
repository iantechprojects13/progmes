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
        $userType = Auth::user()->type;
        if ($userType == 'CHED') {
            return redirect()->route('dashboard.ched')->with('message', 'Sample Text, Sample Notification, Sample Text, Sample Notification, Sample Text, Sample Notification, Sample Text, Sample Notification.');
        } else {
            return redirect()->route('dashboard.hei');
        }
    }

    public function DashboardForCHED() {
        return Inertia::render('Progmes/Dashboard/Dashboard-CHED');
    }

    public function DashboardForHEI() {

        return Inertia::render('Progmes/Dashboard/Dashboard-HEI');

        // $role = $this->getHEIRole(Auth::user()->id);

        // if($role->institutionId && $role->programId) {
        //     $evaluationForm = $this->getEvaluationForm($role->institutionId, $role->programId);

        //     if($evaluationForm) {

        //         $complied = $evaluationForm->item->where('selfEvaluationStatus', 'Complied');
        //         $notComplied = $evaluationForm->item->where('selfEvaluationStatus', 'Not complied');

        //         $compliedPercentage = $this->getPercentage($complied->count(), $evaluationForm->item->count());
        //         $notCompliedPercentage = $this->getPercentage($notComplied->count(), $evaluationForm->item->count());

        //         return Inertia::render('Progmes/Dashboard/Dashboard-HEI', [
        //             'complied' => [$complied->count(), $notComplied->count()],
        //             'compliedPercentage' => $compliedPercentage,
        //             'notComplied' => [$notComplied->count(), $complied->count()],
        //             'notCompliedPercentage' => $notCompliedPercentage,
        //             'totalItem' => $evaluationForm->item->count(),
        //         ]);

        //     } else {
        //         // render dashboard with no data - no evaluation form yet
        //     }
        // }

        
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
