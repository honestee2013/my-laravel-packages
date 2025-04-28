<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use App\Modules\user\Models\BasicInfo;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

use App\Modules\user\Models\EmployeeProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    public function basicInfo(){
		return $this->hasOne(BasicInfo::class, 'user_id');
	}

    public function employeeProfile(){
		return $this->hasOne(EmployeeProfile::class, 'user_id');
	}

    public function profile() {
        return $this->employeeProfile;
    }


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
            ->where('model_type', 'App\\Modules\\user\\Models\\User');  
    }

  


}
