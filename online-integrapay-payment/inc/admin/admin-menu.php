<?php
	function addOnlineIntegrapayMenu() {
		add_menu_page('Invoices By IntegraPay', 'IntegraPay', 'manage_options', 'integrapays','zackOnlineIntegrapayInit');
	}
	function addSubOnlineIntegrapayMenu() {
		add_submenu_page( 'integrapays','integrapays', 'Settings', 'manage_options', 'onlineIntegrapays', 'settingIntegrapay' );;
	}
	
	function zackOnlineIntegrapayInit() {
		
		echo '<div class="wrap">';
			echo '<h1>Invoices By IntegraPay</h1>'; 

			require_once(dirname( ONLINEINTEGRAPAY ) .'/inc/admin/online-grid.php');
			
			$onlineIntegrapayTable = new OnlineIntegrapay_Table();
			$onlineIntegrapayTable->prepare_items();

			echo '<form method="post" name="searchonlineIntegrapay" id="searchonlineIntegrapay" class="validate" novalidate="novalidate">';
			$onlineIntegrapayTable->search_box('search', 'title');
			$onlineIntegrapayTable->display();
			echo '</form>';
		echo '</div>';
	}	
	function backgroundOnlineIntegrapayDelete($id) {
		global $wpdb;

		$wpdb->delete($wpdb->prefix. 'onlineintegrapays', array('id' => (int)$id));
		echo '<div class="wrap">
			<div class="success">
				<ul>
					<li>OnlineIntegrapays deleted successfully.</li>
				</ul>
			</div>
		</div>';
	}
	add_action('admin_menu', 'addOnlineIntegrapayMenu');
	add_action('admin_menu', 'addSubOnlineIntegrapayMenu');
?>