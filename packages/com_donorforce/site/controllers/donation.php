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


class DonorforceControllerDonation extends DonorforceController
{
	
	function display($cachable = false, $urlparams = false) {
		parent::display();
    }
	
	
	function save(){
	
		$app = JFactory::getApplication();
	
		$data = JRequest::get('post');
		
		if($data['project_id'] > 0 && $data['donationtype'] != '')
		{
			
			$session = JFactory::getSession();			
			$session->clear('com_donorforce.project_id');
			$session->clear('com_donorforce.donationtype');
			
			$session->set('com_donorforce.project_id', $data['project_id']);
			$session->set('com_donorforce.donationtype', $data['donationtype']);
			
			$user = JFactory::getUser();
			
			if($user->get('guest'))
			{
				$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep1&project_id='.$data['project_id'].'&donationtype='.$data['donationtype']);
			} else {
				
				$userinfo = DonorForceHelper::getFullUserInfo($user->id);
				if($userinfo->donor_id > 0)
				{
					$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep3&project_id='.$data['project_id'].'&donationtype='.$data['donationtype']);
				} else {
					$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep2&project_id='.$data['project_id'].'&donationtype='.$data['donationtype']);	
				}
			}
						
		} else {
			$app->redirect('index.php?option=com_donorforce&view=projects',"Please Select a Project First!");	
		}
		
	}
	
	function saveuser()
	{   
	
		$app = JFactory::getApplication();
  		$session = JFactory::getSession();
  
		$model  = $this->getModel('donation');
 	
        $jinput = JFactory::getApplication()->input;
		$donationtype = $jinput->get('donationtype'); 
		$project_id = $jinput->get('project_id');
		    
		$data = JRequest::get('post');
   
 
       
	$saveuser = $model->registerUser($data);
	
	
	// Get the validation messages.
	$errors = $model->getErrors();
	
	// Push up to three validation messages out to the user.
	for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
	{
		if ($errors[$i] instanceof Exception)
		{
			$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
		} else {
			$app->enqueueMessage($errors[$i], 'warning');
		}
	}
		
	if($saveuser){	
			 	
	$credentials = array( 'username' => $data['username'], 'password' => $data['password']);
  
  	$app->login($credentials,array('silent'=>true));
	
	} else {
		//JError::raiseWarning(1," record failed to be saved");
		 
		$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep1&project_id='.$project_id ,"Failed to Create User", 'warning');	
	}
	
	$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep2&project_id='.$project_id.'&donationtype='.$donationtype,"Account Created Successfully");
        
	}
	
	function savedonor(){
		
		
		$app = JFactory::getApplication();
  		$session = JFactory::getSession();
  
		$model  = $this->getModel('donation');
 
               
		$data = JRequest::getVar('jform', array(), 'post', 'array');
   		
		$jinput = JFactory::getApplication()->input;
		$donationtype = $jinput->get('donationtype');
		$project_id = $jinput->get('project_id'); 
		
		if($donationtype == ''){
 			$donationtype = $session->get('com_donorforce.donationtype');
		}
		if($project_id == ''){ 
			$project_id = $session->get('com_donorforce.project_id');
		}
   	// For Onceoff Donation 
		
		if($model->saveDonor($data)){
		/*
			$view_name = "payment";
       		$view_config = array();
    	    $view = &$this->getView($view_name, 'html', '', $view_config);
        	$view->setLayout("default");  
       
			$view->assign('email', 'test@test.com');

			$view->display();
			*/	
			
		// Get the validation messages.
		$errors = $model->getErrors();
	
		// Push up to three validation messages out to the user.
		for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
		{
			if ($errors[$i] instanceof Exception)
			{
				$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
			} else {
				$app->enqueueMessage($errors[$i], 'warning');
			}
		}

		//$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep3&project_id='.$project_id.'&donationtype='.$donationtype,"Information Added Successfully");
		
			$app->redirect('index.php?option=com_donorforce&view=donationsimple&project_id='.$project_id,"Information Added Successfully");
		
		} else {
			//JError::raiseWarning(1," record failed to be saved");
			$app->redirect('index.php?option=com_donorforce&view=projects',"Failed to Create User");	
		}
		
	}
	
