<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CMOController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationFormController;
use App\Http\Controllers\HEIFormController;
use App\Http\Controllers\InstitutionProgramController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\ExcelController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('homepage');

Route::get('/error401', function () {
    return Inertia::render('Progmes/Others/Unauthorized');
})->middleware('auth', 'registered')->name('unauthorized');


// google login
Route::controller(App\Http\Controllers\Auth\GoogleAuthController::class)->group(function () {
    Route::get('social/google', 'redirect')->name('auth.google');
    Route::get('social/google/callback', 'callback');
});


//dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth', 'user.verified')->name('dashboard');
Route::get('/CHED/dashboard', [DashboardController::class, 'dashboardForCHED'])->middleware(['auth', 'registered', 'type.ched'])->name('dashboard.ched');
Route::get('/HEI/dashboard', [DashboardController::class, 'dashboardForHEI'])->middleware(['auth', 'type.hei'])->name('dashboard.hei');


//evaluation
Route::get('/evaluation', [EvaluationController::class, 'index'])->middleware('auth', 'user.verified')->name('evaluation');
Route::get('/ched/evaluation', [EvaluationController::class, 'evaluationForCHED'])->middleware(['auth', 'registered', 'type.ched'])->name('evaluation.ched');
Route::get('/hei/evaluation', [EvaluationController::class, 'evaluationForHEI'])->middleware('type.hei')->name('evaluation.hei');
Route::get('/hei/evaluation/{form}', [HEIFormController::class, 'edit'])->middleware('type.hei')->name('form.hei.edit');

Route::post('/hei/evaluation/update', [HEIFormController::class, 'update'])->name('form.hei.update');

//admin-panel
Route::get('/admin/users/list', [UserController::class, 'index'])->middleware(['auth', 'type.ched'])->name('admin.users.list');
Route::get('/admin/users/request', [UserController::class, 'request'])->middleware('auth')->name('admin.users.request');
Route::get('/admin/higher-education-institutions', [InstitutionController::class, 'index'])->middleware(['auth'])->name('admin.hei.list');
Route::get('/admin/higher-education-institutions/create', [InstitutionController::class, 'create'])->middleware(['auth'])->name('admin.hei.create');
Route::get('/admin/higher-education-institutions/{institution}/view', [InstitutionController::class, 'view'])->middleware(['auth'])->name('admin.hei.show');
Route::get('admin/CMOs', [CMOController::class, 'index'])->middleware('auth', 'type.ched')->name('admin.cmo.list');
Route::get('admin/CMOs/create', [CMOController::class, 'create'])->middleware('auth', 'type.ched')->name('admin.cmo.create');
Route::get('admin/CMOs/{cmo}/view', [CMOController::class, 'view'])->middleware('auth', 'type.ched')->name('admin.cmo.show');
Route::match(['post', 'get'], 'admin/form', [InstitutionProgramController::class, 'index'])->middleware('auth')->name('admin.form.list');
Route::post('admin/form/create', [EvaluationFormController::class, 'create'])->middleware('auth')->name('admin.form.create');
Route::get('admin/form/{evaluation}/view', [EvaluationFormController::class, 'view'])->middleware('auth')->name('admin.form.view');
Route::get('/admin/program', [ProgramController::class, 'index'])->middleware(['auth'])->name('admin.program.list');
Route::get('/admin/program/create', [ProgramController::class, 'create'])->middleware(['auth'])->name('admin.program.create');
Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->middleware(['auth'])->name('admin.settings');

Route::post('/register/ched', [RegistrationController::class, 'registerCHED']);
Route::post('/register/hei', [RegistrationController::class, 'registerHEI']);



Route::post('/discipline/create', [ProgramController::class, 'addDiscipline'])->middleware(['auth'])->name('admin.discipline.create');
Route::post('/discipline/delete', [ProgramController::class, 'deleteDiscipline'])->middleware(['auth'])->name('admin.discipline.delete');

Route::post('/admin/higher-education-institutions/register', [InstitutionController::class, 'store']);


Route::get('/register', [RegistrationController::class, 'index'])->middleware(['auth', 'unregistered'])->name('register.select');
Route::get('/register/commission-on-higher-education', [RegistrationController::class, 'accountCHED'])->middleware('unregistered')->name('register.ched');
Route::get('/register/higher-education-institution', [RegistrationController::class, 'accountHEI'])->middleware('unregistered')->name('register.hei');
Route::get('/register/for-verification', [RegistrationController::class, 'pending'])->middleware(['auth', 'toverify'])->name('register.pending');


Route::get('/register/{user}/accept', [RegistrationController::class, 'accept'])->name('register.accept');
Route::get('/register/{user}/reject', [RegistrationController::class, 'reject'])->name('register.reject');






Route::match(['post', 'get'], '/admin/CMOs/upload', [ExcelController::class, 'importExcel']);
Route::post('/admin/CMOs/store', [CMOController::class, 'store'])->name('admin.cmo.store');

Route::get('/chart', function () {
    return Inertia::render('Progmes/Shared/Charts/DoughnutChart');
});






require __DIR__.'/auth.php';
