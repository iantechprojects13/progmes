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

use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;


class RegistrationController extends Controller
{   
    // select account type render page
    public function index () {
        return Inertia::render('Progmes/Auth/Register-Select');
    }

    // ched registration render page
    public function accountCHED () {
        return Inertia::render('Progmes/Auth/Register-CHED', [
            'discipline_list' => DisciplineModel::select('id', 'discipline')->get(),
        ]);
    }

    // hei registration render page
    public function accountHEI () {
        return Inertia::render('Progmes/Auth/Register-HEI', [
            'institution_list' => InstitutionModel::select('id', 'name')->get(),
            'discipline_list' => DisciplineModel::select('id', 'discipline')->orderBy('discipline', 'asc')->get(),
            'program_list' => ProgramModel::select('id','disciplineId', 'program', 'major')->orderBy('disciplineId', 'asc')->get(),
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
                    } elseif (in_array($request->input('discipline'), $value)) {
                        $fail('Discipline has repeated values.');
                    }
                },
            ],
        ];

        if ($request->input('role') !== 'Education Supervisor') {
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
            $user->save();
            
            // create role
            if ($roleValue && $disciplineValue && $otherDisciplineValue) {

                RoleModel::create([
                    'userId' => Auth::user()->id,
                    'role' => $roleValue,
                    'disciplineId' => $disciplineValue,
                ]);

                foreach($otherDisciplineValue as $discipline) {
                    RoleModel::create([
                        'userId' => Auth::user()->id,
                        'role' => $roleValue,
                        'disciplineId' => $discipline,
                    ]);
                }

            } elseif ($roleValue && $disciplineValue) {
                RoleModel::create([
                    'userId' => Auth::user()->id,
                    'role' => $roleValue,
                    'disciplineId' => $disciplineValue,
                ]);
            } elseif ($roleValue) {
                RoleModel::create([
                    'userId' => Auth::user()->id,
                    'role' => $roleValue,
                ]);
            } else {
                return redirect()->back()->with('failed', 'Unable to register.');
            }

            return redirect()->route('register.pending')->with('success', 'Registration successful.');
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

        if ($request->input('role') == 'Dean') {
            $rules['discipline'] = 'required';
        }
        if ($request->input('role') == 'Program Head') {
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
            $user->save();

            // create role
            if ($institutionValue && $roleValue && $programValue) {

                RoleModel::create([
                    'userId' => Auth::user()->id,
                    'institutionId' => $institutionValue,
                    'role' => $roleValue,
                    'programId' => $programValue,
                ]);

            } elseif ($institutionValue && $roleValue && $disciplineValue) {
                RoleModel::create([
                    'userId' => Auth::user()->id,
                    'institutionId' => $institutionValue,
                    'role' => $roleValue,
                    'disciplineId' => $disciplineValue,
                ]);
            } elseif ($institutionValue && $roleValue) {
                RoleModel::create([
                    'userId' => Auth::user()->id,
                    'institutionId' => $institutionValue,
                    'role' => $roleValue,
                ]);
            } else {
                return redirect()->back()->with('failed', 'Unable to register.');
            }

            return redirect()->route('register.pending')->with('success', 'Registration successful.');
        }
    
    }

    public function pending() {
        return Inertia::render('Progmes/Auth/Register-Success');
    }


    public function accept(User $user) {

        $acceptedUser = User::where('id', $user->id)->first();

        $acceptedUser->update([
            'isVerified'=> 1,
            'isActive' => 1
        ]);

        $acceptedUser->save();

        // try {
        //     Mail::to($acceptedUser->email)->send(new NotificationEmail($acceptedUser->name));
        // } catch (\Exception $e) {
        //     Log::error('Email sending failed: ' . $e->getMessage());
        // }
        

        return redirect()->back()->with('success', $acceptedUser->name .'\'s registration has been approved.');
    }

    public function reject(User $user) {

        $rejectedUser = User::where('id', $user->id)->first();

        $rejectedUser->update(['isVerified'=> 0, 'isActive' => 0]);
        $rejectedUser->save();

        return redirect()->back()->with('success', $rejectedUser->name .'\'s registration has been rejected.');
    }

}





