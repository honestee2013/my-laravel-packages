<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\ComplianceStandard',
  'fieldDefinitions' =>  [
    'breakRules' =>    [
      'field_type' => 'checkbox',
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\BreakRule',
        'type' => 'hasMany',
        'display_field' => 'name',
        'hintField' => NULL,
        'dynamic_property' => 'breakRules',
        'foreign_key' => 'compliance_standard_id',
        'local_key' => 'id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\BreakRule',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'label' => 'Break Rules',
      'multiSelect' => true,
    ], 

    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:compliance_standards,name',
      'label' => 'Name',
    ], 

    'description' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'label' => 'Description',
    ], 

    'country_code' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'nullable|max:2',
      'label' => 'Country Code',
    ], 

  ], 

  'hiddenFields' =>  [
    'onTable' =>    [
    ], 

    'onNewForm' =>    [
    ], 

    'onEditForm' =>    [
    ], 

    'onQuery' =>    [
    ], 

  ], 

  'simpleActions' =>  [
    0 => 'show',
    1 => 'edit',
    2 => 'delete',
  ], 

  'isTransaction' => false,
  'dispatchEvents' => false,
  'controls' => 'all',
  'fieldGroups' =>  [
    0 =>    [
      'title' => 'Standard Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'description',
        2 => 'country_code',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
