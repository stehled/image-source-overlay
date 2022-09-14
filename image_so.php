<?php
/**
 * Plugin Name: Image Source Overlay
 * Plugin URI:
 * Text Domain: image_so
 * Description: Adds overlay with image sources.
 * Version: 1.0.0
 * Author: Eduard Stehlík
 * Author URI:
 * License: GPLv3
 */

define( 'IMAGE_SO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'IMAGE_SO__BASE', plugin_basename( __FILE__ ) );

register_activation_hook( __FILE__, array('Image_SO\Inc\Core\Image_SO_Activator', 'activate'));

require_once(IMAGE_SO__PLUGIN_DIR . 'inc/core/class.image_so_activator.php');
require_once(IMAGE_SO__PLUGIN_DIR . 'inc/core/class.image_so_base.php');
require_once(IMAGE_SO__PLUGIN_DIR . 'inc/core/class.image_so_init.php');

if (!class_exists('Image_SO_Init', false)) {
    new \Image_SO\Inc\Core\Image_SO_Init();
}

if (is_admin() && !class_exists('Image_SO_Admin', false)) {
    require_once(IMAGE_SO__PLUGIN_DIR . 'inc/admin/class.image_so_admin.php');
    new \Image_SO\Inc\Admin\Image_SO_Admin();
}

