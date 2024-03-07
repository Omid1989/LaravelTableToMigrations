<?php


namespace LaravelTableToMigrations;


class LaravelTableToMigrations
{
   // protected $query;
    protected $kernel;
    protected static $instance = null;


    public function __construct()
    {
       //$this->query = new Query($this);
        $this->kernel = (new Kernel($this))->bootstrap();

    }

    public function __call($name, $arguments)
    {
        //if (method_exists($this->query, $name)) {
         //   $result = $this->query->$name(...$arguments);
        //} else {
        $result = $this->kernel->getService($name)->call($this, ...$arguments);
        //}
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


    public static function getInstance()
    {
        self::$instance || self::$instance = new self();
        return self::$instance;
    }


    public function destruct()
    {
        //unset($this->query);
        unset($this->kernel);
    }


    public function bind(string $name, Closure $provide)
    {
        $this->kernel->bind($name, $provide);
        return $this;
    }

}
