<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\UserOnboardingStatus',
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

    'onboarding_task_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\OnboardingTask',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'onboardingTask',
        'foreign_key' => 'onboarding_task_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\OnboardingTask',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Onboarding Task',
    ], 

    'status' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'options' =>      [
        'To_Do' => 'To_Do',
        ' In_Progress' => ' In_Progress',
        ' Completed' => ' Completed',
      ], 

      'label' => 'Status',
    ], 

    'due_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'label' => 'Due Date',
    ], 

    'completed_at' =>    [
      'display' => 'inline',
      'field_type' => 'datetimepicker',
      'label' => 'Completed At',
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
      'title' => 'Assignment',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'employee_profile_id',
        1 => 'onboarding_task_id',
      ], 

    ], 

    1 =>    [
      'title' => 'Status',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'status',
        1 => 'due_date',
        2 => 'completed_at',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
