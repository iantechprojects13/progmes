<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\User;
use App\Models\DisciplineModel;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\RoleModel;

class UserController extends Controller
{
    public function index() {
        return Inertia::render('Progmes/Admin/User-List',[
            'user_list' => User::
                where([
                    'isVerified' => 1,
                    'isActive' => 1,
                ])
                ->orderBy('name', 'asc')
                ->paginate(40)
                ->through(fn($account) => [
                    'id' => $account->id,
                    'name' => $account->name,
                    'email' => $account->email,
                    'type' => $account->type,
                    'avatar' => $account->avatar,
                    'role' => RoleModel::where('userId', $account->id)->value('role'),
                    'institution' => InstitutionModel::where('id', RoleModel::where('userId', $account->id)->value('institutionId'))->value('name'),
                ]),
            ]);
    }


    public function request() {
        return Inertia::render('Progmes/Admin/User-Request',[
            'user_list' => User::where('isVerified', null)
                ->orderBy('name', 'asc')->paginate(40)
                ->through(fn($account) => [
                    'id' => $account->id,
                    'name' => $account->name,
                    'email' => $account->email,
                    'type' => $account->type,
                    'avatar' => $account->avatar,
                    'role' => RoleModel::where('userId', $account->id)->value('role'),
                    'institution' => InstitutionModel::where('id', RoleModel::where('userId', $account->id)->value('institutionId'))->value('name'),
                ]),
            ]);
    }
}
