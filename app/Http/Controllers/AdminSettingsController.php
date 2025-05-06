<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AdminSettingsModel;
use App\Models\User;
use App\Models\ProgramModel;
use App\Models\DisciplineModel;
use App\Models\ProgramAssignmentModel;
use App\Models\RoleModel;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = AdminSettingsModel::all();
        $supervisors = User::where('role', 'Education Supervisor')->where('isActive', 1)->orderBy('name', 'asc')->get();

        return Inertia::render('Admin/Settings', [
            'settings' => $settings->select('id', 'setting_key', 'setting_value'),
            'supervisor_list' => $supervisors,
        ]);
    }
    
    public function update(Request $request)
    {
        // dd($request);
        foreach($request->settings as $setting) {
            $settingModel = AdminSettingsModel::where('setting_key', $setting['setting_key'])->first();
            if ($settingModel) {
                $settingModel->update([
                    'setting_value' => $setting['setting_value'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function assign($es)
    {
        $supervisor = User::where('id', $es)->first();
        if (!$supervisor) {
            return redirect('/admin/settings')->with('failed', 'No user found.');
        }

        $assignedProgramIds = getUserAssignedProgramIds($supervisor->id);
        $assignedDisciplineIds = getUserDisciplineIds($supervisor->id);
        $programList = ProgramModel::whereIn('disciplineId', $assignedDisciplineIds)->orderby('program', 'asc')->orderBy('major', 'asc')->get();
        $disciplineList = DisciplineModel::orderBy('discipline', 'asc')->get();

        return Inertia::render('Admin/Program-Assignment', [
            'supervisor' => $supervisor,
            'programList' => $programList,
            'disciplineList' => $disciplineList,
            'assignedProgramIds' => $assignedProgramIds,
            'assignedDisciplineIds' => $assignedDisciplineIds,
        ]);
    }

    public function updateAssignedProgram(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'userId' => 'required',
        ]);
        
        $userId = $request->userId;
        $programIds = $request->programs;
        $user = User::where('id', $userId)->first();

        try {
            // Delete assignments that are not in the new list
            ProgramAssignmentModel::where('userId', $userId)
                ->whereNotIn('programId', $programIds)
                ->delete();
            
            // For each program ID in the request
            foreach ($programIds as $programId) {
                // Check if the assignment already exists
                $exists = ProgramAssignmentModel::where('userId', $userId)
                    ->where('programId', $programId)
                    ->exists();
                
                // If it doesn't exist, create it
                if (!$exists) {
                    ProgramAssignmentModel::create([
                        'userId' => $userId,
                        'programId' => $programId
                    ]);
                }
            }
            
            return redirect('/admin/settings')->with('success', 'Program assignments for '.$user->name.' have been successfully updated.');
        } catch (\Exception $e) {
            return back()->with('failed', 'Failed to update program assignments');
        }
    }

    public function updateAssignedDiscipline(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'userId' => 'required',
        ]);
        
        $userId = $request->userId;
        $disciplineIds = $request->disciplines;
        $user = User::where('id', $userId)->first();

        try {
            // Delete assignments in RoleModel that are not in the new list
            RoleModel::where('userId', $userId)
            ->whereNotIn('disciplineId', $disciplineIds)
            ->delete();
            
            // Delete assignments in ProgramAssignmentModel where 'programId' belongs to disciplines not included
            $excludedProgramIds = ProgramModel::whereIn('disciplineId', $disciplineIds)->pluck('id');
            ProgramAssignmentModel::where('userId', $userId)
            ->whereNotIn('programId', $excludedProgramIds)
            ->delete();

            // For each discipline ID in the request
            foreach ($disciplineIds as $disciplineId) {
            // Check if the discipline already exists
            $exists = RoleModel::where('userId', $userId)
                ->where('disciplineId', $disciplineId)
                ->exists();
            
            // If it doesn't exist, create it
            if (!$exists) {
                RoleModel::create([
                'userId' => $userId,
                'disciplineId' => $disciplineId,
                'role' => 'Education Supervisor',
                'isActive' => 1,
                ]);
            }
            }
            
            return redirect()->back()->with('success', 'Discipline successfully updated.');
        } catch (\Exception $e) {
            return back()->with('failed', 'Failed to update discipline assignments');
        }
    }
    
    public function getAssignedPrograms($id)
    {
        $assignedProgramIds = getUserAssignedProgramIds($id);

        
        return response()->json([
            'data' => ProgramModel::whereIn('id', $assignedProgramIds)->orderBy('program', 'asc')->orderBy('major', 'asc')->get(),
        ]);
    }
}
