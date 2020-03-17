jQuery('#onlineintergrapay_form').on('submit',function (e) {
    e.preventDefault();
    var onlineintegrapayAjax =themeajax.url+'?action=onlineintegrapayAjax';
    var msgTemplate = '<div class="alert alert-{{type}}">' + '{{msg}}' +'</div>';
    var data = new FormData(this);
    jQuery.ajax({
        type    : 'POST', 
        url     : onlineintegrapayAjax,
        data    :  data,     
        contentType: false,      
        cache: false,           
        processData:false,
        dataType: 'json',
        beforeSend : function() {
            jQuery('span.error').html('');
            jQuery('.loader').show();
          },
        success : function(res) {
           switch(res.data.type) {
                case 'success' :
                        window.location.href= res.data.url; 
                    break;
                case 'failure' :
                    for(var i in res.data.html) {
                        var msgs = '';
                        if(res.data.html[i].length) {                                
                            jQuery("#"+ i ).html(msgTemplate.replace(/{{type}}/g, 'danger').replace(/{{msg}}/g, res.data.html[i]));
                        }
                    }
                    break;
                default :
                    break;
            }
        },
        complete : function() {
            setTimeout(function(){
            jQuery('.loader').hide();
     
        }, 1000);

          }


    });
});
 jQuery('.loader').hide();