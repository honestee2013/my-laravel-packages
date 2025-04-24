<?php

return [
    'model' => App\Modules\Production\Models\ProductionProcessDowntime::class,
    'newModalTitle' => 'Add Production Process Downtime',

    'fieldDefinitions' => [
        'production_process_log_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionProcessLog',
                'column' => 'display_name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionProcessLog',
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


        'start_time' => [
            'field_type' => 'datetime',
            'validation' => 'required|string',

        ],

        'end_time' => [
            'field_type' => 'datetime',
            'validation' => 'required|string',
        ],

        'duration' => [
            'field_type' => 'text',
            'label' => 'Duration (Min)',
        ],


        'reason' => [
            'field_type' => 'text',
            'validation' => 'required|string',
        ],


        'notes' => [
            'field_type' => 'textarea',
        ],











    ],



    "hiddenFields" => [
        'onTable' => [],
        'onDetail' => [],
        'onEditForm' => [
            'duration',
        ],
        'onNewForm' => [
            'batch_number',
            'status_id',
            'duration',

        ],
        'onQuery' => [],
    ],

    "simpleActions" => ['show', 'edit', 'delete'],


    "controls" => "all"



];
