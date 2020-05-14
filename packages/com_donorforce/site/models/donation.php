<?php
// No direct access to this file

/*------------------------------------------------------------------------
# com_timesheet  mod_timesheet
# ------------------------------------------------------------------------
# author    Pixako Web Designs & Development
# copyright Copyright (C) 2010 http://www.pixako.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.pixako.com
# Technical Support:  Contact - http://www.pixako.com/contact.html
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
 

jimport('joomla.application.component.modellist');

class DonorforceModelDonation extends JModelList
{




public function registerUser($temp)
{
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_joomla', JPATH_ADMINISTRATOR);
		$lang->load('com_users', JPATH_SITE);
		
		$params = JComponentHelper::getParams('com_users');

		// Initialise the table with JUser.
		$user = new JUser;
		//$data = (array) $this->getData();

		// Merge in the registration data.
		foreach ($temp as $k => $v)
		{
			$data[$k] = $v;
		}

		//add the default group
		$group = array(0 => 2);
		$data['groups'] = $group;

		// Prepare the data for the user object.
		$data['email'] = JStringPunycode::emailToPunycode($data['email']);
		$data['password'] = $data['password'];
		$useractivation = $params->get('useractivation');
		$sendpassword = $params->get('sendpassword', 1);

		// Check if the user needs to activate their account.
		if (($useractivation == 1) || ($useractivation == 2))
		{
			$data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
			$data['block'] = 1;
		}
	
		// Bind the data.
		if (!$user->bind($data))
		{
			
			$this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			return false;
		}
	

		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Store the data.
		if (!$user->save())
		{//print_r($user->getError()); exit;
			//$this->setError($user->getError());
			$this->setError('An account with this email address or user name already exists. Please click on SIGN IN and “Forgot your password” to reset your account login.', 'warning');
			return false;
		}
	
		$config = JFactory::getConfig();
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Compile the notification mail values.
		
		
		$data = $user->getProperties();
		$data['fromname'] = $config->get('fromname');
		$data['mailfrom'] = $config->get('mailfrom');
		$data['sitename'] = $config->get('sitename');
		$data['siteurl'] = JUri::root();
		
		if(JComponentHelper::getParams('com_donorforce')->get('admin_name') != ''){
			$data['fromname'] = JComponentHelper::getParams('com_donorforce')->get('admin_name');
		};
		if(JComponentHelper::getParams('com_donorforce')->get('admin_email') != ''){
			$data['mailfrom'] = JComponentHelper::getParams('com_donorforce')->get('admin_email');
		};
		

		// Handle account activation/confirmation emails.
		if ($useractivation == 2)
		{
			// Set the link to confirm the user email.
			$uri = JUri::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base . JRoute::_('index.php?option=com_users&task=registration.activate&token=' . $data['activation'], false);

			$emailSubject = JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			if ($sendpassword)
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',
					$data['name'],
					$data['sitename'],
					$data['activate'],
					$data['siteurl'],
					$data['username'],
					$data['password_clear']
				);
			}
			else
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW',
					$data['name'],
					$data['sitename'],
					$data['activate'],
					$data['siteurl'],
					$data['username']
				);
			}
		}
		elseif ($useractivation == 1)
		{
			// Set the link to activate the user account.
			$uri = JUri::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base . JRoute::_('index.php?option=com_users&task=registration.activate&token=' . $data['activation'], false);

			$emailSubject = JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			if ($sendpassword)
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
					$data['name'],
					$data['sitename'],
					$data['activate'],
					$data['siteurl'],
					$data['username'],
					$data['password_clear']
				);
			}
			else
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW',
					$data['name'],
					$data['sitename'],
					$data['activate'],
					$data['siteurl'],
					$data['username']
				);
			}
		}
		else
		{

			$emailSubject = JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			if ($sendpassword)
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_BODY',
					$data['name'],
					$data['sitename'],
					$data['siteurl'],
					$data['username'],
					$data['password_clear']
				);
			}
			else
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_BODY_NOPW',
					$data['name'],
					$data['sitename'],
					$data['siteurl']
				);
			}
		}

		// Send the registration email.
		$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);

		// Send Notification mail to administrators
		if (($params->get('useractivation') < 2) && ($params->get('mail_to_admin') == 1))
		{
			$emailSubject = JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			$emailBodyAdmin = JText::sprintf(
				'COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY',
				$data['name'],
				$data['username'],
				$data['siteurl']
			);

			// Get all admin users
			$query->clear()
				->select($db->quoteName(array('name', 'email', 'sendEmail')))
				->from($db->quoteName('#__users'))
				->where($db->quoteName('sendEmail') . ' = ' . 1);

			$db->setQuery($query);

			try
			{
				$rows = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				$this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);
				return false;
			}

			// Send mail to all superadministrators id
			foreach ($rows as $row)
			{
				$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBodyAdmin);

				// Check for an error.
				if ($return !== true)
				{
					$this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
					return false;
				}
			}
		}

		// Check for an error.
		if ($return !== true)
		{
			$this->setError(JText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'));

			// Send a system message to administrators receiving system mails
			$db = JFactory::getDbo();
			$query->clear()
				->select($db->quoteName(array('name', 'email', 'sendEmail', 'id')))
				->from($db->quoteName('#__users'))
				->where($db->quoteName('block') . ' = ' . (int) 0)
				->where($db->quoteName('sendEmail') . ' = ' . (int) 1);
			$db->setQuery($query);

			try
			{
				$sendEmail = $db->loadColumn();
			}
			catch (RuntimeException $e)
			{
				$this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);
				return false;
			}

			if (count($sendEmail) > 0)
			{
				$jdate = new JDate;

				// Build the query to add the messages
				foreach ($sendEmail as $userid)
				{
					$values = array($db->quote($userid), $db->quote($userid), $db->quote($jdate->toSql()), $db->quote(JText::_('COM_USERS_MAIL_SEND_FAILURE_SUBJECT')), $db->quote(JText::sprintf('COM_USERS_MAIL_SEND_FAILURE_BODY', $return, $data['username'])));
					$query->clear()
						->insert($db->quoteName('#__messages'))
						->columns($db->quoteName(array('user_id_from', 'user_id_to', 'date_time', 'subject', 'message')))
						->values(implode(',', $values));
					$db->setQuery($query);

					try
					{
						$db->execute();
					}
					catch (RuntimeException $e)
					{
						$this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);
						return false;
					}
				}
			}
			return false;
		}
		

		if ($useractivation == 1)
		{
			return "useractivate";
		}
		elseif ($useractivation == 2)
		{
			return "adminactivate";
		}
		else
		{
			return $user->id;
		}
	}

public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_donorforce.donation', 'donor', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) {
			return false;
		}
		return $form;
	}
protected function loadFormData()
{
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_donorforce.default.donation.data', array());

	if (empty($data)) {
		//$data = $this->getItem();
	}

	return $data;
}

public function saveDonor($data)
{
	// Get a db connection.
   $db = JFactory::getDbo();
  
   $user = JFactory::getUser();
   $userid = $user->get('id');
  	
	
	$query = "SELECT donor_id FROM #__donorforce_donor WHERE cms_user_id =".$userid;
	$db->setQuery($query);
	
	$donor_id = $db->loadResult();
	
	
  if($donor_id > 0){
	return $donor_id;  
	}

   
	$name = $user->get('name');
	$namesplit = explode(" ",$name);
	$firstname = $namesplit[0];
	$lastname = $namesplit[1];
	
   

    
   // Create a new query object.
   $query = $db->getQuery(true);
    
   $app = JFactory::getApplication();
   $session = JFactory::getSession();
  
   // set the variables from the passed data
     
	  $columns = array(
				'name_first',
				'name_last', 
				'cms_user_id', 
				'phone',
				'mobile',
				'org_type', 
				'org_name',
				'org_latitude',
				'org_longitude',
				'status',
				'phy_address',
				'phy_city',
				'phy_state',
				'phy_zip',
				'phy_country',
				'post_address',
				'post_city',
				'post_state',
				'post_zip',
				'post_country'
				);

	 
     
	 
     $values = array(
      			$db->quote($firstname),
				$db->quote($lastname), 
				$db->quote($userid), 
				$db->quote($data['phone']),
				$db->quote($data['mobile']),
				$db->quote($data['org_type']), 
				$db->quote($data['org_name']),
				$db->quote($data['org_latitude']),
				$db->quote($data['org_longitude']),
				$db->quote($data['status']),
				$db->quote($data['phy_address']),
				$db->quote($data['phy_city']),
				$db->quote($data['phy_state']),
				$db->quote($data['phy_zip']),
				$db->quote($data['phy_country']),
				$db->quote($data['post_address']),
				$db->quote($data['post_city']),
				$db->quote($data['post_state']),
				$db->quote($data['post_zip']),
				$db->quote($data['post_country'])
				);
 
    // Prepare the insert query.
   $query
   ->insert($db->quoteName('#__donorforce_donor'))
   ->columns($db->quoteName($columns))
   ->values(implode(',', $values));
       
   
  // Set the query using our newly populated query object and execute it.
  $db->setQuery($query);
  //$db->query();
   
    if (!$db->query()) {
     JError::raiseError(500, $db->getErrorMsg());
      return false;
    } else {
			
			//Assign the donor to default acymailing list			
					if (JComponentHelper::getComponent('com_acymailing', true)->enabled){	  
							//Get the default Subscription for new donor created 
							$componentParams = JComponentHelper::getParams('com_donorforce');
							$default_subscription = $componentParams->get('acy_mailing_bridge');
								
							if($default_subscription != '0'){								
									//Query to get subscriber id
									$subscriber_query = $db->getQuery(true);
									$subscriber_query = 'SELECT subscriber.subid
																			 FROM #__acymailing_subscriber As subscriber
																			 Where subscriber.userid = '.$userid;
									$db->setQuery($subscriber_query);		
									$subscriber_id = $db->loadObject()->subid; 
										
									// insert query
									$user_sub_query = $db->getQuery(true);
									$sub_columns = array('listid', 'subid', 'subdate','status');	
									$sub_values = array($default_subscription,  $subscriber_id , strtotime("now"),1); 						
									$user_sub_query
											->insert($db->quoteName('#__acymailing_listsub'))
											->columns($db->quoteName($sub_columns))
											->values(implode(',', $sub_values));		
									$db->setQuery($user_sub_query);
									$db->execute(); 
								}					 						
				}//com_acymailing enabled
			
      return true;
      }
}


public function saveRecDonation($data, $project_id)
{		  
 // Get a db connection.
 $db = JFactory::getDbo();
 // Create a new query object.
 $query = $db->getQuery(true);		
 $app = JFactory::getApplication();
 $session = JFactory::getSession();	  
 // set the variables from the passed data	
	$data['donor_id'] = $data['cms_user_id'];		
	
	//echo "<hr />saveRecDonation<pre> data =  "; print_r($data); echo "</pre>";
	//exit; 		
	 
	if($data['donation_type'] == '' ){ $data['donation_type'] = $session->get('com_donorforce.donation_type');  }
	if($data['project_id'] == '' ){ $data['project_id'] = $session->get('com_donorforce.project_id');  }
	
	 
	$data['debit_order_status '] = 0;
			
	$query = DonorForceHelper::buildQuery($data, '#__donorforce_donor_subscriptions' );		
	
	// Set the query 
	$db->setQuery($query);
	if (!$db->query()) {
		 		JError::raiseError(500, $db->getErrorMsg());
		 		return false;
	}
	//print_r($data);
	$return_id = $db->insertid();
	
	if(isset($data['bank_name']))
	{	 	
	 //echo "<pre>";  print_r($data); exit; 
	 $query = DonorForceHelper::buildQuery($data, '#__donorforce_rec_donation' );		 
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		//$db->query();	   
		if (!$db->query()) {
		 JError::raiseError(500, $db->getErrorMsg());
		  return false;
		} else {
			$insertid = $db->insertid();
			// Set the query using our newly populated query object and execute it.
			//$db->setQuery($query);			
			/*if (!$db->query()) {
		 		JError::raiseError(500, $db->getErrorMsg());
		 		return false;
			}*/			
		  	//return $insertid;
		}
	} else {
		//return $data['donor_id'];
		return $return_id;
	}
	return $return_id;
}

