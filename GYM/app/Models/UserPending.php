<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPending extends Model
{
    protected $fillable = [
        'name', 'email', 'contact', 'dob', 'address',
        'gender', 'password', 'profile_picture', 'status',
    ];

    protected $hidden = ['password'];

    public function client()
    {
        return $this->hasOne(Client::class, 'user_pending_id');
    }
}