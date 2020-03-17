jQuery(document).ready(function($){
	jQuery(document).on('click','.approve_review',function(e){
	    e.preventDefault();
	    var post_id = jQuery(this).data('post_id');
	    var id = $(this).attr("id");
	    var aprove_review =ajax_url+'?action=aprove_review';

	    jQuery.ajax({
	        type    : 'POST', 
	        url     : aprove_review,
	        data    : {'post_id': post_id},
	        dataType: 'json',
	        success : function(res) {
	            switch(res.type){
	            	case 'success':
	            		location.reload();
	            		break;
	            }
	        },
	        
	    });
	});
});