<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.login');
});

Route::view('login', 'pages.login');
Route::view('balance', 'pages.balance');
Route::view('register', 'pages.register');
Route::view('withdrawls', 'pages.withdrawls');
Route::view('users', 'pages.user');
Route::view('account', 'pages.account');
Route::view('dashboard', 'pages.dashboard');
Route::view('about', 'pages.about');
Route::view('merchants', 'pages.merchant');
Route::view('merchant-data', 'pages.merchant_data');
Route::view('transactions', 'pages.transactions');
Route::view('conversion', 'pages.conversion');
Route::view('support', 'pages.support');
Route::view('home', 'pages.home');


