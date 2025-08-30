<?php

return [
  'model' => 'App\\Modules\\Hr\\Models\\PayrollYtd',
  'fieldDefinitions' =>  [
    'employee_id' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'validation' => 'required|unique:payroll_ytd,employee_id,{{id}},id,year,{{year}}',
      'label' => 'Employee',
    ], 

    'year' =>    [
      'display' => 'inline',
      'field_type' => 'integer',
      'validation' => 'required|integer|min:1900|max:2100',
      'label' => 'Year',
    ], 

    'gross_earnings' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Gross Earnings',
    ], 

    'taxable_earnings' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Taxable Earnings',
    ], 

    'federal_income_tax' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Federal Income Tax',
    ], 

    'social_security_tax' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Social Security Tax',
    ], 

    'medicare_tax' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Medicare Tax',
    ], 

    'state_income_tax' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'State Income Tax',
    ], 

    'pre_tax_deductions' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Pre Tax Deductions',
    ], 

    'post_tax_deductions' =>    [
      'display' => 'inline',
      'field_type' => 'number',
      'validation' => 'required|numeric|min:0',
      'label' => 'Post Tax Deductions',
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
      'title' => 'Employee and Year',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'employee_id',
        1 => 'year',
      ], 

    ], 

    1 =>    [
      'title' => 'Earnings YTD',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'gross_earnings',
        1 => 'taxable_earnings',
      ], 

    ], 

    2 =>    [
      'title' => 'Taxes YTD',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'federal_income_tax',
        1 => 'social_security_tax',
        2 => 'medicare_tax',
        3 => 'state_income_tax',
      ], 

    ], 

    3 =>    [
      'title' => 'Deductions YTD',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'pre_tax_deductions',
        1 => 'post_tax_deductions',
      ], 

    ], 

  ], 

  'moreActions' =>  [
  ], 

  'report' =>  [
  ], 

];
