<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CMOController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationFormController;
use App\Http\Controllers\HEIFormController;
use App\Http\Controllers\CHEDFormController;
use App\Http\Controllers\InstitutionProgramController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\EvidenceController;


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

Route::get('/unauthorized', function () {
    return Inertia::render('Progmes/Others/Unauthorized');
})->middleware('auth', 'registered')->name('unauthorized');


// google login
Route::controller(App\Http\Controllers\Auth\GoogleAuthController::class)->group(function () {
    Route::get('social/google', 'redirect')->name('auth.google');
    Route::get('social/google/callback', 'callback');
});


// registration
Route::post('/register/ched', [RegistrationController::class, 'registerCHED']);
Route::post('/register/hei', [RegistrationController::class, 'registerHEI']);

//dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth', 'user.verified')->name('dashboard');
Route::get('/admin/dashboard', [DashboardController::class, 'dashboardForAdmin'])->middleware(['auth', 'registered', 'type.ched', 'user.verified'])->name('dashboard.admin');
Route::get('/CHED/dashboard', [DashboardController::class, 'dashboardForCHED'])->middleware(['auth', 'registered', 'type.ched', 'user.verified'])->name('dashboard.ched');
Route::get('/HEI/dashboard', [DashboardController::class, 'dashboardForHEI'])->middleware(['auth', 'type.hei', 'user.verified'])->name('dashboard.hei');


//evaluation
Route::get('/evaluation', [EvaluationController::class, 'index'])->middleware('auth', 'user.verified')->name('evaluation');
Route::get('/ched/evaluation', [EvaluationController::class, 'evaluationForCHED'])->middleware(['auth', 'registered', 'type.ched'])->name('evaluation.ched');
Route::get('/hei/evaluation', [EvaluationController::class, 'evaluationForProgramHead'])->middleware('type.hei')->name('evaluation.hei.programhead');
Route::get('/admin/evaluation', [EvaluationController::class, 'evaluationForAdmin'])->middleware('type.ched')->name('evaluation.admin');

Route::get('hei/evaluation/{tool}', [HEIFormController::class, 'generateKeyBeforeEdit'])->middleware('auth', 'type.hei')->name('form.hei.key');
Route::get('hei/evaluation/{tool}/edit/{key?}', [HEIFormController::class, 'edit'])->middleware('auth', 'type.hei')->name('form.hei.edit');

Route::get('ched/evaluation/{tool}', [CHEDFormController::class, 'generateKeyBeforeEdit'])->middleware('auth', 'type.ched')->name('form.ched.key');
Route::get('ched/evaluation/{tool}/evaluate/{key?}', [CHEDFormController::class, 'evaluate'])->middleware('auth', 'type.ched')->name('form.ched.evaluate');



Route::post('/hei/evaluation/update', [HEIFormController::class, 'update'])->name('form.hei.update');
Route::post('/hei/evaluation/upload', [HEIFormController::class, 'upload'])->name('form.hei.upload');
Route::post('/hei/evaluation/link', [HEIFormController::class, 'submitLink'])->name('form.hei.link');
Route::post('/hei/evaluation/submit', [HEIFormController::class, 'readyForVisit'])->name('form.hei.submit');

Route::post('/hei/evaluation/link/delete', [HEIFormController::class, 'deleteLink'])->name('form.hei.link.delete');


Route::post('/ched/evaluation/update', [CHEDFormController::class, 'update'])->name('form.ched.update');

//admin-panel
Route::get('/admin/users/list', [UserController::class, 'index'])->middleware(['auth', 'type.ched'])->name('admin.users.list');
Route::get('/admin/users/request', [UserController::class, 'request'])->middleware('auth')->name('admin.users.request');
Route::get('/admin/higher-education-institutions', [InstitutionController::class, 'index'])->middleware(['auth'])->name('admin.hei.list');
Route::get('/admin/higher-education-institutions/create', [InstitutionController::class, 'create'])->middleware(['auth'])->name('admin.hei.create');
Route::get('/admin/higher-education-institutions/{institution}/edit', [InstitutionController::class, 'edit'])->middleware(['auth'])->name('admin.hei.edit');
Route::get('/admin/higher-education-institutions/{institution}/view', [InstitutionController::class, 'view'])->middleware(['auth'])->name('admin.hei.show');

Route::get('/admin/CMOs', [CMOController::class, 'index'])->middleware('auth', 'type.ched')->name('admin.cmo.list');
Route::get('/admin/CMOs/draft', [CMOController::class, 'draft'])->middleware('auth', 'type.ched')->name('admin.cmo.draft');
Route::get('admin/CMOs/{cmo}/view', [CMOController::class, 'view'])->middleware('auth', 'type.ched')->name('admin.cmo.show');

