jQuery(document).on('submit','#bankintergrapay_form',function(e){
	e.preventDefault();

	var payment_form_ajax = ajax_url+'?action=payment_form_ajax';
	var form = document.forms.namedItem("bankintergrapay_form");
	var	data = new FormData(form);
    // Data.append("page_number", page_number);
    // Data.append("view_type", view_type);

	// console.log(payment_form_ajax);
	// return;
    jQuery.ajax({
        type    : 'POST', 
        url     : payment_form_ajax,
        data    :  data,  
        processData: false,
        contentType: false,   
        beforeSend : function() {
            jQuery("#bankintergrapay_form span.error").empty();
            jQuery('#bankintergrapay_form_new').text('processing..');
        },
        success : function(res) { 
            switch(res.type){
        		case 'error':
                    for(var i in res.error) {
                        if(res.error[i].length) {
                          jQuery("span.error[id=" + i + "]").html('<div class="alert alert-danger">'+res.error[i]+'</div>');
                          jQuery("#"+i).addClass('alert-text');
                        }
                    } 
                    jQuery('#bankintergrapay_form_new').text('Pay With Ineintergrapay');
        			break;
        		case 'success':
                        window.location.href= res.url;
        			break;	
        	}
        },
        complete: function (data) {
        
	    }
	});
});

jQuery(document).on('submit','#schedule_payment',function(e){
    e.preventDefault();

    var schedule_payment_ajax = ajax_url+'?action=schedule_payment';
    
    var form = document.forms.namedItem("schedule_payment");
    var data = new FormData(form);
    jQuery.ajax({
        type    : 'POST', 
        url     :  schedule_payment_ajax,
        data    :  data,  
        processData: false,
        contentType: false,   
        beforeSend : function() {
            jQuery("#schedule_payment span.error").empty();
            jQuery('#schedule_payment_button').text('processing..');
        },
        success : function(res) { 
            switch(res.type){
                case 'error':
                    for(var i in res.error) {
                        if(res.error[i].length) {
                          jQuery("span.error[id=" + i + "]").html('<div class="alert alert-danger">'+res.error[i]+'</div>');
                          jQuery("#"+i).addClass('alert-text');
                        }
                    } 
                    jQuery('#schedule_payment_button').text('Schedule Payment');
                    break;
                case 'success':
                        window.location.href= res.url;
                    break;  
            }
        },
        complete: function (data) {
        
        }
    });
});