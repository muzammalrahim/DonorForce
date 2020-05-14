<?php 
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access
defined('_JEXEC') or die;

//jimport('joomla.application.component.helper');

abstract class DonorForceHelper
{
	
	

	
	
	static  function getFullUserInfo($userid = 0){
		$db = JFactory::getDbo();
		
		if($userid > 0)
		{
			$query = "SELECT u.id,u.username,u.email,u.name,di.* FROM #__users AS u "
					."LEFT JOIN #__donorforce_donor AS di ON di.cms_user_id = u.id "
					."WHERE u.id = ".(int)$userid;
			$db->setQuery($query);
			return $db->loadObject();
		}
		
	}
	
	

	
static	function getUserDebitInfo($donorid = 0){
		$db = JFactory::getDbo();
		
		if($donorid > 0)
		{
			$query = "SELECT r.* FROM #__donorforce_rec_donation AS r "
					
					."WHERE r.donor_id = ".(int)$donorid;
			$db->setQuery($query);
			
			//echo $query;
		}
		//echo $query;
		return $db->loadObject();
	}
	
	function getUserGroup(){
		
		$group = 0;
		$user =& JFactory::getUser();
		
		if($user->get('id') > 0)
		{
		
			$groups = $user->getAuthorisedGroups();
		
			$lastGroup = end(array_values($groups));
		
			if($lastGroup > 0)
			{
				$group = $lastGroup;	
			}
		}
		return $group;
	}
	
	
	
	
		
	
	function getItemId(){
		
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		
		$component =& JComponentHelper::getComponent('com_donorforce');

		$items	= $menus->getItems('component_id', $component->id);
		
		if(!empty($items[1]->id))
			return $items[1]->id;
		elseif(!empty($items[0]->id))
			return $items[0]->id;
		else
			return 1;	
	}
	
