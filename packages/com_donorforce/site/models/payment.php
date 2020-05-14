<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
class DonorforceModelPayment extends JModelLegacy
{
	function confirmpayment($pg_plugin,$oid)
	{
		$post	= JRequest::get('post');
		$vars = $this->getPaymentVars($pg_plugin,$oid);
		if(!empty($post) && !empty($vars) ){
			JPluginHelper::importPlugin('payment', $pg_plugin);
			$dispatcher = JDispatcher::getInstance();
			$result = $dispatcher->trigger('onTP_ProcessSubmit', array($post,$vars));
		}
		else{
			JFactory::getApplication()->enqueueMessage(JText::_('SOME_ERROR_OCCURRED'), 'error');
		}
		//die("000");
	}
function processpayment($post,$pg_plugin,$order_id)
	{	

		$tjcpgHelper = new tjcpgHelper;
		//	GETTING MENU Itemid
		$jinput=JFactory::getApplication()->input;
		$jinput->set('remote',1);
		
		//$sacontroller = new quick2cartController();
		//$sacontroller->execute('clearcart');
		$orderItemid = $tjcpgHelper->getitemid('index.php?option=com_donorforce&view=payment');
		$chkoutItemid=$orderItemid;
		//$chkoutItemid = $comquick2cartHelper->getitemid('index.php?option=com_quick2cart&view=cartcheckout');
		$return_resp=array();

		//Authorise Post Data
		if(!empty($post['plugin_payment_method']) && $post['plugin_payment_method']=='onsite')
			$plugin_payment_method=$post['plugin_payment_method'];
		
		$vars = $this->getPaymentVars($pg_plugin,$order_id);
		
		//START :: TRIGGER PAYMENT PLUGIN
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('payment', $pg_plugin);
		$data = $dispatcher->trigger('onTP_Processpayment', array($post,$vars));
		//echo "<pre> Plugin Return data"; print_r($data);  echo "</pre>"; //exit;  
		
		$data = $data[0];
		//END :: TRIGGER PAYMENT PLUGIN
		
		// Add details in log file
		$res=@$this->storelog($pg_plugin,$data);
	
		//get order id
		if(empty($order_id))
			$order_id=$data['order_id'];
		
		// RIGHT NOW WE R NOT ADDING CODE FOR GUEST USER
		$guest_email="";
		$data['processor']=$pg_plugin;
		$data['status']=trim($data['status']);
		
		
		if(empty($data['status'])){
			$data['status'] = 'P';
			$return_resp['status']='0';
		}
		else if($data['status']=='C' )
		{
			$data['status'] = 'C';
			$return_resp['status']='1';
		}
		/*else if($order_amount != $data['total_paid_amt']){
			$data['status'] = 'E';
			$return_resp['status']='0';
		}*/
	// IF NOT CONFORM ORDER GET ERORR MSG
		if($data['status']!='C' && !empty($data['error'])  && $data['processor'] != 'payfast'){
			$return_resp['msg']=$data['error']['code']." ".$data['error']['desc'];
		}
		//echo " <pre>   data  ";  print_r( $data ); echo " </pre> "; 
		//echo " <pre>   post  ";  print_r( $post ); echo " </pre> "; 
		$this->updateOrder($data);
		$donation_model = JModelLegacy::getInstance('Donation', 'DonorforceModel'); 
		/*echo '<pre>';
		print_r($post);
		print_r($data);
		echo '</pre>';
		exit;*/

			$donation_id = $post['donation_id'];	
			$session = JFactory::getSession(); 	
			 //echo " <pre>   post  ";  print_r( $post ); echo " </pre> "; 		
			if($donation_id == '' )	{$donation_id	= $session->get('donation_history_id');}

		if(!empty($data['status']) && $data['status']=='C'){
			
			/* echo "<br / >  saving record "; 
			 echo " <pre>   data  ";  print_r( $data ); echo " </pre> "; 
			  echo " <pre>   post  ";  print_r( $post ); echo " </pre> ";  
			  exit; */
			  //echo " <pre>   post  ";  print_r( $post ); echo " </pre> "; 
			  $update['Reference'] = $post['REFERENCE']  ;//"C".$data['cms_user_id']."-".date("His");			
			if(empty($post['REFERENCE'])){
				$donation_history_data = $donation_model->get_donation_history($donation_id);
				$update['Reference'] = $post['REFERENCE'] = $donation_history_data->Reference;
			}
			
			 //echo " <pre>   post  ";  print_r( $post ); echo " </pre> "; 
			$update['donation_history_id'] = $donation_id;
			//$update['cms_user_id'] = $session->get('cms_user_id'); 
			//echo " <pre> update donation_history_id = ".$data['donation_history_id'];
			//echo " <br /> update cms_user_id   = ".$session->get('cms_user_id'); //exit; 
		
			
			//echo "<br />  Reference  = ".$data['Reference']; //exit; 			
			$update['status'] = 'successful';	
			if($data['processor'] == 'eft'){ 
					$update['status'] = 'pending'; 
			}	
			if($data['processor'] == 'eft' || $data['processor'] == 'snapscan'){ 
				//unset($update['Reference']); 
			}	
			
					
			/*echo " <pre>   donation_id  ";  print_r( $donation_id ); echo " </pre> "; 
			echo " <pre>   update  ";  print_r( $update ); echo " </pre> "; 
			echo " <pre>   post  ";  print_r( $post ); echo " </pre> "; 
			echo " <pre>   data  ";  print_r( $data ); echo " </pre> "; 
			echo " <pre>   vars  ";  print_r( $vars ); echo " </pre> "; 
			exit;*/
			$onceoffresult  = $donation_model->saveOnceOffNotity_2($update);
			file_put_contents(dirname(__FILE__).'/payfast.txt', print_r($post, true), FILE_APPEND );
			if(isset($post['taskaction']) && $post['taskaction'] == 'return')
			{

			}
			else{
				if($update['status'] != 'pending'){
					if($vars->user_id){
						$this->sendemail($vars->user_id);
					}else{	
						$this->sendemail();
					}
				}
			}
			
		}
		//$comquick2cartHelper->updatestatus($order_id,$data['status']);
		$return_resp['return']=JURI::root().substr(JRoute::_("index.php?option=com_donorforce&view=orders&layout=order".$guest_email."&orderid=".($order_id)."&processor={$pg_plugin}&status=".$data['status']."&Itemid=".$orderItemid,false),strlen(JURI::base(true))+1);	
		return $return_resp;
	}
//Reccuring status
function processpaymentRec($post,$pg_plugin,$order_id)
	{
		$guest_email = '';
		//if donationtype == recurringDO then no need for plugin just send email
		if(JFactory::getApplication()->input->get('donationtype') == 'recurringDO'){	
		//echo "processpaymentRec recurringDO <pre>";  print_r($post);   exit; 
				
				//$this->sendemailRec();				
				$return_resp['return']=JURI::root().substr(JRoute::_("index.php?option=com_donorforce&view=orders&layout=order".$guest_email."&orderid=".($order_id)."&status=C",false),strlen(JURI::base(true))+1);	
				return $return_resp;					
		}else{		
				$tjcpgHelper = new tjcpgHelper;
				//	GETTING MENU Itemid
				$jinput=JFactory::getApplication()->input;
				$jinput->set('remote',1);
				
				//$sacontroller = new quick2cartController();
				//$sacontroller->execute('clearcart');
				$orderItemid = $tjcpgHelper->getitemid('index.php?option=com_donorforce&view=payment');
				$chkoutItemid=$orderItemid;
				//$chkoutItemid = $comquick2cartHelper->getitemid('index.php?option=com_quick2cart&view=cartcheckout');
				$return_resp=array();
		
				//Authorise Post Data
				if(!empty($post['plugin_payment_method']) && $post['plugin_payment_method']=='onsite')
				$plugin_payment_method=$post['plugin_payment_method'];
				$vars = $this->getPaymentVars($pg_plugin,$order_id);
				//START :: TRIGGER PAYMENT PLUGIN
				$dispatcher = JDispatcher::getInstance();
				JPluginHelper::importPlugin('payment', $pg_plugin);
				$data = $dispatcher->trigger('onTP_Processpayment', array($post,$vars));
				//echo "<pre> Plugin Return data"; print_r($data);  echo "</pre>"; //exit;  
				
				$data = $data[0];
				//END :: TRIGGER PAYMENT PLUGIN		
				// Add details in log file
				$res=@$this->storelog($pg_plugin,$data);
			
				//get order id
				if(empty($order_id))
				$order_id=$data['order_id'];
				
				// RIGHT NOW WE R NOT ADDING CODE FOR GUEST USER
				$guest_email="";
				$data['processor']=$pg_plugin;
				$data['status']=trim($data['status']);
		
				if(empty($data['status'])){
				$data['status'] = 'P';
				$return_resp['status']='0';
				}
				else if($data['status']=='C' )
				{
				$data['status'] = 'C';
				$return_resp['status']='1';
				}
				/*else if($order_amount != $data['total_paid_amt']){
					$data['status'] = 'E';
					$return_resp['status']='0';
				}*/
				// IF NOT CONFORM ORDER GET ERORR MSG
				if($data['status']!='C' && !empty($data['error']) ){
					$return_resp['msg']=$data['error']['code']." ".$data['error']['desc'];
				}
				$this->updateOrder($data);
				$this->updateRecSubscription($post);
				if(!empty($data['status']) && $data['status']=='C'){
					
				$update['status'] = 'successful';
					
					/*//echo "<br / >  saving record ";  exit; 
					$session = JFactory::getSession();
					$update['donation_history_id'] = $session->get('donation_history_id');
					$update['cms_user_id'] = $session->get('cms_user_id'); 
					//echo " <pre> update donation_history_id = ".$data['donation_history_id'];
					//echo " <br /> update cms_user_id   = ".$session->get('cms_user_id'); //exit; 			
					$update['Reference'] = "C".$data['cms_user_id']."-".date("His");
					//echo "<br />  Reference  = ".$data['Reference']; //exit; 			
					$update['status'] = 'successful';			
					$donation_model = JModelLegacy::getInstance('Donation', 'DonorforceModel'); 
					$onceoffresult  = $donation_model->saveOnceOffNotity_2($update);*/
					
					//$this->sendemail();
				//	$this->sendemailRec();
				}		
				//$comquick2cartHelper->updatestatus($order_id,$data['status']);
				$return_resp['return']=JURI::root().substr(JRoute::_("index.php?option=com_donorforce&view=orders&layout=order".$guest_email."&orderid=".($order_id)."&processor=&status=".$data['status'],true),strlen(JURI::base(true))+1);	
				return $return_resp;
	
			}
	}



