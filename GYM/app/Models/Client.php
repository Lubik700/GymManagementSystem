<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    protected $fillable = [
        'user_pending_id', 'name', 'email', 'contact', 'dob',
        'address', 'gender', 'password', 'profile_picture', 'status',
    ];

    protected $hidden = ['password'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

   public function activeSubscription()
{
    return $this->hasOne(Subscription::class)
        ->where('status', 'active')
        ->latest();
}
}