<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\User\LinkController as UserLinkController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LinkController as AdminLinkController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/', [PublicController::class, 'shorten'])->name('shorten');

// Shortener route dipindahkan ke paling bawah agar tidak menabrak Auth/Dashboard

// ============================================================
// AUTH ROUTES (Breeze)
// ============================================================
require __DIR__.'/auth.php';

// ============================================================
// USER PANEL (authenticated)
// ============================================================
Route::middleware(['auth'])->prefix('dashboard')->name('user.')->group(function () {
    Route::get('/', [UserLinkController::class, 'index'])->name('links.index');
    Route::get('/links/create', [UserLinkController::class, 'create'])->name('links.create');
    Route::post('/links', [UserLinkController::class, 'store'])->name('links.store');
    Route::get('/links/{link}', [UserLinkController::class, 'show'])->name('links.show');
    Route::get('/links/{link}/edit', [UserLinkController::class, 'edit'])->name('links.edit');
    Route::get('/links/{link}/export', [\App\Http\Controllers\User\LinkExportController::class, 'csv'])->name('links.export');
    Route::put('/links/{link}', [UserLinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{link}', [UserLinkController::class, 'destroy'])->name('links.destroy');

    // Settings & API Tokens
    Route::get('/settings', [\App\Http\Controllers\User\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/token', [\App\Http\Controllers\User\SettingsController::class, 'generateToken'])->name('settings.token');
    Route::delete('/settings/token/{id}', [\App\Http\Controllers\User\SettingsController::class, 'revokeToken'])->name('settings.token.revoke');

    // Link in Bio
    Route::get('/bio', [\App\Http\Controllers\User\BioPageController::class, 'edit'])->name('bio.edit');
    Route::put('/bio', [\App\Http\Controllers\User\BioPageController::class, 'update'])->name('bio.update');

    // Workspaces (Tim)
    Route::post('/workspaces/switch', [\App\Http\Controllers\User\WorkspaceSessionController::class, 'switch'])->name('workspaces.switch');
    Route::get('/workspaces', [\App\Http\Controllers\User\WorkspaceController::class, 'index'])->name('workspaces.index');
    Route::post('/workspaces', [\App\Http\Controllers\User\WorkspaceController::class, 'store'])->name('workspaces.store');
    Route::get('/workspaces/{workspace}', [\App\Http\Controllers\User\WorkspaceController::class, 'show'])->name('workspaces.show');
    Route::put('/workspaces/{workspace}', [\App\Http\Controllers\User\WorkspaceController::class, 'update'])->name('workspaces.update');
    Route::delete('/workspaces/{workspace}', [\App\Http\Controllers\User\WorkspaceController::class, 'destroy'])->name('workspaces.destroy');
    Route::post('/workspaces/{workspace}/members', [\App\Http\Controllers\User\WorkspaceController::class, 'addMember'])->name('workspaces.members.add');
    Route::patch('/workspaces/{workspace}/members/{user}', [\App\Http\Controllers\User\WorkspaceController::class, 'updateMemberRole'])->name('workspaces.members.update');
    Route::delete('/workspaces/{workspace}/members/{user}', [\App\Http\Controllers\User\WorkspaceController::class, 'removeMember'])->name('workspaces.members.remove');
});

// ============================================================
// ADMIN PANEL
// ============================================================
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Links
    Route::get('/links', [AdminLinkController::class, 'index'])->name('links.index');
    Route::get('/links/{link}', [AdminLinkController::class, 'show'])->name('links.show');
    Route::patch('/links/{link}/toggle', [AdminLinkController::class, 'toggleActive'])->name('links.toggle');
    Route::delete('/links/{link}', [AdminLinkController::class, 'destroy'])->name('links.destroy');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

// ============================================================
// SHORTLINK REDIRECT WIlDCARD (HARUS PALING BAWAH)
// ============================================================
Route::get('/u/{slug}', [\App\Http\Controllers\PublicBioController::class, 'show'])->name('bio.show');

Route::get('/{code}', [PublicController::class, 'redirect'])
    ->where('code', '[a-zA-Z0-9_-]+')
    ->name('redirect');

Route::post('/link/{code}/password', [PublicController::class, 'checkPassword'])
    ->name('link.password');
