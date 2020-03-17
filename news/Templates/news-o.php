<?php 
/* Template Name: News */

get_header(); 
global $post;
$sidebar_settings = get_post_meta($post->ID,'sidebar_settings',true);
$sidebar_setting = json_decode($sidebar_settings);

$max_per_page = get_option( 'posts_per_page' );	
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$post_type = 'news';

$news_categories = get_terms(array(
    'taxonomy' => 'news-category',
    //'hide_empty' => false,
));

$preNewsCats = array();
foreach ($news_categories as $key => $value) {
	$preNewsCats[] = $value->slug;
}

$category_taxonomy = '';
$tag_taxonomy =  '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

if (isset($category) && $category) {
	if ($category != "all" && $category != "category" && in_array($category, $preNewsCats)) {
	    $category_taxonomy = array(
	        'taxonomy' => 'news-category',
	        'field' => 'slug',
	        'terms' => $category
	    );
	}
}

if (isset($_GET['tags']) && $_GET['tags']) {
	$tags = explode(',', $_GET['tags']);
	if (count($tags) > 0 && $_GET['tags'] != "all") {
	    $tag_taxonomy = array(
	        'taxonomy' => 'news-tag',
	        'field' => 'slug',
	        'terms' => $tags
	    );
	}
}

$taxonomy = array($category_taxonomy,$tag_taxonomy);

$args = array( 'post_type' => $post_type, 
			   'posts_per_page' => $max_per_page,
			   //'orderby'  => 'DESC',
			   'post_status'   => 'publish',
			   'paged' => $paged,
			   'meta_key'  => 'news_date_end_milli',
			   //'order'      => 'DESC',
			   'orderby'    => array('meta_value_num' => 'DESC','title' => 'ASC'),
			   'tax_query' => $taxonomy
			);

$loop = new WP_Query($args);
$total_posts = $loop->found_posts;
	
$news_tags = get_terms(array(
    'taxonomy' => 'news-tag',
    'hide_empty' => false,
));
?>


<div class="theme-page support-group-listing-template">

	<section class="page-col-section">
		<div class="page-col-inner">
			<div class="container">
				<div class="row flex-wrap-reverse">
					
					<div class="col-md-8">
						<div class="baf-section news-group-section">
							<div class="title-block">
								<h1><?= get_the_title() ?></h1>
							</div>
							<div class="content-block news-group">
								<div class="baf-select-box">
									<?php require_once "news/filter.phtml"; ?>
								</div>
							<?php 
								if ($total_posts != 0): ?>
								
								<div class="display-result-ratio-text">
									<?php require_once "news/pagination-count.phtml"; ?>
								</div>
								<ul class="baf-lists news-feed" data-page = "<?= $paged ?>" data-posttype="news">
									<?php require_once "news/listing.phtml"; ?>
								</ul>
								<ul class="baf-pagination">
									<?php require_once "news/pagination.phtml"; ?>
								</ul>
							<?php else: ?>
									<div class="display-result-ratio-text"></div>
									<ul class="baf-lists news-feed" data-page = "<?= $paged ?>" data-posttype="news">
										<p class="intro">No matching News.</p>
									</ul>
									<ul class="baf-pagination"></ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div> <!--/ theme-page -->
<script type="text/javascript">
	jQuery(document).ready(function(){
		var tags = <?=json_encode(getSystemNewsTags()) ?>;
		var filteredTags = <?=json_encode(getSystemNewsTags(true)) ?>;
		
		jQuery('#news_tag_select').selectivity({
	    	multiple: true,
	    	items: tags,
	    	data: filteredTags,
	        placeholder: 'Search by Tags...',
	        allowClear: true,
	        showDropdown: false
	    });
	});
</script>
<?php
function getSystemNewsTags($filtered = false) {
    $args   = array(
        'taxonomy'      => 'news-tag',
        'hide_empty'    => false,
    );

    if($filtered) {
        if(isset($_GET['tags']) && !empty($_GET['tags'])) {
            $args['slug']   = explode(",", $_GET['tags']);
        } else {
            $args['slug']   = array(-1);
        }
    }

    $newsTags   = get_terms($args);

    $tags       = array();

    foreach ($newsTags as $key => $value) {
        $tags[]     = array(
            "id"    => $value->slug,
            "text"  => $value->name
        );
    }

    return $tags;
}
?>
<?php get_footer(); ?>