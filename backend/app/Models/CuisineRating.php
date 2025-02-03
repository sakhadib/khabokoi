<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuisineRating extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_cuisine_id',
        'user_id',
        'rating',
    ];




    /**
     * Get the branchCuisine that owns the CuisineRating
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branchCuisine()
    {
        return $this->belongsTo(BranchCuisine::class);
    }




    /**
     * Get the user that owns the CuisineRating
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
