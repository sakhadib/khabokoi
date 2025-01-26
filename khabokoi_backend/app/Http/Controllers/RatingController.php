<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BranchRating;

class RatingController extends Controller
{
    /**
     * Create a new branch rating.
     * 
     * @param  \Illuminate\Http\Request  $request -> branch_id, rating
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBranchRating(Request $request)
    {
        try{
            $request->validate([
                'branch_id' => 'required|integer',
                'rating' => 'required|integer',
            ]);

            $rating = BranchRating::create([
                'branch_id' => $request->branch_id,
                'user_id' => auth()->user()->id,
                'rating' => $request->rating,
            ]);

            $returnable_rating = BranchRating::with('user:id,username', 'branch')->find($rating->id);

            return response()->json([
                'message' => 'Rating created successfully',
                'rating' => $returnable_rating,
            ], 201);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }




    /**
     * Delete a branch rating.
     * 
     * @param  \Illuminate\Http\Request  $request -> branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBranchRating(Request $request)
    {
        try{
            $request->validate([
                'branch_id' => 'required|integer',
            ]);

            $rating = BranchRating::where('branch_id', $request->branch_id)->where('user_id', auth()->user()->id)->first();

            if($rating){
                $rating->delete();
                return response()->json([
                    'message' => 'Rating deleted successfully',
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'Rating not found',
                ], 404);
            }
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }




    /**
     * Update a branch rating.
     * 
     * @param  \Illuminate\Http\Request  $request -> branch_id, rating
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBranchRating(Request $request)
    {
        try{
            $request->validate([
                'branch_id' => 'required|integer',
                'rating' => 'required|integer',
            ]);

            $rating = BranchRating::where('branch_id', $request->branch_id)->where('user_id', auth()->user()->id)->first();

            if($rating){
                $rating->rating = $request->rating;
                $rating->save();

                $returnable_rating = BranchRating::with('user:id,username', 'branch')->find($rating->id);

                return response()->json([
                    'message' => 'Rating updated successfully',
                    'rating' => $returnable_rating,
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'Rating not found',
                ], 404);
            }
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }




    /**
     * Get Average Rating of a branch.
     * 
     * @param $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranchRating($branch_id)
    {
        $rating = BranchRating::where('branch_id', $branch_id)->avg('rating');

        return response()->json([
            'rating' => $rating,
        ], 200);
    }




    /**
     * Get rating count of a branch.
     * 
     * @param $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranchRatingCount($branch_id)
    {
        $rating_count = BranchRating::where('branch_id', $branch_id)->count();

        return response()->json([
            'rating_count' => $rating_count,
        ], 200);
    }





    /**
     * get my rating of a branch.
     * 
     * @param $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyBranchRating($branch_id)
    {
        $rating = BranchRating::where('branch_id', $branch_id)->where('user_id', auth()->user()->id)->first();

        if($rating){
            return response()->json([
                'message' => 'Rating found',
                'rating' => $rating,
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Rating not found',
            ], 404);
        }
    }




    /**
     * Get a user's rating of a branch.
     * 
     * @param $branch_id, $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserBranchRating($branch_id, $user_id)
    {
        $rating = BranchRating::where('branch_id', $branch_id)->where('user_id', $user_id)->first();

        if($rating){
            return response()->json([
                'message' => 'Rating found',
                'rating' => $rating,
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Rating not found',
            ], 404);
        }
    }





    /**
     * Get all ratings of a branch.
     * 
     * @param  $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function allBranchRatings($branch_id)
    {
        $ratings = BranchRating::where('branch_id', $branch_id)->with('user:id,username')->get();

        if($ratings->isEmpty()){
            return response()->json([
                'message' => 'No ratings found',
            ], 404);
        }

        return response()->json([
            'ratings' => $ratings,
        ], 200);
    }




    /**
     * Rating avarage by month.
     * 
     * @param  $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function branchRatingAverageByMonth($branch_id)
    {
        $ratings = BranchRating::where('branch_id', $branch_id)->get();

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
            'rating_avg' => $rating_avg,
        ], 200);
    }




    /**
     * Rating avarage by year.
     * 
     * @param  $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function branchRatingAverageByYear($branch_id)
    {
        $ratings = BranchRating::where('branch_id', $branch_id)->get();

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
            'rating_avg' => $rating_avg,
        ], 200);
    }





    /**
     * Rating avarage by day.
     * 
     * @param  $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function branchRatingAverageByDay($branch_id)
    {
        $ratings = BranchRating::where('branch_id', $branch_id)->get();

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
            'rating_avg' => $rating_avg,
        ], 200);
    }
}
