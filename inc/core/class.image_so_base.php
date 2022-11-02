<?php

namespace Image_SO\Inc\Core;

/**
 * Class Image_SO_Base
 * @package Image_SO\Inc\Core
 * @brief Parent holding important runtime data for easy access.
 */
abstract class Image_SO_Base
{
    protected $plugin_name = 'image-source-overlay';

    protected $source_text = '';

    protected $position = 'top-left';

    protected $only_post = '0';

    protected $nofollow = 'nofollow';

    protected $bool_select = array('0', '1');

    protected $position_select = array('top-left', 'top-right', 'bottom-left', 'bottom-right');

    protected $nofollow_select = array('dofollow', 'nofollow');

    public function __construct() {
        $this->source_text = get_option('image_so_source_text');
        $this->position = get_option('image_so_position');
        $this->only_post = get_option('image_so_only_post');
        $this->nofollow = get_option('image_so_nofollow');
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

    /**
     * @brief Check if value is in specified options.
     * @param $value
     * @param $options
     */
    protected function check_select($value, $options) {
        return in_array($value, $options, false);
    }
}