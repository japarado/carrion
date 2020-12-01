<?php 
namespace Carrion\Components;

abstract class Component 
{
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->load();
    }

    private $name;
    public static $postTypeCache = [];
    public static $taxonomyCache = [];

    public function getName(): string 
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    protected static function postTypeExists(string $name)
    {
        return in_array($name, static::$postTypeCache);
    }

    protected static function taxonomyExists(string $taxonomy)
    {
        return in_array($taxonomy, static::$taxonomyCache);
    }


    public abstract function load(): void;
    public abstract function loadCallback(): void;
    public abstract function unload(): void;
    public abstract function unloadCallback(): void;
}
