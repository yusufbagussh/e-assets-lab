<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);


Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);

// Route::resource('dashboard', App\Http\Controllers\DashboardController::class);

// Route::get('transaksibydate', [App\Http\Controllers\DashboardController::class, 'getDataTransaksibyDate']);


//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('location', App\Http\Controllers\LocationController::class);
    Route::resource('role', App\Http\Controllers\RoleController::class);
    Route::resource('user', App\Http\Controllers\UserController::class);
    Route::resource('item', App\Http\Controllers\ItemController::class);
    Route::resource('borrower', App\Http\Controllers\BorrowerController::class);
    Route::resource('transaction', App\Http\Controllers\TransactionController::class);
    Route::resource('dashboard', App\Http\Controllers\DashboardController::class);
    Route::get('transaksibydate', [App\Http\Controllers\DashboardController::class, 'getDataTransaksibyDate']);
    Route::resource('detailtransaction', App\Http\Controllers\DetailTransactionController::class);
    Route::resource('major', App\Http\Controllers\MajorController::class);

    Route::get('export', [App\Http\Controllers\TransactionController::class, 'export']);

    Route::post('updateitem/{id}', [App\Http\Controllers\ItemController::class, 'updateItem']);
});
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
// Route::resource('transaction', App\Http\Controllers\TransactionController::class);
