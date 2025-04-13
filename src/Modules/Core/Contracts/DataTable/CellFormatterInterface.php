<?php

 namespace App\Modules\Core\Contracts\DataTable;



 interface CellFormatterInterface
{
    public static function format($value, $row);
}
