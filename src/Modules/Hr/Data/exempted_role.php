<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\ExemptedRole',
  'fieldDefinitions' =>  [
    'role_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Access\\Models\\Role',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'role',
        'foreign_key' => 'role_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Access\\Models\\Role',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Role',
    ], 

    'payment_type' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'options' =>      [
        'Full Day' => 'Full Day',
        ' Half Day' => ' Half Day',
        ' Fixed Amount' => ' Fixed Amount',
      ], 

      'label' => 'Payment Type',
    ], 

    'fixed_amount' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Fixed Amount',
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
      'title' => 'Role Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'role_id',
        1 => 'payment_type',
        2 => 'fixed_amount',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
