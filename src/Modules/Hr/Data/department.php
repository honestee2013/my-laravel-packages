<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\Department',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|string|max:255',
      'label' => 'Department',
    ], 

    'company_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Organization\\Models\\Company',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'company',
        'foreign_key' => 'company_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Organization\\Models\\Company',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required|int',
      'label' => 'Company',
    ], 

    'description' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'label' => 'Description',
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
      'title' => 'Department Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'company_id',
        2 => 'description',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
