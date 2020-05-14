<?php
/**
 *  @copyright  Copyright (c) 2009-2013 TechJoomla. All rights reserved.
 *  @license    GNU General Public License version 2, or later
 */
 
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
jimport('joomla.filesystem.file');
//require_once JPATH_COMPONENT . DS . 'helper.php';
$lang = JFactory::getLanguage();
$lang->load('plg_payment_paygate', JPATH_ADMINISTRATOR);
require_once(dirname(__FILE__) . '/paygate/helper.php');
class plgpaymentpaygate extends JPlugin 
{
	var $_payment_gateway = 'payment_paygate';
	var $_log = null;

	function __construct(& $subject, $config)
	{
			parent::__construct($subject, $config);
		/*
		 * @var $this->responseStatus	array	Payment Status codes And Respective Alias in Framework
		 * */
		$this->responseStatus= array(
		 '1'  => 'C',
		 '2'=>'E',
		 '0'=>'D');
		
	}

	
	/* This function falls under STEP 1 of the Common Payment Gateway flow
	 * It is Used to Build List of Payment Gateway in the respective Components
	 *
	 * @param $config	array	list of payment plugin names from component settings/config
	 * @return object	Object	with 'name' set as in the param plugin_name and 'id' set as the plugin's filename
	 * */
	function onTP_GetInfo($config) 
	{	
	//echo "<br />  PayGate onTP_GetInfo"; 
		if(!in_array($this->_name,$config))	/*check if payment plugin is in config*/
			return;
		$obj 		= new stdClass;
		$obj->name 	= $this->params->get( 'plugin_name' )."<span> (Debit card, credit card, debit order)</span>";
		$obj->id	= $this->_name;
		return $obj;
	}

	/* This function falls under STEP 2 of the Common Payment Gateway flow
	 * It Constructs the Payment form in case of On Site Payment gateways like Auth.net
	 * OR constructs the Submit button in case of offsite
	 *
	 * @param $vars	object	list of all data required by payment plugin constructed by the component
	 * @return string	HTML	to display
	 * */
	function onTP_GetHTML($vars)
	{
		
		/* add on any payment plugin specific data to $vars*/
		$vars->action_url = plgPaymentPaygateHelper::buildPaygateUrl();
		$html = $this->buildLayout($vars);	/*pass $vars to buildLayout to get the payment form/html */
		return $html;
	}

/* This function falls under STEP 3 of the Common Payment Gateway flow
 * If Process on the post data from the payment and pass a fixed format data to component for further process
 *
 * @param $data	array	Post data from gateway to notify url
 * @return associative	array	gateway specific fixed format data required by the component to process payment
 * */
	function onTP_Processpayment($data,$vars)
	{		
		$KEY = $this->params->get('secret');
		$PAYGATE_ID = $this->params->get('paygate_id'); //$data['PAYGATE_ID'];
    $REFERENCE =	$data['REFERENCE'];
    $TRANSACTION_STATUS = $data['TRANSACTION_STATUS'];
    $RESULT_CODE = $data['RESULT_CODE'];
    $AUTH_CODE = $data['AUTH_CODE'];
    $AMOUNT = $data['AMOUNT'];
    $RESULT_DESC = $data['RESULT_DESC'];
    $TRANSACTION_ID = $data['TRANSACTION_ID'];
    $RISK_INDICATOR = $data['RISK_INDICATOR'];		
    $CHECKSUM = $data['CHECKSUM'];
		
	 if(isset($data['SUBSCRIPTION_ID'])){
					
		$checksum_source = $PAYGATE_ID."|".$REFERENCE."|".$TRANSACTION_STATUS."|".$RESULT_CODE."|".$AUTH_CODE."|".$AMOUNT."|".$RESULT_DESC."|".$TRANSACTION_ID."|".$data['SUBSCRIPTION_ID']."|".$RISK_INDICATOR."|".$KEY; 
						
		}else{
	 		$checksum_source = $PAYGATE_ID."|".$REFERENCE."|".$TRANSACTION_STATUS."|".$RESULT_CODE."|".$AUTH_CODE."|".$AMOUNT."|".$RESULT_DESC."|".$TRANSACTION_ID."|".$RISK_INDICATOR."|".$KEY; 
	}
		
	$Our_checksum = md5($checksum_source);
	
		$error = array();
		$error['code']	= '';
		$error['desc']	= '';
		$trxnstatus='';
		$trxnstatus = $data['TRANSACTION_STATUS']; 
		$res_orderid = '';		
		$payment_status = '';
		$payment_status = $this->translateResponse($trxnstatus);			
		$error['code']	= $data['RESULT_CODE'] ;
		$error['desc']	= $data['RESULT_DESC'] ;
		
		if($payment_status == 'C'){
					if( $Our_checksum != $CHECKSUM  ){
									$payment_status = 'E';
									$error['desc'] = $error['desc']." Checksum Failed ";
					}
		}
		
		$result = array(
						'order_id'=>$data['order_id'],
						'transaction_id'=>$data['TRANSACTION_ID'],
						/*'buyer_email'=>$data['payer_email'],*/
						'status'=>$payment_status,
						/*'subscribe_id'=>$data['subscr_id'],*/
						/*'txn_type'=>$data['txn_type'],*/
						/*'total_paid_amt'=>$data['mc_gross'],*/
					/*	'raw_data'=>$data,*/
						'error'=>$error,
						);
		return $result;
	}

/* This function falls under STEP 3 of the Common Payment Gateway flow 
 * It Logs the payment process data */
	function onTP_Storelog($data)
	{
		$log = plgPaymentPaygateHelper::Storelog($this->_name,$data);
	}

/* Internal use functions move to common helper
translate the status response depending upon you payment gateway*/
	function translateResponse($trxnstatus){
		foreach($this->responseStatus as $key=>$value)
		{
			if($key == $trxnstatus)
			return $value;
		}
	}

/* Internal use functions move to common helper
 * Builds the layout to be shown, along with hidden fields.
 * */
	function buildLayout($vars, $layout = 'default' )
	{
		if ($vars->is_recurring == '1' ) { $layout = 'recurring'; }
		// Load the layout & push variables
		ob_start();
		$layout = $this->buildLayoutPath($layout);
		include($layout);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

/** Internal use functions move to common helper*/
	function buildLayoutPath($layout) {
		$app = JFactory::getApplication();
		if($layout == 'recurring')
			$core_file 	= dirname(__FILE__) . '/' . $this->_name . '/tmpl/recurring.php';
		else
			$core_file 	= dirname(__FILE__) . '/' . $this->_name . '/tmpl/default.php';
		//$core_file 	= dirname(__FILE__) . '/' . $this->_name . '/tmpl/default.php';
		$override	= JPATH_BASE . '/' . 'templates' . '/' . $app->getTemplate() . '/html/plugins/' . $this->_type . '/' . $this->_name . '/' . $layout.'.php';
		if(JFile::exists($override))
		{
			return $override;
		}
		else
		{
			return  $core_file;
		}
	}
}	


