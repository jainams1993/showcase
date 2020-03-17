jQuery(document).ready(function(){

	jQuery(document).on('click','.pagination-next-btn',function(e){
		e.preventDefault();
		var page = jQuery('.baf-lists').attr('data-page');
		var lastpage = jQuery(this).attr('data-last');
		if (page == lastpage) {
			return
		}else{
			var posttype   = jQuery('.baf-lists').attr('data-posttype');
			page = parseInt(page) + 1;
			paginateResults(posttype,page);
		}
	});

	jQuery(document).on('click','.pagination-previous-btn',function(e){
		e.preventDefault();
		var page = jQuery('.baf-lists').attr('data-page');
		if (page == 1) {
			return
		}else{
			var posttype   = jQuery('.baf-lists').attr('data-posttype');
			page = parseInt(page) - 1;
			paginateResults(posttype,page);
		}
	});

	jQuery('#news_category').on('change',function(){
		var posttype   = jQuery('.baf-lists').attr('data-posttype');
		page = 1;
		paginateResults(posttype,page);
	});

	jQuery('#news_tag_select').on('change', function(e) {
		console.log("change...");
		if(e.value != undefined){
			jQuery("input[name='selected_news_tags']").val(e.value.join(','));
		}else{
			jQuery("input[name='selected_news_tags']").val('');
		}
    	//console.log(jQuery("input[name='selected_news_tags']").val());
    	var posttype   = jQuery('.baf-lists').attr('data-posttype');
		page = 1;
		paginateResults(posttype,page);
    });

    function paginateResults(posttype,page){
    	var category = jQuery('#news_category').val();
    	var tag 	 = jQuery('#selected_news_tags').val();
		var tag_slug = tag.replace('all,','');

		if (category != 'category') {
			//$params = '?category='+category
			$params = '?category='+category+'&tags='+tag;
			var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + $params;
		}else{
			$params = '?tags='+tag;
			var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + $params;
		}
		window.history.pushState({path:newurl},'',newurl);
      	
		jQuery.ajax({
            type    : 'POST', 
            url     : ajax_object.ajax_url,
            dataType: 'JSON',
            data    : {
                action : posttype,
                data   : 'page='+page+'&category='+category+'&tag='+tag,
                //data   : 'page='+page+'&category='+category,
            },
            beforeSend: function(){
               
            },
            success : function(res) {
            	jQuery('ul.baf-lists').html(res.html);
            	jQuery('.baf-pagination').html(res.pagination);
            	jQuery('.display-result-ratio-text').html(res.paginationCount)
            	jQuery('.baf-lists').attr('data-page',page);
            },complete : function() {
            	jQuery('html, body').animate({scrollTop : 200});
            }
        });
	}
})