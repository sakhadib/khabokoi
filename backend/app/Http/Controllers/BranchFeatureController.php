<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BranchFeatures;
use App\Models\Features;
use App\Models\RestaurantOwner;
use App\Models\Restaurant;
use App\Models\Branch;

class BranchFeatureController extends Controller
{
    /**
     * Create or add new branch feature
     * Details -> branch_id, feature_id, is_available, details
     * Depending on the request, it can create a new feature or add an existing feature to a branch
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|integer',
        ]);

        if(!$this->amIOwner($request->branch_id))
        {
            return response()->json([
                'message' => 'You are not authorized to add this branch feature'
            ], 403);
        }



        if($request->has('feature_id'))
        {
            $request->validate([
                'feature_id' => 'required|integer',
                'is_available' => 'required|boolean',
                'details' => 'required|string',
            ]);

            $branchFeature = new BranchFeatures();
            $branchFeature->branch_id = $request->branch_id;
            $branchFeature->feature_id = $request->feature_id;
            $branchFeature->is_available = $request->is_available;
            $branchFeature->details = $request->details;

            $branchFeature->save();

            return response()->json([
                'message' => 'Branch feature created successfully',
                'data' => $branchFeature
            ], 201);
        }
        else
        {
            $request->validate([
                'feature_name' => 'required|string',
                'feature_description' => 'required|string',
                'is_available' => 'required|boolean',
                'details' => 'required|string',
            ]);

            $slug = strtolower(str_replace(' ', '-', $request->feature_name));

            $feature = new Features();
            $feature->name = $request->feature_name;
            $feature->description = $request->feature_description;
            $feature->slug = $slug;

            $feature->save();

            $branchFeature = new BranchFeatures();
            $branchFeature->branch_id = $request->branch_id;
            $branchFeature->feature_id = $feature->id;
            $branchFeature->is_available = $request->is_available;
            $branchFeature->details = $request->details;

            $branchFeature->save();

            return response()->json([
                'message' => 'Branch feature created successfully',
                'data' => $branchFeature
            ], 201);
        }
    }



    /**
     * Delete branch feature
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|integer',
            'feature_id' => 'required|integer',
        ]);

        $branchFeature = BranchFeatures::where('branch_id', $request->branch_id)
                                       ->where('feature_id', $request->feature_id)
                                       ->first();

        if(!$this->amIOwner($request->branch_id))
        {
            return response()->json([
                'message' => 'You are not authorized to delete this branch feature'
            ], 403);
        }

        if($branchFeature)
        {
            $branchFeature->delete();

            return response()->json([
                'message' => 'Branch feature deleted successfully'
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Branch feature not found'
            ], 404);
        }
    }



    /**
     * update the availability of a branch feature
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvailability(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|integer',
            'feature_id' => 'required|integer',
            'is_available' => 'required|boolean',
        ]);

        if(!$this->amIOwner($request->branch_id))
        {
            return response()->json([
                'message' => 'You are not authorized to update this branch feature'
            ], 403);
        }

        $branchFeature = BranchFeatures::where('branch_id', $request->branch_id)
                                       ->where('feature_id', $request->feature_id)
                                       ->first();

        if($branchFeature)
        {
            $branchFeature->is_available = $request->is_available;

            $branchFeature->save();

            return response()->json([
                'message' => 'Branch feature updated successfully',
                'data' => $branchFeature
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Branch feature not found'
            ], 404);
        }
    }



    /**
     * Edit branch feature details
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDetails(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|integer',
            'feature_id' => 'required|integer',
            'details' => 'required|string',
        ]);

        if(!$this->amIOwner($request->branch_id))
        {
            return response()->json([
                'message' => 'You are not authorized to update this branch feature'
            ], 403);
        }

        $branchFeature = BranchFeatures::where('branch_id', $request->branch_id)
                                       ->where('feature_id', $request->feature_id)
                                       ->first();

        if($branchFeature)
        {
            $branchFeature->details = $request->details;

            $branchFeature->save();

            return response()->json([
                'message' => 'Branch feature updated successfully',
                'data' => $branchFeature
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Branch feature not found'
            ], 404);
        }
    }



    /**
     * Get all features of a branch
     * 
     * @param $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function all($branch_id)
    {
        $branchFeatures = BranchFeatures::where('branch_id', $branch_id)
            ->with('feature')
            ->get();

        if($branchFeatures->count() > 0)
        {
            return response()->json([
                'message' => 'Branch features retrieved successfully',
                'data' => $branchFeatures
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Branch features not found'
            ], 404);
        }
    }



    /**
     * Get all available features of a branch
     * 
     * @param $branch_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function available($branch_id)
    {
        $branchFeatures = BranchFeatures::where('branch_id', $branch_id)
            ->where('is_available', true)
            ->with('feature')
            ->get();

        if($branchFeatures->count() > 0)
        {
            return response()->json([
                'message' => 'Branch features retrieved successfully',
                'data' => $branchFeatures
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Branch features not found'
            ], 404);
        }
    }



    /**
     * Get branches with a specific feature
     * 
     * @param $feature_slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function branches($feature_slug)
    {
        $feature = Features::where('slug', $feature_slug)->first();
        
        $branchFeatures = BranchFeatures::where('feature_id', $feature->id)
            ->with('branch')
            ->get();

        if($branchFeatures->count() > 0)
        {
            return response()->json([
                'message' => 'Branches retrieved successfully',
                'feature' => $feature,
                'branches' => $branchFeatures
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Branches not found'
            ], 404);
        }
    }




    /**
     * Check if the user is the owner of the restaurant
     * 
     * @param $branch_id
     * @return boolean
     */
    private function amIOwner($branch_id)
    {
        $this_branch = Branch::find($branch_id);
        $this_restaurant = Restaurant::find($this_branch->restaurant_id);

        $owner = RestaurantOwner::where('restaurant_id', $this_restaurant->id)
                                  ->where('user_id', auth()->user()->id)
                                  ->first();

        if(!$owner)
        {
            return false;
        }

        return true;
    }



    
}
