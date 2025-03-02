<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\User;
use App\Models\DisciplineModel;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\RoleModel;
use Auth;

class UserController extends Controller
{
    public function index(Request $request) {
        
        $role = Auth::user()->role;
        $canEdit = false;
        $canFilter = false;
        $showRequest = false;
        $showInactive = false;
        $disciplineList = [];
        $show = $request->query('show') ? $request->query('show') : 25;
        
        if($role == 'Super Admin') {
            $showInactive = true;
        }

        if ($role == 'Super Admin' || $role == 'Admin') {
            $canEdit = true;
            $canFilter = true;
            $showRequest = true;
        }

        if ($role == 'Education Supervisor') {
            $canEdit = true;
            $showRequest = true;
            $discipline = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->get();
            foreach($discipline as $item) {
                array_push($disciplineList, $item->disciplineId);
            }
        }
        
        $userlist = User::query()
        ->when($request->query('search'), function ($query) use ($request, $role) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%')
                ->orWhere('type', 'like', '%' . $request->query('search') . '%')
                ->orWhere('role', 'like', '%' . $request->query('search') . '%')
                ->orWhereHas('userRole.program', function ($programQuery) use ($request) {
                    $programQuery->where('program', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('major', 'like', '%' . $request->query('search') . '%');
                })
                ->orWhereHas('userRole.institution', function ($institutionQuery) use ($request) {
                    $institutionQuery->where('name', 'like', '%' . $request->query('search') . '%');
                })
                ->orWhereHas('userRole.discipline', function ($institutionQuery) use ($request) {
                    $institutionQuery->where('discipline', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->where([
                'isVerified' => 1,
                'isActive' => 1,
            ])
            ->orderBy('name', 'asc');
        })
        ->when($request->query('type'), function ($query) use ($request) {
            $query->where('type', $request->query('type'));
        })
        ->where([
            'isVerified' => 1,
            'isActive' => 1,
        ])
        ->whereNot('role', 'Super Admin')
        ->orderBy('name', 'asc')
        ->when($role == 'Education Supervisor', function ($query) use ($disciplineList) {
            $query->whereHas('userRole.program', function ($q) use ($disciplineList) {
                $q->whereIn('disciplineId', $disciplineList);
            });
        })
        ->with([
            'userRole' => function ($query) {
                $query->where('isActive', 1);
            },
            'userRole.discipline',
            'userRole.program',
            'userRole.institution',
        ], 'totalRequest')
        ->paginate($show)
        ->withQueryString();
        

        return Inertia::render('Admin/User-List', [
            'user_list' => $userlist,
            'requestCount' => User::requestCount(),
            'canEdit' => $canEdit,
            'canFilter' => $canFilter,
            'showRequest' => $showRequest,
            'showInactive' => $showInactive,
            'filters' => $request->only(['search', 'type']) + ['show' => $show ],
        ]);
    }



    public function request(Request $request) {

        $role = Auth::user()->role;
        $showInactive = false;
        $canEdit = false;
        $canFilter = false;
        $disciplineList = [];
        $show = $request->query('show') ? $request->query('show') : 25;

        if ($role == 'Super Admin') {
            $showInactive = true;
        }

        if ($role == 'Super Admin' || $role == 'Admin') {
            $canFilter = true;
            $canEdit = true;
        }

        if ($role == 'Education Supervisor') {
            $canEdit = true;
            $discipline = RoleModel::where('userId', Auth::user()->id)->where('isActive', 1)->get();
            foreach($discipline as $item) {
                array_push($disciplineList, $item->disciplineId);
            }
        }

        $userlist = User::query()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('type', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('role', 'like', '%' . $request->query('search') . '%')
                        ->orWhereHas('userRole.program', function ($programQuery) use ($request) {
                            $programQuery->where('program', 'like', '%' . $request->query('search') . '%')
                                ->orWhere('major', 'like', '%' . $request->query('search') . '%');
                        })
                        ->orWhereHas('userRole.institution', function ($institutionQuery) use ($request) {
                            $institutionQuery->where('name', 'like', '%' . $request->query('search') . '%');
                        })
                        ->orWhereHas('userRole.discipline', function ($institutionQuery) use ($request) {
                            $institutionQuery->where('discipline', 'like', '%' . $request->query('search') . '%');
                        });
                });
            })
            ->when($request->query('type'), function ($query) use ($request) {
                $query->where('type', $request->query('type'));
            })
            ->whereNull('isVerified')
            ->whereNull('isActive')
            ->whereNot('role', 'Super Admin')
            ->when($role == 'Education Supervisor', function ($query) use ($disciplineList) {
                $query->whereHas('userRole.program', function ($q) use ($disciplineList) {
                    $q->whereIn('disciplineId', $disciplineList);
                });
            })
            ->with([
                'userRole',
                'userRole.discipline',
                'userRole.program',
                'userRole.institution'
            ])
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/User-Request', [
            'user_list' => $userlist,
            'requestCount' => User::requestCount(),
            'canEdit' => $canEdit,
            'canFilter' => $canFilter,
            'showInactive' => $showInactive,
            'filters' => $request->only(['search', 'type']) + ['show' => $show ],
        ]);
    }


    

    public function inactive(Request $request) {
        
        $role = Auth::user()->role;
        $canEdit = false;
        $canFilter = false;
        $disciplineList = [];
        $show = $request->query('show') ? $request->query('show') : 25;
        
        if ($role == 'Super Admin') {
            $canEdit = true;
            $canFilter = true;
        }
        
        $userlist = User::query()
        ->when($request->query('search'), function ($query) use ($request, $role) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%')
                ->orWhere('type', 'like', '%' . $request->query('search') . '%')
                ->orWhere('role', 'like', '%' . $request->query('search') . '%')
                ->orWhereHas('userRole.program', function ($programQuery) use ($request) {
                    $programQuery->where('program', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('major', 'like', '%' . $request->query('search') . '%');
                })
                ->orWhereHas('userRole.institution', function ($institutionQuery) use ($request) {
                    $institutionQuery->where('name', 'like', '%' . $request->query('search') . '%');
                })
                ->orWhereHas('userRole.discipline', function ($institutionQuery) use ($request) {
                    $institutionQuery->where('discipline', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->where([
                'isVerified' => 1,
                'isActive' => 0,
            ])
            ->orderBy('name', 'asc');
        })
        ->when($request->query('type'), function ($query) use ($request) {
            $query->where('type', $request->query('type'));
        })
        ->where([
            'isVerified' => 1,
            'isActive' => 0,
        ])
        ->whereNot('role', 'Super Admin')
        ->orderBy('name', 'asc')
        ->with([
            'userRole' => function ($query) {
                $query->where('isActive', 1);
            },
            'userRole.discipline',
            'userRole.program',
            'userRole.institution'
        ])
        ->paginate($show)
        ->withQueryString();

        return Inertia::render('Admin/User-Inactive', [
            'user_list' => $userlist,
            'requestCount' => User::requestCount(),
            'canEdit' => $canEdit,
            'canFilter' => $canFilter,
            'filters' => $request->only(['search', 'type']) + ['show' => $show ],
        ]);
    }



    public function userLogin() {
        return Inertia::render('Auth/TestLogin', [
            'users' => User::orderBy('name', 'asc')->get(),
        ]);
    }
    
    public function testUserLogin(Request $request) {
        $testuser = User::find($request->user);
        Auth::login($testuser);
        
        return redirect('/dashboard');
    }
}
