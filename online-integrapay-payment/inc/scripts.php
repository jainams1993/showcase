<?php
// Main Styles
function online_integrapay_main_styles() {
	// Register 
	wp_register_style("online-main",plugin_dir_url('') . "online-integrapay-payment/assets/css/online-integrapay.css", null, null);

	// Enqueue
	wp_enqueue_style('online-main');
	wp_enqueue_style('style', get_stylesheet_uri(), null, null);	
	

	if ( function_exists('proto_selection') ) {
		wp_add_inline_style( 'online-main', proto_selection() );
	}
}

add_action('wp_enqueue_scripts', 'online_integrapay_main_styles');

// Main Scripts
function online_integrapay_js() {
	
	if (!is_admin()) {
		// Register 
		wp_register_script('onlineintegrapay-js', plugin_dir_url('') .'online-integrapay-payment/assets/js/js.js', array('jquery'), null, TRUE);		
		// Enqueue
		if ( is_page_template( 'onlineintegrapay.php' ) ) {
			wp_enqueue_script('onlineintegrapay-js');
		}
		//wp_enqueue_script('online_integrapay');
		wp_localize_script( 'onlineintegrapay-js', 'themeajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		?>
		 <script type="text/javascript">
    		var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";

    	</script>
		<?php	
	}
}
add_action('wp_enqueue_scripts', 'online_integrapay_js');
?>