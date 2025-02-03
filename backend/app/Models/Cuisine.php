<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'details',
        'cuisine_category_id',
    ];




    /**
     * Get the category that owns the Cuisine
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(CuisineCategory::class, 'cuisine_category_id');
    }




    /**
     * Get the branchCuisines for the Cuisine
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchCuisines()
    {
        return $this->hasMany(BranchCuisine::class);
    }
}
