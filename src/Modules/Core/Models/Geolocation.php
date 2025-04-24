<?php

namespace App\Modules\core\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geolocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'description' // Fillable properties will be inserted here
    ];

     // Relations will be inserted here
}
