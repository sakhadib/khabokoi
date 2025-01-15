<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
        'phone',
        'email',
        'HouseNo',
        'Block',
        'Street',
        'PostalCode',
        'City',
        'District',
        'Country',
        'opening_hours',
        'image',
        'details'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function branchFeatures()
    {
        return $this->hasMany(BranchFeatures::class);
    }

    public function branchCuisines()
    {
        return $this->hasMany(BranchCuisine::class);
    }

    public function branchRating()
    {
        return $this->hasMany(BranchRating::class);
    }

    public function branchReview()
    {
        return $this->hasMany(BranchReview::class);
    }

    
}
