<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\ProgramAssignmentModel;
use App\Models\ProgramModel;
use App\Models\InstitutionModel;
use App\Models\RoleModel;
use Auth;

class UserController extends Controller
{
    private function getBaseQuery($request, $role, $disciplineList = [], $programList = [])
    {
        return User::query()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    applyUserSearch($query, $request->query('search'));
                    applyProgramSearch($query, $request->query('search'), 'userRole.program', true);
                    applyDisciplineSearch($query, $request->query('search'), 'userRole.discipline', true);
                    applyInstitutionSearch($query, $request->query('search'), 'userRole.institution', true);
                });
            })
            ->when($request->query('type'), function ($query) use ($request) {
                $query->where('type', $request->query('type'));
            })
            ->when($request->query('role'), function ($query) use ($request) {
                $query->where('role', $request->query('role'));
            })
            ->when($request->query('program'), function ($query) use ($request) {
                $query->whereHas('userRole.program', function ($programModel) use ($request) {
                    $programModel->where('id', $request->query('program'));
                });
            })
            ->when($request->query('institution'), function ($query) use ($request) {
                $query->whereHas('userRole.institution', function ($institutionModel) use ($request) {
                    $institutionModel->where('id', $request->query('institution'));
                });
            })
            ->when($role != 'Super Admin', function ($query) use ($disciplineList, $programList) {
                $query->where('type', 'HEI');
            })
            ->when($role == 'Education Supervisor', function ($query) use ($disciplineList, $programList) {
                $query->where(function($q) use ($programList, $disciplineList) {
                    // Include users with matching programs
                    if (!empty($programList)) {
                        $q->whereHas('userRole.program', function ($subQ) use ($programList) {
                            $subQ->whereIn('programId', $programList);
                        });
                    }
                    
                    // Include users with matching disciplines
                    if (!empty($disciplineList)) {
                        $q->orWhereHas('userRole.discipline', function ($subQ) use ($disciplineList) {
                            $subQ->whereIn('disciplineId', $disciplineList);
                        });
                    }
                });
            })
            ->whereNot('role', 'Super Admin');
    }

    private function getUserPermissions($role)
    {
        $permissions = [
            'canEdit' => false,
            'canFilter' => false,
            'showRequest' => false,
            'showInactive' => false,
            'disciplineList' => [],
            'programList' => [],
        ];

        if ($role == 'Super Admin') {
            $permissions['showInactive'] = true;
            $permissions['canEdit'] = true;
            $permissions['canFilter'] = true;
            $permissions['showRequest'] = true;
        } elseif ($role == 'Admin') {
            $permissions['canEdit'] = true;
            $permissions['canFilter'] = true;
            $permissions['showRequest'] = true;
        } elseif ($role == 'Education Supervisor') {
            $permissions['canEdit'] = true;
            $permissions['showRequest'] = true;
            $permissions['disciplineList'] = RoleModel::where('userId', Auth::id())->where('isActive', 1)
                ->pluck('disciplineId')
                ->toArray();
            $permissions['programList'] = ProgramAssignmentModel::where('userId', Auth::id())
                ->pluck('programId')
                ->toArray();
        }

        return $permissions;
    }

    public function index(Request $request)
    {

        $role = Auth::user()->role;
        $permissions = $this->getUserPermissions($role);
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);
        
        $userlist = $this->getBaseQuery($request, $role, $permissions['disciplineList'], $permissions['programList'])
            ->where(['isVerified' => 1, 'isActive' => 1])
            ->with(['userRole' => function ($q) {
                $q->where('isActive', 1);
            }, 'userRole.discipline', 'userRole.program', 'userRole.institution'], 'totalRequest')
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();
        
        return Inertia::render('Admin/user/User-List', [
            'user_list' => $userlist,
            'program_list' => $this->getPrograms(),
            'institution_list' => $this->getInstitutions(),
            'requestCount' => User::requestCount($role, Auth::user()->id),
            'filters' => $request->only(['search', 'type', 'role', 'program', 'institution']) + ['show' => $show],
        ] + $permissions);
    }

    public function request(Request $request)
    {
        $role = Auth::user()->role;
        $permissions = $this->getUserPermissions($role);
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);

        $userlist = $this->getBaseQuery($request, $role, $permissions['disciplineList'], $permissions['programList'])
            ->whereNull(['isVerified', 'isActive'])
            ->with(['userRole', 'userRole.discipline', 'userRole.program', 'userRole.institution'])
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/user/User-Request', [
            'user_list' => $userlist,
            'program_list' => $this->getPrograms(),
            'institution_list' => $this->getInstitutions(),
            'requestCount' => User::requestCount($role, Auth::user()->id),
            'filters' => $request->only(['search', 'type', 'role', 'program', 'institution']) + ['show' => $show],
        ] + $permissions);
    }

    public function inactive(Request $request)
    {
        $role = Auth::user()->role;
        $permissions = $this->getUserPermissions($role);
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);

        $userlist = $this->getBaseQuery($request, $role)
            ->where(['isVerified' => 1, 'isActive' => 0])
            ->with(['userRole' => function ($q) {
                $q->where('isActive', 1);
            }, 'userRole.discipline', 'userRole.program', 'userRole.institution'])
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/user/User-Inactive', [
            'user_list' => $userlist,
            'program_list' => $this->getPrograms(),
            'institution_list' => $this->getInstitutions(),
            'requestCount' => User::requestCount($role, Auth::user()->id),
            'filters' => $request->only(['search', 'type', 'role', 'program', 'institution']) + ['show' => $show],
        ] + $permissions);
    }


    public function userLogin(Request $request)
    {
        $query = User::query()
            ->with(['userRole' => function ($q) {
                $q->where('isActive', 1);
            }, 'userRole.discipline', 'userRole.program', 'userRole.institution']);

        // Apply filters
        if ($request->query('name')) {
            $query->where('name', 'like', '%' . $request->query('name') . '%');
        }

        if ($request->query('role')) {
            $query->where('role', 'like', '%' . $request->query('role') . '%');
        }

        if ($request->query('discipline')) {
            $query->whereHas('userRole.discipline', function ($q) use ($request) {
                $q->where('discipline',  'like', '%' . $request->query('discipline') . '%');
            });
        }

        if ($request->query('program')) {
            $query->whereHas('userRole.program', function ($q) use ($request) {
                $q->where('program', 'like', '%' . $request->query('program') . '%')
                ->orWhere('major', 'like', '%' . $request->query('program') . '%');
            });
        }

        if ($request->query('institution')) {
            $query->whereHas('userRole.institution', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query('institution') . '%');
            });
        }

        $userlist = $query->orderBy('name', 'asc')
            ->paginate(50)
            ->withQueryString();

        return Inertia::render('Auth/TestLogin', [
            'user_list' => $userlist,
            'filters' => $request->only(['name', 'role', 'discipline', 'program', 'institution']),
        ]);
    }

    public function testUserLogin(Request $request)
    {
        Auth::login(User::find($request->user));
        return redirect('/dashboard');
    }

    public function getPrograms()
    {
        $user = Auth::user();

        $query = ProgramModel::query()
        ->when($user->role == 'Education Supervisor', function($query) use ($user) {
            $query->whereIn('id', getUserAssignedProgramIds($user->id));
        })
        ->orderBy('program', 'asc')
        ->orderBy('major', 'asc')
        ->get();

        return $query;
    }

    public function getInstitutions()
    {
        return InstitutionModel::orderBy('name', 'asc')->get();
    }
}