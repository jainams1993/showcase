<?php 
add_shortcode('checkpayment', 'checkpaymentresponse');
function checkpaymentresponse(){
	//$xmlfile = file_get_contents(dirname( ONLINEINTEGRAPAY ).'/inc/admin/live_response.xml');
	header('Content-Type: text/plain');
	//echo "IntegraPay PHP Bank Payment Example\r\n==============================================\r\n\r\n";
	$serviceUrl = "https://paymentsapi.io/basic/PayLinkService.svc?WSDL";

	//***SET EACH OF THE SERVICE INPUT PARAMETERS FROM YOUR SYSTEM AND USER INPUT HERE
	//(see API documentation for a description of each of these fields)***:
	$username = "10256";
	$password = "8Hw%*6TmC!b";
	try
	{
	// Set a parameter array to be passed into the web service:
	  $params = new stdClass;
	  $params->username = $username;
	  $params->password = $password;

	  	// Call the web service function:
		$client = new SoapClient($serviceUrl);
		$response = $client->GetNewStatusUpdatesXml($params);
		
	  	$resultXml = $response->GetNewStatusUpdatesXmlResult;
		
		doxmltoarray($resultXml);
	  	//$result = simplexml_load_string($resultXml);
	}
	//Catch errors received from the web service server while processing the function:
	catch(SoapFault $e)
	{
		$error = $e->getMessage();
		
		//***INSERT CODE TO PROCESS SERVICE FAULTS HERE***:
		echo "Soap Fault Occured: " . $error . "\r\n\r\n";

		//OPTIONAL - If you wish, it is possible to parse the error message to
		//determine the type of exception that was caught and process each type programmatically:
		if ($error == "The username/password is incorrect or has not been granted access to this function")
		{
			//***INSERT CODE TO PROCESS UNAUTHORIZED ACCESS HERE***:
			echo "UnauthorizedAccessFault";
		}
		else if ($error == "A transaction with the transactionID you have provided already exists")
		{
			//**INSERT CODE TO PROCESS DUPLICATE TRANSACTION ERRORS HERE***:
			echo "DuplicateTransactionFault";
		}
		else if (substr_count($error, "Parameter name:") > 0)
		{
			//**INSERT CODE TO PROCESS INVALID PARAMETERS HERE***:
			$parameterName = substr($error, strpos($error, "Parameter name: ") + 16);
			echo "ArgumentMissingFault or ArgumentInvalidFault for parameter: " . $parameterName;
		}
		else
		{
			//**INSERT CODE TO HANDLE ALL OTHER SERVICE FAULTS HERE***:
			//(usually communication dropouts, timeouts or temporary service unavailability)***:
			echo "General Soap Fault";
		}
	}
	//Catch all other exceptions not received from the web service server:
	catch(Exception $e)
	{
		$error = $e->getMessage();
		
		//***INSERT CODE TO PROCESS GENERAL ERRORS HERE***:
		echo "General Error Occured: " . $error;
	}

	
}

function doxmltoarray($xmlfile){

	try {
		$xmlfile = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xmlfile);
		$response = XML2Array::createArray($xmlfile);

		// echo 'XML2Array :';
		//print_r($array['StatusUpdates']['StatusUpdate']);

		// echo "<br>";
		// die;
		foreach ($response['StatusUpdates'] as $key => $res) {
			foreach ($res as $r_key => $r_value) {
				if(is_array($r_value)){
					$payer_id = $r_value['PayerUniqueID'];
					$status = $r_value['TransactionStatus'];
					updatePaymentStatus($payer_id,$status);
				}else{
					$payer_id = $res['PayerUniqueID'];
					$status = $res['TransactionStatus'];
					updatePaymentStatus($payer_id,$status);
					break;
				}
			}	
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function updatePaymentStatus($payer_id,$status){

	switch ($status) {
		case 'P':
			$status_val = 5;
			break;
		case 'C':
		case 'S':
			$status_val = 1;
			break;
		case 'L':
		case 'R':
		case 'RF':
			$status_val = 3;
			break;
		default:
			$status_val = 2;
			break;
	}
	global $wpdb;
	$bankintegrapay = $wpdb->prefix . 'integrapay_bank';
	$sql = "SELECT `token`  FROM `".$bankintegrapay."` WHERE `payerUniqueID` ='".$payer_id ."'";
	$getCurrentToken = $wpdb->get_row($sql);
	
	$onlineintegrapay = $wpdb->prefix . 'onlineintegrapay';
	$wpdb->query("UPDATE {$onlineintegrapay} SET status = '".$status_val."' WHERE token = '".$getCurrentToken->token."'");
	$wpdb->query("UPDATE {$bankintegrapay} SET status = '".$status_val."' WHERE token = '".$getCurrentToken->token."'");
}
?>