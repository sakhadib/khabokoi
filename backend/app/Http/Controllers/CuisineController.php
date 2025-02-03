<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cuisine;
use App\Models\CuisineCategory;
use App\Models\BranchCuisine;

class CuisineController extends Controller
{
    /**
     * Create a new cuisine
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|integer',
        ]);

        $category_id = null;
        $cuisine_id = null;

        if(!$request->has('category_id')){
            $request->validate([
                'category_name' => 'required|string',
                'category_description' => 'required|string',
            ]);

            $category_image = "default_category_image.jpg";

            $category = new CuisineCategory();
            $category->name = $request->category_name;
            $category->details = $request->category_description;
            $category->image = $category_image;

            $category->save();

            $category_id = $category->id;
        }


        if(!$request->has('cuisine_id')){
            $request->validate([
                'cuisine_name' => 'required|string',
                'cuisine_description' => 'required|string',
            ]);

            $cuisine = new Cuisine();
            $cuisine->name = $request->cuisine_name;
            $cuisine->description = $request->cuisine_description;
            $cuisine->category_id = $category_id;
            $cuisine->save();

            $cuisine_id = $cuisine->id;
        }


        $request->validate([
            'nickname' => 'required|string',
            'regular_price' => 'required|numeric',
            'discount_percentage' => 'required|numeric|between:0,100',
            'details' => 'required|string',
            'is_available' => 'required|boolean',
        ]);


        $branchCuisine = new BranchCuisine();
        $branchCuisine->branch_id = $request->branch_id;
        $branchCuisine->cuisine_id = $cuisine_id;
        $branchCuisine->nickname = $request->nickname;
        $branchCuisine->regular_price = $request->regular_price;
        $branchCuisine->discount_percentage = $request->discount_percentage;
        $branchCuisine->details = $request->details;
        $branchCuisine->is_available = $request->is_available;
        $branchCuisine->image = "default_cuisine_image.jpg";

        $branchCuisine->save();


        $returnable_branchCuisine = BranchCuisine::with('cuisine')->find($branchCuisine->id);

        return response()->json([
            'message' => 'Cuisine created successfully',
            'data' => $returnable_branchCuisine
        ], 201);

    }



    /**
     * Update a cuisine
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
        ]);

        $branchCuisine = BranchCuisine::find($request->branch_cuisine_id);

        if(!$branchCuisine){
            return response()->json([
                'message' => 'Cuisine not found'
            ], 404);
        }

        if($request->has('nickname')){
            $request->validate([
                'nickname' => 'string',
            ]);

            $branchCuisine->nickname = $request->nickname;
        }

        if($request->has('regular_price')){
            $request->validate([
                'regular_price' => 'numeric',
            ]);

            $branchCuisine->regular_price = $request->regular_price;
        }

        if($request->has('discount_percentage')){
            $request->validate([
                'discount_percentage' => 'numeric|between:0,100',
            ]);

            $branchCuisine->discount_percentage = $request->discount_percentage;
        }

        if($request->has('details')){
            $request->validate([
                'details' => 'string',
            ]);

            $branchCuisine->details = $request->details;
        }

        if($request->has('is_available')){
            $request->validate([
                'is_available' => 'boolean',
            ]);

            $branchCuisine->is_available = $request->is_available;
        }

        $branchCuisine->save();

        $returnable_branchCuisine = BranchCuisine::with('cuisine')->find($branchCuisine->id);

        return response()->json([
            'message' => 'Cuisine updated successfully',
            'data' => $returnable_branchCuisine
        ], 200);
    }




    /**
     * Delete a cuisine
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'branch_cuisine_id' => 'required|integer',
        ]);

        $branchCuisine = BranchCuisine::find($request->branch_cuisine_id);

        if(!$branchCuisine){
            return response()->json([
                'message' => 'Cuisine not found'
            ], 404);
        }

        $branchCuisine->delete();

        return response()->json([
            'message' => 'Cuisine deleted successfully from the branch'
        ], 200);
    }










    //----------------------------------------------Get Requests----------------------------------------------//

    /**
     * Get all categories
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategory()
    {
        $categories = CuisineCategory::all();

        return response()->json([
            'message' => 'All categories',
            'data' => $categories
        ], 200);
    }




    /**
     * Get all cuisines
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCuisine()
    {
        $cuisines = Cuisine::with('category')->get();

        return response()->json([
            'message' => 'All cuisines',
            'data' => $cuisines
        ], 200);
    }




    /**
     * Get all cuisines of a category
     * 
     * @param $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCuisineByCategory($category_id)
    {
        $cuisines = Cuisine::where('category_id', $category_id)->get();

        return response()->json([
            'message' => 'All cuisines of the category',
            'data' => $cuisines
        ], 200);
    }




    /**
     * Get all branch cuisines of a cuisine
     * 
     * @param $cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranchCuisineByCuisine($cuisine_id)
    {
        $branchCuisines = BranchCuisine::where('cuisine_id', $cuisine_id)
            ->with('cuisine', 'branch')
            ->withCount('cuisineReviews')
            ->withAvg('cuisineRating', 'rating')
            ->get();

        return response()->json([
            'message' => 'All branch cuisines of the cuisine',
            'data' => $branchCuisines
        ], 200);
    }





    /**
     * Get all cuisines of a branch
     * 
     * @param $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCuisineByBranch($branch_id)
    {
        $branchCuisines = BranchCuisine::where('branch_id', $branch_id)
            ->with('cuisine')
            ->withCount('cuisineReviews')
            ->withAvg('cuisineRating', 'rating')
            ->get();

        return response()->json([
            'message' => 'All cuisines of the branch',
            'data' => $branchCuisines
        ], 200);
    }




    /**
     * Get Particular Cuisine
     * 
     * @param $branch_cuisine_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($branch_cuisine_id)
    {
        $branchCuisine = BranchCuisine::with('cuisine', 'branch', 'cuisine.category:id,name', 'cuisineReviews')
            ->withCount('cuisineReviews')
            ->withAvg('cuisineRating', 'rating')
            ->find($branch_cuisine_id);

        if($branchCuisine){
            return response()->json([
                'message' => 'Cuisine retrieved successfully',
                'data' => $branchCuisine
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Cuisine not found'
            ], 404);
        }
    }



    
}
