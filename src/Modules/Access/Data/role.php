<?php

return [

        "model"=>"App\\Modules\\Access\\Models\\Role",
        "fieldDefinitions"=>[
                'name' => [
                    'field_type' => 'text',
                    'validation' => 'required|string|max:255',
                ],
                'description' =>'textarea',
                'editable' => [
                    'field_type' => 'boolean',
                    'label' => 'Editable',
                ],
        ],



        "hiddenFields"=>[
            'onTable' => [
            ],
            'onNewForm' => [
                'editable',

            ],
            'onEditForm' => [
                'editable',
            ],

            'onQuery' => [

            ],
        ],


        "simpleActions"=>['show', 'edit', 'delete'],


        "controls" => [
            'addButton',
            'files' => [
                'export' => ['xls', 'csv', 'pdf'],
                'import' => ['xls', 'csv', ],
                'print',
            ],
            'perPage' => [5, 10, 25, 50, 100, 200, 500],

            'bulkActions' => [
                'export' => ['xls', 'csv', 'pdf', 'delete'],
            ],
            'search',
            'showHideColumns',
        ],

];
