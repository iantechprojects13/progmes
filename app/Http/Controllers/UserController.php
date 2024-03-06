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

        if ($role == 'Super Admin') {
            $canEdit = true;
        }

        $userlist = User::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->query('search') . '%');
        })
        ->when($request->query('sort'), function ($query) use ($request) {
            $query->orderBy($request->query('sort'), 'asc');
        })
        ->when($request->query('type'), function ($query) use ($request) {
            $query->where('type', $request->query('type'));
        })
        ->when($role == 'Super Admin', function ($query) {
            $query->whereNot('role', 'Super Admin');
        })
        ->when($role != 'Super Admin' || $role != 'Education Supervisor', function ($query) {
            $query->whereNot('role', 'Super Admin');
        })
        ->where([
            'isVerified' => 1,
            'isActive' => 1,
        ])
        ->with('userRole')
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/User-List', [
            'user_list' => $userlist,
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
            $query->where('name', 'like', '%' . $request->query('search') . '%');
        })
        ->when($request->query('sort'), function ($query) use ($request) {
            $query->orderBy($request->query('sort'), 'asc');
        })
        ->when($request->query('type'), function ($query) use ($request) {
            $query->where('type', $request->query('type'));
        })
        ->when($role == 'Super Admin', function ($query) {
            $query->whereNot('role', 'Super Admin');
        })
        ->when($role != 'Super Admin' || $role != 'Education Supervisor', function ($query) {
            $query->whereNot('role', 'Super Admin')
            ->orWhere('role', null);
        })
        ->where([
            'isVerified' => null,
            'isActive' => null,
        ])
        ->with('userRole')
        ->paginate(20)
        ->withQueryString();

        return Inertia::render('Progmes/Admin/User-Request', [
            'user_list' => $userlist,
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