	function processpaymentRCO($post,$pg_plugin,$order_id)
	{
		$guest_email ='';
		$vars = $this->getPaymentVars($pg_plugin,$order_id);
		
		//START :: TRIGGER PAYMENT PLUGIN
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('payment', $pg_plugin);
		$data = $dispatcher->trigger('onTP_Processpayment', array($post,$vars));

		$return_resp['return']=JURI::root().substr(JRoute::_("index.php?option=com_donorforce&view=orders&layout=order".$guest_email."&orderid=".($order_id)."&status=C",false),strlen(JURI::base(true))+1);
		
		//if donationtype == recurringDO then no need for plugin just send email
		 			
		 
		 if($data['status']=='C' )
				{
				$data['status'] = 'C';
				$return_resp['status']='1';
				}
				
				if($data['status']!='C' && !empty($data['error']) ){
					$return_resp['msg']=$data['error']['code']." ".$data['error']['desc'];
				}
				$this->updateOrder($data);
				$this->updateRecSubscription($post);
				if(!empty($data['status']) && $data['status']=='C'){
					
				$update['status'] = 'successful';
 
				}		
				//$comquick2cartHelper->updatestatus($order_id,$data['status']);
				$return_resp['return']=JURI::root().substr(JRoute::_("index.php?option=com_donorforce&view=orders&layout=order".$guest_email."&orderid=".($order_id)."&processor=&status=".$data['status'],true),strlen(JURI::base(true))+1);	
				return $return_resp;
	
			 
	} 

//Reccuring status end 
	function store($post)
	{	
	//echo "<br /> store  <pre>"; print_r($post); exit;  
		$db= JFactory::getDBO();
		$user = JFactory::getUser();			
		$row = new stdClass;
		
		// GETTING DATE AND TIME
		$timestamp	= date("Y-m-d H:i:s");
		// Get the IP Address
		if (! empty ( $_SERVER ['REMOTE_ADDR'] )) {
			$ip = $_SERVER ['REMOTE_ADDR'];
		} else {
			$ip = 'unknown';
		}
		
		$row->payee_id 				= $user->id;
		$row->user_info_id 			= $user->id;
		$row->name 	=$user->name;
		$row->email =$user->email;
		// FINAL AMOUNT
		$row->amount 			= $post['amount'];
		
		// ORIGINAL AMT FOR PRODUCT/ ITEMS    // we are not considering tax and shipping charges
		$row->original_amount 		= $post['amount'] ;
		
		//NOT CONSIDERING TAX, ADD ACCORDING TO YOUR NEED
		$row->order_tax 			= 0;
		$row->order_tax_details 	= '';		
		//NOT CONSIDERING SHIPPING, ADD ACCORDING TO YOUR NEED
		$row->order_shipping 		=0;
		$row->order_shipping_details 	='';		
		//NOT CONSIDERING COUPON, ADD ACCORDING TO YOUR NEED
		$row->coupon_code  		= '';
		$row->customer_note 		= '';
		$row->processor 		= $post['gateways'];	
		$row->cdate 				= $timestamp;
		$row->mdate 				= $timestamp;
		$row->ip_address 			= $ip;
		
		// GETTING CURRENCY FROM COMPONENT PARAMS
		$params = JComponentHelper::getParams('com_donorforce');
		$row->currency			= $params->get("addcurrency","USD");
		$row->donation_history_id = $post['donation_history_id']; 
		if($post['rec_donation_subscription_id'] != '') $row->rec_donation_subscription_id = $post['rec_donation_subscription_id']; 
		if($post['rec_donation_debitorder_id'] != '') $row->rec_donation_debitorder_id = $post['rec_donation_debitorder_id']; 


		 

		if(!$db->insertObject('#__donorforce_orders',$row,'id'))
		{
			echo $db->stderr();
			return 0;
		}	
		return $insert_order_id=$db->insertid();
	}


