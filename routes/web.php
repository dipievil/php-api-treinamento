<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SwaggerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

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
    return view('welcome');
});

// Documentation routes
Route::group(['prefix' => 'api'], function () {
    Route::get('documentation', [SwaggerController::class, 'index'])->name('api.documentation');
});
