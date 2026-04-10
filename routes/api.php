<?php

use App\Http\Controllers\Api\TimeEntryApiController;
use Illuminate\Support\Facades\Route;

Route::post('/tickets/{ticket}/time-entries', [TimeEntryApiController::class, 'store'])->name('api.time_entries.store');
