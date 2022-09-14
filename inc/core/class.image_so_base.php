<?php
/*
Projekt: image_so
Soubor: class.image_so_base.php
Uživatel: eda
Datum: 13.09.2022
*/
namespace Image_SO\Inc\Core;

/**
 * Class Image_SO_Base
 * @package Image_SO\Inc\Core
 * @brief Parent holding important runtime data for easy access.
 */
abstract class Image_SO_Base
{
    protected $plugin_name = 'image_so';

    protected $source_text = '';

    protected $position = 'top-left';

    protected $only_post = '0';

    public function __construct() {
        $this->source_text = get_option('image_so_source_text');
        $this->position = get_option('image_so_position');
        $this->only_post = get_option('image_so_only_post');
    }

    /**
     * @brief Redirects and sets data for admin notice.
     * @param string $admin_notice Type of message
     * @param string $page Page to redirect to
     * @param string $response Message to be rendered in admin notice.
     */
    public function custom_redirect($admin_notice, $page, $response) {
        wp_redirect(esc_url_raw(add_query_arg(array(
            'image_so_admin_notice' => $admin_notice,
            'image_so_response' => $response,
        ), admin_url('admin.php?page='. $page ))));

    }

    /**
     * @brief Sets internal option and updates it in wordpress.
     * @param string $key
     * @param string $value
     */
    protected function update_option_value($key, $value) {
        update_option('image_so_' . $key, $value);
        $this->$key = $value;
    }
}