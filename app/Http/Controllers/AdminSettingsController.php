<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AdminSettingsModel;

class AdminSettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Progmes/Admin/Settings', [
            'settings' => AdminSettingsModel::all(),
        ]);
    }
}
