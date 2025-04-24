<?php

return [
    'model' => App\Modules\Production\Models\ProductionOrderItem::class,
    'newModalTitle' => 'Add Production Order Item',



    'fieldDefinitions' => [
        'production_order_request_id' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionOrderRequest',
                'column' => 'order_number',
            ],
            'relationship' => [
                'model' => 'App\Modules\Production\Models\ProductionOrderRequest',
                'type' => 'belongsTo',
                'display_field' => 'order_number',
                'dynamic_property' => 'productionOrder',
                'foreign_key' => 'production_order_request_id',
            ],
            'label' => 'Order Number',
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
            'label' => 'Requested Product',
            'display' => 'block',
            'multiSelect' => false,
            'validation' => 'required|int'
        ],




        'quantity' => [
            'field_type' => 'number',
            'validation' => 'required|decimal:2'
        ],



        'note' => [
            'field_type' => 'textarea',
            'validation' => 'nullable|string'
        ],


    ],



    "hiddenFields" => [
        'onTable' => [],
        'onDetail' => [],
        'onEditForm' => [
            'order_number',

        ],
        'onNewForm' => [
            'order_number',
            'status_id',
        ],
        'onQuery' => [],
    ],

    "simpleActions" => ['show', 'edit', 'delete'],


    "controls" => "all"



];
