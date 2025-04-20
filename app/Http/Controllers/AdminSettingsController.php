<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AdminSettingsModel;
use App\Models\User;
use App\Models\ProgramModel;
use App\Models\ProgramAssignmentModel;

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

        $assignedPrograms = ProgramAssignmentModel::where('userId', $supervisor->id)->with('program')->get();
        $assignedProgramIds = getUserAssignedProgramIds($supervisor->id);
        $disciplineIds = getUserDisciplineIds($supervisor->id);
        $programList = ProgramModel::whereIn('disciplineId', $disciplineIds)->orderby('program', 'asc')->orderBy('major', 'asc')->get();

        return Inertia::render('Admin/Program-Assignment', [
            'supervisor' => $supervisor,
            'assignedPrograms' => $assignedPrograms,
            'programList' => $programList,
            'assignedProgramIds' => $assignedProgramIds,
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
            
            return redirect('/admin/settings')->with('success', 'Program assignments updated successfully.');
        } catch (\Exception $e) {
            return back()->with('failed', 'Failed to update program assignments');
        }
    }
    
    public function getAssignedPrograms($id)
    {
        $assignedProgramIds = getUserAssignedProgramIds($id);

        
        return response()->json([
            'data' => ProgramModel::whereIn('id', $assignedProgramIds)->get(),
        ]);
    }

    public function setAcademicYear(Request $request) {
        if ($request[0]) {
            $setting = AdminSettingsModel::where('id', 1)->first();

            if ($setting) {
                $setting->update([
                    'currentAcademicYear' => $request[0],
                ]);

                $setting->save();

                return redirect()->back()->with('success', $request[0] . ' is set as default academic year.');
            } else {
                return redirect()->back()->with('failed', 'Failed to set default academic year.');
            }
        } else {
            return redirect()->back()->with('failed', 'Failed to set default academic year.');
        }
    }
}
