<?php

/*
Plugin Name: Online Integrapay Payment
Plugin URI: 
Description: Online Integrapay Payment
Version: 1.0
Author: Zaksy Vision
Author URI: https://www.zaksyvision.com/
*/
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
if ( ! defined( 'ONLINEINTEGRAPAY' ) ) {
	define( 'ONLINEINTEGRAPAY', __FILE__ );
	global $wpdb;
	$onlineintegrapay = $wpdb->prefix . 'onlineintegrapay';
	$sql = '';
	
	if($wpdb->get_var("show tables like '$onlineintegrapay'") != $onlineintegrapay) {
		$sql .= "CREATE TABLE " . $onlineintegrapay . " (
		`invoice_id` INT(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(255) NOT NULL,	
		`email` VARCHAR(255) NOT NULL,	
		`address` VARCHAR(255) NOT NULL,	
		`phone` VARCHAR(255) NOT NULL,	
		`invoice` VARCHAR(255) NOT NULL,	
		`amount` VARCHAR(255) NOT NULL,	
		`token` VARCHAR(255) NOT NULL,	
		`status` INT(11) NOT NULL,
		`date`  timestamp,	
		`mail`  INT(3) NOT NULL,
		`payment_method` INT(3) NOT NULL,	
		PRIMARY KEY(invoice_id)
		);";
	}

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$message = 'Plugin activated.';
}
require_once( dirname( ONLINEINTEGRAPAY ) . '/onlineintegrapay.php' );