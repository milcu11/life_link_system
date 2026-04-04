<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset routes
Route::prefix('password')->name('password.')->group(function () {
    Route::get('/forgot', [PasswordResetController::class, 'showForgotPasswordForm'])->name('forgot');
    Route::post('/send-code', [PasswordResetController::class, 'sendResetCode'])->name('send-code');
    Route::get('/verify', [PasswordResetController::class, 'showVerifyForm'])->name('verify');
    Route::post('/verify', [PasswordResetController::class, 'verifyCode'])->name('verify.check');
    Route::get('/reset', [PasswordResetController::class, 'showResetForm'])->name('reset');
    Route::post('/reset', [PasswordResetController::class, 'resetPassword'])->name('reset.confirm');
    Route::post('/resend', [PasswordResetController::class, 'resendCode'])->name('resend');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Donor routes
    Route::middleware(['role:donor'])->prefix('donor')->name('donor.')->group(function () {
        Route::get('/profile', [DonorController::class, 'profile'])->name('profile');
        Route::post('/profile', [DonorController::class, 'updateProfile'])->name('profile.update');
        Route::get('/history', [DonorController::class, 'history'])->name('history');
        Route::post('/availability', [DonorController::class, 'updateAvailability'])->name('availability');
        Route::get('/requests', [DonorController::class, 'viewRequests'])->name('requests');
        Route::post('/respond/{match}', [DonorController::class, 'respondToMatch'])->name('respond');
        // Denied/appeal flow
        Route::get('/denied', [\App\Http\Controllers\DonorAppealController::class, 'denied'])->name('denied');
        Route::post('/appeal', [\App\Http\Controllers\DonorAppealController::class, 'store'])->name('appeal.store');

        // Scheduling and appointment routes
        Route::get('/drives', [\App\Http\Controllers\Donor\AppointmentController::class, 'index'])->name('drives.index');
        Route::post('/drives/{drive}/book', [\App\Http\Controllers\Donor\AppointmentController::class, 'book'])->name('drives.book');
        Route::put('/appointments/{appointment}/cancel', [\App\Http\Controllers\Donor\AppointmentController::class, 'cancel'])->name('appointments.cancel');

        // Messages routes
        Route::get('/messages', [\App\Http\Controllers\Donor\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{hospital}', [\App\Http\Controllers\Donor\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{hospital}', [\App\Http\Controllers\Donor\MessageController::class, 'store'])->name('messages.store');
    });
    
    // Hospital routes
    Route::middleware(['role:hospital'])->prefix('hospital')->name('hospital.')->group(function () {
        Route::get('/requests', [BloodRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [BloodRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [BloodRequestController::class, 'store'])->name('requests.store');
        Route::get('/requests/{request}', [BloodRequestController::class, 'show'])->name('requests.show');
        Route::put('/requests/{request}', [BloodRequestController::class, 'update'])->name('requests.update');
        Route::delete('/requests/{request}', [BloodRequestController::class, 'destroy'])->name('requests.destroy');
        Route::get('/matches/{request}', [MatchingController::class, 'viewMatches'])->name('matches');
        
        // Donation routes
        Route::put('/donations/{donation}/complete', [DonationController::class, 'complete'])->name('donations.complete');
        Route::put('/donations/{donation}/cancel', [DonationController::class, 'cancel'])->name('donations.cancel');

        // Inventory routes
        Route::resource('inventory', \App\Http\Controllers\Hospital\InventoryController::class);

        // Reports routes
        Route::get('reports', [\App\Http\Controllers\Hospital\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [\App\Http\Controllers\Hospital\ReportController::class, 'export'])->name('reports.export');

        // Scheduling routes
        Route::resource('drives', \App\Http\Controllers\Hospital\BloodDriveController::class);
        Route::post('drives/{drive}/cancel', [\App\Http\Controllers\Hospital\BloodDriveController::class, 'cancel'])->name('drives.cancel');

        // Messages routes
        Route::get('messages', [\App\Http\Controllers\Hospital\MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{donor}', [\App\Http\Controllers\Hospital\MessageController::class, 'show'])->name('messages.show');
        Route::post('messages/{donor}', [\App\Http\Controllers\Hospital\MessageController::class, 'store'])->name('messages.store');
    });
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/donors', [AdminController::class, 'donors'])->name('donors');
        Route::get('/donors/{donor}', [AdminController::class, 'donorShow'])->name('donors.show');
        Route::post('/donors/{donor}/approve', [AdminController::class, 'approveDonor'])->name('donors.approve');
        Route::post('/donors/{donor}/reject', [AdminController::class, 'rejectDonor'])->name('donors.reject');
        Route::get('/donors/{donor}/download', [AdminController::class, 'downloadVerificationDocument'])->name('donors.download');
        Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
        Route::get('/donations', [AdminController::class, 'donations'])->name('donations');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/map', [AdminController::class, 'map'])->name('map');
        // Appeals management
        Route::get('/appeals', [AdminController::class, 'appeals'])->name('appeals');
        Route::get('/appeals/{appeal}', [AdminController::class, 'appealShow'])->name('appeals.show');
        Route::get('/appeals/{appeal}/download', [AdminController::class, 'appealDownload'])->name('appeals.download');
        Route::post('/appeals/{appeal}/review', [\App\Http\Controllers\AdminController::class, 'appealReview'])->name('appeals.review');
    });
    
    // Notification routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });
    
    // Matching routes
    Route::post('/match/{request}', [MatchingController::class, 'findMatches'])->name('match.find');
});