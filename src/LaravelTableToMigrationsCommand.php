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
    protected $signature = 'make:migrations {database} {--ignore=} {--m} {--c} {--r} ';

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

//        $ignoreInput = str_replace(' ', '', $this->option('ignore'));
//        $ignoreInput = explode(',', $ignoreInput);
//
//        /*
//        $migrate = new SqlMigrations;
//        $migrate->ignore($ignoreInput);
//        $migrate->convert($this->argument('database'));
//        $migrate->write();
//        */
//
//        $this->info('Proccess Successfully');
//        print_r('just for test');
        LaravelTableToMigrations::print('from class migration');

    }
}
