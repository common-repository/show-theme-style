<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/* 
Copyright © Dreamwinner © « Saturday, June 24 2017 *** 5:19:57 PM ». All rights reserved.
@package Dreamwinner. @version 1.0 
Plugin Name: Show Theme Style
Plugin URI: http://wordpress.org/plugins/show-theme-style/
Description: Links theme style to WordPress Visual Editor
Author: Dreamwinner
Version: 1.0
Text Domain: show-theme-style
*/



add_filter('plugins_loaded', function () {
	$domain = array_shift(explode('/', plugin_basename(dirname(__FILE__))));
	if (!is_textdomain_loaded( $domain )) {
		load_plugin_textdomain( $domain, FALSE, $domain );
	}
 });



/* °°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°° */
add_action( 'admin_head', function () {
	$domain = array_shift(explode('/', plugin_basename(dirname(__FILE__))));
	$content = "<p>" . __("Links theme style to WordPress Visual Editor", $domain) . "</p>";
	$content .= "<p>" . __("If you activated this plugin and doesn't see changes, try change theme. It's possible, your current theme doesn't affect editor content appearance", $domain) . "</p>";
	$content .= "<p>" . __("May be, your current theme doesn't contain font rules, background rules, padding rules etc.", $domain) . "</p>";
	$content .= "<p>" . sprintf(__("You can find my plugins at <a href=\"%s\" target=\"_blank\">%s</a>", $domain), 'https://dreamwinnerblog.wordpress.com/', 'dreamwinnerblog.wordpress.com') . "</p>";
	get_current_screen()->add_help_tab( array(
		'id' 			=> $domain,
		'title' 		=> __('Theme Style', $domain),
		'content' 	=> $content
	));	
 });



/* °°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°° */
add_filter( 'tiny_mce_before_init', function ($params) {
	$styleuri 			= get_stylesheet_uri();
	$stylepath 			= ABSPATH . str_replace(site_url() . '/', '', $styleuri);

	if(!file_exists($stylepath)) { // if we unable to find 'style.css' file, try look for the 'style.min.css'
		$stylepath = preg_replace("/\\.css$/i", '.min.css', $stylepath);
	};

	if(!file_exists($stylepath)) { 
		return $params; // if we unable to find 'style.css' or 'style.min.css' file, bail out
	};

	$styleuri 			= str_replace(ABSPATH, site_url() . '/', $stylepath) . '?ver=' . get_bloginfo( 'version');

	$styles 				= preg_split("/\\s*,\\s*/", $params['content_css']);
	
	if(!in_array($styleuri, $styles)) {
		$styles[] 							= $styleuri;
		$params['content_css'] 	= implode(',', $styles);
	};

	return $params;
 });




function showthemestyleactivate() {
	$domain 		= array_shift(explode('/', plugin_basename(dirname(__FILE__))));
	$mos 			= glob(WP_PLUGIN_DIR . '/' . $domain . '/*.mo'); 
	foreach($mos as  $mo) {
		@copy($mo, WP_LANG_DIR . '/plugins/' . pathinfo( $mo, PATHINFO_BASENAME ));
	}
 }


function showthemestyleuninstall() {
	$domain 		= array_shift(explode('/', plugin_basename(dirname(__FILE__))));
	$files 			= list_files( WP_LANG_DIR . '/plugins' );
	foreach($files as $file) {
		if(preg_match("/\\/" . $domain . "\\-[a-z]{2}_[A-Z]{2}\\.(mo|po)$/", $file)) {
			unlink($file);
		}
	}
 }


register_activation_hook( __FILE__, 	'showthemestyleactivate' );
register_uninstall_hook( __FILE__, 	'showthemestyleuninstall' );
