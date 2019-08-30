<?php 
namespace App\Components;

use App\Components\PostType;
use App\Components\Component;
use App\Traits\HasArgsAccessors;
use Error;

final class Taxonomy extends Component 
{
    public function __construct(string $name, $post_type, array $args = ['public' => true, 'hierarchical' => true])
    {
        parent::__construct($name);
        $this->postType = static::stringifyPostType($post_type);
        $this->args = $args;
    }

    use HasArgsAccessors;

    private $postType;
    private $args;

    public function getPostType(): string
    {
        return $this->postType;
    }

    public function setPostType($post_type): void
    {
        $this->postType = static::stringifyPostType($post_type);
    }

    private static function stringifyPostType($post_type)
    {
        $post_type_class = PostType::class;
        $post_type_as_string = '';
        if($post_type instanceof $post_type_class)
        {
            $post_type_as_string = $post_type->getName();
        }
        else if(is_string($post_type))
        {
            $post_type_as_string = $post_type;
        }
        else 
        {
            throw new Error('Invalid post type exception', 'The post type assigned to the taxonomy was invalid');
        }
        return $post_type_as_string;
    }

    public function load(): void
    {
        add_action('init', [$this, 'loadCallback']);
    }

    public function loadCallback(): void
    {
        register_taxonomy($this->getName(), $this->getPostType(), $this->getArgs());
        register_taxonomy_for_object_type($this->getName(), $this->getPostType());
    }

    public function unload(): void
    {
        add_action('init', [$this, 'unloadCallback']);
    }

    public function unloadCallback(): void
    {
        unregister_taxonomy_for_object_type($this->getName(), $this->getPostType());
    }
}
