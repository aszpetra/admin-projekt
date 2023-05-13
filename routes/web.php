<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftEmployeeController;
use App\Http\Controllers\ShiftLogController;
use App\Http\Controllers\UserController;
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
})->middleware('admin');

Route::resource('employees', EmployeeController::class)
    ->only(['index', 'store', 'create', 'edit', 'destroy', 'update'])
    ->middleware(['auth', 'admin']);

Route::resource('companies', CompanyController::class)
    ->only(['index', 'store', 'create', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified', 'admin']);

Route::resource('shifts', ShiftController::class)
    ->only(['index', 'store', 'create', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified', 'admin']);

Route::resource('holidays', HolidayController::class)
    ->only(['index', 'store', 'create', 'edit', 'destroy', 'update'])
    ->middleware(['auth', 'verified', 'admin']);

Route::resource('shift_employee', ShiftEmployeeController::class)
    ->only(['index', 'store', 'create', 'edit', 'destroy', 'update', 'show'])
    ->middleware(['auth', 'verified', 'admin']);

Route::resource('shift_log', ShiftLogController::class)
    ->only(['store', 'destroy'])
    ->middleware(['auth', 'verified', 'admin']);

Route::get('/all_employees', [EmployeeController::class, 'show'])
    ->middleware(['auth', 'verified', 'admin']);

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])->name('welcome');

Route::get('welcome', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])->name('welcome');

Route::get('dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::post('/dashboard.selected', [DashboardController::class, 'selected'])
    ->middleware(['auth', 'verified', 'admin'])->name('dashboard.selected');

Route::get('/events', [ShiftLogController::class, 'calendarEvents'])
    ->middleware(['auth', 'verified', 'admin']);

Route::get('/general_edit', [EmployeeController::class, 'general_edit'])
    ->middleware(['auth', 'verified', 'admin'])->name('general_edit');

Route::post('/general_update', [EmployeeController::class, 'general_update'])
    ->middleware(['auth', 'verified', 'admin'])->name('general_update');;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/home', 'index')
        ->name('home');
    Route::get('/schedule', 'getSchedule');
    Route::get('/holiday_list', 'holiday_index')
        ->name('holiday_list');
    Route::get('/holiday_create', 'holiday_create')
        ->name('holiday_create');
    Route::post('/holiday_store', 'holiday_store')
        ->name('holiday_store');
    Route::patch('/holiday_update', 'holiday_update')
        ->name('holiday_update');
}) ->middleware(['auth', 'verified']);

Route::get('/holiday_edit', [UserController::class, 'holiday_edit'])
    ->name('holiday_edit');

require __DIR__.'/auth.php';
