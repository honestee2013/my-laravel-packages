<?php

namespace QuickerFaster\CodeGen\Http\Livewire\DataTables;

use App\Modules\Access\Http\Livewire\AccessControls\AccessControlManager;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;



use QuickerFaster\CodeGen\Facades\DataTables\DataTableConfig;

use QuickerFaster\CodeGen\Traits\DataTable\DataTableFieldsConfigTrait;
use QuickerFaster\CodeGen\Traits\DataTable\DataTableConfigTrait;
use QuickerFaster\CodeGen\Traits\DataTable\DataTableControlsTrait;

use App\Modules\Access\Models\Permission;
use Illuminate\Support\Facades\Auth;
use QuickerFaster\CodeGen\Services\AccessControl\AccessControl;

use App\Modules\Access\Models\Role;
use QuickerFaster\CodeGen\Services\AccessControl\AccessControlPermissionService;

class DataTableManager extends Component
{

    use DataTableControlsTrait;


    public $configFileName;
    public $config;
    public $modelName;
    public $recordName;
    public $moduleName;

    public $model;
    public $controls;
    public $columns;
    public $visibleColumns;
    public $fieldDefinitions;
    public $fieldGroups;
    public $multiSelectFormFields;
    public $singleSelectFormFields;
    public $simpleActions;
    public $moreActions;
    public $hiddenFields;
    public $readOnlyFields;

    public $isEditMode;
    public $selectedItem;

    public $sortField = 'id'; // Default sort field
    public $sortDirection = 'asc'; // Default sort direction
    public $perPage = 10;

    public $modalCount = 0;
    public $refreshModalCount = 20;

    public $modalStack = [];
    public $modalCache = [];
    public $feedbackMessages = "";

    public $pageTitle;
    public $queryFilters = [];

    public $modalId = 'addEditModal';


    protected $listeners = [
        "setFeedbackMessageEvent" => "setFeedbackMessage",
        "changeFormModeEvent" => "changeFormMode",
        "changeSelectedItemEvent" => "changeSelectedItem",
        "openAddRelationshipItemModalEvent" => "openAddRelationshipItemModal",

        //"openCropImageModalEvent" => "openCropImageModal",
        'checkPageRefreshTimeEvent' => 'checkPageRefreshTime',

        'addModalFormComponentStackEvent' => 'addModalFormComponentStack',
        'closeModalEvent' => 'closeModal',
    ];




    public function mount()
    {

        // dd(auth()->user()?->can('view_user'));
       //$accessControlManager = new AccessControlManager();
       //$accessControlManager->seedPermissions();

        Log::info("DataTableManager->mount(): " . $this->getId());

        $this->feedbackMessages = "";

        $this->modelName = class_basename($this->model);
        if (!$this->moduleName)
            $this->moduleName = DataTableConfig::extractModuleNameFromModel($this->model);


        if (!$this->recordName)
            $this->recordName = $this->modelName;

        //$data = $this->configTableFields($this->moduleName, $this->modelName, $this->configFileName);
        $this->config = DataTableConfig::getConfigFileFields($this->model);

        $this->fieldDefinitions = $this->config["fieldDefinitions"];
        $this->simpleActions = $this->config["simpleActions"];
        $this->moreActions = $this->config["moreActions"];
        $this->fieldGroups = $this->config["fieldGroups"];

        // Apply the hidden fields from the "DataTableManager"
        foreach ($this->config["hiddenFields"] as $key => $hiddenField) {
            if (isset($this->hiddenFields[$key]))
                $this->hiddenFields[$key] = array_unique(array_merge($this->hiddenFields[$key], $this->config["hiddenFields"][$key]));
            else
                $this->hiddenFields[$key] = $this->config["hiddenFields"][$key];
        }


        $this->controls = $this->config["controls"];
        $this->columns = $this->config["columns"];
        $this->multiSelectFormFields = $this->config["multiSelectFormFields"];
        $this->singleSelectFormFields = $this->config["singleSelectFormFields"];

        // If custom controls are passed, use them. Otherwise, fetch default controls from the trait.
        $this->controls = $this->getPreparedControls($this->controls);

        $this->visibleColumns = $this->config["columns"];// Show all columns by default
        // Hidden on table index view
        if ($this->hiddenFields['onTable'])
            $this->visibleColumns = array_diff($this->visibleColumns, $this->hiddenFields['onTable']);

        // Hidden on form
        if ($this->readOnlyFields == null)
            $this->readOnlyFields = [];

        \Log::info($this->getInlinableModels());

    }


    private function initialiseData() {

    }


    public function getModuleModels()
    {
        $models = [];
        $models = array_merge($models, $this->getModelsFromModule($this->moduleName));
        return $models;
    }

    public function getModelsFromModule($moduleName)
    {
        $models = [];
        $modulePath = base_path("app/Modules/{$moduleName}/Models");
        $files = scandir($modulePath);
        foreach ($files as $file) {
            if (is_file($modulePath . "/" . $file)) {
                $modelName = str_replace(".php", "", $file);
                $models[] = $modelName;
            }
        }
        return $models;
    }



