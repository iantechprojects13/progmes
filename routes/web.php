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
use App\Http\Controllers\PDFController;


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
    Route::get('/social/google', 'redirect')->name('auth.google');
    Route::get('/social/google/callback', 'callback');
});


// registration
Route::post('/register/ched', [RegistrationController::class, 'registerCHED']);
Route::post('/register/hei', [RegistrationController::class, 'registerHEI']);

Route::get('/register', [RegistrationController::class, 'index'])->middleware(['auth', 'unregistered'])->name('register.select');
Route::get('/register/commission-on-higher-education', [RegistrationController::class, 'accountCHED'])->middleware('unregistered')->name('register.ched');
Route::match(['get', 'post'], '/register/higher-education-institution', [RegistrationController::class, 'accountHEI'])->middleware('unregistered')->name('register.hei');
Route::get('/register/for-verification', [RegistrationController::class, 'pending'])->middleware(['auth', 'toverify'])->name('register.pending');

Route::get('/register/{user}/accept', [RegistrationController::class, 'accept'])->name('register.accept');
Route::get('/register/{user}/reject', [RegistrationController::class, 'reject'])->name('register.reject');
Route::get('/register/{user}/activate', [RegistrationController::class, 'activate'])->name('user.activate');
Route::get('/register/{user}/deactivate', [RegistrationController::class, 'deactivate'])->name('user.deactivate');
Route::post('/register/change-role', [RegistrationController::class, 'changeRole'])->name('user.role.change');
Route::get('/register/{user}/delete', [RegistrationController::class, 'destroy'])->name('user.delete');

//Profile
Route::get('/myaccount/{user?}',[RegistrationController::class, 'viewMyAccount'])->middleware(['auth', 'user.verified'])->name('my.account');
Route::get('/admin/users/list/profile/{user?}/view',[RegistrationController::class, 'viewUserProfile'])->middleware(['auth', 'user.verified'])->name('profile.view');

//dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth','registered', 'user.verified', 'active'])->name('dashboard');
Route::get('/ched/dashboard/progress', [DashboardController::class, 'dashboardForCHEDProgress'])->middleware(['auth', 'registered', 'type.ched', 'user.verified'])->name('dashboard.admin.progress');
Route::get('/ched/dashboard/analytics', [DashboardController::class, 'analytics'])->middleware(['auth', 'type.ched'])->name('analytics');
Route::get('/ched/dashboard/overview', [DashboardController::class, 'dashboardForCHEDOverview'])->middleware(['auth', 'registered', 'type.ched', 'user.verified', 'ched.admin'])->name('dashboard.admin.overview');
Route::get('/ched/dashboard', [DashboardController::class, 'dashboardForES'])->middleware(['auth', 'registered', 'type.ched', 'user.verified', 'ched.es'])->name('dashboard.ched');
Route::get('/hei/dashboard', [DashboardController::class, 'dashboardForHEI'])->middleware(['auth', 'type.hei', 'user.verified', 'active'])->name('dashboard.hei');
Route::post('/set-academic-year', [DashboardController::class, 'setAcademicYear'])->middleware(['auth', 'superadmin'])->name('setacadyear');



//evaluation
Route::get('/evaluation', [EvaluationController::class, 'index'])->middleware(['auth', 'user.verified'])->name('evaluation');
Route::get('/ched/evaluation', [EvaluationController::class, 'evaluationForCHED'])->middleware(['auth', 'registered', 'type.ched'])->name('evaluation.ched');
Route::get('/ched/evaluation/archived', [EvaluationController::class, 'archivedForCHED'])->middleware(['auth', 'registered', 'type.ched'])->name('ched.evaluation.archive');
Route::get('/hei/ph/evaluation', [EvaluationController::class, 'evaluationForProgramHead'])->middleware(['auth','type.hei', 'hei.ph'])->name('evaluation.ph');
Route::get('/hei/evaluation', [EvaluationController::class, 'evaluationForHEI'])->middleware(['auth','type.hei', 'hei.vp.dean'])->name('evaluation.hei');
Route::get('/hei/evaluation/archived', [EvaluationController::class, 'archivedForHEI'])->middleware(['auth','type.hei', 'hei.vp.dean'])->name('evaluation.hei.archive');
Route::get('hei/evaluation/{tool}/edit', [HEIFormController::class, 'edit'])->middleware(['auth', 'type.hei'])->name('form.hei.key');
Route::get('ched/evaluation/{tool}/view', [CHEDFormController::class, 'view'])->middleware(['auth', 'type.ched'])->name('evaluation.ched.view');
Route::get('ched/evaluation/{tool}/evaluate/', [CHEDFormController::class, 'evaluate'])->middleware(['auth', 'type.ched'])->name('form.ched.evaluate');
Route::get('ched/evaluation/{tool}/report', [CHEDFormController::class, 'report'])->middleware(['auth', 'type.ched'])->name('form.ched.report');
Route::get('/hei/evaluation/{tool}/view', [HEIFormController::class, 'view'])->middleware(['auth', 'type.hei'])->name('hei.evaluation.view');

