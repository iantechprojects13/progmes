<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\User;
use App\Models\DisciplineModel;
use App\Models\InstitutionModel;
use App\Models\InstitutionProgramModel;
use App\Models\RoleModel;
use App\Models\ProgramModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Throwable;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;


class RegistrationController extends Controller
{   
    
    public function index () {
        return Inertia::render('Progmes/Auth/Register-Select');
    }

    
    public function accountCHED () {
        return Inertia::render('Progmes/Auth/Register-CHED', [
            'discipline_list' => DisciplineModel::select('id', 'discipline')->orderBy('discipline', 'asc')->get(),
        ]);
    }
    
    
    public function accountHEI (Request $request) {
        
        $discipline_list = [];
        $program_list = [];

        if($request->institution) {
            $program_list = InstitutionProgramModel::where('institutionId', $request->institution)->with('program', 'program.discipline')->get();

            foreach ($program_list as $program) {

                $disciplineId = $program->program->discipline->id;
                
                if (!in_array($disciplineId, array_column($discipline_list, 'id'))) {
                    $discipline = DisciplineModel::where('id', $disciplineId)->first();

                    if ($discipline) {
                        array_push($discipline_list, $discipline);
                    }
                }
            }
        }
        
        return Inertia::render('Progmes/Auth/Register-HEI', [
            'institution_list' => InstitutionModel::orderBy('name', 'asc')->get(),
            'discipline_list' => $discipline_list,
            'program_list' => $program_list,
            'institution' => $request->institution ? $request->institution : null,
        ]);
    }
    
