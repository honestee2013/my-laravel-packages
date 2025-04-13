<?php

return [

        "model"=>"App\\Modules\\Core\\Models\\Status",
        "fieldDefinitions"=>[
            'display_name' => ['field_type' => 'text', 'validation' => 'required|string'],
            'name' => ['field_type' => 'text', 'validation' => 'required|string'],
            'description' => ['field_type' => 'textarea'],

        ],

        "hiddenFields"=>[
            'onTable' => [ "name"],
        ],


        "simpleActions"=>['show', 'edit', 'delete'],

        "controls"=>"all",


];
