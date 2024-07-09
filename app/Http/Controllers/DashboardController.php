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

        if ($user->type == 'CHED') {
                return redirect()->route('dashboard.admin.progress');
        }
        else {
            return redirect()->route('dashboard.hei');
        }
    }

    public function DashboardForCHEDProgress(Request $request) {

        if($request->query('hei') != null && $request->query('program') != null) {
            return redirect()->back()->with('error', 'Invalid input');
        }

        $user = Auth::user();
        $role = $user->role;

        $acadYear = $request->query('academicyear') ? $request->query('academicyear') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        $institution = $request->query('hei') != null && $request->query('program') == null ? $request->query('hei') : null;
        $program = $request->query('program') != null ? $request->query('program') : null;
        $filter = $request->query('filter') != null ? $request->query('filter') : null;

        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        
        $complianceTools = EvaluationFormModel::query()
        ->where('effectivity', $acadYear)
        ->where('status', 'In progress')
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
        ->when($role == 'Education Supervisor', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->with('institution_program.program', 'institution_program.institution', 'item', 'complied', 'not_complied', 'not_applicable', 'no_status')
        ->get();
        

        $tools = EvaluationFormModel::where('effectivity', $acadYear)
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
        ->when($role == 'Education Supervisor', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })->get();
        
        

        $comTools = EvaluationFormModel::where('effectivity', $acadYear)
        ->where('status', 'Monitored')
        ->when($request->query('hei'), function($query) use ($request, $institution) {
            $query->whereHas('institution_program.institution', function($q) use ($institution) {
                $q->where('institutionId', $institution);
            });
        })
        ->when($request->query('program'), function($query) use ($request, $program) {
            $query->whereHas('institution_program.program', function($q) use ($program) {
                $q->where('programId', $program);
            });
        })
        ->when($role == 'Education Supervisor', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->whereNotNull('evaluationDate');


        $jan = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 1);})->count();
        $feb = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 2);})->count();
        $mar = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 3);})->count();
        $apr = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 4);})->count();
        $may = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 5);})->count();
        $jun = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 6);})->count();
        $jul = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 7);})->count();
        $aug = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 8);})->count();
        $sep = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 9);})->count();
        $oct = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 10);})->count();
        $nov = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 11);})->count();
        $dec = (clone $comTools)->where(function ($query) { $query->whereMonth('evaluationDate', 12);})->count();

        $quarter1Count = $jan + $feb + $mar;
        $quarter2Count = $apr + $may + $jun;
        $quarter3Count = $jul + $aug + $sep;
        $quarter4Count = $oct + $nov + $dec;
        
        $program_list = ProgramModel::query()->when($user->role == 'Education Supervisor', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })->orderBy('program', 'asc')->orderBy('major', 'asc')->get();
        

        return Inertia::render('Progmes/Dashboard/Dashboard-CHED-Progress', [
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
            'pending' => $tools->where('status', 'Deployed')->count() + $tools->where('status', 'Locked')->count(),
            'monitored' => $tools->where('status', 'Monitored')->count(),
            'program_list' => $program_list,
            'program' => $program,
            'major' => ProgramModel::where('id', $program)->value('major'),
            'programName' => ProgramModel::where('id', $program)->value('program'),
            'hei_list' => InstitutionModel::orderBy('name', 'asc')->get(),
            'hei' => $institution,
            'heiName' => InstitutionModel::where('id', $institution)->value('name'),
            'filters' => $request->only(['']) + ['filter' => $filter ],
            'quarter1' => $quarter1Count,
            'quarter2' => $quarter2Count,
            'quarter3' => $quarter3Count,
            'quarter4' => $quarter4Count,
            'evaluatedTotal' => $quarter1Count + $quarter2Count + $quarter3Count + $quarter4Count,
            'lineChartDataItem' => [$jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec],
        ]);
    }

    public function dashboardForCHEDOverview() {

        $user = Auth::user();
        $role = $user->role;

        $acadYear = AdminSettingsModel::where('id', 1)->value('currentAcademicYear');

        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();


        $acadYearArray = [
            '2020-2021', '2021-2022', '2022-2023', '2023-2024', '2024-2025', 
            '2025-2026', '2026-2027', '2027-2028', '2028-2029', 
            '2029-2030',
        ];

        $index = array_search($acadYear, $acadYearArray);
        $yearsData = [];

        for ($i = 0; $i <= 2; $i++) {
            $comTools = EvaluationFormModel::where('effectivity', $acadYearArray[$index])
                ->where('status', 'Monitored')
                ->when($role == 'Education Supervisor', function ($query) use ($disciplineIds) {
                    $query->whereIn('disciplineId', $disciplineIds);
                })
                ->whereNotNull('evaluationDate');

            $quarters = [ [1, 2, 3], [4, 5, 6], [7, 8, 9], [10, 11, 12] ];
            $yearData = array_map(function($months) use ($comTools) {
                return (clone $comTools)->where(function ($query) use ($months) {
                    foreach ($months as $month) {
                        $query->orWhereMonth('evaluationDate', $month);
                    }
                })->count();
            }, $quarters);

            $yearsData[] = $yearData;
            $index--;
        }

        [$thisYear, $lastYear, $twoYearsAgo] = $yearsData;


        return Inertia::render('Progmes/Dashboard/Dashboard-CHED-Overview', [
            'user' => User::where('isActive', 1)->count(),
            'institution' => InstitutionModel::all()->count(),
            'program' => ProgramModel::all()->count(),
            'discipline' => DisciplineModel::all()->count(),
            'usertype' => [User::where('type', 'CHED')->count(), User::where('type', 'HEI')->count()],
            'thisYear' => $thisYear,
            'lastYear' => $lastYear,
            'twoYearsAgo' => $twoYearsAgo,
        ]);
    }

    public function dashboardForHEI(Request $request) {
        
        $user = Auth::user();
        $acadYear = $request->query('academicyear') ? $request->query('academicyear') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        $discipline = $request->query('discipline') != null && $request->query('program') == null ? $request->query('discipline') : null;
        $program = $request->query('program') != null ? $request->query('program') : null;
        $filter = $request->query('filter') != null ? $request->query('filter') : null;
        $disciplines = [];
        $disciplineIds = [];
        $hasEvaluationTool = false;
        $showNotification = false;
        
        $institution = RoleModel::where('userId', $user->id)->where('isActive', 1)->value('institutionId');
        $programForPH = RoleModel::where('userId', $user->id)->where('isActive', 1)->value('programId');

        if ($user->role == 'Program Head') {
            $program = $programForPH;
        }

        $disciplines = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->with('discipline')->get();
        $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        
        $complianceTools = EvaluationFormModel::query()
        ->where('effectivity', $acadYear)
        ->whereNot('status', 'Deployed')
        ->when(!$request->query('hei') && !$request->query('program'), function($query) use ($request){
            $query->inRandomOrder()->limit(10)->get();
        })
        ->when($request->query('discipline'), function($query) use ($request, $discipline) {
            $query->where('disciplineId', $discipline);
        })
        ->when($request->query('program'), function($query) use ($request, $program) {
            $query->whereHas('institution_program.program', function($q) use ($request, $program) {
                $q->where('programId', $program);
            });
        })
        ->when($user->role == 'Dean', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->when($user->role == 'Program Head', function ($query) use ($programForPH) {
            $query->whereHas('institution_program.program', function ($query) use ($programForPH) {
                $query->where('id', $programForPH);
            });
        })
        ->whereHas('institution_program.institution', function ($query) use ($institution) {
            $query->where('institutionId', $institution);
        })
        ->with('institution_program.program', 'institution_program.institution', 'item', 'complied', 'not_complied', 'not_applicable', 'no_status')
        ->get();

        $tools = EvaluationFormModel::where('effectivity', $acadYear)
        ->when($request->query('discipline'), function($query) use ($request, $discipline) {
            $query->where('disciplineId', $discipline);
        })
        ->when($request->query('program'), function($query) use ($request, $program) {
            $query->whereHas('institution_program.program', function($q) use ($request, $program) {
                $q->where('programId', $program);
            });
        })
        ->when($user->role == 'Dean', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->whereHas('institution_program.institution', function ($query) use ($institution) {
            $query->where('institutionId', $institution);
        })
        ->get();

        $programs = InstitutionProgramModel::where('institutionId', $institution)->where('isActive', 1)->get();
        $programIds = $programs->pluck('program.id')->toArray();

        $program_list = ProgramModel::whereIn('id', $programIds)
        ->when($user->role == 'Dean', function ($query) use ($disciplineIds) {
            $query->where('disciplineId', $disciplineIds);
        })
        ->orderBy('program', 'asc')->orderBy('major', 'asc')->get();

        return Inertia::render('Progmes/Dashboard/Dashboard-HEI', [
            'complianceTool' => $complianceTools->map(fn($item) => [
                'id' => $item->id,
                'status' => $item->status,
                'institution' => $item->institution_program->institution->name,
                'program' => $item->institution_program->program->program,
                'updates' => $item->updates,
                'complied' => $item->complied->count(),
                'notcomplied' => $item->not_complied->count(),
                'notapplicable' => $item->not_applicable->count(),
                'nostatus' => $item->no_status->count(),
                'progress' => $item->item->count() != 0 ? intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count())/$item->item->count())*100)) : 0,
                'updates' => $item->item()->whereDay('updated_at', '=', now()->day)->count(),
            ]),
            'role' => $user->role,
            'academicyear' => $acadYear,
            'readyforvisit' => $tools->where('status', 'Submitted')->count(),
            'inprogress' => $tools->where('status', 'In progress')->count(),
            'pending' => $tools->where('status', 'Deployed')->count() + $tools->where('status', 'Locked')->count(),
            'archived' => $tools->where('status', 'Archived')->count(),
            'program_list' => $program_list,
            'program' => $program,
            'programName' => ProgramModel::where('id', $program)->value('program'),
            'major' => ProgramModel::where('id', $program)->value('major'),
            'discipline_list' => DisciplineModel::orderBy('discipline', 'asc')->get(),
            'discipline' => $discipline,
            'disciplineName' => DisciplineModel::where('id', $discipline)->value('discipline'),
            'heiName' => InstitutionModel::where('id', $institution)->value('name'),
            'filters' => $request->only(['']) + ['filter' => $filter ],
        ]);
    }

    public function analytics () {
        return Inertia::render('Progmes/Dashboard/Dashboard-CHED-Analytics');
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