    // register ched account
    public function registerCHED (Request $request) {


    
        $rules = [
            'role' => 'required',
            'discipline' => 'required',
            'other_discipline' => [
                'array',
                function ($attribute, $value, $fail) use ($request) {
                    if (count($value) !== count(array_unique($value))) {
                        $fail('Discipline has repeated values.');
                    } elseif (in_array($request->discipline, $value)) {
                        $fail('Discipline has repeated values.');
                    }
                },
            ],
        ];

        if ($request->role !== 'Education Supervisor') {
            $rules['discipline'] = 'nullable';
            $rules['other_discipline'] = 'nullable';
        }

        $validator = Validator::make($request->all(), $rules, [
            'role.required' => 'The role field is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $roleValue = $validator->fails() ? null : $validator->validated()['role'];
            $disciplineValue = $validator->fails() ? null : $validator->validated()['discipline'];
            $otherDisciplineValue = $validator->fails() ? null : $validator->validated()['other_discipline'];
            $user = Auth::user();

            // update user type to 'CHED'
            $user->type = 'CHED';
            $user->role = $roleValue;
            $user->save();
            
            // create role
            if ($roleValue) {

                RoleModel::create([
                    'userId' => Auth::user()->id,
                    'role' => $roleValue,
                    'disciplineId' => $disciplineValue,
                ]);

                if ($otherDisciplineValue) {
                    foreach($otherDisciplineValue as $discipline) {
                        RoleModel::create([
                            'userId' => Auth::user()->id,
                            'role' => $roleValue,
                            'disciplineId' => $discipline,
                        ]);
                    }
                }

            } else {
                return redirect()->back()->with('failed', 'Unable to register.');
            }
            return redirect()->route('register.pending');
        }
    
    }


    // register hei account
    public function registerHEI (Request $request) {
        $rules = [
            'institution' => 'required',
            'role' => 'required',
            'discipline' => 'nullable',
            'program' => 'nullable',
        ];

        if ($request->role == 'Dean') {
            $rules['discipline'] = 'required';
        }

        if ($request->role == 'Program Head') {
            $rules['program'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, [
            'institution.required' => 'The institution field is required.',
            'role.required' => 'The role field is required.',
            'discipline.required' => 'The discipline field is required.',
            'program.required' => 'The program field is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $institutionValue = $validator->fails() ? null : $validator->validated()['institution'];
            $roleValue = $validator->fails() ? null : $validator->validated()['role'];
            $disciplineValue = $validator->fails() ? null : $validator->validated()['discipline'];
            $programValue = $validator->fails() ? null : $validator->validated()['program'];
            $user = Auth::user();

            // update user type to 'HEI'
            $user->type = 'HEI';
            $user->role = $roleValue;
            $user->save();

            // create role
            if ($institutionValue && $roleValue) {
                RoleModel::create([
                    'userId' => Auth::user()->id,
                    'institutionId' => $institutionValue,
                    'role' => $roleValue,
                    'disciplineId' => $disciplineValue ? $disciplineValue : null,
                    'programId' => $programValue ? $programValue : null,
                ]);
                
            } else {
                return redirect()->back()->with('failed', 'Unable to register.');
            }

            return redirect()->route('register.pending');
        }
    }

    public function pending() {
        return Inertia::render('Progmes/Auth/Register-Success');
    }

    public function deactivate(User $user) {
        $user = User::where('id', $user->id)->first();
        
        $user->update([
            'isActive' => 0,
        ]);

        $user->save();

        return redirect()->back()->with('success', $user->name .'\'s account has been deactivated.');
    }

    public function activate(User $user) {
        $user = User::where('id', $user->id)->first();
        
        $user->update([
            'isActive' => 1,
        ]);

        $user->save();

        return redirect()->back()->with('success', $user->name .'\'s account has been activated.');
    }


    public function accept(User $user) {

        $acceptedUser = User::where('id', $user->id)->first();
        $roles = RoleModel::where('userId', $acceptedUser->id)->where('isActive', null)->get();

        $acceptedUser->update([
            'isVerified'=> 1,
            'isActive' => 1,
        ]);
        $acceptedUser->save();


        foreach ($roles as $role) {
            $role->update([
                'isActive' => 1,
            ]);
            $role->save();
        }

        try {
            Mail::to($acceptedUser->email)->send(new NotificationEmail($acceptedUser->name));
        } catch (Throwable $thr) {
            return redirect()->back()->with('failed',  $thr->getMessage());
        }

        return redirect()->back()->with('success', $acceptedUser->name .'\'s registration has been approved. Notification email has been sent.');
    }

    public function reject(User $user) {

        $rejectedUser = User::where('id', $user->id)->first();

        $rejectedUser->userRole()->delete();
        $rejectedUser->delete();
        
        return redirect()->back()->with('success', $rejectedUser->name .'\'s registration has been rejected.');
    }

    public function destroy(User $user) {

        $deleteUser = User::where('id', $user->id)->first();

        $deleteUser->userRole()->delete();
        $deleteUser->delete();
        
        return redirect()->back()->with('success', 'User has been deleted.');
    }




    //View account
    public function viewMyAccount($id) {

        $user = Auth::user();
        $roles = [];
        $hasDiscipline = false;
        $hasProgram = false;
        $hasInstitution = false;

        if ($id != $user->id) {
            return redirect('/unauthorized');
        }

        if ($user->role == 'Education Supervisor') {
            $roles = RoleModel::where(['userId'=> $user->id, 'isActive' => 1])->with('discipline')->get();
            if($roles) {
                $hasDiscipline = true;
            }
        }

        if ($user->role == 'Vice-President for Academic Affairs') {
            $roles = RoleModel::where(['userId' => $user->id, 'isActive' => 1])->with('institution')->get();
            if($roles) {
                $hasInstitution = true;
            }
        }

        if ($user->role == 'Dean') {
            $roles = RoleModel::where(['userId' => $user->id, 'isActive' => 1])->with('discipline', 'institution')->get();
            if($roles) {
                $hasDiscipline = true;
                $hasInstitution = true;
            }
        }

        if ($user->role == 'Program Head') {
            $roles = RoleModel::where(['userId' => $user->id, 'isActive' => 1])->with('program', 'institution')->get();

            if($roles) {
                $hasProgram = true;
                $hasInstitution = true;
            }
        }
        
        
        return Inertia::render('Progmes/Auth/Account', [
            'profile' => Auth::user(),
            'roles' => $roles,
            'hasDiscipline' => $hasDiscipline,
            'hasProgram' => $hasProgram,
            'hasInstitution' => $hasInstitution,
        ]);
    }


    public function viewUserProfile(User $user) {

        if (!$user) {
            return redirect('/admin/users/list')->with('error', 'No user found.');
        }

        $userRole = Auth::user()->role;
        $canChangeRole = false;
        $roles = [];
        $hasDiscipline = false;
        $hasProgram = false;
        $hasInstitution = false;

        if ($userRole == 'Super Admin') {
            $canChangeRole = true;
        }

        if ($user->role == 'Education Supervisor') {
            $roles = RoleModel::where(['userId'=> $user->id, 'isActive' => 1])->with('discipline')->get();
            if($roles) {
                $hasDiscipline = true;
            }
        }
        
        if ($user->role == 'Vice-President for Academic Affairs') {
            $roles = RoleModel::where(['userId' => $user->id, 'isActive' => 1])->with('institution')->get();
            if($roles) {
                $hasInstitution = true;
            }
        }

        if ($user->role == 'Dean') {
            $roles = RoleModel::where(['userId' => $user->id, 'isActive' => 1])->with('discipline', 'institution')->get();
            if($roles) {
                $hasDiscipline = true;
                $hasInstitution = true;
            }
        }

        if ($user->role == 'Program Head') {
            $roles = RoleModel::where(['userId' => $user->id, 'isActive' => 1])->with('program', 'institution')->get();

            if($roles) {
                $hasProgram = true;
                $hasInstitution = true;
            }
        }
        

        return Inertia::render('Progmes/Admin/Profile-View', [
            'profile' => $user,
            'roles' => $roles,
            'canChangeRole' => $canChangeRole,
            'hasDiscipline' => $hasDiscipline,
            'hasProgram' => $hasProgram,
            'hasInstitution' => $hasInstitution,
        ]);
    }

    function changeRole(Request $request) {
        $user = User::where('id', $request->userId)->first();
        
        $roles = RoleModel::where('userId', $user->id)->where('isActive', 1)->get();

        $user->update([
            'role' => null,
            'type' => null,
            'isActive' => null,
            'isVerified' => null,
        ]);
        $user->save();

        foreach($roles as $role) {
            $role->update([
                'isActive' => 0,
            ]);
            $role->save();
        }
    }

}





