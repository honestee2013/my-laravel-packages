<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\Shift',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:shifts,name',
      'label' => 'Name',
    ], 

    'start_time' =>    [
      'display' => 'inline',
      'field_type' => 'timepicker',
      'validation' => 'required',
      'label' => 'Start Time',
    ], 

    'end_time' =>    [
      'display' => 'inline',
      'field_type' => 'timepicker',
      'validation' => 'required',
      'label' => 'End Time',
    ], 

    'is_overnight' =>    [
      'display' => 'inline',
      'field_type' => 'checkbox',
      'label' => 'Is Overnight',
    ], 

    'is_active' =>    [
      'display' => 'inline',
      'field_type' => 'checkbox',
      'label' => 'Is Active',
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
      'title' => 'Shift Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'start_time',
        2 => 'end_time',
        3 => 'is_overnight',
        4 => 'is_active',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
