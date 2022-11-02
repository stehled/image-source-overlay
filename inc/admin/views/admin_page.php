<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Image Source Overlay Settings', 'image-source-overlay'); ?></h1>
    <div class="image_so_admin_form">
        <form action="<?php echo(esc_url(admin_url( 'admin-post.php' ))); ?>" method="post" id="image_so_admin_form" >
            <input type="hidden" name="action" value="image_so_admin_form_response">
            <input type="hidden" name="image_so_admin_form_nonce" value="<?php echo(esc_attr($image_so_admin_form_nonce)); ?>" />
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="image_so-source-text"><?php _e('Source text', 'image-source-overlay'); ?></label>
                        </th>
                        <td>
                            <input id="image_so-source-text" type="text" name="image_so-source-text" value="<?php echo(esc_attr($source_text)); ?>" placeholder="<?php _e('Source:', 'image-source-overlay'); ?>" />
                            <p class="description" id="tagline-source-text"><?php _e('Leave blank for default plugin translation.', 'image-source-overlay'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="image_so-position"><?php _e('Position', 'image-source-overlay'); ?></label>
                        </th>
                        <td>
                            <select required id="image_so-position" name="image_so-position" />
                                <option <?php if($position === 'top-left') echo('selected'); ?> value="top-left"><?php _e('Top left', 'image-source-overlay'); ?></option>
                                <option <?php if($position === 'top-right') echo('selected'); ?> value="top-right"><?php _e('Top right', 'image-source-overlay'); ?></option>
                                <option <?php if($position === 'bottom-left') echo('selected'); ?> value="bottom-left"><?php _e('Botom left', 'image-source-overlay'); ?></option>
                                <option <?php if($position === 'bottom-right') echo('selected'); ?> value="bottom-right"><?php _e('Botom right', 'image-source-overlay'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="image_so-only-post"><?php echo __('Only on post page', 'image-source-overlay'); ?></label>
                        </th>
                        <td>
                            <select required id="image_so-only-post" name="image_so-only-post" />
                                <option <?php if($only_post === '0') echo('selected'); ?> value="0"><?php _e('Yes'); ?></option>
                                <option <?php if($only_post === '1') echo('selected'); ?> value="1"><?php _e('No'); ?></option>
                            </select>
                            <p class="description" id="tagline-only-text"><?php _e('If source overlay should be shown only on post pages or on every page including main page.', 'image-source-overlay'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="image_so-nofollow"><?php echo __('Default link setting', 'image-source-overlay'); ?></label>
                        </th>
                        <td>
                            <select required id="image_so-nofollow" name="image_so-nofollow" />
                                <option <?php if($nofollow === 'nofollow') echo('selected'); ?> value="nofollow"><?php _e('Nofollow', 'image-source-overlay'); ?></option>
                                <option <?php if($nofollow === 'dofollow') echo('selected'); ?> value="dofollow"><?php _e('Dofollow', 'image-source-overlay'); ?></option>
                            </select>
                            <p class="description" id="tagline-nofollow-text"><?php _e('If links should affect PageRank score.', 'image-source-overlay'); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save changes', 'image-source-overlay'); ?>"></p>
        </form>
        <br/><br/>
        <div id="image_so_form_feedback"></div>
        <br/><br/>
    </div>
</div>
