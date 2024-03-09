<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\User;
use App\Models\DisciplineModel;
use App\Models\InstitutionModel;
use App\Models\RoleModel;
use App\Models\ProgramModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Mail;
// use App\Mail\NotificationEmail;


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

    
    public function accountHEI () {
        return Inertia::render('Progmes/Auth/Register-HEI', [
            'institution_list' => InstitutionModel::select('id', 'name')->get(),
            'discipline_list' => DisciplineModel::select('id', 'discipline')->orderBy('discipline', 'asc')->get(),
            'program_list' => ProgramModel::select('id','disciplineId', 'program', 'major')->orderBy('program', 'asc')->get(),
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


    public function accept(User $user) {

        $acceptedUser = User::where('id', $user->id)->first();
        $roles = RoleModel::where('userId', $acceptedUser->id)->get();

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

        

        // try {
        //     Mail::to($acceptedUser->email)->send(new NotificationEmail($acceptedUser->name));
        // } catch (\Exception $e) {
        //     Log::error('Email sending failed: ' . $e->getMessage());
        // }
        

        return redirect()->back()->with('success', $acceptedUser->name .'\'s registration has been approved.');
    }

    public function reject(User $user) {

        $rejectedUser = User::where('id', $user->id)->first();
        $roles = RoleModel::where('userId', $rejectedUser->id)->get();

        $rejectedUser->update(['isVerified'=> 0, 'isActive' => 0]);
        $rejectedUser->save();

        foreach ($roles as $role) {
            $role->update([
                'isActive' => 0,
            ]);
            $role->save();
        }

        return redirect()->back()->with('success', $rejectedUser->name .'\'s registration has been rejected.');
    }




    //View account
    public function viewMyAccount($id) {

        $user = Auth::user();
        $canChangeRole = true;
        $roles = [];
        $hasDiscipline = false;
        $hasProgram = false;
        $hasInstitution = false;

        if ($id != $user->id) {
            return redirect('/unauthorized');
        }

        if ($user->role == 'Super Admin') {
            $canChangeRole = false;
        }

        if ($user->role == 'Education Supervisor') {
            $roles = RoleModel::where(['userId'=> $user->id, 'isActive' => 1])->with('discipline')->get();
            if($roles) {
                $hasDiscipline = true;
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
            'canChangeRole' => $canChangeRole,
            'hasDiscipline' => $hasDiscipline,
            'hasProgram' => $hasProgram,
            'hasInstitution' => $hasInstitution,
        ]);
    }

}





