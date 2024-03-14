<?php


namespace LaravelTableToMigrations\Providers;


use LaravelTableToMigrations\Kernel;
use LaravelTableToMigrations\Services\WriteService;

class WriteServiceProvider implements \LaravelTableToMigrations\Contracts\ServiceProviderContract
{

    public function register(Kernel $kernel)
    {
        $kernel->bind('Write',function(...$opt){
            return WriteService::render($this->infoTables,...$opt);
        });
    }
}
