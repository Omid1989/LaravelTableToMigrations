<?php


namespace LaravelTableToMigrations\Providers;


use LaravelTableToMigrations\Contracts\ServiceProviderContract;
use LaravelTableToMigrations\Kernel;
use LaravelTableToMigrations\Services\MigrationsService;

class MigrationsServiceProvider implements ServiceProviderContract
{
    public function register(Kernel $kernel)
    {
        $kernel->bind('print',function (string $outputmessage){

            return MigrationsService::printLaravel($this,$outputmessage);
        });
    }
}
