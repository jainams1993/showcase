<?php
/*
Plugin Name: News
Plugin URI: 
Description: News
Version: 1.0
Author: Zaksy Vision
Author URI: https://www.zaksyvision.com/
*/

define('NEWS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NEWS_PLUGIN_DIR', plugin_dir_path(__FILE__));

add_action( 'init', 'register_news' );
function register_news() {
    $labels = array(
        'name'                  => __('News', 'project_name'),
        'singular_name'         => __('News', 'project_name'),
        'add_new'               => __('Add New', 'project_name'),
        'add_new_item'          => __('Add New News', 'project_name'),
        'edit_item'             => __('Edit News', 'project_name'),
        'new_item'              => __('New News', 'project_name'),
        'all_items'             => __('All News', 'project_name'),
        'view_item'             => __('View News', 'project_name'),
        'search_items'          => __('Search News', 'project_name'),
        'not_found'             => __('No News found', 'project_name'),
        'not_found_in_trash'    => __('No News found in Trash', 'project_name'), 
        'parent_item_colon'     => '',
        'menu_name'             => __('News', 'project_name'),
        'featured_image'        => __( 'Thumbnail' ),
        'set_featured_image'    => __( 'Set Thumbnail' ),
        'remove_featured_image' => __( 'Remove Thumbnail' ),
        'use_featured_image'    => __( 'Use as Thumbnail' )
    );

    $args = array( 
        'labels'            => $labels,
        'public'            => true,
        'rewrite'           => array( 'slug' => 'news'),
        'has_archive'       => false,
        'menu_position'     => 20,
        'capability_type'   => 'post',
        'supports'          => array( 'title', 'editor', 'thumbnail' ),
    );
    register_post_type('news',$args);
}


function news_taxonomy() {  
     


    $taxonomies = array(
        array(
            'slug'         => 'news-category',
            'single_name'  => 'Category',
            'plural_name'  => 'Categories',
            'post_type'    => 'news',
        ),
        array(
            'slug'         => 'news-tag',
            'single_name'  => 'Tag',
            'plural_name'  => 'Tags',
            'post_type'    => 'news',
            'hierarchical' => false,
        )
    );
    foreach( $taxonomies as $taxonomy ) {
        $labels = array(
            'name' => $taxonomy['plural_name'],
            'singular_name' => $taxonomy['single_name'],
            'search_items' =>  'Search ' . $taxonomy['plural_name'],
            'all_items' => 'All ' . $taxonomy['plural_name'],
            'parent_item' => 'Parent ' . $taxonomy['single_name'],
            'parent_item_colon' => 'Parent ' . $taxonomy['single_name'] . ':',
            'edit_item' => 'Edit ' . $taxonomy['single_name'],
            'update_item' => 'Update ' . $taxonomy['single_name'],
            'add_new_item' => 'Add New ' . $taxonomy['single_name'],
            'new_item_name' => 'New ' . $taxonomy['single_name'] . ' Name',
            'menu_name' => $taxonomy['plural_name']
        );
        
        $rewrite = isset( $taxonomy['rewrite'] ) ? $taxonomy['rewrite'] : array( 'slug' => $taxonomy['slug'] );
        $hierarchical = isset( $taxonomy['hierarchical'] ) ? $taxonomy['hierarchical'] : true;
    
        register_taxonomy( $taxonomy['slug'], $taxonomy['post_type'], array(
            'hierarchical' => $hierarchical,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => $rewrite,
        ));
    }
}  
add_action( 'init', 'news_taxonomy');





add_action( 'add_meta_boxes', 'meta_box_news' );
function meta_box_news()
{
    add_meta_box( 'news', 'News Details', 'add_news', 'news', 'normal', 'high' );
}

function add_news(){
    global $post;
    $featured = get_post_meta($post->ID,'featured',true);
    $checked = ($featured == 'on')  ? 'checked' : '';
    $featured_val = ($featured == 'on')  ? 'on' : 'off';

    $featured_h = get_post_meta($post->ID,'featured_home',true);
    $checked_home = ($featured_h == 'on')  ? 'checked' : '';
    $featured_home = ($featured_h == 'on')  ? 'on' : 'off';

    $users = get_users();
    if (get_post_meta($post->ID,'news_date',true)) {
        $news_date = date("m-d-Y", strtotime(get_post_meta($post->ID,'news_date',true)));
    }else{
        $news_date = date("m-d-Y");
    }

    ?>
    <?php wp_localize_script( 'admin', 'dateObject',array( 'date' => $news_date ) ); ?>
    <div class="project_name_custom_post_fileds">
        <div class="project_name_option_row col2">
            <div class="label_option_row"><?php _e('Featured in Sidebar', 'project_name') ?></div>
                    <input type="checkbox" name="featured" id="featured" <?= $checked ?>  value="<?= $featured_val  ?>">
        </div>
        <div class="project_name_option_row col2">
            <div class="label_option_row"><?php _e('Featured on Homepage', 'project_name') ?></div>
            <input type="checkbox" name="featured_home" id="featured_home" <?= $checked_home ?>  value="<?= $featured_home  ?>">
        </div>
        <div class="project_name_option_row col4">
            <div class="label_option_row"><?php _e('Date', 'project_name') ?></div>
                    <input type="text" id="news_date" size="50" name="news_date" value="<?= $news_date ?>" class="admin_news_date">
        </div>
        <div class="project_name_option_row col4">
            <div class="label_option_row"><?php _e('Author', 'project_name') ?></div>
            <select id="news_author" name="news_author">
                <?php  foreach ($users as $key => $user) {
                    $select = '';
                    if ($user->ID == get_post_meta($post->ID,'news_author',true) ) {
                        $select = 'selected';
                    }
                ?>
                <option value="<?= $user->ID ?>" <?= $select?>><?= $user->display_name ?></option>
            <?php } ?>
            </select>
        </div>
     
        <div class="project_name_option_row col4">
            <div class="video-url-box">
                <div class="label_option_row"><?php _e('URL', 'project_name') ?></div>
                <input type="url" id="news_url" size="50" name="news_url" class="external_url" value="<?= get_post_meta($post->ID, 'news_url', true) ?>" />
                <div class="url-wrapper">
                    <div class="url_banner_box"></div>
                    <div class="url_data_box">
                        <div class="label_option_row">
                            <span class="url_favicon_box"></span>
                            <span class="url_title"></span>
                        </div>
                        <div class="label_option_row url_description"></div>
                        <div class="url_loading"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <?php
}

