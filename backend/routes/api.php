<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\BranchFeatureController;
use App\Http\Controllers\CuisineController;


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




// TODO : Authentication Routes

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



// TODO : Restaurant Routes

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
    Route::get('branch/review/count/{id}', [ReviewController::class, 'count']);
    Route::get('branch/review/yearly/{id}', [ReviewController::class, 'countYearWise']);
    Route::get('branch/review/monthly/{id}', [ReviewController::class, 'countMonthWise']);
    Route::get('branch/review/daily/{id}', [ReviewController::class, 'countDayWise']);

    Route::get('branch/rating/{branch_id}', [RatingController::class, 'getBranchRating']);
    Route::get('branch/rating/count/{branch_id}', [RatingController::class, 'getBranchRatingCount']);
    Route::get('branch/userrating/{branch_id}/{user_id}', [RatingController::class, 'getUserBranchRating']);
    Route::get('branch/rating/all/{branch_id}', [RatingController::class, 'allBranchRatings']);
    Route::get('branch/rating/monthly/{branch_id}', [RatingController::class, 'branchRatingAverageByMonth']);
    Route::get('branch/rating/yearly/{branch_id}', [RatingController::class, 'branchRatingAverageByYear']);
    Route::get('branch/rating/daily/{branch_id}', [RatingController::class, 'branchRatingAverageByDay']);

});



// TODO : Branch Routes

Route::group([

    'middleware' => 'api',
    'prefix' => 'restaurant/branch'

], function ($router) {

    Route::post('create', [BranchController::class, 'create']);
    Route::post('update', [BranchController::class, 'update']);
    Route::post('delete', [BranchController::class, 'delete']);

    Route::post('review', [ReviewController::class, 'create']);
    Route::post('review/delete', [ReviewController::class, 'delete']);

    Route::post('rating', [RatingController::class, 'create']);
    Route::post('rating/delete', [RatingController::class, 'delete']); 
    Route::post('rating/update', [RatingController::class, 'update']);
    Route::get('rating/my/{branch_id}', [RatingController::class, 'getMy']);


});



// TODO : Branch Feature Routes

Route::group([

    'middleware' => 'api',
    'prefix' => 'restaurant/branch/feature'

], function ($router) {

    Route::post('create', [BranchFeatureController::class, 'create']);
    Route::post('delete', [BranchFeatureController::class, 'delete']);
    Route::post('update/availability', [BranchFeatureController::class, 'updateAvailability']);
    Route::post('update/details', [BranchFeatureController::class, 'updateDetails']);

});



Route::group([
    'prefix' => 'restaurant/branch/feature'
], function ($router) {

    Route::get('all/{branch_id}', [BranchFeatureController::class, 'all']);
    Route::get('available/{branch_id}', [BranchFeatureController::class, 'available']);
    Route::get('branches/{feature_slug}', [BranchFeatureController::class, 'branches']);    
});




// TODO : Cuisine Routes

Route::group([

    'middleware' => 'api',
    'prefix' => 'cuisine'

], function ($router) {

    Route::post('create', [CuisineController::class, 'create']);
    Route::post('update', [CuisineController::class, 'update']);
    Route::post('delete', [CuisineController::class, 'delete']);
});


Route::group([
    'prefix' => 'cuisine'
], function ($router){

    Route::get('category/all', [CuisineController::class, 'getAllCategory']);
    Route::get('category/show/{id}', [CuisineController::class, 'getCuisineByCategory']);

    Route::get('all', [CuisineController::class, 'getAllCuisine']);
    Route::get('show/{id}', [CuisineController::class, 'getBranchCuisineByCuisine']);

});