<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchFeatures extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'feature_id',
        'is_available',
        'details'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function feature()
    {
        return $this->belongsTo(Features::class);
    }

    
}
