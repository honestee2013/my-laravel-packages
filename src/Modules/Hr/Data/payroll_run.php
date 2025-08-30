<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\PayrollRun',
  'fieldDefinitions' =>  [
    'payrollEmployees' =>    [
      'field_type' => 'checkbox',
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\PayrollEmployee',
        'type' => 'hasMany',
        'display_field' => 'employee_id',
        'hintField' => NULL,
        'dynamic_property' => 'payrollEmployees',
        'foreign_key' => 'payroll_run_id',
        'local_key' => 'id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\PayrollEmployee',
        'column' => 'employee_id',
        'hintField' => NULL,
      ], 

      'label' => 'Payroll Employees',
      'multiSelect' => true,
    ], 

    'payroll_number' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:payroll_runs,payroll_number',
      'label' => 'Payroll Number',
      'autoGenerate' => true,
    ], 

    'title' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:payroll_runs,title',
      'label' => 'Title',
    ], 

    'from_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required',
      'label' => 'daily earning from',
    ], 

    'to_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required',
      'label' => 'daily earning to',
    ], 

    'status' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'options' =>      [
        'Draft' => 'Draft',
        ' Components_Pending' => ' Components_Pending',
        ' Awaiting_Approval' => ' Awaiting_Approval',
        ' Approved' => ' Approved',
        ' Processing' => ' Processing',
        ' Awaiting_Payment' => ' Awaiting_Payment',
        ' Paid' => ' Paid',
        ' Cancelled' => ' Cancelled',
      ], 

      'label' => 'Status',
    ], 

    'payroll_components' =>    [
      'display' => 'inline',
      'field_type' => 'checkbox',
      'validation' => 'nullable',
      'options' =>      [
        'Allowances' => 'Allowances',
        ' Bonuses' => ' Bonuses',
        ' Taxes' => ' Taxes',
        ' Deductions' => ' Deductions',
      ], 

      'label' => 'Included Payroll Components',
      'multiSelect' => true,
    ], 

    'attendance_options' =>    [
      'display' => 'inline',
      'field_type' => 'checkbox',
      'validation' => 'nullable',
      'options' =>      [
        'Attendance_Exempted_Employee' => 'Attendance_Exempted_Employee',
        ' Attendance_Check_Only_Employee' => ' Attendance_Check_Only_Employee',
      ], 

      'label' => 'Included Attendance Exemptions',
      'multiSelect' => true,
    ], 

    'created_by' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'nullable',
      'label' => 'Created By',
    ], 

    'approved_by' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'nullable',
      'label' => 'Approved By',
    ], 

    'approved_at' =>    [
      'display' => 'inline',
      'field_type' => 'datetime',
      'label' => 'Approved At',
    ], 

    'paid_by' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'nullable',
      'label' => 'Paid By',
    ], 

    'paid_at' =>    [
      'display' => 'inline',
      'field_type' => 'datetime',
      'validation' => 'nullable',
      'label' => 'Paid At',
    ], 

    'cancelled_by' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'nullable',
      'label' => 'Cancelled By',
    ], 

    'cancelled_at' =>    [
      'display' => 'inline',
      'field_type' => 'datetime',
      'validation' => 'nullable',
      'label' => 'Cancelled At',
    ], 

    'notes' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'label' => 'Notes',
    ], 

    'editable' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Editable',
    ], 

  ], 

  'hiddenFields' =>  [
    'onTable' =>    [
      0 => 'roleAllowances',
      1 => 'roleBonuses',
      2 => 'empployeeAllowances',
      3 => 'empployeeBonuses',
      4 => 'roleDeductions',
      5 => 'employeeDeductions',
    ], 

    'onNewForm' =>    [
      0 => 'editable',
      1 => 'status',
      2 => 'created_by',
      3 => 'created_at',
      4 => 'approved_by',
      5 => 'approved_at',
      6 => 'paid_by',
      7 => 'paid_at',
      8 => 'cancelled_by',
      9 => 'cancelled_at',
      10 => 'roleAllowances',
      11 => 'roleBonuses',
      12 => 'empployeeAllowances',
      13 => 'empployeeBonuses',
      14 => 'roleDeductions',
      15 => 'employeeDeductions',
    ], 

    'onEditForm' =>    [
      0 => 'editable',
      1 => 'status',
      2 => 'created_by',
      3 => 'created_at',
      4 => 'approved_by',
      5 => 'approved_at',
      6 => 'paid_by',
      7 => 'paid_at',
      8 => 'cancelled_by',
      9 => 'cancelled_at',
      10 => 'roleAllowances',
      11 => 'roleBonuses',
      12 => 'empployeeAllowances',
      13 => 'empployeeBonuses',
      14 => 'roleDeductions',
      15 => 'employeeDeductions',
    ], 

    'onQuery' =>    [
    ], 

  ], 

  'simpleActions' =>  [
    0 => 'show',
    1 => 'edit',
    2 => 'delete',
  ], 

  'isTransaction' => true,
  'dispatchEvents' => true,
  'controls' => 'all',
  'fieldGroups' =>  [
    0 =>    [
      'title' => 'Payroll Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'payroll_number',
        1 => 'title',
        2 => 'from_date',
        3 => 'to_date',
        4 => 'status',
        5 => 'notes',
      ], 

    ], 

    1 =>    [
      'title' => 'Payroll Components',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'payroll_components',
      ], 

    ], 

    2 =>    [
      'title' => 'Attendance Exemptions',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'attendance_options',
      ], 

    ], 

    3 =>    [
      'title' => 'Audit Trails',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'created_by',
        1 => 'created_at',
        2 => 'approved_by',
        3 => 'approved_at',
        4 => 'paid_by',
        5 => 'paid_at',
        6 => 'cancelled_by',
        7 => 'cancelled_at',
      ], 

    ], 

    4 =>    [
      'title' => 'Included Allowances',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'roleAllowances',
        1 => 'empployeeAllowances',
      ], 

    ], 

    5 =>    [
      'title' => 'Included Bonuses',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'roleBonuses',
        1 => 'empployeeBonuses',
      ], 

    ], 

    6 =>    [
      'title' => 'Included Deductions',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'roleDeductions',
        1 => 'employeeDeductions',
      ], 

    ], 

  ], 

  'moreActions' =>  [
    0 =>    [
      'title' => 'Generate Payroll',
      'icon' => 'fas fa-cogs',
      'updateModelField' => true,
      'fieldName' => 'status',
      'fieldValue' => 'generated',
      'actionName' => 'generate_payroll',
      'handleByEventHandlerOnly' => true,
    ], 

    1 =>    [
      'title' => 'Approve Payroll',
      'icon' => 'fas fa-check-circle',
      'updateModelField' => true,
      'fieldName' => 'status',
      'fieldValue' => 'approved',
      'actionName' => 'approve_payroll',
      'handleByEventHandlerOnly' => true,
    ], 

    2 =>    [
      'title' => 'Process Payment',
      'icon' => 'fas fa-money-check-alt',
      'updateModelField' => true,
      'fieldName' => 'status',
      'fieldValue' => 'processed',
      'actionName' => 'process_payment',
      'handleByEventHandlerOnly' => true,
    ], 

    3 =>    [
      'title' => 'Cancel Payroll',
      'icon' => 'fas fa-ban',
      'updateModelField' => true,
      'fieldName' => 'status',
      'fieldValue' => 'cancelled',
      'actionName' => 'cancel_payroll',
      'handleByEventHandlerOnly' => true,
    ], 

    4 =>    [
      'title' => 'View Report',
      'icon' => 'fas fa-file-alt',
      'route' => 'reports.show',
      'params' =>      [
        'modelName' => 'PayrollRun',
        'moduleName' => 'Hr',
      ], 

    ], 

  ], 

  'report' =>  [
    'model' => 'App\\Modules\\hr\\Models\\PayrollRun',
    'itemsModel' => 'App\\Modules\\hr\\Models\\PayrollEmployee',
    'recordModel' => 'App\\Modules\\hr\\Models\\PayrollRun',
    'headerFields' =>    [
      0 => 'title',
      1 => 'payroll_number',
      2 => 'from_date',
      3 => 'to_date',
      4 => 'status',
    ], 

    'summaryFields' =>    [
      'base_salary' => 'Total Base Salary',
      'total_allowances' => 'Total Allowances',
      'total_bonuses' => 'Total Bonuses',
      'total_deductions' => 'Total Deductions',
      'net_salary' => 'Total Net Salary',
    ], 

    'signatories' =>    [
      0 =>      [
        'label' => 'Prepared By',
        'field' => 'created_by',
      ], 

      1 =>      [
        'label' => 'Approved By',
        'field' => 'approved_by',
      ], 

    ], 

    'foreignKey' => 'payroll_run_id',
  ], 

];
