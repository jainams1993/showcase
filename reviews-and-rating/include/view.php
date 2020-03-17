<?php 

add_shortcode('view_dealer_reviews','dealer_reviewlist');
function dealer_reviewlist($data)
{
	$current_user = wp_get_current_user();
	$query_args = array(
			'post_type'  => 'reviews',
			'order'      => 'DESC',
			'post_status'=> 'publish',
			'posts_per_page' => '10',
			'paged' 		=> '1',
			'meta_query' => array(
				array(
					'key'     => 'dealer_id',
					'value'   => $data['user_id'],
				),
			),
		);
	$reviews = new WP_Query($query_args);
	


	$count_query = array(
			'post_type'  => 'reviews',
			'order'      => 'DESC',
			'post_status'=> 'publish',
			'posts_per_page' => '-1',
			'meta_query' => array(
				array(
					'key'     => 'dealer_id',
					'value'   => $data['user_id'],
				),
			),
		);
	$count_review = new WP_Query($count_query);
	
	$post = $count_review->posts;
	$user_name = array();
	foreach ($post as $key => $value) {
		$user_name[] = get_post_meta($value->ID,'rar_name',true);
	}
?>
		<div class="container">
			<div class="row">
				<div class="column-lg-12 row_dasboard-prop-listing">
					<div class="car-holder full-height">
						<div class="car-holder-head">
							<div class="transaction_filters">
								<button type="button" class="mobile-filter-trigger">
                                    <label class="trigger-label">Filter</label>
                                    <div class="trigger-icon">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </div>
                                    <div class="clear"></div>
                                </button>
								<form method="post" id="dealer_review_filters" name="dealer_review_filters" class="filter-form"> 
						            <div class="col-md-3 form-group">
						                <input type="text" id="dealer_review_name" class="form-control" name="dealer_review_name" placeholder="<?= __('Title','dealer_review') ?>"> 
						            </div>
						            <div class="col-md-3 form-group">
						                <input type="text" id="dealer_review_form_date" class="form-control" name="dealer_review_form_date" placeholder="<?= __('From Date','wpestate') ?>"> 
						            </div>
						            <div class="col-md-3 form-group">
						                <input type="text" id="dealer_review_to_date" class="form-control"  name="dealer_review_to_date" placeholder="<?= __('To Date','wpestate') ?>"> 
						                <input type="hidden" name="user_id" value="<?= $data['user_id']; ?>"> 
						            </div>
						            
						            <div class="col-md-3">
						                <div class="full-box-wrapper">
						                    
						                    <div class="half-box">
						                        <div class="form-btn-group">
						                        <button type="button" id="filter_button" name="dealer_review_filters_name" class="btn btn-filter" onclick="review_filter.filter();">
													
													<span><i class="fa fa-filter" aria-hidden="true"></i><?= __('Filter','wpestate') ?></span>
													<span class="loading-box">
														<i class="fa fa-spinner fa-spin fa-fw"></i>
														
													</span>
													<span class="success-box">
														<i class="fa fa-check" aria-hidden="true"></i>
													</span>
												</button>
												</div>
						                    </div>

						                    <div class="half-box">
						                        <div class="form-btn-group">
						                        <button name="dealer_review_reset_name" id="reset_button" class="bbtn btn-reset" onclick="review_filter.refresh();">
													
													<span><i class="fa fa-refresh" aria-hidden="true"></i><?= __('Reset','wpestate') ?></span>
													<span class="loading-box">
														<i class="fa fa-spinner fa-spin fa-fw"></i>
														
													</span>
													<span class="success-box">
														<i class="fa fa-check" aria-hidden="true"></i>
													</span>
												</button>
												</div>
						                    </div>


						                </div>
						            </div>
						            <div class="clear"></div>
						        </form>
							</div>
						</div>
						<div class="car-holder-body">
                            <div class="holder-content">
		                        <div class="replacement_div">
						
						<?php  

						if ($reviews->posts) {
							require_once(REVIEWS_PLUGIN_DIR.'include/review_list.php') ;  
						}else{
							echo "<h4 class='text-center'>No Reviews</h4>";	
						}

						if ($count_review->post_count > 10 ) {		
							custom_paginations_reviews($reviews->max_num_pages, $range = 2,'1','reviews_pagination');
						}
						?>
								</div>
                            </div>
                        </div>
					</div>
				</div>
				
			</div>
		</div>
    <script type="text/javascript">
    	jQuery( function() {
    		var availableTags = <?= json_encode($user_name); ?>;
	    	jQuery( "#dealer_review_name" ).autocomplete({
	      		source: availableTags,
	      		select: function( event, ui ) {
	      			jQuery("#dealer_review_name").val(ui.item.label); // display the selected text
        			// jQuery("#user_id").val(ui.item.key);
	        		//userlistingmap.ajaxcall(null,true);
        		},
        		
	    	});
  		});
    </script>
<?php 		
	}
?>