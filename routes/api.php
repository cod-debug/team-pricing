<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamPricingController;
use App\Http\Controllers\SystemWidePartsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth', 'team_admin'])->group(function () {
    Route::post('team-pricing', [TeamPricingController::class, 'get'])->name('get_team_pricing');
    Route::post('team-pricing/upload', [TeamPricingController::class, 'uploadPost'])->name('upload_team_pricing');
});

Route::middleware(['auth', 'system_admin'])->group(function () {
    Route::post('system-wide-part', [SystemWidePartsController::class, 'get'])->name('get_system_wide_part');
    Route::post('system-wide-part/upload', [SystemWidePartsController::class, 'uploadPost'])->name('upload_system_wide_part');
});