<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'branch_cuisine_id',
    ];




    /**
     * Get the branchCuisine that owns the WishList
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branchCuisine()
    {
        return $this->belongsTo(BranchCuisine::class);
    }




    /**
     * Get the user that owns the WishList
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
