<?php
/**
* Plugin Name: One Click Purchase
* Plugin URI: https://github.com/shaazlarik/one-click-purchase
* Author: Coding Perks
* Author URI: https://github.com/shaazlarik/one-click-purchase
* Description: Adding one click purchase feature
* Version: 1.0.1
* Licence: GPL2 or Later
* License URL: http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
* Text Domain: one-click-purchase
**/

// defined( 'ABSPATH' ) or die('Please get proper access');

// Include our updater file
if( ! class_exists( 'One_click_purchase_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
}

$updater = new One_click_purchase_Updater( __FILE__ );
$updater->set_username( 'shaazlarik' );
$updater->set_repository( 'one-click-purchase' );

$updater->authorize( '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' ); // Your auth code goes here for private repos

$updater->initialize();


// check for clear-cart get param to clear the cart, append ?clear-cart to any site url to trigger this
add_action( 'init', 'one_click_purchase' );

function one_click_purchase() {
	$pid = $_GET['pid'];
	if ( isset( $_GET['pid'] ) ) {		
		
		global $woocommerce;
		$woocommerce->cart->empty_cart();
		$woocommerce->cart->add_to_cart($pid);
		$product_cart_id = $woocommerce->cart->generate_cart_id( $pid );
		if( $woocommerce->cart->find_product_in_cart( $product_cart_id ) ){
			wp_safe_redirect( wc_get_checkout_url() );
		  exit();
		}
		
		
	}
}


// enable gutenberg for woocommerce
function activate_gutenberg_product( $can_edit, $post_type ) {
	if ( $post_type == 'product' ) {
		   $can_edit = true;
	   }
	   return $can_edit;
   }
   add_filter( 'use_block_editor_for_post_type', 'activate_gutenberg_product', 10, 2 );
   
   // enable taxonomy fields for woocommerce with gutenberg on
   function enable_taxonomy_rest( $args ) {
	   $args['show_in_rest'] = true;
	   return $args;
   }
   add_filter( 'woocommerce_taxonomy_args_product_cat', 'enable_taxonomy_rest' );
   add_filter( 'woocommerce_taxonomy_args_product_tag', 'enable_taxonomy_rest' );
