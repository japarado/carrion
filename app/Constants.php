<?php 
namespace App;

use App\Constructs\Enum;

final class Constants extends Enum 
{
    const PLUGINFILE_PATH = WP_PLUGIN_DIR . '/hexer/hexer.php';
    const UPGRADEFILE_PATH = ABSPATH . 'wp-admin/includes/upgrade.php';
}
