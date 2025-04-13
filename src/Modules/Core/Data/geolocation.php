<?php

return [
  'model' => 'App\\Modules\\core\\Models\\Geolocation',
  'fieldDefinitions' =>  [
    'name' =>    [
      'field_type' => 'string',
      'validation' => 'required|string|max:255',
      'label' => 'Name',
    ], 

    'address' =>    [
      'field_type' => 'textarea',
      'validation' => 'required|string|max:255',
      'label' => 'Address',
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
      'title' => 'Geolocation Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'address',
        2 => 'description',
      ], 

    ], 

  ], 

];
