<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\RoleModel;
use Auth;

class UserController extends Controller
{
    private function getBaseQuery($request, $role, $disciplineList = [])
    {
        return User::query()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $search = '%' . $request->query('search') . '%';
                    $query->where('name', 'like', $search)
                        ->orWhere('type', 'like', $search)
                        ->orWhere('role', 'like', $search)
                        ->orWhereHas('userRole.program', function ($q) use ($search) {
                            $q->where('program', 'like', $search)
                                ->orWhere('major', 'like', $search);
                        })
                        ->orWhereHas('userRole.institution', function ($q) use ($search) {
                            $q->where('name', 'like', $search);
                        })
                        ->orWhereHas('userRole.discipline', function ($q) use ($search) {
                            $q->where('discipline', 'like', $search);
                        });
                });
            })
            ->when($request->query('type'), function ($query) use ($request) {
                $query->where('type', $request->query('type'));
            })
            ->when($role == 'Education Supervisor', function ($query) use ($disciplineList) {
                $query->whereHas('userRole.program', function ($q) use ($disciplineList) {
                    $q->whereIn('disciplineId', $disciplineList);
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
            $permissions['disciplineList'] = RoleModel::where('userId', Auth::id())
                ->where('isActive', 1)
                ->pluck('disciplineId')
                ->toArray();
        }

        return $permissions;
    }

    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $permissions = $this->getUserPermissions($role);
        $show = $request->query('show', 25);

        $userlist = $this->getBaseQuery($request, $role, $permissions['disciplineList'])
            ->where(['isVerified' => 1, 'isActive' => 1])
            ->with(['userRole' => function ($q) {
                $q->where('isActive', 1);
            }, 'userRole.discipline', 'userRole.program', 'userRole.institution'], 'totalRequest')
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/User-List', [
            'user_list' => $userlist,
            'requestCount' => User::requestCount($role),
            'filters' => $request->only(['search', 'type']) + ['show' => $show],
        ] + $permissions);
    }

    public function request(Request $request)
    {
        $role = Auth::user()->role;
        $permissions = $this->getUserPermissions($role);
        $show = $request->query('show', 25);

        $userlist = $this->getBaseQuery($request, $role, $permissions['disciplineList'])
            ->whereNull(['isVerified', 'isActive'])
            ->with(['userRole', 'userRole.discipline', 'userRole.program', 'userRole.institution'])
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/User-Request', [
            'user_list' => $userlist,
            'requestCount' => User::requestCount($role),
            'filters' => $request->only(['search', 'type']) + ['show' => $show],
        ] + $permissions);
    }

    public function inactive(Request $request)
    {
        $role = Auth::user()->role;
        $permissions = $this->getUserPermissions($role);
        $show = $request->query('show', 25);

        $userlist = $this->getBaseQuery($request, $role)
            ->where(['isVerified' => 1, 'isActive' => 0])
            ->with(['userRole' => function ($q) {
                $q->where('isActive', 1);
            }, 'userRole.discipline', 'userRole.program', 'userRole.institution'])
            ->orderBy('name', 'asc')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/User-Inactive', [
            'user_list' => $userlist,
            'requestCount' => User::requestCount($role),
            'filters' => $request->only(['search', 'type']) + ['show' => $show],
        ] + $permissions);
    }

    public function userLogin()
    {
        $userlist = User::with(['userRole.discipline', 'userRole.program', 'userRole.institution'])
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Auth/TestLogin', [
            'user_list' => $userlist,
        ]);
    }

    public function testUserLogin(Request $request)
    {
        Auth::login(User::find($request->user));
        return redirect('/dashboard');
    }
}