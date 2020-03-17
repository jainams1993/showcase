<?php 
//File is use for store data and scheduled payment
function onlineintegrapayAjax(){
	require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/class.php' );
	$OnlineIntergrapay = new OnlineIntergrapay();
	$response = $OnlineIntergrapay->insertdata($_POST);
	wp_send_json_success($response); 
}
add_action('wp_ajax_onlineintegrapayAjax','onlineintegrapayAjax');
add_action('wp_ajax_nopriv_onlineintegrapayAjax','onlineintegrapayAjax');
?>