<?php
/*
Plugin Name: Bank Integrapay Payment
Plugin URI: 
Description: Bank Integrapay Payment
Version: 1.0
Author: Zaksy Vision
Author URI: https://www.zaksyvision.com/
*/

if ( ! defined( 'BANKINTEGRAPAY' ) ) {
	define( 'BANKINTEGRAPAY_PATH', plugin_dir_path( __FILE__ ) );
	define( 'BANKINTEGRAPAY_URL', plugin_dir_url( __FILE__ ) );
}	

// Creating custom table bankintegrapay
register_activation_hook ( __FILE__, 'on_activate' );
function on_activate() {
    global $wpdb;
	$bankintegrapay = $wpdb->prefix . 'bankintegrapay';
	$sql = '';
	
	if($wpdb->get_var("show tables like 'bankintegrapay'") != $bankintegrapay) {
		$sql .= "CREATE TABLE " . $bankintegrapay . " (
			id INT(11) NOT NULL AUTO_INCREMENT,
			resultPayerID VARCHAR(50) NULL,
			payerUniqueID VARCHAR(50) NULL,
			payerDisplayID VARCHAR(50) NULL,
			payerFirstName VARCHAR(255) NOT NULL,	
			payerLastName VARCHAR(255) NOT NULL,	
			payerAddressStreet VARCHAR(255) NOT NULL,
			payerAddressSuburb VARCHAR(255) NOT NULL,
			payerAddressState VARCHAR(255) NOT NULL,
			payerAddressPostCode VARCHAR(255) NOT NULL,
			payerAddressCountry VARCHAR(255) NOT NULL,
			payerEmail VARCHAR(255) NOT NULL,
			payerPhone VARCHAR(255) NOT NULL,
			payerMobile VARCHAR(255) NOT NULL,
			invoice VARCHAR(255) NOT NULL,
			token VARCHAR(255) NOT NULL,
			status INT(11) NOT NULL,
			date TIMESTAMP NOT NULL,
			mail INT(3),	
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}

add_action( 'wp_enqueue_scripts', 'load_custom_scripts' );
function load_custom_scripts() {
    $path_js = BANKINTEGRAPAY_URL.'assets/js/custom.js';
	wp_register_script('custom_js',$path_js, array('jquery'),'1.1', true);
	wp_enqueue_script('custom_js');

	wp_register_style('custom_css',BANKINTEGRAPAY_URL.'assets/css/custom.css');
	wp_enqueue_style('custom_css');
	// wp_localize_script( 'inquiry_popup_js', 'custom_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

// Creating shortcode for form fields and ajax to save it
add_shortcode( 'bank_integrapay','bank_integrapay_callback' );
function bank_integrapay_callback() { 
    ob_start();
    // $file11 = include(STYLESHEETPATH.'/template_screenings_Previous.php');
    require_once( BANKINTEGRAPAY_PATH . 'inc/template/payment_form.php' );
    return ob_get_clean();
}

require_once( BANKINTEGRAPAY_PATH . 'inc/ajax/payment_form_ajax.php' );

add_shortcode( 'schedule-bank-payments','schedule_bank_form' );
function schedule_bank_form(){
	ob_start();
    require_once( BANKINTEGRAPAY_PATH . 'inc/template/schedule_payment_form.php' );
    return ob_get_clean();
}

require_once( BANKINTEGRAPAY_PATH . 'inc/ajax/schedule_payment.php' );

// Register menu page in admin backend to display list of bank_integrapay user 
require_once( BANKINTEGRAPAY_PATH . 'admin/register_bank_integra_page.php' );

?>