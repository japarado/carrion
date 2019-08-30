<?php 
namespace App\Components;

use App\Components\Component;
use App\Traits\HasArgsAccessors;


final class PostType extends Component
{
    public function __construct(string $name, array $args = [])
    {
        parent::__construct($name);
        $this->args = $args;
    }

    use HasArgsAccessors;

    private $args;

    public function load(): void
    {
        if(!static::postTypeExists($this->getName()))
        {
            add_action('init', [$this, 'loadCallback']);
            static::$postTypeCache[$this->getName()] = $this->getName();
            $this->addToPostTypeCache();
        }
    }

    public function loadCallback(): void
    {
        $this->hasLabel() ?: $this->assignDefaultLabel();
        register_post_type($this->getName(), $this->getArgs());
    }

    public function unload(): void
    {
        add_action('init', [$this, 'unloadCallback']);
    }

    public function unloadCallback(): void
    {
        unregister_post_type($this->getName());
        static::$postTypeCache = array_map(function($post_type) {
            return $post_type != $this->getName();
        }, static::$postTypeCache);
    }

    private function assignDefaultLabel()
    {
        $args = $this->getArgs();
        $args['label'] = ucfirst($this->getName());
        $this->setArgs($args);
    }

    private function hasLabel()
    {
        return array_key_exists('label', $this->getArgs());
    }

    private function addToPostTypeCache()
    {
        static::$postTypeCache[$this->getName()] = $this->getName();
    }

}