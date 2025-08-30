<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\RoleSalary',
  'fieldDefinitions' =>  [
    'role_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Access\\Models\\Role',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'role',
        'foreign_key' => 'role_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Access\\Models\\Role',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required|unique:role_salaries,role_id',
      'label' => 'Role',
    ], 

    'salary' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Salary',
    ], 

    'currency' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Currency',
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
      'title' => 'Role Salary Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'role_id',
        1 => 'salary',
        2 => 'currency',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
