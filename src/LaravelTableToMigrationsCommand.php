<?php


namespace LaravelTableToMigrations;

use Illuminate\Console\Command;

class LaravelTableToMigrationsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'make:migrations {database} {--ignore=}   ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts an existing MySQL database to laravel file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $ignoreInput = str_replace(' ', '', $this->option('ignore'));
        $ignoreInput = explode(',', $ignoreInput);
        $database = $this->argument('database');

         LaravelTableToMigrations::getInstance()->setDatabase($database);
        LaravelTableToMigrations::getInstance()->setOptions($options);
        $tables = LaravelTableToMigrations::getInstance()->getTables();
        $progressBar = $this->output->createProgressBar(
                  LaravelTableToMigrations::getInstance()->getTablesCount());


        $progressBar->start();
        foreach ($tables as $table) {

            if (!in_array($table->table_name, $ignoreInput)) {

                LaravelTableToMigrations::getInstance()->MakeSchema($table);
                LaravelTableToMigrations::getInstance()->CompileSchema();
                LaravelTableToMigrations::getInstance()->Write($table);

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->info('Proccess Successfully');


    }
}
