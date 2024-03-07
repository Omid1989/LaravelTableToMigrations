<?php


namespace LaravelTableToMigrations;


use Illuminate\Support\ServiceProvider;

class LaravelTableToMigrationsServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->app->singleton('command.make:migrations',
            function () {
                return new LaravelTableToMigrationsCommand();
            }
        );

        $this->commands('command.make:migrations');
    }
}
