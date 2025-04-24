<?php

namespace QuickerFaster\CodeGen\Http\Livewire\DataTables;

use Livewire\Form;
use Livewire\Component;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;


use QuickerFaster\CodeGen\Facades\UserActivities\UserActivityLoggerFacade;
use QuickerFaster\CodeGen\Facades\DataTables\DataTableConfig;

use QuickerFaster\CodeGen\Services\CodeGenerators\CodeGeneratorService;

use QuickerFaster\CodeGen\Events\DataTableFormEvent;
use QuickerFaster\CodeGen\Events\DataTableFormFieldEvent;

use Illuminate\Support\Facades\Hash;




class DataTableForm extends Component
{

    use WithFileUploads;

    //////  EVENT LISTENERS  //////
    protected $listeners = [
        'openEditModalEvent' => 'openEditModal',
        'openAddModalEvent' => 'openAddModal',
        'openDetailModalEvent' => 'openDetailModal',

        'openCropImageModalEvent' => 'openCropImageModal',
        'saveCroppedImageEvent' => 'saveCroppedImage',

        'deleteSelectedEvent' => 'deleteSelected',
        'confirmDeleteEvent' => 'confirmDelete',

        'resetFormFieldsEvent' => 'resetFields',
        'submitDatatableFormEvent' => 'saveRecord',

        'refreshFieldsEvent' => 'refreshFields',

        'updateModelFieldEvent' => 'updateModelField',
    ];


    ///////// DEFINED AND PASSED IN BY THE PARENT CLASS /////////
    public $multiSelectFormFields;
    public $singleSelectFormFields;
    public $fieldDefinitions;
    public $fieldGroups;

    public $hiddenFields;
    public $columns;
    public $fields;
    public $model;
    public $moduleName;
    public $modelName;
    public $configFileName;
    public $config;

    public $modalId;



    ////// DEFINE BY THIS CLASS //////
    public $selectedItem;
    public $isEditMode;
    public $messages = [];
    public $selectedItemId;
    public $selectedRows = [];
    //public $modalStack;
    public $pageTitle;
    public $queryFilters = [];


    public function mount()
    {

        $this->dispatch("addModalFormComponentStackEvent", ['modalId' => $this->modalId, 'componentId' =>$this-> getId()]); // Close the model

        Log::info("DataTableForm->mount(): ".$this->getId());

        // Initialize fields with keys from fieldDefinitions
        foreach ($this->fieldDefinitions as $field => $type) {
            $this->fields[$field] = null; // Default values (null or empty)
        }


    }


    public function updated($field, $value)
    {
        
        $data = [
            'componentId' => $this->getId(),
            'field' => $field,
            'value'=> $value,
            'fieldDefinitions' => $this->fieldDefinitions,
            'fields' => $this->fields,
        ];

        // Event name = 'Model name' + 'FormFieldUpdatedEvent'. eg GeolocationFormFieldUpdatedEvent
        $event = "{$this->modelName}FormFieldUpdatedEvent";
        // Sending DtatTableForm Generic event
        DataTableFormFieldEvent::dispatch($data);

        // Specific
        if (class_exists("\\App\\Modules\\{$this->moduleName}\\Events\\{$event}"))
            $event::dispatch($data);

    }



    public function generateOrderNumber($model, $modelName, $field)
    {
        $code = CodeGeneratorService::generateCode($model, $modelName, $field);
        $this->fields[$field] = $this->fields[$field]?: $code;

    }










