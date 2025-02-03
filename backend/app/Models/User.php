<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



    //--------------------RELATIONSHIPS--------------------
    // Define relationships with other models             |
    //-----------------------------------------------------

    /**
     * get related restaurant owners
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restaurantOwners()
    {
        return $this->hasMany(RestaurantOwner::class)->with('restaurant');
    }


    /**
     * get related search histories
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class);
    }


    /**
     * get related branch reviews
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchReviews()
    {
        return $this->hasMany(BranchReview::class);
    }


    /**
     * get related branch ratings
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchRatings()
    {
        return $this->hasMany(BranchRating::class);
    }


    /**
     * get wishlist
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }


    /**
     * get related cuisine reviews
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuisineReviews()
    {
        return $this->hasMany(CuisineReview::class);
    }


    /**
     * get related cuisine ratings
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuisineRatings()
    {
        return $this->hasMany(CuisineRating::class);
    }
}
