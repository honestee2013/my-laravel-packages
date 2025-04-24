<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    protected $table = 'users';


    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'password_confirmation', 'remember_token', 'user_type' // Fillable properties will be inserted here
    ];

     // Relations will be inserted here
}