    public function refreshFields() {
        /*Log::info("DataTableForm->refreshFields(): getId: ".$this->getId());
        Log::info("DataTableForm->refreshFields(): model: ".$this->model);
        Log::info("DataTableForm->refreshFields(): modelName: ".$this->modelName);
        Log::info("DataTableForm->refreshFields(): moduleName: ".$this->moduleName);


        foreach ($this->fieldDefinitions as $fieldName => $type) {

            Log::info("Accessing field: $fieldName ");

            if( // Options field
                isset($this->fieldDefinitions[$fieldName]['options'])
                && isset($this->fieldDefinitions[$fieldName]['relationship'])
                && isset($this->fieldDefinitions[$fieldName]['relationship']["model"])
            ) {
                Log::info(message: "This has options : $fieldName ");

                /*$model = $this->fieldDefinitions[$fieldName]['relationship']["model"];
                $tableName = app($model)->getTable();
                if(Schema::hasColumn($tableName, 'display_name')) // Try using display_name if it exist
                    $this->fieldDefinitions[$fieldName]['options'] = $model::pluck('display_name', 'id')->toArray();
                else // name is always expected to exist
                    if(Schema::hasColumn($tableName, 'name')) // Try using display_name if it exist
                    $this->fieldDefinitions[$fieldName]['options'] = $model::pluck('name', 'id')->toArray();**** /

                $configPath = "$this->moduleName.".Str::snake($this->modelName);
                $configPath = strtolower($configPath);
                $configOptions = config("$configPath.fieldDefinitions.$fieldName.options", []);
                Log::info(message: "Config path : $configPath.fieldDefinitions.$fieldName.options");

                $updatedOptions = DataTableOption::getOptionList($configOptions);


                if ($updatedOptions) {
                    $this->fieldDefinitions[$fieldName]['options'] = $updatedOptions;
                    Log::info($this->fieldDefinitions[$fieldName]['options']);
                }
            }
        }

        $this->dispatch('$refresh');*/
    }



    // updateModelFieldEvent  handler
    public function updateModelField($modelIds, $fieldName, $fieldValue)
    {

        // Ensure $modelIds is an array of integers
        $modelIds = is_array($modelIds) ? array_map('intval', $modelIds) : [intval($modelIds)];

        // Validation
        if (empty($modelIds) || !is_array($modelIds)) {
            throw new InvalidArgumentException("Model IDs must be a non-empty array.");
        }
        // Validation: Ensure all IDs are integers
        foreach ($modelIds as $id) {
            if (!is_numeric($id)) {
                throw new InvalidArgumentException("All Model IDs must be numeric.");
            }
        }

        // Now create or update the model using the sanitized fields array
        if (isset($this->config["isTransaction"])) {
            DB::beginTransaction();
        }

        try {

            // Fetch old records
            $oldRecords = $this->model::whereIn('id', $modelIds)->get();

            // Ensure $modelIds is an array of integers
            $modelIds = is_array($modelIds) ? array_map('intval', $modelIds) : [intval($modelIds)];
            // Sending [After Update Event]
            $oldRecords = $this->model::whereIn('id', $modelIds)->get();
            // Perform the update
            $data = $this->addAuditTrailFields("updated", [$fieldName => $fieldValue]);
            $this->model::whereIn('id', $modelIds)->update($data);
            // Sending [After Update Event]
            $newRecords = $this->model::whereIn('id', $modelIds)->get();

            // Dispatch events
            $this->dispatchAllEvents("BeforeUpdate", $oldRecords, $newRecords);
            $this->dispatchAllEvents("AfterUpdate", $oldRecords, $newRecords);
            $this->dispatch('recordSavedEvent');

        } catch (\Exception $e) {
            if (isset($this->config["isTransaction"])) {
                DB::rollBack(); // Rollback the transaction
            }

            // Log the error or handle the exception as needed
            throw $e;
        }


    }


    private function addAuditTrailFields($action, $fields) {

        // Ensure that the [$action] is in the list of the [auditTrail] array
        if (!isset($this->config["auditTrail"]) || !array_search($action , $this->config["auditTrail"]))
            return $fields;

        $actorField = $action."_by";
        $actionTimeField = $action."_at";
        $fields[$actorField] = auth()->user()->id;
        $fields[$actionTimeField] = now();
        return $fields;
    }



    private function getSingleSelectFormFieldsValidatableFormat($singleSelectFormFields)
    {
        if (!is_array($singleSelectFormFields)) {
            return $singleSelectFormFields;
        }

        $formatted = [];
        foreach ($singleSelectFormFields as $key => $singleSelectFormField) {
            // For single select the first element should be the needed valeue
            $formatted[$key] = is_array($singleSelectFormField)? $singleSelectFormField[0]: $singleSelectFormField;
        }

        return $formatted;
    }

