<?php

/**
 * @package CustomActivationPage
 */
/*
  Plugin Name:  super_plugin
  Plugin URI: http://wp.uwiuw.com.com/custom-activation-page
  Description: Redesign and styling activation page
  Version: 0.0.1
  Author: uwiuw (uwiuw.inlove@gmail.com)
  Author URI: http://wp.uwiuw.com.com/about/
  License: GPLv2 or later
 */

$abspath = plugin_dir_path(__FILE__);
$absurl = plugins_url();
//$x = array_keys(get_defined_vars());
//$hasildebug = print_r($x, TRUE);
//echo "\n" . '<pre style="font-size:14px"><hr>' . '$hasildebug ' . htmlentities2($hasildebug) . '</pre>';

/**
 * List of Variable already created by wordpress
 *
 * $wp_post_statuses
 * $wp_theme_directories
 * $_wp_post_type_features
 * $l10n
 * $wp_taxonomies
 * $pagenow
 * $is_lynx
 * $is_winIE
 * $is_macIE
 * $is_opera
 * $is_NS4
 * $is_safari
 * $is_chrome
 * $is_iphone
 * $is_IE
 * $is_apache
 * $allowedtags
 * $allowedposttags
 * $site_id
 * $public
 * $blogname
 * $current_blog
 * $wp_current_filter
 * $current_site
 * $cookie_domain
 * $path
 * $merged_filters
 * $wp_filter
 * $wp_object_cache
 * $wp_object_cache
 * $_wp_using_ext_object_cache
 * $wpdb
 * $timestart
 * $PHP_SELF
 * $blog_id
 * $required_mysql_version
 * $required_php_version
 * $manifest_version
 * $tinymce_version
 * $wp_db_version
 * $wp_version
 * $base
 * $table_prefix
 * MULAI VARIABLE STANDAR seperti
 * $_REQUEST
 * $HTTP_POST_FILES
 * $_FILES
 * $HTTP_SERVER_VARS
 * $_SERVER
 * $HTTP_COOKIE_VARS
 * $_COOKIE
 * $HTTP_GET_VARS
 * $_GET
 * $HTTP_POST_VARS
 * $_POST
 * $HTTP_ENV_VARS
 * $_ENV
 * $GLOBALS
 */
/* * *
 * Manakah yg lebih dulu diinisiasi oleh wordpress..plugin ataukah wordpress ?
 * Plugin di wp-setting.php:196
 */

isPluginInHighestPos($plugin);
if ($_FILES) {

    $ajaxRep = new Uw_AjaxResponse;

    if ($_POST['_wpnonce-custom-background-upload']) {
        //di http://wpmulti32.com/wp-admin/themes.php?page=custom-background
        $ajax = <<<HTML
jQuery(document).ready(function() {
    var before = jQuery("#message").text();
    jQuery("#message").html('<p><del>' + before + '</del>. Uploading feature is blocked $setan</p>');
});
HTML;
        $ajaxRep->set($ajax);
        add_action('admin_footer', array($ajaxRep, 'cetak'));
    } elseif ($_POST['_wpnonce-custom-header-upload']) {
        //http://wpmulti32.com/wp-admin/themes.php?page=custom-header
        $ajax = <<<HTML
<br/><br/><div id="message" class="updated below-h2"><p>Uploading feature is blocked</p></div>
HTML;
        $ajaxRep->set($ajax);
        add_filter('wp_handle_upload_prefilter', array($ajaxRep, 'killPrint'));
    }

    unset($_FILES);
}

function isPluginInHighestPos($plugin) {
    $needle = basename(dirname($plugin)) . DIRECTORY_SEPARATOR . basename($plugin);
    $needle = str_replace(DIRECTORY_SEPARATOR, '/', $needle);
    $active_plugins = (array) get_option('active_plugins', array());
    if (false !== $pos = array_search($needle, $active_plugins)) {
        if ($pos !== 0) {
            $temp = $active_plugins[$pos];
            unset($active_plugins[$pos]);
            array_push($active_plugins, $temp);
            update_option('active_plugins', $active_plugins);
        }
    }
}

class Uw_AjaxResponse
{

    /**
     * Ajax command
     * @var string
     */
    private $_ajax;
    private $_location = 'admin_footer';

    function set($ajax) {
        $this->ajax = $ajax;

    }

    function cetak() {
        if ($this->ajax) {
            $o = "<script>$this->ajax</script>";
        }
        echo $o;

    }

    function killPrint() {
        die($this->ajax);

    }

}
