<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\TimeOffRequest',
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

    'leave_type' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'options' =>      [
        'Sick Leave' => 'Sick Leave',
        ' Maternity Leave' => ' Maternity Leave',
        ' Bereavement Leave' => ' Bereavement Leave',
        ' Public Holidays' => ' Public Holidays',
        ' Jury Duty' => ' Jury Duty',
        ' Sabbatical Leave' => ' Sabbatical Leave',
        ' Annual Leave' => ' Annual Leave',
        ' Unpaid Leave' => ' Unpaid Leave',
        ' Military Leave' => ' Military Leave',
        ' Paternity Leave' => ' Paternity Leave',
        ' Family And Medical Leave' => ' Family And Medical Leave',
        ' Casual Leave' => ' Casual Leave',
        ' Compensatory Leave' => ' Compensatory Leave',
        ' Time Off In Lieu' => ' Time Off In Lieu',
        ' Marriage Leave' => ' Marriage Leave',
        ' Study Leave' => ' Study Leave',
        ' Voting Leave' => ' Voting Leave',
        ' Adoption Leave' => ' Adoption Leave',
        ' Compassionate Leave' => ' Compassionate Leave',
        ' Holiday Leave' => ' Holiday Leave',
        ' Personal Leave' => ' Personal Leave',
        ' Religious Holidays' => ' Religious Holidays',
        ' Emergency Leave' => ' Emergency Leave',
        ' Garden Leave' => ' Garden Leave',
      ], 

      'label' => 'Leave Type',
    ], 

    'start_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required',
      'label' => 'Start Date',
    ], 

    'end_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required',
      'label' => 'End Date',
    ], 

    'submission_date' =>    [
      'display' => 'inline',
      'field_type' => 'datetimepicker',
      'label' => 'Submission Date',
    ], 

    'status' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'options' =>      [
        'Pending' => 'Pending',
        ' Approved' => ' Approved',
        ' Rejected' => ' Rejected',
      ], 

      'label' => 'Status',
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
        'hintField' => 'email',
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'label' => 'Reporting Manager',
    ], 

  ], 

  'hiddenFields' =>  [
    'onTable' =>    [
    ], 

    'onNewForm' =>    [
      0 => 'submission_date',
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
      'title' => 'Request Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'employee_profile_id',
        1 => 'leave_type',
        2 => 'start_date',
        3 => 'end_date',
      ], 

    ], 

    1 =>    [
      'title' => 'Status & Management',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'status',
        1 => 'user_id',
        2 => 'submission_date',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
