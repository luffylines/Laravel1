<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'is_admin', // Assuming 'is_admin' is a boolean field to check admin status
        'google2fa_secret', // Add this field for storing the 2FA secret
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Room Rental System Relationships
    public function ownedRooms()
    {
        return $this->hasMany(Room::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function recommendations()
    {
        return $this->hasMany(AiRecommendation::class);
    }

    // Define the isAdmin method
    public function isAdmin()
    {
        return $this->is_admin === 1; // Assuming `is_admin` is a column in your `users` table
    }
    
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin'; // Assuming 'role' column exists in the users table
    }
}
