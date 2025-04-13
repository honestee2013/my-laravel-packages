<?php

protected function getConfigStub($module, $modelName, $modelData, $command)
{
    $stub = File::get(app_path('Modules/Core/Stubs/config.stub'));

    $includes = $modelData['includes'] ?? [];
    $includedConfig = [];

    foreach ($includes as $includeFile) {
        $includePath = app_path("Modules/{$module}/Data/{$includeFile}"); // Adjust path as needed
        if (File::exists($includePath)) {
            $includedConfig = array_merge($includedConfig, include $includePath);
        } else {
            $command->warn("Included file not found: {$includePath}"); // Handle missing files
        }
    }

    // Generate field definitions from YAML (unless overridden by include):
    //$yamlFieldDefinitions = $this->generateFieldDefinitionsFromYaml($module, $modelName, $modelData['fields'] ?? []);
    $yamlFieldDefinitions = [];
    foreach ($modelData['fields'] as $fieldName => $field) {

        if (isset($field['partial'])) {
            $partialPath = app_path("Modules/{$module}/Data/{$field['partial']}");
            if (File::exists($partialPath)) {
                $partialFieldDefinitions = include $partialPath;
                // Merge partial definitions into the main definitions.
                $yamlFieldDefinitions = array_merge($yamlFieldDefinitions, $partialFieldDefinitions);
            } else {
                $command->warn("Partial field definition file not found: {$partialPath}");
            }
        } else { // Regular field definition
            $yamlFieldDefinitions[$fieldName] = [
                'field_type' => $field['type'] ?? "string",
            ];

            if (isset($field['validation'])) {
                $validationString = implode('|', $field['validation']);
                $yamlFieldDefinitions[$fieldName]['validation'] = $validationString;
            }

            if (isset($field['options'])) {
                $options = $field['options'];

                if (!is_array($options)) {
                    $options = array_values(explode( ',', Str::title($options)));
                    $yamlFieldDefinitions[$fieldName]['options'] = array_combine($options, $options); // map as ["value" => "value"]
                } else {
                    $yamlFieldDefinitions[$fieldName]['options'] = $options;
                }

           }

            // Default label
            $label = Str::title(str_replace("_", " ",Str::snake($fieldName)));

            // Relationship (belogsTo)
            if (isset($field['foreign'])) {
                $relatedModel = null;
                $relationshipType = null;
                $displayField = null;
                // Find the related model and relationship type from the relations section
                if (isset($modelData['relations'])) {
                    foreach ($modelData['relations'] as $relationName => $relationData) {
                        if ($relationData['foreignKey'] == $fieldName) { // Match by foreign key
                            $relatedModel = $relationData['model'];
                            $relationshipType = $relationData['type'];
                            $displayField = $relationData['displayField']?? 'name';
                            break; // Found it, exit the loop
                        }
                    }
                }

                $dynamic_property = $this->getDynamicProperty($relationshipType, $fieldName);

                if ($relatedModel && $relationshipType) { // Only if the relationship is found.
                    $yamlFieldDefinitions[$fieldName]['relationship'] = [
                        'model' => $relatedModel, // Correct model path from relations
                        'type' => $relationshipType, // Correct relationship type from relations
                        'display_field' => $displayField, // Or whichever field you want to display
                        'dynamic_property' => $dynamic_property,
                        'foreign_key' => $fieldName,
                        'inlineAdd' => true,
                    ];
                    $yamlFieldDefinitions[$fieldName]['options'] = [
                        'model' => $relatedModel, // Correct model path from relations
                        'column' => $displayField, // Or whichever field you want to display
                    ];
                } else {
                    $command->warn("Relationship not found for field: {$fieldName}. Ensure it's defined in the 'relations' section.");
                }


                $label = Str::title(str_replace("_", " ",Str::snake($dynamic_property)));

            // Handle relations ( hasMany, belongsToMany)
            } else if (isset($modelData['relations'])) {
                foreach ($modelData['relations'] as $relationName => $relationData) {
                    $relatedModel = $relationData['model'];
                    $relationshipType = $relationData['type'];
                    $foreignKey = $relationData['foreignKey'];
                    $displayField = $relationData['displayField']?? 'name';

                    if ($relationshipType === 'hasMany' || $relationshipType === 'belongsToMany') {
                        // Add a virtual field for the hasMany relationship
                        $yamlFieldDefinitions[$relationName] = [
                            'field_type' => 'checkbox', // Or a suitable type for relationships
                            'relationship' => [
                                'model' => $relatedModel,
                                'type' => $relationshipType,
                                'display_field' => $displayField, // Or the field you want to display
                                'dynamic_property' => $relationName, // The name of the relationship
                                'foreign_key' => $foreignKey,
                                'local_key' => 'id', // Local key for the relationship
                                'inlineAdd' => false, // Or false, depending on your needs
                            ],
                            'options' => [
                                'model' => $relatedModel,
                                'column' => $displayField, // Or the field you want to display
                            ],
                            'label' => Str::title($relationName), // Label for the field
                            'multiSelect' => true,
                        ];
                    }
                }
            }

            // Other field configuration
            $yamlFieldDefinitions[$fieldName]['label'] = $field["label"]?? $label;

            // Check auto generate
            if (isset($field['autoGenerate'])) {
                $yamlFieldDefinitions[$fieldName]["autoGenerate"] =  $field['autoGenerate'];
            }


            foreach ($field as $key => $value) {
                $yamlFieldDefinitions[$fieldName][$key] = $value ;
            }


        }
    }



    // Handle fieldGroups
    $fieldGroups = $modelData['fieldGroups'] ?? [];
    $fieldGroupsString = var_export($fieldGroups, true);
    $fieldGroupsString = str_replace("array (", "[", str_replace(")", "]", $fieldGroupsString)); // Clean up var_export



    // Merge included configs and YAML generated configs (include overrides YAML if both exist).
    $configData = array_merge(
        $includedConfig, // Include first, so it has lower override priority.
        [
            "model" => "App\\Modules\\{$module}\\Models\\{$modelName}",
            "fieldDefinitions" => $yamlFieldDefinitions,
            "hiddenFields" => $modelData['hiddenFields'] ?? [],
            "simpleActions" => $modelData['simpleActions'] ?? [],
            "isTransaction" => $modelData['isTransaction'] ?? false,
            "dispatchEvents" => $modelData['dispatchEvents'] ?? false,
            "controls" => $modelData['controls'] ?? [],
            "fieldGroups" => $fieldGroups, // Add fieldGroups to the config data

        ],
    );

    /*foreach ($modelData as $key => $modelDataItem) {
        if (!array_search($key, array_keys($configData)))
            $configData[$key] = $modelDataItem;
    }*/


    $configString = var_export($configData, true);
    $configString = str_replace("=> \n", "=>", $configString); // Place [ on the same line

    $configString = str_replace("array (", "[", str_replace(")", "]", $configString)); // Clean up var_export
    $configString = str_replace("],", "], \n", $configString); // Add newline after ] closes

    $stub = str_replace('{{configData}}', $configString, $stub);

    return $stub;
}
