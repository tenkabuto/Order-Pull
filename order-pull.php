<?php
/*
Plugin Name: Order Pull
Version: 0.1
Plugin URI: https://github.com/tenkabuto/order-pull
Author: Brandon Hall
Author URI: http://brandon.zeroqualms.net
Description: Outputting only the order info you need so you can get back to working your magic.
*/
global $wp_version;
  
$exit_msg = 'WP Delish requires WordPress 2.8 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare($wp_version, "2.8", "<")) {
  exit($exit_msg);
}

class OrderPull {
	var $plugin_url;
	
	// For the time being, let's effectively setup a useless page
	function page_handler() {
		// include('order-pull-page.php');
		print "Hello world!";
	}
	
	// Initialize the plugin
	function OrderPull() {
		$this->plugin_url = trailingslashit( WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__) ));
		
		// Add page
		add_action('admin_menu', array(&$this, 'admin_menu'));
	}
	
	// Hook the options page
	function admin_menu() {
		// The designation of add_MANAGEMENT_page causes the menu item to be listed under the Tools menu!
		add_management_page('Order Pull Output', 'Order Pull', 'edit_posts', basename(__FILE__), array(&$this, 'page_handler'));
	}
}

// Create a new instance of the class
$OrderPull = new OrderPull();
if (isset($OrderPull)) {
	// Register the activation function by passing the reference to my instance
	register_activation_hook( __FILE__, array(&$OrderPull, 'OrderPull') );
}
?>