Route::get('admin/tool/{academicYear?}', [InstitutionProgramController::class, 'index'])->middleware('auth')->name('admin.form.list');
Route::get('admin/form/{evaluation}/view', [EvaluationFormController::class, 'view'])->middleware('auth')->name('admin.form.view');
Route::post('/admin/form/deploy', [EvaluationFormController::class, 'deploy'])->middleware('auth')->name('admin.form.deploy');

Route::get('hei/tool/create/{id?}', [HEIFormController::class, 'store'])->middleware('auth')->name('hei.tool.store');

Route::get('/admin/program', [ProgramController::class, 'index'])->middleware(['auth'])->name('admin.program.list');
Route::get('/admin/program/create', [ProgramController::class, 'create'])->middleware(['auth'])->name('admin.program.create');

Route::post('/admin/program/store', [ProgramController::class, 'store'])->middleware(['auth'])->name('admin.program.store');
Route::get('/admin/program/{program?}/edit', [ProgramController::class, 'edit'])->middleware(['auth'])->name('admin.program.edit');
Route::post('/admin/program/update', [ProgramController::class, 'update'])->middleware(['auth'])->name('admin.program.update');

Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->middleware(['auth'])->name('admin.settings');

//discipline
Route::get('/admin/program/discipline', [DisciplineController::class,'index'])->middleware('auth')->name('admin.discipline.list');
Route::get('/admin/program/discipline/create', [DisciplineController::class, 'create'])->middleware(['auth'])->name('admin.discipline.create');
Route::post('/admin/program/discipline/store', [DisciplineController::class, 'store'])->middleware(['auth'])->name('admin.discipline.store');


Route::post('/discipline/delete', [ProgramController::class, 'deleteDiscipline'])->middleware(['auth'])->name('admin.discipline.delete');

Route::post('/admin/higher-education-institutions/register', [InstitutionController::class, 'store']);
Route::post('/admin/higher-education-institutions/update', [InstitutionController::class, 'update']);

Route::get('/register', [RegistrationController::class, 'index'])->middleware(['auth', 'unregistered'])->name('register.select');
Route::get('/register/commission-on-higher-education', [RegistrationController::class, 'accountCHED'])->middleware('unregistered')->name('register.ched');
Route::get('/register/higher-education-institution', [RegistrationController::class, 'accountHEI'])->middleware('unregistered')->name('register.hei');
Route::get('/register/for-verification', [RegistrationController::class, 'pending'])->middleware(['auth', 'toverify'])->name('register.pending');


Route::get('/register/{user}/accept', [RegistrationController::class, 'accept'])->name('register.accept');
Route::get('/register/{user}/reject', [RegistrationController::class, 'reject'])->name('register.reject');
Route::get('/register/{user}/deactivate', [RegistrationController::class, 'deactivate'])->name('user.deactivate');


// CMO
Route::post('/admin/CMOs/create/import', [ExcelController::class, 'importExcel'])->name('admin.cmo.import');

Route::get('/admin/CMOs/draft/{id}/edit', [CMOController::class, 'edit'])->name('admin.cmo.edit');

Route::post('/admin/CMOs/store', [CMOController::class, 'store'])->middleware('auth')->name('admin.cmo.store');
Route::post('/admin/CMOs/save-and-publish', [CMOController::class, 'saveAndPublish'])->middleware('auth')->name('admin.cmo.save.publish');
Route::post('/admin/CMOs/save-as-draft', [CMOController::class, 'saveAsDraft'])->middleware('auth')->name('admin.cmo.save.draft');
Route::get('/admin/CMOs/publish/{cmo}', [CMOController::class, 'publish'])->middleware('auth')->name('admin.cmo.publish');

Route::get('/admin/CMOs/delete/{id?}', [CMOController::class, 'destroy'])->middleware('auth')->name('admin.cmo.delete');

Route::get('/admin/CMOs/activate/{id?}', [CMOController::class, 'activate'])->middleware('auth')->name('admin.cmo.activate');
Route::get('/admin/CMOs/deactivate/{id?}', [CMOController::class, 'deactivate'])->middleware('auth')->name('admin.cmo.deactivate');





Route::get('/chart', function () {
    return Inertia::render('Progmes/Shared/Charts/DoughnutChart');
});

Route::get('/test', function () {
    return Inertia::render('Progmes/Shared/Test');
});





require __DIR__.'/auth.php';
