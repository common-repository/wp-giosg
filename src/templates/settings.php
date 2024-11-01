<div class="wrap">
    <h1></h1>
    <form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">
        <input type="hidden" name="action" value="wp_giosg_save_settings" />
        <?php echo wp_nonce_field('wp-giosg-settings-action', 'wp-giosg-settings-nonce'); ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="active"><?php _e('Enable chat', 'wp-giosg'); ?></label>
                    </th>
                <td>
                    <input type="checkbox" name="active"<?php checked($this->settings->get('active')); ?> />
                    <p class="description"><?php _e('Activate live chat.', 'wp-giosg'); ?></p>
                    </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="scriptVersion"><?php _e('Script version', 'wp-giosg'); ?></label>
                </th>
                <td>
                    <select name="scriptVersion">
                        <option value="v1"<?php selected($this->settings->get('script_version') === 'v1'); ?>><?php _e('v1', 'wp-giosg'); ?></option>
                        <option value="v2"<?php selected($this->settings->get('script_version') === 'v2'); ?>><?php _e('v2', 'wp-giosg'); ?></option>
                    </select>
                    <p class="description"><?php _e('The version of the giosg script to use.', 'wp-giosg'); ?></p>
                </td>
            </tr>
            <tr>
            <tr>
                <th scope="row">
                    <label for="anonymously"><?php _e('Display chat for anonymous users', 'wp-giosg'); ?></label>
                    </th>
                <td>
                    <input type="checkbox" name="anonymously"<?php checked($this->settings->get('anonymously')); ?> />
                    <p class="description"><?php _e('Enable chat for both anonymous and logged in users.', 'wp-giosg'); ?></p>
                    </td>
                </tr>
            <tr>
                <th scope="row">
                    <label for="companyId"><?php _e('ID/UUID', 'wp-giosg'); ?></label>
                    </th>
                <td>
                    <input type="text" name="companyId" value="<?php echo $this->settings->get('id'); ?>" />
                    <p class="description"><?php _e('Giosg company ID or UUID.', 'wp-giosg'); ?></p>
                    </td>
                </tr>
            <tr>
                <th scope="row">
                    <label for="enableBasket"><?php _e('Enable basket', 'wp-giosg'); ?></label>
                    </th>
                <td>
                    <input type="checkbox" name="enableBasket"<?php checked($this->settings->get('enable_basket')); ?><?php disabled(!is_plugin_active('woocommerce/woocommerce.php')); ?> />
                    <p class="description"><?php _e('Enable basket tracking.', 'wp-giosg'); ?></p>
                    <p class="description"><?php _e('You need to have woocommerce enabled to use this feature.', 'wp-giosg'); ?></p>
                </td>
            </tr>
            <!--
            <tr>
                <th scope="row">
                    <label for="ecommerceType"><?php _e('E-Commerce', 'wp-giosg'); ?></label>
                </th>
                <td>
                    <select name="ecommerceType">
                        <?php foreach ($this->getStores() as $key => $store) : ?>
                        <option value="<?php echo $key; ?>"><?php echo $store; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description"><?php _e('Type of e-commerce solution.', 'wp-giosg'); ?></p>
                </td>
            </tr>
            -->
        </table>
        <?php echo get_submit_button(__('Save settings', 'wp-giosg'), 'primary', 'wp-giosg-settings'); ?>
    </form>
</div>