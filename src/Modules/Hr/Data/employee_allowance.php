<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\EmployeeAllowance',
  'fieldDefinitions' =>  [
    'payroll_run_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\PayrollRun',
        'type' => 'belongsTo',
        'display_field' => 'payroll_number',
        'dynamic_property' => 'payrollRun',
        'foreign_key' => 'payroll_run_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\PayrollRun',
        'column' => 'payroll_number',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Payroll Number',
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
      'validation' => 'required',
      'label' => 'Employee ID',
    ], 

    'allowance_type_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\AllowanceType',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'allowanceType',
        'foreign_key' => 'allowance_type_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\AllowanceType',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Allowance Type',
    ], 

    'amount' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Fixed Amount (or %]',
    ], 

    'addition_type' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'options' =>      [
        'Fixed_Amount' => 'Fixed_Amount',
        '  Percentage_Of_Basic_Salary' => '  Percentage_Of_Basic_Salary',
        ' Percentage_Of_Gross_Pay' => ' Percentage_Of_Gross_Pay',
      ], 

      'label' => 'Addition Type',
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
      'title' => 'Allowance Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'payroll_run_id',
        1 => 'employee_profile_id',
        2 => 'allowance_type_id',
        3 => 'addition_type',
        4 => 'amount',
        5 => 'notes',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
