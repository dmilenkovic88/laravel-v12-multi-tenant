<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['tenant.module:employees'])->group(function () {
    Route::get('/employees', fn () => view('modules.employees.index'))
        ->name('ta.employees.index');
});
