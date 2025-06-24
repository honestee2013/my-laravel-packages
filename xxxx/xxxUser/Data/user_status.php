<?php

return [

        "model"=>"App\\Modules\\User\\Models\\UserStatus",
        "fieldDefinitions"=>[
            'name' => ['field_type' => 'text', 'validation' => 'nullable|string'],
            'display_name' => ['field_type' => 'text', 'validation' => 'required|string'],


            'category_id' =>    [
              'relationship' =>      [
                'model' => 'App\\Modules\\User\\Models\\UserStatusCategory',
                'type' => 'belongsTo',
                'display_field' => 'display_name',
                'dynamic_property' => 'category',
                'foreign_key' => 'category_id',
                'inlineAdd' => false,
              ], 
        
              'options' =>      [
                'model' => 'App\\Modules\\User\\Models\\UserStatusCategory',
                'column' => 'display_name',
              ], 
        
              'display' => 'inline',
              'field_type' => 'select',
              'validation' => 'required',
              'label' => 'Category',
            ], 


            'description' => ['field_type' => 'textarea'],

            'editable' => [ 'field_type' => 'text'],
        ],


        "hiddenFields"=>[
          'onTable' => [ "name"],
          'onNewForm' => [ "editable"],
          'onEditForm' => [ "editable"],
        ],
        

        "simpleActions"=>['show', 'edit', 'delete'],

        "controls"=>"all",


];
