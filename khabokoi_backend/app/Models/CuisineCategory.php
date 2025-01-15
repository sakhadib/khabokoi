<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuisineCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
        'image'
    ];

    public function cuisines()
    {
        return $this->hasMany(Cuisine::class);
    }
}
