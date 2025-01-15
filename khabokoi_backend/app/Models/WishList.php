<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_cuisine_id',
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
