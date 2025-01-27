<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BranchReview;

class ReviewController extends Controller
{
    /**
     * Create a new branch review.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try{
            $request->validate([
                'branch_id' => 'required|integer',
                'review' => 'required|string',
            ]);

            $review = BranchReview::create([
                'branch_id' => $request->branch_id,
                'user_id' => auth()->user()->id,
                'review' => $request->review,
                'parent_id' => $request->parent_id,
            ]);

            $returnable_review = BranchReview::with('user:id,username', 'branch')->find($review->id);

            return response()->json([
                'message' => 'Review created successfully',
                'review' => $returnable_review,
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
     * Delete a branch review.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try{
            $request->validate([
                'review_id' => 'required|integer',
            ]);

            $review = BranchReview::find($request->review_id);

            if(!$review){
                return response()->json([
                    'message' => 'Review not found',
                ], 404);
            }

            if($review->user->id != auth()->user()->id){
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            $review->delete();

            return response()->json([
                'message' => 'Review deleted successfully',
            ], 200);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }




    /**
     * Get all reviews of a branch.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        try{
            $request->validate([
                'branch_id' => 'required|integer',
            ]);

            $reviews = BranchReview::where('branch_id', $request->branch_id)
                ->whereNull('parent_id')
                ->with('user:id,username', 'children.user:id,username')
                ->get();

            return response()->json([
                'reviews' => $reviews,
            ], 200);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }




    /**
     * Get review count of a branch.
     * 
     * @param  $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function count($branch_id)
    {
        $count = BranchReview::where('branch_id', $branch_id)->count();

        return response()->json([
            'count' => $count,
        ], 200);
    }




    /**
     * Get review count of a branch year wise.
     * 
     * @param  $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function countYearWise($branch_id)
    {
        $reviews = BranchReview::where('branch_id', $branch_id)
            ->selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->get();

        return response()->json([
            'reviews' => $reviews,
        ], 200);
    }




    /**
     * Get review count of a branch month wise.
     * 
     * @param  $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function countMonthWise($branch_id)
    {
        $reviews = BranchReview::where('branch_id', $branch_id)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->get();

        return response()->json([
            'reviews' => $reviews,
        ], 200);
    }





    /**
     * Get review count of a branch day wise.
     * 
     * @param  $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function countDayWise($branch_id)
    {
        $reviews = BranchReview::where('branch_id', $branch_id)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, COUNT(*) as count')
            ->groupBy('year', 'month', 'day')
            ->get();

        return response()->json([
            'reviews' => $reviews,
        ], 200);
    }
}