	function genRandomString($length = 8)
	{     
		$result = '';
		$chars = 'bcdfghjklmnprstvwxzaeiou';
	   
		for ($p = 0; $p < $length; $p++)
		{
			$result .= ($p%2) ? $chars[mt_rand(19, 23)] : $chars[mt_rand(0, 18)];
		}
	   
		return $result;
	}
	

	
	
	
	function updateUser($data){
		
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_joomla', JPATH_ADMINISTRATOR);
		$lang->load('com_users', JPATH_SITE);
				
		$config = JFactory::getConfig();
		$params = JComponentHelper::getParams('com_users');
		
		// Initialise the table with JUser.
		$user = new JUser($data['cms_user_id']);
		
		
		// Bind the data.
		if (!$user->bind($data)) {
			$this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Store the data.
		if (!$user->save()) {
			$this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
			return false;
		}
		
		return $user->id;
		
	}
	
	function buildQuery($fields, $table, $case = 'INSERT', $where = ''){
		
		$db = JFactory::getDbo();
	
			
	
		$query = "SHOW COLUMNS FROM $table";
		
		$db->setQuery($query);
		$columns = $db->loadColumn();
		
		//print_r($columns);
		//$columns = $db->getTableColumns($table);
	
		$query = $case;
		
		if($case == 'INSERT')
		{
			$query .= " INTO";
		}
		
		$query .= " `".$table."` SET ";
		
		foreach ($fields as $k => $v)
		{
			if(in_array($k, $columns))
			{			
				if($v != '')
				{				
					$query .= '`'.$k.'` = ';
					$query .= "'".$db->escape($v)."',";
				} else {
					$query .= '`'.$k.'` = ';
					$query .= "'',";
				}
			}
		}
		
		$query .= ')';
	
		$query = str_replace(',)','',$query);
		
		if($where != '')
		$query .= ' WHERE '.$where;
		
		return $query;
		
	}
	
	function fetchTemplate($name, $type){
		
		$content = '';
		
		$fullpath = JPATH_BASE.DS.'components'.DS.'com_donorforce'.DS.'templates'.DS.$type.DS.'tpl_'.$type.'.'.$name.'.php';
		if ( file_exists( $fullpath ) )
		{ 
			//get the file content
			ob_start();
				include $fullpath;
				$content = ob_get_contents();
			ob_end_clean();
		} else {
			echo "Unable to load Template File";
			return false;
		}
		
		return $content;
	}
	
	function parseTemplate($replacement, $data){
		
		$matches = array();
		$content = '';
		
		if(preg_match_all('#\{\#content\#\}#iU', $data, $matches))
		{
			$data = str_replace($matches[0], $replacement, $data);
		}
		
		return $data;
	}
	
	function getProject($id)
	{
		$db = JFactory::getDbo();
	
		
		
		$query = "select * from #__donorforce_project where project_id=".$id;
		$db->setQuery($query);
		//print_r($db->loadObject()); exit;
		return $db->loadObject();
		
	}
	
	
	
	function addQueryLog($q){
		
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		
		$fields['cms_user_id'] = $user->get('id');
		$fields['query'] = $q;
		
		$query = self::buildQuery($fields, '#__donorforce_querylog');
		$db->setQuery($query);
		return $db->query();
	}
	
	function saveToLog($file, $text){
   	
			if(!file_exists(JPATH_COMPONENT.DS.'logs'.DS.$file))
			{
				touch(JPATH_COMPONENT.DS.'logs'.DS.$file);
			}
   			
   			$f = fopen(JPATH_COMPONENT.DS.'logs'.DS.$file, "a+");
    		fwrite($f, date('Y-m-d H:i:s')." ".$text."\r\n");
    		fclose($f);
    		return 1;
	}
	
	
	
  static function getLoggedIn(){
		
		$user = JFactory::getUser();
		
		if($user->get('guest'))
		{
		
		$uri = JFactory::getUri();
		$return = ('index.php'.$uri->toString(array('query')));
		
		echo "<h4><a href=\"".JRoute::_('index.php?option=com_users&amp;view=login&amp;return='.$return)."\"><u>Please click here to login before accessing the Member's Area</u></a></h4>";
			return false;
		} else {
			return $user->get('id');
		}
			
	}
	
	
	
	function sendMail($to = '', $subject = '', $body = '', $admin = 0, $recepient=1){
		
		jimport('joomla.application.component.helper');
		if( JComponentHelper::getParams('com_donorforce')->get('send_thankyou') == 0){
			return false;	
		}	
		
		echo "<br />  Sending email to user  
			to =  $to  <br /> 
			subject = $subject <br /> 
			body = $body  <br /> 
			admin =  $admin <br /> 
			recepient = $recepient  <br />"; //exit; 
		
		$user = JFactory::getUser();
		$config = JFactory::getConfig();
		$db = JFactory::getDbo();
		
		$params = self::getParams();
		
		
		$data['mailfrom'] 	= $params->get('admin_email',$config->get('mailfrom'));
		$data['fromname'] 	= $params->get('admin_name',$config->get('fromname'));
		$data['siteurl']	= JUri::base();
		
		if($to != '' && $recepient == 1)
		{		
			$emailSubject = $subject;
			$content = $body;	
			return JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $to, $subject, $body);
		}
		if($admin == 1)
		{
		
			/*$db->setQuery('SELECT * FROM #__users WHERE sendEmail = 1');
			$users = (array) $db->loadObjectList();
			
			foreach ($users as $u)
			{
					
				$data['email'] = $u->email;
				$data['name'] = $u->name;
				
			*/	
				
				//$template = FoodPortalHelper::fetchTemplate('admin','email');
			
				// Compute the mail body.
				//$emailBody = FoodPortalHelper::parseTemplate($content, $template);
		
				// Send the email.
				return JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['mailfrom'], $subject, $body);
				
				//return JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $subject, $body, 1);
		/*	} // End For */
		} //End IF			
	}
	
	
	
static	function getParams(){
		$params = JComponentHelper::getParams('com_donorforce');
		return $params;
	}
	
	
	
	function addhttp($url) {
		if (!preg_match("@^[hf]tt?ps?://@", $url)) {
			$url = "http://" . $url;
		}
    	return $url;
	}
	
	
	function getDocumentType(){
   	 	$document = JFactory::getDocument();
		return $document->getType();
	}
	
	
 static	function getPaymentConfigs(){
		$user =& JFactory::getUser();
		$param = self::getParams();
		$pmconfigs = array();
 		$pmconfigs['host'] = "https://www.paygate.co.za/paywebv2/process.trans";
		$pmconfigs['hostsubs'] = "https://www.paygate.co.za/paysubs/process.trans";
		$pmconfigs['notify_url'] = JUri::base().'index.php?option=com_donorforce&task=donation.notify';
		$pmconfigs['paygate_id'] = $param->get('paygate_id');
		$pmconfigs['secret'] = $param->get('secret');
		$pmconfigs['currency'] = $param->get('currency');
		return $pmconfigs;
	}
	
