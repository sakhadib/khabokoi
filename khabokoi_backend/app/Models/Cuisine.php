<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
        'cuisine_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(CuisineCategory::class, 'cuisine_category_id');
    }

    public function branchCuisines()
    {
        return $this->hasMany(BranchCuisine::class);
    }
}
