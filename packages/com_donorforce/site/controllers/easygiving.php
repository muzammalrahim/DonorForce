<?php

/*------------------------------------------------------------------------
# com_timesheet  mod_timesheet
# ------------------------------------------------------------------------
# author    Pixako Web Designs & Development
# copyright Copyright (C) 2010 http://www.pixako.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.pixako.com
# Technical Support:  Contact - http://www.pixako.com/contact.html
-------------------------------------------------------------------------*/
defined('_JEXEC') or die();


class DonorforceControllerEasyGiving extends DonorforceController
{
	
	function display($cachable = false, $urlparams = false) { 
			parent::display();
   }	
		
		
	 function donate(){
			
		$post =  JRequest::get();
		$easyGivingModel = $this->getModel('EasyGiving');
		require_once JPATH_COMPONENT."/assets/payweb3/paygate.payweb3.php";
			
		if(JPluginHelper::isEnabled('payment', 'paygate')){
				$paygate = 	JPluginHelper::getPlugin('payment', 'paygate');
				$paygate->params = json_decode(	$paygate->params,	true	);
		}
		 
		$REFERENCE = 'eg_'.$post['name'].'_'.strtotime(date('Y-m-d H:i:s'));
		$RETURN_URL = JRoute::_(JUri::root()."index.php?option=com_donorforce&view=easygiving&layout=returnpaygate&reference=".$REFERENCE);
		$NOTIFY_URL = JRoute::_(JUri::root()."index.php?option=com_donorforce&task=easygiving.notifyeasygiving");
		//$NOTIFY_URL = 'http://ctsol.co/demos/testlogdata/logdata.php'; 
		 
		$AMOUNT = round($post['amount']).'00'; 
		$mandatoryFields = array(
			'PAYGATE_ID'        => filter_var($paygate->params['paygate_id'], FILTER_SANITIZE_STRING),
			'REFERENCE'         => filter_var($REFERENCE, FILTER_SANITIZE_STRING),
			'AMOUNT'            => filter_var($AMOUNT, FILTER_SANITIZE_NUMBER_INT),
			'CURRENCY'          => filter_var(JComponentHelper::getParams('com_donorforce')->get('addcurrency'), FILTER_SANITIZE_STRING),
			'RETURN_URL'        => filter_var($RETURN_URL, FILTER_SANITIZE_URL),
			'TRANSACTION_DATE'  => filter_var(date('Y-m-d H:i'), FILTER_SANITIZE_STRING),
			'LOCALE'            => filter_var('en-za',FILTER_SANITIZE_STRING),
			'COUNTRY'           => filter_var('ZAF', FILTER_SANITIZE_STRING),
			'EMAIL'             => filter_var($post['email'], FILTER_SANITIZE_EMAIL)
		);
 
	 	$optionalFields = array(
			'PAY_METHOD'        =>  '',
			'PAY_METHOD_DETAIL' => '',
			'NOTIFY_URL'        => (filter_var($NOTIFY_URL, FILTER_SANITIZE_URL)),
			'USER1'             => $post['name'],
			'USER2'             => $post['email'],
			'USER3'             => $post['project'],
			'VAULT'             => '',
			'VAULT_ID'          => ''
		); 

		$data = array_merge($mandatoryFields, $optionalFields);
		$encryption_key  = $paygate->params['secret'];  
		$PayWeb3 = new PayGate_PayWeb3();	 
		$PayWeb3->setEncryptionKey($encryption_key);	 
		$PayWeb3->setInitiateRequest($data);	 
		$payWeb3returnData = $PayWeb3->doInitiate();
		
		
		// echo " <pre>  payWeb3returnData  ";  print_r( 	$payWeb3returnData ); echo " </pre> ";   
		// echo " <pre>  PayWeb3  ";  print_r( 	$PayWeb3 ); echo " </pre> ";   
		$process_url = PayGate_PayWeb3::$process_url
		?>
    
    	<form role="form" class="form-horizontal text-left" action="<?php echo $process_url; ?>" method="post" name="paygate_process_form" id="paygate_process_form">
     
      <?php
			
		 		if(isset($PayWeb3->processRequest) || isset($PayWeb3->lastError)){
						/* We have received a response from PayWeb3
						   TextArea for display example purposes only. */
						 
						if (!isset($PayWeb3->lastError)) {
							$isValid = $PayWeb3->validateChecksum($PayWeb3->initiateResponse);
							if($isValid){
								/* If the checksums match loop through the returned fields and create the redirect from */
								foreach($PayWeb3->processRequest as $key => $value){
									echo <<<HTML
					<input type="hidden" name="{$key}" value="{$value}" />
HTML;
								}
							
							?>
              <h3 style="text-align:center;">Processing Your Request.</h3>
							<script>
              jQuery(document).ready(function(e) {
                jQuery('#paygate_process_form').submit();
              });
              </script>
							<?php
							} else {
								echo '<p>Checksums do not match</p>';
							}
						}
			} 
			?>
			</form>
			<?php
 
		}

