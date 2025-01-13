<?php

use Illuminate\Support\Facades\Route;

//frontend routes
Route::get('/', function () { return view('frontend/welcome'); })->name('home');
Route::get('/menu', function () { return view('frontend.menu'); })->name('menu');
Route::get('/services', function () { return view('frontend.services'); })->name('services');
Route::get('/blog', function () { return view('frontend.blog'); })->name('blog');
Route::get('/about', function () { return view('frontend.about'); })->name('about');
Route::get('/contact', function () { return view('frontend.contact'); })->name('contact');


// backend routes 
Route::get('/login', function () {return view('dashboard.login');})->name('login');
