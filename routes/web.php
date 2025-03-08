<?php

use App\Http\Controllers\bill\BillController;
use App\Http\Controllers\bill\FundController;
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
    Route::get('/order',[BillController::class,'orderView'])->name('order');
    Route::post('/bill',[BillController::class,'create_order'])->name('create.order');
    Route::get('/orderlist',[BillController::class,'orderlist'])->name('orderlist');

    Route::get('bill_details/{action}/{id}',[BillController::class,'bill_details'])->name('customer.viewBill');

    
});

#write here only admin access Routes
Route::group(['middleware' => 'admin.access'], function(){

    Route::get('/menu_list', [BillController::class,'menulist'])->name('menulist');
    Route::get('/menu_add', function () {return view('dashboard.menu_add');})->name('menu_add');
    Route::Post('/menu_store',[BillController::class,'menu_store'])->name('menu_store');
    Route::get('/add-fund',[FundController::class,'fundView'])->name('add_fund_view');
    Route::post('/store_fund',[FundController::class,'store_fund'])->name('store_fund');
    Route::get('/expens-fund',[FundController::class,'expensfundView'])->name('expens_fund_view');
    Route::post('/store-expens-fund',[FundController::class,'store_expens_fund'])->name('store_expens_fund');
    Route::get('/expense-fund-list',[FundController::class,'expens_fund_list'])->name('expens_fund_list');
    


});

