//jQuery('#review_form').hide();

jQuery('#review_button').on('click',function (e) {
    jQuery('#review_model').modal({backdrop: 'static', keyboard: false})  
});

jQuery('#cancel').on('click',function (e) {
    jQuery('span.error').html('');
    jQuery('#review_model').modal('hide'); 
});
jQuery('#reviews_and_rating_form').on('submit',function (e) {
    e.preventDefault();
    var msgTemplate = '<div class="alert alert-{{type}}">' + '{{msg}}' +'</div>';
    var data = new FormData(this);
    var reviewsAjax =ajax_url+'?action=reviewsAjax';
        
    jQuery.ajax({
        type    : 'POST', 
        url     : reviewsAjax,
        data    : data,     
        contentType: false,      
        cache: false,           
        processData:false,
        dataType: 'json',
        beforeSend : function() {
            jQuery('span.error').html('');
            jQuery('#transaction_reset_name').addClass('btn-loading');
            jQuery('#transaction_reset_name .loading-box').show();

        },
        success : function(res) {
           switch(res.data.type) {
                case 'success' :
                    jQuery('.rr-action-sucess').show();
                    jQuery('#success').html(msgTemplate.replace(/{{type}}/g, 'success').replace(/{{msg}}/g, res.data.html));
                    jQuery('input').val('');
                    jQuery('#reviews_and_rating_form input').removeClass('alert-text');
                    jQuery('#reviews_and_rating_form')[0].reset();
                    break;
                case 'failure' :
                    jQuery('#reviews_and_rating_form input').removeClass('alert-text');
                    for(var i in res.data.html) {
                        var msgs = '';
                        if(res.data.html[i].length) {                                
                            jQuery("#"+ i ).html(msgTemplate.replace(/{{type}}/g, 'danger').replace(/{{msg}}/g, res.data.html[i]));
                            jQuery("."+ i ).addClass('alert-text');
                        }
                    }
                    break;
                default :
                    break;
            }
        },
        complete : function() {
                jQuery('#transaction_reset_name').removeClass("btn-loading");
                jQuery('#transaction_reset_name .loading-box').hide();

            }  
    });
});

jQuery(document).on('click','.replacement_div .reviews_pagination li a',function(e){
    e.preventDefault();
    var paged = this.text;
    var filter_data = jQuery('#dealer_review_filters').serialize();
    var custom_paginations_reviews_list = ajax_url+'?action=custom_paginations_reviews_list';
       
        jQuery.ajax({
            type    : 'POST', 
            url     : custom_paginations_reviews_list,
            data    : {'paged':jQuery(this).attr('data-page'),'data':filter_data},     
            dataType: 'json',

            success : function(res) {
                jQuery('.replacement_div').empty();
                jQuery('.replacement_div').append(res.html);
            },
        });
});

/* filter and paginatin for inquery page   */
jQuery(document).ready(function() {
    jQuery("#dealer_review_form_date").datepicker({
        dateFormat: 'yy/mm/dd',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date,
        yearRange: "2016:2022",
        onClose: function( selectedDate ) {
        jQuery( "#dealer_review_to_date" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    jQuery("#dealer_review_to_date").datepicker({
        dateFormat: 'yy/mm/dd',
        changeMonth: true,
        changeYear: true,
        minDate: '0',
        onClose: function( selectedDate ) {
        jQuery( "#dealer_review_form_date" ).datepicker({
        dateFormat: 'yy/mm/dd',
        changeMonth: true,
        changeYear: true,
        maxDate: '0',
        } );
        }
    });
});

var review_filter = {
    filter : function() {
        jQuery('#dealer_review_filters #filter_button').addClass('btn-loading');
        jQuery('#filter_button .loading-box').show();
        jQuery("form#dealer_review_filters").submit();
    },
    refresh : function() {
        jQuery('#dealer_review_filters #reset_button').addClass('btn-loading');
        jQuery('#reset_button .loading-box').show();
        
        jQuery("form#dealer_review_filters")[0].reset();
        jQuery("form#dealer_review_filters").submit();
    }
};

jQuery(document).on('submit','#dealer_review_filters',function(e){
    e.preventDefault();
    var frmData = jQuery(this).serialize();
    var custom_paginations_reviews_list =ajax_url+'?action=custom_paginations_reviews_list';

    jQuery.ajax({
        type    : 'POST', 
        url     : custom_paginations_reviews_list,
        data    : {'data': frmData},
        dataType: 'json',
        success : function(res) {
            jQuery('#filter_button').removeClass("btn-loading");
            jQuery('#reset_button').removeClass("btn-loading");
            jQuery('.form-btn-group .loading-box').hide();
            
            jQuery('.replacement_div').empty();
            jQuery('.replacement_div').append(res.html);
            jQuery('.loading-box').hide();
        },
        
    });
});

