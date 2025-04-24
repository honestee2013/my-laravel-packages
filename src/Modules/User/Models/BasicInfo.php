<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicInfo extends Model
{
    use HasFactory;

    protected $table = 'basic_infos';


    protected $fillable = [
        'user_id', 'phone_number', 'email', 'address_line_1', 'address_line_2', 'city', 'state', 'postal_code', 'country', 'date_of_birth', 'gender' // Fillable properties will be inserted here
    ];

       public function user(){
		return $this->belongsTo('App\Models\User', 'user_id');
	}

 // Relations will be inserted here
}