public function saveRecCODonation($data, $project_id)
{		  
 // Get a db connection.
 $db = JFactory::getDbo();
 // Create a new query object.
 $query = $db->getQuery(true);		
 $app = JFactory::getApplication();
 $session = JFactory::getSession();	  
 // set the variables from the passed data	
	$data['donor_id'] = $data['cms_user_id'];		
	
	//echo "<hr />saveRecDonation<pre> data =  "; print_r($data); echo "</pre>";
	//exit; 		
	 
	if($data['donation_type'] == '' ){ $data['donation_type'] = $session->get('com_donorforce.donation_type');  }
	if($data['project_id'] == '' ){ $data['project_id'] = $session->get('com_donorforce.project_id');  }
	
	 
	$data['debit_order_status '] = 0;
			
	$query = DonorForceHelper::buildQuery($data, '#__donorforce_donor_subscriptions' );		
	
	// Set the query 
	$db->setQuery($query);
	if (!$db->query()) {
		 		JError::raiseError(500, $db->getErrorMsg());
		 		return false;
	}
	//print_r($data);
	$return_id = $db->insertid();
	
	if(isset($data['bank_name']))
	{	 	
	//  echo "<pre>";  print_r($data); exit; 
	 $query = DonorForceHelper::buildQuery($data, '#__donorforce_rec_donation' );		 
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		//$db->query();	   
		if (!$db->query()) {
		 JError::raiseError(500, $db->getErrorMsg());
		  return false;
		} else {
			$insertid = $db->insertid();
			// Set the query using our newly populated query object and execute it.
			//$db->setQuery($query);			
			/*if (!$db->query()) {
		 		JError::raiseError(500, $db->getErrorMsg());
		 		return false;
			}*/			
		  	//return $insertid;
		}
	} else {
		//return $data['donor_id'];
		return $return_id;
	}
	return $return_id;
}

