<?php 	
	get_header();	
	if(isset($_GET['webPageToken'])){
		require_once(dirname( ONLINEINTEGRAPAY ) .'/inc/class.php');
		$onlineIntegrapay = new OnlineIntergrapay();
		$webPageToken= $onlineIntegrapay->responseToken($_GET['webPageToken']);
		global $wpdb;
	    if($webPageToken['result'] == 'OK')
	    {
	    	if($webPageToken['resultID'] == 'C'){
	       		$wpdb->query("UPDATE {$wpdb->prefix}onlineintegrapay SET status ='2' 
	       			WHERE token ='".$_GET['webPageToken']."'");

	    	?>
	    	<script type="text/javascript">
	    		window.location.href = '<?= site_url('online-integrapay-payment'); ?>';
	    	</script>
	    	<?php } 
	    	else{ ?>
		    	<div class="container woocommerce integrapay-container">
		    		<div class="thanku-msg">
			    		<h1 class="thanku-title"><?= __('Thank You !','integrapay_payment') ?></h1>
			    		<h5><?= __('for choosing','integrapay_payment') ?> <span><?= __('Test','integrapay_payment') ?></span></h5>
			    		<div class="thanku-img">
			    			<img src="<?= plugin_dir_url('') ?>woocommerce-integrapay-payment/images/images.png" width="200" height="200">
			    		</div>
			    		<div class="thanku-content">
				    		<?= __('We have sent you a confirmation email at','integrapay_payment') ?>(<?= $webPageToken['payerEmail'] ?>) <?= __('having all information about your recent transaction.','integrapay_payment') ?>
				    		<br />
				    		
			    		</div>
			    	</div>
					
					<p class="order">
					
					</p>
				</div>
	    	<?php 
	    	}
		}else{ ?>
			<div class="container woocommerce integrapay-container">
				<div class="thanku-img">
		    		<img src="<?= plugin_dir_url('') ?>woocommerce-integrapay-payment/images/danger.png" width="200" height="200">
		    	</div>
		    	<div class="thanku-content">
		    		<a href="site_url('online-integrapay-payment')"><?= __('please Try again' ,'integrapay_payment') ?></a>
		    		<br />
	    		</div>
			</div>
		<?php }
	}else { ?>
		<div class="container woocommerce integrapay-container">
			<div class="thanku-img">
	    		<img src="<?= plugin_dir_url('') ?>woocommerce-integrapay-payment/images/danger.png" width="200" height="200">
	    	</div>
	    	<div class="thanku-content">
	    		<?= __('something went wrong' ,'integrapay_payment') ?>
	    		<br />
    		</div>
		</div>
	<?php }	
get_footer();
?>