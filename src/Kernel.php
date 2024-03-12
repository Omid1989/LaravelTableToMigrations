<?php


namespace LaravelTableToMigrations;

use LaravelTableToMigrations\Contracts\ServiceProviderContract;
use LaravelTableToMigrations\Exceptions\ServiceNotFoundException;
use LaravelTableToMigrations\Providers\CompileSchemaProvider;
use LaravelTableToMigrations\Providers\MakeSchemaProvider;
use LaravelTableToMigrations\Providers\WriteServiceProvider;
use Tightenco\Collect\Support\Collection;
use Closure;

class Kernel
{
    protected $providers = [
        WriteServiceProvider::class,
        MakeSchemaProvider::class,
        CompileSchemaProvider::class
    ];

    protected $binds;


    public function __construct()
    {
        $this->binds = new Collection();

    }

    public function bootstrap()
    {
        $this->registerProviders();
        return $this;
    }

    public function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    public function bind(string $name, Closure $provider)
    {
        $this->binds[$name] = $provider;
    }

    public function getService(string $name)
    {
        if (!$this->binds->offsetExists($name)) {
             throw new ServiceNotFoundException("Service: {$name} not found!");

        }
        return $this->binds[$name];
    }

    private function register(ServiceProviderContract $instance)
    {
        $instance->register($this);
    }
}
