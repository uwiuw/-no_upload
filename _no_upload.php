<?php

/**
 * @package -no_upload
 */
/*
  Plugin Name: -no_upload
  Plugin URI: http://wp.uwiuw.com.com/custom-activation-page
  Description: WordPress plugin to block all upload attempt. Hackish! don't use if
  you want something saver. But it's seem working
  Version: 0.0.1
  Author: uwiuw (uwiuw.inlove@gmail.com)
  Author URI: http://wp.uwiuw.com.com/about/
  License: GPLv2 or later
 */

$abspath = plugin_dir_path(__FILE__);
$absurl = plugins_url();

/* * *
 * Manakah yg lebih dulu diinisiasi oleh wordpress..plugin ataukah wordpress ?
 * Plugin di wp-setting.php:196
 */
isPluginInHighestPos($plugin);
if ($_FILES) {

    $ajaxRep = new Uw_AjaxResponse;

    if ($_POST['_wpnonce-custom-background-upload']) {
        //@see http://wpmulti32.com/wp-admin/themes.php?page=custom-background
        $ajax = <<<HTML
jQuery(document).ready(function() {
    var before = jQuery("#message").text();
    jQuery("#message").html('<p><del>' + before + '</del>. Uploading feature is blocked $setan</p>');
    }
);
HTML;
        $ajaxRep->set($ajax);
        add_action('admin_footer', array($ajaxRep, 'cetak'));
    } elseif ($_POST['_wpnonce-custom-header-upload']) {
        //@see http://wpmulti32.com/wp-admin/themes.php?page=custom-header
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
