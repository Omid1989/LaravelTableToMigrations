<?php


namespace LaravelTableToMigrations\Providers;


use LaravelTableToMigrations\Kernel;
use LaravelTableToMigrations\Services\CompileSchema;

class CompileSchemaProvider implements \LaravelTableToMigrations\Contracts\ServiceProviderContract
{

    public function register(Kernel $kernel)
    {
        $kernel->bind('CompileSchema',function(...$opt){
            return CompileSchema::render($this->infoTables,...$opt);
        });
    }
}
