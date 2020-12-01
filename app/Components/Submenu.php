<?php
namespace Carrion\Components;

use Carrion\Components\Menu;
use Carrion\Components\TopMenu;

final class Submenu extends Menu
{
    public function __construct(string $page_title, string $menu_title, TopMenu $top_menu, $callback = null, string $slug = '', string $capability = 'manage_options')
    {
        parent::__construct($page_title, $menu_title, $callback, $slug, $capability);
        $this->topMenu = $top_menu;
    }

    /*
     * Getters and setters 
     */

    public function getTopMenu(): TopMenu 
    {
        return $this->topMenu;
    }

    public function setTopMenu(Topmenu $topMenu): void 
    {
        $this->topMenu = $topMenu;
    }

    public function loadCallback(): void
    {
        add_submenu_page($this->getTopMenu()->getSlug(), $this->getPageTitle(), $this->getMenuTitle(), $this->getCapability(),
            $this->getSlug(), $this->getCallback());
    }

    public function unloadCallback(): void
    {
        remove_submenu_page($this->getTopMenu()->getSlug(), $this->getSlug());
    }
}
