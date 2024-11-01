<?php
/**
 * Plugin Name: WP Giosg
 * Plugin URI: https://wordpress.org/plugins/wp-giosg/
 * Description: Integrates giosg with wordpress.
 * Version: 1.0.8
 * Requires at least: 3.1.0
 * Requires PHP: 5.6
 * Author: Cyclonecode
 * Author URI: https://stackoverflow.com/users/1047662/cyclonecode?tab=profile
 * Copyright: Cyclonecode
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-giosg
 * Domain Path: /languages
 *
 * @package wp-giosg
 * @author Cyclonecode
 */

namespace WPGiosg;

require_once __DIR__ . '/vendor/autoload.php';

use WPGiosg\Plugin\DI\Container;
use WPGiosg\Plugin\Settings\Settings;

function get_giosg_container()
{
    return $GLOBALS['giosg_container'];
}

$GLOBALS['giosg_container'] = $container = new Container();
$container['settings'] = function (Container $container) {
    return new Settings(Plugin::SETTINGS_NAME);
};
$container['plugin'] = function (Container $container) {
    return new Plugin($container['settings']);
};

add_action(
    'plugins_loaded',
    function () {
        get_giosg_container()['plugin'];
    }
);

register_activation_hook(__FILE__, Plugin::class . '::activate');
register_uninstall_hook(__FILE__, Plugin::class . '::delete');
