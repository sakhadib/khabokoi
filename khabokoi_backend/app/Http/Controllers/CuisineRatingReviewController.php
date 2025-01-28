<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CuisineRating;
use App\Models\CuisineReview;
use App\Models\BranchCuisine;

class CuisineRatingReviewController extends Controller
{
    //--------------------------------------------------------------Cuisine Rating : POST--------------------------------------------------------------//
    /**
     * Create a new cuisine rating
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRating(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
        ]);

        $cuisineRatingChecker = CuisineRating::where('branch_cuisine_id', $request->branch_cuisine_id)->where('user_id', auth()->user()->id)->first();

        if($cuisineRatingChecker)
        {
            $cuisineRatingChecker->rating = $request->rating;
            $cuisineRatingChecker->save();

            return response()->json([
                'message' => 'Cuisine rating updated successfully',
                'data' => $cuisineRatingChecker
            ], 200);
        }

        $cuisineRating = new CuisineRating();
        $cuisineRating->branch_cuisine_id = $request->branch_cuisine_id;
        $cuisineRating->rating = $request->rating;
        $cuisineRating->user_id = auth()->user()->id;

        $cuisineRating->save();

        return response()->json([
            'message' => 'Cuisine rating created successfully',
            'data' => $cuisineRating
        ], 201);
    }



    /**
     * Delete a cuisine rating
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRating(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
        ]);

        $cuisineRating = CuisineRating::where('branch_cuisine_id', $request->branch_cuisine_id)->where('user_id', auth()->user()->id)->first();

        if(!$cuisineRating){
            return response()->json([
                'message' => 'Cuisine rating not found'
            ], 404);
        }

        $cuisineRating->delete();

        return response()->json([
            'message' => 'Cuisine rating deleted successfully'
        ], 200);
    }


    

    //--------------------------------------------------------------Cuisine Rating : GET--------------------------------------------------------------//

    /**
     * Get average rating of a branch cuisine
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAverageRating($branch_cuisine_id)
    {
        $averageRating = CuisineRating::where('branch_cuisine_id', $branch_cuisine_id)->avg('rating');

        return response()->json([
            'message' => 'Average rating fetched successfully',
            'data' => $averageRating
        ], 200);
    }



    /**
     * Get rating count of a branch cuisine
     * 
     * @param $branch_cuisine_id
     */
    public function getRatingCount($branch_cuisine_id)
    {
        $ratingCount = CuisineRating::where('branch_cuisine_id', $branch_cuisine_id)->count();

        return response()->json([
            'message' => 'Rating count fetched successfully',
            'data' => $ratingCount
        ], 200);
    }



    
    /**
     * Get my rating of a branch cuisine
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyRating($branch_cuisine_id)
    {
        $myRating = CuisineRating::where('branch_cuisine_id', $branch_cuisine_id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if($myRating){
            return response()->json([
                'message' => 'Rating found',
                'data' => $myRating
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Rating not found'
            ], 404);
        }
    }



    /**
     * Get A user's rating of a branch cuisine
     * 
     * @param $branch_cuisine_id
     * @param $user_id
     */
    public function getUserRating($branch_cuisine_id, $user_id)
    {
        $userRating = CuisineRating::where('branch_cuisine_id', $branch_cuisine_id)
            ->where('user_id', $user_id)
            ->first();

        if($userRating){
            return response()->json([
                'message' => 'Rating found',
                'data' => $userRating
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Rating not found'
            ], 404);
        }
    }



