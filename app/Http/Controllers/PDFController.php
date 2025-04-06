<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EvaluationFormModel;
use App\Models\LibEvaluationFormModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Carbon\Carbon;

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

    public function deficiencyReport($tool, $orientation = 'portrait', $type) {
        
        $complianceTool = EvaluationFormModel::where('id', $tool)
        ->with(['item' => function ($toolQuery) {
            $toolQuery->where('evaluationStatus', 'Not complied');
        }, 'cmo', 'item.criteria', 'institution_program.program', 'institution_program.institution'])
        ->first();

        $data = [
            'tool' => $complianceTool,
        ];
        
        $fileName = 'Deficiency-Report-'. time() . '.pdf';
        $report = Pdf::loadView('report.deficiency', $data)->setPaper('a4', $orientation);
        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create deficiency report.');
        }
    }

    public function deficiencyReportForLibrary($tool, $orientation = 'portrait', $type) {
        
        $complianceTool = LibEvaluationFormModel::where('id', $tool)
        ->with(['item' => function ($toolQuery) {
            $toolQuery->where('evaluationStatus', 'Not complied');
        }, 'item.criteria', 'institution'])
        ->first();

        $data = [
            'tool' => $complianceTool,
        ];
        
        $fileName = 'Deficiency-Report-'. time() . '.pdf';
        $report = Pdf::loadView('report.deficiency-library', $data)->setPaper('a4', $orientation);
        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create deficiency report.');
        }
    }

    public function monitoringReport($tool, $orientation = 'portrait', $type) {

        $complianceTool = EvaluationFormModel::where('id', $tool)
        ->with(['item', 'cmo', 'item.criteria', 'institution_program.program', 'institution_program.institution'])
        ->first();

        $data = [
            'tool' => $complianceTool,
        ];
        
        $fileName = 'Monitoring-Report-'. time() . '.pdf';
        $report = Pdf::loadView('report.monitoring', $data)->setPaper('a4', $orientation);
        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create monitoring/evaluation report.');
        }
    }

    public function monitoringReportForLibrary($tool, $orientation = 'portrait', $type) {

        $complianceTool = LibEvaluationFormModel::where('id', $tool)
        ->with(['item', 'item.criteria', 'institution'])
        ->first();

        $data = [
            'tool' => $complianceTool,
        ];
        
        $fileName = 'Monitoring-Report-'. time() . '.pdf';
        $report = Pdf::loadView('report.monitoring-library', $data)->setPaper('a4', $orientation);
        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create monitoring/evaluation report.');
        }
    }
}