	function savedonation(){

		$app = JFactory::getApplication();
  		$session = JFactory::getSession();
		$model  = $this->getModel('donation');
		$data = JRequest::get('post');
 		$donationtype = $session->get('com_donorforce.donationtype');
		$project_id = $session->get('com_donorforce.project_id');
		$userid = DonorForceHelper::getLoggedin();
		$data['cms_user_id'] = $userid;
		$userinfo = DonorForceHelper::getFullUserInfo($userid);
		$data['donor_id'] = $userinfo->donor_id;      	   
	   	// For Onceoff Donation 
		
		$data['amount'] = $data['donationamount'];
		$data['amountdisp'] = $data['donationamount'];

		if($data['otheramount'] > 0){	
				$pattern = '~^-?[0-9]+(\.[0-9]+)$~xD';
				if(preg_match($pattern,$data['otheramount']))
				{
					$otheramount = (string)$data['otheramount'];
					$data['amount'] = str_replace('.','',$otheramount);	
					$data['amountdisp'] = $otheramount;	
				} else {
					$data['amount'] = $data['otheramount'].'00';
					$data['amountdisp'] = $data['otheramount'];
				}
				
		} 
		
		$donationid = $model->saveOnceOffDonation($data, $project_id, $userinfo);
	   // echo "<pre> saveOnceOffDonation = "; print_r($data);  

	//echo "<br />  Donation Database inserted  "; exit;

		if($donationid > 0){
		// Get the validation messages.
		$errors = $model->getErrors();
		// Push up to three validation messages out to the user.
		for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
		{
			if ($errors[$i] instanceof Exception)
			{
				$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
			} else {
				$app->enqueueMessage($errors[$i], 'warning');
			}
		}
		
		$data['donationid'] = $donationid;
	
		$view_name = "payment";
		$view_config = array();
		$view = &$this->getView($view_name, 'html', '', $view_config);
		$view->setLayout("default");
		
	   $userinfo = DonorForceHelper::getFullUserInfo($data['cms_user_id']);
	   
	   $data['email'] = $userinfo->email;
	   $data['name'] = $userinfo->name; 

	   		$view->assign('data', $data);
			$view->assign('userinfo', $userinfo);
			$view->assign('paymentconfig', DonorForceHelper::getPaymentConfigs());
			$view->assign('document', JFactory::getDocument());
	
			$view->display();
			
		
		} else {
			//JError::raiseWarning(1," record failed to be saved");
			$app->redirect('index.php?option=com_donorforce&view=projects',"Failed to Add Amount");	
		}
		
		
			
		
	}
	
	//FAHZ edit
	function ProcessToPaymentGatways(){		
		$app = JFactory::getApplication();
  	$session = JFactory::getSession();
		$model  = $this->getModel('donation');
		$data = JRequest::get('post');
 		$jinput = JFactory::getApplication()->input;
		$donationtype = $jinput->get('donationtype'); 
		$project_id = $jinput->get('project_id');		
		if($donationtype == ''){ 
			$donationtype = $session->get('com_donorforce.donationtype');	
		}
		if($project_id == ''){ 
			$project_id = $session->get('com_donorforce.project_id');
		}
		
		//$donationtype = $session->get('com_donorforce.donationtype');
		//$project_id = $session->get('com_donorforce.project_id');
		$userid = DonorForceHelper::getLoggedin();
		$data['cms_user_id'] = $userid;
		$userinfo = DonorForceHelper::getFullUserInfo($userid);
		$data['donor_id'] = $userinfo->donor_id;      	   	   	
		if(isset($data['jform']['otheramount'])){
			$data['otheramount'] = 	$data['jform']['otheramount'];
		}
		if(isset($data['jform']['donationamount'])){
			$data['donationamount'] = 	$data['jform']['donationamount'];
		}
		if(!$data['otheramount']){
			$data['amount'] = $data['donationamount'];
			$data['amountdisp'] = $data['donationamount'];
		}else{
			$data['amount'] = $data['otheramount'];
			$data['amountdisp'] = $data['otheramount'];
		}

		// For recurringDO Donation
		//echo "<pre> data before  "; print_r($data);  echo "</pre>";  
	//	if($donationtype == 'recurringDO' ||  $donationtype == 'recurringCO' ){ 
			if($donationtype == 'recurringDO' ){ 	
			$UserDebitInfo = DonorForceHelper::getUserDebitInfo($userid);
				
				//echo "<pre> UserDebitInfo  "; print_r($UserDebitInfo);  echo "</pre>"; 
				
			foreach($UserDebitInfo as $key=>$value){
				//echo "<br /> key = $key   value = $value"; 
				if( !isset($data['jform'][$key]) || empty($data['jform'][$key])){
						$data['jform'][$key] = $value; 
					}
			}
			
		}
		
		
		
		
		
		
		/*echo "<hr /> <pre>  UserDebitInfo  "; print_r($UserDebitInfo); 
		echo "<hr /> <pre> data after  "; print_r($data); exit; */
		

		$view_name = "payment";
		$view_config = array();
		$view = &$this->getView($view_name, 'html', '', $view_config);
		$view->setLayout("default");
		
	    $userinfo = DonorForceHelper::getFullUserInfo($data['cms_user_id']);
	   
	   $data['email'] = $userinfo->email;
	   $data['name'] = $userinfo->name; 
	   $data['project_id'] = $project_id ; 
		$view->assign('data', $data);
		$view->assign('userinfo', $userinfo);
		$view->assign('donationtype',$donationtype);
		$view->assign('project_id',$project_id);
		$view->assign('paymentconfig', DonorForceHelper::getPaymentConfigs());
		$view->assign('document', JFactory::getDocument());	
		$view->display();
	}
	//FAHZ edit
	
