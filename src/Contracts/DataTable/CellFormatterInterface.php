<?php

 namespace QuickerFaster\CodeGen\Contracts\DataTable;



 interface CellFormatterInterface
{
    public static function format($value, $row);
}