	function getOrderInfo($order_id)
	{
		$db = JFactory::getDBO();
		$query="SELECT * FROM `#__donorforce_orders` WHERE `id`=".$order_id;
		$db->setQuery($query);
		return $order_result = $db->loadObject();
	}
	
	function getDonationHistoryInfo($history_id){
		
		$db = JFactory::getDBO();
		$query="
		SELECT
			#__donorforce_project.`name` AS project_name,
			#__donorforce_project.`image` AS project_image,
			#__donorforce_history.date,
			#__donorforce_history.amount,
			#__donorforce_history.`status`,
			#__donorforce_history.`donation_type`,
			#__donorforce_project.project_id,
			#__donorforce_donor.donor_id,
			#__donorforce_history.`Reference`,
			#__donorforce_history.donor_history_id
		FROM
			#__donorforce_history
		INNER JOIN #__donorforce_project ON #__donorforce_history.project_id = #__donorforce_project.project_id
		INNER JOIN #__donorforce_donor ON #__donorforce_donor.donor_id = #__donorforce_history.donor_id
		WHERE
			#__donorforce_history.donor_history_id = ".$history_id; 		
		
		$db->setQuery($query);
		return $history_info = $db->loadObject();
	}
	function getRecDonationInfo($id){
		$db = JFactory::getDBO();
		$query="
		SELECT
			#__donorforce_project.`name` AS project_name,
			#__donorforce_project.`image` AS project_image,		
			#__donorforce_donor_subscriptions.amount,
			#__donorforce_donor_subscriptions.`donation_type`,
			#__donorforce_project.project_id,
			#__donorforce_donor.donor_id,
			#__donorforce_donor_subscriptions.subscription_id,
			#__donorforce_donor_subscriptions.donation_start_date,
			#__donorforce_donor_subscriptions.donation_end_date,
			#__donorforce_donor_subscriptions.frequency,
			#__donorforce_donor_subscriptions.deduction_day	
		FROM
			#__donorforce_donor_subscriptions 
		LEFT JOIN #__donorforce_project  ON #__donorforce_donor_subscriptions.project_id = #__donorforce_project.project_id
		LEFT JOIN #__donorforce_donor ON #__donorforce_donor_subscriptions.donor_id = #__donorforce_donor.donor_id
		WHERE
			#__donorforce_donor_subscriptions.subscription_id = ".$id; 		
		//echo $query; 
		$db->setQuery($query);
		return $history_info = $db->loadObject();
	}	
	/**
	 * @params
	 * 			$pg_plugin - plugin name
	 * 			$tid - order id
	 * @return - HTML from payment gateway
	 * */
	function getHTML($pg_plugin,$tid,$donationtype,$donation_history_infor=null)
	{
		// GETTING PAYMENT FORM VARIABLES
		$vars = $this->getPaymentVars($pg_plugin,$tid);
		if($donation_history_infor->amount != ''){ $vars->amount = $donation_history_infor->amount; } 
		if($donation_history_infor->Reference != ''){ $vars->Reference = $donation_history_infor->Reference; }
		
		//if($donationtype == 'recurringCO' || $donationtype == 'recurringDO'  ){
		if($donationtype == 'recurringCO' ){	
			$vars->is_recurring = 1;
			//echo "<pre>  donation_history_infor = "; print_r($donation_history_infor); echo "</pre>"; exit;  
			$vars->SUBS_START_DATE = $donation_history_infor->donation_start_date;
			$vars->SUBS_END_DATE = $donation_history_infor->donation_end_date;
			$vars->frequency = $donation_history_infor->frequency;
			$vars->deduction_day = $donation_history_infor->deduction_day;		
		}
		
		//GETTING PAYMENT HTML
		JPluginHelper::importPlugin('payment', $pg_plugin);
		$dispatcher = JDispatcher::getInstance();
		//echo '<pre>'; print_r($vars); exit;
		$html = $dispatcher->trigger('onTP_GetHTML', array($vars));
		return $html;
	}
	/**
	 * @params
	 * 			$pg_plugin - plugin name
	 * 			$oid - order id
	 * @return - HTML from payment gateway
	 * */
	
