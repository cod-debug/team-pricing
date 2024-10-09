<?php

use App\Http\Controllers\TeamPricingController;
use App\Http\Controllers\SystemWidePartsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('team-pricing')->group(function(){
    Route::get('', [TeamPricingController::class, 'index'])->name('team_pricing');
    Route::get('upload', [TeamPricingController::class, 'upload'])->name('team_pricing_upload');
});

Route::middleware(['auth', 'verified'])->prefix('system-wide-parts')->group(function(){
    Route::get('', [SystemWidePartsController::class, 'index'])->name('system_wide_parts');
    Route::get('upload', [SystemWidePartsController::class, 'upload'])->name('system_wide_parts_upload');
});

require __DIR__.'/auth.php';
