<?php
add_shortcode( 'ReviewRatingForm','reviewsDetails' );
add_shortcode( 'ReviewForm','reviewsForm' );
function reviewsDetails($data){
	global $post;
	$current_user = wp_get_current_user();
	$userID = get_query_var('args');
	echo do_shortcode('[ReviewForm]');
	for ($i=1; $i <= 5 ; $i++) {  ?>
			<script type="text/javascript">
				jQuery('.main_rating<?= $i; ?>').raty({
				  	number:     5,
				  	starOn: paths+
				  	'img/star-on.png', 
				  	starOff: paths+'img/star-off.png',
				});
			</script>
<?php	}

	$query_args = array(
		'post_type'  => 'reviews',
		'order'      => 'DESC', 
		'post_status'=> 'publish',
		'posts_per_page' => '-1',
		'meta_query' => array(
			array(
				'key'     => 'dealer_id',
				'value'   => $data['userid'],
				'compare'  => '='
			),
		),
	);

	$reviews = new WP_Query($query_args);
	$reviews_count = count($reviews->posts);
	$count = 0;
 	foreach ($reviews->posts as $skey => $sv) {
 		$total_reviews = get_post_meta($sv->ID,'total_reviews',true);
		$count += $total_reviews  / 5; 
	}
?>
	<?php if($reviews->post){	?>
	<div class="content_reating_review">
		<div id="reviewsBox" class="single-content-box">
            <div class="title">
                <i class="fa fa-star" aria-hidden="true"></i>
                <span>
	                <?php 
	                	if ($reviews_count > 1) {
	                		echo "Reviews";
	                	}else{
	                		echo "review";
	                	}
	                ?>
                </span>
            </div>
            <div class="content">
				<div class="review-post">
					<div class="col-md-6">
						<div class="review-count"><span><?php echo round($count/count($reviews->posts),1); ?></span><span>/5</span></div>
						<div class="averagerating"></div>
						<script type="text/javascript">
							jQuery('.averagerating').raty({
							  	number: 5,
							  	starOn: paths+'img/star-on.png', 
							  	starOff: paths+'img/star-off.png',
							  	starHalf: paths+'img/star-half.png',
							  	readOnly: true,
							  	score:<?php echo $count/count($reviews->posts); ?> 
							});
						</script>
					</div>
					<div class="col-md-6">
						<sapn class="review-count"><?= $reviews_count ?></sapn>
						<sapn class="review-text">
							<?php 
			                	if ($reviews_count > 1) {
			                		echo "Reviews";
			                	}else{
			                		echo "review";
			                	}
			                ?>
						</sapn>
					</div>
					<?php
					if ( is_user_logged_in() ) { 
						if ($userID != $current_user->ID) { ?>
							<div class="col-md-12">
								<button class="review-toggle-btn" id="review_button" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><?php _e('Rate Car Dealer','review_rating');?></button>
							</div>
						<?php }	?>	
					<?php }else{ ?>
							<div class="col-md-12">
								<a href="#" data-toggle="modal" data-target="#loginPopup" id="contact_box_login_form" class="review-toggle-btn"><?php _e('Rate Car Dealer','review_rating');?></a>
	                    	</div>
					<?php }	?>	
				</div>	
				<hr>
				<div class="review_container" id="reviews_container">
					<?php 
						$i = 0;
						foreach ($reviews->posts as $key => $value) {	
							 	$total_reviews = get_post_meta($value->ID,'total_reviews',true);
								$score = $total_reviews  / 5;
					?>
							<div class="review_data">
								<div class="review-user"><?= current(get_post_meta($value->ID,'rar_name')); ?></div>
								<div class="review-star-box">
									<div class="reviews_<?php echo $value->ID; ?>"></div>
									<script type="text/javascript">
										var rId  =  <?php echo $value->ID ?>;
										jQuery('.reviews_'+rId).raty({
										  	number: 5,
										  	starOn: paths+'img/star-on.png', 
										  	starOff: paths+'img/star-off.png',
										  	starHalf: paths+'img/star-half.png',
										  	readOnly: true,
										  	score: <?php echo $score; ?>,
										});
									</script>
								</div>

								<div class="multirating-group">
									<div class="multirating">
										<span>Prompt and courteous</span>
										<div class="main_rating1_<?= $value->ID; ?> rar_score"></div>
									</div>

									<div class="multirating">
										<span>Clean and professional</span>
										<div class="main_rating2_<?= $value->ID; ?> rar_score"></div>
									</div>

									<div class="multirating">
										<span>Thoroughly reviewed vehicle features</span>
										<div class="main_rating3_<?= $value->ID; ?> rar_score"></div>
									</div>
									<div class="multirating">
										<span>Honest</span>
										<div class="main_rating4_<?= $value->ID; ?> rar_score"></div>
									</div>
									<div class="multirating">
										<span>Overall Sales Partner rating</span>
										<div class="main_rating5_<?= $value->ID; ?> rar_score"></div>
									</div>
								</div>
								<?php			
								for ($i=1; $i <= 5 ; $i++) { 
										$rar_score = get_post_meta( $value->ID, 'rar_score_'.$i, true );		?>	
											<script type="text/javascript">
												jQuery(document).ready(function(){
													jQuery('.main_rating<?= $i; ?>_<?= $value->ID; ?>').raty({
													  	number: 5,
													  	starOn: paths+'img/star-on.png', 
													  	starOff: paths+'img/star-off.png',
													  	starHalf: paths+'img/star-half.png',
													  	readOnly: true,
													  	score: <?php echo $rar_score; ?>,
													});
												});
											</script>
								<?php	} ?>
								<div class="review-publish-box">
									<?php _e('Published on','review_rating');?> <?= $value->post_date  ?>
								</div>
								<div class="review-title"><?= get_the_title($value->ID) ?></div>
								<blockquote class="review-content"><p><?=  get_post_field('post_content',$value->ID)  ?></p></blockquote>
							</div>
					<?php 
						$i++;
					} ?>
				</div>

			<?php
			 if(count($reviews->posts) > intval(get_option('load_review_more')) ){ ?>
				<a class="load_more_review" ><?php _e('Load More Reviews','review_rating'); ?>(<span class="load_review_count"><?= count($reviews->posts) - intval(get_option('load_review_more')) ; ?></span>)</a>
			<?php } ?>

			</div>
				
		</div>
	</div>
	<?php } else { 
		global $post;
	
		if ($userID != $current_user->ID) { ?>
			<div id="reviewsBox" class="single-content-box">
				<div class="title">
		            <i class="fa fa-star" aria-hidden="true"></i>
		            <span>Review</span>
		        </div>
		        <div class="content">            	
					<div class="row">
						<div class="col-lg-12">

							<div class="review-post">
								<div class="review-list-btn">
									<button class="review-toggle-btn"  id="review_button" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><?php _e('Write your own review','review_rating');?></button>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
	<?php }	
	} 
}

