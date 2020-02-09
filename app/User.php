<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    /**
     * Seller and buyer will use the users table
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set up the mutator for the name
     *
     */
    public function setNameAttribute($name)
    {
      $this->attributes['name'] = strtolower($name);
    }

    /**
     * Set up the accessor for the name and return in uppercase
     *
     */
    public function getNameAttribute($name)
    {
      return ucwords($name);
    }

    /**
     * Set up the mutator for the email
     *
     */
    public function setEmailAttribute($email)
    {
      $this->attributes['email'] = strtolower($email);
    }

    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    /**
     * Static method to Generate an authentication code
     */
     public static function generateVerificationCode()
     {
       return str_random(40);
     }
}