    // Save Record Method (Add or Edit)
    public function saveRecord($modalId)
    {

        //Log::info("DataTableForm->saveRecord(): ".$this->getId());

        // Retrieve dynamic validation rules
        $validationData = $this->getDynamicValidationRules();



        $validationMsgs = []; // ///To be implemented later

        // In Edit mode livewire threats the singleSelectFormFields as array
        if ($this->isEditMode)
            $this->singleSelectFormFields = $this->getSingleSelectFormFieldsValidatableFormat($this->singleSelectFormFields);

        // Validate inputs, including any custom messages
        if ($validationData)
            $this->validate($validationData, [...$this->messages, ...$validationMsgs]);

        // Handle file uploads for image, photo & picture fields
        foreach (DataTableConfig::getSupportedImageColumnNames() as $imageField) {
            if (isset($this->fields[$imageField]) && is_object($this->fields[$imageField])) {
                // Validate file type (e.g., ensure it's an image)
                if (!$this->fields[$imageField]->isValid()) {
                    throw new \Exception('Invalid file upload.');
                }

                $path = $this->fields[$imageField]->store('uploads', 'public'); // Use Laravel's Storage system
                $this->fields[$imageField] = $path; // Add the file path to the fields array
            }
        }


        // Handle simple (No Relationship involved) multi-select form fields (convert them to JSON for storage)
        if ($this->multiSelectFormFields) {
            foreach ($this->multiSelectFormFields as $fieldName => $value) {
                // Json_encode returns "['item1', 'item2']"
                $this->fields[$fieldName] = json_encode($value); // Store multi-select fields as JSON
            }
        }

        if ($this->singleSelectFormFields) {
            foreach ($this->singleSelectFormFields as $fieldName => $value) {

                if (is_array($value) && !empty($value))
                    $this->fields[$fieldName] = array_values($value)[0];//json_encode($value); // Store multi-select fields as JSON
                else if (!is_array($value) && !empty($value))
                    $this->fields[$fieldName] =  $value;
                else // Empty handling
                    $this->fields[$fieldName] = '';//json_encode($value); // Store multi-select fields as JSON

            }
        }


        // Ensure only allowed fields are saved (whitelisting)
        //$allowedFields = ['name', 'email', 'location', 'photo']; // Define which fields are allowed to be updated/created
        $allowedFields = $this->columns;
        // In any case remove
        $allowedFields = array_diff($allowedFields, $this->hiddenFields['onQuery']);

        if ($this->isEditMode) {
            $allowedFields = array_diff($allowedFields, $this->hiddenFields['onEditForm']);
        } else {
            $allowedFields = array_diff($allowedFields, $this->hiddenFields['onNewForm']);
        }



        $sanitizedFields = array_filter(
            $this->fields,
            fn($key) => in_array($key, $allowedFields),
            ARRAY_FILTER_USE_KEY
        );

        $sanitizedFields = $this->hashPasswordFields($sanitizedFields);
        

        // $sanitizedFields = $this->addAuditTrailFields("created", $sanitizedFields);
        if ($this->isEditMode)
            $sanitizedFields = $this->addAuditTrailFields("updated", $sanitizedFields);
        else
            $sanitizedFields = $this->addAuditTrailFields("created", $sanitizedFields);
        


        // Now create or update the model using the sanitized fields array
        if (isset($this->config["isTransaction"])) {
            DB::beginTransaction();
        }

        try {

            $record = null;
            if ($this->isEditMode) {
                $record = $this->model::find($this->selectedItemId);
                $oldRecord = $record->toArray();
                // Sending [After Update Event]
                $this->dispatchAllEvents("BeforeUpdate", $oldRecord, $sanitizedFields);
                // Update the record
                UserActivityLoggerFacade::logAction(auth()->id(), "Attempting to update $this->modelName record", $this->model, $this->selectedItemId);
                $record?->update($sanitizedFields);
                ////UserActivityLoggerFacade::logAction(auth()->id(), "Successfully updated $this->modelName record", $this->model, $this->selectedItemId);
                // Sending [After Update Event]
                $this->dispatchAllEvents("AfterUpdate", $oldRecord, $record->toArray());
                //dd($sanitizedFields, $record->fillable, $sanitizedFields["is_active"]);

            } else {
                // Sending [Before Create Event]
                $this->dispatchAllEvents("BeforeCreate", [], $sanitizedFields);
                // Create a new record
                UserActivityLoggerFacade::logAction(auth()->id(), "Attempting to add new $this->modelName record", $this->model, $this->selectedItemId);
                //dd($sanitizedFields);

                $record = $this->model::create($sanitizedFields);

                ////UserActivityLoggerFacade::logAction(auth()->id(), "Successfully to added new $this->modelName record", $this->model, $this->selectedItemId);
                // Sending [After Create Event]
                $this->dispatchAllEvents("AfterCreate", [], $record->toArray());
            }

            if (isset($this->config["isTransaction"])) {
                DB::commit(); // Commit the transaction
            }

        } catch (\Exception $e) {

            UserActivityLoggerFacade::logAction(auth()->id(), "Error updating {$this->modelName} record: {$e->getMessage()}", $this->model, $this->selectedItemId);

            if (isset($this->config["isTransaction"])) {
                DB::rollBack(); // Rollback the transaction
            }

            // Log the error or handle the exception as needed
            throw $e;
        }


        // In case the audit fields are hidden (i.e remove from $this->fields) add them back
        if ($this->isEditMode)
            $this->fields = $this->addAuditTrailFields("updated", $this->fields);
        else
            $this->fields = $this->addAuditTrailFields("created", $this->fields);


        // Handle complex (Relationship involved) multi-select form fields
        //if ($this->multiSelectFormFields && $record) {
        foreach ($this->fieldDefinitions as $fieldName => $value) {

            // Handle the array of company IDs and bulk update the geolocation_id for all companies at once
            if (
                isset($this->fieldDefinitions[$fieldName]['relationship'])
                && isset($this->fieldDefinitions[$fieldName]['relationship']['type'])
                && isset($this->fieldDefinitions[$fieldName]['relationship']['model'])
                && isset($this->fieldDefinitions[$fieldName]['relationship']['dynamic_property'])
            ) {

                // many-to-one & one-to-many
                if (isset($this->fieldDefinitions[$fieldName]['relationship']['foreign_key'])) {

                    $foreign_key = $this->fieldDefinitions[$fieldName]['relationship']['foreign_key'];
                    // one-to-many
                    if ($this->fieldDefinitions[$fieldName]['relationship']['type'] == 'hasMany') {
                        if (isset($this->multiSelectFormFields[$fieldName]) && is_array($this->multiSelectFormFields[$fieldName])) {
                            // Clean up the previous links
                            $record->{$this->fieldDefinitions[$fieldName]['relationship']['dynamic_property']}()
                                ->update([$foreign_key => null]);
                            // Attach new links
                            $this->fieldDefinitions[$fieldName]['relationship']['model']
                                ::whereIn('id', $this->multiSelectFormFields[$fieldName])->update([$foreign_key => $record->id]);
                        }

                    }
                    // many-to-one
                    else if ($this->fieldDefinitions[$fieldName]['relationship']['type'] == 'belongsTo') {
                        $record?->update([$foreign_key => $this->fields[$fieldName]]);
                    }
                    // many-to-many
                    else if ($this->fieldDefinitions[$fieldName]['relationship']['type'] == 'belongsToMany') {

                        if (isset($this->multiSelectFormFields[$fieldName]) && is_array($this->multiSelectFormFields[$fieldName])) {
                            $record?->{$this->fieldDefinitions[$fieldName]['relationship']['dynamic_property']}()
                                ->sync($this->multiSelectFormFields[$fieldName]);
                        }
                    }
                }


            }

        }


        // Display saving success message
        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'Record saved successfully.',
            'icon' => 'success',
        ]);





        // Close the modal, reset fields and refresh the table after saving
        if (!$this->isEditMode)
            $this->resetFields(); // Next new form should be blank
        $this->dispatch("recordSavedEvent"); // Table refresh
        $this->dispatch('closeModalEvent', ["modalId" => $modalId]);  // To show modal
        $this->dispatch('refreshFieldsEvent');  // To update  modal modal field dropdown options data
        $this->dispatch('$refresh');  // To update  modal modal field dropdown options data


    }


    private function dispatchAllEvents($eventName, $oldRecord, $newRecord) {
        if (!isset($this->config["dispatchEvents"]))
            return;

        // AVAILABLE FOR IMPLEMENTATION EVENTS:
        // DataTableFormEvent, DataTableFormBeforeCreateEvent,  DataTableFormAfterCreateEvent,
        // DataTableFormBeforeUpdateEvent,  DataTableFormAfterUpdateEvent,
        // {AnyModelName}Event, {AnyModelName}BeforeCreateEvent,  {AnyModelName}AfterCreateEvent,
        // {AnyModelName}BeforeUpdateEvent,  {AnyModelName}AfterUpdateEvent,

        // Sending DtatTableForm Generic event
        DataTableFormEvent::dispatch($oldRecord, $newRecord, $eventName, $this->model);

        // Sending DtatTableForm Specific event eg. DataTableForm{BeforeUpdate}Event
        $dataTableFormEvent = "DataTableForm{$eventName}Event";
        if(class_exists($dataTableFormEvent))
            $dataTableFormEvent::dispatch($oldRecord, $newRecord, $eventName, $this->model);


        // Specific Model releted eg. {User}BeforeUpdateEvent
        $specificEvent = $this->getSpecificEventFullName($eventName);
        $event = $this->getEventFullName();
        if (class_exists($specificEvent))
            $specificEvent::dispatch($oldRecord, $newRecord);

        // Generic Model releted eg. {User}Event
        if (class_exists($event))
            $event::dispatch($oldRecord, $newRecord, $eventName);
    }