    public function getInlinableModels()
    {
        $inlinableModels = [];
        foreach ($this->fieldDefinitions as $field => $fieldData) {
            if (isset($fieldData['relationship']['inlineAdd']) && $fieldData['relationship']['inlineAdd']) {
                $inlinableModels[] = $fieldData['relationship']['model'];
            }
        }
        return array_unique($inlinableModels);
    }



    public function addModalFormComponentStack($data)
    {
        if (is_array($data) && isset($data['componentId'], $data['modalId'])) {
            $this->modalStack[$data['modalId']] = $data['componentId'];
            //array_push($this->modalStack, $data);
            //$this->modalStack = array_unique($this->modalStack);
            \Log::info(json_encode($this->modalStack));
            //$this->modalStack = array_unique($this->modalStack);
        }
    }


    public function closeModal($data)
    {

        if (!isset($data["modalId"])) {
            return;
        }

        // Remove the modal from the stack
        //array_pop($this->modalStack);

        $componentId = $this->modalStack[$data["modalId"]] ?? null;
        //unset($this->modalStack[$data["modalId"]]);
        \Log::info(json_encode($this->modalStack));

        $d = [
            "modalId" => $data["modalId"],
            "componentId" => $componentId,
            "componentIds" => array_values($this->modalStack),
        ];

        $this->dispatch("close-modal-event", $d);
    }



    public function changeFormMode($data)
    {

        if ($data['mode'] == 'edit') {
            $this->isEditMode = true;
        } else {
            // New modal
            $this->isEditMode = false;
            // Update the stack
            //array_push($this->modalStack, $data['componentId']) ;
        }
    }


    public function changeSelectedItem($id)
    {
        // Load the selected item details from the database
        $this->selectedItem = $this->model::findOrFail($id);
    }



    public function openAddRelationshipItemModal($model, $moduleName = "", $recordId = null)
    {
        // Use a consistent cache key based on model and record ID
        /*$cacheKey = $model . "_" . ($recordId ?? "new");

        if (isset($this->modalCache[$cacheKey])) {
            $modalHtml = $this->modalCache[$cacheKey]["modalHtml"];
            $modalId = $this->modalCache[$cacheKey]["modalId"];
            Log::info("Reusing cached modal for {$model} (Modal ID: {$modalId})");
            $this->dispatch("open-child-modal-event", ['modalHtml' => $modalHtml, "modalId" => $modalId]);
            return;
        }*/

        Log::info("Opening Add Relationship Item Modal. Model: {$model}, Module: {$moduleName}");

        // Reset multiple open modals if needed
        $this->checkPageRefreshTime();

        // Assign next modal ID
        $modalId = ++$this->modalCount;

        // Determine module name if not provided
        $modelName = class_basename($model);
        if (!$moduleName) {
            $moduleName = DataTableConfig::extractModuleNameFromModel($model);
        }

        // Get configuration data for the table fields
        $data = DataTableConfig::getConfigFileFields($model);

        Log::info("Config Data Retrieved", $data);

        // Prepare modal data
        $data["modalId"] = $modalId;
        $data["isEditMode"] = false;
        $data["model"] = $model;
        $data["moduleName"] = $moduleName;
        $data["modelName"] = $modelName;
        $data["modalClass"] = "childModal";
        $data["fieldGroups"] = $data["fieldGroups"] ?? [];
        $data["config"] = $data;


        // Render modal views
        $modalHeader = view('core.views::data-tables.modals.modal-header', $data)->render();
        $modalBodyContent = view('core.views::data-tables.partials.form-render', $data)->render();
        $modalBody = view('core.views::data-tables.modals.modal-body', ["content" => $modalBodyContent])->render();
        $modalFooter = view('core.views::data-tables.modals.modal-footer', $data)->render();

        // Combine modal HTML
        $modalHtml = $modalHeader . $modalBody . $modalFooter;

        // Cache the modal HTML
        //$this->modalCache[$cacheKey] = ["modalHtml" => $modalHtml, "modalId" => $modalId];

        // Dispatch the modal event
        Log::info("Dispatching open-child-modal-event with modalId: " . $modalId);
        $this->dispatch("open-child-modal-event", ['modalHtml' => $modalHtml, "modalId" => $modalId]);
    }







    /*public function openCropImageModal($field, $imgUrl, $id)
    {

        //$modalId = ++$this->modalCount;
        
        $modalId = "crop-image-modal";
        $data = [
            "modalId" => $modalId,
            "field" => $field,
            "imgUrl" => $imgUrl,
            "id" => $id,
        ];

        //@include('core.views::data-tables.modals.crop-image-modal')
        $modalHtml = view('core.views::data-tables.modals.crop-image-modal', $data)->render();
        //$this->dispatch("open-add-relationship-modal", ['modalHtml' => $modalHtml, "modalId" => $modalId]);
        $data["modalHtml"] = $modalHtml;

        ///$this->dispatch("open-add-relationship-modal", $data);
        $this->dispatch("show-crop-image-modal-event", $data);
    }*/


    public function checkPageRefreshTime()
    {
        if ($this->modalCount >= $this->refreshModalCount) {
            $this->dispatch('confirm-page-refresh');
        }
    }



    //////////////// RENDERING METHODS /////////////////
    public function render()
    {
        return view('core.views::data-tables.data-table-manager', []);
    }



}
