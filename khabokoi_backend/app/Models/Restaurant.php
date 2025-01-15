<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'details', 
        'image'
    ];

    public function owners()
    {
        return $this->hasMany(RestaurantOwner::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }


}
