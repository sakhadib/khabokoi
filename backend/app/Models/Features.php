<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];




    /**
     * Get all of the branches for the Features
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchFeatures()
    {
        return $this->hasMany(BranchFeatures::class);
    }
}