	function getPaymentVars($pg_plugin, $orderid)
	{
		$tjcpgHelper = new tjcpgHelper;
		
		//	GETTING MENU Itemid
		$params = JComponentHelper::getParams( 'com_donorforce' );
		$orderItemid = $tjcpgHelper->getitemid('index.php?option=com_donorforce&view=payment');
		$pass_data = $this->getOrderInfo($orderid);
		$vars = new stdClass;
		$vars->order_id = $orderid;
		$vars->user_id=$pass_data->user_info_id;
		$vars->user_firstname = $pass_data->name;
		$vars->user_email = $pass_data->email;
		$vars->phone =!empty($pass_data->phone)?$pass_data->phone: '';
		$vars->item_name = "Test Techjoomla Product";  //  order prod name
		$vars->payment_description = JText::_('COM_EWALLET_ORDER_PAYMENT_DESC');		
		$donation = '&donation_id='; 		
		$donation .= JRequest::getVar('donation');
		if(JRequest::getVar('Recdonationid') != ''){
		$donation = '&recdonation_id=';
		$donation .= JRequest::getVar('Recdonationid');
		}
		// URL SPECIFICATIONS
		$vars->submiturl = JRoute::_("index.php?option=com_donorforce&controller=payment&task=confirmpayment&processor={$pg_plugin}");
		/*$vars->return = JURI::root().substr(JRoute::_("index.php?option=com_donorforce&view=orders&layout=order&orderid=".($orderid)."&processor={$pg_plugin}&Itemid=".$orderItemid,false),strlen(JURI::base(true))+1);*/		
		$vars->cancel_return = JURI::root().substr(JRoute::_("index.php?option=com_donorforce&view=orders&layout=cancel&processor={$pg_plugin}&Itemid=".$orderItemid,false),strlen(JURI::base(true))+1);
		$vars->return=$vars->url=$vars->notify_url=JRoute::_(JURI::root()."index.php?option=com_donorforce&task=payment.processpayment&order_id=".($orderid)."&processor=".$pg_plugin.$donation,false);
	
		$vars->currency_code = $pass_data->currency;
		$vars->comment = $pass_data->customer_note;
		$vars->amount = $pass_data->amount;
		return $vars;
	}
		
function storelog($name,$data)
{
	//echo "<br /> Payment Model -> storelog "; 			
  $data1=array();
  $data1['raw_data']=$data['raw_data'];
	$data1['JT_CLIENT']="com_donorforce";
	$dispatcher = JDispatcher::getInstance();
	JPluginHelper::importPlugin('payment', $name);
	$data = $dispatcher->trigger('onTP_Storelog', array($data1));	
}
function updateOrder($data)
{
		//echo "<br /> Payment Modle  updateOrder "; //exit; 
		$db= JFactory::getDBO();
		$res = new stdClass();
		$eoid=$data['order_id']; // $eoid means extracted order id
		$res->id = $eoid;
		$res->mdate 			= date("Y-m-d H:i:s"); 
		$res->transaction_id 	= $data['transaction_id']; 
		$res->status 	  		= $data['status'];
		$res->processor 		= $data['processor']; 
		//$res->payee_id			= $data['buyer_email'];
		//appending raw data to orders's extra field data
		$tjcpgHelper = new tjcpgHelper;
		//$res->extra = $tjcpgHelper->appendExtraFieldData($data['raw_data'],$eoid);
		if(!$db->updateObject( '#__donorforce_orders', $res, 'id' )) 
		{
			//return false;
		}
  }
	
function updateRecSubscription($data){
	//echo "<pre> updateRecSubscription  data = "; print_r($data); exit; 
	if($data['recdonation_id'] != '' && $data['SUBSCRIPTION_ID'] != ''){
			$data['transaction_id'] = $data['SUBSCRIPTION_ID'];
		$db =& JFactory::getDBO();
		$query = "UPDATE #__donorforce_donor_subscriptions SET transaction_id =".$data['transaction_id']." 
							WHERE subscription_id=".$data['recdonation_id'];
		$db->setQuery( $query );
		$db->query();
	}
}	
  
  
// Send Email for onceoff donation  
function sendemail($user_id=0){
	//print_r($user_id);
	//exit;
	
jimport('joomla.application.component.helper');
if(JComponentHelper::getParams('com_donorforce')->get('send_thankyou') == 0){
	return false;	
}
		
//require_once JPATH_COMPONENT."/assets/dompdf/dompdf_config.inc.php";	
require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';
$item = DonorForceHelper::getPdfTemplate(); 
if($user_id){
$don  = DonorForceHelper::getLatetsDonation($user_id);  
}else{
	$don  = DonorForceHelper::getLatetsDonation(); 
}

/*-- Tax changes --*/
if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
		DonorforceHelper::sendTaxReceiptPDF($don); 
}
/*-- Tax changes end --*/

//print_r($don); exit;
$app    = JFactory::getApplication();
$params = DonorForceHelper::getParams();
 
$dompdf = new DOMPDF();


/*tickect email functionality*/
$tickect=$_SESSION['ticketsAjax'];
if($tickect != ''){	
	$event_id = '';  
	$event_info = ''; 
	$event_id =  $_SESSION['eventidAjax'];
	if($event_id != ''){ 
		$db=JFactory::getDbo();
		$event_query = 'SELECT event_date FROM #__eb_events WHERE id = '.$event_id;
		$db->setQuery($event_query);
		$event_info = $db->loadObject();		
		$time = new DateTime($event_info->event_date);
		$date = $time->format('Y-m-d');
		$time = $time->format('G:i:s');
 	}
	
	// Sending email to both User and Admin
	$to  = $don->email . ', ';
	$to .=  $params->get('admin_email');
	
	$subject = 'Africa Alive Event Ticket';
	$message = 'a.	Donor Name: '.$don->name_first."\r\n"."\r\n";
	$message =  $message.'b.	Event Name: '.$don->name."\r\n"."\r\n"; 
	$message = $message. 'c.	Event Date: '.$date."\r\n"."\r\n";
	$message =  $message.'d.	Event Time: '.$time."\r\n"."\r\n";
	$message = $message. 'e.	Number of Tickets: '.$tickect."\r\n"."\r\n";
	$headers = 'From: '.$app->getCfg('mailfrom');
	mail($to, $subject, $message, $headers);
}
/*tickect email functionality end*/


/*=========================== Old html Layout ==================================*/ 
$html = '<html>
<style>'.$item->custom_style.'
.temp_header {display: inline-block;}
.top_thankyou{
	display: inline-block;
	float: right;
	margin-right: 10px;
}
@page {}
body {margin-top: -20px;}</style>
<body>
';
	$html.='<table width="100%">
	         <tr>
      			<td  valign="top">';      
					if(!empty($item->head_logo)) 
					{
						$html.='<img style="min-height:50px;" src="'.JURI::base( true ).'/'.$item->head_logo .'" name="" />';
					}
					else
				{	$html.="Logo";}
 	$html.=' </td>
            <td align="right">';        
					 if(!empty($item->head_addresses)) 
					 {
						$html.= $item->head_addresses;
					 }
					 else
				     {  $html.= "Addresses will be Here";}
 
