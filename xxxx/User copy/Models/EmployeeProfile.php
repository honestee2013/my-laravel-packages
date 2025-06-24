<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $table = 'employee_profiles';


    protected $fillable = [
        'user_id', 'employee_id', 'department', 'designation', 'joining_date', 'reporting_manager_id', 'job_title_id', 'employment_type', 'work_location' // Fillable properties will be inserted here
    ];

       public function user(){
		return $this->belongsTo('App\Models\User', 'user_id');
	}

   public function jobTitle(){
		return $this->belongsTo('App\Modules\user\Models\JobTitle', 'job_title_id');
	}

   public function reportingManager(){
		return $this->belongsTo('App\Models\User', 'reporting_manager_id');
	}

 // Relations will be inserted here
}
