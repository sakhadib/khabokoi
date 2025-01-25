<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Restaurant;
use App\Models\User;
use App\Models\RestaurantOwner;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    /**
     * Create a new restaurant.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try{

            $request->validate([
                'name' => 'required|string',
                'details' => 'required|string',
            ]);

            $image_url = 'default_restaurant.jpg';

            if($request->image){
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ]);
                $imageName = time() . '.' . $request->image->extension();
                $request->image->storeAs('restaurant_images', $imageName, 'public');
                $image_url = $request->image->store('restaurant_images', 'public');
            }

            $restaurant = Restaurant::create([
                'name' => $request->name,
                'details' => $request->details,
                'image' => $image_url,
            ]);

            $user = auth()->user();

            RestaurantOwner::create([
                'user_id' => $user->id,
                'restaurant_id' => $restaurant->id,
                'details' => 'Creator',
            ]);

            return response()->json([
                'message' => 'Restaurant created successfully',
                'restaurant' => $restaurant,
                'owner' => $user,
            ], 201);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        }
    }



    /**
     * Add a new owner to the restaurant.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOwner(Request $request)
    {
        try{
            $request->validate([
                'restaurant_id' => 'required|integer',
                'user_id' => 'required|integer',
                'details' => 'required|string',
            ]);

            $restaurant = Restaurant::find($request->restaurant_id);

            $me = auth()->user();

            if(!$restaurant){
                return response()->json([
                    'message' => 'Restaurant not found',
                ], 404);
            }

            if($restaurant->owners->where('user_id', $me->id)->count() == 0){
                return response()->json([
                    'message' => 'You are not the owner of this restaurant',
                ], 403);
            }

            $user = User::find($request->user_id);

            if(!$user){
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

            $owner = RestaurantOwner::create([
                'user_id' => $user->id,
                'restaurant_id' => $restaurant->id,
                'details' => $request->details,
            ]);

            $restaurant_with_owners = Restaurant::with('owners')->find($restaurant->id);

            return response()->json([
                'message' => 'Owner added successfully',
                'restaurant' => $restaurant_with_owners,
            ], 201);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        }
    }



    /**
     * Get all restaurants.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $restaurants = Restaurant::withCount('branches')->get();

        return response()->json([
            'restaurants' => $restaurants,
        ], 200);
    }



    /**
     * Get a restaurant by id.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $restaurant = Restaurant::with('branches', 'owners')->find($id);

        if(!$restaurant){
            return response()->json([
                'message' => 'Restaurant not found',
            ], 404);
        }

        return response()->json([
            'restaurant' => $restaurant,
        ], 200);
    }




    /**
     * Get all restaurants owned by the authenticated user.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRestaurants()
    {
        $user = auth()->user();

        $restaurants = $user->restaurantOwners;

        return response()->json([
            'restaurants' => $restaurants,
        ], 200);
    }



    
}
