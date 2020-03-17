<?php
	if(!class_exists('WP_List_Table')){
	   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}
?>
<?php
	class OnlineIntegrapay_Table extends Wp_List_Table {
		function __construct() {
	       	parent::__construct(array(
		      'singular' => 'onlineIntegrapay',
		      'plural'   => 'onlineIntegrapay',
		      'ajax'     => false
		    ));
	    }
	  
	    public static function get_onlineIntegrapay($per_page = 10, $page_number = 1) {

			global $wpdb;
			$sql = "SELECT * FROM {$wpdb->prefix}onlineintegrapay";

			if (!empty($_REQUEST['s'])) {
				$sql .= ' WHERE `title` LIKE "%'. esc_sql($_REQUEST['s']). '%"';
			}
			$sql .= ' ORDER BY invoice_id DESC';
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
			$result = $wpdb->get_results($sql);
			return $result;
		}

		public static function delete_onlineIntegrapay($id) {
			global $wpdb;
			$wpdb->query("DELETE FROM `{$wpdb->prefix}onlineintegrapay` WHERE `id` = $id ");
		}

		public static function record_count() {
			global $wpdb;

			$sql = "SELECT COUNT(*) FROM `{$wpdb->prefix}onlineintegrapay`";

			return $wpdb->get_var($sql);
		}

		function column_is_active($item) {
			echo ($item->position) ? 'Yes' : 'No';
		}		

		public function column_default( $item, $column_name ) {
			switch ($column_name) {
				case 'status':
					$statusName = $this->getStatusName($item->status);
					echo $statusName;
					return;
					break;
				case 'payment_method':
					$payment_name = ($item->payment_method == 2 ? 'Bank Payment' : 'Card Payment');
					echo $payment_name;
					return;
					break;
				
				default:
					# code...
					break;
			}
			
			return $item->{$column_name};
		}
		function column_cb($item) {
			return sprintf(
				'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item->id
			);
		}
		
		function getStatusName($status){
			require_once(dirname( ONLINEINTEGRAPAY ) .'/inc/class.php');
			$onlineIntegrapay 	= new OnlineIntergrapay();
			$statu 			= $onlineIntegrapay->getStatus();
			return $statu[$status];
		}
	    function get_columns() {
		   	return $columns = array(
		   	  'cb'        	 		=> '<input type="checkbox" />',
		      'invoice_id'         	=> __('Id'),
		      'name'  				=> __('Name'),
		      'email'  				=> __('Email'),
		      'address'				=> __('Address'),
		      'phone'				=> __('Phone'),
		      'invoice'				=> __('Invoice Name'),
		      'amount'				=> __('Amount in $'),
		      'status'				=> __('Status'),
		      'date'				=> __('Date'),
		      'payment_method'		=> __('Payment Mode'),
		   );
		}

		function get_sortable_columns() {
		   	return $sortable = array(
		      'id'         => array('id', true),
		      'sort_order' => array('sort_order', true),
		   );
		}

		public function get_bulk_actions() {
			$actions = [
				'bulk-delete' => 'Delete'
			];

			return $actions;
		}

		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();

			$this->process_bulk_action();

			$per_page     = $this->get_items_per_page('onlineIntegrapay_per_page', 20);
			$current_page = $this->get_pagenum();
			$total_items  = self::record_count();

			$this->set_pagination_args([
				'total_items' => $total_items,
				'per_page'    => $per_page 
			]);

			$columns  = $this->get_columns();
			
			$hidden   = array();
			$sortable = $this->get_sortable_columns();

			$this->_column_headers = array($columns, $hidden, $sortable);

			$this->items = self::get_onlineIntegrapay($per_page, $current_page);
		}

		public function process_bulk_action() {
			if ('delete' === $this->current_action()) {
				$nonce = esc_attr($_REQUEST['_wpnonce']);

				if (!wp_verify_nonce($nonce, 'delete_onlineIntegrapay')) {
					die( 'Can not delete.' );
				}
				else {
					self::delete_onlineIntegrapay(absint($_GET['onlineIntegrapay']));
					wp_redirect(esc_url(add_query_arg()));
					exit;
				}
			}
			if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
			   || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
			) {
				$delete_ids = esc_sql($_POST['frame']);
				// loop over the array of record IDs and delete them
				foreach ( $delete_ids as $id ) {
					self::delete_onlineIntegrapay($id);
				}
			}
		}

		function get_default_primary_column_name() {
			return 'title';
		}
	}
?>