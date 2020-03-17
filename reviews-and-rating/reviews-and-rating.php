<?php
/*
 * Plugin Name: Reviews and Ratings
 * Plugin URI: 
 * Description: Custom Reviews and Rating plugin
 * Version: 1.0
 * Author: Zaksy Vision
 * Author URI: https://www.zaksyvision.com
 * Text Domain: dealer_review
 *
 
 */
include 'script.php';

define('REVIEWS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('REVIEWS_PLUGIN_DIR', plugin_dir_path(__FILE__));

function validate($data){
	$error = array();
	foreach ($data as $key => $value) {
		switch ($key) {
			case 'rar_name':
			case 'rar_title':
				if(empty($value)){
					$error[$key] = __('Please fill the required field.','review_rating');
				}elseif (strlen($value) > 50) {
                    $error[$key] = __('Its to long.','review_rating');
                }
				break;
			case 'rar_email':
				if(empty($value)){
					$error[$key] = __('Please fill the required field.','review_rating');
				}elseif (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
				    $error[$key] = $value .__('is not a valid email address!','review_rating');
				}
            case 'score':
                    foreach ($value as $k => $v) {
                        if(empty($v)){
                            $error['custom'.$k] = 'Reviews required';
                        }
                    }
                    break;    
			default:
				break;
		}
	}
	return $error;
}

function addAjaxUrl(){
	$ajax_url = admin_url('admin-ajax.php'); ?>
	<script type="text/javascript">
		var ajax_url = '<?= $ajax_url; ?>';
	</script>
<?php } 
add_action('wp_head','addAjaxUrl');
			  
function custom_paginations_reviews($pages = '', $range = 2,$paged,$where)	{  
    $showitems = ($range * 2)+1;  

     if(1 != $pages)
     {
        echo '<ul class="pagination column-12 c '.$where.'" id="pagination">';
        if($paged!=1){
             $prev_page=$paged-1;
        }else{
             $prev_page=1;
        }
        echo "<li class=\"roundleft\"><a href='".get_pagenum_link($paged - 1)."' data-page='".$prev_page."'><i class=\"fa fa-angle-left\"></i></a></li>";
      
        for ($i=1; $i <= $pages; $i++)
        {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 if ($paged == $i){
                    print '<li class="active"><a href="'.get_pagenum_link($i).'" data-page="'.$i.'">'.$i.'</a><li>';
                 }else{
                    print '<li><a href="'.get_pagenum_link($i).'" data-page="'.$i.'">'.$i.'</a><li>';
                 }
             }
        }
         
        $prev_page= get_pagenum_link($paged + 1);
        if ( ($paged +1) > $pages){
            $prev_page= get_pagenum_link($paged );
             echo "<li class=\"roundright\"><a href='".$prev_page."' data-page='".$paged."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
        }else{
             $prev_page= get_pagenum_link($paged + 1);
             echo "<li class=\"roundright\"><a href='".$prev_page."' data-page='".($paged+1)."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
        }
        echo "</ul>\n";
     }
}	
			
require_once 'include/view.php';
require_once 'include/post.php';
require_once 'include/form.php';
require_once 'include/ajax.php';
require_once 'shortcode.php';

?>