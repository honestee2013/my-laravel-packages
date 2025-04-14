<?php



namespace QuickerFaster\CodeGen\Facades\DataTables;

use Illuminate\Support\Facades\Facade;





class DataTableOption extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'DataTableOptionService';
    }
}

