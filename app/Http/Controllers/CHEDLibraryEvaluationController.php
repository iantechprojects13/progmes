<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibEvaluationFormModel;
use App\Models\AdminSettingsModel;
use Inertia\Inertia;
use Auth;

class CHEDLibraryEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $show = $request->query('show') ? $request->query('show') : 25;
        $acadYear = $request->query('ay') ? $request->query('ay') : AdminSettingsModel::where('id', 1)->value('currentAcademicYear');
        
        $complianceTools = LibEvaluationFormModel::query()
            ->when($request->query('search'), function ($searchQuery) use ($request) {
                $searchQuery->whereHas('institution', function ($institutionQuery) use ($request) {
                    $institutionQuery->where('name', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->where('effectivity', $acadYear)
            ->when($request->query('status'), function ($statusQuery) use ($request) {
                $statusQuery->where('status', $request->query('status'));
            })
            ->with(['institution' => function($query) {
                $query->orderBy('name', 'asc');
            }])
            ->whereHas('institution')
            ->join('institution', 'lib_evaluation_form.institutionId', '=', 'institution.id')
            ->orderBy('institution.name', 'asc')
            ->select('lib_evaluation_form.*')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Admin/library/Library-Evaluation-List', [
            'evaluation_list' => $complianceTools,
            'filters' => $request->only(['search', 'status']) + ['show' => $show, 'academicYear' => $acadYear ],
        ]);
    }

}
