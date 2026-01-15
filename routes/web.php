<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/workbench', function () {
    return view('workbench');
})->name('workbench');
