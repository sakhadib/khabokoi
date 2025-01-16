<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuisineReview extends Model
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
        'review',
        'parent_id',
    ];




    /**
     * Get the branchCuisine that owns the CuisineReview
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branchCuisine()
    {
        return $this->belongsTo(BranchCuisine::class);
    }




    /**
     * Get the user that owns the CuisineReview
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }




    /**
     * Get the parent review that owns the CuisineReview
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(CuisineReview::class, 'parent_id');
    }




    /**
     * Get all of the children for the CuisineReview
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(CuisineReview::class, 'parent_id');
    }
}
