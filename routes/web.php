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
use App\Http\Controllers\GPRController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\LibCriteriaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\HEILibraryEvaluationController;
use App\Http\Controllers\CHEDLibraryEvaluationController;


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
    return Inertia::render('Others/Unauthorized');
})->middleware('auth', 'registered')->name('unauthorized');


// google login
Route::controller(App\Http\Controllers\Auth\GoogleAuthController::class)->group(function () {
    Route::get('/social/google', 'redirect')->name('auth.google');
    Route::get('/auth/google/callback', 'callback');
});


// registration
Route::post('/register/ched', [RegistrationController::class, 'registerCHED']);
Route::post('/register/hei', [RegistrationController::class, 'registerHEI']);

Route::get('/register', [RegistrationController::class, 'index'])->middleware(['auth', 'unregistered'])->name('register.select');
Route::get('/register/commission-on-higher-education', [RegistrationController::class, 'accountCHED'])->middleware('unregistered')->name('register.ched');
Route::match(['get', 'post'], '/register/higher-education-institution', [RegistrationController::class, 'accountHEI'])->middleware('unregistered')->name('register.hei');
Route::get('/register/for-verification', [RegistrationController::class, 'pending'])->middleware(['auth', 'toverify'])->name('register.pending');

Route::get('/register/{user}/accept', [RegistrationController::class, 'acceptUser'])->name('register.accept');
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
Route::get('/ched/dashboard', [DashboardController::class, 'dashboardForCHED'])->middleware(['auth', 'registered', 'type.ched', 'user.verified'])->name('dashboard.admin.ched');
Route::get('/ched/dashboard/analytics', [DashboardController::class, 'analytics'])->middleware(['auth', 'type.ched'])->name('analytics');
Route::get('/ched/dashboard/overview', [DashboardController::class, 'dashboardForCHEDOverview'])->middleware(['auth', 'registered', 'type.ched', 'user.verified', 'ched.admin'])->name('dashboard.admin.overview');
Route::get('/hei/dashboard', [DashboardController::class, 'dashboardForHEI'])->middleware(['auth', 'type.hei', 'user.verified', 'active'])->name('dashboard.hei');
Route::post('/set-academic-year', [DashboardController::class, 'setAcademicYear'])->middleware(['auth', 'superadmin'])->name('setacadyear');



//evaluation
Route::get('/evaluation', [EvaluationController::class, 'index'])->middleware(['auth', 'user.verified'])->name('evaluation');
Route::get('/ched/evaluation', [EvaluationController::class, 'evaluationForCHED'])->middleware(['auth', 'registered', 'type.ched'])->name('evaluation.ched');
Route::get('/ched/evaluation/monitored', [EvaluationController::class, 'monitoredForCHED'])->middleware(['auth', 'registered', 'type.ched'])->name('evaluation.ched.monitored');
Route::get('/hei/ph/evaluation', [EvaluationController::class, 'evaluationForProgramHead'])->middleware(['auth','type.hei', 'hei.ph'])->name('evaluation.ph');
Route::get('/hei/evaluation', [EvaluationController::class, 'evaluationForHEI'])->middleware(['auth','type.hei', 'hei.vp.dean'])->name('evaluation.hei');
Route::get('/hei/evaluation/monitored', [EvaluationController::class, 'monitoredForHEI'])->middleware(['auth','type.hei', 'hei.vp.dean'])->name('evaluation.hei.monitored');
Route::get('hei/evaluation/{tool}/edit', [HEIFormController::class, 'edit'])->middleware(['auth', 'type.hei', 'hei.ph'])->name('evaluation.hei.edit');
Route::get('ched/evaluation/{tool}/view', [CHEDFormController::class, 'view'])->middleware(['auth', 'type.ched'])->name('evaluation.ched.view');
Route::get('ched/evaluation/{tool}/evaluate/', [CHEDFormController::class, 'evaluate'])->middleware(['auth', 'type.ched', 'evaluate'])->name('form.ched.evaluate');
Route::get('ched/evaluation/{tool}/report', [CHEDFormController::class, 'report'])->middleware(['auth', 'type.ched'])->name('form.ched.report');
Route::get('/hei/evaluation/{tool}/view', [HEIFormController::class, 'view'])->middleware(['auth', 'type.hei'])->name('hei.evaluation.view');

Route::post('/hei/evaluation/conforme', [HEIFormController::class, 'conforme'])->name('form.hei.conforme');
Route::post('/hei/evaluation/update', [HEIFormController::class, 'update'])->name('form.hei.update');
Route::post('/hei/evaluation/upload', [HEIFormController::class, 'upload'])->name('form.hei.upload');
Route::post('/hei/evaluation/link', [HEIFormController::class, 'submitLink'])->name('form.hei.link');
Route::post('/hei/evaluation/submit', [HEIFormController::class, 'readyForVisit'])->name('form.hei.submit');
Route::post('/hei/evaluation/link/delete', [HEIFormController::class, 'deleteLink'])->name('form.hei.link.delete');

