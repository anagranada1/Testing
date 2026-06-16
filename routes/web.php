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
