<?php

return [
  'model' => 'App\\Modules\\User\\Models\\JobTitle',
  'fieldDefinitions' =>  [
    'title' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:job_titles,title',
      'label' => 'Title',
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
      'title' => 'Job Title Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'title',
        1 => 'description',
      ], 

    ], 

  ], 

];
