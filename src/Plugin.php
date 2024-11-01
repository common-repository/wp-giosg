<?php

namespace WPGiosg;

use WPGiosg\Plugin\Settings\Settings;

class Plugin
{
    /**
     * Plugin specific constants.
     */
    const VERSION = '1.0.8';
    const SETTINGS_NAME = 'wp_giosg_settings';
    const PLUGIN_SLUG = 'wp-giosg';
    const NONCE = 'wp-giosg';
    const DEFAULT_SCRIPT_VERSION = 'v1';

    /**
     * @var string
     */
    private $capability = 'manage_options';

    /**
     *
     * @var Settings
     */
    private $settings;

    /**
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
        $this->init();
    }

    /**
     * Uninstalls the plugin.
     */
    public static function delete()
    {
        $settings = get_giosg_container()['settings'];
        $settings->delete();
    }

    /**
     * Check if any updates needs to be performed.
     */
    public static function activate()
    {
        // Load settings.
        $settings = get_giosg_container()['settings'];

        if (version_compare($settings->get('version'), self::VERSION, '<')) {
            $settings->add('script_version', self::DEFAULT_SCRIPT_VERSION);
            $settings->set('version', self::VERSION);

            // Store updated settings.
            $settings->save();
        }
    }

    /**
     * @return array
     */
    private function getCurrencies()
    {
        return get_woocommerce_currencies();
    }

    /**
     * @return string
     */
    private function getCurrency()
    {
        return get_woocommerce_currency();
    }

    /**
     * Initialization.
     */
    public function init()
    {
        // Allow people to change what capability is required to use this plugin.
        $this->capability = apply_filters('wp_giosg_cap', $this->capability);

        $this->addFilters();
        $this->addActions();
        $this->localize();
    }

    /**
     * Register filters.
     */
    private function addFilters()
    {
        add_filter('plugin_action_links', [$this, 'addActionLinks'], 10, 2);
        add_filter('plugin_row_meta', [$this, 'filterPluginRowMeta'], 10, 4);
    }

    /**
     * Register actions.
     */
    private function addActions()
    {
        add_action('admin_init', [$this, 'onAdminInit']);
        add_action('admin_menu', [$this, 'addMenu']);
        add_action('in_admin_header', [$this, 'doAdminHeader']);
        add_action('admin_post_wp_giosg_save_settings', [$this, 'saveSettings']);
        if ($this->settings->get('active')) {
            if (is_user_logged_in() || $this->settings->get('anonymously')) {
                add_action('wp_head', [$this, 'addScript']);
                if ($this->settings->get('enable_basket')) {
                    add_action('wp_head', [$this, 'addBasket']);
                    add_action('wp_head', [$this, 'registerScripts']);
                    add_action('wp_ajax_giosg_update_cart', [$this, 'ajaxGetCart']);
                    add_action('wp_ajax_nopriv_giosg_update_cart', [$this, 'ajaxGetCart']);
                }
            }
        }
    }

