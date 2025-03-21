<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationGPRModel;
use Inertia\Inertia;
use Auth;

class GPRController extends Controller
{
    public function index()
    {
        return Inertia::render('Application/GPR-View', [
            'items' => ApplicationGPRModel::all(),
        ]);
    }


    public function edit()
    {
        return Inertia::render('Application/GPR-Edit', [
            'items' => ApplicationGPRModel::all(),
        ]);
    }

    public function update(Request $request)
    {
        foreach($request->items as $item) {
            ApplicationGPRModel::where('id', $item['id'])->update([
                'item' => $item['item'],
            ]);
        }

        return redirect()->back()->with('success', 'Changes saved successfully.');
    }
}
