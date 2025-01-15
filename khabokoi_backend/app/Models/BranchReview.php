<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'user_id',
        'parent_id',
        'review'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(BranchReview::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BranchReview::class, 'parent_id');
    }
}
