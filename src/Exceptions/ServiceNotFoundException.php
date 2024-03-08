 <?php


namespace LaravelTableToMigrations\Exceptions;


use Exception;

class ServiceNotFoundException  extends Exception
{
     public function ServiceNotFoundException($message)
     {
         throw new Exception($message);
     }
}
