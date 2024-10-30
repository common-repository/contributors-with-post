<?php

/*
Plugin Name: Contributors with post
Description: This plugin is used for contributers role and there author page info with post.
Version: 1.0
Author: Dhaval Kasavala
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: contributer-listing
*/


if(!defined('ABSPATH')) {
	die;
}

require_once(plugin_dir_path(__FILE__).'includes/class-contributers-metabox.php');

function cwp_run_contributers() {
	new cwp_contributers_metabox();
}
add_action('init','cwp_run_contributers');