function reviewsForm(){ 
	global $post; 
	$user = get_query_var('args');
	$user = get_user_by( 'email', $user );
	$user = $user->data->ID; ?>
	
		<div class="modal fade cp-modal" id="review_model" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-big-title"><?php _e('Submit your review','review_rating');?></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
					</div>
					<div class="modal-body">
						<div class="modal-content-box">
							<div class="success" id="success"></div>
							<form role="form" method="post" id="reviews_and_rating_form" name="reviews_and_rating_form" enctype="multipart/form-data">
								<div class="row-penal">
									<div class="column-md-6">
										<div class="form-group">
											<div class="form-group">
												<input type="text" placeholder="<?php _e('Name','review_rating');?>"  name="rar_name" class="form-control rar_name" />
												<span class="error" id="rar_name"></span>
											</div>
										</div>
									</div>
									<div class="column-md-6">
										<div class="form-group">
											
											<input type="text" class="form-control rar_title" name="rar_title" placeholder="<?php _e('Review title','review_rating');?>" />
											<span class="error" id="rar_title"></span>
										</div>
									</div>
									<div class="column-md-12">
										<div class="form-group rating-group">
											<span>Prompt and courteous</span>
											<div id="" class="main_rating1 rar_score"></div>
											<span class="error" id="custom0"></span>
										</div>

										<div class="form-group rating-group">
											<span>Clean and professional</span>
											<div id="" class="main_rating2 rar_score"></div>
											<span class="error" id="custom1"></span>
										</div>

										<div class="form-group rating-group">
											<span>Thoroughly reviewed vehicle features</span>
											<div id="" class="main_rating3 rar_score"></div>
											<span class="error" id="custom2"></span>
										</div>
										<div class="form-group rating-group">
											<span>Honest</span>
											<div id="" class="main_rating4 rar_score"></div>
											<span class="error" id="custom3"></span>
										</div>
										<div class="form-group rating-group">
											<span>Overall Sales Partner rating</span>
											<div id="" class="main_rating5 rar_score"></div>
											<span class="error" id="custom4"></span>
										</div>
									</div>
									<div class="column-md-12"> 
										<div class="form-group">
											
											<textarea name="rar_review" class="form-control" placeholder="Your review"></textarea>
											<span class="error" id="rar_review"></span>
										</div>							
									</div>
									<div class="column-md-12">
										<div class="row-penal">
											<div class="column-md-6">
													
											<div class="form-btn-group">
                                                <button type="submit" name="transaction_reset_name" class="btn contactform-btn" id="transaction_reset_name">
                                                    <span><?= __('Submit','wpestate') ?></span>
                                                    <span class="loading-box">
                                                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                                                        
                                                    </span>
                                                    <span class="success-box">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </span>
                                                </button>
                                            </div>

											</div>
											<div class="column-md-6">
												<button  type="reset" id="cancel" class="btn contactform-btn btn-cancel"><?php _e('Cancel','review_rating');?></button>
											</div>
											<input type="hidden" name="login_user_id" value="<?= get_current_user_id(); ?>">
											<input type="hidden" name="dealer_id" value="<?= $user ?>">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>		
<?php 
}
add_action('wp_footer', 'load_more_pagination');
function load_more_pagination(){ ?> 
	<script type="text/javascript">
		var load_review_more = "<?= intval(get_option('load_review_more')) ?>";
		jQuery(".reviews_container .review_data").not('.review_data:nth-child(-n+'+load_review_more+')').addClass('hidden');
		
		jQuery(".load_more_review").on("click", function () {
		    jQuery(".review_container").find(".review_data:hidden:lt("+load_review_more+")").removeClass('hidden');
		    jQuery('.load_review_count').html(jQuery('.review_data.hidden').length);
		    if(jQuery('.review_data.hidden').length == 0){
		    	jQuery('.load_more_review').remove();
		    }
		});
	</script>	
<?php } ?>