Route::post('/hei/evaluation/conforme', [HEIFormController::class, 'conforme'])->name('form.hei.conforme');
Route::post('/hei/evaluation/update', [HEIFormController::class, 'update'])->name('form.hei.update');
Route::post('/hei/evaluation/upload', [HEIFormController::class, 'upload'])->name('form.hei.upload');
Route::post('/hei/evaluation/link', [HEIFormController::class, 'submitLink'])->name('form.hei.link');
Route::post('/hei/evaluation/submit', [HEIFormController::class, 'readyForVisit'])->name('form.hei.submit');
Route::post('/hei/evaluation/link/delete', [HEIFormController::class, 'deleteLink'])->name('form.hei.link.delete');

Route::post('/ched/evaluation/update', [CHEDFormController::class, 'update'])->name('form.ched.update');
Route::post('/ched/evaluation/archive', [CHEDFormController::class, 'archive'])->name('form.ched.archive');
Route::post('/ched/evaluation/lock', [CHEDFormController::class, 'lock'])->name('form.ched.lock');
Route::post('/ched/evaluation/unlock', [CHEDFormController::class, 'unlock'])->name('form.ched.unlock');


//admin-panel
//USERS
Route::get('/admin/users/list', [UserController::class, 'index'])->middleware(['auth','user.verified', 'type.ched'])->name('admin.users.list');
Route::get('/admin/users/request', [UserController::class, 'request'])->middleware(['auth','user.verified', 'type.ched', 'action.user.accept'])->name('admin.users.request');
Route::get('/admin/users/inactive', [UserController::class, 'inactive'])->middleware(['auth','user.verified', 'type.ched', 'action.user.activate'])->name('admin.users.inactive');

//HEI
Route::get('/admin/higher-education-institutions', [InstitutionController::class, 'index'])->middleware(['auth'])->name('admin.hei.list');
Route::get('/admin/higher-education-institutions/create', [InstitutionController::class, 'create'])->middleware(['auth'])->name('admin.hei.create');
Route::get('/admin/higher-education-institutions/{institution}/edit', [InstitutionController::class, 'edit'])->middleware(['auth'])->name('admin.hei.edit');
Route::get('/admin/higher-education-institutions/{institution}/view', [InstitutionController::class, 'view'])->middleware(['auth'])->name('admin.hei.show');
Route::get('/admin/higher-education-institutions/{institution}/delete', [InstitutionController::class, 'delete'])->middleware(['auth'])->name('admin.hei.show');
Route::post('/admin/higher-education-institutions/register', [InstitutionController::class, 'store']);
Route::post('/admin/higher-education-institutions/update', [InstitutionController::class, 'update']);

// CMO
Route::get('/admin/CMOs', [CMOController::class, 'index'])->middleware('auth', 'type.ched')->name('admin.cmo.list');
Route::get('/admin/CMOs/draft', [CMOController::class, 'draft'])->middleware('auth', 'type.ched', 'cmo.draft')->name('admin.cmo.draft');
Route::get('admin/CMOs/{cmo}/view', [CMOController::class, 'view'])->middleware('auth', 'type.ched')->name('admin.cmo.show');
Route::post('/admin/CMOs/create/import', [ExcelController::class, 'importExcel'])->name('admin.cmo.import');
Route::get('/admin/CMOs/draft/{id}/edit', [CMOController::class, 'edit'])->name('admin.cmo.edit');
Route::post('/admin/CMOs/store', [CMOController::class, 'store'])->middleware('auth')->name('admin.cmo.store');
Route::post('/admin/CMOs/save-and-publish', [CMOController::class, 'saveAndPublish'])->middleware('auth')->name('admin.cmo.save.publish');
Route::post('/admin/CMOs/save-as-draft', [CMOController::class, 'saveAsDraft'])->middleware('auth')->name('admin.cmo.save.draft');
Route::get('/admin/CMOs/publish/{cmo}', [CMOController::class, 'publish'])->middleware('auth')->name('admin.cmo.publish');
Route::get('/admin/CMOs/delete/{id?}', [CMOController::class, 'destroy'])->middleware('auth')->name('admin.cmo.delete');
Route::get('/admin/CMOs/activate/{id?}', [CMOController::class, 'activate'])->middleware('auth')->name('admin.cmo.activate');
Route::get('/admin/CMOs/deactivate/{id?}', [CMOController::class, 'deactivate'])->middleware('auth')->name('admin.cmo.deactivate');

