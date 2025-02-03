<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Facebook Routes
Route::get('auth/facebook/redirect', [AuthController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);



Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});


Route::post('register', [AuthController::class, 'register']);


Route::group([

    'middleware' => 'api',
    'prefix' => 'restaurant'

], function ($router) {

    Route::post('create', [RestaurantController::class, 'create']);
    Route::post('addowner', [RestaurantController::class, 'addOwner']);
    Route::get('myrestaurants', [RestaurantController::class, 'myRestaurants']);

});

Route::group([

    'prefix' => 'restaurant'
    //* Accessible by all users. so no middleware

], function ($router) {

    Route::get('all', [RestaurantController::class, 'all']);
    Route::get('show/{id}', [RestaurantController::class, 'show']);
    Route::post('branch/review/all', [ReviewController::class, 'all']);

});



Route::group([

    'middleware' => 'api',
    'prefix' => 'restaurant/branch'

], function ($router) {

    Route::post('create', [BranchController::class, 'create']);
    Route::post('update', [BranchController::class, 'update']);
    Route::post('delete', [BranchController::class, 'delete']);

    Route::post('review', [ReviewController::class, 'create']);
    Route::post('review/delete', [ReviewController::class, 'delete']);

});