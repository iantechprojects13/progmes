<?php

namespace App\Http\Controllers;

use App\Imports\CMOImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\DisciplineModel;
use App\Models\ProgramModel;

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
        }

        if (!$excelContent) {
            return redirect()->route('admin.cmo.create')->with('failed', 'Import failed!');
        } else {
            return Inertia::render('Progmes/Admin/CMO-Upload', [
                'file' => $excelContent,
                'discipline_list' => DisciplineModel::all(),
                'program_list' => ProgramModel::all(),
                'file_name' => $fileName,
            ]);
        }
    }
}