function saveOnceOffDonation($data, $project_id){
//echo "<pre> "; print_r($data); exit;   
	// Get a db connection.
	$db = JFactory::getDbo();
		
	// Create a new query object.
	$query = $db->getQuery(true);
		
	   $app = JFactory::getApplication();
	   $session = JFactory::getSession();	
	   
	   	   // set the variables from the passed data
		 
		 $columns = array('donor_id','project_id','cms_user_id','date','amount','status','donation_type');
		
		 $values = array(
		  $db->quote($data['donor_id']),
		  $db->quote($project_id),
		  $db->quote($data['cms_user_id']),
		  $db->quote(date('Y-m-d H:i:s')),
		  $db->quote($data['amount']),
		  $db->quote('pending'),
		  $db->quote('onceoff'));

		// Prepare the insert query.
	   $query
	   ->insert($db->quoteName('#__donorforce_history'))
	   ->columns($db->quoteName($columns))
	   ->values(implode(',', $values));
		   
	   
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		//$db->query();
	   
		if (!$db->query()){	 
		 JError::raiseError(500, $db->getErrorMsg());
		  return false;
		} else if( ($data['gateways'] == 'paygate') 
			|| ($data['gateways'] == 'eft') 
			|| ($data['gateways'] == 'snapscan')  
			|| ($data['gateways'] == 'payfast') 
			|| ($data['gateways'] == 'paypal')){			
				 
				$inserted_id = $db->insertid();
			 	$query = $db->getQuery(true);
				 
				 $REFERENCE = 'REF-'.$data['cms_user_id'].'-'.$inserted_id; 
				 
				if( $data['gateways'] == 'paygate'){
					$REFERENCE = 'CUST-'.$data['cms_user_id'].'-'.$inserted_id; 
				}else if( $data['gateways'] == 'eft'){
					$REFERENCE = 'EFT-'.$inserted_id; 
				}else if( $data['gateways'] == 'snapscan'){
					$REFERENCE = 'SNPS-'.$inserted_id; 
				}else if( $data['gateways'] == 'payfast'){
					$REFERENCE = 'CUSTPF-'.$inserted_id; 
				}else if( $data['gateways'] == 'paypal'){
					$REFERENCE = 'CUSTPP-'.$inserted_id; 
				}
				
				$fields = array($db->quoteName('Reference') . ' = ' . $db->quote($REFERENCE) ); 
				$conditions = array($db->quoteName('donor_history_id') . ' = '.$inserted_id );
				$query->update($db->quoteName('#__donorforce_history'))->set($fields)->where($conditions);
				$db->setQuery($query); 
				$result = $db->execute();
				//echo "<pre> "; print_r($REFERENCE); print_r($inserted_id);exit; 
		  	return $inserted_id;
				
		}else { return $db->insertid();}
}

