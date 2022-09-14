<?php
/*
Projekt: image_so
Soubor: class.image_so_admin.php
Uživatel: eda
Datum: 13.09.2022
*/
namespace Image_SO\Inc\Admin;

use Image_SO\Inc\Core\Image_SO_Base;

/**
 * Class Image_SO_Admin
 * @package Image_SO\Inc\Admin
 * @brief Handles Admin-side requests and modifications.
 */
final class Image_SO_Admin extends Image_SO_Base
{
    public function __construct() {
        parent::__construct();
        add_action('plugin_action_links_' . IMAGE_SO__BASE, array($this, 'add_settings_link'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_notices', array($this, 'render_admin_notices'));
        add_filter('attachment_fields_to_edit', array($this, 'custom_media_fields'), null, 2);
        add_action('edit_attachment', array($this, 'custom_media_save_attachment'));
        add_action('admin_post_image_so_admin_form_response', array($this, 'admin_form_submit'));
    }

    /**
     * @brief Renders specified view.
     * @param string $name View name
     * @param array $args Parameters to be passed to view
     */
    public static function view($name, $args = array()) {
        foreach ( $args AS $key => $val ) {
            $$key = $val;
        }
        $file = IMAGE_SO__PLUGIN_DIR . 'inc/admin/views/'. $name . '.php';
        include($file);
    }

    /**
     * @brief Adds plugin admin page to Wordpress menu.
     */
    public function admin_menu() {
        add_management_page(__('Image SO Settings', $this->plugin_name), __('Image SO Settings', $this->plugin_name), 'manage_options', 'image_so', array($this, 'admin_page'));
    }

    /**
     * @brief Sets admin page to render.
     */
    public function admin_page(){
        if(current_user_can( 'manage_options')) {
            $image_so_admin_form_nonce = wp_create_nonce( 'image_so_admin_form_nonce');
            self::view('admin_page', array('plugin_name' => $this->plugin_name, 'image_so_admin_form_nonce' => $image_so_admin_form_nonce, 'position' => $this->position, 'source_text' => $this->source_text, 'only_post' => $this->only_post));
        }
        else {
            ?>
            <p> <?php __("You are not authorized to perform this operation.", $this->plugin_name) ?> </p>
            <?php
        }
    }

    /**
     * @brief Renders admin notice after redirect.
     */
    public function render_admin_notices() {
        if (isset($_REQUEST['image_so_admin_notice'])) {
            if($_REQUEST['image_so_admin_notice'] === "success") {
                $html =	'<div class="notice notice-success is-dismissible"> 
							<p><strong>' . __('Success', $this->plugin_name) . ' </strong>';
                $html .= htmlspecialchars(print_r($_REQUEST['image_so_response'], true)) . '</p></div>';
                echo $html;
            }
        }
    }

    /**
     * @brief Handles admin form submit.
     */
    public function admin_form_submit() {
        if(isset($_POST['image_so_admin_form_nonce']) && wp_verify_nonce($_POST['image_so_admin_form_nonce'], 'image_so_admin_form_nonce')) {
            $source_text = sanitize_text_field($_POST['image_so-source-text']);
            $position = sanitize_text_field($_POST['image_so-position']);
            $only_post = sanitize_text_field($_POST['image_so-only-post']);
            $this->update_option_value('source_text', $source_text);
            $this->update_option_value('position', $position);
            $this->update_option_value('only_post', $only_post);
            $admin_notice = "success";
            $this->custom_redirect($admin_notice, 'image_so', __('settings were saved.', $this->plugin_name));
            exit;
        }
        else {
            wp_die(__( 'Invalid nonce specified', $this->plugin_name), __('Error', $this->plugin_name), array(
                'response' 	=> 403,
                'back_link' => 'admin.php?page=cookierow',
            ));
        }
    }

    /**
     * @brief Adds custom fields to media library.
     * @param array $form_fields Previous media form fields
     * @param $post Post data
     * @return array media form fields
     */
    function custom_media_fields($form_fields, $post) {
        $field_value = get_post_meta($post->ID, 'image_so_source_name', true);

        $form_fields['image_so_source_name'] = array(
            'value' => $field_value ? $field_value : '',
            'label' => __('Source name', $this->plugin_name),
            'helps' => __('Include the source name', $this->plugin_name),
            'input'  => 'text'
        );

        $field_value = get_post_meta($post->ID, 'image_so_source_url', true);

        $form_fields['image_so_source_url'] = array(
            'value' => $field_value ? $field_value : '',
            'label' => __('Source URL', $this->plugin_name),
            'helps' => __('URL to link the source to', $this->plugin_name),
            'input'  => 'url'
        );

        $field_value = get_post_meta($post->ID, 'image_so_source_position', true);
        $form_fields['image_so_source_position'] = array(
            'value' => $field_value ? $field_value : '',
            'label' => __('Source position', $this->plugin_name),
            'helps' => __('Where the source will be positioned', $this->plugin_name),
            'input' => 'html'
        );
        $form_fields['image_so_source_position']['html'] = "<select name='attachments[{$post->ID}][image_so_source_position]'>";
        $form_fields['image_so_source_position']['html'] .= '<option '.selected(get_post_meta($post->ID, "image_so_source_position", true), 'default',false).' value="default">' . __('Default', $this->plugin_name) . '</option>';
        $form_fields['image_so_source_position']['html'] .= '<option '.selected(get_post_meta($post->ID, "image_so_source_position", true), 'top-left',false).' value="top-left">' . __('Top left', $this->plugin_name) . '</option>';
        $form_fields['image_so_source_position']['html'] .= '<option '.selected(get_post_meta($post->ID, "image_so_source_position", true), 'top-right',false).' value="top-right">' . __('Top right', $this->plugin_name) . '</option>';
        $form_fields['image_so_source_position']['html'] .= '<option '.selected(get_post_meta($post->ID, "image_so_source_position", true), 'bottom-left',false).' value="bottom-left">' . __('Bottom left', $this->plugin_name) . '</option>';
        $form_fields['image_so_source_position']['html'] .= '<option '.selected(get_post_meta($post->ID, "image_so_source_position", true), 'bottom-right',false).' value="bottom-right">' . __('Bottom right', $this->plugin_name) . '</option>';
        $form_fields['image_so_source_position']['html'] .= '</select>';

        return $form_fields;
    }

    /**
     * @brief Handles saving of custom fields.
     * @param $attachment_id
     */
    function custom_media_save_attachment($attachment_id) {
        if (isset($_REQUEST['attachments'][$attachment_id]['image_so_source_name'])) {
            $image_so_source_name = $_REQUEST['attachments'][$attachment_id]['image_so_source_name'];
            update_post_meta($attachment_id, 'image_so_source_name', $image_so_source_name);
        }
        if (isset($_REQUEST['attachments'][$attachment_id]['image_so_source_url'])) {
            $image_so_source_url = $_REQUEST['attachments'][$attachment_id]['image_so_source_url'];
            update_post_meta($attachment_id, 'image_so_source_url', $image_so_source_url);
        }
        if (isset($_REQUEST['attachments'][$attachment_id]['image_so_source_position'])) {
            $image_so_source_position = sanitize_text_field($_REQUEST['attachments'][$attachment_id]['image_so_source_position']);
            update_post_meta($attachment_id, 'image_so_source_position', $image_so_source_position);
        }
    }

    /**
     * @brief Adds link next to "deactivate" in plugin page.
     * @param $links
     * @return mixed
     */
    public function add_settings_link($links) {
        array_unshift($links, $this->get_settings_link());
        return $links;
    }

    /**
     * @brief Build the link.
     * @return string
     */
    private function get_settings_link()
    {
        return  '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>';
    }
}