<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\RoleSchedule',
  'fieldDefinitions' =>  [
    'name' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:role_schedules,name',
      'label' => 'Name',
    ], 

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

    'shift_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\Shift',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'shift',
        'foreign_key' => 'shift_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\Shift',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Shift',
    ], 

    'day_of_week_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\DayOfWeek',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'dayOfWeek',
        'foreign_key' => 'day_of_week_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\DayOfWeek',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required',
      'label' => 'Day Of Week',
    ], 

    'override_time_start' =>    [
      'display' => 'inline',
      'field_type' => 'timepicker',
      'label' => 'Override Time Start',
    ], 

    'override_time_end' =>    [
      'display' => 'inline',
      'field_type' => 'timepicker',
      'label' => 'Override Time End',
    ], 

    'overtime_after_hours' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Overtime After Hours',
    ], 

    'max_paid_overtime_hours' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Max Paid Overtime Hours',
    ], 

    'max_daily_hours' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'nullable|numeric|min:0',
      'label' => 'Max Daily Hours',
    ], 

    'break_rule_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\BreakRule',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'breakRule',
        'foreign_key' => 'break_rule_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Modules\\Hr\\Models\\BreakRule',
        'column' => 'name',
        'hintField' => NULL,
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'label' => 'Break Rule',
    ], 

    'late_grace_minutes' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'required|integer|min:0',
      'label' => 'Late Grace Minutes',
    ], 

    'early_leave_grace_minutes' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'required|integer|min:0',
      'label' => 'Early Leave Grace Minutes',
    ], 

    'effective_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'required|date',
      'label' => 'Effective Date',
    ], 

    'end_date' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'validation' => 'nullable|date|after_or_equal:effective_date',
      'label' => 'End Date',
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
      'title' => 'Schedule Assignment',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'name',
        1 => 'role_id',
        2 => 'shift_id',
        3 => 'day_of_week_id',
        4 => 'effective_date',
        5 => 'end_date',
        6 => 'is_active',
      ], 

    ], 

    1 =>    [
      'title' => 'Standard Times',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'time_start',
        1 => 'time_end',
      ], 

    ], 

    2 =>    [
      'title' => 'Overrides and Overtime',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'override_time_start',
        1 => 'override_time_end',
        2 => 'overtime_after_hours',
        3 => 'break_rule_id',
      ], 

    ], 

    3 =>    [
      'title' => 'Grace Periods',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'late_grace_minutes',
        1 => 'early_leave_grace_minutes',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
