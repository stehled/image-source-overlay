<?php

namespace Image_SO\Inc\Core;

use DOMDocument;

/**
 * Class Image_SO_Init
 * @package Image_SO\Inc\Core
 * @brief Class managing HTML manipulation and checking for database update.
 */
final class Image_SO_Init extends Image_SO_Base
{
    public function __construct() {
        parent::__construct();
        add_action('plugins_loaded', array($this, 'update_plugin'));
        add_filter('the_content', array($this, 'add_source_overlay'));
        add_filter('post_thumbnail_html', array($this, 'add_source_overlay'));
        add_action('wp_enqueue_scripts', array($this, 'hook_css'), 20);
    }

    /**
     * @brief Goes through HTML, selects images and adds overlays.
     * @param $content HTML to scan
     * @return string content HTML
     */
    public function add_source_overlay($content) {
        global $post;
        if ($this->only_post || is_singular()) {
            $doc = new DOMDocument();
            if (empty($content)) {
                return $content;
            }
            libxml_use_internal_errors(true);
            $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            libxml_use_internal_errors(false);
            $tags = $doc->getElementsByTagName('img');
            foreach ($tags as $tag) {
                $id = attachment_url_to_postid(sanitize_url($tag->getAttribute('src')));
                if ($id === 0) {
                    $src = preg_replace('/-[0-9]{3,4}x[0-9]{3,4}(\.[a-zA-Z]{3,4})$/', '$1', $tag->getAttribute('src'));
                    $id = attachment_url_to_postid(sanitize_url($src));
                }
                if ($id !== 0) {
                    if (!empty($source = get_post_meta($id, 'image_so_source_name', true))) {
                        $wrap = $doc->createElement('div');
                        $wrap->setAttribute('class', 'image-so-image-wrap');
                        $overlay = $doc->createElement('div');
                        $position = $this->position;
                        if (!empty($custom_position = get_post_meta($id, 'image_so_source_position', true)) && $custom_position !== 'default') {
                            $position = $custom_position;
                        }
                        $source_text = $this->source_text;
                        $source = htmlspecialchars($source);
                        $overlay->textContent = (!empty($source_text) ? $source_text : (__('Source', 'image-source-overlay') . ':')) . ' ';
                        if (!empty($source_url = get_post_meta($id, 'image_so_source_url', true))) {
                            $nofollow = get_post_meta($id, 'image_so_nofollow', true);
                            $nofollow = (!empty($nofollow) && $nofollow !== 'default') ? $nofollow : $this->nofollow;
                            $url = $doc->createElement('a');
                            $url->setAttribute('href', esc_url($source_url));
                            $url->setAttribute('target', '_blank');
                            if ($nofollow === 'nofollow') {
                                $url->setAttribute('rel', 'nofollow');
                            }
                            $url->textContent = esc_html($source);
                            $overlay->appendChild($url);
                        } else {
                            $overlay->textContent = esc_html($overlay->textContent . $source);
                        }
                        $overlay->setAttribute('class', esc_attr('image-so-overlay image-so-' . $position));
                        $parent = $tag->parentNode;
                        $parent->removeChild($tag);
                        $wrap->appendChild($tag);
                        $wrap->appendChild($overlay);
                        $parent->appendChild($wrap);
                    }
                }
            }
            return $doc->saveHTML();
        }
        return $content;
    }

    /**
     * @brief Adds CSS to front-end.
     */
    public function hook_css() {
        wp_enqueue_style( 'image_so', IMAGE_SO__PLUGIN_URL . '/assets/overlay.css', false );
    }

    /**
     * @brief Check version number and update database.
     */
    public function update_plugin() {
        if (IMAGE_SO__VERSION_NUMBER !== get_option('image_so_version_number')) {
            new Image_SO_Updater();
        }
    }
}