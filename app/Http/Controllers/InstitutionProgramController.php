<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InstitutionProgramModel;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\CMOModel;
use App\Models\AdminSettingsModel;
use Auth;

class InstitutionProgramController extends Controller
{
    public function index($academicYear = null) {
        $effectivity = $academicYear ? $academicYear : AdminSettingsModel::where('userId', 201)->value('currentAcademicYear');
        $program_list = ProgramModel::with('active_cmo')->get();


        return Inertia::render('Progmes/Admin/EvaluationForm',[
            'list' => InstitutionProgramModel::with('institution', 'program', 'evaluationForm')
            ->paginate(50)
            ->through(fn($item) => [
                'id' => $item->id,
                'institution' => $item->institution,
                'program' => $item->program,
                'evaluation' => $item->evaluationForm()->where('effectivity', $effectivity)->with('item')->first(),
            ]),
            'effectivity' => $effectivity,
            'program_list' => $program_list,
        ]);
    }
    
    public function create() {   
        return redirect('/admin/form');
    }
}
