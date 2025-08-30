<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\PayGrade',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:pay_grades,name',
      'label' => 'Name',
    ], 

    'min_salary' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Min Salary',
    ], 

    'max_salary' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Max Salary',
    ], 

    'currency' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Currency',
    ], 

    'description' =>    [
      'display' => 'inline',
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
      'title' => 'Pay Grade Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'min_salary',
        2 => 'max_salary',
        3 => 'currency',
        4 => 'description',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