	function saverecdonation(){
		
		$app = JFactory::getApplication();
  		$session = JFactory::getSession();
  		
		$params	= $app->getParams();
		
		$model  = $this->getModel('donation');

		$data = JRequest::getVar('jform', array(), 'post', 'array');
  		// print_r($_REQUEST); exit;
 		$donationtype = $session->get('com_donorforce.donationtype');
		$project_id = $session->get('com_donorforce.project_id');
		
		
		
		/*if(empty($data['donationamount']) && empty($data['otheramount']))
		{
			JError::raiseWarning(1,"Please Select a donation amount or Type the Other Donation Amount");
			$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep3');	
		}*/
		
		$userid = DonorForceHelper::getLoggedin();
		$data['cms_user_id'] = $userid;
      	   
	 	$data['amount'] = $data['donationamount'];
		$data['amountdisp'] = $data['donationamount'];

		if($data['otheramount'] > 0){
				
				$pattern = '~^-?[0-9]+(\.[0-9]+)$~xD';
					
				if(preg_match($pattern,$data['otheramount']))
				{
					$otheramount = (string)$data['otheramount'];
					$data['amount'] = str_replace('.','',$otheramount);	
					$data['amountdisp'] = $otheramount;	
				} else {
					$data['amount'] = $data['otheramount'].'00';
					$data['amountdisp'] = $data['otheramount'];
				}
				
		} 
		
		  $session->set('donation.amount',$data['amount']); 
	  	// For Onceoff Donation 	
		if($model->saveRecDonation($data, $project_id)){
		/*
			$view_name = "payment";
       		$view_config = array();
    	    $view = &$this->getView($view_name, 'html', '', $view_config);
        	$view->setLayout("default");  
       
			$view->assign('email', 'test@test.com');

			$view->display();
			*/	
			
		// Get the validation messages.
		$errors = $model->getErrors();
	
		// Push up to three validation messages out to the user.
		for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
		{
			if ($errors[$i] instanceof Exception)
			{
				$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
			} else {
				$app->enqueueMessage($errors[$i], 'warning');
			}
		}
		

		$userinfo = DonorForceHelper::getFullUserInfo($data['cms_user_id']);
		$data['email'] = $userinfo->email;
		$data['name'] = $userinfo->name;
		
		//If Debit Credit Card
	
		$p=DonorForceHelper::getProject($project_id );
		
			
			if(isset($_REQUEST['isCredit']) && $_REQUEST['isCredit']==1){
			
			$data['donationid'] = $project_id;
			$data['cc'] = 1;
			
				$view_name = "payment";
				$view_config = array();
				$view = &$this->getView($view_name, 'html', '', $view_config);
				$view->setLayout("default");
		   
				$view->assign('data', $data);
				$view->assign('userinfo', $userinfo);
				$view->assign('paymentconfig', DonorForceHelper::getPaymentConfigs());
				$view->assign('document', JFactory::getDocument());
		
				$view->display();
			} else {
		
				$data['donationid'] = $project_id;
				$data['cc'] = 1;
			
				$view_name = "payment";
				$view_config = array();
				$view = &$this->getView($view_name, 'html', '', $view_config);
				$view->setLayout("debitorder");
		   		   
		   
				$view->assign('data', $data);
				$view->assign('userinfo', $userinfo);
				$view->assign('paymentconfig', DonorForceHelper::getPaymentConfigs());
				$view->assign('document', JFactory::getDocument());
		
				$view->display();
	

			// If Debit Bank
			//$app->redirect('index.php?option=com_donorforce&view=projects',"Thank you for your donation of ".substr($data["amount"], 0, -2).".00"."   as a recurring order towards the ".$p->name);
			}
		} else {
			//JError::raiseWarning(1," record failed to be saved");
			$app->redirect('index.php?option=com_donorforce&view=projects',"Failed to Create User");	
		}	
		
	}
	
