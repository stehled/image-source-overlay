<?php

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
        add_management_page(__('Image SO Settings', 'image-source-overlay'), __('Image SO Settings', 'image-source-overlay'), 'manage_options', 'image_so', array($this, 'admin_page'));
    }

    /**
     * @brief Sets admin page to render.
     */
    public function admin_page(){
        if(current_user_can( 'manage_options')) {
            $image_so_admin_form_nonce = wp_create_nonce( 'image_so_admin_form_nonce');
            self::view('admin_page', array('image_so_admin_form_nonce' => $image_so_admin_form_nonce, 'position' => $this->position, 'source_text' => $this->source_text, 'only_post' => $this->only_post, 'nofollow' => $this->nofollow));
        }
        else {
            ?>
            <p> <?php __("You are not authorized to perform this operation.", 'image-source-overlay') ?> </p>
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
							<p><strong>' . __('Success', 'image-source-overlay') . ' </strong>';
                $html .= sanitize_text_field(print_r($_REQUEST['image_so_response'], true)) . '</p></div>';
                echo wp_kses_post($html);
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
            if (!$this->check_select($position, $this->position_select)) {
                $this->invalid_option();
            }
            $only_post = sanitize_text_field($_POST['image_so-only-post']);
            if (!$this->check_select($only_post, $this->bool_select)) {
                $this->invalid_option();
            }
            $nofollow = sanitize_text_field($_POST['image_so-nofollow']);
            if (!$this->check_select($nofollow, $this->nofollow_select)) {
                $this->invalid_option();
            }
            $this->update_option_value('source_text', $source_text);
            $this->update_option_value('position', $position);
            $this->update_option_value('only_post', $only_post);
            $this->update_option_value('nofollow', $nofollow);
            $admin_notice = "success";
            $this->custom_redirect($admin_notice, 'image_so', __('settings were saved.', 'image-source-overlay'));
            exit;
        }
        else {
            wp_die(__( 'Invalid nonce specified', 'image-source-overlay'), __('Error', 'image-source-overlay'), array(
                'response' 	=> 403,
                'back_link' => 'admin.php?page=image_so',
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
            'value' => $field_value ? esc_attr($field_value) : '',
            'label' => __('Source name', 'image-source-overlay'),
            'helps' => __('Include the source name', 'image-source-overlay'),
            'input'  => 'text'
        );

        $field_value = get_post_meta($post->ID, 'image_so_source_url', true);

        $form_fields['image_so_source_url'] = array(
            'value' => $field_value ? esc_attr($field_value) : '',
            'label' => __('Source URL', 'image-source-overlay'),
            'helps' => __('URL to link the source to', 'image-source-overlay'),
            'input'  => 'url'
        );

        $field_value = get_post_meta($post->ID, 'image_so_source_position', true);
        $form_fields['image_so_source_position'] = array(
            'label' => __('Source position', 'image-source-overlay'),
            'helps' => __('Where the source will be positioned', 'image-source-overlay'),
            'input' => 'html'
        );
        $form_fields['image_so_source_position']['html'] = "<select name='attachments[{$post->ID}][image_so_source_position]'>";
        $form_fields['image_so_source_position']['html'] .= '<option '.selected($field_value, 'default',false).' value="default">' . __('Default', 'image-source-overlay') . '</option>';
        $form_fields['image_so_source_position']['html'] .= '<option '.selected($field_value, 'top-left',false).' value="top-left">' . __('Top left', 'image-source-overlay') . '</option>';
        $form_fields['image_so_source_position']['html'] .= '<option '.selected($field_value, 'top-right',false).' value="top-right">' . __('Top right', 'image-source-overlay') . '</option>';
        $form_fields['image_so_source_position']['html'] .= '<option '.selected($field_value, 'bottom-left',false).' value="bottom-left">' . __('Bottom left', 'image-source-overlay') . '</option>';
        $form_fields['image_so_source_position']['html'] .= '<option '.selected($field_value, 'bottom-right',false).' value="bottom-right">' . __('Bottom right', 'image-source-overlay') . '</option>';
        $form_fields['image_so_source_position']['html'] .= '</select>';

        $field_value = get_post_meta($post->ID, 'image_so_nofollow', true);
        $form_fields['image_so_nofollow'] = array(
            'label' => __('Link setting', 'image-source-overlay'),
            'helps' => __('If link should be dofollow or nofollow', 'image-source-overlay'),
            'input' => 'html'
        );
        $form_fields['image_so_nofollow']['html'] = "<select name='attachments[{$post->ID}][image_so_nofollow]'>";
        $form_fields['image_so_nofollow']['html'] .= '<option '.selected($field_value, 'default',false).' value="default">' . __('Default', 'image-source-overlay') . '</option>';
        $form_fields['image_so_nofollow']['html'] .= '<option '.selected($field_value, 'nofollow',false).' value="nofollow">' . __('Nofollow', 'image-source-overlay') . '</option>';
        $form_fields['image_so_nofollow']['html'] .= '<option '.selected($field_value, 'dofollow',false).' value="dofollow">' . __('Dofollow', 'image-source-overlay') . '</option>';
        $form_fields['image_so_nofollow']['html'] .= '</select>';

        return $form_fields;
    }

    /**
     * @brief Handles saving of custom fields.
     * @param $attachment_id
     */
    function custom_media_save_attachment($attachment_id) {
        if (isset($_REQUEST['attachments'][$attachment_id]['image_so_source_name'])) {
            $image_so_source_name = sanitize_text_field($_REQUEST['attachments'][$attachment_id]['image_so_source_name']);
            update_post_meta($attachment_id, 'image_so_source_name', $image_so_source_name);
        }
        if (isset($_REQUEST['attachments'][$attachment_id]['image_so_source_url'])) {
            $image_so_source_url = sanitize_url($_REQUEST['attachments'][$attachment_id]['image_so_source_url']);
            update_post_meta($attachment_id, 'image_so_source_url', $image_so_source_url);
        }
        if (isset($_REQUEST['attachments'][$attachment_id]['image_so_source_position'])) {
            $image_so_source_position = sanitize_text_field($_REQUEST['attachments'][$attachment_id]['image_so_source_position']);
            if (!$this->check_select($image_so_source_position, $this->select_default($this->position_select))) {
                $this->invalid_option();
            }
            update_post_meta($attachment_id, 'image_so_source_position', $image_so_source_position);
        }
        if (isset($_REQUEST['attachments'][$attachment_id]['image_so_nofollow'])) {
            $image_so_nofollow = sanitize_text_field($_REQUEST['attachments'][$attachment_id]['image_so_nofollow']);
            if (!$this->check_select($image_so_nofollow, $this->select_default($this->nofollow_select))) {
                $this->invalid_option();
            }
            update_post_meta($attachment_id, 'image_so_nofollow', $image_so_nofollow);
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
        return  '<a href="' . esc_url(admin_url('admin.php?page=image_so')) . '">' . __('Settings', 'image-source-overlay') . '</a>';
    }

    /**
     * @brief Returns select options with default option appended.
     * @param $options Array of select options
     * @return array
     */
    private function select_default($options) {
        $options[] = 'default';
        return $options;
    }

    /**
     * @brief Sends 403 with invalid option error.
     */
    private function invalid_option() {
        wp_die(__( 'Invalid option', 'image-source-overlay'), __('Error', 'image-source-overlay'), array(
            'response' 	=> 403,
            'back_link' => 'admin.php?page=image_so',
        ));
    }
}