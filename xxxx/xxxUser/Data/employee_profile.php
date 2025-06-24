<?php

return [
  'model' => 'App\\Modules\\User\\Models\\EmployeeProfile',
  'fieldDefinitions' =>  [
    'user_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Models\\User',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'user',
        'foreign_key' => 'user_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Models\\User',
        'column' => 'name',
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'User',
    ], 

    'employee_id' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:employee_profiles,employee_id',
      'label' => 'Employee',
      'autoGenerate' => true,
    ], 

    'department' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Department',
    ], 

    'designation' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Designation',
    ], 

    'joining_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required|string|max:255',
      'label' => 'Joining Date',
    ], 

    'reporting_manager_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Models\\User',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'reportingManager',
        'foreign_key' => 'reporting_manager_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Models\\User',
        'column' => 'name',
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'label' => 'Reporting Manager',
    ], 

    'job_title_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\user\\Models\\JobTitle',
        'type' => 'belongsTo',
        'display_field' => 'title',
        'dynamic_property' => 'jobTitle',
        'foreign_key' => 'job_title_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\user\\Models\\JobTitle',
        'column' => 'title',
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required|string|max:255',
      'label' => 'Job Title',
    ], 

    'employment_type' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required|string|max:255',
      'options' =>      [
        'Full-Time' => 'Full-Time',
        ' Part-Time' => ' Part-Time',
        ' Contract' => ' Contract',
      ], 

      'label' => 'Employment Type',
    ], 

    'work_location' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Work Location',
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
      'title' => 'Basic Employee Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'user_id',
        1 => 'employee_id',
        2 => 'job_title_id',
        3 => 'joining_date',
        4 => 'employment_type',
      ], 

    ], 

    1 =>    [
      'title' => 'Job Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'department',
        1 => 'designation',
        2 => 'reporting_manager_id',
        3 => 'work_location',
      ], 

    ], 

  ], 

];
