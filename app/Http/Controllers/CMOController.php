<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CMOModel;
use App\Models\CriteriaModel;
use App\Models\DisciplineModel;
use App\Models\ProgramModel;

class CMOController extends Controller
{
    public function index() {
        return Inertia::render('Progmes/Admin/CMO',[
            'cmo_list' => CMOModel::paginate(50)
                ->through(fn($cmo) => [
                    'id' => $cmo->id,
                    'number' => $cmo->number,
                    'series' => $cmo->series,
                    'version' => $cmo->version,
                    'discipline' => DisciplineModel::where('id', $cmo->disciplineId)->value('discipline'),
                    'program' => ProgramModel::where('id', $cmo->programId)->value('program'),
                ]),
            ]);
    }

    public function create() {
        return Inertia::render('Progmes/Admin/CMO-Create');
    }

    public function store(Request $request) {
        $cmoCreated = CMOModel::create([
            'disciplineId' => $request->discipline,
            'programId' => $request->program,
            'number' => $request->number,
            'series' => $request->series,
            'version' => $request->version,
        ]);

        $id = $cmoCreated->id;
        
        foreach ($request->area as $index => $item) {
            if($index !=0) {
                CriteriaModel::create([
                    'cmoId' => $id,
                    'itemNo' => $index,
                    'area' => $request->area[$index],
                    'minimumRequirement' => $request->minReq[$index],
                ]);
            }
        }

        return redirect()->route('admin.cmo.list');

    }

    public function view(CMOModel $cmo) {
        return Inertia::render('Progmes/Admin/CMO-View',[
            'criteria' => CriteriaModel::where('cmoId', $cmo->id)
                ->get()
                ->map(fn($cmo) => [
                    'id' => $cmo->id,
                    'area' => $cmo->area,
                    'minimumRequirement' => $cmo->minimumRequirement,
                ]),
            'cmo' => 'CMO No.'.$cmo->number.' Series of '.$cmo->series,
            ]);
    }
}
