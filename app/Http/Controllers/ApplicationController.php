<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ApplicationGPRModel;
use App\Models\ApplicationFormModel;
use App\Models\ApplicationItemModel;
use App\Models\SupportDocumentModel;
use App\Models\RoleModel;
use Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        if (auth()->user()->type == 'CHED') {
            return redirect()->route('ched.application.list');
        }
        if (auth()->user()->type == 'HEI') {
            return redirect()->route('hei.application.list');
        }
    }

    
    public function programApplicationCHED(Request $request)
    {
        $show = $request->query('show') ? $request->query('show') : 25;
        $applications = ApplicationFormModel::query()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('type', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('academicYear', 'like', '%' . $request->query('search') . '%')
                    ->orWhereHas('program', function ($programQuery) use ($request) {
                        $programQuery->where('program', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('major', 'like', '%' . $request->query('search') . '%');
                    })
                    ->orWhereHas('institution', function ($institutionQuery) use ($request) {
                        $institutionQuery->where('name', 'like', '%' . $request->query('search') . '%');
                    });
                });
            })
            ->where('status', '!=', 'draft')
            ->orderByRaw("CASE WHEN status = 'Applied' THEN 1 WHEN status = 'Draft' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'asc')
            ->orderBy('updated_at', 'asc')
            ->with('institution', 'program')
            ->paginate($show)
            ->withQueryString();

        return Inertia::render('Application/CHED-Application-List', [
            'application_list' => $applications,
            'filters' => $request->only('search'),
        ]);
    }

    

    public function programApplicationHEI(Request $request)
    {
        $show = $request->query('show') ? $request->query('show') : 25;
        $user = Auth::user();
        $institution = $user->userRole()
                ->where('isActive', 1)
                ->select('institutionId')
                ->with('institution')
                ->first();
        
        
        $applications = ApplicationFormModel::query()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('type', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('academicYear', 'like', '%' . $request->query('search') . '%')
                    ->orWhereHas('program', function ($programQuery) use ($request) {
                        $programQuery->where('program', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('major', 'like', '%' . $request->query('search') . '%');
                    });
                });
            })
            ->where('institutionId', $institution->institutionId)
            ->orderByRaw("CASE WHEN status = 'Applied' THEN 1 WHEN status = 'Draft' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'asc')
            ->orderBy('updated_at', 'asc')
            ->with('program')
            ->paginate($show)
            ->withQueryString();
        
        return Inertia::render('Application/HEI-Application-List', [
            'application_list' => $applications,
            'institution' => $institution,
            'filters' => $request->only('search'),
        ]);
    }


    public function applicationCHEDEvaluate($id)
    {
        $id = (int) $id;
        $application = ApplicationFormModel::where('id', $id)->with('institution', 'program', 'item', 'item.gpr', 'item.support_document')->first();
        
        return Inertia::render('Application/CHED-Application-Edit', [
            'application' => $application,
        ]);
    }


    public function applicationHEIEdit($id)
    {
        $id = (int) $id;
        $application = ApplicationFormModel::where('id', $id)
        ->with('institution', 'item', 'item.gpr', 'item.support_document')
        ->first();
        
        $programs = RoleModel::where('userId', Auth::user()->id)
            ->where('isActive', 1)
            ->with('discipline.program')
            ->get()
            ->pluck('discipline.program');
        
        return Inertia::render('Application/HEI-Application-Edit', [
            'application' => $application,
            'programs' => $programs,
        ]);
    }


    public function create(Request $request)
    {
        $application = ApplicationFormModel::create([
            'institutionId' => $request->institutionId,
            'status' => 'draft',
        ]);

        $gpr = ApplicationGPRModel::all();

        foreach($gpr as $item) {
            ApplicationItemModel::create([
                'applicationId' => $application->id,
                'gprId' => $item->id,
            ]);
        }
        
        return redirect('/hei/program-application/'. $application->id . '/edit');
    }


    public function update(Request $request)
    {
        // dd($request);
        $application = ApplicationFormModel::find($request->id);

        $application->update([
            'programId' => $request->program,
            'type' => $request->type,
            'academicYear' => $request->academicYear,
        ]);

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $applicationItem = ApplicationItemModel::find($item['id']);
                if ($applicationItem) {
                    $applicationItem->update(['isExisting' => $item['isExisting']]);
                }
            }
        }

        return redirect()->back()->with('success', 'Changes saved successfully.');
    }


    public function remark(Request $request)
    {
        // dd($request);
        $application = ApplicationFormModel::find($request->id);

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $applicationItem = ApplicationItemModel::find($item['id']);
                if ($applicationItem) {
                    $applicationItem->update(['remarks' => $item['remarks']]);
                }
            }
        }

        return redirect()->back()->with('success', 'Changes saved successfully.');
    }

    public function submit(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'institution' => 'required',
            'program' => 'required',
            'type' => 'required',
            'academicYear' => 'required',
        ]);

        $application = ApplicationFormModel::find($request->id);

        $application->update([
            'programId' => $request->program,
            'type' => $request->type,
            'academicYear' => $request->academicYear,
            'status' => 'Applied',
        ]);

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                if (!isset($item['isExisting'])) {
                    return redirect()->back()->with('failed', 'All values in the \'Existing?\' column must not be null.');
                }
                $applicationItem = ApplicationItemModel::find($item['id']);
                if ($applicationItem) {
                    $applicationItem->update(['isExisting' => $item['isExisting']]);
                }
            }
        }

        return redirect(route('hei.application.list'))->with('success', 'Application submitted successfully.');
    }

    public function uploadLink(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'id' => 'required',
            'url' => 'required|url',
        ]);

        SupportDocumentModel::create([
            'itemId' => $request->id,
            'url' => $request->url,
        ]);

        return redirect()->back()->with('success', 'Link uploaded successfully.');
    }


    public function destroy($id)
    {
        $application = ApplicationFormModel::find($id);
        $application->delete();
        return redirect()->back()->with('success', 'Application deleted successfully.');
    }

}


