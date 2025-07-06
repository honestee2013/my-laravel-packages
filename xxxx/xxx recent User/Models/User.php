<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class User extends Model
{
    use HasFactory;
    
    


    protected $table = 'users';


    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'password_confirmation', 'remember_token', 'user_type' // Fillable properties will be inserted here
    ];

     // Relations will be inserted here
}