Route::post('/ched/evaluation/update', [CHEDFormController::class, 'update'])->name('form.ched.update');
Route::post('/ched/evaluation/monitored', [CHEDFormController::class, 'monitored'])->name('form.ched.monitored');
Route::post('/ched/evaluation/lock', [CHEDFormController::class, 'lock'])->name('form.ched.lock');
Route::post('/ched/evaluation/unlock', [CHEDFormController::class, 'unlock'])->name('form.ched.unlock');

//Library Evaluation
Route::get('/library', [EvaluationController::class, 'library'])->middleware(['auth', 'user.verified'])->name('library.evaluation');
Route::get('/hei/library/evaluation', [HEILibraryEvaluationController::class, 'index'])->middleware(['auth', 'user.verified', 'hei.vp.librarian'])->name('hei.library.evaluation.list');
Route::get('hei/library/{tool}/edit', [HEILibraryEvaluationController::class, 'edit'])->middleware(['auth', 'type.hei', 'librarian'])->name('hei.library.evaluation.edit');
Route::get('hei/library/{tool}/view', [HEILibraryEvaluationController::class, 'view'])->middleware(['auth', 'type.hei', 'hei.vp.librarian'])->name('hei.library.tool.view');
Route::post('hei/library/evaluation/update', [HEILibraryEvaluationController::class, 'update'])->middleware(['auth', 'type.hei', 'librarian'])->name('hei.library.evaluation.update');
Route::post('/hei/library/evaluation/link', [HEILibraryEvaluationController::class, 'submitLink'])->name('hei.library.evaluation.link');
Route::post('/hei/library/evaluation/submit', [HEILibraryEvaluationController::class, 'completed'])->name('hei.library.evaluation.submit');
Route::post('/hei/library/evaluation/link/delete', [HEILibraryEvaluationController::class, 'deleteLink'])->name('hei.library.evaluation.link.delete');
Route::post('hei/library/tool/create', [HEILibraryEvaluationController::class, 'store'])->middleware('auth')->name('hei.library.tool.store');
Route::get('/ched/library/evaluation', [CHEDLibraryEvaluationController::class, 'libraryEvaluationList'])->middleware(['auth', 'user.verified', 'type.ched'])->name('ched.library.evaluation.list');
Route::get('ched/library/evaluation/{tool}/view', [CHEDLibraryEvaluationController::class, 'view'])->middleware(['auth', 'type.ched'])->name('ched.library.tool.view');
Route::get('ched/library/evaluation/{tool}/evaluate/', [CHEDLibraryEvaluationController::class, 'evaluate'])->middleware(['auth', 'type.ched', 'evaluate'])->name('ched.library.tool.evaluate');
Route::post('ched/library/evaluation/update', [CHEDLibraryEvaluationController::class, 'update'])->middleware(['auth', 'type.ched'])->name('ched.library.tool.update');
Route::get('ched/library/evaluation/{tool}/report', [CHEDLibraryEvaluationController::class, 'report'])->middleware(['auth', 'type.ched'])->name('ched.library.tool.report');

// PROGRAM APPLICATION
// gpr
Route::get('/ched/program-application/gpr', [GPRController::class, 'index'])->middleware(['auth', 'user.verified', 'type.ched'])->name('ched.gpr.view');
Route::get('/ched/program-application/gpr/edit', [GPRController::class, 'edit'])->middleware(['auth', 'user.verified', 'type.ched'])->name('ched.gpr.edit');
Route::post('/ched/program-application/gpr/update', [GPRController::class, 'update'])->middleware(['auth', 'user.verified', 'type.ched'])->name('ched.gpr.update');
Route::get('/ched/program-application/gpr/{id}/delete', [GPRController::class, 'delete'])->middleware(['auth', 'user.verified', 'type.ched'])->name('ched.gpr.delete');


