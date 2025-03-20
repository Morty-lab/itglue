<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role', // Add role field to fillable
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

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the company information for the user.
     */
    public function companyInformation()
    {
        return $this->hasOne(CompanyInformation::class);
    }

    /**
     * Get the company details for the user.
     */
    public function companyDetails()
    {
        return $this->hasOne(CompanyDetails::class);
    }

    /**
     * Get the branches for the user.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the employees for the user.
     */
    public function employees()
    {
        return $this->hasMany(EmployeeInformation::class);
    }

    /**
     * Get the devices for the user.
     */
    public function devices()
    {
        return $this->hasMany(DeviceInformation::class);
    }

    /**
     * Get the licenses for the user.
     */
    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
