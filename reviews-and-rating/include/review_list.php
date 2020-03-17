	<div class="content_reating_review">
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