    /**
     * Handle admin_init action.
     */
    public function onAdminInit()
    {
        // Make sure woocommerce is activated
        if ($this->settings->get('enable_basket')) {
            // Only available after admin_init has been fired.
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                $this->settings->set('enable_basket', false);
                $this->settings->save();
            }
        }
    }

    /**
     * @param array $links
     * @param string $file
     * @return array
     */
    public function addActionLinks(array $links, $file)
    {
        $settings_link = '<a href="' . admin_url('options-general.php?page=' . self::PLUGIN_SLUG) . '">' . __('Settings', 'wp-giosg') . '</a>';
        if ($file === 'wp-giosg/bootstrap.php') {
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
     * Filters the array of row meta for each plugin in the Plugins list table.
     *
     * @param array $plugin_meta An array of the plugin's metadata.
     * @param string $plugin_file Path to the plugin file relative to the plugins directory.
     * @return array An array of the plugin's metadata.
     */
    public function filterPluginRowMeta(array $plugin_meta, $plugin_file)
    {
        if ($plugin_file !== 'wp-giosg/bootstrap.php') {
            return $plugin_meta;
        }

        $plugin_meta[] = sprintf(
            '<a target="_blank" href="%1$s"><span class="wp-giosg dashicons dashicons-star-filled" aria-hidden="true"></span>%2$s</a>',
            'https://www.buymeacoffee.com/cyclonecode',
            esc_html_x('Sponsor', 'verb', 'wp-giosg')
        );
        $plugin_meta[] = sprintf(
            '<a target="_blank" href="%1$s"><span class="wp-giosg dashicons dashicons-thumbs-up" aria-hidden="true"></span>%2$s</a>',
            'https://wordpress.org/support/plugin/wp-giosg/reviews/?rate=5#new-post',
            esc_html_x('Rate', 'verb', 'wp-giosg')
        );
        $plugin_meta[] = sprintf(
            '<a target="_blank" href="%1$s"><span class="wp-giosg dashicons dashicons-editor-help" aria-hidden="true"></span>%2$s</a>',
            'https://wordpress.org/support/plugin/wp-giosg/#new-topic-0',
            esc_html_x('Support', 'verb', 'wp-giosg')
        );

        return $plugin_meta;
    }

    /**
     *
     */
    public function ajaxGetCart()
    {
        check_ajax_referer(self::NONCE);
        wp_send_json($this->getCartItems());
    }

    /**
     * Add scripts and stylesheets.
     */
    public function registerScripts()
    {
        wp_register_script(
            'wp_giosg',
            plugin_dir_url(__FILE__) . '/js/basket.js',
            ['jquery'],
            self::VERSION,
            true
        );
        wp_localize_script(
            'wp_giosg',
            'wp_giosg',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'data' => [
                    'action' => 'giosg_update_cart',
                    '_ajax_nonce' => wp_create_nonce(self::NONCE),
                ],
            ]
        );
        wp_enqueue_script('wp_giosg');
    }

    /**
     * Add basket script if enabled.
     */
    public function addBasket()
    {
        if ($this->settings->get('active')) {
            if ((is_user_logged_in() || $this->settings->get('anonymously')) && $this->settings->get('enable_basket')) {
                extract(['giosg' => $this]);
                require_once __DIR__ . '/templates/basket.php';
            }
        }
    }

    /**
     * @return array
     */
    private function getCartItems()
    {
        $items = [];
        $coupons = [];
        foreach (WC()->cart->get_cart() as $item) {
            $items[] = [
                'name' => $item['data']->get_title(),
                'quantity' => $item['quantity'],
                'price' => $item['data']->get_price(),
            ];
        }
        foreach (WC()->cart->get_coupons() as $coupon) {
            $coupons[] = [
                'name' => $coupon->get_code(),
                'type' => $coupon->get_discount_type(),
                'amount' => $coupon->get_amount(),
            ];
            $items[] = [
                'name' => $coupon->get_code(),
                'quantity' => 1,
                'price' => 0,
            ];
        }
        return [
            'items' => $items,
            'coupons' => $coupons,
            'cart_subtotal' => WC()->cart->get_cart_subtotal(),
            'total' => WC()->cart->get_total(),
            'discount_subtotal' => WC()->cart->get_displayed_subtotal(),
            'discount_total' => WC()->cart->get_discount_total(),
            'cart_total' => WC()->cart->get_cart_total(),
            'cart_discount_total' => WC()->cart->get_cart_discount_total(),
            'displayed_subtotal' => WC()->cart->get_displayed_subtotal(),
            'subtotal' => WC()->cart->get_subtotal(),
            'total_discount' => WC()->cart->get_total_discount(),
        ];
    }

    /**
     * Render admin header.
     */
    public function doAdminHeader()
    {
        if (get_current_screen()->id !== 'settings_page_wp-giosg') {
            return;
        }
        $sectionText = __('Settings', 'wp-giosg');
        $title = ' | ' . $sectionText;
        ?>
        <div id="wp-giosg-admin-header" style="padding-top: 20px">
            <span><img style="float: left; margin-right: 20px;" width="64" src="<?php echo plugin_dir_url(__FILE__); ?>assets/icon-128x128.png" alt="<?php _e('WP Giosg', 'wp-giosg'); ?>" />
                <h1 style="font-size: 23px; font-weight: 400; margin-top: 20px"><?php _e('WP Giosg', 'wp-giosg'); ?><?php echo $title; ?></h1>
            </span>
        </div>
        <?php
    }

    /**
     * Add menu item for plugin.
     */
    public function addMenu()
    {
        add_submenu_page(
            'options-general.php',
            __('WP Giosg Settings', 'wp-giosg'),
            __('WP Giosg Settings', 'wp-giosg'),
            $this->capability,
            self::PLUGIN_SLUG,
            [$this, 'displaySettingsPage']
        );
    }

    /**
     * Save settings.
     */
    public function saveSettings()
    {
        // Validate so user has correct privileges.
        if (!current_user_can($this->capability)) {
            die(__('You are not allowed to perform this action.', 'wp-giosg'));
        }
        // Verify nonce and referer.
        check_admin_referer('wp-giosg-settings-action', 'wp-giosg-settings-nonce');
        // Filter and sanitize form values.
        $this->settings->set('script_version', filter_input(
            INPUT_POST,
            'scriptVersion',
            FILTER_SANITIZE_STRING
        ));
        $this->settings->set('active', filter_input(
            INPUT_POST,
            'active',
            FILTER_VALIDATE_BOOLEAN
        ));
        $this->settings->set('anonymously', filter_input(
            INPUT_POST,
            'anonymously',
            FILTER_VALIDATE_BOOLEAN
        ));
        $id = filter_input(
            INPUT_POST,
            'companyId',
            FILTER_SANITIZE_STRING
        );
        $this->settings->set('id', trim($id));
        $this->settings->set('enable_basket', filter_input(
            INPUT_POST,
            'enableBasket',
            FILTER_VALIDATE_BOOLEAN
        ));
        $this->settings->save();
        wp_safe_redirect(admin_url('options-general.php?page=' . self::PLUGIN_SLUG));
    }

    /**
     * Get all supported stores.
     *
     * @return array
     */
    private function getStores()
    {
        return [
            '' => __('Select', 'wp-giosg'),
            'woocommerce' => 'woocommerce',
            'shopify' => 'shopify',
            'wp-commerce' => 'WP Commerce',
        ];
    }

    /**
     * Display the settings page.
     */
    public function displaySettingsPage()
    {
        require_once __DIR__ . '/templates/settings.php';
    }

    /**
     * Localize plugin.
     */
    protected function localize()
    {
        load_plugin_textdomain('wp-giosg', false, 'wp-giosg/languages');
    }

    /**
     * Register scripts.
     */
    public function addScript()
    {
        if ($this->settings->get('active')) {
            if (is_user_logged_in() || $this->settings->get('anonymously')) {
                extract([
                    'id' => $this->settings->get('id'),
                    'version' => $this->settings->get('script_version') === self::DEFAULT_SCRIPT_VERSION ? '' : 2,
                ]);
                require_once __DIR__ . '/templates/script.php';
            }
        }
    }
}
