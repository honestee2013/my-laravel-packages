<?php

namespace QuickerFaster\CodeGen\Services\AccessControl;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use QuickerFaster\CodeGen\Services\Core\ApplicationInfo;
use function Laravel\Prompts\error;
use App\Modules\Access\Models\Permission;

class AccessControlPermissionService
{


    const MSG_PERMISSION_DENIED = "You do not have permission to perform this action.";

    const PERMISSION_ACTIONS  = ['view', 'create',  'edit', 'delete', 'print', 'export', 'import'];


    public static function checkPermission($action, $modelName): bool
    {
        $permissionName = $action."_". Str::snake($modelName);
        return auth()->user()->hasPermissionTo($permissionName);
    }




    public static function checkPermissionsExistsOrCreate($resourceNames) {
        foreach($resourceNames as $resourceName) {
            $permissionNames = self::getResourcePermissionNames($resourceName);
            foreach($permissionNames as $permissionName) {
                if(!Permission::where("name", $permissionName)->first())
                Permission::create(['name' => $permissionName, 'description' => 'Allow role or user to '.str_replace('_', ' ',$permissionName)]);
            }
        }
    }


        // Get resoure permission name list
        public static function getResourcePermissionNames($resourceName) {
            $resourcePermissionNames = [];
            $resourceName = Str::snake($resourceName);
            foreach(self::PERMISSION_ACTIONS as $control) {
                $resourcePermissionNames[] = strtolower($control."_".$resourceName );
            }
            return $resourcePermissionNames;
        }



        public static function seedPermissionNames() {
            $modules = ApplicationInfo::getModuleNames();
          

            // Get all model names
            $modelNames = [];
            foreach ($modules as $module) {
                $moduleName = basename($module); // Get the module name from the directory
                $directory = app_path("Modules/".$moduleName."/Models");
                $namespace = addslashes("App\\Modules\\".$moduleName."\\Models\\");

                $modelNames = array_merge($modelNames, ApplicationInfo::getAllModelNames($directory, $namespace));    
            }


            // Check if permissions exist or create them
            self::checkPermissionsExistsOrCreate($modelNames);
        

        }










    
}



