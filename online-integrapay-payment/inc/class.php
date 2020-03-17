<?php 

	class  OnlineIntergrapay
	{
        const STATUS_COMPLETED  = 1;
        const STATUS_HOLD       = 2;
        const STATUS_FAILED     = 3;
        const STATUS_DUPLICATE  = 3;

        const STATUS_FAILED_TEXT    = 'Transaction Failed';
        const STATUS_COMPLETED_TEXT = 'completed';
        const STATUS_HOLD_TEXT		= 'Pending Payment';
        const STATUS_DUPLICATE_TEXT	= 'Duplicate Token';
		

		public static function getStatus() {
            return array(
                self::STATUS_FAILED     => self::STATUS_FAILED_TEXT,
                self::STATUS_COMPLETED  => self::STATUS_COMPLETED_TEXT,
                self::STATUS_DUPLICATE  => self::STATUS_DUPLICATE_TEXT,
                self::STATUS_HOLD       => self::STATUS_HOLD_TEXT,
            );
        }

		public function insertdata($data)
		{
			global $wpdb;
			$error = $this->validate($data);
			if($error){
		 		$response = array(
		            'type' => 'failure',
		            'html' => $error
				); 
			}else{
				$data['token'] 		= '';
				$data['status'] 	= self::STATUS_HOLD;
				//$data['date'] 	= date("Y/m/d");
				$tokenData = $wpdb->insert($wpdb->prefix.'onlineintegrapay', $data);
				$lastid = $wpdb->insert_id;
				
				if($data['payment_method'] == 1){
					//call Credit card payment
					$integrapay_data = $this->createToken($lastid,$data['amount'],$data);

					switch ($integrapay_data['result']) {
						case 'ERROR':
								if($integrapay_data['errortype'] == 'DUPLICATE_TRANSACTION'){
									$update = $wpdb->query(" DELETE FROM {$wpdb->prefix}onlineintegrapay WHERE invoice_id = ".$lastid);
									$result['invoice'] = 'Invoice token already used.';
									$response = array(
							            'type' => 'failure',
							            'html'  => $result,
									);
								}else{
									$result['ERROR'] = 'something went wrong please try again';
									$response = array(
							            'type' => 'failure',
							            'html'  => $result,
									);
									
								}
							break;
						case 'OK':
							if(isset($integrapay_data['webPageToken'])){
								global $wpdb;
								$update = $wpdb->query("UPDATE {$wpdb->prefix}onlineintegrapay SET 
									token = '".$integrapay_data['webPageToken']."' WHERE invoice_id = ".$lastid);

								//$url ='https://testpayments.integrapay.com.au/RTP/Payment.aspx?webPageToken='.$integrapay_data['webPageToken'];
						 		$url = get_option('integrapay_integrapay_payment_response','').'?webPageToken='.$integrapay_data['webPageToken'];
						 		$response = array(
						            'type' => 'success',
						            'url'  => $url,
								);
							}else{
								$result['ERROR'] = 'Something went wrong please try again';
								$response = array(
						            'type' => 'failure',
						            'html'  => $result,
								);
							}
							break;
						default:
							# code...
							break;
					}
					
				}elseif ($data['payment_method'] == 2) {
					//call Bank method
					$integrapay_data = $this->createeDDR($lastid,$data['amount'],$data);
					switch ($integrapay_data['result']) {
						case 'ERROR':
								$result['ERROR'] = 'something went wrong please try again';
								$response = array(
						            'type' => 'failure',
						            'html'  => $result,
								);
							break;
						case 'OK':
							if(isset($integrapay_data['webPageToken'])){
								global $wpdb;
								$update = $wpdb->query("UPDATE {$wpdb->prefix}onlineintegrapay SET 
									token = '".$integrapay_data['webPageToken']."' WHERE invoice_id = ".$lastid);

								$bankintegrapay = $wpdb->prefix . 'integrapay_bank';
								$insert_id = $wpdb->insert( $bankintegrapay, array(
													'token' => $integrapay_data['webPageToken'],
											        'invoice' 				=> $data['invoice'],
											        'status' 				=> 1,
											       )
											    );
		    					$lastid = $wpdb->insert_id;
								
								//$url ='https://testpayments.integrapay.com.au/DDR/DDR.aspx?webPageToken='.$integrapay_data['webPageToken'];
								$url = get_option('eddr_url','').'?webPageToken='.$integrapay_data['webPageToken'];
						 		$response = array(
						            'type' => 'success',
						            'url'  => $url,
								);
							}else{
								$result['ERROR'] = 'Something went wrong please try again';
								$response = array(
						            'type' => 'failure',
						            'html'  => $result,
								);
							}
							break;
						default:
							break;
					}
					
				}
				

				
			}
			return $response;
			
		}

		/*
			use for create token for credit/debit card payment method
		*/
		public function createToken($transactionID,$total,$data){			
			$username 	= get_option('integrapay_payment_username','');
			$password 	= get_option('integrapay_payment_password','');
			$url 		= get_option('integrapay_payment_api','');
			$totals 	= $total * 100;
			$integrapay = get_option('woocommerce_integrapay_payment_settings' ,'');
			$input_xml 	= '<?xml version="1.0" encoding="utf-8"?>
							<request>
							<username>'.$username.'</username>
							<password>'.$password.'</password>
							<command>PreHostedRealTimePayment</command>
							<returnUrl>'.site_url('online-integrapay-payment-response').'</returnUrl>
							<transactionID>SANDBOXNC'.$data['invoice'].'</transactionID>
							<transactionAmountInCents>'.$totals.'</transactionAmountInCents>
							<payerFirstName>'.$data['name'].'</payerFirstName>
							<payerAddressStreet>'.$data['address'].'</payerAddressStreet>
							<payerEmail>'.$data['email'].'</payerEmail>
							</request>';		
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $input_xml );
			$data = curl_exec($ch);
			curl_close($ch);
			$array_data = json_decode(json_encode(simplexml_load_string($data)), true);
			return $array_data;
		}

		public function responseToken($webPageToken){
			global $wpdb;
			$username 	= get_option('integrapay_payment_username','');
			$password 	= get_option('integrapay_payment_password','');
			$url 		= get_option('integrapay_payment_api','');
			$input_xml = '<?xml version="1.0" encoding="utf-8"?>
						<request>
						<username>'.$username.'</username>
						<password>'.$password.'</password>
						<command>PostHostedRealTimePayment</command>
						<webPageToken>'.$webPageToken.'</webPageToken>
						</request>';
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $input_xml );
			$data = curl_exec($ch);
			curl_close($ch);    
			//convert the XML result into array
			$data = json_decode(json_encode(simplexml_load_string($data)), true);
			if($data['result'] == 'OK'){
				$integrapay = $wpdb->prefix . 'onlineintegrapay';
				$sql = "SELECT *  FROM ".$integrapay." WHERE token ='".$webPageToken."'";
    			$integrapayGet  = $wpdb->get_results($sql);
				$update = $wpdb->query("UPDATE {$wpdb->prefix}onlineintegrapay SET status =". self::STATUS_COMPLETED ."  WHERE token = '".$webPageToken . "'");
				$sql = "SELECT *  FROM {$wpdb->prefix}onlineintegrapay WHERE token ='".$webPageToken."' AND mail=1";
    			$mailres  = $wpdb->get_results($sql);
				if(empty($mailres)){
					$this->mailsend($data,$integrapayGet,$webPageToken);
				}
			}
			return $data;
		}

		public function validate($data){
			$error =array();
			foreach ($data as $key => $value) {
				switch ($key) {
					case 'payment_method':
						if($value == 0){
							$error[$key] = 'Please select payment method.';
						}
						break;
					case 'name':
					case 'address':
					case 'invoice':
						if(empty($value)){
							$error[$key] = 'Please fill the required field.';
						}
						break;
					case 'phone':
						if(empty($value)){
							$error[$key] = 'Please fill the required field.';
						}elseif(!preg_match("/(0|\+?\d{2})(\d{7,8})/", $value))
		    			{
		      				$error[$key] = 'Invalid Number!';
		    			}
						break;
					case 'amount':
						if(empty($value)){
							$error[$key] = 'Please fill the required field.';
						}elseif(!preg_match("/^\d+(?:\.\d{2})?$/", $value))
		    			{
		      				$error[$key] = 'Invalid Amount!';
		    			}
						break;
					case 'email':
						if(empty($value)){
							$error[$key] = "Please fill the required field!";
						}elseif (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
						    $error[$key] = $value ." is not a valid email address!";
						} 
						break;
					default:
						break;
				}
			}

			return $error;
		}
		
		public function mailsend($data,$dbData,$webPageToken){
			if($dbData){
				$dbdata = current($dbData);
				$data['phone'] 		= $dbdata->phone;
				$data['invoice'] 	= $dbdata->invoice;
			}

			$admin_email = "accounts@nc-electrical.com.au";
		  	
		    $headers= "MIME-Version: 1.0\n" .
	        "From: admin@nc-electrical.com.au\n".
	        "Content-Type: text/html; charset=utf-8\n". 
	        "CC:".$admin_email;

	        ob_start();
	        $theme_dir    = get_stylesheet_directory();
	        require_once( dirname( ONLINEINTEGRAPAY ) . '/inc/email/success.php' );
	        $message = ob_get_clean();

	        $subject = "Online Payment on NC Electrical";
	        $to = $data['payerEmail'];
	        // send the email using wp_mail()
	        wp_mail($to, $subject, $message, $headers);
	        global $wpdb;
	       	$wpdb->query("UPDATE {$wpdb->prefix}onlineintegrapay SET mail =1  WHERE token ='".$webPageToken."'");
		}

		//use to create eDDR
		function createeDDR($transactionID,$total,$data){
			$username 	= get_option('integrapay_payment_username','');
			$password 	= get_option('integrapay_payment_password','');
			$template_id = get_option('bank_template_id','');
			$url 		= get_option('integrapay_payment_api','');
			$input_xml = '<?xml version="1.0" encoding="utf-8"?>
			<request>
				<username>'.$username.'</username>
				<password>'.$password.'</password>
				<command>PreDDR</command>
				<returnUrl>'.site_url('thanks-onlinebankpayment').'</returnUrl>
				<eddrTemplateGUID>'.$template_id.'</eddrTemplateGUID>
				<payerUniqueID>'.strtotime("now").'</payerUniqueID>
				<payerFirstName>'.$data['name'].'</payerFirstName>
				<payerEmail>'.$data['email'].'</payerEmail>
				<payerMobile>'.$data['phone'].'</payerMobile>
			</request>';
			//$url = 'https://testpayments.integrapay.com.au/API/API.ashx';
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $input_xml );
			$data = curl_exec($ch);
			curl_close($ch);
			$array_data = json_decode(json_encode(simplexml_load_string($data)), true);
			return $array_data;
		}
	}
?>