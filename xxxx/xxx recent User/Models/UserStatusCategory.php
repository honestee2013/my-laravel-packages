<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class UserStatusCategory extends Model
{
    use HasFactory;
    
    


    protected $table = 'user_status_categories';


    protected $fillable = [
        'name', 'description', 'editable' // Fillable properties will be inserted here
    ];

       public function userStatuses(){
		return $this->hasMany('App\Modules\User\Models\UserStatus');
	}

 // Relations will be inserted here
}
