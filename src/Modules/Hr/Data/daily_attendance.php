<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\DailyAttendance',
  'fieldDefinitions' =>  [
    'attendance_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required',
      'label' => 'Attendance Date',
    ], 

    'employee_id' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:employee_profiles,employee_id',
      'label' => 'Employee ID',
      'autoGenerate' => true,
    ], 

    'attendance_type' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required',
      'label' => 'Attendance Type',
    ], 

    'attendance_time' =>    [
      'display' => 'inline',
      'field_type' => 'timepicker',
      'validation' => 'required',
      'label' => 'Attendance Time',
    ], 

    'scheduled_start' =>    [
      'display' => 'inline',
      'field_type' => 'timepicker',
      'label' => 'Scheduled Start',
    ], 

    'check_status' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Check Status',
    ], 

    'minutes_difference' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'integer|min:0',
      'label' => 'Minutes Difference',
    ], 

    'scheduled_end' =>    [
      'display' => 'inline',
      'field_type' => 'timepicker',
      'label' => 'Scheduled End',
    ], 

    'device_id' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required',
      'label' => 'Device',
    ], 

    'latitude' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'label' => 'Latitude',
    ], 

    'longitude' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'label' => 'Longitude',
    ], 

    'sync_status' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'options' =>      [
        'Pending' => 'Pending',
        ' Success' => ' Success',
        ' Failed' => ' Failed',
      ], 

      'label' => 'Sync Status',
    ], 

    'sync_attempts' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'required|integer|min:0',
      'label' => 'Sync Attempts',
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
      'title' => 'Attendance Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'attendance_date',
        1 => 'employee_id',
        2 => 'attendance_type',
        3 => 'attendance_time',
        4 => 'scheduled_start',
        5 => 'check_status',
        6 => 'minutes_difference',
        7 => 'scheduled_end',
        8 => 'device_id',
      ], 

    ], 

    1 =>    [
      'title' => 'Location & Sync',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'latitude',
        1 => 'longitude',
        2 => 'sync_status',
        3 => 'sync_attempts',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
