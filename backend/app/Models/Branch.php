<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
* Branch Model, This represents the Branch table in the database
* A Branch belongs to a Restaurant
* A Branch has many BranchFeatures
* A Branch has many BranchCuisines
* A Branch has many BranchRating
* A Branch has many BranchReview
*/
class Branch extends Model
{
    use HasFactory;





    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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





    /**
     * Get the restaurant that owns the Branch
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }






    /**
     * Get the BranchFeatures for the Branch
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchFeatures()
    {
        return $this->hasMany(BranchFeatures::class)->with('feature');
    }






    /**
     * Get the BranchCuisines for the Branch
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchCuisines()
    {
        return $this->hasMany(BranchCuisine::class);
    }







    /**
     * Get the BranchRating for the Branch
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchRating()
    {
        return $this->hasMany(BranchRating::class);
    }





    /**
     * Get the BranchReview for the Branch
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchReview()
    {
        return $this->hasMany(BranchReview::class);
    }


    /**
     * Get the opening_hours attribute.
     *
     * @param  string  $value
     * @return mixed
     */
    public function getOpeningHoursAttribute($value)
    {
        return json_decode($value, true); // Decode to an associative array
    }


}
