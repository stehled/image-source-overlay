<?php

namespace Image_SO\Inc\Core;

/**
 * Class Image_SO_Activator
 * @package Image_SO\Inc\Core
 * @brief Handles plugin activation.
 */
class Image_SO_Activator
{
    private static $minPhpVersion = '5.6.0';

    /**
     * @brief Check PHP version and adds options.
     */
    public static function activate() {
        if (version_compare( PHP_VERSION, self::$minPhpVersion, '<' )) {
            deactivate_plugins( plugin_basename( __FILE__ ));
            wp_die('This plugin requires a minmum PHP Version of ' . self::$minPhpVersion);
        }
        add_option('image_so_position', 'top-left');
        add_option('image_so_source_text', '');
        add_option('image_so_only_post', '0');
        add_option('image_so_nofollow', 'nofollow');
        add_option('image_so_version_number', IMAGE_SO__VERSION_NUMBER);
    }
}