<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/manage-slider', [App\Http\Controllers\SliderController::class, 'index'])->name('manage.slider');

    Route::get('/search-images', [App\Http\Controllers\SliderController::class, 'searchImages']);
    Route::post('/save-images', [App\Http\Controllers\SliderController::class, 'saveImages']);
    Route::delete('/delete-image/{id}', [App\Http\Controllers\SliderController::class, 'deleteImage']);

});

