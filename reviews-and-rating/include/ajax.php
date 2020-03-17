<?php 

add_action('wp_ajax_nopriv_custom_paginations_reviews_list','custom_paginations_reviews_list');
add_action('wp_ajax_custom_paginations_reviews_list', 'custom_paginations_reviews_list');
function custom_paginations_reviews_list(){
	ob_start();
	$params = array();
	parse_str($_POST['data'],$params);
	
	if(empty($params['dealer_review_form_date'])){
		$params['dealer_review_form_date'] = null;
	}

	if(empty($params['dealer_review_to_date'])){
		$params['dealer_review_to_date'] = null;
	}
	
	if(empty($params['dealer_review_name'])){
		$name = null;
	}else{	
		$name  = array(
		    	'key'     => 'rar_name',
				'value'   => $params['dealer_review_name'],
	 			'compare' => 'LIKE',
		    );
	}
	
	$user_id = array(
		'key'     => 'dealer_id',
		'value'   => $params['user_id'],
	);
	$query_args = array(
			'post_type'  => 'reviews',
			'order'      => 'DESC',
			'post_status'=> 'publish',
			'posts_per_page' => '10',
			'paged'          => $_POST['paged'],
			'meta_query' => array(
				'relation' => 'AND',
				$name,
				$user_id,
			),
			'date_query' => array(
						        array(
						            'after'     => $params['dealer_review_form_date'],
						            'before'    => $params['dealer_review_to_date'],
						            'inclusive' => true,
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
				'relation' => 'AND',
				$name,
				$user_id,
			),
			'date_query' => array(
				        array(
				            'after'     => $params['dealer_review_form_date'],
				            'before'    => $params['dealer_review_to_date'],
				            'inclusive' => true,
				        ),
				    ),
		);
	$count_review = new WP_Query($count_query);
	
	if ($reviews->posts) {
		require_once(REVIEWS_PLUGIN_DIR.'include/review_list.php') ; 
	}else{
		echo "<h4 class='text-center'>No Reviews</h4>";
	}
		if (isset($_POST['paged'])) {
			$paged = $_POST['paged'];
		}else{
			$paged = 1;
		}

	if ($count_review->post_count > 10 ) {
		echo custom_paginations_reviews($reviews->max_num_pages, $range = 2,$paged,'reviews_pagination'); 
	}

	$html = ob_get_clean();
	$response = array('type' => 'success', 'html' => $html );
	wp_send_json($response); 
}

add_action('wp_ajax_nopriv_reviewsAjax','reviewsAjax');
add_action('wp_ajax_reviewsAjax','reviewsAjax');
function reviewsAjax(){
	$error = validate($_POST);
 	if($error){
 		$response = array(
            'type' => 'failure',
            'html' => $error
		);
		wp_send_json_success($response);
	}else{ 
		$newpost = array(
			'post_author' => 1,
			'post_date' => date('Y-m-d H:i:s'),
			'post_content' => nl2br($_POST['rar_review']),
			'post_status' => 'pending', 
			'post_title' => $_POST['rar_title'], 
			'post_type' => 'reviews'
		);
		$newpostid = wp_insert_post($newpost, true);
		
		update_post_meta($newpostid, 'rar_review_ip', $_SERVER['REMOTE_ADDR']);
		update_post_meta($newpostid, 'login_user_id', $_POST['login_user_id']);
		update_post_meta($newpostid, 'dealer_id', $_POST['dealer_id']);


			if (isset($_POST['score'])) {
				$i = 1;
				$total = 0;
				foreach ($_POST['score'] as $key => $value) {
					if (!empty($value)) {
						$total += $value;
						update_post_meta($newpostid,'rar_score_'.$i, $value);
					$i++;
					}else{
						update_post_meta($newpostid,'rar_score_'.$i, '');
					}
				}
					update_post_meta($newpostid,'total_reviews', $total);
			}

		if (isset($_POST['rar_name'])) { update_post_meta($newpostid,'rar_name', $_POST['rar_name']); }
		if (isset($_POST['rar_email'])) { update_post_meta($newpostid,'rar_email', $_POST['rar_email']); }
		if (isset($_POST['rar_review'])) { update_post_meta($newpostid,'rar_review', $_POST['rar_review']); }

		$response = array(
            'type' => 'success',
            'html' =>  __('Thank you for submit your review','review_rating'),
		);
		wp_send_json_success($response); 
	}
}


add_action('wp_ajax_nopriv_aprove_review','aprove_review');
add_action('wp_ajax_aprove_review','aprove_review');
function aprove_review(){
	global $wpdb;
	
	$res = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_status = 'publish' WHERE ID = %d",$_POST['post_id'] ) ); 
	if ($res) {
		$response = array('type' => 'success', 'msg' => 'Approved' );
		wp_send_json($response);
	}
exit();
}
?>