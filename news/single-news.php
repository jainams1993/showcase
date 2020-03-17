<?php 
get_header(); 
$post_id 			 = get_the_ID();
$news_date 		 	 = get_post_meta($post_id,'news_date',true);
$date				 = date_create($news_date );
$new_date 			 =  date_format($date,'F d, Y');
//$date = DateTime::createFromFormat('j-m-Y', $news_date);
$news_author 		 = get_post_meta($post_id,'news_author',true);
$news_url 		 	 = get_post_meta($post_id,'news_url',true);
$news_category		 = get_the_terms($post_id,'news-category');
$news_tags		 	 = get_the_terms($post_id,'news-tag');
$baf_attchment 		 = get_post_meta( $post_id, 'baf_attchment', true );
$back_url			 = site_url('news');

if (isset($_GET['ref'])) {
	$back_url	= site_url('news').'?category='.$_GET['ref'];
}

if($news_category){
	$cat_nmae = wp_list_pluck( $news_category, 'name' );
	$news_category_name = array_pop($cat_nmae);
	$cat_slug = wp_list_pluck( $news_category, 'slug' );
	$news_category_slug = array_pop( $cat_slug);
	//$back_url = site_url('news').'?category='.$news_category_slug;
}

wp_reset_postdata();
?>
<div class="theme-single-page news-template">
	<section class="page-col-section">
		<div class="page-col-inner">
			<div class="container">
				<div class="row flex-wrap-reverse">
					<div class="col-md-8">
						<div class="project-section news-section">
							<div class="link-block">
								<a href="<?= $back_url ?>"><i class="fas fa-chevron-left"></i> Back to News</a>
							</div>
							<ul class="listing_info">
								<?php if($news_date): echo '<li>'.$new_date.'</li>'; endif;?><li><?= get_the_author_meta('display_name',$news_author) ?></li>
								<?php if ($news_category): ?>
								<?php foreach ($news_category as $key => $value) { ?>
									<li><?= $value->name ?></li>
								<?php } ?>
								<?php endif; ?>
							</ul>
							<?php if ($news_tags): ?>
								<ul class="listing_tags">
									<?php foreach ($news_tags as $key => $value) { ?>
										<li><a href="<?= site_url('news') ?>?tags=<?= $value->slug ?>">#<?= $value->name ?></a></li>
									<?php } ?>
								</ul>
							<?php endif; ?>
							<div class="title-block">
								<h1><?= get_the_title() ?></h1>
							</div>
							<div class="sgroup-content-block">
								<?= get_the_post_thumbnail() ?>
								<?php // nl2br(get_post_field('post_content', $post_id)); ?>
								<?= nl2br(the_content()) ?>
							</div>
							<div class="attachment-block">
								<?php if ($baf_attchment):
									foreach ( $baf_attchment as $single_baf_attchment ) {
					                    $attachments = get_post($single_baf_attchment); ?>
					                    <a href="<?= $attachments->guid ?>" target="_blank"><?= $attachments->post_title ?></a>
					                <?php } endif;?>
								
								<br/><br/>
								<?php if ($news_url): ?>
									<p><a href="<?= $news_url ?>" target="_blank">View News </a></p>
								<?php endif; ?>
							</div>

							<div class="social-share-block">
								<label>Share this</label>
								<ul class="social-list">
									<li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= site_url(); ?>" title="Facebook share" target="_blank" ><i class="fab fa-facebook-f"></i></a></li>
		                            <li> <a class="twitter" href="https://twitter.com/intent/tweet?url=<?= site_url(); ?>" title="Twitter share" target="_blank" ><i class="fab fa-twitter"></i></a> </li>
		                            <li> <a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?= site_url(); ?>" title="Linkedin share" target="_blank" ><i class="fab fa-linkedin-in"></i></a> </li>
		                            <li><a href="" data-toggle="modal" data-target="#shareModal" data-id="<?= $post_id ?>" class="share_post" data-title="News Shared from Brain Aneurysm Foundation"><i class="fas fa-envelope"></i></a></li>
		                        </ul>
							</div>
							<?php getPrevNext() ?>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
</div>
<script type="text/javascript">
	(function($) {
		$('.twitter').click(function(e) {
			e.preventDefault();
			var url = 'https://twitter.com/intent/tweet?url='+window.location.href;
			window.open(url, "Twitter", "height=600,width=600,resizable=1");
		});
		$('.facebook').click(function(e) {
			e.preventDefault();
			var url = 'https://www.facebook.com/sharer/sharer.php?u='+window.location.href;
			window.open(url, "Facebook", "height=600,width=600,resizable=1");
		});
		$('.linkedin').click(function(e) {
			e.preventDefault();
			var url = 'https://www.linkedin.com/shareArticle?mini=true&url='+window.location.href;
			window.open(url, "Linkedin", "height=600,width=600,resizable=1");
		});
	})(jQuery);
</script>
<?php 

function getPrevNext(){
	$max_per_page = get_option( 'posts_per_page' );	
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
	$category = isset($_GET['ref']) ? $_GET['ref'] : '';

	if (isset($category) && $category) {
		if ($category != "all" && $category != "category" && in_array($category, $preNewsCats)) {
		    $category_taxonomy = array(
		        'taxonomy' => 'news-category',
		        'field' => 'slug',
		        'terms' => $category
		    );
		}
	}

	$taxonomy = array($category_taxonomy,$tag_taxonomy);

	$args = array( 'post_type' => $post_type, 
				   'posts_per_page' => -1,
				   //'orderby'  => 'DESC',
				   'post_status'   => 'publish',
				   'meta_key'  => 'news_date_end_milli',
				   //'order'      => 'DESC',
				   'orderby'    => array('meta_value_num' => 'DESC','title' => 'ASC'),
				   'tax_query' => $taxonomy
				);

	$loop = new WP_Query($args);
	$total_posts = $loop->found_posts;
	
	$pages = array();
	foreach ($loop->posts as $page) {
	   $pages[] += $page->ID;
	}

	$current = array_search(get_the_ID(), $pages);
	if($current != 0){
		$prevID = $pages[$current-1];
	}/*else{
		$prevID = $pages[--$total_posts];
	}*/
	
	if ($current != (--$total_posts)) {
		$nextID = $pages[$current+1];
	}/*else{
		$nextID = $pages[0];
	}*/
	
	echo '<div class="next_prev_block">';

	$add_cat_url = '';
	if ($category) {
		$add_cat_url = "?ref=".$category;
	}
	?>
	<span class="left">
		<?php if (!empty($prevID)): ?>
				<a href="<?= get_permalink($prevID) ?><?= $add_cat_url ?>" title="<?= get_the_title($prevID) ?>"><?= get_the_title($prevID) ?></a>
		<?php endif; ?>
	</span>

	<span class="right">
		<?php if (!empty($nextID)): ?>
				<a href="<?= get_permalink($nextID) ?><?= $add_cat_url ?>" title="<?= get_the_title($nextID) ?>"><?= get_the_title($nextID) ?></a>
		<?php endif; ?>
	</span>
<?php } ?>
<?php get_footer(); ?>