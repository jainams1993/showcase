<?php
// Paginate news
add_action( 'wp_ajax_nopriv_news', 'news' );
add_action( 'wp_ajax_news', 'news' );

function news(){
    $params = array();
    parse_str($_POST['data'], $params);
    $post_type = 'news';
    $max_per_page = get_option( 'posts_per_page' );
    $category_taxonomy = '';
    $tag_taxonomy =  '';
        
    
    if ($params['category'] != "all" && $params['category'] != "category") {
        $category_taxonomy = array(
            'taxonomy' => 'news-category',
            'field' => 'slug',
            'terms' => $params['category']
        );
    }
    if ($params['tag']) {
        $tags = explode(',', $params['tag']);
        //array_shift($tags);
        
        if (count($tags) > 0) {
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
           'paged' => $params['page'],
           'meta_key'  => 'news_date_end_milli',
           //'order'      => 'DESC',
           'orderby'    => array('meta_value_num' => 'DESC','title' => 'ASC'),
           'tax_query' => $taxonomy
        );

    $paged = $params['page'];
    $loop = new WP_Query($args); 
    $total_posts = $loop->found_posts;
    
    if ($total_posts != 0):
    ob_start();
    require(NEWS_PLUGIN_DIR."/Templates/news/listing.phtml");
    $response['html'] = ob_get_clean();

    ob_start();
    require(NEWS_PLUGIN_DIR."Templates/news/pagination.phtml");
    $response['pagination'] = ob_get_clean();

    ob_start();
    require(NEWS_PLUGIN_DIR."Templates/news/pagination-count.phtml");
    $response['paginationCount'] = ob_get_clean();

     else:
        $response['html'] = '<ul class="baf-lists news-feed" data-page = "'.$paged.'" data-posttype="'.$post_type.'"><p class="intro">No matching News.</p></div>';
        $response['pagination'] = '';
        $response['paginationCount'] = '';
    endif;

    wp_send_json($response);
    exit(); 
}
?>