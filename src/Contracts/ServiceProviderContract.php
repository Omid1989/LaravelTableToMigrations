<?php


namespace LaravelTableToMigrations\Contracts;


use LaravelTableToMigrations\Kernel;

interface ServiceProviderContract
{
   public function register(kernel $kernel);
}