     $html .= '</td> </tr>';
$html .='<tr>
<td>&nbsp;</td>
<td align="right"> <h3>';
if(!empty($item->main_title)) 
 {
		$html.= $item->main_title;
 }else{
	 	$html.= "Thank You";}
		$html .='</h3>
		<h4>&nbsp;&nbsp;';
		$html.= "Receipt No:";
		$html .=(isset($don->Reference))?($don->Reference):'';
		$html .='</h4>
		</td>
		</tr>'; 
$html.='<tr><td valign="top">';
 $country = '';
if($don->phy_country == 'ZA')
{
	$country = 'South Africa';
} else {
	 $country = $don->phy_country;	
}
 $html.= date('F j, Y').'<br>';
 $html.= '<strong>'.$don->name_first.' '.$don->name_last.'</strong><br>';
 $html.= $don->phy_address.'<br>'.$don->phy_city.'<br>'.$don->phy_state.'<br>'.$don->phy_zip.'<br>'.$country;
  $html .= '</td>
<td>&nbsp;</td>
</tr>';
$html .= '
<tr>
<td colspan="2" align="left" style="border-top:1px solid #999">
Dear '.$don->name_first.' '.$don->name_last.'<br><br>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>'; 
$html .='</td>
<td>&nbsp;</td>
</tr>
';
// Body Content will go here .  
if(!empty($item->thankyou_body))
{
	$html .=$item->thankyou_body; 	
}
// Body Content end here . 

$html .='<tr>
<td colspan="2">';
if(!empty($item->bottom_body_txt)) 
 {
	 $html.=$item->bottom_body_txt;
 }
 else
 $html.= "Bottom Body Text will be Here";
 $html.='</td>
</tr>
<tr>
<td>';
if(!empty($item->footer_slogan)) 
 {
 $html.= $item->footer_slogan;
 }
 else
 $html.= "";
 $html.='</td>
<td align="right" valign="middle">';
if(!empty($item->footer_addresses)) 
 {
	 $html.= $item->footer_addresses;
 }
 else
 $html.= "Footer Addresses Text will be Here";
 $html.='</td>
</tr>';
$html.='</table></body></html>';
/*=========================== Old html Layout end ===============================*/




$html ='<html><head>'; 
$html .='<style>';
$html .='
body {margin-top: -20px;}
body{position: relative; }
.temp_header {display: inline-block;}
span.blable {
    width: 150px;
    display: inline-block;
}
.tax_pdf{     max-width: 60%; border:1px solid black;  }
.tax_cont{ border: 1px solid black;}
.tax_header{     
		margin: 10px;
    display: inline-block;
    width: 100%;  
}
#receipt_view .temp_logo{ 
	float: left;
  display: inline-block;
  width: 50%;
}
.address1{    
	float: left;
  display: inline-block;
  width: 20%;
	margin-top: 50px;
}
.address2{
	 display: inline-block;
   width: 20%;
	 margin-top: 50px;
}
.address1 p, .address2 p{ margin-bottom:4px;  }

.header_empty {
    border: 1px solid black;
    padding: 5px;
    border-right: 0px;
    border-left: 0px;
}
.recpt_no{     
	border-top: 2px solid black;
  border-bottom: 1px solid black;
	text-align:right;
}
.recpt_no span{    
		border-left: 2px solid black;
    display: inline-block;
    padding: 5px;
    min-width: 100px;
    text-align: left; 
	}		
