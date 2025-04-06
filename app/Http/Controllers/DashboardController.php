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
use App\Models\LibEvaluationFormModel;
use App\Models\RoleModel;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $user = Auth::user();
        if ($user->type == 'CHED') {
                return redirect()->route('dashboard.admin.ched');
        }
        else {
            return redirect()->route('dashboard.hei');
        }
    }

    public function DashboardForCHED(Request $request) {
        
        $user = Auth::user();
        $role = $user->role;

        $currentYear = (Carbon::now()->year);

        $acadYear = $request->query('academicyear') ? $request->query('academicyear') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        $institution = $request->query('hei') != null ? $request->query('hei') : null;
        $program = $request->query('program') != null ? $request->query('program') : null;
        $supervisorq = $request->query('supervisor') != null ? (int) $request->query('supervisor') : null;
        $filter = $request->query('filter') != null ? $request->query('filter') : null;
        $year = $request->query('year') != null ? $request->query('year') : $currentYear;
        $period = $request->query('period') != null ? $request->query('period') : 'Quarter';


        $justLastYear = $year - 1;
        $yearBeforeLast = $year -2;
        $currentMonth = Carbon::now()->month;

        $disciplineIds = [];
    
        // Get discipline IDs for the current user if they are an Education Supervisor
        if($role == 'Education Supervisor') {
            $disciplines = RoleModel::where('userId', $user->id)
                ->where('isActive', 1)
                ->with('discipline')
                ->get();
            $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        }
    
        // Get discipline IDs for the specified supervisor if provided
        if($request->query('supervisor') !== null && $request->query('supervisor') !== 'null' && $request->query('supervisor') !== '') {
            $disciplines = RoleModel::where('userId', $request->query('supervisor'))
                ->where('isActive', 1)
                ->with('discipline')
                ->get();
            $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        }
        

        $supervisor_list = User::where('role', 'Education Supervisor')->where('isActive', 1)->orderBy('name', 'asc')->get();
        $supervisor = User::where('id', $request->query('supervisor'))->first();
        
        
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
        ->when($role == 'Education Supervisor' || $request->query('supervisor'), function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })->get();

        
        

        $comTools = EvaluationFormModel::whereYear('evaluationDate', $year)
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
        ->when($role == 'Education Supervisor' || $request->query('supervisor'), function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->whereNotNull('evaluationDate');

        $months = range(1, 12);
        $monthlyCounts = array_map(function($month) use ($comTools) {
            return (clone $comTools)->whereMonth('evaluationDate', $month)->count();
        }, $months);

        [$jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec] = $monthlyCounts;

        $quarter1Count = $jan + $feb + $mar;
        $quarter2Count = $apr + $may + $jun;
        $quarter3Count = $jul + $aug + $sep;
        $quarter4Count = $oct + $nov + $dec;

        $yearArray = [$currentYear, $justLastYear, $yearBeforeLast];
        $index = 0;

        for ($i = 0; $i <= 2; $i++) {
            $comTools = EvaluationFormModel::whereYear('evaluationDate', $yearArray[$index])
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

            $quarters = [ [1, 2, 3], [4, 5, 6], [7, 8, 9], [10, 11, 12] ];
            $yearData = array_map(function($months) use ($comTools) {
                return (clone $comTools)->where(function ($query) use ($months) {
                    foreach ($months as $month) {
                        $query->orWhereMonth('evaluationDate', $month);
                    }
                })->count();
            }, $quarters);
            
            $yearsData[] = $yearData;
            $index++;
        }

        [$thisYear, $lastYear, $twoYearsAgo] = $yearsData;
        
        
        $program_list = ProgramModel::query()->when($user->role == 'Education Supervisor', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })->orderBy('program', 'asc')->orderBy('major', 'asc')->get();
        

        $totalPrograms = EvaluationFormModel::where('effectivity', 'like', '%' . $year . '%')
        ->when($role == 'Education Supervisor', function ($query) use ($disciplineIds) {
            $query->whereIn('disciplineId', $disciplineIds);
        })
        ->select('institutionProgramId')
        ->distinct()
        ->get()
        ->count();

        // dd($request->query());


        if ($program) {
            $totalPrograms = InstitutionProgramModel::where('programId', $program)->count();
        } else if ($institution) {
            $totalPrograms = InstitutionProgramModel::where('institutionId', $institution)->count();
        } else {
            $totalPrograms = EvaluationFormModel::where('effectivity', 'like', '%' . $year . '%')
            ->when($role == 'Education Supervisor', function ($query) use ($disciplineIds) {
                $query->whereIn('disciplineId', $disciplineIds);
            })
            ->select('institutionProgramId')
            ->distinct()
            ->get()
            ->count();
        }


        return Inertia::render('Dashboard/Dashboard-CHED', [
            'userCount' => User::where('isActive', 1)->count(),
            'institutionCount' => InstitutionModel::all()->count(),
            'programCount' => ProgramModel::all()->count(),
            'disciplineCount' => DisciplineModel::all()->count(),
            'usertype' => [User::where('type', 'CHED')->count(), User::where('type', 'HEI')->count()],
            'currentYear' => $currentYear,
            'academicyear' => $acadYear,
            'readyforvisit' => $tools->where('status', 'Submitted')->count(),
            'inprogress' => $tools->where('status', 'In progress')->count() + $tools->where('status', 'Deployed')->count(),
            'forreevaluation' => $tools->where('status', 'For re-evaluation')->count(),
            'monitored' => $tools->where('status', 'Monitored')->count(),
            'program_list' => $program_list,
            'program' => $program,
            'major' => ProgramModel::where('id', $program)->value('major'),
            'programName' => ProgramModel::where('id', $program)->value('program'),
            'hei_list' => InstitutionModel::orderBy('name', 'asc')->get(),
            'hei' => $institution,
            'heiName' => InstitutionModel::where('id', $institution)->value('name'),
            'filters' => $request->only(['supervisor', 'program', 'hei']) + ['year' => $year, 'academicyear' => $acadYear],
            'quarterData' => [$quarter1Count, $quarter2Count, $quarter3Count, $quarter4Count],
            'thisYear' => $thisYear,
            'lastYear' => $lastYear,
            'twoYearsAgo' => $twoYearsAgo,
            'evaluatedTotal' => $quarter1Count + $quarter2Count + $quarter3Count + $quarter4Count,
            'byMonthData' => [$jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec],
            'monthIndex' => $currentMonth-1,
            'isCurrentYear' => $year == $currentYear ? true : false,
            'analyticsLabel' => [$year, $year-1, $year-2],
            'displayAnalyticsData' => $year <= $currentYear ? true : false,
            'totalPrograms' => $totalPrograms,
            'currentQuarter' => Carbon::now()->quarter,
            'supervisor_list' => $supervisor_list,
            'supervisor' => $supervisorq,
            'supervisor_name' =>  $supervisor->name ?? null,
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

        $libComplianceTools = LibEvaluationFormModel::query()
        ->where('effectivity', $acadYear)
        ->whereNot('status', 'Deployed')
        ->whereHas('institution', function ($query) use ($institution) {
            $query->where('institutionId', $institution);
        })
        ->with('institution', 'item', 'complied', 'not_complied', 'not_applicable', 'no_status')
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

        return Inertia::render('Dashboard/Dashboard-HEI', [
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
            'libComplianceTool' => $libComplianceTools->map(fn($item) => [
                'id' => $item->id,
                'status' => $item->status,
                'institution' => $item->institution->name,
                'complied' => $item->complied->count(),
                'notcomplied' => $item->not_complied->count(),
                'notapplicable' => $item->not_applicable->count(),
                'nostatus' => $item->no_status->count(),
                'progress' => $item->item->count() != 0 ? intval(round((($item->complied->count() + $item->not_complied->count() + $item->not_applicable->count())/$item->item->count())*100)) : 0,
            ]),
            'role' => $user->role,
            'academicyear' => $acadYear,
            'readyforvisit' => $tools->where('status', 'Submitted')->count(),
            'inprogress' => $tools->where('status', 'In progress')->count(),
            'forreevaluation' => $tools->where('status', 'For re-evaluation')->count(),
            'monitored' => $tools->where('status', 'Monitored')->count(),
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


    public function viewTools($academicyear, $status, $supervisor, $program, $institution) {
        $user = Auth::user();
        $role = $user->role;
        $disciplineIds = [];
    
        // Get discipline IDs for the current user if they are an Education Supervisor
        if($role == 'Education Supervisor') {
            $disciplines = RoleModel::where('userId', $user->id)
                ->where('isActive', 1)
                ->with('discipline')
                ->get();
            $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        }
    
        // Get discipline IDs for the specified supervisor if provided
        if($supervisor !== null && $supervisor !== 'null' && $supervisor !== '') {
            $disciplines = RoleModel::where('userId', $supervisor)
                ->where('isActive', 1)
                ->with('discipline')
                ->get();
            $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        }
    
        // Build the query
        $query = EvaluationFormModel::query();

        // Apply academic year and status filters
        $query->where('effectivity', $academicyear)
              ->where(function($q) use ($status) {
              $q->where('status', $status);
              if ($status == 'In progress') {
                  $q->orWhere('status', 'Deployed');
              }
              });

        // Apply discipline filter if applicable
        if($role == 'Education Supervisor' || ($supervisor !== null && $supervisor !== 'null' && $supervisor !== '')) {
            $query->whereIn('disciplineId', $disciplineIds);
        }
        
        // Apply program filter if provided
        if($program !== null && $program !== 'null' && $program !== '') {
            $query->whereHas('institution_program', function($q) use ($program) {
                $q->where('programId', $program);
            });
        }
        
        // Apply institution filter if provided
        if($institution !== null && $institution !== 'null' && $institution !== '') {
            $query->whereHas('institution_program', function($q) use ($institution) {
                $q->where('institutionId', $institution);
            });
        }
        
        // Get the results with relationships
        $tools = $query->with('institution_program.institution', 'institution_program.program')->get();
        
        return response()->json($tools);
    }



    public function viewMonitoredTools($supervisor, $program, $institution, $year, $month, $quarter)
    {
        $user = Auth::user();
        $role = $user->role;
        $disciplineIds = [];
    
        // Get discipline IDs for the current user if they are an Education Supervisor
        if($role == 'Education Supervisor') {
            $disciplines = RoleModel::where('userId', $user->id)
                ->where('isActive', 1)
                ->with('discipline')
                ->get();
            $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        }
        
        // Get discipline IDs for the specified supervisor if provided
        if($supervisor !== null && $supervisor !== 'null' && $supervisor !== '') {
            $disciplines = RoleModel::where('userId', $supervisor)
                ->where('isActive', 1)
                ->with('discipline')
                ->get();
            $disciplineIds = $disciplines->pluck('discipline.id')->toArray();
        }
    
        // Build the query
        $query = EvaluationFormModel::query();

        
        
        // Apply discipline filter if applicable
        if($role == 'Education Supervisor' || ($supervisor !== null && $supervisor !== 'null' && $supervisor !== '')) {
            $query->whereIn('disciplineId', $disciplineIds);
        }
        
        // Apply academic year and status filters
        $query->where(function ($q) {
            $q->where('status', 'Monitored')->orWhereNotNull('evaluationDate');
        });

        if($year !== null && $year !== 'null' && $year !== '') {
            $query->whereYear('evaluationDate', $year);
        }

        if($month !== null && $month !== 'null' && $month !== '') {
            $query->whereMonth('evaluationDate', $month);
        }

        if($quarter !== null && $quarter !== 'null' && $quarter !== '') {
            $query->where(function ($q) use ($quarter) {
                if ($quarter == 1) {
                    $q->whereMonth('evaluationDate', 1)
                      ->orWhereMonth('evaluationDate', 2)
                      ->orWhereMonth('evaluationDate', 3);
                } elseif ($quarter == 2) {
                    $q->whereMonth('evaluationDate', 4)
                      ->orWhereMonth('evaluationDate', 5)
                      ->orWhereMonth('evaluationDate', 6);
                } elseif ($quarter == 3) {
                    $q->whereMonth('evaluationDate', 7)
                      ->orWhereMonth('evaluationDate', 8)
                      ->orWhereMonth('evaluationDate', 9);
                } elseif ($quarter == 4) {
                    $q->whereMonth('evaluationDate', 10)
                      ->orWhereMonth('evaluationDate', 11)
                      ->orWhereMonth('evaluationDate', 12);
                }
            });
        }
        
        // Apply program filter if provided
        if($program !== null && $program !== 'null' && $program !== '') {
            $query->whereHas('institution_program', function($q) use ($program) {
                $q->where('programId', $program);
            });
        }
        
        // Apply institution filter if provided
        if($institution !== null && $institution !== 'null' && $institution !== '') {
            $query->whereHas('institution_program', function($q) use ($institution) {
                $q->where('institutionId', $institution);
            });
        }
        
        // Get the results with relationships
        $tools = $query->with('institution_program.institution', 'institution_program.program')->get();
        
        return response()->json($tools);   
    }

}   
