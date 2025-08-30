<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\BonusType',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:bonus_types,name',
      'label' => 'Name',
    ], 

    'description' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'label' => 'Description',
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
      0 => 'editable',
    ], 

    'onEditForm' =>    [
      0 => 'editable',
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
      'title' => 'Bonus Type Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'description',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
