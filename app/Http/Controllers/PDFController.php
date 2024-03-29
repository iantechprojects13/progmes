<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EvaluationFormModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PDFController extends Controller
{
    public function generateDeficiencyReport(Request $request, $tool) {

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
        return $report->stream($fileName);

        // redirect()->back()->with('success', 'Deficiency report has been downloaded.');
   }
}