<?php

return [
    'model' => App\Modules\Production\Models\ProductionOrderRequest::class,
    'fieldDefinitions' => [

        'order_number' => [
            'field_type' => 'text',
            'label' => 'Order Number',
            'validation' => 'nullable|unique:production_orders,order_number',
        ],


        'items' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'type' => 'belongsToMany',
                'display_field' => 'name',
                'dynamic_property' => 'requestedItems',
                'foreign_key' => 'item_id',
                'inlineAdd' => false,
            ],
            'display' => 'inline',
            'label' => 'Requested Products',
            'multiSelect' => true,
        ],



        'due_date' => [
            'field_type' => 'datetime',
            'validation' => 'required|string',
        ],


        'supervisor_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Models\User',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Models\User',
                'type' => 'belongsTo',
                'display_field' => 'name',
                'dynamic_property' => 'supervisor',
                'foreign_key' => 'supervisor_id',
                'inlineAdd' => false,
            ],
            'label' => 'Supervisor',
            'validation' => 'required|int'
        ],



        'status_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Core\Models\Status',
                'column' => 'display_name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Core\Models\Status',
                'type' => 'belongsTo',
                'display_field' => 'display_name',
                'dynamic_property' => 'status',
                'foreign_key' => 'status_id',
            ],
            'label' => 'Status',
            'display' => 'block',
            'multiSelect' => false,
            'validation' => 'required|int',
            'formatter' => 'App\Modules\Core\Formatters\StatusFormatter',

        ],



        'resources' => [
            'field_type' => 'select',
            'optiions' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'type' => 'belongsToMany',
                'display_field' => 'name',
                'dynamic_property' => 'allocatedResources',
                'foreign_key' => 'item_id',
                'inlineAdd' => false,
            ],
            'display' => 'inline',
            'label' => 'Allocated Resources',
            'multiSelect' => true,
        ],



        'remarks' => [
            'field_type' => 'textarea',
            'validation' => 'nullable|string'
        ],





    ],



    "hiddenFields" => [
        'onTable' => [
            'supervisor_id',
            'resources',
        ],
        'onDetail' => [
            'resources',
        ],
        'onEditForm' => [
            'order_number',
            'items',
            'item_id',
            'resources',

        ],
        'onNewForm' => [
            'order_number',
            'status_id',
            'items',
            'resources',
        ],
        'onQuery' => [],
    ],

    "simpleActions" => ['show', 'edit'],


    "controls" => [
        'addButton',

        'files' => [
            'export' => ['xls', 'csv', 'pdf'],
            'import' => ['xls', 'csv'],
            'print',
        ],
        'perPage' => [5, 10, 25, 50, 100, 200, 500],

        'bulkActions' => [
            'export' => ['xls', 'csv', 'pdf'],
        ],
        'search',
        'showHideColumns',
    ],


];
