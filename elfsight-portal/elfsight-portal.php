<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('ElfsightPortal')) {
    class ElfsightPortal {
        private $portal_version = '1.0.0';

        private $params_action;

        public $app_slug;
        public $app_name;
        public $app_version; 

        public $plugin_slug;
        public $plugin_menu_icon;

        public $menu_id;

        public function __construct($config) {
            $this->app_slug = $config['app_slug'];
            $this->app_name = $config['app_name'];
            $this->app_version = $config['app_version'];

            $this->plugin_slug = $config['plugin_slug'];
            $this->plugin_menu_icon = $config['plugin_menu_icon'];

            $this->embed_url = $config['embed_url'];

            $this->params_action = 'elfsight_apps_' . str_replace('-', '_', $this->app_slug) . '_params';

            add_action('plugin_action_links_' . $this->plugin_slug, array($this, 'addActionLinks'));
            add_action('admin_menu', array($this, 'addAdminMenu'));
            add_action('admin_init', array($this, 'registerAssets'));
            add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
            add_action('wp_ajax_' . $this->params_action, array($this, 'saveParams'));
            add_action('admin_enqueue_scripts', array($this, 'localizeAssets'));

            $this->capability = apply_filters('elfsight_apps_capability', 'manage_options');
        }

        public function addActionLinks($links) {
            $links[] = '<a href="' . esc_url(admin_url('admin.php?page=' . $this->app_slug)) . '">Settings</a>';
            $links[] = '<a href="https://elfsight.com/?utm_source=markets&utm_medium=wordpressorg&utm_campaign=' . str_replace('elfsight-', '', $this->app_slug) . '&utm_content=plugins-list" target="_blank">More plugins by Elfsight</a>';

            return $links;
        }

        public function addAdminMenu() {
            $this->menu_id = add_menu_page($this->app_name, $this->app_name, $this->capability, $this->app_slug, array($this, 'getPage'), $this->plugin_menu_icon);
        }

        public function registerAssets() {
            wp_register_style('elfsight-portal-admin', plugins_url('assets/elfsight-portal-admin.css', __FILE__), array(), $this->portal_version);
            wp_register_script('elfsight-portal-admin', plugins_url('assets/elfsight-portal-admin.js', __FILE__), array('jquery'), $this->portal_version, true);
        }

        public function enqueueAssets($hook) {
            if ($hook == $this->menu_id) {
                wp_enqueue_style('elfsight-portal-admin');
                wp_enqueue_script('elfsight-portal-admin');
            }
        }

        public function localizeAssets($hook) {
            if ($hook == $this->menu_id) {
                wp_localize_script('elfsight-portal-admin', 'elfsightPortalAjaxObj', array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce($this->params_action . '_nonce'),
                    'action' => $this->params_action
                ));
            }
        }

        public function saveParams() {
            if (!wp_verify_nonce($_REQUEST['nonce'], $this->params_action . '_nonce')) {
                exit;
            }

            if (isset($_REQUEST['params'])) {
                update_option($this->params_action, json_encode($_REQUEST['params']));
            }
        }

        public function getPage() {
            $params = get_option($this->params_action, '');

            $url = $this->embed_url;

            if (!empty($params)) {
                $url .= (parse_url($this->embed_url, PHP_URL_QUERY) ? '&' : '?') . 'params=' . rawurlencode($params);
            }

            ?><div class="elfsight-portal wrap"><iframe src="<?php echo $url; ?>"></iframe></div><?php
        }
    }
}

?>