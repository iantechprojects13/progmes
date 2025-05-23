<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\InstitutionModel;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HEILibraryEvaluationController;
use App\Http\Controllers\HEIFormController;
use App\Http\Controllers\AdminSettingsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/dashboard/tool/{academicyear}/{status}/view', function ($academicyear, $status) {
//     return response()->json([
//         'statusCode' => '200',
//         'message' => 'Success',
//     ]);
// });


Route::get('/dashboard/tool/{academicyear}/{status}/{supervisor}/{program}/{institution}/view', [DashboardController::class, 'viewTools']);
Route::get('/dashboard/tool/{supervisor}/{program}/{institution}/{year}/{month}/{quarter}/view', [DashboardController::class, 'viewMonitoredTools']);
Route::get('/admin/settings/{id}/assignedprograms', [AdminSettingsController::class, 'getAssignedPrograms']);

Route::get('/hei/library/evaluation/{id}/progress', [HEILibraryEvaluationController::class, 'getToolProgress']);
Route::get('/hei/evaluation/{id}/progress', [HEIFormController::class, 'getToolProgress']);
