<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\BreakRule',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:break_rules,name',
      'label' => 'Name',
    ], 

    'after_hours' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'After Hours',
    ], 

    'min_shift_minutes' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'required|integer|min:0',
      'label' => 'Min Shift Minutes',
    ], 

    'break_duration_minutes' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'required|integer|min:0',
      'label' => 'Break Duration Minutes',
    ], 

    'break_type' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'options' =>      [
        'Paid' => 'Paid',
        ' Unpaid' => ' Unpaid',
      ], 

      'label' => 'Break Type',
    ], 

    'max_breaks' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'required|integer|min:0',
      'label' => 'Max Breaks',
    ], 

    'break_interval_minutes' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'nullable|integer|min:0',
      'label' => 'Break Interval Minutes',
    ], 

    'compliance_standard_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\ComplianceStandard',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'complianceStandard',
        'foreign_key' => 'compliance_standard_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\ComplianceStandard',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'label' => 'Compliance Standard',
    ], 

    'is_mandatory' =>    [
      'display' => 'inline',
      'field_type' => 'radio',
      'options' =>      [
        'Yes' => 'Yes',
        ' No' => ' No',
      ], 

      'label' => 'Is Mandatory',
    ], 

    'is_active' =>    [
      'display' => 'inline',
      'field_type' => 'radio',
      'options' =>      [
        'Yes' => 'Yes',
        ' No' => ' No',
      ], 

      'label' => 'Is Active',
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
      'title' => 'Rule Triggers',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'after_hours',
        2 => 'min_shift_minutes',
      ], 

    ], 

    1 =>    [
      'title' => 'Break Specifications',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'break_duration_minutes',
        1 => 'break_type',
        2 => 'max_breaks',
        3 => 'break_interval_minutes',
      ], 

    ], 

    2 =>    [
      'title' => 'Compliance & Status',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'compliance_standard_id',
        1 => 'is_mandatory',
        2 => 'is_active',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
