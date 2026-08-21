<?php

use App\Http\Controllers\AdminConfigController;
use App\Http\Controllers\AuditSuaraController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CalonController;
use App\Http\Controllers\CheckTokenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HakSuaraController;
use App\Http\Controllers\LiveCountController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('voting.index'));

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/check-token', [CheckTokenController::class, 'check'])->name('check-token');

Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
Route::post('/voting/vote', [VotingController::class, 'vote'])->name('voting.vote');

// Live Counting Public & Projector Display
Route::get('/live-count', [LiveCountController::class, 'index'])->name('live-count');
Route::get('/live-count/data', [LiveCountController::class, 'data'])->name('live-count.data');

Route::middleware(['auth', 'desktop'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/calon', [CalonController::class, 'index'])->name('calon.index');
    Route::post('/calon', [CalonController::class, 'store'])->name('calon.store');
    Route::put('/calon/{calon}', [CalonController::class, 'update'])->name('calon.update');
    Route::delete('/calon/{calon}', [CalonController::class, 'destroy'])->name('calon.destroy');

    Route::get('/hak-suara', [HakSuaraController::class, 'index'])->name('hak-suara.index');
    Route::post('/hak-suara', [HakSuaraController::class, 'store'])->name('hak-suara.store');
    Route::delete('/hak-suara/{hakSuara}', [HakSuaraController::class, 'destroy'])->name('hak-suara.destroy');
    Route::get('/hak-suara/import', fn () => redirect()->route('hak-suara.index'));
    Route::post('/hak-suara/import', [HakSuaraController::class, 'import'])->name('hak-suara.import');
    Route::get('/hak-suara/download-sample', [HakSuaraController::class, 'downloadSample'])->name('hak-suara.download-sample');

    // Audit Suara Manual & Rekonsiliasi TPS
    Route::get('/audit-suara', [AuditSuaraController::class, 'index'])->name('audit-suara.index');
    Route::patch('/audit-suara/{vote}', [AuditSuaraController::class, 'verifySingle'])->name('audit-suara.verify-single');
    Route::post('/audit-suara/quick-verify', [AuditSuaraController::class, 'quickVerifyByToken'])->name('audit-suara.quick-verify');
    Route::post('/audit-suara/hanguskan-sisa', [AuditSuaraController::class, 'hanguskanSisa'])->name('audit-suara.hanguskan-sisa');
    Route::post('/audit-suara/batch-verify', [AuditSuaraController::class, 'batchVerifyAll'])->name('audit-suara.batch-verify');

    // Cetak Dokumen Resmi Pemilihan
    Route::get('/cetak/undangan', [PrintController::class, 'undangan'])->name('cetak.undangan');
    Route::get('/cetak/kartu', [PrintController::class, 'kartu'])->name('cetak.kartu');
    Route::get('/cetak/berita-acara', [PrintController::class, 'beritaAcara'])->name('cetak.berita-acara');

    Route::get('/admin-config', [AdminConfigController::class, 'index'])->name('admin-config.index');
    Route::post('/admin-config/school-profile', [AdminConfigController::class, 'updateSchoolProfile'])->name('admin-config.school-profile');
    Route::post('/admin-config/undangan-template', [AdminConfigController::class, 'updateUndanganTemplate'])->name('admin-config.undangan-template');
    Route::post('/admin-config', [AdminConfigController::class, 'store'])->name('admin-config.store');
    Route::put('/admin-config/{user}', [AdminConfigController::class, 'update'])->name('admin-config.update');
    Route::delete('/admin-config/{user}', [AdminConfigController::class, 'destroy'])->name('admin-config.destroy');

    Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');
    Route::post('/tokens', [TokenController::class, 'store'])->name('tokens.store');
    Route::patch('/tokens/{token}', [TokenController::class, 'update'])->name('tokens.update');
    Route::delete('/tokens/{token}', [TokenController::class, 'destroy'])->name('tokens.destroy');
});
