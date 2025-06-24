<?php

namespace App\Models; 

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use App\Modules\user\Models\BasicInfo;
use App\Modules\user\Models\UserStatus;

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

    protected $guard_name = 'web'; // ✅ important line

    //protected $table = 'users';


    protected $fillable = [
        'name', 'email', 'user_status_id', 'email_verified_at', 'password', 'password_confirmation', 'remember_token', 'user_type' // Fillable properties will be inserted here
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

    public function userStatus()
    {
        return $this->hasOne(UserStatus::class, 'user_status_id');
    }
    public function getUserStatusNameAttribute()
    {
        return $this->userStatus ? $this->userStatus->display_name : null;
    }



    // In App\Models\User.php
public function scopeWithAdminRoles($query)
{
    $query->whereHas('roles', function ($q) {
        $q->whereIn('name', ['admin', 'super_admin']);
    });
}





  


}
