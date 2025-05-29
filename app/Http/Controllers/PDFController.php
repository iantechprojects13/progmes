<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EvaluationFormModel;
use App\Models\LibEvaluationFormModel;
use App\Models\AdminSettingsModel;
use App\Models\RoleModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Auth;

class PDFController extends Controller
{

    public function generateReport(Request $request) {
        
        $complianceTool = EvaluationFormModel::where('id', $request->id)->first();

        if (!$complianceTool) {
            redirect()->back()->with('failed', 'Failed to save data.');
        }

        $complianceTool->update([
            'evaluationDate' => $request->evaluationDate,
            'conforme1' => $request->conforme1,
            'conforme1Title' => $request->conforme1Title,
            'conforme2' => $request->conforme2,
            'conforme2Title' => $request->conforme2Title,
            'evaluatedBy' => $request->evaluatedBy,
            'evaluatedByTitle' => $request->evaluatedByTitle,
            'reviewedBy' => $request->reviewedBy,
            'reviewedByTitle' => $request->reviewedByTitle,
            'notedBy' => $request->notedBy,
            'notedByTitle' => $request->notedByTitle,
        ]);
        
        $complianceTool->save();

        return redirect()->back()->with('success', 'Changes saved successfully.');;
   }

   public function generateReportForLibrary(Request $request) {
        
        $complianceTool = LibEvaluationFormModel::where('id', $request->id)->first();

        if (!$complianceTool) {
            redirect()->back()->with('failed', 'Failed to save data.');
        }

        $complianceTool->update([
            'evaluationDate' => $request->evaluationDate,
            'conforme1' => $request->conforme1,
            'conforme1Title' => $request->conforme1Title,
            'conforme2' => $request->conforme2,
            'conforme2Title' => $request->conforme2Title,
            'evaluatedBy' => $request->evaluatedBy,
            'evaluatedByTitle' => $request->evaluatedByTitle,
            'reviewedBy' => $request->reviewedBy,
            'reviewedByTitle' => $request->reviewedByTitle,
            'notedBy' => $request->notedBy,
            'notedByTitle' => $request->notedByTitle,
        ]);

        $complianceTool->save();

        return redirect()->back()->with('success', 'Changes saved successfully.');;
    }