		//PAYFAST FUNCTIONS!!!!

		function donate_payfast(){
			
		$post =  JRequest::get();
		$easyGivingModel = $this->getModel('EasyGiving');
		if(JPluginHelper::isEnabled('payment', 'payfast')){
				$payfast = 	JPluginHelper::getPlugin('payment', 'payfast');
				$payfast->params = json_decode(	$payfast->params,	true	);
		}
		
	    $name = str_replace(" ","_", $post['name']);
		$REFERENCE = 'eg_'.$name.'_'.strtotime(date('Y-m-d H:i:s'));
		$RETURN_URL = JRoute::_(JUri::root()."index.php?option=com_donorforce&view=easygiving&layout=returnpayfast&reference=".$REFERENCE.'&email='.$post['email']);
		$NOTIFY_URL = JRoute::_(JUri::root()."index.php?option=com_donorforce&task=easygiving.notifyPayfast");
		//$AMOUNT = round($post['amount']).'00'; 
		$AMOUNT = round($post['amount']); 
		 
		$sandbox = $payfast->params['sandbox']; 
		if($sandbox) {
			$call_back_url = 'ssl://sandbox.payfast.co.za';
			$url =  'sandbox.payfast.co.za/eng/process';
		} else {
			$call_back_url =  'ssl://www.payfast.co.za';
			$url =  'www.payfast.co.za/eng/process';
		}
		
		$secure = true; 
		if ($secure) { $url = 'https://' . $url; }	
		$merchant_id = $payfast->params['merchant_id']; 
		$merchant_key = $payfast->params['merchant_key']; 
		$name   = $post['name'];
		$email  = $post['email'];
		$project    =$post['project'];
		$item_name = 'Donation';
		$item_description = 'PayFast EasiGiving Donation';
		$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
		
		?>
    
    	<form role="form" class="form-horizontal text-left" action="<?php echo htmlentities($url)?>" method="post" name="payfast_process_form" id="payfast_process_form">  
      	<h3 style="text-align:center;">Processing Your Request.</h3> 
      <script>
				jQuery(document).ready(function(e) {
					jQuery('#payfast_process_form').submit();
				});
     </script>
     
    	 <!-- Receiver Details -->
      <input type="hidden" name="merchant_id" value="<?php echo $merchant_id ?>" />
      <input type="hidden" name="merchant_key" value="<?php echo $merchant_key ?>" />
      <input type="hidden" name="return_url" data-url="return_url" value="<?php echo $RETURN_URL ?>" />
      <input type="hidden" name="cancel_url" value="<?php echo $RETURN_URL ?>" />
      <input type="hidden" name="notify_url" value="<?php echo $NOTIFY_URL ?>" />
  
  		<!-- Payer Details -->
      <input type="hidden" name="name_first" value="<?php echo $name ?>" />
      <input type="hidden" name="name_last" value="" />
      <input type="hidden" name="email_address" value="<?php echo $email ?>" />
  		
      
      <!-- Transaction Details -->
      <input type="hidden" name="amount" value="<?php echo $AMOUNT; ?>" />
      <input type="hidden" name="item_name" value="<?php echo trim($item_name) ?>" />
      <input type="hidden" name="item_description" value="<?php echo trim($item_description) ?>" />
      <input type="hidden" name="custom_str1" value="<?php echo $txnid ?>" /> 
  
  		 <!-- Custom Data -->
  		 <input type="hidden" name="custom_str2" value="<?php echo $name; ?>" />
     	 <input type="hidden" name="custom_str3" value="<?php echo $email; ?>" />
    	 <input type="hidden" name="custom_int1" value="<?php echo $project; ?>" />
     	<input type="hidden" name="custom_str4" value="<?php echo  $REFERENCE; ?>" />
     
     </form>
			<?php
 
		}

	
	function notifyPayfast(){
			
		//echo back to payfast that ipn is received.
		header( 'HTTP/1.0 200 OK' );
		flush();
		
		if(JPluginHelper::isEnabled('payment', 'payfast')){
				$payfast = 	JPluginHelper::getPlugin('payment', 'payfast');
				$payfast->params = json_decode(	$payfast->params,	true	);
		}
			 
		$sandbox = $payfast->params['sandbox']; 
		if($sandbox) {
			//$call_back_url = 'ssl://sandbox.payfast.co.za';
			$pfHost =  'sandbox.payfast.co.za';
		} else {
			//$call_back_url =  'ssl://www.payfast.co.za';
			$pfHost =  'www.payfast.co.za';
		}
		
		 
		
			
		/*$pfData = array(
				'option' => 'com_donorforce',
				'task' => 'notifyPayfast',
				'm_payment_id' => '',
				'pf_payment_id' => '360152',
				'payment_status' => 'COMPLETE',
				'item_name' => 'Donation',
				'item_description' => 'PayFast EasiGiving Donation',
				'amount_gross' => '11.00',
				'amount_fee' => '-0.25',
				'amount_net' => '10.75',
				'custom_str1' => '303e49bb4678c098f03b',
				'custom_str2' => 'test',
				'custom_str3' => 'test@gmail.com',
				'custom_str4' => 'eg_test_1496175937',
				'custom_str5' => '',
				'custom_int1' => '5',
				'custom_int2' => '',
				'custom_int3' => '',
				'custom_int4' => '',
				'custom_int5' => ''	,
				'name_first' => 'Test',
			  	'name_last' => 'User 01',
			 	'email_address' => 'sbtu01@payfast.co.za',
			 	'merchant_id' => '10000100',
			  	'signature' => 'eae4265640dc301242cd86cc636b3404',
			  	'Itemid' => ''
			);	*/
 
 

		 $pfData = JRequest::get();
/*	 file_put_contents(dirname(__FILE__).'/payfast.txt', print_r($pfData, true), FILE_APPEND );
	   file_put_contents(dirname(__FILE__).'/payfast.txt','-------------------------------', FILE_APPEND );		*/
		 // echo " <pre> post  ";  print_r( $pfData ); echo " </pre> ";  
		 

		
		// Strip any slashes in data
		foreach( $pfData as $key => $val )
		{
				$pfData[$key] = stripslashes( $val );
		}
		
		// $pfData includes of ALL the fields posted through from PayFast, this includes the empty strings
		foreach( $pfData as $key => $val )
		{
				//echo " <br />   key = $key"; 
				if($key == 'm_payment_id') $pfParamString = '';
				if($key == 'Itemid') continue;  
				if( $key != 'signature' )
				{
						$pfParamString .= $key .'='. urlencode( $val ) .'&';
				}
		}
		  
		// Remove the last '&' from the parameter string
		$pfParamString = substr( $pfParamString, 0, -1 );
		$pfTempParamString = $pfParamString;
		echo " <br /><br /> pfTempParamString=$pfTempParamString";  
		//file_put_contents(dirname(__FILE__).'/payfast.txt','----------------pfTempParamString='.$pfTempParamString, FILE_APPEND );	
		
		// If a passphrase has been set in the PayFast Settings, then it needs to be included in the signature string.
		$passPhrase = ''; //You need to get this from a constant or stored in you website database
		 
	 
			
		/*if( !empty( $passPhrase ) )
		{
				$pfTempParamString .= '&passphrase='.urlencode( $passPhrase );
		}*/
		$signature = md5( $pfTempParamString );
		
		 echo " <br /><br />signature=$signature"; 
		// file_put_contents(dirname(__FILE__).'/payfast.txt','----------------signature='.$signature, FILE_APPEND );	
		 
		if($signature!=$pfData['signature'])
		{
				die('Invalid Signature');
		}
		
		echo " <br />   signature is correct ";  
		
		
			// Variable initialization
			$validHosts = array(
					'www.payfast.co.za',
					'sandbox.payfast.co.za',
					'w1w.payfast.co.za',
					'w2w.payfast.co.za',
			);
			 
			$validIps = array();			 
			foreach( $validHosts as $pfHostname )
			{
					$ips = gethostbynamel( $pfHostname );			 
					if( $ips !== false )
					{
							$validIps = array_merge( $validIps, $ips );
					}
			}
			 
			// Remove duplicates
		/*	$validIps = array_unique( $validIps );			 
			if( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ))
			{
					die('Source IP not Valid');
			}*/
			
			 echo " <br />  source ip is valid ";     
					
			/*$cartTotal = xxxx; //This amount needs to be sourced from your application
			if( abs( floatval( $cartTotal ) - floatval( $pfData['amount_gross'] ) ) > 0.01 )
			{
					die('Amounts Mismatch');
			}	*/	
			
			
			if( in_array( 'curl', get_loaded_extensions() ) )
			{
					// Variable initialization
					$url = 'https://'. $pfHost .'/eng/query/validate';
			 
					// Create default cURL object
					$ch = curl_init();
			 
					// Set cURL options - Use curl_setopt for freater PHP compatibility
					// Base settings
					curl_setopt( $ch, CURLOPT_USERAGENT, PF_USER_AGENT );  // Set user agent
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );      // Return output as string rather than outputting it
					curl_setopt( $ch, CURLOPT_HEADER, false );             // Don't include header in output
					curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			 
					// Standard settings
					curl_setopt( $ch, CURLOPT_URL, $url );
					curl_setopt( $ch, CURLOPT_POST, true );
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $pfParamString );
					curl_setopt( $ch, CURLOPT_TIMEOUT, PF_TIMEOUT );
					if( !empty( $pfProxy ) )
					{
							curl_setopt( $ch, CURLOPT_PROXY, $proxy );
					}      
					// Execute CURL
					$response = curl_exec( $ch );
					
					 
					
