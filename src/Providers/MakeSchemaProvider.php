<?php


namespace LaravelTableToMigrations\Providers;


use LaravelTableToMigrations\Kernel;
use LaravelTableToMigrations\Services\MakeSchema;

class MakeSchemaProvider implements \LaravelTableToMigrations\Contracts\ServiceProviderContract
{

    public function register(Kernel $kernel)
    {
        $kernel->bind('MakeSchema',function(...$opt){
            return MakeSchema::render($this->infoTables,...$opt);
        });
    }
}
