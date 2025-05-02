<?php

namespace QuickerFaster\CodeGen\Services\AccessControl;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function Laravel\Prompts\error;

class AccessControlService
{


    const MSG_PERMISSION_DENIED = "You do not have permission to perform this action.";



    public static function checkPermission($action, $modelName): bool
    {
        $permissionName = $action."_". Str::snake($modelName);
        return auth()->user()->hasPermissionTo($permissionName);
    }



    
}



