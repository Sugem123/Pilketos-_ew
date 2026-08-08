<?php

use App\Http\Controllers\AdminConfigController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CalonController;
use App\Http\Controllers\CheckTokenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HakSuaraController;
use App\Http\Controllers\LaporanController;
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

Route::middleware(['auth', 'desktop'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/calon', [CalonController::class, 'index'])->name('calon.index');
    Route::post('/calon', [CalonController::class, 'store'])->name('calon.store');
    Route::get('/calon/{calon}/edit', [CalonController::class, 'edit'])->name('calon.edit');
    Route::put('/calon/{calon}', [CalonController::class, 'update'])->name('calon.update');
    Route::delete('/calon/{calon}', [CalonController::class, 'destroy'])->name('calon.destroy');
    Route::post('/calon/haksuara', [CalonController::class, 'updateHaksuara'])->name('calon.haksuara');

    Route::get('/hak-suara', [HakSuaraController::class, 'index'])->name('hak-suara.index');
    Route::post('/hak-suara', [HakSuaraController::class, 'store'])->name('hak-suara.store');
    Route::delete('/hak-suara/{hakSuara}', [HakSuaraController::class, 'destroy'])->name('hak-suara.destroy');
    Route::post('/hak-suara/import', [HakSuaraController::class, 'import'])->name('hak-suara.import');
    Route::get('/hak-suara/download-sample', [HakSuaraController::class, 'downloadSample'])->name('hak-suara.download-sample');

    Route::get('/admin-config', [AdminConfigController::class, 'index'])->name('admin-config.index');
    Route::post('/admin-config', [AdminConfigController::class, 'store'])->name('admin-config.store');
    Route::put('/admin-config/{user}', [AdminConfigController::class, 'update'])->name('admin-config.update');
    Route::delete('/admin-config/{user}', [AdminConfigController::class, 'destroy'])->name('admin-config.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');
    Route::post('/tokens', [TokenController::class, 'store'])->name('tokens.store');
    Route::patch('/tokens/{token}', [TokenController::class, 'update'])->name('tokens.update');
    Route::delete('/tokens/{token}', [TokenController::class, 'destroy'])->name('tokens.destroy');
});
