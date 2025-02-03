<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuisineCategory extends Model
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
        'image'
    ];




    /**
     * Get all of the cuisines for the CuisineCategory
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuisines()
    {
        return $this->hasMany(Cuisine::class);
    }
}
