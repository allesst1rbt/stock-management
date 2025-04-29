<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', function () {
    return Inertia::render('Login');
})->name('login');
Route::get('/', function () {
    redirect('/login');
});
Route::middleware(['token', 'jwt:web'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');
    Route::prefix('dashboard')->group(function () {
        Route::get('/stocks', fn () => Inertia::render('Stocks'));
        Route::get('/categories', fn () => Inertia::render('Categories'));
        Route::get('/users', fn () => Inertia::render('Users'));
    });

});
