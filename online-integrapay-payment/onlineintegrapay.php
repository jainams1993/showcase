<?php 
//add custom Templates method
require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/templates.php' );
//add for online bank payment response
require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/online_bank.php' );
//add custom enqueue scripts
require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/scripts.php' );
//add custom enqueue scripts
require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/ajax.php' );
//add custom link integrapay
require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/admin/config.php' );
//add custom link For Admin settings
require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/admin/admin-menu.php' );


require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/admin/checkpayment.php' );
require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/admin/xml2Array.php' );