<?php
function create_post_type() {
	$labels = array(
		'name' 					=> __('Reviews', 'rar_reviews'),
		'singular_name' 		=> __('Reviews Item', 'rar_reviews'),
		'add_new' 				=> __('Add New', 'rar_reviews'),
		'add_new_item' 			=> __('Add New Reviews item', 'rar_reviews'),
		'edit_item' 			=> __('Edit Reviews item', 'rar_reviews'),
		'new_item' 				=> __('New Reviews item', 'rar_reviews'),
		'all_items' 			=> __('All Reviews items', 'rar_reviews'),
		'view_item' 			=> __('View Reviews item', 'rar_reviews'),
		'search_items' 			=> __('Search Reviews item', 'rar_reviews'),
		'not_found' 			=> __('No Reviews item found', 'rar_reviews'),
		'not_found_in_trash' 	=> __('No Reviews item found in Trash', 'rar_reviews'), 
		'parent_item_colon' 	=> '',
		'menu_name' 			=> __('Reviews', 'reviews'),

	);

	$args = array( 
		'labels'			=> $labels,
		'public'			=> true,
		'rewrite'			=> array( 'slug' => 'reviews' ),
		'menu_icon' 		=> plugins_url('star-on.png', __FILE__ ),
		'has_archive'   	=> true,
		'menu_position' 	=> 20,
		'capability_type' 	=> 'post',
		'supports'      	=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
		'capabilities' => array(
            				'create_posts' => 'do_not_allow',
       		 			),
        'map_meta_cap' => true, 
	);
register_post_type('reviews',$args);
}

add_action( 'init', 'create_post_type' );

add_filter( 'manage_edit-reviews_columns', 'edit_reviews_columns' ) ;
function edit_reviews_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' 	 => __( 'Reviews' ),
		'dealer_name'=> __( 'Dealer Name' ),
		'rating' 	 => __( 'Rating' ),
		'status'	 => __( 'Status' ),
		'date' 		 => __( 'Date' ),
	);
	return $columns;
}

add_action( 'manage_reviews_posts_custom_column', 'manage_reviews_columns', 10, 2 );
function manage_reviews_columns( $column, $post_id ) {
global $post;

	switch($column){
		case 'rating' :
				for ($i=1; $i <= 5 ; $i++) { 
						$rar_score = get_post_meta( $post_id, 'rar_score_'.$i, true );		?>	
							<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('.main_rating<?= $i; ?>_<?= $post_id; ?>').raty({
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
					

				<div class="multirating-group">
					<div class="multirating">
						<span>Prompt and courteous</span>
						<div class="main_rating1_<?= $post_id; ?> rar_score"></div>
					</div>

					<div class="multirating">
						<span>Clean and professional</span>
						<div class="main_rating2_<?= $post_id; ?> rar_score"></div>
					</div>

					<div class="multirating">
						<span>Thoroughly reviewed vehicle features</span>
						<div class="main_rating3_<?= $post_id; ?> rar_score"></div>
					</div>
					<div class="multirating">
						<span>Honest</span>
						<div class="main_rating4_<?= $post_id; ?> rar_score"></div>
					</div>
					<div class="multirating">
						<span>Overall Sales Partner rating</span>
						<div class="main_rating5_<?= $post_id; ?> rar_score"></div>
					</div>
				</div>	


		<?php 
			break;
		case 'dealer_name':
			$id = get_post_meta( $post_id, 'dealer_id', true );
			$user_info = get_userdata($id);
			$dealer_name    = getUserassociationName($id);
			echo '<a href="'.get_edit_user_link($user_info->ID).'">'.$dealer_name.'</a>';
			break;
		case 'status':
			$status = get_post_status( $post_id );
			if ($status == 'pending') { ?>
				<input type="button" name="approve_review" class="approve_review" id="approve_review_<?= $post_id?>" value="Approve" data-post_id="<?= $post_id; ?>" >		
		<?php	}else{
				echo "Approved";
			}
			break;
		}
}

function hd_add_buttons() {
    global $pagenow;
    if (is_admin()) {
        if ($_GET['post_type'] == 'reviews') {
            echo '<style>.page-title-action,#menu-posts-reviews ul.wp-submenu li:nth-child(3){display: none !important;}
            </style>';
        }
    }
}
add_action('admin_head', 'hd_add_buttons');

add_action('admin_init','add_review_load_settings');
function add_review_load_settings(){
		add_settings_field('load_review_more','Review on load','load_review_more_text','general','default');
}

function load_review_more_text(){
	settings_fields('general');
	echo '<input type="text" name="load_review_more" id="load_review_more" value="'.get_option('load_review_more').'" class="regular-text">';
}



add_action( 'add_meta_boxes', 'review_metabox' );
function review_metabox(){
	add_meta_box( 'reviews_metabox', 'Review Box', 'reviews_callback','', 'normal', 'high' );
}
function reviews_callback(){	
	global $post;

	for ($i=1; $i <= 5 ; $i++) { 
			$rar_score = get_post_meta( $post->ID, 'rar_score_'.$i, true );		?>	
				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('.main_rating<?= $i; ?>_<?= $post->ID; ?>').raty({
						  	number: 5,
						  	starOn: paths+'img/star-on.png', 
						  	starOff: paths+'img/star-off.png',
						  	starHalf: paths+'img/star-half.png',
						  	readOnly: true,
						  	score: <?php echo $rar_score; ?>,
						});
					});
				</script>
	<?php	} 	?>
	
<div class="multirating-group">
	<div class="multirating">
		<span>Prompt and courteous</span>
		<div class="main_rating1_<?= $post->ID; ?> rar_score"></div>
	</div>

	<div class="multirating">
		<span>Clean and professional</span>
		<div class="main_rating2_<?= $post->ID; ?> rar_score"></div>
	</div>

	<div class="multirating">
		<span>Thoroughly reviewed vehicle features</span>
		<div class="main_rating3_<?= $post->ID; ?> rar_score"></div>
	</div>
	<div class="multirating">
		<span>Honest</span>
		<div class="main_rating4_<?= $post->ID; ?> rar_score"></div>
	</div>
	<div class="multirating">
		<span>Overall Sales Partner rating</span>
		<div class="main_rating5_<?= $post->ID; ?> rar_score"></div>
	</div>
</div>

<?php } ?>