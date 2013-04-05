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

class OrderPull {}
?>