<?php

use App\Http\Controllers\ServiceFormController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::get('/forms/{slug}', [ServiceFormController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('forms.show');

Route::post('/forms/{slug}/execute', [ServiceFormController::class, 'execute'])
    ->where('slug', '[a-z0-9-]+')
    ->name('forms.execute');

// Transbank devuelve al tarjetahabiente por POST en 1.0 y por GET desde 1.2.
Route::match(['get', 'post'], '/forms/{slug}/return', [ServiceFormController::class, 'redirectReturn'])
    ->where('slug', '[a-z0-9-]+')
    ->name('forms.return');

Route::post('/finalUrl', [ServiceFormController::class, 'finalUrl'])
    ->name('finalUrl');

Route::post('/url', [ServiceFormController::class, 'url'])
    ->name('url');
