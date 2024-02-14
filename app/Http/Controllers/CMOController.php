<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CMOModel;
use App\Models\CriteriaModel;
use App\Models\DisciplineModel;
use App\Models\ProgramModel;
use App\Http\Requests\CMORequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CMOController extends Controller
{
    public function index($list = 'published') {

        return Inertia::render('Progmes/Admin/CMO',[
        'cmo_list' => CMOModel::where('status', $list)
            ->paginate(50)
            ->through(fn($cmo) => [
                'id' => $cmo->id,
                'number' => $cmo->number,
                'series' => $cmo->series,
                'version' => $cmo->version,
                'status' => $cmo->status,
                'active' => $cmo->isActive,
                'modified' => Carbon::parse($cmo->updated_at)->format('M d, Y'),
                'discipline' => DisciplineModel::where('id', $cmo->disciplineId)->value('discipline'),
                'program' => ProgramModel::where('id', $cmo->programId)->value('program'),
            ]),
        'listItems' => $list,
        ]);
    }

    public function create() {
        return Inertia::render('Progmes/Admin/CMO-Create');
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

    public function edit(Request $request) {
        return Inertia::render('Progmes/Admin/CMO-Edit', [
            'cmo' => CMOModel::where('id', $request->id)->with('criteria')->first(),
            'discipline_list' => DisciplineModel::all(),
            'program_list' => ProgramModel::all(),
        ]);
    }

    public function update(CMORequest $request) {

        CMOModel::where('id', $request->id)->update([
            'disciplineId' => $request->discipline,
            'programId' => $request->program,
            'number' => $request->number,
            'series' => $request->series,
            'version' => $request->version,
        ]);
        
        foreach ($request->area as $index => $item) {
            if($index !=0) {
                CriteriaModel::where('cmoId', $request->id)->update([
                    'itemNo' => $index,
                    'area' => $request->area[$index],
                    'minimumRequirement' => $request->minReq[$index],
                ]);
            }
        }

        return redirect()->route('admin.cmo.list', ['list' => 'draft'])->with('success', 'Successfully saved.');

    }
}
