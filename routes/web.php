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
    return view('welcome');
});

Route::resource('api/location', App\Http\Controllers\LocationController::class);
Route::resource('api/role', App\Http\Controllers\RoleController::class);
Route::resource('api/user', App\Http\Controllers\UserController::class);
Route::resource('api/item', App\Http\Controllers\ItemController::class);
Route::resource('api/borrower', App\Http\Controllers\BorrowerController::class);
Route::resource('api/transaction', App\Http\Controllers\TransactionController::class);
Route::resource('api/dashboard', App\Http\Controllers\DashboardController::class);
Route::resource('api/detailtransaction', App\Http\Controllers\DetailTransactionController::class);
Route::resource('api/major', App\Http\Controllers\MajorController::class);

Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
//API route for login user
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::get('/login', [App\Http\Controllers\API\AuthController::class, 'index']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});
