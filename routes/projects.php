<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['tenant.module:projects'])->group(function () {
    Route::get('/projects', fn () => view('modules.projects.index'))
        ->name('ta.projects.index');
});
