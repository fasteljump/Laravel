<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\TicketValidationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'login'])->name('login');
Route::get('/login', [PageController::class, 'login']);
Route::get('/inscription', [PageController::class, 'register'])->name('register');
Route::get('/mot-de-passe-oublie', [PageController::class, 'forgot'])->name('forgot-password');
Route::match(['get', 'post'], '/profil', [PageController::class, 'profile'])->name('profile');
Route::match(['get', 'post'], '/parametres', [PageController::class, 'settings'])->name('settings');

Route::get('/tableau-de-bord', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('projets')->name('projects.')->group(function (): void {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/creation', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/detail/{project?}', [ProjectController::class, 'show'])->name('show');
    Route::get('/edition/{project?}', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
});

Route::prefix('tickets')->name('tickets.')->group(function (): void {
    Route::get('/', [TicketController::class, 'index'])->name('index');
    Route::get('/creation', [TicketController::class, 'create'])->name('create');
    Route::post('/', [TicketController::class, 'store'])->name('store');
    Route::get('/detail/{ticket?}', [TicketController::class, 'show'])->name('show');
    Route::get('/edition/{ticket?}', [TicketController::class, 'edit'])->name('edit');
    Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
    Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');

    Route::post('/{ticket}/time-entries', [TimeEntryController::class, 'store'])->name('time_entries.store');
    Route::delete('/{ticket}/time-entries/{timeEntry}', [TimeEntryController::class, 'destroy'])->name('time_entries.destroy');
    Route::post('/{ticket}/validation/accept', [TicketValidationController::class, 'accept'])->name('validation.accept');
    Route::post('/{ticket}/validation/refuse', [TicketValidationController::class, 'refuse'])->name('validation.refuse');
});
