<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\DeductionType',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:deduction_types,name',
      'label' => 'Name',
    ], 

    'description' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'label' => 'Description',
    ], 

    'pre_tax' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Pre Tax',
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
      'title' => 'Deduction Type Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'pre_tax',
        2 => 'description',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
