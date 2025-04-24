<?php

return [
    'model' => App\Modules\Production\Models\ProductionProcessInput::class,
    'newModalTitle' => 'Add Production Process Input',

    'fieldDefinitions' => [
        'production_process_log_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionProcessLog',
                'column' => 'display_name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionItemionProcessLog',
                'type' => 'belongsTo',
                'display_field' => 'display_name',
                'dynamic_property' => 'productionProcessLog',
                'foreign_key' => 'production_process_log_id',
            ],
            'label' => 'Production Log',
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
