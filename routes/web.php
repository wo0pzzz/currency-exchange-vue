<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('main');
//Route::get('/get_data', [\App\Http\Controllers\MainController::class, 'getData'])->name('get_data');
