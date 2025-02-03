<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WishList;
use App\Models\BranchCuisine;

class WishListController extends Controller
{
    /**
     * Add a new cuisine to the user's wishlist
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToWishlist(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
        ]);

        $branch_cuisine = BranchCuisine::find($request->branch_cuisine_id);

        if(!$branch_cuisine)
        {
            return response()->json([
                'message' => 'Branch cuisine not found',
            ], 404);
        }


        $wishlist_checker = WishList::where('user_id', auth()->user()->id)
                                    ->where('branch_cuisine_id', $request->branch_cuisine_id)
                                    ->first();

        if($wishlist_checker)
        {
            return response()->json([
                'message' => 'Cuisine already in wishlist',
            ], 400);
        }


        $wishlist = new WishList();
        $wishlist->user_id = auth()->user()->id;
        $wishlist->branch_cuisine_id = $request->branch_cuisine_id;

        $wishlist->save();

        return response()->json([
            'message' => 'Cuisine added to wishlist successfully',
            'data' => $wishlist,
        ], 201);
    }




    /**
     * Remove a cuisine from the user's wishlist
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromWishlist(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
        ]);

        $wishlist = WishList::where('user_id', auth()->user()->id)
                            ->where('branch_cuisine_id', $request->branch_cuisine_id)
                            ->first();
        
        if(!$wishlist)
        {
            return response()->json([
                'message' => 'Cuisine not found in wishlist',
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'message' => 'Cuisine removed from wishlist successfully',
        ], 200);
    }




    /**
     * Get all the cuisines in the user's wishlist
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWishlist()
    {
        $wishlists = WishList::where('user_id', auth()->user()->id)
                            ->with('branchCuisine')
                            ->get();

        if($wishlists->isEmpty())
        {
            return response()->json([
                'message' => 'Wishlist is empty',
            ], 200);
        }

        return response()->json([
            'message' => 'All cuisines in wishlist',
            'data' => $wishlists,
        ], 200);
    }



    /**
     * Get count of my wishlisted cuisines
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWishlistCount()
    {
        $count = WishList::where('user_id', auth()->user()->id)->count();

        return response()->json([
            'message' => 'Count of wishlisted cuisines',
            'data' => $count,
        ], 200);
    }




    /**
     * Check if a cuisine is in the user's wishlist
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkWishlist($branch_cuisine_id)
    {
        $wishlist = WishList::where('user_id', auth()->user()->id)
                            ->where('branch_cuisine_id', $branch_cuisine_id)
                            ->with('branchCuisine')
                            ->first();

        if(!$wishlist)
        {
            return response()->json([
                'message' => 'Cuisine not in wishlist',
            ], 200);
        }

        return response()->json([
            'message' => 'Cuisine in wishlist',
            'data' => $wishlist,
        ], 200);
    }





    /**
     * Get count of users who wishlisted a cuisine
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWishlistCountByCuisine($branch_cuisine_id)
    {
        $count = WishList::where('branch_cuisine_id', $branch_cuisine_id)->count();

        return response()->json([
            'message' => 'Count of users who wishlisted a cuisine',
            'data' => $count,
        ], 200);
    }
}
