<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemasukkanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout'); 

    //pemasukkan
    Route::get('/pemasukkan', [PemasukkanController::class, 'index'])->name('pemasukkan');
    Route::post('/pemasukkan/create', [PemasukkanController::class, 'create'])->name('pemasukkan.create');
    Route::delete('/pemasukkan/delete/{id}', [PemasukkanController::class, 'destroy'])->name('pemasukkan.delete');

    //pengeluaran
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');
    Route::post('/pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::delete('/pengeluaran/delete/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.delete');

    // tabungan
    Route::get('/tabungan', [TabunganController::class, 'index'])->name('tabungan');
    Route::post('/tabungan/create', [TabunganController::class, 'create'])->name('tabungan.create');
    Route::put('/tabungan/{id}', [TabunganController::class, 'update'])->name('tabungan.update');
    Route::delete('/tabungan/delete/{id}', [TabunganController::class, 'destroy'])->name('tabungan.delete');

    // chatbot
    Route::get('/chatbot', [ChatbotController::class, 'showChat'])->name('chatbot.show');
    Route::post('/chatbot/message', [ChatbotController::class, 'processMessage'])->name('chatbot.message');

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
