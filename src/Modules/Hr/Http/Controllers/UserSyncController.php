<?php

namespace App\Modules\Hr\Http\Controllers;



use App\Http\Controllers\Controller;
use App\Models\User;

class UserSyncController extends Controller
{
    public function syncUser()
    {
        $user = User::with(['employeeProfile', 'basicInfo'])
            ->whereHas('employeeProfile')
            ->get()
            ->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,

                    'username' => $user->username,
                    'password' => $user->password,
                    'role' => $user->employeeProfile->role->name,

                    'employee_id' => optional($user->employeeProfile)->employee_id,
                    'department' => optional($user->employeeProfile)->department,
                    'designation' => optional($user->employeeProfile)->designation,
                    'profile_picture' => optional($user->basicInfo)->profile_picture
                        ? asset('storage/' . $user->basicInfo->profile_picture)
                        : null,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'User data synced successfully.',
            'data' => $user,
        ]);
    }
}
