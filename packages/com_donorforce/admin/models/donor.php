<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');

class DonorforceModelDonor extends JModelAdmin
{
	
	protected	$option 		= 'com_donorforce';
	protected 	$text_prefix	= 'com_donorforce';
	
    function __construct()
    {
        parent::__construct();
    }
	
	public function getForm($data = array(), $loadData = true) 
	{
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_donorforce.donor', 'donor', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_donorforce.edit.donor.data', array());

		if (empty($data)) {
			$data = $this->getItem();			
		}
		return $data;
	}
	
	public function delete(&$pks)
	{	
		$res=$this->deleteJoomlaUser($pks);
		
		if(!$res)
		{
			return false;
		}
		
		return parent::delete($pks);
	}
	
	public function save($data)
	{		
		$data['entries'] = json_encode($data['entries']);
		if(empty($data['cms_user_id'])){
			$data['cms_user_id']=$this->addJoomlaUser($data);	
		}
		
		if(empty($data['cms_user_id'])){
			$this->setMessage(JText::_('Can not Create Joomla User!'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_donorforce&view=donor&layout=edit', false));
			return false;
		}
		else{
			$user_id = $data['cms_user_id'];
			$email = $data['email'];
			$name_first = $data['name_first'];
			$name_last = $data['name_last'];
			$username = $data['username'];
			$password = $data['password'];
			$userObj = JFactory::getUser($user_id);
			$userArray = array('username' => $username, 'email' => $email, 'name' => $name_first.' '.$name_last);
			if(isset($password)){
				$userArray = array_merge($userArray,array('password' => $password, 'password2' => $password));
			}
			$userObj->bind($userArray);
			$userObj->save();
		}
		
		//Update Acymailing
		if (JComponentHelper::getComponent('com_acymailing', true)->enabled){	  
				//if( $data['donor_id'] != 0){
				$db = JFactory::getDbo();		
						$subscription = JRequest::getVar('sid', array(), 'post', 'array');						
						//echo "<br /> subscription =  <pre> ";print_r($subscription); echo "</pre>";    
						//echo "<pre>data="; print_r($data); echo "</pre>"; 
					if( $data['cms_user_id'] != 0){
								
								$Delete_user_sub_query = $db->getQuery(true);				
								$Delete_user_sub_query = '
										DELETE listsub.* 
										FROM   #__acymailing_listsub  As listsub
										INNER JOIN #__acymailing_subscriber As subscriber on subscriber.subid = listsub.subid
										Where subscriber.userid =  '.$data['cms_user_id'];														 
								$db->setQuery($Delete_user_sub_query);
								$db->execute(); 
					 }
						
						if(!empty( $subscription)){
							//Query to get subscriber id
							$subscriber_query = $db->getQuery(true);
							$subscriber_query = 'SELECT subscriber.subid
																	 FROM #__acymailing_subscriber As subscriber
																	 Where subscriber.userid = '.$data['cms_user_id'];
							$db->setQuery($subscriber_query);		
							$subscriber_id = $db->loadObject()->subid; 
							//echo "</br> subscriber_query = $subscriber_query";
							//echo "<pre> subscriber_id = ".$subscriber_id;
							//query to insert subscripber if plugin (AcyMailing : (auto)Subscribe during Joomla registration) is disabled.
							if(empty($subscriber_id)){
								$db = JFactory::getDbo();
								// Create a new query object.
								$query = $db->getQuery(true);
								// Insert columns.
								$columns = array('email', 'userid', 'name','created', 'confirmed','enabled');								
								// $current_date = new DateTime(); 
								// $current_date = $current_date->getTimestamp();
								$values = array(  
									$db->quote($data['email']), 
									$db->quote($data['cms_user_id']),
									$db->quote($data['name_first']),
									 'NOW()',
									1,
									1
								);
								// Prepare the insert query.
								$query
								->insert($db->quoteName('#__acymailing_subscriber'))
								->columns($db->quoteName($columns))
								->values(implode(',', $values));
								// Set the query using our newly populated query object and execute it.
								$db->setQuery($query);

								//echo "<pre> query = ".$query->dump();  exit; 


								$db->execute();
								$subscriber_id = $db->insertid();
							}

								// insert query
								foreach($subscription as $sub){
									//echo "<br />  sub = $sub ";					
										$user_sub_query = $db->getQuery(true);
										$sub_columns = array('listid', 'subid', 'subdate','status');	
										$sub_values = array($sub,  $subscriber_id , strtotime("now"),1); 						
										$user_sub_query
												->insert($db->quoteName('#__acymailing_listsub'))
												->columns($db->quoteName($sub_columns))
												->values(implode(',', $sub_values));		
										$db->setQuery($user_sub_query);
										$db->execute(); 

										//echo "<br />  user_sub_query = ".$user_sub_query->dump(); exit;  


								}//end foreach 									
				  }//end empty subscription if
				
				
		//}
	/*	else{
			//-- Assign the new donor the default acy mailing list  --// 
				$db = JFactory::getDbo();
				$componentParams = JComponentHelper::getParams('com_donorforce');
				$default_subscription = $componentParams->get('acy_mailing_bridge');
				
				if($default_subscription != 0){				
						//Query to get subscriber id
						$subscriber_query2 = $db->getQuery(true);
						$subscriber_query2 = 'SELECT subscriber.subid
																		 FROM #__acymailing_subscriber As subscriber
																		 Where subscriber.userid = '.$data['cms_user_id'];
						$db->setQuery($subscriber_query2);		
						$subscriber_id2 = $db->loadObject()->subid; 
						
						$user_sub_query2 = $db->getQuery(true);
						$sub_columns2 = array('listid', 'subid', 'subdate','status');	
						$sub_values2 = array($default_subscription,  $subscriber_id2 , strtotime("now"),1); 						
						$user_sub_query2
								->insert($db->quoteName('#__acymailing_listsub'))
								->columns($db->quoteName($sub_columns2))
								->values(implode(',', $sub_values2));		
						$db->setQuery($user_sub_query2);
						$db->execute();
				}//end default subsriber   				
		 }//else end */
				
		}//com_acymailing enabled
				
		
		if($data['dateofbirth'] == ''){ $data['dateofbirth'] = NULL; }  
		//echo "<pre> data = "; print_r($data);
		//exit;  
		return parent::save($data); 
	
	}
	
	function addJoomlaUser($data)
	{
		$data['name']=$data['name_first'].' '.$data['name_last'];
		
		$groups=JRequest::getVar('user_group',2);
		
		$data['groups']=explode(',',$groups);
			
		// Initialise variables;
		$pk			= (!empty($data['cms_user_id'])) ? $data['cms_user_id'] : 0;
		$user		= JUser::getInstance($pk);

		//$my = JFactory::getUser();
		
		//unset($data['id']);
		// Bind the data.
		if (!$user->bind($data))
		{
			$this->setError($user->getError());

			return false;
		}

		// Store the data.
		if (!$user->save())
		{
			$this->setError($user->getError());

			return false;
		}
		
		//$this->setState('user.id', $user->id);
		
		return $user->id;
	}
	
	function deleteJoomlaUser($pks)
	{		
		// Iterate the items to get cms_pks.		
		foreach ($pks as $i => $pk)
		{
			//could b improved to, get all records in one trip
			if ($item=$this->getItem($pk))
			{
				$cms_pk=$item->cms_user_id;
				
				$user = JUser::getInstance($item->cms_user_id);
		
				// delete the user.
				if (!$user->delete())
				{
					$this->setError($user->getError());
		
					return false;
				}
			}
			else
			{
				$this->setError('unable to fetch data');

				return false;
			}
		}
		
		return true;
				
	}
	
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();

		if ($pk > 0)
		{			
			$db=JFactory::getDbo();
			
			$query="
				SELECT
					a.*,	
					u.username,
					u.email
				FROM
					#__donorforce_donor as a
				LEFT JOIN #__users as u ON a.cms_user_id = u.id
				WHERE
					a.donor_id = $pk ";
			
			$db->setQuery($query);
			
			$item=$db->loadObject();
			
			if ($error = $db->getErrorMsg()) 
			{
				$this->setError($error);
				return false;				
			}

			return $item;
		}

		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');

		return $item;
	}
	
	public function getDonorHistory()
	{					
		$db=JFactory::getDbo();
		$donor_id=JRequest::getVar('donor_id',0);		
		$query="
		SELECT
			#__donorforce_project.`name` AS project_name,
			#__donorforce_history.date,
			#__donorforce_history.amount,
			#__donorforce_history.`Reference`,
			#__donorforce_history.`status`,
			#__donorforce_history.donor_history_id
		FROM
			#__donorforce_history
		INNER JOIN #__donorforce_project ON #__donorforce_history.project_id = #__donorforce_project.project_id
		INNER JOIN #__donorforce_donor ON #__donorforce_donor.donor_id = #__donorforce_history.donor_id
		WHERE
			#__donorforce_donor.donor_id = $donor_id	
		ORDER BY #__donorforce_history.`date` DESC
		LIMIT 20 OFFSET 0
	";
	
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		return $items;
	}

	public function getGiftHistory()
	{					
		$db=JFactory::getDbo();
		$donor_id=JRequest::getVar('donor_id',0);		
		$query="
		SELECT
			p.`name` AS project_name,
			gf.date,gf.reference,gf.status,gf.gift_id,gf.desc
			
		FROM
			#__donorforce_gift as gf
		INNER JOIN #__donorforce_project As p ON p.project_id = gf.project_id
		INNER JOIN #__donorforce_donor   AS d ON d.donor_id = gf.donor_id
		WHERE
			d.donor_id = $donor_id	
		ORDER BY gf.date DESC
		LIMIT 20 OFFSET 0
	";
	
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		return $items;
	}

	public function getDonorSubscriptions()
	{
					
		$db=JFactory::getDbo();
		$donor_id=JRequest::getVar('donor_id',0);
		
		$query="
			SELECT
				#__donorforce_donor_subscriptions.subscription_id,
				#__donorforce_donor_subscriptions.project_id,
				#__donorforce_donor_subscriptions.donation_type,
				#__donorforce_donor_subscriptions.amount,
				#__donorforce_donor_subscriptions.source,
				#__donorforce_project.`name` AS project_name,
				#__donorforce_donor_subscriptions.`transaction_id` AS transaction_id
			FROM
				#__donorforce_donor_subscriptions
			LEFT JOIN #__donorforce_project ON #__donorforce_donor_subscriptions.project_id = #__donorforce_project.project_id
			INNER JOIN #__donorforce_donor ON #__donorforce_donor_subscriptions.donor_id = #__donorforce_donor.cms_user_id
			WHERE
				#__donorforce_donor.donor_id = $donor_id			
	
	";
	
		//echo $query;
		
		$db->setQuery($query);
		
		$items=$db->loadObjectList();
		
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}

		return $items;
	}

	public function Status(&$sub = null){
		$db = JFactory::getDbo();
		
		foreach($sub as $s){
			$s->project_name = $s->project_name.'-----';
			
			$results = ''; 
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__donorforce_orders'));
			$query->where($db->quoteName('rec_donation_subscription_id') . '='. $db->quote($s->subscription_id));
			$db->setQuery($query);
			$results = $db->loadObject();
			echo "<br /> paymtne = "; print_r($results);
			if(!empty($results)){
				if($results->processor == 'paygate') $response = $this->status_xml($s->transaction_id);
			}
			
		}
		 
	}
	
	public function status_xml($transaction_id){
			echo "<br /> ------------------------------------------- xml "; 
			
			$plugin = JPluginHelper::getPlugin('payment', 'paygate');
			$params = new JRegistry($plugin->params);
			$paygate_id = 17331013346;  $params->get('paygate_id');
			$pwd =  $params->get('pwd');
			
			
			
			define( "SERVER_URL", "https://www.paygate.co.za/payxml/process.trans" );
			$XMLHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><!DOCTYPE protocol SYSTEM \"https://www.paygate.co.za/payxml/payxml_v4.dtd\">";
			
			$XMLTrans = '<protocol ver="4.0" pgid="'.$paygate_id .'" pwd="'.$pwd.'"><querytx tid="'.$transaction_id.'" /></protocol>';
			
			echo "<br /> <hr /> XMLTrans = ".htmlentities($XMLTrans, ENT_COMPAT, 'UTF-8')."<hr />";  
			
			
			$Request = $XMLHeader.$XMLTrans;
			$header[] = "Content-type: text/xml";
			$header[] = "Content-length: ".strlen($Request)."\r\n";
			$header[] = $Request;
			$ch = curl_init();
			if (!$ch) die("ERROR: cURL initialization failed.  Check your cURL/PHP configuration.");
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
			curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "POST");             
			curl_setopt ($ch, CURLOPT_URL, SERVER_URL);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);             
			$Response = curl_exec ($ch);
			
			
			$xml = simplexml_load_string($Response); // assume XML in $x
			echo "<br /><pre> xml ---> "; print_r( $xml) ; echo "<br /> ";

			
			echo "<br /> Resutl = ".htmlentities($Response, ENT_COMPAT, 'UTF-8');
			$curlError = curl_errno($ch);
			echo "<br /> curlError = ".$curlError;

			
	}

	public function delete_donorforce_history($donor_history_id,$donor_id ){		
		$db=JFactory::getDbo();			
		$query=" DELETE FROM `#__donorforce_history` WHERE  `donor_history_id` = ".$donor_history_id; 
		$db->setQuery($query);			 
		$result = $db->execute();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		echo "<br />  Delete Result =  $result "; 
		$app = JFactory::getApplication();
		if($result == 1){
			$app->redirect('index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$donor_id, 'Donation Deleted Successfully');
	    }else{  
			$app->redirect('index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$donor_id, 'Error In Deleting Donation');
		}
			
	}
	public function GetDonorsData(){
		$db=JFactory::getDbo();
		$query="
			SELECT donor_id, name_first 
			FROM #__donorforce_donor ";
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		//print_r($items);
		return $items;
	}
	
	public function getCountDonorHistory()
	{					
		$db=JFactory::getDbo();
		$donor_id=JRequest::getVar('donor_id',0);		
		$query="
		SELECT COUNT(*)	AS total_rows		
		FROM
			#__donorforce_history
		INNER JOIN #__donorforce_project ON #__donorforce_history.project_id = #__donorforce_project.project_id
		INNER JOIN #__donorforce_donor ON #__donorforce_donor.donor_id = #__donorforce_history.donor_id
		WHERE
			#__donorforce_donor.donor_id = $donor_id			
		ORDER BY #__donorforce_history.`date` DESC	
	";
		$db->setQuery($query);
		$items=$db->loadObject();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		return $items;		
	}

	public function getCountDonorGifts()
	{					
		$db=JFactory::getDbo();
		$donor_id=JRequest::getVar('donor_id',0);		
		$query="
		SELECT COUNT(*)	AS total_rows		
		FROM
			#__donorforce_gift As gf
		INNER JOIN #__donorforce_project As p ON gf.project_id = p.project_id
		INNER JOIN #__donorforce_donor As d ON gf.donor_id = d.donor_id
		WHERE
			d.donor_id = $donor_id			
		ORDER BY gf.`date` DESC	 ";
		
		$db->setQuery($query);
		$items=$db->loadObject();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		return $items;		
	}

	public function resend_tankyou( $history_id,$donor_id ){
		$app    = JFactory::getApplication();
		$params = DonorForceHelper::getParams();
		$don  = DonorforceHelper::getLatetsDonation($history_id); 
		DonorforceHelper::sendThankYouPDF($don); 
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();	
		$mailer->setSender(array(
						$params->get('admin_email',$app->getCfg('mailfrom')), 
						$params->get('admin_name',$app->getCfg('fromname'))
		));				
		$mailer->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));
		$recipient = $don->email; 
		$mailer->addRecipient($recipient);
		$body   = '<h2>Donation Receipt</h2>'
			. '<div>Thankyou for your Donation, Please find the attached Donation Receipt</div>';
		$mailer->isHTML(true);
		$mailer->setBody($body);
		$mailer->addAttachment(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf");
		$send = $mailer->Send();
		
		$app = JFactory::getApplication();
		if($send == 1){
			$app->redirect('index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$donor_id, 'Thank Your Letter ReSend Successfully');
	    }else{  
			$app->redirect('index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$donor_id, 'Error In Sending Thank Your Letter');
		}

	}

	public function resend_receipt( $history_id,$donor_id ){
		$app    = JFactory::getApplication();
		$params = DonorForceHelper::getParams();
		$don  = DonorforceHelper::getLatetsDonation($history_id); 
		DonorforceHelper::sendTaxReceiptPDF($don); 
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();	
		$mailer->setSender(array(
						$params->get('admin_email',$app->getCfg('mailfrom')), 
						$params->get('admin_name',$app->getCfg('fromname'))
		));				
		$mailer->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));
		$recipient = $don->email; 
		$mailer->addRecipient($recipient);
		$body   = '<h2>Donation Receipt</h2>'
			. '<div>Thankyou for your Donation, Please find the attached Donation Receipt</div>';
		$mailer->isHTML(true);
		$mailer->setBody($body);
		$mailer->addAttachment(JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf");
		$send = $mailer->Send();
		if($send == 1){
			$app->redirect('index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$donor_id, 'Donation Receipt ReSend Successfully');
	    }else{  
			$app->redirect('index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$donor_id, 'Error In Sending Donation Receipt');
		}
		
	}
	function exportuser(){
		 $db = JFactory::getDBO();
		 $data    = JRequest::getUser(); 
		 $data_id = (int) $this->item->donor_id;		 
		 
		 $query="
				SELECT
					d.* WHERE d.id == $data_id
				FROM
					#__donorforce_donor AS d
				-- INNER JOIN #__donorforce_donor AS d ON s.donor_id = d.cms_user_id
				-- INNER JOIN #__users AS u ON d.cms_user_id = u.id 
				-- INNER JOIN #__donorforce_project AS p ON s.project_id = p.project_id 
				";
				
			

			//echo "<br /> query =  ".$query; 
			$db->setQuery($query);
			$data = $db->loadObjectList();	
			$export_data = array();	
			//echo "<pre> data = "; print_r( $data  ); echo "</pre>";  
	
			foreach($data as $single){  
					$exp_single = array( 
											 'donor_id' => $single->donor_id,
											 'name_title' => $single->name_title,											 
											 'name_first' => $single->name_first,
											 'name_last' => $single->name_last,		
											 'phone'=>$single->phone,				 					 
											 'dateofbirth' => $single->dateofbirth,
											 'mobile' => $single->mobile,
											 'email' => $single->donation_start_date,
											 'org_type' => $single->donation_end_date,
											 'user_created' => $single->deduction_day,
											 'org_name' => $single->frequency,
											 'level' => $single->subscription_id,
											 'membership' => $single->membership,
											 'status' => $single->status,
											 'published' => $single->published,
											 											 
											);
					$export_data[] = $exp_single;					
			 }
				
			$header = array('Donor Number', 'Donor ID', 'First Name', 'Sur Name','Telephone','Date Of Birth', 'Mobile', 'Email','Donation Start Date','Donation End Date',
			'Organization Type','Date Of User Creation','Organization Name','Donor level','Membership Status','Status','Publication Status');
			
			$header_type = array( 
					'donor_id' =>'Label',
					'name_title' => 'Label', 
					'name_first'=> 'Label', 
					'name_last'=> 'Label', 
					'phone'=> 'Number', 
					'dateofbirth'=> 'Label', 
					'mobile'=> 'Number', 
					'email'=>'Label',
					'org_type' =>'Label', 
					'level'=> 'Label', 
					'membership' =>'Label',
					'status' => 'Label',
					'published' => 'Number'
					
			);				
			//$this->xls('Subscription_Export',$header,$header_type,$export_data);
			$this->phpexcel('exportuser',$header,$header_type,$export_data);
	}//exportxls
}
?>