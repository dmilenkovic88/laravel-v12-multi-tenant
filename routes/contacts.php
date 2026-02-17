<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['tenant.module:contacts'])->group(function () {
    Route::get('/contacts', fn () => view('modules.contacts.index'))
        ->name('ta.contacts.index');
});
