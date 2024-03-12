<?php


namespace LaravelTableToMigrations\Providers;


use LaravelTableToMigrations\Kernel;
use LaravelTableToMigrations\Services\WriteService;

class WriteServiceProvider implements \LaravelTableToMigrations\Contracts\ServiceProviderContract
{

    public function register(Kernel $kernel)
    {
        $kernel->bind('write',function(...$opt){
            return WriteService::run($this->infoTables,...$opt);
        });
    }
}
