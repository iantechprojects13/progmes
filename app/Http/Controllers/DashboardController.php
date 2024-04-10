<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AdminSettingsModel;
use App\Models\InstitutionProgramModel;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\DisciplineModel;
use App\Models\EvaluationFormModel;
use App\Models\EvaluationItemModel;
use App\Models\RoleModel;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $user = Auth::user();

        if ($user->type == 'CHED' && $user->role != 'Education Supervisor') {
                return redirect()->route('dashboard.admin.progress');
        }
        else if ($user->role == 'Education Supervisor') {
            return redirect()->route('dashboard.ched');
        } else {
            return redirect()->route('dashboard.hei');
        }
    }

    public function DashboardForAdminProgress(Request $request) {

        // dd($request->query('hei'));

        $acadYear = $request->query('academicyear') ? $request->query('academicyear') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        $institution = $request->query('hei') != null && $request->query('program') == null ? $request->query('hei') : null;
        $program = $request->query('program') != null ? $request->query('program') : null;
        $filter = $request->query('filter') != null ? $request->query('filter') : 'hei';
        
        $complianceTools = EvaluationFormModel::query()
        ->where('effectivity', $acadYear)
        ->whereNot('status', 'Deployed')
        ->when(!$request->query('hei') && !$request->query('program'), function($query) use ($request){
            $query->inRandomOrder()->limit(10)->get();
        })
        ->when($request->query('hei'), function($query) use ($request, $institution) {
            $query->whereHas('institution_program.institution', function($q) use ($request, $institution) {
                $q->where('institutionId', $institution);
            });
        })
        ->when($request->query('program'), function($query) use ($request, $program) {
            $query->whereHas('institution_program.program', function($q) use ($request, $program) {
                $q->where('programId', $program);
            });
        })
        ->with('institution_program.program', 'institution_program.institution', 'item', 'complied', 'not_complied', 'not_applicable', 'no_status')
        ->get();

        $tools = EvaluationFormModel::where('effectivity', $acadYear)->get();
        $programs = ProgramModel::all();

        return Inertia::render('Progmes/Dashboard/Dashboard-Admin-Progress', [
            'complianceTool' => $complianceTools->map(fn($item) => [
                'id' => $item->id,
                'status' => $item->status,
                'institution' => $item->institution_program->institution->name,
                'program' => $item->institution_program->program->program,
                'complied' => $item->complied->count(),
                'notcomplied' => $item->not_complied->count(),
                'notapplicable' => $item->not_applicable->count(),
                'nostatus' => $item->no_status->count(),
                'progress' => $item->item->count() != 0 ? intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count())/$item->item->count())*100)) : 0,
            ]),
            'academicyear' => $acadYear,
            'readyforvisit' => $tools->where('status', 'Submitted')->count(),
            'inprogress' => $tools->where('status', 'In progress')->count(),
            'pending' => $tools->where('status', 'Deployed')->count(),
            'archived' => $tools->where('status', 'Archived')->count(),
            'program_list' => ProgramModel::orderBy('program', 'asc')->orderBy('major', 'asc')->get(),
            'program' => $program,
            'hei_list' => InstitutionModel::orderBy('name', 'asc')->get(),
            'hei' => $institution,
            'filters' => $request->only(['']) + ['filter' => $filter ],
        ]);
    }

    public function dashboardForAdminOverview() {
        return Inertia::render('Progmes/Dashboard/Dashboard-Admin-Overview', [
            'user' => User::where('isActive', 1)->count(),
            'institution' => InstitutionModel::all()->count(),
            'program' => ProgramModel::all()->count(),
            'discipline' => DisciplineModel::all()->count(),
            'usertype' => [User::where('type', 'CHED')->count(), User::where('type', 'HEI')->count()],
        ]);
    }

    public function dashboardForES() {
        return Inertia::render('Progmes/Dashboard/Dashboard-CHED', [
            'data' => 'Hello World',
        ]);
    }

    public function dashboardForHEI() {
        return Inertia::render('Progmes/Dashboard/Dashboard-HEI');
        
    }

    public function setAcademicYear(Request $request) {
        if ($request[0]) {
            $setting = AdminSettingsModel::where('id', 1)->first();


            if ($setting) {
                $setting->update([
                    'currentAcademicYear' => $request[0],
                ]);

                $setting->save();

                return redirect()->back()->with('success', $request[0] . ' is set as default academic year.');
            } else {
                return redirect()->back()->with('failed', 'Failed to set default academic year.');
            }
        } else {
            return redirect()->back()->with('failed', 'Failed to set default academic year.');
        }

    }


}
