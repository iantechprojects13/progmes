<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\ProgramAssignmentModel;
use App\Models\RoleModel;
use Auth;

class UserController extends Controller
{
    private function getBaseQuery($request, $role, $programList = [])
    {
        return User::query()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    // $search = '%' . $request->query('search') . '%';
                    // $query->where('name', 'like', $search)
                    //     ->orWhere('type', 'like', $search)
                    //     ->orWhere('role', 'like', $search)
                    //     ->orWhereHas('userRole.program', function ($q) use ($search) {
                    //         $q->where('program', 'like', $search)
                    //             ->orWhere('major', 'like', $search);
                    //     })
                    //     ->orWhereHas('userRole.institution', function ($q) use ($search) {
                    //         $q->where('name', 'like', $search);
                    //     })
                    //     ->orWhereHas('userRole.discipline', function ($q) use ($search) {
                    //         $q->where('discipline', 'like', $search);
                    //     });
                    
                    applyUserSearch($query, $request->query('search'));
                    applyProgramSearch($query, $request->query('search'), 'userRole.program', true);
                    applyDisciplineSearch($query, $request->query('search'), 'userRole.discipline', true);
                    applyInstitutionSearch($query, $request->query('search'), 'userRole.institution', true);
                });
            })
            ->when($request->query('type'), function ($query) use ($request) {
                $query->where('type', $request->query('type'));
            })
            ->when($role == 'Education Supervisor', function ($query) use ($programList) {
                $query->whereHas('userRole.program', function ($q) use ($programList) {
                    $q->whereIn('programId', $programList);
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
        
        $userlist = $this->getBaseQuery($request, $role, $permissions['programList'])
            ->where(['isVerified' => 1, 'isActive' => 1])
            ->with(['userRole' => function ($q) {
                $q->where('isActive', 1);
            }, 'userRole.discipline', 'userRole.program', 'userRole.institution'], 'totalRequest')
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/user/User-List', [
            'user_list' => $userlist,
            'requestCount' => User::requestCount($role),
            'filters' => $request->only(['search', 'type']) + ['show' => $show],
        ] + $permissions);
    }

    public function request(Request $request)
    {
        $role = Auth::user()->role;
        $permissions = $this->getUserPermissions($role);
        $show = sanitizePerPage($request->query('show'), Auth::user()->id);

        $userlist = $this->getBaseQuery($request, $role, $permissions['programList'])
            ->whereNull(['isVerified', 'isActive'])
            ->with(['userRole', 'userRole.discipline', 'userRole.program', 'userRole.institution'])
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/user/User-Request', [
            'user_list' => $userlist,
            'requestCount' => User::requestCount($role),
            'filters' => $request->only(['search', 'type']) + ['show' => $show],
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
            'requestCount' => User::requestCount($role),
            'filters' => $request->only(['search', 'type']) + ['show' => $show],
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

    public function testUsers($name, $role, $institution, ) {
        
    }

    
}