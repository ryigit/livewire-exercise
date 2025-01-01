<?php

use App\Livewire\Opportunities;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/opportunities', Opportunities::class);
