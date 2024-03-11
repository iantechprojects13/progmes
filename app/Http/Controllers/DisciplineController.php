<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\DisciplineModel;
use Inertia\Inertia;

class DisciplineController extends Controller
{
    public function index(Request $request) {

        $role = Auth::user()->role;
        $canEdit = false;

        if ($role == 'Super Admin') {
            $canEdit = true;
        }

        $disciplinelist = DisciplineModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where('discipline', 'like', '%' . $request->query('search') . '%');
        })->orderBy('discipline');
        $itemCount = $disciplinelist->count();
        $disciplinelist = $disciplinelist
        ->paginate(10)
        ->withQueryString();
        
        // ->paginate(10)
        // ->withQueryString();

        
        return Inertia::render('Progmes/Admin/Discipline', [
            'discipline_list' => $disciplinelist,
            'canEdit' => $canEdit,
            'filters' => $request->only(['search']),
            'count' => $itemCount,
        ]);

    }
    
    public function create() {
        return Inertia::render('Progmes/Admin/Discipline-Create');
    }

    public function edit($id) {
        $disciplineModel = DisciplineModel::find($id);

        if($disciplineModel) {
            return Inertia::render('Progmes/Admin/Discipline-Edit', [
                'discipline' => $disciplineModel,
            ]);
        }

        return redirect('/admin/program/discipline/')->with('failed', 'Discipline not found.');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'discipline' => 'required',
        ], [
            'discipline.required' => 'The discipline field is required.'
        ]);

        DisciplineModel::create([
            'discipline' => $validated['discipline'],
        ]);

        return redirect()->route('admin.discipline.list')->with('success', 'Discipline successfully added.');
    }

    public function update(Request $request) {
        $validated = $request->validate([
            'discipline' => 'required',
        ], [
            'discipline.required' => 'The discipline field is required.'
        ]);

        $discipline = DisciplineModel::find($request->id);

        if($discipline) {
            $discipline->update([
                'discipline' => $validated['discipline'],
            ]);
            $discipline->save();

            return redirect('/admin/program/discipline/')->with('success', 'Discipline successfully updated.');
        }

    }


    public function delete(Request $request) {

        $discipline = DisciplineModel::where('id', $request->id)->first();

        if($discipline) {
            DisciplineModel::destroy($request->id);
            return redirect()->back()->with('success', 'Deleted successfully.');
        }

        return redirect()->back()->with('failed', 'Failed to delete program.');
    }

}
