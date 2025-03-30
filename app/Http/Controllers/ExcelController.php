<?php

namespace App\Http\Controllers;

use App\Imports\CMOImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\DisciplineModel;
use App\Models\ProgramModel;
use App\Models\CriteriaModel;
use App\Models\CMOModel;
use Auth;

class ExcelController extends Controller
{
    public function importExcel(Request $request) {
        $user = Auth::user();
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'Please select a file.',
            'file.mimes' => 'The file must be a type of: Excel or CSV.',
        ]);
        
        $excelContent = [];
        $fileName = '';
        
        if (request()->file('file')) {
            $excelContent = Excel::toArray(new CMOImport, request()->file('file'));
            $fileName = request()->file('file')->getClientOriginalName();
        } else {
            return redirect()->back()->with('failed', 'Import failed!');
        }

        // Check if the first row contains the expected headers
        if (empty($excelContent[0]) || count($excelContent[0]) === 0) {
            return redirect()->back()->with('failed', 'The uploaded file is empty!');
        }
        
        $firstRow = $excelContent[0][0];
        if (!isset($firstRow[0]) || !isset($firstRow[1]) || 
            strtolower(trim($firstRow[0])) !== 'area' || 
            !in_array(strtolower(trim($firstRow[1])), ['minimum requirement', 'minimum requirements'])
            ) {
            return redirect()->back()->with('failed', 'Invalid file format! The first row must contain "Area" and "Minimum Requirement" columns.');
        }
        
        $newCMO = CMOModel::create([
            'createdBy' => $user->id,
            'status' => 'Draft',
            'isActive' => 0,
        ]);
        
        foreach ($excelContent[0] as $index => $data) {
            if ($index === 0) {
                continue;
            }
            
            
            if (empty($data[0]) && empty($data[1])) {
                continue;
            }

            
            
            CriteriaModel::create([
                'cmoId' => $newCMO->id,
                'itemNo' => $index,
                'area' => $data[0],
                'minimumRequirement' => $data[1],
            ]);
        }
    
        return redirect()->route('admin.cmo.edit', ['id' => $newCMO]);
    }
    
}