static function getCurrency()
	{
	$componentParams = JComponentHelper::getParams('com_donorforce');
	$param = $componentParams->get('addcurrency');
	return $param;
	}

	function getPdfTemplate()
	{
		$db = JFactory::getDbo();
		$query = "select * from #__donorforce_invoice_temp LIMIT 1";
		$db->setQuery($query);
		$result= $db->loadObject();
		return $result;
	}
	
	function getLatetsDonation($user_id=0)
	{
		$db = JFactory::getDbo();
		if($user_id){
				$user =& JFactory::getUser($user_id);
		}else{
				$user =& JFactory::getUser();
		}
		$query = "
				SELECT
					s.*, u.email,
					p.`name` as project_name,
					p.`description` as project_description,
					h.amount,
					h.*
				FROM
					#__donorforce_donor AS s
				INNER JOIN #__users AS u ON s.cms_user_id = u.id
				INNER JOIN #__donorforce_history AS h ON h.cms_user_id = u.id
				INNER JOIN #__donorforce_project AS p ON h.project_id = p.project_id
				WHERE
					u.id = ".$user->id."
				ORDER BY
					donor_history_id DESC
				LIMIT 1
		";		
		//echo $query;
		$db->setQuery($query);
		$result= $db->loadObject();
		//print_r($result);
		return $result;
	}
	
	
	function getLatetsDonationByID($id)
	{
		$db = JFactory::getDbo();
		$user =& JFactory::getUser();
		$query = "
				SELECT
					s.*, u.email,
					p.`name`,
					h.amount,
					h.*
				FROM
				 #__donorforce_history AS h
				
				LEFT JOIN #__users AS u ON h.cms_user_id = u.id
				LEFT JOIN #__donorforce_donor AS s ON s.cms_user_id = u.id
				LEFT JOIN #__donorforce_project AS p ON h.project_id = p.project_id
				
				WHERE
					h.donor_history_id = ".$id."
				ORDER BY
					donor_history_id DESC
				LIMIT 1
		";		
		//echo $query;
		$db->setQuery($query);
		$result= $db->loadObject();
		//print_r($result);
		return $result;
	}
	
	
	
	
	function getLatetsDonationRec(){
		
		$db = JFactory::getDbo();
		$user =& JFactory::getUser();
		$query = "
				SELECT
					s.*, u.email,
					p.`name` as project_name,p.project_id,
					p.`description` as project_description,
					sub.amount,sub.donor_id,
					sub.subscription_id as donor_history_id,					
					concat('SubID_', sub.subscription_id) as Reference,					
					sub.donation_type
				FROM
					#__donorforce_donor AS s
				INNER JOIN #__users AS u ON s.cms_user_id = u.id
				INNER JOIN #__donorforce_donor_subscriptions AS sub ON sub.donor_id = s.cms_user_id
				INNER JOIN #__donorforce_project AS p ON sub.project_id = p.project_id
				WHERE
					u.id = ".$user->id."
				ORDER BY
					subscription_id DESC
				LIMIT 1
		";		
		//echo $query;
		$db->setQuery($query);
		$result= $db->loadObject();
		//print_r($result);
		return $result;
	}
	
	
static	function displayAmount($amount)
	{			
		$pattern = '~^-?[0-9]+(\.[0-9]+)$~xD';	
		if(preg_match($pattern,$amount))
		{
			//do something 
		} else {
			$afterdecimal = substr($amount, -2);
			$beforedecimal = substr($amount, 0, -2);
			$amount = $beforedecimal.'.'.$afterdecimal;
		}
	 return $amount;
	}