public function saveBequest($data)
{
  
		  
	   // Get a db connection.
	   $db = JFactory::getDbo();
	   
	   
	   // Create a new query object.
	   $query = $db->getQuery(true);
		
	   $app = JFactory::getApplication();
	   $session = JFactory::getSession();
	  
	   // set the variables from the passed data
	
	$data['follow'] = 'No';
	if(isset($data['REFERENCE']) && empty($data['REFERENCE'])){
		unset($data['REFERENCE']);
	}	 
	$query = DonorForceHelper::buildQuery($data, '#__donorforce_bequest');
	

		 
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		//$db->query();
	   
		if (!$db->query()) {
		 JError::raiseError(500, $db->getErrorMsg());
		  return false;
		} else {
				
				
						
			$insertid = $db->insertid();
			
			//send email
			DonorForceHelper::sendMail('','New Bequest Request Received','A User has submitted a Bequest Request on the Website, Please login to the administration of the website to follow up.', 1, 0);


		  	return $insertid;
		}
}


public function saveOnceOffNotity_2($data){		
	  //echo "<pre> saveOnceOffNotity_2 data  "; print_r($data);  exit; 
	   
	   $db = JFactory::getDbo();
	   $query = $db->getQuery(true);
	   $app = JFactory::getApplication();
	   //generate refrence here
	   
		//$data['status'] = 'successful';			
		$where = "donor_history_id=".$data['donation_history_id']; 
		$query = DonorForceHelper::buildQuery($data, '#__donorforce_history', 'UPDATE', $where);
	
		 //echo "<br/> query = ".$query; exit; 
	
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		//$db->query();  
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		} 
		
		$userinfo = DonorForceHelper::getFullUserInfo($data['cms_user_id']);
		 //echo "<pre> userinfo "; print_r($userinfo);  exit;
		//send email
		//DonorForceHelper::sendMail($userinfo->email,'New Donation Payment Successful','A new Donation is received Succefully by Donor:'.$userinfo->email, 1, 1);
		
		 return $data['donor_history_id'];	
}





