<?php 
	add_shortcode('reviewlist','reviewlist');
	function reviewlist($data)
	{
		$query_args = array(
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

		$reviews = new WP_Query($query_args);
		if($reviews->posts){
			$reviews_count = $reviews->post_count; 
			$count = 0;
		 	foreach ($reviews->posts as $skey => $sv) {
		 		$total_reviews = get_post_meta($sv->ID,'total_reviews',true);
				$count += $total_reviews  / 5;  
			}

			$score = round($count/ $reviews_count,1);
            if(count($reviews->posts) == 0){
                $count_point = "0";
            }else{ 
                $count_point =  round($count/count($reviews->posts),2);
            }  
			
			$result = '<div class="dealer-profile">
						<div class="reviews_'.$data['user_id'].' dealer-reting"></div>
						<div class="dealer-view">
							<span class="review-number"> ('.$count_point.')</span>
							<span>'.$reviews->post_count.' review</span>
						</div>
						<script type="text/javascript">
							var rId  =  '.$data['user_id'].'
							jQuery(".reviews_"+rId).raty({
						  		number: 5,
						  		starOn: paths+"img/star-on.png", 
						  		starOff: paths+"img/star-off.png",
						  		starHalf: paths+"img/star-half.png",
						  		readOnly: true,
						  		score:'. $score .',
							});
						</script>
					</div>';
		}else{
			$result = '';
		}
	return $result;

	}

	add_shortcode('reviewlistaverage','reviewlistaverage');

	function reviewlistaverage($data){
		
		global $ss;
		$step = array(
				'key' 		=> 'membership_current_step',
				'value' 	=> '-1',
				'compare' 	=> '=',
			);
		$args = array(
			'role'      => 'cardealer',
			'meta_query' => array(
							'relation' => 'AND',
							$step,
						),
		);
		$wp_user_query 	= new WP_User_Query( $args );

		$authors 		= $wp_user_query->get_results();
		$id = array();
		foreach ($authors as $key => $value) {
			$id[] = $value->ID;
		}

		foreach ($id as $key => $value) {
			$query_args = array(
				'post_type'  => 'reviews',
				'order'      => 'DESC',
				'post_status'=> 'publish',
				'posts_per_page' => '-1',
				'meta_query' => array(
					array(
						'key'     => 'dealer_id',
						'value'   => $value,
					),
				),
			);
			$reviews = new WP_Query($query_args);
			if($reviews->posts){
				$reviews_count = $reviews->post_count;
				$count = 0;
				foreach ($reviews->posts as $skey => $sv) {
			 		$count += (int)get_post_meta($sv->ID,'rar_score',true); 
				}
				$score = round($count/ $reviews_count,1);
				$ss[$value] = $score;
			}
		}

		$reviews = new WP_Query($query_args);
		
		arsort($ss);
		ob_start();
			require_once( dirname( MEMBERSHIP ) . '/inc/Templates/user-listing-map/avg_listing.php' );
		return ob_get_clean();
	}

?>