function sendTaxReceiptPDF($don){
	
	//echo "<pre> sendTaxReceiptPDF don = "; print_r( $don  ); echo "</pre>";  exit; 
	
			$pdf_template = DonorForceHelper::getPdfTemplate();
			
$Taxhtml2 = '<html style=""><body style="" >

	<style>
		.tax_pdf * {
			/*	border : 1px solid black; */
		}

		.tax_header{ 
			border-bottom: 1px solid black;
			display: table; 
			width:100%; 
			/* margin: 10px 0px; */
		}
		.header_row{
			display: table-row;
		}
		.temp_logo{ 
			float: left;
			display: table-cell;
			width: 25%;
			text-align: center;
		}
		.temp_logo img{ 
			width: 150px; 
			margin-top: 20px;
		}
		.temp_logo span{
			display: block;
			margin-top: 11px;
		}
		.address_div{
			display: table-cell;
			width: 40%;
			float: left;
		}
		.address_div h4{
			margin-top: 0px;
		}
		.address1{    
			width: 50%;
			float: left;
			display: inline-block;
			padding-left: 25px;
		}
		.address2{
			width: 50%;
			float: left;
			display: inline-block;
			padding-left: 10px;
		}
		.address1 p, .address2 p {
			margin-bottom: 4px;
			margin-top: 0px;
			padding:0px;
		}
		.receipt{
			display: table-cell;
			width: 35%;
			float: left;
		}
		.receipt div{
			/* margin-top: -5px; */
		}
		.receipt .receipt_label {
			text-align: right;
			width: 50%;
			float: left;
			display: inline-block;
		}
		.receipt .receipt_value {
			width: 50%;
			float: left;
			padding-left: 5px;
			margin-right: -5px;
		}
		.header_empty {
			/* border: 1px solid black; */
			height:8px;
			border-right: 0px;
			border-left: 0px;
		}
		.donation_recpt{ /* border: 1px solid black; */}
		.donation_recpt h2 {
			margin: 0px;
			padding: 5px;
		}
		.donation_recpt p{ line-height: 30px;}
		.tax_intent{ /*  margin: 0px -10px; */}
		.tax_intent p{line-height: 30px;}
		.recpt_no {
			/* border-top: 1px solid black; */
			/* border-bottom: 1px solid black; */
			display: inline-block;
			width: 100%;
		}
		.recpt_no span{    
			border-left: 1px solid black;
			display: inline-block;
			padding: 5px;
			min-width: 100px;
			text-align: left; 
		}
		.tax_body{    
			/* border-top: 1px solid black; */
			border-bottom: 1px solid black;
			/* margin: 10px -10px; */
			padding: 5px 10px;
		}
		.tax_body p {
			margin: 0px !important;
		}		
		.tax_intent {
			border-bottom: 1px solid black;
		}
		.empty_no{
			width: 60%;
			display: inline-block;
			padding: 5px;
		}
		.chairman_image {
			/* border-bottom: 1px solid black; */
			display: block;	
			/* margin: 0 -10px; */
			padding: 10px;
		}
		.chairman{  
			/* border-top: 1px solid black; */
			border-bottom: 1px solid black;
			display: block;	
			padding: 10px;	
		}
		.tax_footer{ 
			display: table;
			width: 100%;
			/* border: 1px solid #000;
			border-top: none; */
			padding: 10px;
		}			
		.footer_row {display: table-row; width: 100%;}		
		.footer_row span { display: table-cell;}		
		.footer_row span.last{
			text-align: right;
			padding-right: 5px;
		}
		img{margin:4px;}';
		$Taxhtml2 .= (!empty($pdf_template->custom_style2))? $pdf_template->custom_style2 : ''; 
		$Taxhtml2 .='
	</style>

	<div class="tax_pdf">
	 	<div class="tax_cont">
			<div class="tax_header">
				<div class="header_row">
					<div class="temp_logo">';                 
						if(!empty($pdf_template->head_logo2)) { 
							$Taxhtml2 .= '<img src="'.JURI::root().$pdf_template->head_logo2 .'"/>';						
						}else{
							//$Taxhtml2 .= 'Logo will be  Here'; 
						}
						if(!empty($pdf_template->org_name)){
							$org_name = $pdf_template->org_name;
						}
						else{
							$org_name = 'Organization Name';
						}
						$Taxhtml2 .='
						<span><b>'.$pdf_template->pobox.'</b></span>
					</div>
					<div class="address_div">
						<h1 style="text-align: center;">'.$org_name.'</h1>
						<div class="address1">
							<h4>Physical Address</h4>';        		 
							if(!empty($pdf_template->physical_address)) 
							{
								$Taxhtml2 .= $pdf_template->physical_address;
							}
							else
							$Taxhtml2 .='Physical Addresses will be Here';
							$Taxhtml2 .='          
						</div>
						<div class="address2">
							<h4>Postal Address</h4>'; 					 
							if(!empty($pdf_template->postal_address)) 
							{
								$Taxhtml2 .= $pdf_template->postal_address;
							}
							else $Taxhtml2 .='Postal Addresses will be Here';
							$Taxhtml2 .='        
						</div>   
					</div>
					<div class="receipt">
						<h1 style="width: 50%; margin-left: 50%; padding-left: 5px; margin-right: -5px;">Receipt</h1>
						<div><span class="receipt_label">Receipt:</span> <span class="receipt_value">'.$don->Reference.'</span></div>
						<div><span class="receipt_label">Date:</span> <span class="receipt_value">'.date('d/m/Y').'</span></div>
						<div><span class="receipt_label">VAT Number:</span> <span class="receipt_value">'.$don->vat_number.'</span></div>
						<div><span class="receipt_label">Company Name:</span> <span class="receipt_value">'.$don->org_name.'</span></div>
						<div><span class="receipt_label">Address:</span> <span class="receipt_value" style="display: inline-block;">{address_1} <br> {address_2} <br> {city} <br> {country}</span></div>
					</div> 
				</div>
			</div><!-- tax_header end -->
			<!-- 	<div class="header_empty"></div>
			<div class="donation_recpt">
       			<h2>Donations Receipt</h2>
          		<span>';
					 
					if(!empty($pdf_template->receipt_text)) 
					{
						$Taxhtml2 .= $pdf_template->receipt_text;
					}
					else $Taxhtml2 .='Donation Receipt Text will be here';
					$Taxhtml2 .='		
				</span>
       		</div>--><!-- donation_recpt end -->
	   		<!-- <div class="recpt_no">
        		<span class="empty_no" style="padding:2px;"></span>
				<span style="width: 18%;display: inline-block; border-left:1px solid black; padding:2px;">Receipt No.</span>
          		<span style="width: 18%;display: inline-block;border-left:1px solid black;padding:2px;">'; 
					$Taxhtml2 .= $don->Reference; 
					$Taxhtml2 .='</span>            
        	</div> -->
			<div class="tax_body">';
			 
			 
				/*-- Tax body --*/
				/*
				<div class="body_row" style="border: 1px solid black;"><span style="width: 150px; display: inline-block;">Name of Donor</span> <span style="width: 600px; display: inline-block; border-left: 1px solid black;">{donor_name}</span></div>
				<div class="body_row" style="border: 1px solid black;">
				<div style="display: inline-block; width: 150px;">Address of Donor</div>
				<div style="display: inline-block; border-left: 1px solid black; width: 600px;"><span style="display: block;">115 Low Street</span> <span style="display: block;">Mountainside</span> <span style="display: block;">HILLVALE</span></div>
				</div>
				<div class="body_row" style="border: 1px solid black;">
				<div style="display: inline-block; width: 150px;">Amount of Donation</div>
				<div style="display: inline-block; border-left: 1px solid black; width: 600px;"><span style="display: block;">{donation_amount}</span></div>
				</div>
				<div class="body_row" style="border: 1px solid black;">
				<div style="display: inline-block; width: 150px;">Nature of Donation</div>
				<div style="display: inline-block; border-left: 1px solid black; width: 600px;"><span style="display: inline-block; width: 200px;">Description: </span>&nbsp;{project_description}</div>
				</div>
				<div class="body_row" style="border: 1px solid black;">
				<div style="display: inline-block; width: 150px;">Date of Donation</div>
				<div style="display: inline-block; border-left: 1px solid black; width: 600px;"><span style="display: block;">{donation_date}</span></div>
				</div>			 
				*/
				/*-- Tax body end --*/
			 
			 
			 
       		 	if(!empty($pdf_template->receipt_body)) 
				{
					$Taxhtml2 .= $pdf_template->receipt_body;
				}
				else $Taxhtml2 .='Receipt Body Text will be here';							          
          		$Taxhtml2 .='
        	</div>
        	<div class="tax_intent">';
				if(!empty($pdf_template->statement_intent)) 
				{
					$Taxhtml2 .= $pdf_template->statement_intent;
				}
				else $Taxhtml2 .=' Statement Intent Text will be here';							          
         		$Taxhtml2 .='
        	</div><!-- tax_intent end -->
			<div class="chairman_image">';         
				if(!empty($pdf_template->chairman_image)){
					$Taxhtml2 .='<span style="display:inline-block;width:500px;"><img src="'.JURI::root().$pdf_template->chairman_image.'" /></span>';
				}
				$Taxhtml2 .='
			</div>
			<div class="chairman">
				<span style="display:inline-block;width:500px;">'; 
				$Taxhtml2 .= (!empty($pdf_template->chairman_title))? $pdf_template->chairman_title : ''; 
				$Taxhtml2 .='
				</span>
			</div> 
		</div><!-- tax_cont end -->
		
		
		<div class="tax_footer">
     		<div class="footer_row">
        		<span>';
					$Taxhtml2 .= (!empty($pdf_template->footer1))? $pdf_template->footer1 : '' ; 
					$Taxhtml2 .='
				</span>  				
          		<span style="text-align: center;">'; 
					$Taxhtml2 .=  (!empty($pdf_template->footer2))? $pdf_template->footer2 : '';
					$Taxhtml2 .='
				</span>  				
          		<span class="last">'; 
					$Taxhtml2 .= (!empty($pdf_template->footer3))? $pdf_template->footer3 : ''; 
					$Taxhtml2 .='
				</span>         
        	</div>
        
			<div class="footer_row">
				<span>'; 
					$Taxhtml2 .= (!empty($pdf_template->footer4))? $pdf_template->footer4 : ''; 
					$Taxhtml2 .='
				</span>  
				<span style="text-align: center;">'; 
					$Taxhtml2 .= (!empty($pdf_template->footer5))? $pdf_template->footer5 : ''; 
					$Taxhtml2 .='
				</span>  
        		<span class="last">'; 
					$Taxhtml2 .=(!empty($pdf_template->footer6))? $pdf_template->footer6 : ''; 
					$Taxhtml2 .='
				</span>         
        	</div>
     	</div>
	</div>
</body></html>'; 


	/*-- Change shortcode to dynamic data --*/
	//echo "<pre> don = "; print_r( $don ); echo "</pre>";  
	$donor_name  = $don->name_first. ' '.$don->name_last;
	$donor_address =  '
							<span style="display:block;">'.$don->phy_address.'</span>
			<span style="display:block;">'.$don->phy_city.' '.$don->phy_state.'</span>
			<span style="display:block;">'.$don->phy_zip.' '.$don->phy_country.' </span>';
	$Taxhtml2 .='<span style="display:block;">123'.$don->donation_type.' 123</span>  ';
	$Taxhtml2 = str_replace('{donation_reference}',$don->Reference, $Taxhtml2);
	$Taxhtml2 = str_replace('{donor_name}', $donor_name, $Taxhtml2);
	$Taxhtml2 = str_replace('{donor_address}', $donor_address, $Taxhtml2);
	$Taxhtml2 = str_replace('{donation_date}', $don->date, $Taxhtml2);
	$amount  =  DonorforceHelper::getCurrency(). " ".$don->amount;
	$Taxhtml2 = str_replace('{donation_amount}',$amount, $Taxhtml2);
	$Taxhtml2 = str_replace('{project_description}',$don->project_description, $Taxhtml2);
	$Taxhtml2 = str_replace('{donation_type}',$don->donation_type, $Taxhtml2);
	$Taxhtml2 = str_replace('{project_name}',$don->project_name, $Taxhtml2);
	$Taxhtml2 = str_replace('{donor_number}','D'.str_pad($don->donor_id, 5, '0', STR_PAD_LEFT), $Taxhtml2);
	$Taxhtml2 = str_replace('{project_number}','P'.str_pad($don->project_id, 5, '0', STR_PAD_LEFT), $Taxhtml2);
	$Taxhtml2 = str_replace('{address_1}',$don->phy_address, $Taxhtml2);
	$Taxhtml2 = str_replace('{address_2}',$don->phy_address2, $Taxhtml2);
	$Taxhtml2 = str_replace('{city}',$don->phy_city, $Taxhtml2);
	$Taxhtml2 = str_replace('{country}',$don->phy_country, $Taxhtml2);
	
	
	
	require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';	
	$dompdf_tax = new DOMPDF();
	$dompdf_tax->load_html($Taxhtml2);
	$dompdf_tax->render();
	$output_tax = $dompdf_tax->output();
	file_put_contents(JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf", $output_tax); 
	
	
	//return $Taxhtml2;		


} 
	


}
?>