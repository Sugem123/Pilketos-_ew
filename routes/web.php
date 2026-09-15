<?php

use App\Http\Controllers\AdminConfigController;
use App\Http\Controllers\AuditSuaraController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BilikController;
use App\Http\Controllers\CalonController;
use App\Http\Controllers\CalonPublicController;
use App\Http\Controllers\CheckTokenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HakSuaraController;
use App\Http\Controllers\LiveCountController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CalonPublicController::class, 'index'])->name('landing');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Aktivasi Pairing PC Bilik Suara (Bisa diakses tanpa login admin)
Route::get('/bilik', [BilikController::class, 'showPairingForm'])->name('bilik.pairing-form');
Route::post('/bilik/pairing', [BilikController::class, 'submitPairing'])->name('bilik.pairing-submit');
Route::post('/bilik/unpair', [BilikController::class, 'unpair'])->name('bilik.unpair');

// Bilik Suara E-Voting — hanya PC yang sudah dipairing kode bilik ATAU admin yang login
Route::middleware(['bilik.auth'])->group(function () {
    Route::post('/check-token', [CheckTokenController::class, 'check'])->name('check-token');

    Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
    Route::post('/voting/vote', [VotingController::class, 'vote'])->name('voting.vote');
});

// Live Counting Public & Projector Display
Route::get('/live-count', [LiveCountController::class, 'index'])->name('live-count');
Route::get('/live-count/data', [LiveCountController::class, 'data'])->name('live-count.data');

// Publikasi Kandidat (publik, tanpa login)
Route::get('/calon', [CalonPublicController::class, 'index'])->name('calon-public.index');
Route::get('/calon/{calon}', [CalonPublicController::class, 'show'])->name('calon-public.show');
Route::post('/cek-dpt', [CalonPublicController::class, 'checkDpt'])->name('calon-public.check-dpt');

// Remote Scanner Pairing untuk Audit Suara (Akses HP Panitia Multi-Device)
Route::get('/audit-suara/remote/{session}', [AuditSuaraController::class, 'remoteView'])->name('audit-suara.remote-view');
Route::post('/audit-suara/remote/join', [AuditSuaraController::class, 'remoteJoin'])->name('audit-suara.remote-join');
Route::get('/audit-suara/remote/status/{session}/{device_id}', [AuditSuaraController::class, 'remoteDeviceStatus'])->name('audit-suara.remote-status');
Route::post('/audit-suara/remote/push', [AuditSuaraController::class, 'remotePush'])->name('audit-suara.remote-push');
Route::post('/audit-suara/remote/disconnect', [AuditSuaraController::class, 'remoteDisconnect'])->name('audit-suara.remote-disconnect');

