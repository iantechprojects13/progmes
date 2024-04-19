<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EvaluationFormModel;
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
            'visitDate' => $request->visitDate,
            'conforme' => $request->conforme,
            'conformeTitle' => $request->conformeTitle,
            'evaluatedBy' => $request->evaluatedBy,
            'evaluatedByTitle' => $request->evaluatedByTitle,
            'reviewedBy' => $request->reviewedBy,
            'reviewedByTitle' => $request->reviewedByTitle,
            'notedBy' => $request->notedBy,
            'notedByTitle' => $request->notedByTitle,
        ]);

        $complianceTool->save();

        return redirect()->back()->with('success', 'Changes saved.');
   }

   public function deficiencyReport($tool, $type) {
        
        $complianceTool = EvaluationFormModel::where('id', $tool)
        ->with(['item' => function ($toolQuery) {
            $toolQuery->where('evaluationStatus', 'Not complied');
        }, 'cmo', 'item.criteria', 'institution_program.program', 'institution_program.institution'])
        ->first();

        $data = [
            'tool' => $complianceTool,
        ];
        
        $fileName = time() . '.pdf';
        $report = Pdf::loadView('report.deficiency', $data);
        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create deficiency report.');
        }
   }

   public function monitoringReport($tool, $type) {

        $complianceTool = EvaluationFormModel::where('id', $tool)
        ->with(['item', 'cmo', 'item.criteria', 'institution_program.program', 'institution_program.institution'])
        ->first();

        $data = [
            'tool' => $complianceTool,
        ];
        
        $fileName = time() . '.pdf';
        $report = Pdf::loadView('report.monitoring', $data);
        
        if ($type == 'view') {
            return $report->stream($fileName);
        } else if ($type == 'download') {
            return $report->download($fileName);
        } else {
            return redirect()->back()->with('failed', 'Failed to create monitoring/evaluation report.');
        }

   }
}