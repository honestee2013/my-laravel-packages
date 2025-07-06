<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class UserStatus extends Model
{
    use HasFactory;
    
    


    protected $table = 'user_statuses';


    protected $fillable = [
        'name', 'description', 'user_status_category_id', 'editable' // Fillable properties will be inserted here
    ];

       public function category(){
		return $this->belongsTo('App\Modules\User\Models\UserStatusCategory', 'user_status_category_id');
	}

 // Relations will be inserted here
}
