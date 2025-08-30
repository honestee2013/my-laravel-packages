<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\DailyEarning',
  'fieldDefinitions' =>  [
    'employee_id' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required',
      'label' => 'Employee ID',
    ], 

    'work_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required',
      'label' => 'Work Date',
    ], 

    'regular_hours' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'label' => 'Regular Hours',
    ], 

    'overtime_hours' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'label' => 'Overtime Hours',
    ], 

    'total_hours' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'label' => 'Total Hours',
    ], 

    'regular_amount' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'label' => 'Regular Amount',
    ], 

    'overtime_amount' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'label' => 'Overtime Amount',
    ], 

    'total_amount' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'label' => 'Total Amount',
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
      'title' => 'Hours Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'employee_id',
        1 => 'work_date',
        2 => 'regular_hours',
        3 => 'overtime_hours',
        4 => 'total_hours',
      ], 

    ], 

    1 =>    [
      'title' => 'Earning Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'regular_amount',
        1 => 'overtime_amount',
        2 => 'total_amount',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