//application
Route::get('/application', [ApplicationController::class, 'index'])->middleware(['auth', 'user.verified'])->name('application');
Route::get('/hei/program-application', [ApplicationController::class, 'programApplicationHEI'])->middleware(['auth', 'user.verified', 'type.hei'])->name('hei.application.list');
Route::get('/ched/program-application', [ApplicationController::class, 'programApplicationCHED'])->middleware(['auth', 'user.verified', 'type.ched'])->name('ched.application.list');
Route::get('/ched/program-application/{id}/evaluate', [ApplicationController::class, 'applicationCHEDEvaluate'])->middleware(['auth', 'user.verified', 'type.ched'])->name('ched.application.evaluate');
Route::get('/hei/program-application/{id}/edit', [ApplicationController::class, 'applicationHEIEdit'])->middleware(['auth', 'user.verified', 'type.hei'])->name('hei.application.edit');
Route::post('/application/create', [ApplicationController::class, 'create'])->middleware(['auth', 'user.verified', 'type.hei'])->name('hei.application.create');
Route::post('/application/update', [ApplicationController::class, 'update'])->middleware(['auth', 'user.verified', 'type.hei'])->name('hei.application.update');
Route::post('/application/submit', [ApplicationController::class, 'submit'])->middleware(['auth', 'user.verified', 'type.hei'])->name('hei.application.submit');
Route::post('/application/link', [ApplicationController::class, 'uploadLink'])->middleware(['auth', 'user.verified', 'type.hei'])->name('hei.application.link');
Route::get('/application/{id}/delete', [ApplicationController::class, 'destroy'])->middleware(['auth', 'user.verified'])->name('hei.application.delete');
Route::post('/application/remark', [ApplicationController::class, 'remark'])->middleware(['auth', 'user.verified', 'type.ched'])->name('ched.application.remark');


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
Route::get('/admin/CMOs/draft/{cmo}/view', [CMOController::class, 'view'])->middleware('auth', 'type.ched', 'cmo.draft')->name('admin.cmo.draft.view');
Route::get('/admin/CMOs/draft/{id}/edit', [CMOController::class, 'edit'])->name('admin.cmo.edit');
Route::get('admin/CMOs/{cmo}/view', [CMOController::class, 'view'])->middleware('auth', 'type.ched')->name('admin.cmo.show');
Route::post('/admin/CMOs/store', [CMOController::class, 'store'])->middleware('auth')->name('admin.cmo.store');
Route::post('/admin/CMOs/update', [CMOController::class, 'update'])->middleware('auth', 'type.ched')->name('admin.cmo.update');
Route::post('/admin/CMOs/save-as-draft', [CMOController::class, 'saveAsDraft'])->middleware('auth')->name('admin.cmo.save.draft');
Route::post('/admin/CMOs/save-and-publish', [CMOController::class, 'saveAndPublish'])->middleware('auth')->name('admin.cmo.save.publish');
Route::get('/admin/CMOs/publish/{cmo}', [CMOController::class, 'publish'])->middleware('auth')->name('admin.cmo.publish');
Route::get('/admin/CMOs/delete/{id?}', [CMOController::class, 'destroy'])->middleware('auth')->name('admin.cmo.delete');
Route::get('/admin/CMOs/activate/{id?}', [CMOController::class, 'activate'])->middleware('auth')->name('admin.cmo.activate');
Route::get('/admin/CMOs/deactivate/{id?}', [CMOController::class, 'deactivate'])->middleware('auth')->name('admin.cmo.deactivate');

Route::post('/admin/CMOs/create/import', [ExcelController::class, 'importExcel'])->name('admin.cmo.import');

//Librarian CMO
Route::get('/admin/library', [CHEDLibraryEvaluationController::class, 'index'])->middleware('auth', 'type.ched', 'admin')->name('admin.library.list');
Route::get('/admin/library/CMO/view', [LibCriteriaController::class, 'index'])->middleware('auth', 'type.ched', 'admin')->name('library.cmo.view');
Route::get('/admin/library/CMO/edit', [LibCriteriaController::class, 'edit'])->middleware('auth', 'type.ched', 'admin')->name('library.cmo.edit');
Route::post('/admin/library/CMO/update', [LibCriteriaController::class, 'update'])->middleware('auth', 'type.ched', 'admin')->name('library.cmo.update');
Route::post('/admin/library/CMO/deploy', [LibCriteriaController::class, 'deploy'])->middleware('auth', 'type.ched', 'admin')->name('library.cmo.deploy');


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
Route::post('/report/library/generate', [PDFController::class, 'generateReportForLibrary'])->middleware(['auth'])->name('report.library.generate');
Route::get('/report/monitoring/{tool}/{orientation}/{type}', [PDFController::class, 'monitoringReport'])->middleware(['auth'])->name('report.monitoring');
Route::get('/report/deficiency/{tool}/{orientation}/{type}', [PDFController::class, 'deficiencyReport'])->middleware(['auth'])->name('report.deficiency');
Route::get('/report/library/monitoring/{tool}/{orientation}/{type}', [PDFController::class, 'monitoringReportForLibrary'])->middleware(['auth'])->name('report.library.monitoring');
Route::get('/report/library/deficiency/{tool}/{orientation}/{type}', [PDFController::class, 'deficiencyReportForLibrary'])->middleware(['auth'])->name('report.library.deficiency');




Route::get('/api/data', [DashboardController:: class, 'getData']);

Route::get('/login-testuser', [UserController::class, 'userLogin'])->name('test.login');
Route::post('/testuserlogin', [UserController::class, 'testUserLogin']);

require __DIR__.'/auth.php';
