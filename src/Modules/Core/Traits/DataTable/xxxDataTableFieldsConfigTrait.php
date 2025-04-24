<?php

namespace App\Modules\Core\Traits\DataTable;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


trait DataTableFieldsConfigTrait
{

    private $defaultHiddenFields = [ "remember_token"];

    public function configTableFields($moduleName, $modelName, $fileName = null) {

        if (!$fileName)
            $fileName = Str::snake($modelName);

        $moduleName = strtolower($moduleName);
        $configPath = "$moduleName.$fileName";

        $config = [
            "fieldDefinitions" => $this->initialiseFieldDefinitions(config( "$configPath.fieldDefinitions")) ?? [],
            "simpleActions" => config( "$configPath.simpleActions") ?? [],
            "moreActions" =>config( "$configPath.moreActions") ?? [],
            "hiddenFields" => $this->initialiseHiddenFields(config( "$configPath.hiddenFields")) ?? [],
            "controls" => config( "$configPath.controls") ?? [],
            "overrides" => config( "$configPath.overrides") ?? [],
        ];

        $config = $this->setFieldGroups($config, config( "$configPath.fieldGroups"));

        $config = $this->setColumns($config);
        $config = $this->setMultiSelectFormFields($config);
        $config = $this->overrideFields($config);
        
        return $config;
    }


    public function getConfigFileField($moduleName, $modelName, $fieldName) {
        $fileName = Str::snake($modelName);
        $moduleName = strtolower($moduleName);
        return config( "$moduleName.$fileName.$fieldName") ?? null;
    }


    private function setColumns($config) {
        // Populate the table fields
        $config["columns"] = [];

        foreach (array_keys($config["fieldDefinitions"]) as $fieldName) {
            $config["columns"][] = $fieldName;
        }
        $config["visibleColumns"] = $config["columns"];
        return $config;
    }


    private function setMultiSelectFormFields($config) {
        $available = false;
        foreach (array_keys($config["fieldDefinitions"]) as $fieldName) {
            // Handle multi selection form fields
            if (is_array($config["fieldDefinitions"][$fieldName])
                && isset($config["fieldDefinitions"][$fieldName]['multiSelect'])
                && $config["fieldDefinitions"][$fieldName]['multiSelect'])
            {
                $config["multiSelectFormFields"][$fieldName] = [];
                $available = true;
            }
        }

        // To avoid error set "multiSelectFormFields" to empty array
        if (!$available)
            $config["multiSelectFormFields"] = [];

        return $config;
    }


    public function extractModuleNameFromModel($model)
    {
        // Assuming the model namespace is something like "App\Modules\Inventory\Models\Item"
        $namespaceParts = explode('\\', $model);
        // Extract the module name from the namespace (e.g., "Inventory")
        return $namespaceParts[2] ?? 'default';
    }


    private function setFieldGroups($config, $fieldGroups) {
        if (!$fieldGroups && isset($config["fieldDefinitions"])) {
            $fieldGroups = ["fields" => array_keys($config["fieldDefinitions"])];
            $config["fieldGroups"] = [$fieldGroups];
            return $config;
        }

        // Add the default field group
        $fieldDefinitions = array_keys($config["fieldDefinitions"]);
        $groupedFields = [];
        $ungroupedFields = $fieldDefinitions;
        foreach ($fieldGroups as $group) {
            $groupedFields = array_merge($groupedFields, $group['fields']);
        }

        $ungroupedFields = array_diff($ungroupedFields, $groupedFields);

        if ($ungroupedFields)
            $fieldGroups[] = ["fields" => $ungroupedFields];


        $config["fieldGroups"] = $fieldGroups;

        return $config;

    }



    private function initialiseHiddenFields($hiddenFields) {
        // Setup the none existing hidden fields
        if (!isset($hiddenFields['onTable']))
            $hiddenFields['onTable'] = [];
        if (!isset($hiddenFields['onDetail']))
            $hiddenFields['onDetail'] = [];
        if (!isset($hiddenFields['onNewForm']))
            $hiddenFields['onNewForm'] = [];
        if (!isset($hiddenFields['onEditForm']))
            $hiddenFields['onEditForm'] = [];
        if (!isset($hiddenFields['onQuery']))
            $hiddenFields['onQuery'] = [];
        if (!isset($hiddenFields['onNewFormValidation']))
            $hiddenFields['onNewFormValidation'] = [];
        if (!isset($hiddenFields['onEditFormValidation']))
            $hiddenFields['onEditFormValidation'] = [];

        // Add the default hidden fields
        foreach ($this->defaultHiddenFields as $field) {
            if (!in_array($field, array_keys($hiddenFields['onTable'])))
                $hiddenFields['onTable'][] = $field;
            if (!in_array($field, array_keys($hiddenFields['onDetail'])))
                $hiddenFields['onDetail'][] = $field;
            if (!in_array($field, array_keys($hiddenFields['onNewForm'])))
                $hiddenFields['onNewForm'][] = $field;
            if (!in_array($field, array_keys($hiddenFields['onEditForm'])))
                $hiddenFields['onEditForm'][] = $field;
            if (!in_array($field, array_keys($hiddenFields['onQuery'])))
                $hiddenFields['onQuery'][] = $field;
            if (!in_array($field, array_keys($hiddenFields['onNewFormValidation'])))
                $hiddenFields['onNewFormValidation'][] = $field;
            if (!in_array($field, array_keys($hiddenFields['onEditFormValidation'])))
                $hiddenFields['onEditFormValidation'][] = $field;
        }

        return $hiddenFields;
    }


    //////////// INITIALISATION METHODS /////////////////
    private function initialiseFieldDefinitions($fieldDefinitions) {
        // If is not defined in the config file, porpulate it from the database table column fields
        if (!$fieldDefinitions) {
            $tableName = (new $this->model)->getTable();
            $fieldDefinitions = $this->getTableFieldsWithTypes($tableName);
        }

        return $fieldDefinitions;
    }

    private function getTableFieldsWithTypes($tableName)
    {
        // Query the INFORMATION_SCHEMA to get column names and types
        $columns = DB::select(
            "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND TABLE_SCHEMA = '" . env('DB_DATABASE') . "'"
        );

        // Prepare an array to store columns and their types
        $fields = [];
        foreach ($columns as $column) {
            $fields[$column->COLUMN_NAME] = $column->DATA_TYPE;
        }

        return $fields;
    }


    public function getSupportedImageColumnNames() {
        return ['image', 'photo', 'picture', 'logo', 'invoice'];
    }


    private function overrideFields($config) {

       foreach($config as $configKey => $configValue) {
            if (isset($config["overrides"][$configKey])) {
                foreach($configValue as $key => $value) {
                    if (isset($config["overrides"][$configKey][$key])) {
                        $config[$configKey][$key] = $config["overrides"][$configKey][$key];
                    }

                }

            }
       }

       return $config;
    }
}