function saveOnceOffNotify($data){
	//echo "<pre> saveOnceOffNotify data  "; print_r($data);  exit; 
	 // Get a db connection.
	   $db = JFactory::getDbo();
	   // Create a new query object.
	   $query = $db->getQuery(true);
	   $app = JFactory::getApplication();
	   $custom = explode('-',$data['REFERENCE']);
	   $donation_id = $custom[2];
	   $cms_user_id = $custom[1];
		if($donation_id > 0 && $cms_user_id > 0 && $data['RESULT_CODE'] == '990017')
		{
			$data['status'] = 'successful';			
			$where = "donor_history_id=".$donation_id." AND cms_user_id=".$cms_user_id;
			$query = DonorForceHelper::buildQuery($data, '#__donorforce_history', 'UPDATE', $where);
		} else {
			JError::raiseNotice(1, 'Donation Was not Successful, Please contact Admin');
			return false;
		}
			// Set the query using our newly populated query object and execute it.
			$db->setQuery($query);
			//$db->query();  
			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			} 
			$userinfo = DonorForceHelper::getFullUserInfo($cms_user_id);
			//send email
			//DonorForceHelper::sendMail('','New Donation Payment Successful','A new Donation is received Succefully by Donor:'.$userinfo->email, 1, 0);

		  	return $cms_user_id;	
}
	
	
	
	
	function saveRecNotify($data){
		//echo "<pre> saveRecNotify data  "; print_r($data);  exit; 
	 // Get a db connection.
	   $db = JFactory::getDbo();
	   
	   
	   // Create a new query object.
	   $query = $db->getQuery(true);
		
	   $app = JFactory::getApplication();
	  
	  
	$custom = explode('-',$data['REFERENCE']);
	
	
	
	$project_id = $custom[2];
	$cms_user_id = $custom[1];
	
	
		if($project_id > 0 && $cms_user_id > 0 && $data['RESULT_CODE'] == '990017')
		{
			$userinfo = DonorForceHelper::getFullUserInfo($cms_user_id);
			
			$data['status'] = 'successful';
			$data['cms_user_id'] = $cms_user_id;
			$data['donor_id'] = $userinfo->donor_id;
			$data['project_id'] = $project_id;
			$data['date'] = date('Y-m-d H:i:s');
			$data['amount'] = $data['AMOUNT'];
			$data['donation_type'] = 'recurring';
			$query = DonorForceHelper::buildQuery($data, '#__donorforce_history');
		

		} else {
			JError::raiseError(500, 'Bad Request');
			return false;
		}

			// Set the query using our newly populated query object and execute it.
			$db->setQuery($query);
			//$db->query();
		   
			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			} 
							
			
			//send email
			DonorForceHelper::sendMail('','New Recurring Donation Payment Successful','A new Recurring Donation was Succefully added by Donor:'.$userinfo->email, 1, 0);


		  	return $cms_user_id;	
	}
	
	
	
 function get_donation_history($donation_history_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("*");
		$query->from($db->quoteName('#__donorforce_history'));
		$query->where($db->quoteName('donor_history_id') . ' = '.$donation_history_id );
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result; 
}	


 function get_rec_donation_subscription($subscription_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("*");
		$query->from($db->quoteName('#__donorforce_donor_subscriptions'));
		$query->where($db->quoteName('subscription_id') . ' = '.$subscription_id );
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result; 
}	
	
	

}