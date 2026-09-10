<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'contact_number',
    'is_physician_in_charge', // Matches your exact DB column column name!
];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting (IMPORTANT FIX)
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_physician_in_charge' => 'boolean', // âœ… ADD THIS LINE

    ];

    /**
     * Send the branded Velasquez Health Center password reset
     * notification instead of Laravel's default one - same secure
     * token/URL logic, just our own email design.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}