	function savebequest(){
		
		
		$app = JFactory::getApplication();
  		$session = JFactory::getSession();
  
		$model  = $this->getModel('donation');
 
               
		$data = JRequest::getVar('jform', array(), 'post', 'array');
   
 		$donationtype = $session->get('com_donorforce.donationtype');
		$project_id   = $session->get('com_donorforce.project_id');
		
		$userid              = DonorForceHelper::getLoggedin();
		$data['cms_user_id'] = $userid;
      	   
	   	// For Onceoff Donation 
		
		if($model->saveBequest($data)){
		/*
			$view_name = "payment";
       		$view_config = array();
    	    $view = &$this->getView($view_name, 'html', '', $view_config);
        	$view->setLayout("default");  
       
			$view->assign('email', 'test@test.com');

			$view->display();
			*/	
			
		// Get the validation messages.
		$errors = $model->getErrors();
	
		// Push up to three validation messages out to the user.
		for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
		{
			if ($errors[$i] instanceof Exception)
			{
				$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
			} else {
				$app->enqueueMessage($errors[$i], 'warning');
			}
		}
		$p=DonorForceHelper::getProject($project_id );
		$app->redirect('index.php?option=com_donorforce&view=projects',"Thank you for your donation  as a Bequest  towards the  ". $p->name );
		
		} else {
			//JError::raiseWarning(1," record failed to be saved");
			$app->redirect('index.php?option=com_donorforce&view=projects',"Failed to Create User");	
		}
		
	}
	
	function notify(){
	
		$app = JFactory::getApplication();
  		$session = JFactory::getSession();
  		$don=DonorForceHelper::getLatetsDonation(); 
		$params	= $app->getParams();
		
		$model  = $this->getModel('donation');
		$amount = $session->get('donation.amount');
		$data = JRequest::get('post');
		//print_r($data); exit;
		if(empty($data['CHECKSUM']))
		{
			echo 'Bad Request';
			return;	
		}
		
		$pconfig = DonorForceHelper::getPaymentConfigs();
		
		$project_id  = $session->get('com_donorforce.project_id');
		$projectType = $session->get('com_donorforce.donationtype');

		$p=DonorForceHelper::getProject($project_id);
		if(isset($data['SUBSCRIPTION_ID']) && !empty($pconfig['paygate_id']))
		{
				//If OnceOff Donation
				if($model->saveRecNotify($data))
				{
					
				    $view_name = "payment";
					$view_config = array();
					$view = &$this->getView($view_name, 'html', '', $view_config);
					$view->setLayout("thankyou");  
				
					$view->assign('document', JFactory::getDocument());
					$view->assign('message', "Thank you for your donation of <b>".DonorForceHelper::getCurrency()." ".DonorForceHelper::displayAmount($data['AMOUNT'])."</b>  as a ".$projectType." towards the  <b>".$p->name." </b>");
					
					$view->display();
					?> 
					<script>
                    jQuery.get('index.php?option=com_donorforce&task=ajax.genratePdf&tmpl=component&format=raw',function(e){})
                    </script>

					
					<?php
			
				}
			
		} else {
		
		
			//If OnceOff Donation
			if($model->saveOnceOffNotify($data)){
			
			$view_name = "payment";
       		$view_config = array();
    	    $view = &$this->getView($view_name, 'html', '', $view_config);
        	$view->setLayout("thankyou");  
       		$view->assign('document', JFactory::getDocument());
			$view->assign('message', "Thank you for your donation of <b>".DonorForceHelper::getCurrency()." ".DonorForceHelper::displayAmount($data['AMOUNT'])."</b>  as a Special Gift towards the  <b>".$p->name." </b>..");

			$view->display();
			?> 
					<script>
                    jQuery.get('index.php?option=com_donorforce&task=ajax.genratePdf&tmpl=component&format=raw',function(e){})
                    </script>

					
					<?php
			}
			
		}
		
	}
}
?>