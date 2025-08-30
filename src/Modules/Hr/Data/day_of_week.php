<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\DayOfWeek',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:days_of_week,name',
      'label' => 'Name',
    ], 

    'short_name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:days_of_week,short_name|max:3',
      'label' => 'Short Name',
    ], 

    'editable' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Editable',
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
      'title' => 'Day Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'short_name',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