.tax_intent {
    border-bottom: 1px solid black;
}
.chairman_image {
    border-bottom: 1px solid black;
}
.chairman .date{
	float: right;
  min-width: 200px;
}
.tax_footer{ 
	display: table;
  width: 100%;
}			
.footer_row {display: table-row;}		
.footer_row span { display: table-cell;}		
.footer_row span.last{
	text-align: right;
  padding-right: 5px;
}
.tax_body{    
	border-top: 1px solid black;
  border-bottom: 1px solid black;
  margin: 10px 0px;
  padding: 5px;
}
.temp_header {
    width: 70%;
}
.top_thankyou{
		display: inline-block;
    margin-right: 10px; 
	width: 25%;  
}
body{ overflow: hidden; }

';   
$html .= (!empty($item->custom_style)) ? ($item->custom_style) : (''); 
$html .='</style>
<body class="pdf">'; 
$html .='<div class="temp_header">
          <div id="logo">';             
          	//$html .= (!empty($item->head_logo))? ( '<img style="min-height:50px;" src="'.JPATH_ROOT.'/'.$item->head_logo .'" name="" />' ) : ('Logo will be  Here');
          	$html .= (!empty($item->head_logo))? ( '<img style="min-height:50px;" src="'.JURI::root().$item->head_logo .'" name="" />' ) : ('');
						$html .=	'
					</div>
          <div id="head_addresses">';
						$html .= (!empty($item->head_addresses)) ? ($item->head_addresses) : ('Addresses will be Here'); 
						$html .=  '
					</div>
          <div style="clear:both"></div>
        </div>         
        
				<div class="top_thankyou">
          <h3>'; 
					$html .= (!empty($item->main_title))? ($item->main_title) : ("Thank You"); 
					$html .='
					</h3>
        	<h4>'; 
						$html.= "Receipt No:";
						$html .=(isset($don->Reference))?($don->Reference):'';
						$html .='
					</h4>
        </div>
				
				<div>';          
      		$html .= date('F j, Y').'<br>';
      		$html .= $don->name_first.' '.$don->name_last.'<br>';
      		$html .= $don->phy_address.', '.$don->phy_city.', '.$don->phy_state.', '.$don->phy_zip.', '.$country.'<br>';
					$html .='
        </div>
        
				<div class="main_body">'; 
         $html .=    (!empty($item->thankyou_body))? ($item->thankyou_body) : ('<b>System genrated Invoice will be show here</b>'); 
         $html .='
				</div>
        
				<div id="bottom_body_txt">'; 
				 $html .= (!empty($item->bottom_body_txt)) ? ($item->bottom_body_txt) : ('Bottom Body Text will be Here');
				 $html .='    
        </div>
        
				<div class="footer">
        <div class="slogan">'; 
         	$html .= (!empty($item->footer_slogan)) ? ($item->footer_slogan)  : ('');   
        	$html .='
				</div>
        <div class="footer_addresses">'; 
					$html .= (!empty($item->footer_addresses))? ($item->footer_addresses) : ('Footer Addresses Text will be Here'); 
					$html .='
				</div>        
		</div>
	</body></html>'; 
			

/*-- Change shortcode to dynamic data --*/
$donor_name  = $don->name_first. ' '.$don->name_last;
$donor_address =  '
						<span style="display:block;">'.$don->phy_address.'</span>
						<span style="display:block;">'.$don->phy_city.' '.$don->phy_state.'</span>
						<span style="display:block;">'.$don->phy_zip.' '.$don->phy_country.' </span>  ';		
$html = str_replace('{donation_reference}',$don->Reference, $html);
$html = str_replace('{donor_name}', $donor_name, $html);		
$html = str_replace('{donor_address}', $donor_address, $html);		
$html = str_replace('{donation_date}', $don->date, $html);	
$amount  =  DonorforceHelper::getCurrency(). " ".$don->amount;	
$html = str_replace('{donation_amount}',$amount, $html);			
$html = str_replace('{project_description}',$don->project_description, $html);		
$html = str_replace('{project_name}',$don->project_name, $html);	
$html = str_replace('{donation_type}',$don->donation_type, $html);	
$html = str_replace('{donor_number}','D'.str_pad($don->donor_id, 5, '0', STR_PAD_LEFT), $html);
$html = str_replace('{project_number}','P'.str_pad($don->project_id, 5, '0', STR_PAD_LEFT), $html);


//print_r(  $html   );  exit; 
//print_r($html); exit;
//$customPaper = array(0,0,0,900);
//$dompdf->set_paper($customPaper);
$dompdf->load_html($html);
$dompdf->render();

$output = $dompdf->output();
file_put_contents(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf", $output);
//$dompdf->stream(JPATH_COMPONENT."hello.pdf");
/**-------------------Send Email--------------------------------**/
$mailer = JFactory::getMailer();
$config = JFactory::getConfig();
 
$mailer->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
				$params->get('admin_name',$app->getCfg('fromname'))));
				
$mailer->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));

$recipient =$don->email;

 

//$recipient =$don->email;
//echo "<br /> parems admin email = ".$params->get('admin_email'); 
//echo "<br /> don->email  = ".$don->email;   

/*$recipient = array(
				$params->get('admin_email'), 
				$don->email
				); */
				
//echo "<br /> recipent mails = <pre> ".print_r( $recipient );  exit; 
				
 
$mailer->addRecipient($recipient);
$body   = '<h2>Donation Receipt</h2>'
    . '<div>Thankyou for your Donation, Please find the attached Donation Receipt</div>';

$mailer->isHTML(true);
$mailer->setBody($body);
// Optional file attached

if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
	$mailer->addAttachment(
		array(
			JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf",
			JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf"
		)
	);
}else{
			$mailer->addAttachment(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf");
}

$send = $mailer->Send();


// Testing admin emails 
$mailer2 = JFactory::getMailer();
$mailer2->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
				$params->get('admin_name',$app->getCfg('fromname'))));				
$mailer2->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));
$mailer2->addRecipient($params->get('admin_email'));
$admin_body ='<h2>Donation Receipt</h2>'
    . '<div>A Donation from  "'.$don->name_first.' '.$don->name_last.'" has been received, Please find the attached Donation Receipt</div>';
$mailer2->isHTML(true);
$mailer2->setBody($admin_body); 


