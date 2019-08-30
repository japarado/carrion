<?php
namespace App\Plugin;

use App\Constructs\Singleton;
use App\Components\Component;
use App\Constants;

final class Plugin extends Singleton
{
    public function start()
    {
        $this->init();
        $this->registerPluginHooks();
        Component::$postTypeCache = get_post_types();
    }

    private $name;
    private $pluginFile;

    public function getName(): string 
    {
        return $this->name;
    }

    private function setName(string $name): void 
    {
        $this->name = $name;
    }

    public function getPluginFile(): string 
    {
        return $this->pluginFile;
    }

    private function setPluginFile(string $plugin_file): void 
    {
        $this->pluginFile = $plugin_file;
    }

    public function activate()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE test_table (
        id int(11) NOT NULL AUTO_INCREMENT,
        created_at timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
        data longtext NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate";

        require_once Constants::UPGRADEFILE_PATH;
        dbDelta($sql);
    }

    public function deactivate()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE form_submissions");
    }

    public static function uninstall()
    {
    }

    private function init()
    {
        $this->setName(basename(dirname(dirname(__DIR__)), ".php"));
        $this->setPluginFile(dirname(dirname(__DIR__)) . "/" . $this->getName() . ".php");
    }

    private function registerPluginHooks()
    {
        register_activation_hook($this->getPluginFile(), [$this, 'activate']);
        register_deactivation_hook($this->getPluginFile(), [$this, 'deactivate']);
        register_uninstall_hook($this->getPluginFile(), [self::class, 'uninstall']);
    }
}

