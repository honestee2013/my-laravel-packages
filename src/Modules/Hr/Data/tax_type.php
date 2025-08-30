<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\TaxType',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:tax_types,name',
      'label' => 'Name',
    ], 

    'description' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'label' => 'Description',
    ], 

    'is_statutory' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'options' =>      [
        'Yes' => 'Yes',
        ' No' => ' No',
      ], 

      'label' => 'Is Statutory',
    ], 

    'rate' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Rate',
    ], 

    'wage_base_limit' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Wage Base Limit',
    ], 

    'additional_rate' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Additional Rate',
    ], 

    'additional_threshold' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Additional Threshold',
    ], 

    'editable' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'options' =>      [
        'Yes' => 'Yes',
        ' No' => ' No',
      ], 

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
      'title' => 'Tax Type Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'description',
        2 => 'is_statutory',
        3 => 'editable',
      ], 

    ], 

    1 =>    [
      'title' => 'Rates and Limits',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'rate',
        1 => 'wage_base_limit',
        2 => 'additional_rate',
        3 => 'additional_threshold',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