function save_news_data($post_id ,$post){
  	if ($post->post_type == 'news') {
        update_post_meta($post->ID, 'featured' ,  'off');
        update_post_meta($post->ID, 'featured_home' ,  'off');

        if (isset($_POST['news_date'])) {
            $news_date = DateTime::createFromFormat('m-d-Y', $_POST['news_date']);
            update_post_meta($post->ID,'news_date',$news_date->format('Y-m-d'));

            $n_date = strtotime($news_date->format('Y-m-d'));        
            update_post_meta($post->ID, 'news_date_end_milli' , $n_date);
        }


        $key_array = array(
            'featured',
            'featured_home',
            'news_author',
            'news_url'
        );

        foreach ($key_array as $key) {
            if (isset($_POST[$key])) {
                update_post_meta($post->ID, $key ,  $_POST[$key]);
            }
        }
    }
}
add_action( 'save_post', 'save_news_data',10,3 );


add_filter('single_template', 'news_template');

function news_template($single) {
    global $post;
    /* Checks for single template by post type */
   
    if ( $post->post_type == 'news' ) {
        if ( file_exists( NEWS_PLUGIN_DIR . '/single-news.php' ) ) {
            return NEWS_PLUGIN_DIR . '/single-news.php';
        }
    }
    return $single;
}


add_action( 'wp_enqueue_scripts', 'load_news_front_css_js' );
function load_news_front_css_js() {
    global $post;
    if(!$post){
        return;
    }

    //if (NEWS_PLUGIN_DIR. 'Templates/news.php' == get_page_template_slug()) {
    if ('template/news.php' == get_page_template_slug()) {
        wp_enqueue_script('news-js', NEWS_PLUGIN_URL.'js/news.js',array('jquery'), '1.0');
        //wp_localize_script('news-js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'max_per_page' => get_option( 'posts_per_page' ), 'tags' => json_encode(getSystemNewsTags()), 'filteredTags' => json_encode(getSystemNewsTags(true))));
    }
}
include 'Templates/news/ajax.php';

/*function getSystemNewsTags($filtered = false) {
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
}*/

// add member filter
add_action('restrict_manage_posts', 'news_filter_post_type_by_taxonomy');
function news_filter_post_type_by_taxonomy() {
    global $typenow;
    $post_type = 'news';
    $taxonomy  = 'news-category';
    if ($typenow == $post_type) {
        $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("Show All {$info_taxonomy->label}"),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => true,
        ));
    };
}

add_filter('parse_query', 'filtered_news_list');
function filtered_news_list($query) {
    global $pagenow;
    $post_type = 'news';
    $taxonomy  = 'news-category';
    $q_vars    = &$query->query_vars;
    if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}

// Add column
add_filter( 'manage_news_posts_columns', 'my_news_columns' ) ;

function my_news_columns( $columns ) {
    $columns = array(
        'cb' => '&lt;input type="checkbox" />',
        'title' => __( 'News' ),
        'category' => __( 'Category' ),
        'featured_on_sidebar' => __( 'Featured on Sidebar' ),
        'featured_on_home' => __( 'Featured on Homepage ' ),
        'date' => __( 'Date' )
    );
    return $columns;
}

// Add column data
add_action( 'manage_news_posts_custom_column' , 'custom_news_column', 10, 2 );
function custom_news_column( $column, $post_id ) {
    switch ( $column ) {
        case 'category' :
            $terms = get_the_term_list( $post_id , 'news-category' , '' , ',' , '' );
            if ( is_string( $terms ) )
                echo $terms;
        break;
        case 'featured_on_sidebar' :
            $news_featured = get_post_meta($post_id,'featured',true);
            if ($news_featured == 'on') {
                echo 'Featured';
            }
        break;
        case 'featured_on_home' :
            $news_home_featured = get_post_meta($post_id,'featured_home',true);
            if ($news_home_featured == 'on') {
                echo 'Featured';
            }
        break;
    }
}

add_filter( 'manage_edit-news_sortable_columns', 'my_sortable_category_column' );
function my_sortable_category_column( $columns ) {
    $columns['category'] = 'category';
    $columns['featured_on_sidebar'] = 'featured';
    $columns['featured_on_home']    = 'featured_home';
    return $columns;
}
?>