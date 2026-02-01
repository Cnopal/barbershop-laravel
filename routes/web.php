<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AppointmentController;

use App\Http\Controllers\Customer\AppointmentController as CustomerAppointmentController;
use App\Http\Controllers\Customer\CustomerServiceController;


use App\Http\Controllers\Staff\AppointmentController as StaffAppointmentController;
use App\Http\Controllers\Staff\ScheduleController;
use App\Http\Controllers\Staff\FeedbackController;
use App\Http\Controllers\Staff\ServiceController as StaffServiceController;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Customer\AiHairController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
// use App\Http\Controllers\StripeController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('staffs', StaffController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('customers', CustomerController::class);

    // ✅ Available slots route MUST be before resource route to avoid conflicts
    Route::get('/appointments/available-slots', [AppointmentController::class, 'availableSlots'])
        ->name('appointments.available-slots');
    Route::resource('appointments', AppointmentController::class);
});



// ================= STAFF =================
Route::middleware(['auth', 'role:staff'])->prefix('staff')->as('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // Appointments - slots route must be before resource routes
    Route::get('/appointments/slots/available', [StaffAppointmentController::class, 'getAvailableSlots'])->name('appointments.slots');
    Route::resource('appointments', StaffAppointmentController::class);

    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

    // Feedbacks
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');

    // Services
    Route::get('/services', [StaffServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [StaffServiceController::class, 'show'])->name('services.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    // Customer creation endpoints
    Route::post('/appointments/check-customer', [AppointmentController::class, 'checkCustomer'])->name('appointments.check-customer');
    Route::post('/appointments/create-customer', [AppointmentController::class, 'createCustomer'])->name('appointments.create-customer');


});


// ================= CUSTOMER =================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->as('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('appointments', CustomerAppointmentController::class);
    Route::resource('services', CustomerServiceController::class);
    Route::patch('/appointments/{appointment}/cancel', [CustomerAppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/slots/available', [CustomerAppointmentController::class, 'getAvailableSlots'])
        ->name('appointments.slots');
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');


    // STRIPE PAYMENT
    Route::get('/appointments/{appointment}/pay', [CustomerAppointmentController::class, 'pay'])->name('appointments.pay');
    Route::get('/appointments/{appointment}/payment/success', [CustomerAppointmentController::class, 'paymentSuccess'])->name('appointments.payment.success');
    Route::get('/appointments/{appointment}/payment/cancel', [CustomerAppointmentController::class, 'paymentCancel'])->name('appointments.payment.cancel');

    // AI HAIR
    Route::get('/ai-hair', [AiHairController::class, 'index'])->name('ai-hair.index');
    Route::post('/ai-hair', [AiHairController::class, 'analyze'])->name('ai-hair.analyze');
    Route::get('/ai-hair/health', [AiHairController::class, 'checkApiHealth'])->name('customer.ai-hair.health');
});


