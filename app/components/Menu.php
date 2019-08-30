<?php 
namespace App\Components;

use App\Components\Component;

abstract class Menu extends Component
{
    public function __construct(string $page_title, string $menu_title, $callback = null, string $slug = '', string $capability = 'manage_options')
    {
        $this->pageTitle = $page_title;
        $this->menuTitle = $menu_title;
        $this->capability = $capability;
        $this->callback = $callback ?: function() {};
        $this->slug = $slug ?: static::generateSlug($menu_title);
        $this->load();
    }

    private $pageTitle;
    private $menuTitle;
    private $capability;
    private $callback;
    private $slug;

    public function getPageTitle(): string 
    {
        return $this->pageTitle;
    }

    public function setPageTitle(string $pageTitle): void
    {
        $this->pageTitle = $pageTitle;
    }

    public function getMenuTitle(): string
    {
        return $this->menuTitle;
    } 

    public function setMenuTitle(string $menuTitle) : void
    {
        $this->menuTitle = $menuTitle;
    }

    public function getCapability(): string
    {
        return $this->capability;
    }

    public function setCapability(string $capability): void 
    {
        $this->capability = $capability;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback): void
    {
        $this->callback = $callback;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void 
    {
        $this->slug = $slug;
    }

    private static function generateSlug(string $input)
    {
        return implode("-", explode(" ", strtolower($input)));
    }

    public function load(): void
    {
        add_action('admin_menu', [$this, 'loadCallback']);
    }

    public function unload(): void
    {
        add_action('admin_menu', [$this, 'unloadCallback']);
    }
}
