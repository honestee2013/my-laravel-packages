<?php

return [
  'model' => 'App\\Modules\\User\\Models\\BasicInfo',
  'fieldDefinitions' =>  [
    
    'profile_picture' =>    [
      'display' => 'inline',
      'field_type' => 'file',
      'validation' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'label' => 'Profile Picture',
    ],

    'user_id' =>    [
      'relationship' =>      [
        'model' => 'App\\Models\\User',
        'type' => 'belongsTo',
        'display_field' => 'name',
        'dynamic_property' => 'user',
        'foreign_key' => 'user_id',
        'inlineAdd' => false,
      ], 

      'options' =>      [
        'model' => 'App\\Models\\User',
        'column' => 'name',
      ], 

      'display' => 'inline',
      'field_type' => 'select',
      'validation' => 'required|unique:basic_infos,user_id',
      'label' => 'User',
    ], 


    'phone_number' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Phone Number',
    ], 

    'email' =>    [
      'display' => 'inline',
      'field_type' => 'email',
      'label' => 'Email',
    ], 

    'address_line_1' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Address Line 1',
    ], 

    'address_line_2' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Address Line 2',
    ], 

    'city' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'City',
    ], 

    'state' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'State',
    ], 

    'postal_code' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Postal Code',
    ], 

    'country' =>    [
      'display' => 'inline',
      'field_type' => 'string',
      'label' => 'Country',
    ], 

    'date_of_birth' =>    [
      'display' => 'inline',
      'field_type' => 'datepicker',
      'label' => 'Date Of Birth',
    ], 

    'gender' =>    [
      'display' => 'inline',
      'field_type' => 'select',
      'options' =>      [
        'Male' => 'Male',
        ' Female' => ' Female',
        ' Other' => ' Other',
      ], 

      'label' => 'Gender',
    ], 


    'about_me' =>    [
      'display' => 'inline',
      'field_type' => 'textarea',
      'validation' => 'nullable|string|max:1000',
      'label' => 'About Me',
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
      'title' => 'Personal Details',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'user_id',
        1 => 'profile_picture',
        2 => 'date_of_birth',
        3 => 'gender',
        4 => 'about_me',

      ], 

    ], 

    1 =>    [
      'title' => 'Contact Information',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'phone_number',
        1 => 'email',
      ], 

    ], 

    2 =>    [
      'title' => 'Address',
      'groupType' => 'hr',
      'fields' =>      [
        0 => 'address_line_1',
        1 => 'address_line_2',
        2 => 'city',
        3 => 'state',
        4 => 'postal_code',
        5 => 'country',
      ], 

    ], 

  ], 

];
