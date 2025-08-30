<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\UserSalary',
  'fieldDefinitions' =>  [
    'employee_profile_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\EmployeeProfile',
        'type' => 'belongsTo',
        'display_field' => 'employee_id',
        'dynamic_property' => 'employeeProfile',
        'foreign_key' => 'employee_profile_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\EmployeeProfile',
        'column' => 'employee_id',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Employee ID',
    ], 

    'salary' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
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
      'title' => 'User Specific Salary',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'employee_profile_id',
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
