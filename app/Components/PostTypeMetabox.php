<?php

namespace Carrion\Components;

use Carrion\Components\Component;
use Carrion\Components\PostType;

/**
 * Base Metabox class. Most of the class properties match the arguments from the native WordPress
 * add_meta_box function.
 *
 * Usage: Simply create another class and extend this class. Implement the save($post_id) and render($post) functions
 * as you wish, although calling their parent implementation will take care of a bunch of stuff for you. These are 
 * creating a nonce in the view, checking if the nonce is valid before saving, and autosave checking functions 
 * when the post is saved
 */
abstract class PostTypeMetabox extends Component
{

    public function __construct(string $name, string $title, $screen, string $context = 'advanced', string $priority = 'default', array $callbackArgs = [])
    {
        parent::__construct($name);
        $this->title = $title;
        $this->screen = self::stringifyPostType($screen);
        $this->context = $context;
        $this->priority = $priority;
        $this->callbackArgs = $callbackArgs;

        add_action("save_post", [$this, 'save']);
    }

    /**
     * Metabox title. Displayed in the metabox card heading
     */
    private $title;

    /**
     * The current post type to display this metabox in 
     */
    private $screen;

    /**
     * Initial metabox location
     */
    private $context;

    /**
     * Priority of execution for this hook
     */
    private $priority;

    /**
     * Additionall options for the callback 
     */
    private $callbackArgs;

    // Getters and setters

    public function setName(string $name): void 
    {
        parent::setName($name);
        add_action("save_post_{$this->getName()}", [$this, 'save']);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    public function getScreen(): string
    {
        return $this->screen;
    }

    public function setScreen($screen): void
    {
        $this->screen = $screen;
    }

    public function getContext(): string
    {
        return $this->context;
    }


    public function setContext(string $context): void
    {
        $this->context = $context;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function getCallbackArgs(): array
    {
        return $this->callbackArgs;
    }

    public function setCallbackArgs(array $callbackArgs): void
    {
        $this->callbackArgs = $callbackArgs;
    }

    public function load(): void
    {
        add_action('add_meta_boxes', [$this, 'loadCallback']);
    }

    public function loadCallback(): void
    {
        add_meta_box(
            $this->getName(),
            $this->getTitle(),
            [$this, 'render'],
            $this->getScreen(),
            $this->getContext(),
            $this->getPriority(),
            $this->getCallbackArgs()
        );
    }

    public function unload(): void
    {
        add_action('do_meta_boxes', [$this, 'unloadCallback']);
    }

    public function unloadCallback(): void
    {
        remove_meta_box($this->getName(), $this->getScreen()->getName(), $this->getContext());
    }

    public function render($post)
    {
        wp_nonce_field(basename(__FILE__), "{$this->getName()}-nonce");
    }

    public function save($post_id)
    {
        if (!empty($_POST['post-type'])) 
        {
            if ($this->getScreen()->getName() != $_POST['post-type']) 
            {
                return;
            }
        }

        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST[$this->getName() . '-nonce']) && (wp_verify_nonce($_POST[$this->getName() . '-nonce'], basename(__FILE__)))) ? true : false;

        if ($is_autosave || $is_revision || $is_valid_nonce) 
        {
            return;
        }
    }

    public function sanitizeFields(array $field_list)
    {
        //TODO: Implementation of PostTypeMetabox::sanitizeFields()
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
            throw new \Error('Invalid post type exception', 'The post type assigned to the taxonomy was invalid');
        }
        return $post_type_as_string;
    }
}
