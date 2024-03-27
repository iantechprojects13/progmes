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
        $canDelete = false;
        $canAdd = false;
        $show = $request->query('show') ? $request->query('show') : 25;

        if ($role == 'Super Admin') {
            $canEdit = true;
            $canDelete = true;
            $canAdd = true;
        }

        if ($role == 'Admin') {
            $canEdit = true;
            $canAdd = true;
        }

        $disciplinelist = DisciplineModel::query()
        ->when($request->query('search'), function ($query) use ($request) {
            $query->where('discipline', 'like', '%' . $request->query('search') . '%');
        })->orderBy('discipline')
        ->with('institution_program')
        ->paginate($show)
        ->withQueryString();

        
        return Inertia::render('Progmes/Admin/Discipline', [
            'discipline_list' => $disciplinelist,
            'canEdit' => $canEdit,
            'canAdd' => $canAdd,
            'canDelete' => $canDelete,
            'filters' => $request->only(['search']) + ['show' => $show ],
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


    public function delete($discipline) {

        $disciplineModel = DisciplineModel::find($discipline);

        if(!$disciplineModel) {
           return redirect()->back()->with('failed', 'Failed to delete program.');
        }
        
        $disciplineModel->institution_program()->delete();
        $disciplineModel->program()->delete();
        $disciplineModel->delete();

        return redirect()->back()->with('success', 'Deleted successfully.');
    }

}
