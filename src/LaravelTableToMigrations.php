<?php


namespace LaravelTableToMigrations;


use LaravelTableToMigrations\Helper\InfoTables;

class LaravelTableToMigrations
{
    protected $infoTables;
    protected $dbname;
    protected $kernel;
    protected static $instance = null;


    public function __construct()
    {
        $this->infoTables = new InfoTables();
        $this->kernel = (new Kernel())->bootstrap();
      }

    public function __call($name, $arguments)
    {
        if (method_exists($this->infoTables, $name)) {
            $result = $this->infoTables->$name(...$arguments);
        } else {
            $result = $this->kernel->getService($name)->call($this, ...$arguments);
        }
        return $result;
    }


    public static function __callStatic($name, $arguments)
    {
        $instance = new self();
        return $instance->$name(...$arguments);
    }

    public function __destruct()
    {
        $this->destruct();
    }


    public function destruct()
    {
        unset($this->InfoTables);
        unset($this->kernel);
    }


    public function bind(string $name, Closure $provide)
    {
        $this->kernel->bind($name, $provide);
        return $this;
    }

    public static function getInstance()
    {
        self::$instance || self::$instance = new self();
        return self::$instance;
    }
}
