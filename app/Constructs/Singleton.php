<?php 
namespace Carrion\Constructs;

class Singleton
{
    protected static $instance = null;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if(static::$instance == null)
        {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
