<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified', 'active', 'tenant.db'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // DEBUG: proveri tenant DB
    Route::get('/debug-db', function () {
        return response()->json([
            'default_connection' => \Illuminate\Support\Facades\DB::getDefaultConnection(),
            'tenant_database' => \Illuminate\Support\Facades\DB::connection('tenant')->getDatabaseName(),
        ]);
    })->name('ta.debug-db');

    // NAPOMENA: Ovaj middleware je obavezan za tenant rute!
    Route::middleware(['tenant.access'])->group(function () {

        require __DIR__.'/accounts.php';
        require __DIR__.'/contacts.php';
        require __DIR__.'/employees.php';
        require __DIR__.'/projects.php';
        require __DIR__.'/tasks.php';

    });



});

require __DIR__.'/settings.php';
