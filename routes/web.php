<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServicePackageController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\JobCardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Vehicles
    Route::resource('vehicles', VehicleController::class);
    Route::get('/customers/{customer}/vehicles', [VehicleController::class, 'getByCustomer'])->name('vehicles.by-customer');

    // Service Categories
    Route::resource('service-categories', ServiceCategoryController::class);

    // Services
    Route::resource('services', ServiceController::class);

    // Service Packages
    Route::resource('service-packages', ServicePackageController::class);

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::post('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');

    // Job Cards
    Route::resource('job-cards', JobCardController::class);
    Route::post('/job-cards/{jobCard}/invoice', [JobCardController::class, 'createInvoice'])->name('job-cards.create-invoice');

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    // Payments
    Route::resource('payments', PaymentController::class);
    Route::get('/invoices/{invoice}/payments/create', [PaymentController::class, 'createForInvoice'])->name('payments.create-for-invoice');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/appointments', [ReportController::class, 'appointments'])->name('appointments');
        Route::get('/services', [ReportController::class, 'services'])->name('services');
    });

    // Settings
    Route::resource('settings', SettingController::class)->only(['index', 'store', 'update']);

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('can:manage_users')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('staff', StaffController::class);
    });
});
