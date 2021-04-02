<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;

class User extends BaseModel implements AuthenticatableContract, JWTSubject, AuthorizableContract
{
    // Soft Delete and User Authentication
    use SoftDeletes, Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    // Don't expose passwords when querying users.
    protected $hidden = ['password', 'deleted_at'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // TODO: implement
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // TODO: Implement custom parameters for custom claims
    public function getJWTCustomClaims()
    {
        return [];
    }
}
