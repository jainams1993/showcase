<?php
add_action('wp_head','addReviewsAndRatingStyle');
function addReviewsAndRatingStyle(){ 
	wp_register_style('customs-css',plugins_url() . '/reviews-and-rating/css/custom.css');
	wp_enqueue_style( 'customs-css' );
?>
	<script type="text/javascript" src="<?= plugins_url()?>/reviews-and-rating/js/jquery.raty.js"></script>
	<script type="text/javascript">
		var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
		var paths    = "<?php echo plugin_dir_url( __FILE__ );?>";
		jQuery('#custom').raty({
		  	number:     5,
		  	starOn: paths+'img/star-on.png', 
		  	starOff: paths+'img/star-off.png',
		});
	</script>
<?php }

add_action('wp_footer','addReviewsAndRatingScripts');
function addReviewsAndRatingScripts(){ 
    wp_enqueue_script( 'custom-js', plugins_url() . '/reviews-and-rating/js/custom.js', array( 'jquery' ), '1.0', true );
}

function admin_js_css() { 
	wp_enqueue_script( 'admin_custom-js', plugins_url() . '/reviews-and-rating/js/admin_custom.js', array( 'jquery' ), '1.0', true );
?>
	<script type="text/javascript" src="<?= plugins_url()?>/reviews-and-rating/js/jquery.raty.js"></script>
	<script type="text/javascript">
		var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
		var paths    = "<?php echo plugin_dir_url( __FILE__ );?>";
		jQuery('#custom').raty({
		  	number:     5,
		  	starOn: paths+'img/star-on.png', 
		  	starOff: paths+'img/star-off.png',
		});
	</script>
    
<?php
	echo '<style type="text/css">';
    echo '.column-rating { text-align: center; width:30% !important; overflow:hidden }';
    echo '</style>';
}
add_action('admin_head', 'admin_js_css');
?>