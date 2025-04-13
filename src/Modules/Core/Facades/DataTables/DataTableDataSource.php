<?php 

namespace App\Modules\Core\Facades\DataTables;

use Illuminate\Support\Facades\Facade;



class DataTableDataSource extends Facade {

    protected static function getFacadeAccessor() {
        return "DataTableDataSourceService";
    }

}

