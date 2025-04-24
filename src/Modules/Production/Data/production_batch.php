<?php

return [
    'model' => App\Modules\Production\Models\ProductionBatch::class,
    'fieldDefinitions' => [


        'batch_number' => [
            'field_type' => 'text',
            'label' => 'Batch Number',
        ],



        'production_order_request_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionOrderRequest',
                'column' => 'order_number',
                'filters' => [['is_approved' => false]],
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionOrderRequest',
                'type' => 'belongsTo',
                'display_field' => 'order_number',
                'dynamic_property' => 'productionOrder',
                'foreign_key' => 'production_order_request_id',
            ],
            'label' => 'Production Order',
            'display' => 'block',
            'multiSelect' => false,
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
            'formatter' => 'App\Modules\Core\Formatters\StatusFormatter',

        ],



        'inputs' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'type' => 'belongsToMany',
                'display_field' => 'name',
                'dynamic_property' => 'inputItems',
                'foreign_key' => 'item_id',
                'inlineAdd' => false,
            ],
            'display' => 'inline',
            'label' => 'Input Items (Resources)',
            'multiSelect' => true,
        ],



        'outputs' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'type' => 'belongsToMany',
                'display_field' => 'name',
                'dynamic_property' => 'outputItems',
                'foreign_key' => 'item_id',
                'inlineAdd' => false,
            ],
            'display' => 'inline',
            'label' => 'Output Items (Products)',
            'multiSelect' => true,
        ],


        'notes' => [
            'field_type' => 'textarea',
            'validation' => 'nullable|string',
        ],




    ],


    "hiddenFields" => [
        'onTable' => [],
        'onDetail' => [],
        'onEditForm' => [
            'batch_number',
            'inputs',
            'outputs',
        ],
        'onNewForm' => [
            'batch_number',
            'status_id',
            'inputs',
            'outputs',

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
