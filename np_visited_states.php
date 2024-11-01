<?php
/*
Plugin Name: Visited US States
Plugin URI: http://www.p3ck.us/visited_us_states/
Description: Utilizes AMMap to keep track of visited US States and displays a map via shortcode.
Version: 1.0.3
Author: Nic P
Author URI: http://www.p3ck.us
License: 
License URI: 
*/

defined('ABSPATH') or die("No script kiddies please!");
define('vus_plugin_path', plugin_dir_path(__FILE__)); 
define('vus_ammap_url', plugins_url( "ammap/", __FILE__ ));
require_once vus_plugin_path . 'inc/vus_map.php';
require_once vus_plugin_path . 'inc/vus_settings_page.php';
require_once vus_plugin_path . 'inc/vus_states_page.php';

add_action( 'admin_init', 'register_vus_settings' );
add_action( 'admin_menu', 'vus_settings_menu' );
add_shortcode('visited_states', 'vus_show_map');

register_activation_hook( __FILE__,  'vus_activate'  );
register_deactivation_hook( __FILE__, 'vus_deactivate' ) ;
register_uninstall_hook( __FILE__,  'vus_uninstall'  );

function register_vus_settings() {
	register_setting( 'vus_visited_states', 'vus_states' );
  register_setting( 'vus_settings', 'vus_settings', 'vus_settings_validate' );
  add_settings_section('vus_settings_main', 'States Settings', 'vus_settings_callback', 'vus_settings_section');
	add_settings_field('vus_theme', 'Theme', 'vus_setting_theme', 'vus_settings_section', 'vus_settings_main');
	add_settings_field('vus_waterColor', 'Water Color', 'vus_setting_waterColor', 'vus_settings_section', 'vus_settings_main');
  add_settings_field('vus_color', 'Unvisited Color', 'vus_setting_color', 'vus_settings_section', 'vus_settings_main');
  add_settings_field('vus_selectedColor', 'Visited Color', 'vus_setting_selectedColor', 'vus_settings_section', 'vus_settings_main');
  add_settings_field('vus_outlineColor', 'Outline Color', 'vus_setting_outlineColor', 'vus_settings_section', 'vus_settings_main');
  add_settings_field('vus_rollOverColor', 'Roll Over Color', 'vus_setting_rollOverColor', 'vus_settings_section', 'vus_settings_main');
  add_settings_field('vus_rollOverOutlineColor', 'Roll Over Outline Color', 'vus_setting_rollOverOutlineColor', 'vus_settings_section', 'vus_settings_main');
} 

// Add options to DB on activate
function vus_activate(){
   add_option('vus_states');
   add_option('vus_plugin_options' );
   vus_check_defaults();
}

function vus_deactivate(){

}

// Delete options from DB on uninstall
function vus_uninstall(){
	delete_option('vus_states');
}


// Create options page
function vus_settings_menu() {
  add_menu_page( 'Visited US States', 'Visited US States', 'manage_options', 'vus-settings', '' );
  add_submenu_page('vus-settings', 'Settings', 'Settings', 'manage_options', 'vus-settings', 'vus_settings_page');
	add_submenu_page( 'vus-settings', 'States', 'States', 'manage_options', 'vus-visited-states', 'vus_states_page' );
}
        

$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'vus_plugin_settings_link' );

                   
	// Add settings link on plugin page
	function vus_plugin_settings_link($links) { 
  	$settings_link = '<a href="admin.php?page=vus-settings">Settings</a>'; 
  	array_unshift($links, $settings_link); 
  	return $links; 
	}
 
?>