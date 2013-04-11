<?php
/*
Plugin Name: Order Pull
Version: 0.1
Plugin URI: https://github.com/tenkabuto/order-pull
Author: Brandon Hall
Author URI: http://brandon.zeroqualms.net
Description: Outputting only the WooCommerce order info you need so you can get back to working your magic.
*/
global $wp_version;
  
$exit_msg = 'Order Pull requires both the plugin "WooCommerce" and WordPress 3.5 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if ( version_compare($wp_version, "3.5", "<") && class_exists('Woocommerce') ) {
  exit($exit_msg);
}

class OrderPull {
	var $plugin_url;
	
	// For the time being, let's effectively setup a useless page
	function page_handler() {
		print '<div class="wrap">
	<table id="orders" class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>
		<th scope="col" class="manage-column">First Name</th>
		<th scope="col" class="manage-column">Last Name</th>
		<th scope="col" class="manage-column">Company Name</th>
		<th scope="col" class="manage-column">Shipping Address 1</th>
		<th scope="col" class="manage-column">Shipping Address 2</th>
		<th scope="col" class="manage-column">City</th>
		<th scope="col" class="manage-column">State</th>
		<th scope="col" class="manage-column">ZIP Code</th>
	</tr>
	</thead>';
		
		$args = array(
			'post_type' => 'shop_order',
			'tax_query' => array(
				array(
					'taxonomy' => 'shop_order_status',
					'field' => 'slug',
					'terms' => 'processing'
				),
			'post_count' => '-1'
			)
		);

		query_posts( $args );
		global $wp_query;
		
		// the Loop
		while (have_posts()) : the_post();
?>
		<tr>
		<?php 
		// Many thanks to the answer [here](http://wordpress.org/support/topic/get-two-post-meta-keys-and-values-print-array) on how to use multiple keys by putting them in an array
		$meta_keys = array('_shipping_first_name', '_shipping_last_name', '_shipping_company', '_shipping_address_1', '_shipping_address_2', '_shipping_city');
		
		// And to the answer [here](http://devhints.wordpress.com/2006/10/21/php-proper-case-function/) for proper case functions
		foreach($meta_keys as $key) { $ship_shape = get_post_meta(get_the_ID(), $key); foreach ($ship_shape as $value) { echo "<td>".ucwords(strtolower($value))."</td>"; } }

		// These shouldn't be off-case
		$meta_keys = array('_shipping_state', '_shipping_postcode');
		foreach($meta_keys as $key) { $ship_shape = get_post_meta(get_the_ID(), $key); foreach ($ship_shape as $value) { echo "<td>".$value."</td>"; } }

		// To check custom field values: $custom_fields = get_post_custom(get_the_ID()); echo "<td>"; foreach ( $custom_fields as $key => $value ) { echo $key . " => " . $value . "<br />"; } echo "</td>"; ?></td>
		</tr>
<?php
		endwhile;
		
		print '</table>
		</div>';
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