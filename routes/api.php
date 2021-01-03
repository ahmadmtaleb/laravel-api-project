<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ImagesController;



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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
  
Route::group(['middleware' => 'auth.jwt'], function () {
 
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('items', [ItemsController::class, 'index']);
    Route::get('items/{id}', [ItemsController::class, 'show']);
    Route::post('items/create', [ItemsController::class, 'store']);
    Route::put('items/edit/{id}', [ItemsController::class, 'update']);
    Route::delete('items/delete/{id}', [ItemsController::class, 'destroy']);

    // adding a new item with one image
    Route::post('items/add-new-item', [ItemsController::class, 'addNewItem']);

    // adding a new item with one image
    Route::post('items/add-new-item-b', [ItemsController::class, 'addNewItem_b']);

    Route::get('images', [ImagesController::class, 'index']);
    Route::get('images/{id}', [ImagesController::class, 'show']);
    Route::post('images/upload', [ImagesController::class, 'store']);
    Route::put('images/edit/{id}', [ImagesController::class, 'update']);
    Route::delete('images/delete/{id}', [ImagesController::class, 'destroy']);
    
    Route::post('items/add-new-item', [ItemsController::class, 'addNewItem']);




  
});
