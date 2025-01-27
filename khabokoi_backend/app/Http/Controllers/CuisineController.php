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
}
