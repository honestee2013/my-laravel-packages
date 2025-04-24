<?php

namespace QuickerFaster\CodeGen\Traits\DataTable;


trait DataTableControlsTrait
{



    public function getPreparedControls($controls) {
        if (!isset($controls))
            return [];
        else if (is_array($controls))
            return $controls; // Do nothing
        else if ($controls == strtolower("all"))
            return $this->getDataTableAllControls();

    }




    public function getDataTableAllControls()
    {
        return [
            'addButton',
            'files' => [
                'export' => ['xls', 'csv', 'pdf'],
                'import' => ['xls', 'csv'],
                'print',
            ],
            'bulkActions' => [
                'export' => ['xls', 'csv', 'pdf'],
                'delete',
            ],
            'perPage' => [5, 10, 25, 50, 100, 200, 500],
            'search',
            'showHideColumns',
            'filterColumns',
        ];
    }


    public function getDataTableFilesControls()
    {
        return [
            'files' => [
                'export' => ['xls', 'csv', 'pdf'],
                'import' => ['xls', 'csv'],
                'print',
            ]
        ];
    }

}


