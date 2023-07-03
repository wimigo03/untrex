<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {

    
    //Route::post('/login/store', [LoginController::class, 'store'])->name('login.store');
    //route::get('/login', 'App\Http\Controllers\Admin\LoginController@index')->name('login');

});
