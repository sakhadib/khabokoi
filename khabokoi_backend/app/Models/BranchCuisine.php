<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchCuisine extends Model
{
    use HasFactory;

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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }

    public function cuisineReviews()
    {
        return $this->hasMany(CuisineReview::class);
    }

    public function cuisineRating()
    {
        return $this->hasMany(CuisineRating::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
