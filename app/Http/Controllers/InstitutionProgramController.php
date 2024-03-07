<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InstitutionProgramModel;
use App\Models\InstitutionModel;
use App\Models\ProgramModel;
use App\Models\CMOModel;
use App\Models\AdminSettingsModel;
use Auth;

class InstitutionProgramController extends Controller
{
    
    
    public function create() {   
        return redirect('/admin/form');
    }
}
