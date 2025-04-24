<?php

return [
    'model' => App\Modules\Production\Models\ProductionOrderApproval::class,
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
                'model' => 'App\Modules\Item\Models\Item',
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


        'resources' => [
            'field_type' => 'select',
            'options' => [
                'model' => 'App\Modules\Production\Models\ProductionItem',
                'column' => 'name',
            ],
            'relationship' => [
                'model' => 'App\Modules\Item\Models\Item',
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




        'due_date' => [
            'field_type' => 'datetime',
            'validation' => 'nullable|string',
        ],





        'status_id' => [
            'field_type' => 'radio',
            'options' => app('App\Modules\Core\Models\Status')
            ->where("name", "pending")
            ->orWhere("name", "approved")
            ->orWhere("name", "rejected")
            ->pluck('display_name', 'id'),

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
            'validation' => 'required',
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



        'remarks' => [
            'field_type' => 'textarea',
            'validation' => 'nullable|string'
        ],





    ],



    "hiddenFields" => [
        'onTable' => [
            'resources',
            'remarks',
        ],
        'onDetail' => [
            'resources',
        ],
        'onEditForm' => [
            'order_number',
            'items',
            'resources',
            'due_date',
            'quantity',

        ],
        'onNewForm' => [
            'order_number',
            'items',
            'resources',
            'due_date',
            'quantity',
            'status_id',
        ],
        'onQuery' => [

        ],
    ],

    "simpleActions" => ['show', 'edit'],

    "moreActions" => [
        'approve' => [
            'icon' => 'fas fa-check text-sm me-1 text-success',
            'updateModelField' => true,
            'fieldName' => "status_id",
            'fieldValue' => app('App\Modules\Core\Models\Status')->where("name", "approved")->first()?->id,
        ],
        'reject' => [
            'icon' => 'fas fa-times text-sm me-1 text-danger',
            'updateModelField' => true,
            'fieldName' => "status_id",
            'fieldValue' => app('App\Modules\Core\Models\Status')->where("name", "rejected")->first()?->id,
        ],

    ],


    "controls" => [

        'files' => [
            'export' => ['xls', 'csv', 'pdf'],
            'print',
        ],
        'perPage' => [5, 10, 25, 50, 100, 200, 500],

        'bulkActions' => [
            'export' => ['xls', 'csv', 'pdf'],

            'updateModelFields' => [
                'approve' => [
                    'fieldName' => "status_id",
                    'fieldValue' => app('App\Modules\Core\Models\Status')->where("name", "approved")->first()?->id,
                ],
                'reject' => [
                    'fieldName' => "status_id",
                    'fieldValue' => app('App\Modules\Core\Models\Status')->where("name", "rejected")->first()?->id,
                ]
            ],

        ],
        'search',
        'showHideColumns',
    ],


];
