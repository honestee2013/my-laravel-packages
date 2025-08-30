<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\EmployeeProfile',
  'fieldDefinitions' =>  [
    'employee_id' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:employee_profiles,employee_id',
      'label' => 'Employee ID',
      'autoGenerate' => true,
    ], 

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
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required|unique:basic_infos,user_id',
      'label' => 'User',
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

    'shift_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\Shift',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'shift',
        'foreign_key' => 'shift_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\Shift',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required|int',
      'label' => 'Shift',
    ], 

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
      'label' => 'Reporting Manager ID',
    ], 

    'job_title_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\JobTitle',
        'type' => 'belongsTo',
        'display_field' => 'title',
        'dynamic_property' => 'jobTitle',
        'foreign_key' => 'job_title_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\JobTitle',
        'column' => 'title',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Job Title',
    ], 

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
      'validation' => 'required',
      'label' => 'Primary Role',
    ], 

    'employment_type' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'options' =>      [
        'Full-Time' => 'Full-Time',
        ' Part-Time' => ' Part-Time',
        ' Contract' => ' Contract',
      ], 

      'label' => 'Employment Type',
    ], 

    'hourly_rate' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required',
      'label' => 'Hourly Rate',
    ], 

    'work_location' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Work Location',
    ], 

    'joining_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required|string|max:255',
      'label' => 'Joining Date',
    ], 

    'termination_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'label' => 'Termination Date',
    ], 

    'notes' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'validation' => 'nullable',
      'label' => 'Notes',
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
        0 => 'employee_id',
        1 => 'user_id',
        2 => 'job_title_id',
        3 => 'role_id',
        4 => 'shift_id',
        5 => 'employment_type',
        6 => 'hourly_rate',
        7 => 'joining_date',
        8 => 'termination_date',
      ], 

    ], 

    1 =>    [
      'title' => 'Job Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'department',
        1 => 'designation',
        2 => 'employee_profile_id',
        3 => 'work_location',
        4 => 'notes',
      ], 

    ], 

  ], 

  'moreActions' =>  [
    0 =>    [
      'title' => 'Show ID Card',
      'icon' => 'fas fa-id-card',
      'route' => 'show.employee.id.card',
    ], 

    1 =>    [
      'title' => 'Download ID Card (PDF]',
      'icon' => 'fas fa-file-pdf',
      'route' => 'download.employee.id.card',
    ], 

  ], 

  'report' =>  [
  ], 

];
