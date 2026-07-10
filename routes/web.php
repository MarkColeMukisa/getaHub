<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TenantController;
use App\Models\Tenant;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('test', function (): never {
    dd('test');
});

Route::get('/', function (): Factory|View {
    return view('welcome');
})->name('welcome');

Route::get('/calculator', function (): Factory|View {
    $tenants = Tenant::orderBy('name')->get();

    return view('calculator', ['tenants' => $tenants]);
})->name('calculator');

// Redirect /index to the calculator tool for consistency with prior behavior
Route::redirect('/index', '/calculator');

Route::get('/dashboard', [TenantController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated user routes (no admin requirement)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Bills
    Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
    Route::get('/tenants/{tenant}/previous-reading', [BillController::class, 'previousReading'])->name('tenants.previous-reading');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
});

// Admin-only tenant management routes
Route::middleware(['auth', 'can:manage-tenants'])->group(function () {
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    Route::patch('/tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');
    Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');
    Route::get('/tenants-export', [TenantController::class, 'exportCsv'])->name('tenants.export');
    Route::view('/admin/users', 'admin.users')->name('admin.users');
});

// Route::get('/send', [SmsController::class, 'send']);

require __DIR__.'/auth.php';