if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
$mailer2->addAttachment(
		array(
			JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf",
			JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf"
		)
	);
}else{
	$mailer2->addAttachment(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf");
}

$send2 = $mailer2->Send();
// Testing admin emails end 


//echo "<hr /><pre>"; print_r($mailer); exit; 

if ( $send !== true || $send2 !== true ) {
    echo 'Error sending email: ';
} else {
    echo 'Mail sent';
}
/**---------------------------------------------------------**/

return true; 
}
  
  
  
   // Send Email for recurring donation   
function sendemailRec(){
 //echo "<br /> Email Start ";  exit; 	
jimport('joomla.application.component.helper');
if(JComponentHelper::getParams('com_donorforce')->get('send_thankyou') == 0){
	return false;	
}
	
//require_once JPATH_COMPONENT."/assets/dompdf/dompdf_config.inc.php";		
require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';
$item = DonorForceHelper::getPdfTemplate(); 
//$don  = DonorForceHelper::getLatetsDonationRec(); 
$don  = DonorForceHelper::getLatetsDonationRec(); 

/*-- Tax changes --*/
if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
		DonorforceHelper::sendTaxReceiptPDF($don); 
}
/*-- Tax changes end --*/

//print_r($don); exit;
$app    = JFactory::getApplication();
$params = DonorForceHelper::getParams();
 
$dompdf = new DOMPDF();
 
/*=========================== Old html Layout ==================================*/ 
$html = '<html>
<style> body {	margin-top: -20px;}</style>
<body>';
$html.='<table width="100%">
	         <tr>
      			<td  valign="top">';        
					if(!empty($item->head_logo)) 
					{
						$html.='<img style="min-height:50px;" src="'.JURI::base( true ).'/'.$item->head_logo .'" name="" />';
					}
					else
				{	$html.="Logo";}
 	$html.=' </td>
            <td align="right">';
        
					 if(!empty($item->head_addresses)) 
					 {
						$html.= $item->head_addresses;
					 }
					 else
				     {  $html.= "Addresses will be Here";}
 
     $html .= '</td></tr>';	
$html .='<tr>
<td>&nbsp;</td>
<td align="right"> <h3>';
					if(!empty($item->main_title)) 
					 {
						$html.= $item->main_title;
					 }
					 else
				     {  $html.= "Thank You";}
$html .='</h3>
<h4>&nbsp;&nbsp;';
					  $html.= "Receipt No:";
$html .=(isset($don->Reference))?($don->Reference):'';
$html .='</h4>
</td>
</tr>';
$html.='<tr><td valign="top">';
$country = ''; 
if($don->phy_country == 'ZA')
{
	$country = 'South Africa';
} else {
	 $country = $don->phy_country;	
}
 $html.= date('F j, Y').'<br>';
 $html.= '<strong>'.$don->name_first.' '.$don->name_last.'</strong><br>';
 $html.= $don->phy_address.'<br>'.$don->phy_city.'<br>'.$don->phy_state.'<br>'.$don->phy_zip.'<br>'.$country;
  $html .= '</td>
<td>&nbsp;</td>
</tr>';
$html .= '
<tr>
<td colspan="2" align="left" style="border-top:1px solid #999">
Dear '.$don->name_first.' '.$don->name_last.'<br><br>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>'; 
$html .='</td>
<td>&nbsp;</td>
</tr>
';
// Body Content will go here .  
if(!empty($item->thankyou_body))
{
	$html .=$item->thankyou_body; 	
}
// Body Content end here . 

$html .='<tr>
<td colspan="2">';
if(!empty($item->bottom_body_txt)) 
 {
	 $html.=$item->bottom_body_txt;
 }
 else
 $html.= "Bottom Body Text will be Here";
 $html.='</td>
</tr>
<tr>
<td>';
if(!empty($item->footer_slogan)) 
 {
 $html.= $item->footer_slogan;
 }
 else
 $html.= "";
 $html.='</td>
<td align="right" valign="middle">';
if(!empty($item->footer_addresses)) 
 {
	 $html.= $item->footer_addresses;
 }
 else
 $html.= "Footer Addresses Text will be Here";
 $html.='</td>
</tr>';
$html.='</table></body></html>';

/*=========================== Old html Layout  end ==================================*/ 




$html ='<html><head>'; 
$html .='<style>';
$html .='
body {margin-top: -20px;}
body{position: relative; }
.temp_header {display: inline-block;}
span.blable {
    width: 150px;
    display: inline-block;
}
.tax_pdf{     max-width: 60%; border:1px solid black;  }
.tax_cont{ border: 1px solid black;}
.tax_header{     
		margin: 10px;
    display: inline-block;
    width: 100%;  
}
#receipt_view .temp_logo{ 
	float: left;
  display: inline-block;
  width: 50%;
}
.address1{    
	float: left;
  display: inline-block;
  width: 20%;
	margin-top: 50px;
}
.address2{
	 display: inline-block;
   width: 20%;
	 margin-top: 50px;
}
.address1 p, .address2 p{ margin-bottom:4px;  }

.header_empty {
    border: 1px solid black;
    padding: 5px;
    border-right: 0px;
    border-left: 0px;
}
.recpt_no{     
	border-top: 2px solid black;
  border-bottom: 1px solid black;
	text-align:right;
}
.recpt_no span{    
		border-left: 2px solid black;
    display: inline-block;
    padding: 5px;
    min-width: 100px;
    text-align: left; 
	}		
.tax_intent {
    border-bottom: 1px solid black;
}
.chairman_image {
    border-bottom: 1px solid black;
}
.chairman .date{
	float: right;
  min-width: 200px;
}
.tax_footer{ 
	display: table;
  width: 100%;
}			
.footer_row {display: table-row;}		
.footer_row span { display: table-cell;}		
.footer_row span.last{
	text-align: right;
  padding-right: 5px;
}
.tax_body{    
	border-top: 1px solid black;
  border-bottom: 1px solid black;
  margin: 10px 0px;
  padding: 5px;
}
.temp_header {
    width: 70%;
}
.top_thankyou{
		display: inline-block;
    margin-right: 10px; 
	width: 25%;  
}
body{ overflow: hidden; }

