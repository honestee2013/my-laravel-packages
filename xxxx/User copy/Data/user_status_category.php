<?php

return [

        "model"=>"App\\Modules\\User\\Models\\UserStatusCategory",
        "fieldDefinitions"=>[
            'name' => ['field_type' => 'text', 'validation' => 'nullable|string'],
            'display_name' => ['field_type' => 'text', 'validation' => 'required|string'],

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
