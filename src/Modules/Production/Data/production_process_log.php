<?php

return [
    'model' => App\Modules\Production\Models\ProductionProcessLog::class,
    'fieldDefinitions' => [


        'production_batch_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionBatch',
                'column' => 'batch_number',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionItemionBatch',
                'type' => 'belongsTo',
                'display_field' => 'batch_number',
                'dynamic_property' => 'batch',
                'foreign_key' => 'production_batch_id',
            ],
            'label' => 'Batch Number',
            'display' => 'block',
            'multiSelect' => false,
            'validation' => 'required|int'

        ],





        'production_process_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionProcess',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionItemionProcess',
                'type' => 'belongsTo',
                'display_field' => 'name',
                'dynamic_property' => 'productionProcess',
                'foreign_key' => 'production_process_id',
            ],
            'label' => 'Production Process',
            'display' => 'block',
            'multiSelect' => false,
            'validation' => 'required|int',

        ],



        'progress' => [
            'field_type' => 'select',
            'options' => [
                '0' => '0%',
                '10' => '10%',
                '20' => '20%',
                '30' => '30%',
                '40' => '40%',
                '50' => '50%',
                '60' => '60%',
                '70' => '70%',
                '80' => '80%',
                '90' => '90%',
                '100' => '100%',
            ],
            'formatter' => 'App\Modules\Core\Formatters\ProgressFormatter',
            'validation' => 'nullable|int',
            'label' => 'Progress (%)',

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
            'formatter' => 'App\Modules\Core\Formatters\StatusFormatter',
            'display' => 'block',
            'multiSelect' => false,
            'validation' => 'required|int'
        ],



        'inputs' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Item\Models\Item',
                'type' => 'belongsToMany',
                'display_field' => 'name',
                'dynamic_property' => 'inputItems',
                'foreign_key' => 'item_id',
                'inlineAdd' => false,
            ],
            'display' => 'inline',
            'label' => 'Input Items (Resources)',
            'multiSelect' => true,
            'validation' => 'nullable|int'

        ],




        'outputs' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Item\Models\Item',
                'type' => 'belongsToMany',
                'display_field' => 'name',
                'dynamic_property' => 'outputItems',
                'foreign_key' => 'item_id',
                'inlineAdd' => false,
            ],
            'display' => 'inline',
            'label' => 'Output Items (Products)',
            'multiSelect' => true,
            'validation' => 'nullable|int'

        ],







        'start_time' => [
            'field_type' => 'datetime',
            'validation' => 'required|string'
        ],


        'actual_end_time' => [
            'field_type' => 'datetime',
            'label' => 'End time',
            'validation' => 'required|string'
        ],

        'total_downtime' => [
            'field_type' => 'datetime',
            'label' => 'Total Downtime (Min)',
            'validation' => 'nullable|string'
        ],



        'operator_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Models\User',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Models\User',
                'type' => 'belongsTo',
                'display_field' => 'name',
                'dynamic_property' => 'operator',
                'foreign_key' => 'operator_id',
                'inlineAdd' => false,
            ],
            'label' => 'Head Operator',
            'validation' => 'required|int',

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
            'label' => 'Head Supervisor',
            'validation' => 'required|int',

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
            'inputs',
            'outputs',
            'total_downtime',
        ],
        'onNewForm' => [
            'inputs',
            'outputs',
            'total_downtime',
            'progress',
        ],

        'onQuery' => [],
    ],

    "simpleActions" => ['show', 'edit'],

    "dispatchEvents"=>true,

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
