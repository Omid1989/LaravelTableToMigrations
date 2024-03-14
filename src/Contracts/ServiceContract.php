<?php


namespace LaravelTableToMigrations\Contracts;


use LaravelTableToMigrations\Helper\InfoTables;

interface ServiceContract
{
    public static function render(InfoTables $infoTables, ...$opt);
}
