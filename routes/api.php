<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/get_data', [\App\Http\Controllers\MainController::class, 'getData'])->name('get_data');
