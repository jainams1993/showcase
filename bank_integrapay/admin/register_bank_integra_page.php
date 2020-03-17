<?php 
add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
    add_menu_page('Bank Integrapay', 'Bank Integrapay', 'manage_options', 'bank_integrapay', 'bank_integrapay_callback_page' );
    // add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
    // add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
}
function bank_integrapay_callback_page(){
	echo date('Y-m-d H:i:s');
}
?>