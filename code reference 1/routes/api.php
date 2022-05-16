<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiEmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    echo 'Welcome to employee app API';
});


Route::get('getEmployeeList', 'App\Http\Controllers\ApiEmployeeController@getEmployeeList');
Route::post('createUpdateEmployee', 'App\Http\Controllers\ApiEmployeeController@createUpdateEmployee');
Route::get('destoryEmployeeData/{id}', 'App\Http\Controllers\ApiEmployeeController@destoryEmployeeData');