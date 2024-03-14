<?php


namespace LaravelTableToMigrations\Services;


use LaravelTableToMigrations\Helper\InfoTables;

class CompileSchema implements \LaravelTableToMigrations\Contracts\ServiceContract
{

    public static function render(InfoTables $infoTables, ...$opt)
    {
        $infoTables::$stub = file_get_contents(__DIR__ . '/../Stubs/table.stub');
        $infoTables::$stub = str_replace("[TableUp]", $infoTables::$schema['up'], $infoTables::$stub);
        $infoTables::$stub = str_replace("[TableDown]", $infoTables::$schema['down'], $infoTables::$stub);

    }
}
