<?php

return [
    'model' => App\Modules\Production\Models\ProductionBatchOutput::class,
    'newModalTitle' => 'Add Production Batch Output',

    'fieldDefinitions' => [
        'production_batch_id' => [
            'field_type' => 'select',

            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionBatch',
                'column' => 'batch_number',
            ],


            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionBatch',
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



        'item_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'type' => 'belongsTo',
                'display_field' => 'name',
                'dynamic_property' => 'item',
                'foreign_key' => 'item_id',
            ],
            'label' => 'Allocated Resource',
            'display' => 'block',
            'multiSelect' => false,
            'validation' => 'required|int'

        ],



        'planned_quantity' => [
            'field_type' => 'number',
            'validation' => 'required|decimal:2'
        ],



        'actual_quantity' => [
            'field_type' => 'number',
            'validation' => 'required|decimal:2'
        ],










    ],



    "hiddenFields" => [
        'onTable' => [],
        'onDetail' => [],
        'onEditForm' => [],
        'onNewForm' => [
            'batch_number',
            'status_id',

        ],
        'onQuery' => [],
    ],

    "simpleActions" => ['show', 'edit', 'delete'],


    "controls" => "all"



];
