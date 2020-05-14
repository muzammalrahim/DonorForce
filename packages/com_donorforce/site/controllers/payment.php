<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Payments controller class.
 */
class DonorforceControllerPayment extends DonorforceController
{
	function getHTML() {
		$model= $this->getModel( 'payment');
		$jinput=JFactory::getApplication()->input;
		$pg_plugin = $jinput->get('processor');
		$user = JFactory::getUser();
		$session =JFactory::getSession();
		$order_id = $jinput->get('order');
		$html=$model->getHTML($pg_plugin,$order_id);
		if(!empty($html[0]))
		echo $html[0];
		jexit();
	}

	function confirmpayment(){ 
		$model= $this->getModel( 'payment');
		$session =JFactory::getSession();
		$jinput=JFactory::getApplication()->input;
		$order_id = $session->get('order_id');
		$pg_plugin = $jinput->get('processor');
		$response=$model->confirmpayment($pg_plugin,$order_id);
	}

	/** Payment gateway sends payment response to notify URL. */
	function processpayment()
	{  
		$mainframe=JFactory::getApplication();
		$jinput=JFactory::getApplication()->input;
		$session =JFactory::getSession();
		$post = JRequest::get('post');
		if($session->has('payment_submitpost')){
			$post = $session->get('payment_submitpost');
			$session->clear('payment_submitpost');
		}
		else{
			//$post = JRequest::get('post');
			$rawDataPost = JRequest::get('POST');
			$rawDataGet = JRequest::get('GET');
			//echo "<pre> rawDataPost = "; print_r($rawDataPost); echo "</pre>";
			//echo "<pre> rawDataGet = "; print_r($rawDataGet); echo "</pre>";  
			
			$post = array_merge($rawDataGet, $rawDataPost);
			//echo "<pre> all post = "; print_r($post); echo "</pre>"; exit;    
		}
		
		
		$pg_plugin = $jinput->get('processor');
		$order_id = $jinput->get('order_id','','STRING');
		if($pg_plugin == 'payfast'){
			header( 'HTTP/1.0 200 OK' );
			flush();
			$db = JFactory::getDbo();

			// Create a new query object.
			$query = $db->getQuery(true);

			// Select all records from the user profile table where key begins with "custom.".
			// Order it by the ordering field.
			$query->select('*');
			$query->from($db->quoteName('#__donorforce_orders'));
			$query->where($db->quoteName('id') . ' = '. $db->quote($order_id));

			// Reset the query using our newly populated query object.
			$db->setQuery($query);

			// Load the results as a list of stdClass objects (see later for more options on retrieving data).
			$row = $db->loadAssoc();
			if($row['status'] == 'C' && !isset($post['taskaction']) && $post['taskaction'] != 'return'){
				exit;
			}
		}
		$model= $this->getModel('payment');

		if(empty($post) || empty($pg_plugin) ){
			JFactory::getApplication()->enqueueMessage(JText::_('SOME_ERROR_OCCURRED'), 'error');
			return;
		}
		$person=json_encode($post);
		
		$donation_id = JRequest::getVar('donation_id');
		
		//echo "<pre> post =  "; print_r($post); echo "</pre>"; exit;   
		if($post['donationtype'] == 'recurringCO' &&  $donation_id !=''){
			$response=$model->processpaymentRCO($post,$pg_plugin,$order_id);
			//$mainframe->redirect($response['return'],$response['msg']);			

		}else if($post['donationtype'] != 'recurringDO' &&  $donation_id !=''){
			$response=$model->processpayment($post,$pg_plugin,$order_id);
			$mainframe->redirect($response['return'],$response['msg']);
		}else{			
			$response=$model->processpaymentRec($post,$pg_plugin,$order_id);
			$mainframe->redirect($response['return'],$response['msg']);		  
			//$mainframe->redirect($return_url);			
		}
	}
	
