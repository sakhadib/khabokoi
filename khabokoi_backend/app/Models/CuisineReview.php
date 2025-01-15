<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuisineReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_cuisine_id',
        'user_id',
        'review',
        'parent_id',
    ];

    public function branchCuisine()
    {
        return $this->belongsTo(BranchCuisine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(CuisineReview::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(CuisineReview::class, 'parent_id');
    }
}
