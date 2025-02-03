<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Cuisine;
use App\Models\BranchCuisine;
use App\Models\Restaurant;

use App\Models\SearchHistory;

class SearchController extends Controller
{
    /**
     * This function will be used for detailed search. It will search for branches and BranchCuisines
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'search_query' => 'required|string',
        ]);

        $search_query = $request->search_query;

        $search_query_array = explode(' ', $search_query);

        $branches = Branch::where(function($query) use ($search_query_array){
            foreach($search_query_array as $search_query)
            {
                $query->orWhere('name', 'like', '%'.$search_query.'%');
            }
        })->get();


        $restaurants = Restaurant::where(function($query) use ($search_query_array){
            foreach($search_query_array as $search_query)
            {
                $query->orWhere('name', 'like', '%'.$search_query.'%');
            }
        })->get();



        $branch_cuisines = BranchCuisine::whereHas('cuisine', function($query) use ($search_query_array){
            foreach($search_query_array as $search_query)
            {
                $query->orWhere('nickname', 'like', '%'.$search_query.'%');
            }
        })->get();



        $Cuisines = Cuisine::where(function($query) use ($search_query_array){
            foreach($search_query_array as $search_query)
            {
                $query->orWhere('name', 'like', '%'.$search_query.'%');
            }
        })->get();



        $search_history = new SearchHistory();
        $search_history->search_query = $search_query;
        $search_history->user_id = auth()->user()->id;
        $search_history->save();



        return response()->json([
            'message' => 'Search results',
            'data' => [
                'branches' => $branches,
                'restaurants' => $restaurants,
                'branch_cuisines' => $branch_cuisines,
                'cuisines' => $Cuisines,
            ]
        ], 200);
    }




    /**
     * This function will be used for quick search. It will search for Cuisines only
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickSearch(Request $request)
    {
        $request->validate([
            'search_query' => 'required|string',
        ]);

        $search_query = $request->search_query;

        $search_query_array = explode(' ', $search_query);

        $Cuisines = Cuisine::where(function($query) use ($search_query_array){
            foreach($search_query_array as $search_query)
            {
                $query->orWhere('name', 'like', '%'.$search_query.'%');
            }
        })->get();

        return response()->json([
            'message' => 'Search results',
            'data' => $Cuisines
        ], 200);
    }
}
