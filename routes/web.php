<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear-cache', function () {
    try {
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('config:cache');
        return 'DONE'; //Return anything
    } catch (Throwable $th) {
        //throw $th;
    }
});


Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/example', function () {
    return Inertia\Inertia::render('Example');
})->name('example');

Route::get('/', function () {
    return Inertia\Inertia::render('Inicio');
})->name('inicio');

Route::get('/quienSoy', function () {
    return Inertia\Inertia::render('QuienSoy');
})->name('quienSoy');

Route::get('/proyectos', function () {
    return Inertia\Inertia::render('Proyectos');
})->name('proyectos');

Route::get('/articulo', function () {
    return Inertia\Inertia::render('Articulo');
})->name('articulo');

Route::get('/servicios', function () {
    return Inertia\Inertia::render('Servicios');
})->name('servicios');

Route::get('/contacto', function () {
    return Inertia\Inertia::render('Contacto');
})->name('contacto');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
