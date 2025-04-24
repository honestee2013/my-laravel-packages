<?php

return [
    'model' => App\Modules\Production\Models\ProductionProcess::class,

    'fieldDefinitions' => [
        'name' => [ 'field_type' => 'text', 'validation' => 'required|string'],
        'description' =>'textarea',
    ],

    "hiddenFields" => [
        'onTable' => [],
        'onDetail' => [],
        'onEditForm' => [],
        'onNewForm' => [
        ],
        'onQuery' => [],
    ],

    "simpleActions" => ['show', 'edit', 'delete'],
    "controls" => "all"

];
