<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftEmployeeController;
use App\Http\Controllers\ShiftLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('employees', EmployeeController::class)
    ->only(['index', 'store', 'create', 'edit', 'destroy', 'update'])
    ->middleware(['auth', 'verified']);

Route::resource('companies', CompanyController::class)
    ->only(['index', 'store', 'create', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('shifts', ShiftController::class)
    ->only(['index', 'store', 'create', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('holidays', HolidayController::class)
    ->only(['index', 'store', 'create', 'edit', 'destroy', 'update'])
    ->middleware(['auth', 'verified']);

Route::resource('shift_employee', ShiftEmployeeController::class)
    ->only(['index', 'store', 'create', 'edit', 'destroy', 'update', 'show'])
    ->middleware(['auth', 'verified']);

Route::resource('shift_log', ShiftLogController::class)
    ->only(['store', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::get('welcome', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('welcome');

Route::get('dashboard', function (){
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/dashboard.selected', [DashboardController::class, 'selected'])
    ->middleware(['auth', 'verified'])->name('dashboard.selected');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
