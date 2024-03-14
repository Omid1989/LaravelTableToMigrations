<?php


namespace LaravelTableToMigrations\Services;


use LaravelTableToMigrations\Contracts\ServiceContract;
use LaravelTableToMigrations\Helper\InfoTables;

class WriteService implements ServiceContract
{

    public static function render(InfoTables $infoTables, ...$opt)
    {
        $filename = date('Y_m_d_His') . "_create_" . $opt[0]->table_name . "_table.php";
        $path = app()->databasePath().'/migrations/';
        file_put_contents($path.$filename, $infoTables::$stub);

    }
}
