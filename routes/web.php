<?php

use App\Http\Controllers\bill\BillController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\AuthenticationController;

//frontend routes
Route::get('/', function () { return view('frontend/welcome'); })->name('home');
Route::get('/menu', function () { return view('frontend.menu'); })->name('menu');
Route::get('/services', function () { return view('frontend.services'); })->name('services');
Route::get('/blog', function () { return view('frontend.blog'); })->name('blog');
Route::get('/about', function () { return view('frontend.about'); })->name('about');
Route::get('/contact', function () { return view('frontend.contact'); })->name('contact');


// backend routes 
Route::group(['middleware'=> 'guest'],function()
{
    Route::get('/login', function () {return view('dashboard.login');})->name('login');
    Route::post('/authentication', [AuthenticationController::class, 'authentication'])->name('user.authentication');

});


Route::group(['middleware' => 'auth'], function()
{
    //authenticates routes
    Route::get('/new', function () {return view('dashboard.new');})->name('new');
    Route::get('/logout', [AuthenticationController::class, 'staff_logout'])->name('logout'); 
    Route::get('/order', function () {return view('dashboard.orderView');})->name('order');
    Route::post('/bill',[BillController::class,'create_order'])->name('create.order');
    
});
