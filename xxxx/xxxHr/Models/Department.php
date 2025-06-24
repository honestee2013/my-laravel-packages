<?php

namespace App\Modules\hr\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_id', 'description' // Fillable properties will be inserted here
    ];

       public function company(){
		return $this->belongsTo('App\Modules\Organization\Models\Company', 'company_id');
	}

 // Relations will be inserted here
}