';   
$html .= (!empty($item->custom_style)) ? ($item->custom_style) : (''); 
$html .='</style>
<body class="pdf">'; 
$html .='<div class="temp_header">
          <div id="logo">';             
          	//$html .= (!empty($item->head_logo))? ( '<img style="min-height:50px;" src="'.JPATH_ROOT.'/'.$item->head_logo .'" name="" />' ) : ('Logo will be  Here');
          	$html .= (!empty($item->head_logo))? ( '<img style="min-height:50px;" src="'.JURI::root().$item->head_logo .'" name="" />' ) : ('');
						$html .=	'
					</div>
          <div id="head_addresses">';
						$html .= (!empty($item->head_addresses)) ? ($item->head_addresses) : ('Addresses will be Here'); 
						$html .=  '
					</div>
          <div style="clear:both"></div>
        </div>         
        
				<div class="top_thankyou">
          <h3>'; 
					$html .= (!empty($item->main_title))? ($item->main_title) : ("Thank You"); 
					$html .='
					</h3>
        	<h4>'; 
						$html.= "Receipt No:";
						$html .=(isset($don->Reference))?($don->Reference):'';
						$html .='
					</h4>
        </div>
				
        <div>';          
      		$html .= date('F j, Y').'<br>';
      		$html .= $don->name_first.' '.$don->name_last.'<br>';
      		$html .= $don->phy_address.', '.$don->phy_city.', '.$don->phy_state.', '.$don->phy_zip.', '.$country.'<br>';
					$html .='
        </div>
        
				<div class="main_body">'; 
         $html .=    (!empty($item->thankyou_body))? ($item->thankyou_body) : ('<b>System genrated Invoice will be show here</b>'); 
         $html .='
				</div>
        
				<div id="bottom_body_txt">'; 
				 $html .= (!empty($item->bottom_body_txt)) ? ($item->bottom_body_txt) : ('Bottom Body Text will be Here');
				 $html .='    
        </div>
        
				<div class="footer">
        <div class="slogan">'; 
         	$html .= (!empty($item->footer_slogan)) ? ($item->footer_slogan)  : ('');   
        	$html .='
				</div>
        <div class="footer_addresses">'; 
					$html .= (!empty($item->footer_addresses))? ($item->footer_addresses) : ('Footer Addresses Text will be Here'); 
					$html .='
				</div>        
		</div>
	</body>
</html>'; 
			






/*-- Change shortcode to dynamic data --*/
$donor_name  = $don->name_first. ' '.$don->name_last;

$donor_address =  '
						<span style="display:block;">'.$don->phy_address.'</span>
						<span style="display:block;">'.$don->phy_city.' '.$don->phy_state.'</span>
						<span style="display:block;">'.$don->phy_zip.' '.$don->phy_country.' </span>  ';		

$html = str_replace('{donation_reference}',$don->Reference, $html);

$html = str_replace('{donor_name}', $donor_name, $html);		
$html = str_replace('{donor_address}', $donor_address, $html);		
$html = str_replace('{donation_date}', $don->date, $html);	
$amount  =  DonorforceHelper::getCurrency(). " ".$don->amount;	
$html = str_replace('{donation_amount}',$amount, $html);			
$html = str_replace('{project_description}',$don->project_description, $html);		
$html = str_replace('{project_name}',$don->project_name, $html);	
$html = str_replace('{donation_type}',$don->donation_type, $html);	
$html = str_replace('{donor_number}','D'.str_pad($don->donor_id, 5, '0', STR_PAD_LEFT), $html);
$html = str_replace('{project_number}','P'.str_pad($don->project_id, 5, '0', STR_PAD_LEFT), $html);


//print_r(  $html   );  exit; 
//print_r($html); exit;
//$customPaper = array(0,0,0,900);
//$dompdf->set_paper($customPaper);
$dompdf->load_html($html);
$dompdf->render();
 
$output = $dompdf->output();
file_put_contents(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf", $output);
//$dompdf->stream(JPATH_COMPONENT."hello.pdf");
/**-------------------Send Email--------------------------------**/
$mailer = JFactory::getMailer();
$config = JFactory::getConfig();
 
$mailer->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
				$params->get('admin_name',$app->getCfg('fromname'))));
				
$mailer->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));

$recipient =$don->email;
 
$mailer->addRecipient($recipient);
$body   = '<h2>Donation Receipt</h2>'
    . '<div>Thankyou for your Donation, Please find the attached Donation Receipt</div>';

$mailer->isHTML(true);
$mailer->setBody($body);
// Optional file attached
if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){	
	$mailer->addAttachment(array(
		JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf",
		JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf"
		)
	);
}else{
	$mailer->addAttachment(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf");
}
$send = $mailer->Send();



// Testing admin email
$mailer2 = JFactory::getMailer();
$mailer2->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
				$params->get('admin_name',$app->getCfg('fromname'))));				
$mailer2->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));
$mailer2->addRecipient($params->get('admin_email'));
$admin_body ='<h2>Donation Receipt</h2>'
    . '<div>A Donation from  "'.$don->name_first.' '.$don->name_last.'" has been received, Please find the attached Donation Receipt</div>';
$mailer2->isHTML(true);
$mailer2->setBody($admin_body); 

if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
	$mailer2->addAttachment(
		array(
					JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf",
					JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf"
		 )
	);
}else{
	$mailer2->addAttachment(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf");
}
$send2 = $mailer2->Send();
// Testing admin email end
 

//echo "<hr /><pre>"; print_r($mailer); exit; 
if ( $send !== true || $send2 !== true ) { 
    echo 'Error sending email: ';
} else {
    echo 'Mail sent';
}
/**---------------------------------------------------------**/

return true; 
	
	
}    
  

}
