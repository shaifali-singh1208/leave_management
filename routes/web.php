<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::resource('leave-type', LeaveTypeController::class, ['as' => 'admin']);

    Route::resource('manager', ManagerController::class, ['as' => 'admin']);

    Route::resource('employee', EmployeeController::class, ['as' => 'admin']);

    Route::get('leave-applications', [LeaveApplicationController::class, 'adminIndex'])->name('admin.leave-applications.index');

    Route::patch('leave-applications/{leaveApplication}/status', [LeaveApplicationController::class, 'adminUpdateStatus'])->name('admin.leave-applications.status');
});

Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {

    Route::get('leave-applications', [LeaveApplicationController::class, 'managerIndex'])->name('leave-applications.index');

    Route::patch('leave-applications/{leaveApplication}/review', [LeaveApplicationController::class, 'managerReview'])->name('leave-applications.review');
});

Route::middleware(['auth', 'employee'])->prefix('employee')->name('employee.')->group(function () {

    Route::resource('leave-applications', LeaveApplicationController::class)
        ->only(['index', 'create', 'store', 'destroy']);
});

require __DIR__ . '/auth.php';
