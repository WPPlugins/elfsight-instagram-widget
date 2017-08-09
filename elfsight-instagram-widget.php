<?php
/*
Plugin Name: Elfsight Instagram Widget
Description: Embed Instagram profile to your website. Promoting your account and getting new followers have never been easier.
Plugin URI: https://elfsight.com/instagram-widget-instalink/?utm_source=markets&utm_medium=wordpressorg&utm_campaign=instagram-widget&utm_content=plugin-site
Version: 1.0.1
Author: Elfsight
Author URI: https://elfsight.com/?utm_source=markets&utm_medium=wordpressorg&utm_campaign=instagram-widget&utm_content=plugins-list
*/

if (!defined('ABSPATH')) exit;

require_once('elfsight-portal/elfsight-portal.php');

new ElfsightPortal(array(
        'app_slug' => 'elfsight-instagram-widget',
        'app_name' => 'Instagram Widget',
        'app_version' => '1.0.1',

        'plugin_slug' => plugin_basename(__FILE__),
        'plugin_menu_icon' => plugins_url('assets/img/menu-icon.png', __FILE__),

        'embed_url' => 'https://apps.elfsight.com/embed/instalink/?utm_source=portals&utm_medium=wordpress&utm_campaign=instagram-widget&utm_content=sign-up'
    )
);

?>