//Evaluation Tool
Route::get('/admin/tool', [EvaluationFormController::class, 'index'])->middleware('auth', 'type.ched')->name('admin.form.list');
Route::get('/admin/tool/{evaluation}/view', [EvaluationFormController::class, 'view'])->middleware('auth')->name('admin.form.view');
Route::post('/admin/form/deploy', [EvaluationFormController::class, 'deploy'])->middleware('auth', 'type.ched')->name('admin.form.deploy');
Route::post('hei/tool/create', [HEIFormController::class, 'store'])->middleware('auth')->name('hei.tool.store');

//Program
Route::get('/admin/program', [ProgramController::class, 'index'])->middleware(['auth', 'type.ched'])->name('admin.program.list');
Route::get('/admin/program/create', [ProgramController::class, 'create'])->middleware(['auth', 'type.ched', 'admin'])->name('admin.program.create');
Route::post('/admin/program/store', [ProgramController::class, 'store'])->middleware(['auth', 'type.ched', 'admin'])->name('admin.program.store');
Route::get('/admin/program/{program?}/edit', [ProgramController::class, 'edit'])->middleware(['auth', 'type.ched', 'admin'])->name('admin.program.edit');
Route::post('/admin/program/update', [ProgramController::class, 'update'])->middleware(['auth', 'type.ched', 'admin'])->name('admin.program.update');
Route::get('/admin/program/{program}/delete', [ProgramController::class, 'delete'])->middleware(['auth', 'type.ched', 'superadmin'])->name('admin.program.delete');

//Discipline
Route::get('/admin/program/discipline', [DisciplineController::class,'index'])->middleware('auth', 'type.ched')->name('admin.discipline.list');
Route::get('/admin/program/discipline/create', [DisciplineController::class, 'create'])->middleware(['auth', 'type.ched', 'admin'])->name('admin.discipline.create');
Route::get('/admin/program/discipline/{id}/edit', [DisciplineController::class, 'edit'])->middleware(['auth', 'type.ched', 'admin'])->name('admin.discipline.edit');
Route::post('/admin/program/discipline/store', [DisciplineController::class, 'store'])->middleware(['auth', 'type.ched', 'admin'])->name('admin.discipline.store');
Route::post('/admin/program/discipline/update', [DisciplineController::class, 'update'])->middleware(['auth', 'type.ched', 'admin'])->name('admin.discipline.update');
Route::get('/admin/discipline/{discipline}/delete', [DisciplineController::class, 'delete'])->middleware(['auth', 'type.ched', 'superadmin'])->name('admin.discipline.delete');


//Admin Settings
Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->middleware(['auth'])->name('admin.settings');

//Email Notification
Route::post('/evaluation/notify', [EvaluationController::class, 'sendEmail'])->middleware(['auth'])->name('evaluation.notify');

//Generate Report
Route::post('/report', [PDFController::class, 'report'])->middleware(['auth'])->name('report.create');
Route::post('/report/generate', [PDFController::class, 'generateReport'])->middleware(['auth'])->name('report.generate');
Route::get('/report/monitoring/{tool}/{type}', [PDFController::class, 'monitoringReport'])->middleware(['auth'])->name('report.monitoring');
Route::get('/report/deficiency/{tool}/{type}', [PDFController::class, 'deficiencyReport'])->middleware(['auth'])->name('report.deficiency');

// Route::get('/chart', function () {
//     return Inertia::render('Progmes/Shared/Charts/DoughnutChart');
// });

// Route::get('/test', function () {
//     return Inertia::render('Progmes/Shared/Test');
// });

Route::get('/allprog', [ProgramController::class, 'allprog']);

Route::get('/login-testuser', [UserController::class, 'userLogin']);
Route::post('/testuserlogin', [UserController::class, 'testUserLogin']);

require __DIR__.'/auth.php';
