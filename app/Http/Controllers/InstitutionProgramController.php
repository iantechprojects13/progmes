<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InstitutionProgramModel;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\AdminSettingsModel;
use Auth;

class InstitutionProgramController extends Controller
{
    public function index(Request $request) {

        $effectivity = $request->effectivity ? $request->effectivity : AdminSettingsModel::where('userId', Auth::user()->id)->value('currentAcademicYear');

        return Inertia::render('Progmes/Admin/EvaluationForm',[
            'list' => InstitutionProgramModel::with('institution', 'program', 'program.cmo', 'evaluationForm', 'evaluationForm.cmo')
            ->paginate(8)
            ->through(fn($item) => [
                'id' => $item->id,
                'institution' => $item->institution,
                'program' => $item->program,
                'evaluation' => $item->evaluationForm->where('effectivity', $effectivity)->first(),
            ]),
            'effectivity' => $effectivity,
        ]);
    }
    
    public function create() {   
        return redirect('/admin/form');
    }
}
