<?php



namespace App\Modules\Core\Facades\DataTables;

use Illuminate\Support\Facades\Facade;





class DataTableConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'DataTableConfigService';
    }
}

