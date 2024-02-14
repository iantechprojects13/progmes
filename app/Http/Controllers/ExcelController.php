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

class ExcelController extends Controller
{
    public function importExcel(Request $request) {

        $request->validate([
            'cmo_file' => 'required|mimes:xlsx,xls',
        ], [
            'cmo_file.required' => 'Please select an Excel file.',
            'cmo_file.mimes' => 'File must be in Excel format: .xlsx or .xls.',
        ]);

        $excelContent = [];
        $fileName ='';

        if (request()->file('cmo_file')) {
            $excelContent = Excel::toArray(new CMOImport, request()->file('cmo_file'));
            $fileName = request()->file('cmo_file')->getClientOriginalName();
        } else {
            $excelContent = null;
            return redirect()->route('admin.cmo.list')->with('failed', 'Import failed!');
        }

        $newCMO = CMOModel::create([
            'status' => 'draft',
            'isActive' => 0,
        ]);
        
        foreach ($excelContent[0] as $index => $data) {

            if ($index === 0) {
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
