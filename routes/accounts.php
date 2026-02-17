<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['tenant.module:accounts'])->group(function () {
    Route::get('/accounts', fn () => view('modules.accounts.index'))
        ->name('ta.accounts.index');
});