	function save()
	{		
		//echo "<br /> save";  
		$mainframe=JFactory::getApplication();
		$jinput=JFactory::getApplication()->input;

		// GETTING POST DATA
		$post	= JRequest::get('post');
		//echo "<pre> payment.save post = "; print_r($post);  exit;
		
		$data = array();
		$data['donor_id']= $post['donor_id']; 
		$data['cms_user_id']=$post['cms_user_id']; 
		$data['amount']=$post['amount']; 
		//echo "<pre> data ="; print_r($data); 
		
		
		// GET DONATION MODEL
		$session =JFactory::getSession();
		//$donation_type = $session->get('com_donorforce.donationtype');			
		//$jinput = JFactory::getApplication()->input;
		$donation_type = $post['donationtype']; 
		$project_id = $post['project_id'];		
		if($donation_type == ''){ 
			$donation_type = $session->get('com_donorforce.donationtype');	
		}
		if($project_id == ''){ 
			$project_id = $session->get('com_donorforce.project_id');
		}
		
		if($donation_type == 'onceoff'){
			$Donation_model = $this->getModel('donation');
			$donationid = $Donation_model->saveOnceOffDonation($data, $post['project_id']);
			//echo "<pre> donationid =".$donationid; 
			$post['donation_history_id'] = $donationid; 			
			// GET MODEL
			$model= $this->getModel('payment');
			$order_id=$model->store($post);		
			$session = JFactory::getSession();
			$session->clear('order_id');
			$session->clear('donation_history_id');
			$session->clear('cms_user_id');
			//$session->clear('project_id');					
			$session->set('order_id',$order_id);
			$session->set('donation_history_id',$donationid);
			$session->set('cms_user_id',$data['cms_user_id']);
			//$session->set('project_id',$post['project_id']);						
			$Itemid=0;
			$msg='';		
		// exit; 
				
		$link=JURI::root().substr(JRoute::_('index.php?option=com_donorforce&view=payment&layout=pay&donation='.$donationid.'&order_id='.$order_id.'&donationtype='.$donation_type,false),strlen(JURI::base(true))+1);
		$mainframe->redirect($link,$msg);
		
		
		}else if( $donation_type == 'recurringCO'){  
			$Donation_model = $this->getModel('donation');
			
					$data['donation_start_date'] = $post['donation_start_date'];
					$data['donation_end_date'] = $post['donation_end_date'];					
					$data['deduction_day'] = $post['deduction_day'];
					$data['frequency'] = $post['frequency'];
					
					
			
			
			//echo "<pre> data in saving recurring co "; print_r($data); echo "</pre>"; exit; 
			
			
			
			
		    $Recdonationid = $Donation_model->saveRecDonation($data, $post['project_id']);
			
			//echo "<pre> Recdonationid =".$Recdonationid; 
			$post['donation_history_id'] = $Recdonationid; 			
			// GET MODEL
			$model= $this->getModel('payment');
			  
			$post['rec_donation_subscription_id'] = $Recdonationid; 
			$post['donation_history_id'] = '';  unset($post['donation_history_id']);
			
			$order_id=$model->store($post);		
			
			$session = JFactory::getSession();
			$session->clear('order_id');
			$session->clear('donation_history_id');
			$session->clear('cms_user_id');
			//$session->clear('project_id');					
			$session->set('order_id',$order_id);
			//$session->set('donation_history_id',$donationid);
			$session->set('cms_user_id',$data['cms_user_id']);
			//$session->set('project_id',$post['project_id']);						
			$Itemid=0;
			$msg='';		
			
			
			$link=JURI::root().substr(JRoute::_('index.php?option=com_donorforce&view=payment&layout=pay&Recdonationid='.$Recdonationid.'&order_id='.$order_id.'&donationtype='.$donation_type.'&Itemid='.$Itemid,false),strlen(JURI::base(true))+1);
		$mainframe->redirect($link,$msg);
			
		}else if ($donation_type == 'recurringDO'){
			
					//echo "<pre> payment save recurringDO "; print_r($post); echo "</pre>";  //exit;   			
				$userid = DonorForceHelper::getLoggedin();
				$userinfo = DonorForceHelper::getFullUserInfo($userid);		
				$data['cms_user_id'] = $userid;		
				$data['donor_id'] = $userinfo->donor_id; 
											
				if(isset($post['jform']['otheramount']) && ($post['jform']['otheramount'] != '') ){
					$data['amount'] = 	$post['jform']['otheramount'];
				}
				else{
					$data['amount'] = 	$post['jform']['donationamount'];
				}
					
					$data['bank_name'] = $post['jform']['bank_name'];
					$data['account_number'] = $post['jform']['account_number'];
					$data['account_name'] = $post['jform']['account_name'];
					$data['account_type'] = $post['jform']['account_type'];
					$data['branchcode'] = $post['jform']['branchcode'];
					$data['branch_name'] = $post['jform']['branch_name'];
					$data['comp_code'] = $post['jform']['comp_code'];
					$data['beneficiary_reference'] = $post['jform']['beneficiary_reference'];
					//$data['gateways'] = $post['gateways'];
					$data['donation_start_date'] = $post['jform']['donation_start_date'];
					$data['donation_end_date'] = $post['jform']['donation_end_date'];
					
					$data['deduction_day'] = $post['jform']['deduction_day'];
					$data['frequency'] = $post['jform']['frequency'];
					
					//$data['task'] = $post['task'];;
					$data['donation_type']= $donation_type;
					$data['project_id']= $project_id; 
					
				//	echo "<hr /><pre> post =  "; print_r($post); echo "</pre>";
				//	echo "<hr /><pre> data =  "; print_r($data); echo "</pre>";
					//exit; 
					
					$Donation_model = $this->getModel('donation');
					$Recdonationid = $Donation_model->saveRecDonation($data, $post['project_id']);
					
					//echo "<pre> Recdonationid =".$Recdonationid; 
					$post['donation_history_id'] = $Recdonationid;
					$post['amount']   = 	$data['amount'];	
					$post['donor_id'] = 	$data['donor_id'];			
					$post['cms_user_id'] = $data['cms_user_id'];	
					$post['gateways'] = 'noplugin';	
					
					// GET MODEL
					$model= $this->getModel('payment');
					$order_id=$model->store($post);		
					
					$session = JFactory::getSession();
					$session->clear('order_id');
					$session->clear('donation_history_id');
					$session->clear('cms_user_id');
					//$session->clear('project_id');					
					$session->set('order_id',$order_id);
					//$session->set('donation_history_id',$donationid);
					$session->set('cms_user_id',$data['cms_user_id']);
					//$session->set('project_id',$post['project_id']);						
					$Itemid=0;
					$msg='';					
					
					$link=JURI::root().substr(JRoute::_('index.php?option=com_donorforce&view=payment&layout=debit&Recdonationid='.$Recdonationid.'&order_id='.$order_id.'&donationtype='.$donation_type.'&Itemid='.$Itemid,false),strlen(JURI::base(true))+1);
				$mainframe->redirect($link,$msg);
			 
		}
		
		
		
		
	}
	
	
	
	
	function saveonepage()
	{		
		$jinput = JFactory::getApplication()->input;
		$mainframe=JFactory::getApplication();
		$jinput=JFactory::getApplication()->input;
		// GETTING POST DATA
		$post	= JRequest::get('post');
		
		$data = array();
		$data['donor_id']= $post['donor_id']; 
		$data['cms_user_id']=$post['cms_user_id']; 
		$data['amount']=$post['amount'];
		$data['gateways'] = $post['gateways']; 

		/*if(isset($data['jform']['otheramount'])){
			$data['otheramount'] = 	$post['jform']['otheramount'];
		}
		if(isset($data['jform']['donationamount'])){
			$data['donationamount'] = 	$post['jform']['donationamount'];
		}
		if(!$data['otheramount']){
			$data['amount'] = $post['donationamount'];
			$data['amountdisp'] = $post['donationamount'];
		}else{
			$data['amount'] = $post['otheramount'];
			$data['amountdisp'] = $post['otheramount'];
		}*/
		if( ($post['donationtype'] == 'recurringDO')){
			if($post['jform']['donationamount'] == ''){
					$post['amount'] = $post['jform']['otheramount'];
			}else{
					$post['amount'] = $post['jform']['donationamount'];
						}
		}else if( ($post['donationtype'] == 'recurringCO') ){
				if($post['jform']['co_donationamount'] == ''){
							$post['amount'] = $post['jform']['co_otheramount'];
						}else{
							$post['amount'] = $post['jform']['co_donationamount'];
						}		
	  }else{			
					if($post['donationamount'] == 'other'){
						$post['amount'] = $post['otheramount'];
					}else{
						$post['amount'] = $post['donationamount'];
					}
		}
		
		
		// GET DONATION MODEL
		$donation_type = $post['donationtype']; 
		$project_id = $post['project_id'];	
		$post['amount'] =  number_format( (int) $post['amount'], 2, '.', '');			
		$data['amount']	= $post['amount'];
		
		
			
		if($donation_type == 'onceoff'){
			$data['amountdisp'] = $post['amount'];			
			$Donation_model = $this->getModel('donation');
			$donationid = $Donation_model->saveOnceOffDonation($data, $post['project_id']);
			//echo "<pre> donationid =".$donationid; 
			$post['donation_history_id'] = $donationid; 			
			// GET MODEL
			$model= $this->getModel('payment');
			$order_id=$model->store($post);		
			$Itemid=0; $msg='';				
			$link=JURI::root().substr(JRoute::_('index.php?option=com_donorforce&view=payment&layout=pay&donation='.$donationid.'&order_id='.$order_id.'&donationtype='.$donation_type,false),strlen(JURI::base(true))+1);
			$mainframe->redirect($link,$msg);
		
		
		}else if( $donation_type == 'recurringCO'){
		  	
		 

			$Donation_model = $this->getModel('donation');			
			$data['donation_start_date'] = $post['jform']['co_donation_start_date'];
			$data['donation_end_date'] = $post['jform']['co_donation_end_date'];					
			$data['deduction_day'] = $post['jform']['co_deduction_day'];
			$data['frequency'] = $post['jform']['co_frequency'];			
			//echo "<pre> data in saving recurring co "; print_r($data); echo "</pre>"; exit; 
			$data['source'] = $post['gateways'];

			$data['credit_account_name'] = $post['jform']['credit_account_name'];
			$data['credit_card_number'] = $post['jform']['credit_card_number'];
			$data['credit_expiry_date'] = $post['jform']['credit_expiry_date'];
			$data['credit_card_cvv'] = $post['jform']['credit_card_cvv'];
			$data['credit_card_type'] = $post['jform']['credit_card_type'];
 




			$data['donation_type']= $donation_type;
			$data['project_id']= $project_id; 
					

//print_r($data); exit;



			$Recdonationid = $Donation_model->saveRecDonation($data, $post['project_id']);			
			//echo "<pre> Recdonationid =".$Recdonationid; 
			$post['donation_history_id'] = $Recdonationid; 			
			// GET MODEL
			$model= $this->getModel('payment');
			  
			$post['rec_donation_subscription_id'] = $Recdonationid; 
			$post['donation_history_id'] = '';  unset($post['donation_history_id']);
			
			$order_id=$model->store($post);		
			
			$Itemid=0; $msg='';		
			/*$link=JURI::root().substr(JRoute::_('index.php?option=com_donorforce&view=payment&layout=pay&Recdonationid='.$Recdonationid.'&order_id='.$order_id.'&donationtype='.$donation_type.'&Itemid='.$Itemid,false),strlen(JURI::base(true))+1);*/

			$link=JURI::root().substr(JRoute::_('index.php?option=com_donorforce&view=payment&layout=debit&Recdonationid='.$Recdonationid.'&order_id='.$order_id.'&donationtype='.$donation_type.'&Itemid='.$Itemid,false),strlen(JURI::base(true))+1);

		$mainframe->redirect($link,$msg);
			
		}else if ($donation_type == 'recurringDO'){
			
				//echo "<pre> payment save recurringDO "; print_r($post); echo "</pre>";  exit;   			
				$userid = DonorForceHelper::getLoggedin();
				$userinfo = DonorForceHelper::getFullUserInfo($userid);		
				$data['cms_user_id'] = $userid;		
				$data['donor_id'] = $userinfo->donor_id; 
								
				
					$data['bank_name'] = $post['jform']['bank_name'];
					$data['account_number'] = $post['jform']['account_number'];
					$data['account_name'] = $post['jform']['account_name'];
					$data['account_type'] = $post['jform']['account_type'];
					$data['branchcode'] = $post['jform']['branchcode'];
					$data['branch_name'] = $post['jform']['branch_name'];
					$data['comp_code'] = $post['jform']['comp_code'];
					$data['beneficiary_reference'] = $post['jform']['beneficiary_reference'];
					$data['donation_start_date'] = $post['jform']['donation_start_date'];
					$data['donation_end_date'] = $post['jform']['donation_end_date'];					
					$data['deduction_day'] = $post['jform']['deduction_day'];
					$data['frequency'] = $post['jform']['frequency'];
					
					$data['donation_type']= $donation_type;
					$data['project_id']= $project_id; 
			
					
					$Donation_model = $this->getModel('donation');
					$Recdonationid = $Donation_model->saveRecCODonation($data, $post['project_id']);
					
					//echo "<pre> Recdonationid =".$Recdonationid; 
					$post['rec_donation_debitorder_id'] = $Recdonationid;
					$post['amount']   = 	$data['amount'];	
					$post['donor_id'] = 	$data['donor_id'];			
					$post['cms_user_id'] = $data['cms_user_id'];	
					$post['gateways'] = 'noplugin';	
					
					// GET MODEL
					$model= $this->getModel('payment');
					$order_id=$model->store($post);		
					$Itemid=0; $msg='';										
					$link=JURI::root().substr(JRoute::_('index.php?option=com_donorforce&view=payment&layout=debit&Recdonationid='.$Recdonationid.'&order_id='.$order_id.'&donationtype='.$donation_type.'&Itemid='.$Itemid,false),strlen(JURI::base(true))+1);
				$mainframe->redirect($link,$msg);
			 
		}
		
		
		
		
	}
	


}