    /**
     * Get all ratings of a branch cuisine
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRatings($branch_cuisine_id)
    {
        $branchCuisine = BranchCuisine::with(['cuisine', 'ratings' => function($query) {
            $query->with('user');
        }])->find($branch_cuisine_id);

        if (!$branchCuisine) {
            return response()->json([
            'message' => 'Branch cuisine not found'
            ], 404);
        }

        return response()->json([
            'message' => 'All ratings fetched successfully',
            'data' => $branchCuisine
        ], 200);
    }




    /**
     * Rating Avarage by date
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRatingAverageByDate($branch_cuisine_id)
    {
        $ratings = CuisineRating::where('branch_cuisine_id', $branch_cuisine_id)->get();

        if($ratings->isEmpty()){
            return response()->json([
                'message' => 'No ratings found',
            ], 404);
        }

        $rating_avg = $ratings->groupBy(function($date){
            return \Carbon\Carbon::parse($date->created_at)->format('d');
        });

        $rating_avg = $rating_avg->map(function($item, $key){
            return $item->avg('rating');
        });

        return response()->json([
            'message' => 'Rating average by date',
            'rating_avg' => $rating_avg,
        ], 200);
    }




    /**
     * Rating Avarage by month
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRatingAverageByMonth($branch_cuisine_id)
    {
        $ratings = CuisineRating::where('branch_cuisine_id', $branch_cuisine_id)->get();

        if($ratings->isEmpty()){
            return response()->json([
                'message' => 'No ratings found',
            ], 404);
        }

        $rating_avg = $ratings->groupBy(function($date){
            return \Carbon\Carbon::parse($date->created_at)->format('m');
        });

        $rating_avg = $rating_avg->map(function($item, $key){
            return $item->avg('rating');
        });

        return response()->json([
            'message' => 'Rating average by month',
            'rating_avg' => $rating_avg,
        ], 200);
    }





    /**
     * Rating Avarage by year
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRatingAverageByYear($branch_cuisine_id)
    {
        $ratings = CuisineRating::where('branch_cuisine_id', $branch_cuisine_id)->get();

        if($ratings->isEmpty()){
            return response()->json([
                'message' => 'No ratings found',
            ], 404);
        }

        $rating_avg = $ratings->groupBy(function($date){
            return \Carbon\Carbon::parse($date->created_at)->format('Y');
        });

        $rating_avg = $rating_avg->map(function($item, $key){
            return $item->avg('rating');
        });

        return response()->json([
            'message' => 'Rating average by year',
            'rating_avg' => $rating_avg,
        ], 200);
    }








    //--------------------------------------------------------------Cuisine Review : POST--------------------------------------------------------------//

    /**
     * Create a new cuisine review
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createReview(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
            'review' => 'required|string',
        ]);

        $parent_id = $request->parent_id ?? null;

        $cuisineReview = new CuisineReview();
        $cuisineReview->branch_cuisine_id = $request->branch_cuisine_id;
        $cuisineReview->review = $request->review;
        $cuisineReview->user_id = auth()->user()->id;
        $cuisineReview->parent_id = $parent_id;

        $cuisineReview->save();


        return response()->json([
            'message' => 'Cuisine review created successfully',
            'data' => $cuisineReview
        ], 201);
    }



    /**
     * Delete a cuisine review
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReview(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
            'review_id' => 'required|integer',
        ]);

        $cuisineReview = CuisineReview::where('branch_cuisine_id', $request->branch_cuisine_id)
            ->where('user_id', auth()->user()->id)
            ->where('id', $request->review_id)->first();

        if(!$cuisineReview){
            return response()->json([
                'message' => 'Cuisine review not found'
            ], 404);
        }

        $cuisineReview->delete();

        return response()->json([
            'message' => 'Cuisine review deleted successfully'
        ], 200);
    }





    /**
     * Update a cuisine review
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateReview(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
            'review_id' => 'required|integer',
            'review' => 'required|string',
        ]);

        $cuisineReview = CuisineReview::where('branch_cuisine_id', $request->branch_cuisine_id)
            ->where('user_id', auth()->user()->id)
            ->where('id', $request->review_id)->first();

        if(!$cuisineReview){
            return response()->json([
                'message' => 'Cuisine review not found'
            ], 404);
        }

        $cuisineReview->review = $request->review;
        $cuisineReview->save();

        return response()->json([
            'message' => 'Cuisine review updated successfully',
            'data' => $cuisineReview
        ], 200);
    }




    //--------------------------------------------------------------Cuisine Review : GET--------------------------------------------------------------//

    /**
     * Get all reviews of a branch cuisine
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllReviews($branch_cuisine_id)
    {
        $branchCuisine = BranchCuisine::with(['cuisine', 'reviews' => function($query) {
            $query->with('user');
        }])->find($branch_cuisine_id);

        if (!$branchCuisine) {
            return response()->json([
            'message' => 'Branch cuisine not found'
            ], 404);
        }

        return response()->json([
            'message' => 'All reviews fetched successfully',
            'data' => $branchCuisine
        ], 200);
    }




    /**
     * Get my review of a branch cuisine
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyReview($branch_cuisine_id)
    {
        $myReviews = CuisineReview::where('branch_cuisine_id', $branch_cuisine_id)
            ->where('user_id', auth()->user()->id)
            ->get();


        if($myReviews->isEmpty()){
            return response()->json([
                'message' => 'You have not reviewed this cuisine'
            ], 404);
        }

        return response()->json([
            'message' => 'My reviews fetched successfully',
            'data' => $myReviews
        ], 200);
    }





    /**
     * Get total review count of a branch cuisine
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReviewCount($branch_cuisine_id)
    {
        $reviewCount = CuisineReview::where('branch_cuisine_id', $branch_cuisine_id)->count();

        return response()->json([
            'message' => 'Review count fetched successfully',
            'data' => $reviewCount
        ], 200);
    }




    /**
     * Get review count by date
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReviewCountByDate($branch_cuisine_id)
    {
        $reviews = CuisineReview::where('branch_cuisine_id', $branch_cuisine_id)->get();

        if($reviews->isEmpty()){
            return response()->json([
                'message' => 'No reviews found',
            ], 404);
        }

        $review_count = $reviews->groupBy(function($date){
            return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
        });

        $review_count = $review_count->map(function($item, $key){
            return $item->count();
        });

        return response()->json([
            'message' => 'Review count by date',
            'review_count' => $review_count,
        ], 200);
    }




    /**
     * Get review count by month
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReviewCountByMonth($branch_cuisine_id)
    {
        $reviews = CuisineReview::where('branch_cuisine_id', $branch_cuisine_id)->get();

        if($reviews->isEmpty()){
            return response()->json([
                'message' => 'No reviews found',
            ], 404);
        }

        $review_count = $reviews->groupBy(function($date){
            return \Carbon\Carbon::parse($date->created_at)->format('Y-m');
        });

        $review_count = $review_count->map(function($item, $key){
            return $item->count();
        });

        return response()->json([
            'message' => 'Review count by month',
            'review_count' => $review_count,
        ], 200);
    }





    /**
     * Get review count by year
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReviewCountByYear($branch_cuisine_id)
    {
        $reviews = CuisineReview::where('branch_cuisine_id', $branch_cuisine_id)->get();

        if($reviews->isEmpty()){
            return response()->json([
                'message' => 'No reviews found',
            ], 404);
        }

        $review_count = $reviews->groupBy(function($date){
            return \Carbon\Carbon::parse($date->created_at)->format('Y');
        });

        $review_count = $review_count->map(function($item, $key){
            return $item->count();
        });

        return response()->json([
            'message' => 'Review count by year',
            'review_count' => $review_count,
        ], 200);
    }
    
}
