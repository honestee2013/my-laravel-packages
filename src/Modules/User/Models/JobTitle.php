<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Modules\Core\Traits\HasEditableTraits; // Import the HasEditableTraits trait

class JobTitle extends Model
{
    use HasFactory, HasEditableTraits;

    protected $table = 'job_titles';


    protected $fillable = [
        'title', 'description' // Fillable properties will be inserted here
    ];

     // Relations will be inserted here



}
