<?php

use Illuminate\Http\Request;
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

// `Route::resource('api/location', App\Http\Controllers\LocationController::class);
// Route::resource('api/role', App\Http\Controllers\RoleController::class);
// // Route::resource('api/user', App\Http\Controllers\UserController::class);
// // Route::resource('api/item', App\Http\Controllers\ItemController::class);
// Route::resource('api/borrower', App\Http\Controllers\BorrowerController::class);
// Route::resource('api/transaction', App\Http\Controllers\TransactionController::class);
// Route::resource('api/dashboard', App\Http\Controllers\DashboardController::class);
// Route::resource('api/detailtransaction', App\Http\Controllers\DetailTransactionController::class);
// Route::resource('api/major', App\Http\Controllers\MajorController::class);

// Route::get('api/export', [App\Http\Controllers\TransactionController::class, 'export']);

// Route::post('api/updateitem/{id}', [App\Http\Controllers\ItemController::class, 'updateItem']);

// Route::post('api/register', [App\Http\Controllers\AuthController::class, 'register']);

// Route::post('api/logout', [App\Http\Controllers\AuthController::class, 'logout']);

// Route::post('api/login', [App\Http\Controllers\AuthController::class, 'login']);

// //Protecting Routes
// Route::group(['middleware' => ['auth:sanctum']], function () {
//     // Route::resource('api/dashboard', App\Http\Controllers\DashboardController::class);
//     // Route::resource('api/item', App\Http\Controllers\ItemController::class);
//     Route::resource('api/user', App\Http\Controllers\UserController::class);
//     Route::resource('api/item', App\Http\Controllers\ItemController::class);
// });

// // Route::middleware('auth:sanctum')->get('/api/user', function (Request $request) {
// //     return $request->user();
// // });

// Route::middleware('auth:sanctum')->get('api/authenticated', function () {
//     return true;
// });`
