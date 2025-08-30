<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\EmployeeDocument',
  'fieldDefinitions' =>  [
    'employee_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\EmployeeProfile',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'employee',
        'foreign_key' => 'employee_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\EmployeeProfile',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Employee ID',
    ], 

    'document_type' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required',
      'label' => 'Document Type',
    ], 

    'file_path' =>    [
      'display' => 'inline',
      'field_type' => 'file',
      'validation' => 'required',
      'label' => 'File Path',
    ], 

    'original_name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Original Name',
    ], 

    'mime_type' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Mime Type',
    ], 

    'notes' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'validation' => 'nullable',
      'label' => 'Notes',
    ], 

  ], 

  'hiddenFields' =>  [
    'onTable' =>    [
      0 => 'file_path',
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
      'title' => 'Document Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'employee_id',
        1 => 'document_type',
        2 => 'file_path',
        3 => 'original_name',
        4 => 'mime_type',
        5 => 'notes',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
