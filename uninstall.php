<?php
/*
Projekt: image_so
Soubor: uninstall.php
Uživatel: eda
Datum: 13.09.2022
*/

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}
delete_option('image_so_position');
delete_option('image_so_source_text');

delete_post_meta_by_key( 'image_so_source_name' );
delete_post_meta_by_key( 'image_so_source_url' );
delete_post_meta_by_key( 'image_so_source_position' );
