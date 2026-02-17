<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['tenant.module:tasks'])->group(function () {
    Route::get('/tasks', fn () => view('modules.tasks.index'))
        ->name('ta.tasks.index');
});
