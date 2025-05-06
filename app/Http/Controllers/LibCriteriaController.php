<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibCriteriaModel;
use App\Models\LibEvaluationFormModel;
use App\Models\LibEvaluationItemModel;
use App\Models\AdminSettingsModel;
use App\Models\InstitutionModel;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Auth;

class LibCriteriaController extends Controller
{
    public function index(Request $request)
    {
        $canEdit = Auth::user()->role == 'Super Admin';
        $acadYear = getAcademicYear($request->query('academicyear'), Auth::user()->id);

        $libCriteria = LibCriteriaModel::where('isActive', 1)->orderBy('no', 'asc')->get();
        return Inertia::render('Admin/library/Library-CMO-View',
        [
            'items' => $libCriteria,
            'effectivity' => $acadYear,
            'library_cmo' => AdminSettingsModel::where('setting_key', 'library_cmo')->value('setting_value'),
            'canEdit' => $canEdit,
        ]);
    }

    public function edit()
    {
        $libCriteria = LibCriteriaModel::where('isActive', 1)->orderBy('no', 'asc')->get();
        return Inertia::render('Admin/library/Library-CMO-Edit',
        [
            'items' => $libCriteria,
            'library_cmo' => AdminSettingsModel::where('setting_key', 'library_cmo')->value('setting_value'),
        ]);
    }

    public function update(Request $request)
    {
        foreach($request->items as $item) {
            // Check if the item has a valid database ID or a temporary ID
            if (isset($item['id']) && is_numeric($item['id'])) {
                // Get the existing record
                $existingItem = LibCriteriaModel::find($item['id']);
                
                if (!$existingItem) {
                    continue; // Skip if record doesn't exist
                }
                
                // Check if there's an associated record in LibEvaluationItemModel
                $hasAssociatedRecord = LibEvaluationItemModel::where('libCriteriaId', $item['id'])->exists();
                
                if ($hasAssociatedRecord) {
                    // Check if the values are different from the existing record
                    if ($existingItem->area != $item['area'] || 
                        $existingItem->minimumRequirement != $item['minimumRequirement'] || 
                        $existingItem->no != $item['no']) {
                        
                        // Values are different, mark the old record as inactive
                        $existingItem->update([
                            'isActive' => 0
                        ]);
                        
                        // Create a new record with the updated values
                        $newRecord = LibCriteriaModel::create([
                            'no' => $item['no'],
                            'area' => $item['area'],
                            'minimumRequirement' => $item['minimumRequirement'],
                            'isActive' => 1
                        ]);
                    }
                    // If values are the same, do nothing (keep the existing record)
                } else {
                    // No associated record, update existing item normally
                    $existingItem->update([
                        'no' => $item['no'],
                        'area' => $item['area'],
                        'minimumRequirement' => $item['minimumRequirement'],
                        'isActive' => 1
                    ]);
                }
            } else {
                // Create new item (for temporary IDs like 'temp-timestamp')
                LibCriteriaModel::create([
                    'no' => $item['no'],
                    'area' => $item['area'],
                    'minimumRequirement' => $item['minimumRequirement'],
                    'isActive' => 1
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Changes saved successfully.');
    }

    public function deleteItem($id)
    {
        $item = LibCriteriaModel::find($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    public function deploy(Request $request)
    {
        $validatedData = $request->validate([
            'effectivity' => 'required',
        ]);
        
        // Get all institutions that don't have an evaluation form with the same effectivity
        $institutions = InstitutionModel::query()
            ->whereNotExists(function ($query) use ($validatedData) {
                $query->select(DB::raw(1))
                    ->from('lib_evaluation_form')
                    ->whereRaw('lib_evaluation_form.institutionId = institution.id')
                    ->where('effectivity', $validatedData['effectivity']);
            })->get();
        
        foreach($institutions as $institution) {
            LibEvaluationFormModel::create([
                'institutionId' => $institution->id,
                'effectivity' => $validatedData['effectivity'],
                'status' => 'Deployed',
            ]);
        }
        
        return redirect()->back()->with('success', 'Compliance evaluation tool for library has been deployed.');
    }


}
