<?php

use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PosController::class, 'index'])->name('pos.index');
Route::post('/orders', [PosController::class, 'store'])->name('pos.store');
