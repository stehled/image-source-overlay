<?php
/*
Projekt: viviwp
Soubor: admin_page.php
Uživatel: eda
Datum: 09.09.2022
*/
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo __('Image Source Overlay Settings', $plugin_name); ?></h1>
    <div class="image_so_admin_form">
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="image_so_admin_form" >
            <input type="hidden" name="action" value="image_so_admin_form_response">
            <input type="hidden" name="image_so_admin_form_nonce" value="<?php echo $image_so_admin_form_nonce ?>" />
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="<?php echo $plugin_name; ?>-source-text"><?php echo __('Source text', $plugin_name); ?></label>
                    </th>
                    <td>
                        <input id="<?php echo $plugin_name; ?>-source-text" type="text" name="image_so-source-text" value="<?php echo($source_text); ?>" placeholder="<?php _e('Source:', $plugin_name); ?>" />
                        <p class="description" id="tagline-source-text"><?php _e('Leave blank for default plugin translation.', $plugin_name); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo $plugin_name; ?>-position"><?php echo __('Position', $plugin_name); ?></label>
                    </th>
                    <td>
                        <select required id="<?php echo $plugin_name; ?>-position" name="image_so-position" />
                            <option <?php if($position === 'top-left') echo('selected'); ?> value="top-left"><?php _e('Top left', $plugin_name); ?></option>
                            <option <?php if($position === 'top-right') echo('selected'); ?> value="top-right"><?php _e('Top right', $plugin_name); ?></option>
                            <option <?php if($position === 'bottom-left') echo('selected'); ?> value="bottom-left"><?php _e('Botom left', $plugin_name); ?></option>
                            <option <?php if($position === 'bottom-right') echo('selected'); ?> value="bottom-right"><?php _e('Botom right', $plugin_name); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo $plugin_name; ?>-only-post"><?php echo __('Only on post page', $plugin_name); ?></label>
                    </th>
                    <td>
                        <select required id="<?php echo $plugin_name; ?>-only-post" name="image_so-only-post" />
                        <option <?php if($only_post === '0') echo('selected'); ?> value="0"><?php _e('Yes'); ?></option>
                        <option <?php if($only_post === '1') echo('selected'); ?> value="1"><?php _e('No'); ?></option>
                        </select>
                        <p class="description" id="tagline-only-text"><?php _e('If source overlay should be shown only on post pages or on every page including main page.', $plugin_name); ?></p>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save changes'); ?>"></p>
        </form>
        <br/><br/>
        <div id="image_so_form_feedback"></div>
        <br/><br/>
    </div>
</div>
