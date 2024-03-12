<?php


namespace LaravelTableToMigrations\Contracts;


use LaravelTableToMigrations\Helper\InfoTables;

interface ServiceContract
{
    public static function run(InfoTables $infoTables, ...$opt);
}
