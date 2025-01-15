<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuisineRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_cuisine_id',
        'user_id',
        'rating',
    ];

    public function branchCuisine()
    {
        return $this->belongsTo(BranchCuisine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
