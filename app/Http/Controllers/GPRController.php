<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationGPRModel;
use App\Models\ApplicationItemModel;
use Inertia\Inertia;
use Auth;

class GPRController extends Controller
{
    public function index()
    {
        return Inertia::render('Application/GPR-View', [
            'items' => ApplicationGPRModel::where('isActive', 1)
            ->orderBy('no', 'asc')
            ->get(),
        ]);
    }


    public function edit()
    {
        return Inertia::render('Application/GPR-Edit', [
            'items' => ApplicationGPRModel::where('isActive', 1)
            ->orderBy('no', 'asc')
            ->get(),
        ]);
    }

    public function update(Request $request)
    {
        foreach($request->items as $item) {
            // Check if the item has a valid database ID or a temporary ID
            if (isset($item['id']) && is_numeric($item['id'])) {
                // Get the existing GPR record
                $existingGPR = ApplicationGPRModel::find($item['id']);
                
                if (!$existingGPR) {
                    continue; // Skip if record doesn't exist
                }
                
                // Check if there's an associated record in ApplicationItemModel
                $hasAssociatedRecord = ApplicationItemModel::where('gprId', $item['id'])->exists();
                
                if ($hasAssociatedRecord) {
                    // Check if the values are different from the existing record
                    if ($existingGPR->item != $item['item'] || $existingGPR->no != $item['no']) {
                        // Values are different, mark the old record as inactive
                        $existingGPR->update([
                            'isActive' => 0
                        ]);
                        
                        // Create a new record with the updated values
                        $newRecord = ApplicationGPRModel::create([
                            'item' => $item['item'],
                            'no' => $item['no'],
                            'isActive' => 1
                        ]);
                    }
                    // If values are the same, do nothing (keep the existing record)
                } else {
                    // No associated record, update existing item normally
                    $existingGPR->update([
                        'item' => $item['item'],
                        'no' => $item['no'],
                        'isActive' => 1,
                    ]);
                }
            } else {
                // Create new item (for temporary IDs like 'temp-timestamp')
                ApplicationGPRModel::create([
                    'item' => $item['item'],
                    'no' => $item['no'],
                    'isActive' => 1
                ]);
            }
        }

        return redirect()->back()->with('success', 'Changes saved successfully.');
    }

    public function delete($id)
    {
        // Check if there's an associated record in ApplicationItemModel
        $hasAssociatedRecord = ApplicationItemModel::where('gprId', $id)->exists();
        
        if ($hasAssociatedRecord) {
            // If associated records exist, just mark as inactive
            ApplicationGPRModel::where('id', $id)->update([
                'isActive' => 0
            ]);
            return redirect()->back()->with('success', 'This item has been temporarily removed from the list instead of deleted, as it is still referenced by other records.');
        } else {
            // No associated records, safe to delete
            ApplicationGPRModel::destroy($id);
            return redirect()->back()->with('success', 'Item deleted successfully.');
        }
    }
}
