<?php

namespace App\Http\Controllers;


use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Restaurant;
use App\Models\RestaurantOwner;

class BranchController extends Controller
{
    /**
     * Create a new branch.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try{
            $request->validate([
                'restaurant_id' => 'required|integer',
                'name' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|string',
                'District' => 'required|string',
                'Country' => 'required|string',
                'details' => 'required|string',
                'opening_hours' => 'required|json',
            ]);


            $me = auth()->user();
            $isOwner = RestaurantOwner::where('restaurant_id', $request->restaurant_id)
                ->where('user_id', $me->id)
                ->exists();

            if (!$isOwner) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            

            $branch = Branch::create([
                'restaurant_id' => $request->restaurant_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'District' => $request->District,
                'Country' => $request->Country,
                'details' => $request->details,
                'HouseNo' => $request->HouseNo,
                'Street' => $request->Street,
                'PostalCode' => $request->PostalCode,
                'city' => $request->city,
                'opening_hours' => $request->opening_hours,
            ]);

            $branch->opening_hours = json_decode($branch->opening_hours);

            return response()->json([
                'message' => 'Branch created successfully',
                'branch' => $branch->load('restaurant'),
            ], 201);
        }
        catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        }
    }




    /**
     * Update the branch.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try{
            $request->validate([
                'branch_id' => 'required|integer',
                'name' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|string',
                'District' => 'required|string',
                'Country' => 'required|string',
                'details' => 'required|string',
                'opening_hours' => 'required|json',
            ]);

            $branch = Branch::find($request->branch_id);

            $me = auth()->user();
            $isOwner = RestaurantOwner::where('restaurant_id', $branch->restaurant_id)
                ->where('user_id', $me->id)
                ->exists();

            if (!$isOwner) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            $branch->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'District' => $request->District,
                'Country' => $request->Country,
                'details' => $request->details,
                'HouseNo' => $request->HouseNo,
                'Street' => $request->Street,
                'PostalCode' => $request->PostalCode,
                'city' => $request->city,
                'opening_hours' => $request->opening_hours,
            ]);

            $branch->opening_hours = json_decode($branch->opening_hours);

            return response()->json([
                'message' => 'Branch updated successfully',
                'branch' => $branch->load('restaurant'),
            ], 200);
        }
        catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        }
    }




    /**
    * Delete the branch.
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function delete(Request $request)
    {
        try{
            $request->validate([
                'branch_id' => 'required|integer',
            ]);

            $branch = Branch::find($request->branch_id);

            $me = auth()->user();
            $isOwner = RestaurantOwner::where('restaurant_id', $branch->restaurant_id)
                ->where('user_id', $me->id)
                ->exists();

            if (!$isOwner) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            $branch->delete();

            return response()->json([
                'message' => 'Branch deleted successfully',
            ], 200);
        }
        catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
