<?php

return [
  'model' => 'App\\Modules\\hr\\Models\\Department',
  'fieldDefinitions' =>  [
    'name' =>    [
      'field_type' => 'string',
      'validation' => 'required|string|max:255',
      'label' => 'Department',
    ], 

    'company_id' =>    [
      'field_type' => 'select',
      'validation' => 'required|int',
      'label' => 'Company Id',
    ], 

    'description' =>    [
      'field_type' => 'textarea',
      'label' => 'Description',
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
      'title' => 'Department Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'company_id',
        2 => 'description',
      ], 

    ], 

  ], 

];
