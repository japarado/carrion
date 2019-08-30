<?php 
namespace App\Components;

use App\Components\Menu;

final class Topmenu extends Menu 
{
    public function __construct(string $page_title, string $menu_title, $callback = null, string $slug = '', string $capability = 'manage_options',
        string $menuIcon = '', int $position = 99)
    {
        parent::__construct($page_title, $menu_title, $callback, $slug, $capability);

        $this->menuIcon = $menuIcon;
        $this->position = $position;
    }

    private $menuIcon;
    private $position;

    public function getMenuIcon(): string 
    {
        return $this->menuIcon;
    }

    public function setMenuIcon(string $menuIcon): void 
    {
        $this->menuIcon = $menuIcon;
    }

    public function getPosition(): int 
    {
        return $this->position;
    }

    public function setPosition(string $position): void 
    {
        $this->position = $position;
    }

    public function loadCallback(): void 
    {
        add_menu_page($this->getPageTitle(), $this->getMenuTitle(), $this->getCapability(), $this->getSlug(), 
            $this->getCallback(), $this->getMenuIcon(), $this->getPosition());
    }

    public function unloadCallback(): void
    {
        remove_menu_page($this->getSlug());
    }
}

