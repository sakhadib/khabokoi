<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchCuisine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'cuisine_id',
        'nickname',
        'regular_price',
        'discount_percentage',
        'details',
        'image',
        'is_available',
    ];



    /**
     * Get the branch that owns the BranchCuisine
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class)
                    ->with(['restaurant', 'branchFeatures'])
                    ->withCount(['branchReview', 'branchFeatures'])
                    ->withAvg('branchRating', 'rating');
    }




    /**
     * Get the cuisine that owns the BranchCuisine
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class)->with('category');
    }




    /**
     * Get the cuisine Reviews for the BranchCuisine
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuisineReviews()
    {
        return $this->hasMany(CuisineReview::class);
    }




    /**
     * Get the cuisineRatings for the BranchCuisine
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuisineRating()
    {
        return $this->hasMany(CuisineRating::class)->with('user:id,username');
    }




    /**
     * Get the wishlists for the BranchCuisine
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
