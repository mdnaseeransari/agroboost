<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('crops', CropController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('tasks', TaskController::class);
    Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/team', [SettingsController::class, 'team'])->name('settings.team');
    Route::get('/settings/farm', [SettingsController::class, 'farm'])->name('settings.farm');
    Route::patch('/settings/farm', [SettingsController::class, 'updateFarm'])->name('settings.farm.update');
    Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::patch('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
    Route::patch('/settings/security', [SettingsController::class, 'updateSecurity'])->name('settings.security.update');
    // Notifications Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::view('/help', 'help')->name('help.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
