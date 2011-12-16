<?php

/*
  Plugin Name: -no_upload
  Plugin URI: http://wp.uwiuw.com.com/custom-activation-page
  Description: WordPress plugin to block all upload attempt. Hackish! don't use if
  you want something safety. But it's seem working for me.
  Version: 0.0.1
  Author: uwiuw (uwiuw.inlove@gmail.com)
  Author URI: http://wp.uwiuw.com.com/about/
  License: GPLv2 or later
 */

//$abspath = plugin_dir_path(__FILE__);
//$absurl = plugins_url();

isPluginInHighestPos($plugin);
if ($_FILES) {

    $ajaxRep = new Uw_AjaxResponse;

    if ($_POST['_wpnonce-custom-background-upload']) {
        //@see local server : http://wpmulti32.com/wp-admin/themes.php?page=custom-background
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
        //@see local server : http://wpmulti32.com/wp-admin/themes.php?page=custom-header
        $ajax = <<<HTML
<br/><br/><div id="message" class="updated below-h2"><p>Uploading feature is blocked</p></div>
HTML;
        $ajaxRep->set($ajax);
        add_filter('wp_handle_upload_prefilter', array($ajaxRep, 'killPrint'));
    }

    unset($_FILES);
}

/**
 * Check whether the plugin is the hightest on list of plugin.
 *
 * The purpose is to make sure this plugin always the first to initiate.
 * so it can unset $_FILES before any attempt to process it.
 *
 * @param string $plugin current plugin path
 *
 * @return void
 */
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

/**
 * Uw_AjaxResponse
 *
 * Multi-use Print ajax. Works on hook
 *
 * @category  Uw
 * @package   Uw_AjaxResponse
 * @author    Aulia Ashari <uwiuw.inlove@gmail.com>
 * @copyright 2011 Outerim Aulia Ashari
 * @license   http://dummylicense/ dummylicense License
 * @version   Release: @package_version@
 * @link      http://uwiuw.com/
 * @since     3.3
 * @todo      kembangkan bagian location dan semacamnya itu
 */
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
