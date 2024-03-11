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
        $list = ['type' => 'HEI'];
        
        if ($role == 'Super Admin') {
            $canEdit = true;
        }

        if ($role != 'Super Admin') {
            
        }
        
        $userlist = User::query()
        ->when($request->query('search'), function ($query) use ($request, $role, $list) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('type', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('role', 'like', '%' . $request->query('search') . '%');
            })
            ->where([
                'isVerified' => 1,
                'isActive' => 1,
            ])
            ->when($role != 'Super Admin', function ($query) use ($list) {
                $query->where($list);
            })
            ->orderBy('name', 'asc');
        })
        ->when($request->query('sort'), function ($query) use ($request) {
            $query->orderBy($request->query('sort'), 'asc');
        })
        ->when($request->query('type'), function ($query) use ($request) {
            $query->where('type', $request->query('type'));
        })
        ->when($role != 'Super Admin', function ($query) use ($list) {
            $query->where($list);
        })
        ->where([
            'isVerified' => 1,
            'isActive' => 1,
        ])
        ->whereNot('role', 'Super Admin')
        ->with('userRole', 'userRole.discipline', 'userRole.program', 'userRole.institution');
        
        $count = $userlist->count();

        $userlist = $userlist
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/User-List', [
            'user_list' => $userlist,
            'count' => $count,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search', 'sort', 'type']),
        ]);

    }



    public function request(Request $request) {

        $role = Auth::user()->role;
        $canEdit = false;

        if ($role == 'Super Admin') {
            $canEdit = true;
        }

        $userlist = User::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('type', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('role', 'like', '%' . $request->query('search') . '%');
            })
            ->where([
                'isVerified' => null,
                'isActive' => null,
            ])->orderBy('name', 'asc');
        })
        ->when($request->query('sort'), function ($query) use ($request) {
            $query->orderBy($request->query('sort'), 'asc');
        })
        ->when($request->query('type'), function ($query) use ($request) {
            $query->where('type', $request->query('type'));
        })
        ->where([
            'isVerified' => null,
            'isActive' => null,
        ])
        ->whereNot('role', 'Super Admin')
        ->with('userRole', 'userRole.discipline', 'userRole.program', 'userRole.institution');
        
        $count = $userlist->count();
        
        $userlist = $userlist->paginate(10)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/User-Request', [
            'user_list' => $userlist,
            'count' => $count,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search', 'sort', 'type']),
        ]);

    }



    // public function request() {
    //     return Inertia::render('Progmes/Admin/User-Request',[
    //         'user_list' => User::where('isVerified', null)
    //             ->orderBy('name', 'asc')->paginate(40)
    //             ->through(fn($account) => [
    //                 'id' => $account->id,
    //                 'name' => $account->name,
    //                 'email' => $account->email,
    //                 'type' => $account->type,
    //                 'avatar' => $account->avatar,
    //                 'role' => RoleModel::where('userId', $account->id)->value('role'),
    //                 'institution' => InstitutionModel::where('id', RoleModel::where('userId', $account->id)->value('institutionId'))->value('name'),
    //             ]),
    //         ]);
    // }
}