Route::middleware(['auth', 'desktop'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Mengedit calon / kandidat (foto, biodata, visi, misi, nomor urut) — bisa diakses Admin & Operator
    Route::get('/calon', [CalonController::class, 'index'])->name('calon.index');
    Route::post('/calon', [CalonController::class, 'store'])->name('calon.store');
    Route::post('/calon/{calon}/reorder', [CalonController::class, 'reorder'])->name('calon.reorder');
    Route::put('/calon/{calon}', [CalonController::class, 'update'])->name('calon.update');
    Route::delete('/calon/{calon}', [CalonController::class, 'destroy'])->name('calon.destroy');

    // 2. Menginputkan calon pemilih/DPT — bisa diakses Admin & Operator
    Route::get('/hak-suara', [HakSuaraController::class, 'index'])->name('hak-suara.index');
    Route::post('/hak-suara', [HakSuaraController::class, 'store'])->name('hak-suara.store');
    Route::delete('/hak-suara/{hakSuara}', [HakSuaraController::class, 'destroy'])->name('hak-suara.destroy');
    Route::get('/hak-suara/import', fn () => redirect()->route('hak-suara.index'));
    Route::post('/hak-suara/import', [HakSuaraController::class, 'import'])->name('hak-suara.import');
    Route::get('/hak-suara/download-sample', [HakSuaraController::class, 'downloadSample'])->name('hak-suara.download-sample');

    // 3. Mencetak kartu undangan, kartu pemilih, buku DPT & daftar hadir — bisa diakses Admin & Operator
    Route::get('/cetak/undangan', [PrintController::class, 'undangan'])->name('cetak.undangan');
    Route::get('/cetak/kartu', [PrintController::class, 'kartu'])->name('cetak.kartu');
    Route::get('/cetak/dpt', [PrintController::class, 'daftarDpt'])->name('cetak.dpt');
    Route::get('/cetak/daftar-hadir', [PrintController::class, 'daftarHadir'])->name('cetak.daftar-hadir');

    // 4. Manajemen Bilik Suara TPS (Generate Kode Pairing 1 PC 1 Kode) — bisa diakses Admin & Operator
    Route::get('/bilik', [BilikController::class, 'index'])->name('admin.bilik.index');
    Route::post('/bilik', [BilikController::class, 'store'])->name('admin.bilik.store');
    Route::post('/bilik/{bilik}/reset', [BilikController::class, 'reset'])->name('admin.bilik.reset');
    Route::patch('/bilik/{bilik}/toggle', [BilikController::class, 'toggleActive'])->name('admin.bilik.toggle');
    Route::delete('/bilik/{bilik}', [BilikController::class, 'destroy'])->name('admin.bilik.destroy');

    // ─── KHUSUS ADMINISTRATOR (Operator DILARANG) ───
    Route::middleware(['role.admin'])->group(function () {
        // Cetak Berita Acara Pleno
        Route::get('/cetak/berita-acara', [PrintController::class, 'beritaAcara'])->name('cetak.berita-acara');

        // Audit Suara Manual & Rekonsiliasi TPS
        Route::get('/audit-suara', [AuditSuaraController::class, 'index'])->name('audit-suara.index');
        Route::get('/audit-suara/remote-session', [AuditSuaraController::class, 'createRemoteSession'])->name('audit-suara.create-remote');
        Route::get('/audit-suara/remote-poll/{session}', [AuditSuaraController::class, 'remotePoll'])->name('audit-suara.remote-poll');
        Route::post('/audit-suara/device-action', [AuditSuaraController::class, 'deviceAction'])->name('audit-suara.device-action');
        Route::patch('/audit-suara/{vote}', [AuditSuaraController::class, 'verifySingle'])->name('audit-suara.verify-single');
        Route::post('/audit-suara/quick-verify', [AuditSuaraController::class, 'quickVerifyByToken'])->name('audit-suara.quick-verify');
        Route::post('/audit-suara/hanguskan-sisa', [AuditSuaraController::class, 'hanguskanSisa'])->name('audit-suara.hanguskan-sisa');
        Route::post('/audit-suara/batch-verify', [AuditSuaraController::class, 'batchVerifyAll'])->name('audit-suara.batch-verify');

        // Pengaturan & Manajemen Pengguna
        Route::get('/admin-config', [AdminConfigController::class, 'index'])->name('admin-config.index');
        Route::post('/admin-config/school-profile', [AdminConfigController::class, 'updateSchoolProfile'])->name('admin-config.school-profile');
        Route::post('/admin-config/undangan-template', [AdminConfigController::class, 'updateUndanganTemplate'])->name('admin-config.undangan-template');
        Route::post('/admin-config/voting-settings', [AdminConfigController::class, 'updateVotingSettings'])->name('admin-config.voting-settings');
        Route::post('/admin-config', [AdminConfigController::class, 'store'])->name('admin-config.store');
        Route::put('/admin-config/{user}', [AdminConfigController::class, 'update'])->name('admin-config.update');
        Route::delete('/admin-config/{user}', [AdminConfigController::class, 'destroy'])->name('admin-config.destroy');

        // Display Token Bilik Suara
        Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');
        Route::post('/tokens', [TokenController::class, 'store'])->name('tokens.store');
        Route::patch('/tokens/{token}', [TokenController::class, 'update'])->name('tokens.update');
        Route::delete('/tokens/{token}', [TokenController::class, 'destroy'])->name('tokens.destroy');
    });
});
