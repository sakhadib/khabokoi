<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function branchFeatures()
    {
        return $this->hasMany(BranchFeatures::class);
    }
}
