<?php

namespace App\Modules\Access\Http\Livewire\AccessControls;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Modules\Access\Models\Role;

use Illuminate\Support\Facades\File;
use App\Modules\Access\Models\Permission;

class AccessControlManager extends Component
{


    public $moduleNames = [];
    public $showResourceControlButtonGroup = false;
    public $resourceNames = ['Store', 'HumanResource'];

    public $selectedScopeName = 'Role';
    public $scopeNames;
    public $selectedScope = null;
    public $selectedScopeId;
    public $selectedModule = null;

    public $isUrlAccess = false;


    public $controlList = ['view', 'create',  'edit', 'delete', 'print', 'export', 'import'];
    public $controlsCSSClasses = [
        'view' => ['color' => 'info', 'bg' => 'info', 'icon' => 'fas fa-eye'],
        'create' => ['color' => 'success', 'bg' => 'success', 'icon' => 'fas fa-plus'],
        'edit' => ['color' => 'warning', 'bg' => 'warning', 'icon' => 'fas fa-edit'],
        'delete' => ['color' => 'danger', 'bg' => 'danger', 'icon' => 'fas fa-trash'],

        'print' => ['color' => 'success', 'bg' => 'success', 'icon' => 'fas fa-print'],

        'export' => ['color' => 'primary', 'bg' => 'primary', 'icon' => 'fas fa-file-pdf'],
        'import' => ['color' => 'primary', 'bg' => 'primary', 'icon' => 'fas fa-file-import'],


        // 'restore' => ['color' => 'success', 'bg' => 'success', 'icon' => 'fas fa-undo'],
        // 'approve' => ['color' => 'success', 'bg' => 'success', 'icon' => 'fas fa-check'],
        // 'reject' => ['color' => 'danger', 'bg' => 'danger', 'icon' => 'fas fa-times'],
        // 'send' => ['color' => 'success', 'bg' => 'success', 'icon' => 'fas fa-paper-plane'],
        // 'forceDelete' => ['color' => 'danger', 'bg' => 'danger', 'icon' => 'fas fa-trash'],
        // 'archive' => ['color' => 'success', 'bg' => 'success', 'icon' => 'fas fa-archive'],
        // 'unarchive' => ['color' => 'success', 'bg' => 'success', 'icon' => 'fas fa-archive'],

        
    ];
    public $resourceControlButtonGroup = [];





    protected $listeners = [
    ];


    public function mount() {

        $this->moduleNames = $this->getModuleNames();
        $selectedScopeClassName = "App\Modules\Access\Models\\".$this->selectedScopeName;
        $this->scopeNames =  $selectedScopeClassName::all()->pluck("name", "id");

        /*$this->selectedRole = Role::where('name', 'Staff')->first();
        if (!$this->selectedRole) {
            $this->selectedRoleId = $this->selectedRole->id;
        }

        $this->checkPermissionsExistsOrCreate($this->resourceNames);
        $this->setupResourceControlButtonGroup();*/

    }


    public function updatedSelectedScopeId($id) {

        $this->updateSelectedScope();
        $this->showResourceControlButtonGroup = false;
        //$this->selectedModule = null;
     }


     public function updatedSelectedModule($module) {
        $this->showResourceControlButtonGroup = false;
     }


     protected function updateSelectedScope() {
        if ($this->selectedScopeName == 'Role') {
            //$data['scope'] = Role::with('team')->with('permissions')->findOrFail($id);
            $this->selectedScope = Role::with('permissions')->find($this->selectedScopeId);
        } else if ($this->selectedScopeName == 'User') {
            //$data['scope'] = User::with('team')->with('permissions')->findOrFail($id);
            $this->selectedScope  = User::with('permissions')->find($this->selectedScopeId);
        }
     }



    public function manageAccessControl() {

        $this->updateSelectedScope();
        if (!$this->selectedScope)
            return;

        $directory = app_path("Modules/".ucfirst($this->selectedModule)."/Models");
        $namespace = addslashes("App\\Modules\\".ucfirst($this->selectedModule)."\\Models\\");

        $this->resourceNames = $this->getAllModelNames($directory, $namespace);


        $this->checkPermissionsExistsOrCreate($this->resourceNames);
        $this->setupResourceControlButtonGroup();

        //$this->dispatch('$refresh');
        //$this->dispatch('refreshComponentEvent');
        $this->showResourceControlButtonGroup = true;

    }








    private function getModuleNames() {
        $moduleNames = [];
        // Get all module directories
        $modules = File::directories(base_path('app/Modules'));

        // Loop through each module to load views, routes, and config files dynamically
        foreach ($modules as $module) {
            $moduleNames[] = basename($module); // Get the module name from the directory
        }

        return $moduleNames;
    }




    public function getAllModelNames($directory = null, $namespace = 'App\\Models\\')
    {


        if (!$directory) {
            $directory = app_path('Models');
        }

        $models = [];
        if (!file_exists($directory))
            return $models;
        
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $fullClassName = $namespace . str_replace(['/', '.php'], ['\\', ''], $relativePath);


            //if (class_exists($fullClassName)) {
                // Take class name out of the path
                $models[] = class_basename($fullClassName);
            //}
        }

        return $models;
    }




    protected function checkPermissionsExistsOrCreate($resourceNames) {
        foreach($resourceNames as $resourceName) {
            $permissionNames = $this->getResourcePermissionNames($resourceName);
            foreach($permissionNames as $permissionName) {
                if(!Permission::where("name", $permissionName)->first())
                Permission::create(['name' => $permissionName, 'description' => 'Allow role or user to '.str_replace('_', ' ',$permissionName)]);
            }
        }
    }



    private function setupResourceControlButtonGroup () {
        foreach ($this->resourceNames as $resourceName) {
            $resourcePermissionNames = $this->getResourcePermissionNames($resourceName);
            if (empty($this->resourceControlButtonGroup[$resourceName]))
                $this->resourceControlButtonGroup[$resourceName] = $this->getPermissionConfig($resourceName, $resourcePermissionNames);
        }

    }




    private function getPermissionConfig($resource, $resourcePermissionNames) {
        $resourcePermissionNameConfig = [];


        foreach ($resourcePermissionNames as $key => $resourcePermissionName) {
            $control = explode('_',$resourcePermissionName)[0];
            $resource = explode('_',$resourcePermissionName)[1];

            //dd(boolval(in_array($resourcePermissionName, $this->selectedRole->getPermissionNames()->toArray())));

            $resourcePermissionNameConfig [] = [
                'model' => Role::class,
                'stateSyncMethod' => 'method',
                'recordId' => $this->selectedScopeId,
                'componentId' => $resourcePermissionName,
                'onStateValue' => $resourcePermissionName,
                'offStateValue' => '',
                'state' => boolval(in_array($resourcePermissionName, $this->selectedScope->getPermissionNames()->toArray())),
                'icon' => $this->controlsCSSClasses[$control]['icon'],
                'iconBg' => "light",
                'iconColor' => "dark",
                'subtitle' => "<span> <strong>".$this->selectedScope?->name."</strong> should be able to <strong>$control</strong> $resource records</span>",
            ];
        }

      return $resourcePermissionNameConfig;
    }



    // Get resoure permission name list
    private function getResourcePermissionNames($resourceName) {
        $resourcePermissionNames = [];
        $resourceName = Str::snake($resourceName);
        foreach($this->controlList as $control) {
            $resourcePermissionNames[] = strtolower($control."_".$resourceName );
        }
        return $resourcePermissionNames;
    }


    public function render()
    {
        return view('access.views::access-controls.access-control-manager');
    }
}