					curl_close( $ch );
					
					if($response == 'VALID'){ 
						echo " <br /> valid  ";
						switch( $pfData['payment_status'] ){
									 case 'COMPLETE':
									 //$this->updatePayfastTransaction($pfData);   
									 $transaction = true;                 
									 break;
		
						}
						
						if($transaction){
						 	// echo " <pre> result_data   ";  print_r( $result_data ); echo " </pre> ";   exit; 
								
								$name = $pfData['custom_str2'];
								$email = $pfData['custom_str3'];
								
								$easyGivingModel = $this->getModel('EasyGiving');
								$user_info  = $easyGivingModel->getUserFromEmail($email);
								// echo " <pre>  user_info  ";  print_r( $user_info ); echo " </pre> "; exit;   
								
								$insert_Data = '';	
								$insert_Data['project_id'] = (!empty($pfData['custom_int1']))? $pfData['custom_int1'] : '' ;
								$insert_Data['Reference'] = (!empty($pfData['custom_str4']))? $pfData['custom_str4'] : '' ;						 
								$insert_Data['amount'] = (!empty($pfData['amount_gross']))? $pfData['amount_gross'] : '' ;
								$insert_Data['donation_type'] =  'onceoff' ;
								
								$TRANSACTION_STATUS = $pfData['payment_status'];
								$insert_Data['status'] = ($payment_status == '1')? 'successful' : 'pending' ;								
								$insert_Data['donor_id'] = $insert_Data['cms_user_id'] = '';
								$insert_Data['PAY_REQUEST_ID'] = $pfData['pf_payment_id'];
								$insert_Data['name'] = $pfData['name_first'];
								$insert_Data['email'] = $pfData['custom_str3']; 
								$insert_Data['amount'] = $pfData['amount_gross']; 
								 	
								//echo " <pre>   insert_Data  ";  print_r( $insert_Data ); echo " </pre> "; exit;   	
									
								if(!empty($user_info) && $user_info->donor_id != '' ){
									$insert_Data['donor_id']  = (!empty( $user_info->donor_id ))? $user_info->donor_id :  ''; 
									$insert_Data['cms_user_id'] = (!empty( $user_info->cms_user_id ))? $user_info->cms_user_id :  ''; 	
								}
								
								$history_id = $easyGivingModel->insertEasyDonation($insert_Data);
								if($history_id){
										$insert_Data['history_id'] = $history_id; 
										$easyGivingModel->sendThankYou($insert_Data);
								}
								  
								// echo " <pre>  insert  ";  print_r(  $insert ); echo " </pre> ";   	
					}
					}
				}		
	     }
		
		
		
		
		
		
		
		//function to capture the ipn from paygate. 
		function notifyeasygiving(){
			
			//echo back to paygate that ipn is received.
			error_log(file_get_contents('php://input'));
			echo 'OK';
	
			

			
				 $post = JRequest::get();
			/*	$post = array(
				'PAYGATE_ID' => '10011072130',
				'PAY_REQUEST_ID' => '862A55A5-F7C0-9A7E-BDB2-618527710A2C',
				'REFERENCE' => 'eg_MANDLA GQADA',
				'TRANSACTION_STATUS' => '1',
				'RESULT_CODE' => '990017',
				'AUTH_CODE' => '10BIZ1',
				'CURRENCY' => 'ZAR',
				'AMOUNT' => '2200',
				'RESULT_DESC' => 'Auth Done',
				'TRANSACTION_ID' => '46131869',
				'RISK_INDICATOR' => 'AX',
				'PAY_METHOD' => 'CC',
				'PAY_METHOD_DETAIL' => 'Visa',
				'USER1' => 'MANDLA GQADA',
				'USER2' => 'arsalan7720@gmail.com',
				'USER3' => '1',
				'CHECKSUM' => '706734dcef6b2a3fbd8fcd46bb3e5c12'		
			);	*/
				
			//echo " <pre>  dirname(__FILE__) paygate.txt = ";  print_r( dirname(__FILE__).'/paygate.txt'  ); echo " </pre> ";   	
				
			// file_put_contents(dirname(__FILE__).'/paygate.txt', print_r($post, true), FILE_APPEND );
			// file_put_contents(dirname(__FILE__).'/paygate.txt','-------------------------------', FILE_APPEND );		
			 //echo " <pre> post  ";  print_r( $post ); echo " </pre> ";   	 exit; 
				 
				
				if($post['PAY_REQUEST_ID'] != ''){
					require_once JPATH_COMPONENT."/assets/payweb3/paygate.payweb3.php";				
					if(JPluginHelper::isEnabled('payment', 'paygate')){
							$paygate = 	JPluginHelper::getPlugin('payment', 'paygate');
							$paygate->params = json_decode(	$paygate->params,	true	);
					}
					
					$data = array(
						'PAYGATE_ID'     => $post['PAYGATE_ID'],
						'PAY_REQUEST_ID' => $post['PAY_REQUEST_ID'],
						'REFERENCE'      => $post['REFERENCE']
					);
			
					$encryption_key = $paygate->params['secret'];
					$PayWeb3 = new PayGate_PayWeb3();
					$PayWeb3->setEncryptionKey($encryption_key);
					$PayWeb3->setQueryRequest($data);
					$returnData = $PayWeb3->doQuery();
					if($returnData){
						$result_data = $PayWeb3->queryResponse; 
						if( !empty($result_data)){
								// echo " <pre> result_data   ";  print_r( $result_data ); echo " </pre> ";   exit; 
								
								$name = $result_data['USER1'];
								$email = $result_data['USER2'];
								
								$easyGivingModel = $this->getModel('EasyGiving');
								$user_info  = $easyGivingModel->getUserFromEmail($email);
								// echo " <pre>  user_info  ";  print_r( $user_info ); echo " </pre> "; exit;   
								
								$insert_Data = '';
								$insert_Data['project_id'] = (!empty($result_data['USER3']))? $result_data['USER3'] : '' ;
								$insert_Data['Reference'] = (!empty($result_data['REFERENCE']))? $result_data['REFERENCE'] : '' ;
								$insert_Data['amount'] = (!empty($result_data['AMOUNT']))? $result_data['AMOUNT'] : '' ;
								$insert_Data['donation_type'] =  'onceoff' ;
								
								$TRANSACTION_STATUS = $result_data['TRANSACTION_STATUS'];
								$insert_Data['status'] = ($TRANSACTION_STATUS == '1')? 'successful' : 'pending' ;								
								$insert_Data['donor_id'] = $insert_Data['cms_user_id'] = '';
								$insert_Data['PAY_REQUEST_ID'] = $result_data['PAY_REQUEST_ID'];
								$insert_Data['name'] = $result_data['USER1'];
								$insert_Data['email'] = $result_data['USER2']; 
								$insert_Data['amount'] = $result_data['AMOUNT']/100; 
								 	
								//echo " <pre>   insert_Data  ";  print_r( $insert_Data ); echo " </pre> "; exit;   	
									
								if(!empty($user_info) && $user_info->donor_id != '' ){
									$insert_Data['donor_id']  = (!empty( $user_info->donor_id ))? $user_info->donor_id :  ''; 
									$insert_Data['cms_user_id'] = (!empty( $user_info->cms_user_id ))? $user_info->cms_user_id :  ''; 	
								}
								
								$history_id = $easyGivingModel->insertEasyDonation($insert_Data);
								if($history_id){
										$insert_Data['history_id'] = $history_id; 
										$easyGivingModel->sendThankYou($insert_Data);
								}
								
								// echo " <pre>  insert  ";  print_r(  $insert ); echo " </pre> ";   
								
						}
						
					}
							
					
				}
			
		}
		
		
		
		
}