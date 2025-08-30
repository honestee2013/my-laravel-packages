<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\SalaryHistory',
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
      'validation' => 'required|numeric|min:0',
      'label' => 'Salary',
    ], 

    'currency' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required',
      'label' => 'Currency',
    ], 

    'effective_date' =>    [
      'display' => 'inline',
      'field_type' => 'datetimepicker',
      'validation' => 'required',
      'label' => 'Effective Date',
    ], 

    'reason' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Reason',
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
      'title' => 'Salary Change Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'employee_profile_id',
        1 => 'salary',
        2 => 'currency',
        3 => 'effective_date',
        4 => 'reason',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