private function getSpecificEventFullName($eventName) {
    return "\\App\\Modules\\{$this->moduleName}\\Events\\{$eventName}".$this->modelName."Event";
}

private function getEventFullName() {
    return "\\App\\Modules\\{$this->moduleName}\\Events\\".$this->modelName."Event";
}



    // Get Dynamic Validation Rules
    public function getDynamicValidationRules()
    {

        $rules = [];
        foreach ($this->fieldDefinitions as $field => $type) {

            // Check if  the field is not hidden only then validate
            if (
                (
                    $this->isEditMode && !in_array($field, $this->hiddenFields['onEditForm'])
                    && !in_array($field, $this->hiddenFields['onEditForm'])
                )
                ||
                (
                    !$this->isEditMode && !in_array($field, $this->hiddenFields['onNewForm'])
                     && !in_array($field, $this->hiddenFields['onNewForm'])
                )
            ) {

                // Handle multi-select form field validation and messages
                if (isset($this->multiSelectFormFields[$field])
                        && !empty($this->multiSelectFormFields[$field])
                    ) {

                    foreach (array_keys($this->multiSelectFormFields) as $fieldName) {
                        $validationFiedName = 'multiSelectFormFields.' . $fieldName;
                        if (isset($this->fieldDefinitions[$fieldName]) && isset($this->fieldDefinitions[$fieldName]['validation'])) {
                            $rules[$validationFiedName] = $this->fieldDefinitions[$fieldName]['validation'];
                        }
                    }

                // Handle single-select form field validation and messages
                } else if (isset($this->singleSelectFormFields[$field])
                        && !empty($this->singleSelectFormFields[$field])
                    ) {

                    foreach (array_keys($this->singleSelectFormFields) as $fieldName) {
                        $validationFiedName = 'singleSelectFormFields.' . $fieldName;
                        if (isset($this->fieldDefinitions[$fieldName]) && isset($this->fieldDefinitions[$fieldName]['validation'])) {
                            $rules[$validationFiedName] = $this->fieldDefinitions[$fieldName]['validation'];
                        }
                    }

                } else {

                    // Check if validation exists in fieldDefinitions  not on multiselect
                    if (isset($this->fieldDefinitions[$field]['validation'])) {
                        // Apply validation directly for non-image fields only

                        if (!in_array($field, DataTableConfig::getSupportedImageColumnNames())) {
                            // Handle the multiSelectFormFields outside this loop
                            //if (is_array($this->multiSelectFormFields) && !in_array($field, array_keys($this->multiSelectFormFields)))
                            $rules["fields.$field"] = $this->fieldDefinitions[$field]['validation'];
                            // e.g. "unique:users,email,{$this->recordId}" should not validate update record
                            $rules["fields.$field"] = $this->adjustUniqueFieldValidation($rules["fields.$field"]);

                        }
                        // Apply validation for image fields only if the image is an object (i.e., uploaded)
                        else if (is_object($this->fields[$field])) {
                            $rules["fields.$field"] = $this->fieldDefinitions[$field]['validation'];
                        }
                    }
                }



            }
        }

        
        return  $this->removePasswordConfirmIfPasswordNotChanged($rules); 

    }

    private function hashPasswordFields($sanitizedFields) {
        if ($this->isEditMode) {
            
            $record = $this->model::find($this->selectedItemId);
    
            foreach ($sanitizedFields as $key => $value) {
                if (str_contains($key, 'password')) {
                    if ($sanitizedFields[$key] != null) {
                        
                        // Only hash if it's different from the current hashed password
                        if (!Hash::check($value, $record->$key)) {
                            $sanitizedFields[$key] = Hash::make($value);
                        } else {
                            // Keep the original hashed password if unchanged
                            $sanitizedFields[$key] = $record->$key;
                        }
                    }
                }
            }
        } else {
            foreach ($sanitizedFields as $key => $value) {
                if (str_contains($key, 'password')) {
                    $sanitizedFields[$key] = Hash::make($value);
                }
            }
        }
    
        return $sanitizedFields;
    }
    


    private function removePasswordConfirmIfPasswordNotChanged($rules) {
       $before = $rules;
        if ($this->isEditMode) {
            foreach ($rules as $key => $value) {
                if (str_contains($key, 'password')) {
                    $inputField = str_replace('fields.', '', $key);
                    $inputValue = $this->fields[$inputField] ?? null;
    
                    // If the password field is empty, it means user did not change it
                    if (empty($inputValue)) {
                        unset($rules[$key]);
    
                        // Also remove confirmation rule if exists
                        $confirmKey = str_replace('password', 'password_confirmation', $key);
                        if (isset($rules[$confirmKey])) {
                            unset($rules[$confirmKey]);
                        }
                    }
                }
            }
        }

        //dd($before, $rules);
 
        return $rules;
    }
    
    

    /*
array:4 [▼ // /Users/mac/LaravelProjects/packages/quicker-faster/code-gen/src/Http/Livewire/DataTables/DataTableForm.php:638
  "fields.name" => "required"
  "fields.email" => "required|email|unique:users,email,2"
  "fields.password" => "required|min:8|confirmed"
  "singleSelectFormFields.user_type" => "required|string:max:255"
]



    */


    protected function adjustUniqueFieldValidation($validation) {
        // If the field includes "unique" and there's a selected item ID, customize the rule
        if (str_contains($validation, 'unique') && $this->selectedItemId) {
            // Find and modify the unique rule
            $validation = preg_replace(
                '/unique:([^,]+),([^,]+)/',
                "unique:$1,$2,{$this->selectedItemId}",
                $validation
            );
        }

        return $validation;
    }


    // Method to open the Edit Modal
    public function openEditModal($id, $model)
    {
        Log::info("DataTableForm->openEditModal(): Id: ".$id." this->model: ".$this->model);
        Log::info("DataTableForm->openEditModal(): Id: ".$id." model: ".$model);
        Log::info("DataTableForm->openEditModal(): ".$this->getId());

        // Reset multiple opend modal component to release space
        $this->dispatch('checkPageRefreshTimeEvent');

        // Reset all the form fields and make new ones
        $this->resetFields();

        $record = $model::find($id);
        $this->selectedItemId = $record->id;

        foreach ($this->fieldDefinitions as $field => $type) {
            // Only populate non-password fields
            if (str_contains($field, 'password')) {
                $this->fields[$field] = null;
            } else {
                $this->fields[$field] = $record->$field;
            }
        }
        

        // handle multi-selection form field
        if ($record && $this->multiSelectFormFields) {
            foreach (array_keys($this->multiSelectFormFields) as $fieldName) {
                // Handle hasMany relationship different
                if (
                    isset($this->fieldDefinitions[$fieldName]['relationship'])
                    && isset($this->fieldDefinitions[$fieldName]['relationship']['type'])
                ) {
                    if (
                        $this->fieldDefinitions[$fieldName]['relationship']['type'] == 'hasMany'
                        || $this->fieldDefinitions[$fieldName]['relationship']['type'] == 'belongsToMany'
                    ) {
                        if ($this->fieldDefinitions[$fieldName]['relationship']['dynamic_property']
                            && $record->{$this->fieldDefinitions[$fieldName]['relationship']['dynamic_property']}
                        ) {

                            $this->multiSelectFormFields[$fieldName]
                                = $record->{$this->fieldDefinitions[$fieldName]['relationship']['dynamic_property']}
                                    ->pluck('id')->toArray();
                        }
                    }


                }
                // Handle other: multiSelect & hasOne relationship same
                else {
                    // Initialise the $multiSelectFormFields array with record values
                    //if ($record->$fieldName) // for empty record, Empty array '[]' is needed instead of NULL
                    $this->multiSelectFormFields[$fieldName] = json_decode($record->$fieldName) ?? [];
                }
            }
        }
        // Handle single option selection
        if ($record && $this->singleSelectFormFields) {
            foreach (array_keys($this->singleSelectFormFields) as $theField) {
                $this->singleSelectFormFields[$theField] = [$record->$theField];
            }
        }

        $this->isEditMode = true;
        //$this->dispatch('open-add-edit-modal' );
        //$this->dispatch('show-modal-main' );
        $this->dispatch('open-modal-event', ["modalId" => "addEditModal"]);  // To show modal

        $this->dispatch('changeFormModeEvent', ['mode' => 'edit']);
    }



    //////////////////// CROPPING IMAGE  /////////////////////
    public function showCropImageModal($field, $imgUrl)
    {
        $this->dispatch('show-crop-image-modal', ['field' => $field, 'imgUrl' => $imgUrl, 'id' => $this->getId()]);
    }



    public function saveCroppedImage($field, $croppedImageBase64, $id)
    {
        // Validate the Base64 string format
        if (!preg_match('/^data:image\/(jpg|jpeg|png);base64,/', $croppedImageBase64, $matches)) {
            // Handle invalid image format
            throw new \Exception('Invalid image format.');
        }

        // Extract the file extension from the Base64 string
        $extension = $matches[1] == 'jpeg' ? 'jpg' : $matches[1];

        // Decode the Base64 string to binary data
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageBase64));

        // Generate a unique file name
        $fileName = 'cropped_image_' . time() . '.' . $extension;

        // Use Laravel's Storage facade to save the file in a secure way
        $filePath = 'uploads/' . $fileName;


        // Save the file to the disk (public disk)
        Storage::disk('public')->put($filePath, $imageData);

        // Update the field with the relative file path
        $this->fields[$field] = $filePath;

        // Dispatch success notification
        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'Image was cropped successfully!',
            'icon' => 'success',
        ]);
    }



    // Reset the form fields
    public function resetFields()
    {
        // Handle multi selection form fields
        foreach ($this->fieldDefinitions as $field => $type) {
            $this->fields[$field] = null; // Reset each field
            if (isset($this->multiSelectFormFields[$field]))
                $this->multiSelectFormFields[$field] = [];
        }
        $this->selectedItemId = null;
    }



    ////////////////////// ADD EDIT MODAL ///////////////////
    // Method to open the Add Modal
    public function openAddModal()
    {
        // Reset multiple opend modal component to release space
        $this->dispatch('checkPageRefreshTimeEvent');

        $this->resetFields();
        $this->isEditMode = false;
        //$this->dispatch('open-add-edit-modal');
        $this->dispatch('open-modal-event', ["modalId" => "addEditModal"]);  // To show modal

        $this->dispatch('changeFormModeEvent', ['mode' => 'new']);
    }


    ////////////////////// CONFIRM DELETE /////////////////////
    public function confirmDelete($ids)
    {

        // Sanitize the input
        if (is_array($ids)) {
            $ids = array_filter(
                $ids,
                fn($index) => intval($ids[$index]),
                ARRAY_FILTER_USE_KEY
            );
        } else {
            $ids = [intval($ids)];
        }

        $this->selectedRows = $ids;
        // Emit an event to trigger SweetAlert
        $this->dispatch('confirm-delete');
    }


    public function deleteSelected()
    {
        $this->model::whereIn('id', $this->selectedRows)->delete();
        $this->resetSelection();
        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'Record deleted successfully.',
            'icon' => 'success',
        ]);
        $this->dispatch("recordDeletedEvent");
    }


    private function resetSelection()
    {
        $this->selectedRows = [];
        $this->selectedItem = '';
        $this->selectedItemId = '';
    }


    ///////////////////// SHOW DETAIL MODAL //////////////////
    public function openDetailModal($id, $model)
    {
        // Load the selected item details from the database
        $this->selectedItem = $model::findOrFail($id);
        // Emit event to trigger the modal
        $this->dispatch('changeSelectedItemEvent', $id);
        //$this->dispatch('open-show-item-detail-modal');
        $this->dispatch('open-modal-event', ["modalId" => "detail", "modalClass" => "childModal"]);
    }


    public function render()
    {
        return view('core.views::data-tables.data-table-form', ['key' => $this->getId()]);
    }

}


