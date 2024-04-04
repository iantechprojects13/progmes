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

    public function generateDeficiencyReport(Request $request) {
        
        $complianceTool = EvaluationFormModel::where('id', $request->id)->first();

        $complianceTool->update([
            'visitDate' => $request->visitDate,
            'evaluatedBy' => $request->evaluatedBy,
            'evaluatedByTitle' => $request->evaluatedByTitle,
            'reviewedBy' => $request->reviewedBy,
            'reviewedByTitle' => $request->reviewedByTitle,
            'notedBy' => $request->notedBy,
            'notedByTitle' => $request->notedByTitle,
        ]);

        return redirect()->back()->with('generated', 'Changes saved.');
   }

    public function downloadDeficiencyReport($tool) {

        $complianceTool = EvaluationFormModel::where('id', $tool)
        ->with(['item' => function ($toolQuery) {
            $toolQuery->where('evaluationStatus', 'Not complied');
        },'item.criteria', 'institution_program.program', 'institution_program.institution'])
        ->first();
        
        // dd($complianceTool);

       $data = [
            'tool' => $complianceTool,
       ];
        
        // return view('report.deficiency', $data);
        

        $fileName = time() . '.pdf';
        $report = Pdf::loadView('report.deficiency', $data);
        
        return $report->download($fileName);

   }


   public function viewDeficiencyReport($tool) {
        
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
        
        





        
        return $report->stream($fileName);

   }
}