    public function deficiencyReport($tool, $orientation = 'landscape', $type) {
        
        $complianceTool = EvaluationFormModel::where('id', $tool)
        ->with(['item' => function ($toolQuery) {
            $toolQuery->where('evaluationStatus', 'Not complied');
        }, 'cmo', 'item.criteria', 'institution_program.program', 'institution_program.institution'])
        ->first();

        if (!$this->canViewReport($complianceTool)) {
            $fileName = 'Report-Access-Denied-'. time() . '.pdf';
            $report = Pdf::loadView('report.access-denied')->setPaper('a4', $orientation);
        } else {
            $data = [
                'tool' => $complianceTool,
            ];
            
            $fileName = 'Deficiency-Report-'. time() . '.pdf';
            $report = Pdf::loadView('report.deficiency', $data)->setPaper('a4', $orientation);
        }

        
        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create deficiency report.');
        }
    }

    public function deficiencyReportForLibrary($tool, $orientation = 'landscape', $type) {
        
        $complianceTool = LibEvaluationFormModel::where('id', $tool)
        ->with(['item' => function ($toolQuery) {
            $toolQuery->where('evaluationStatus', 'Not complied');
        }, 'item.criteria', 'institution'])
        ->first();

        if (!$this->canViewLibraryReport($complianceTool)) {
            $fileName = 'Report-Access-Denied-'. time() . '.pdf';
            $report = Pdf::loadView('report.access-denied')->setPaper('a4', $orientation);
        } else {
            $data = [
                'tool' => $complianceTool,
                'cmo' => AdminSettingsModel::where('setting_key', 'library_cmo')->value('setting_value'),
            ];
            
            $fileName = 'Deficiency-Report-'. time() . '.pdf';
            $report = Pdf::loadView('report.deficiency-library', $data)->setPaper('a4', $orientation);
        }
        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create deficiency report.');
        }
    }

    public function monitoringReport($tool, $orientation = 'landscape', $type) {

        $complianceTool = EvaluationFormModel::where('id', $tool)
        ->with(['item', 'cmo', 'item.criteria', 'institution_program.program', 'institution_program.institution'])
        ->first();

        if (!$complianceTool) {
            return redirect()->back()->with('failed', 'Compliance evaluation tool not found.');
        }

        $userType = Auth::user()->type;
        $preparedBy = null;

        if ($userType == 'HEI') {
            $result = EvaluationFormModel::query()
                ->where('id', $complianceTool->id)
                ->selectRaw("
                    CASE 
                        WHEN LOWER(conforme1Title) LIKE '%program head%' OR LOWER(conforme1Title) LIKE '%ph%' THEN conforme1
                        WHEN LOWER(conforme2Title) LIKE '%program head%' OR LOWER(conforme2Title) LIKE '%ph%' THEN conforme2
                        ELSE NULL
                    END as prepared_by
                ")
                ->first();
            $preparedBy = $result ? $result->prepared_by : null;
        }

        if (!$this->canViewReport($complianceTool)) {
            $fileName = 'Report-Access-Denied-'. time() . '.pdf';
            $report = Pdf::loadView('report.access-denied')->setPaper('a4', $orientation);
        } else {
            $data = [
                'tool' => $complianceTool,
                'userType' => $userType,
                'preparedBy' => $preparedBy,
                'preparedByTitle' => 'Program Head',
            ];
            
            $fileName = 'Program-Monitoring-Report-'. time() . '.pdf';
            $report = Pdf::loadView('report.monitoring', $data)->setPaper('a4', $orientation);
        }

       
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create monitoring/evaluation report.');
        }
    }

    public function monitoringReportForLibrary($tool, $orientation = 'landscape', $type) {

        $complianceTool = LibEvaluationFormModel::where('id', $tool)
        ->with(['item', 'item.criteria', 'institution'])
        ->first();
        
        if (!$complianceTool) {
            return redirect()->back()->with('failed', 'Library compliance evaluation tool not found.');
        }
        
        $userType = Auth::user()->type;
        $preparedBy = null;

        if ($userType == 'HEI') {
            $result = LibEvaluationFormModel::query()
                ->where('id', $complianceTool->id)
                ->selectRaw("
                    CASE 
                        WHEN LOWER(conforme1Title) LIKE '%librarian%' OR LOWER(conforme1Title) LIKE '%lib%' THEN conforme1
                        WHEN LOWER(conforme2Title) LIKE '%librarian%' OR LOWER(conforme2Title) LIKE '%lib%' THEN conforme2
                        ELSE NULL
                    END as prepared_by
                ")
                ->first();
            $preparedBy = $result ? $result->prepared_by : null;
        }

        if (!$this->canViewLibraryReport($complianceTool)) {
            $fileName = 'Report-Access-Denied-'. time() . '.pdf';
            $report = Pdf::loadView('report.access-denied')->setPaper('a4', $orientation);
        } else {
            $data = [
                'tool' => $complianceTool,
                'userType' => $userType,
                'preparedBy' => $preparedBy,
                'preparedByTitle' => 'Librarian',
                'cmo' => AdminSettingsModel::where('setting_key', 'library_cmo')->value('setting_value'),
            ];
            
            $fileName = 'Library-Monitoring-Report-'. time() . '.pdf';
            $report = Pdf::loadView('report.monitoring-library', $data)->setPaper('a4', $orientation);
        }

        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create monitoring/evaluation report.');
        }
    }

    public function canViewReport($tool) {
        $user = Auth::user();

        if ($user->type == 'HEI') {
            $assignedRole = RoleModel::where('userId', $user->id)->where('isActive', 1)->with('discipline', 'program', 'institution')->first();
            
            if(!($assignedRole->institutionId == $tool->institution_program->institution->id)) {
                return false;
            }
            
            if ($user->role == 'Program Head') {
                if (!($tool->institution_program->program->program == $assignedRole->program->program)) {
                    return false;
                }
            } else if ($user->role == 'Program Coordinator') {
                if (!($tool->institution_program->program->id == $assignedRole->program->id)) {
                    return false;
                }
            } else if ($user->role == 'Dean') {
                if (!($tool->institution_program->program->disciplineId == $assignedRole->discipline->id)) {
                    return false;
                }
            } 
        } else if ($user->type == 'CHED') {
            if ($user->role == 'Education Supervisor') {
                $assignedPrograms = getUserAssignedProgramIds($user->id);
                if (!in_array($tool->institution_program->program->id, $assignedPrograms)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function canViewLibraryReport($tool) {
        $user = Auth::user();

        if ($user->type == 'HEI') {
            $assignedRole = RoleModel::where('userId', $user->id)->where('isActive', 1)->with('discipline', 'program', 'institution')->first();
            
            if(!($assignedRole->institutionId == $tool->institution->id)) {
                return false;
            }
            
            if ($user->role == 'Librarian' || $user->role == 'Vice-President for Academic Affairs' || $user->role == 'Quality Assurance Officer') {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }
}