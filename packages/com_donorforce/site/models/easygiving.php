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

class DonorforceModelEasyGiving extends JModelList
{



public function getForm($data = array(), $loadData = true) {
		
	/*	$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_donorforce.donation', 'donor', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) {
			return false;
		}
		return $form;*/
	}
protected function loadFormData()
{/*
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_donorforce.default.donation.data', array());
	if (empty($data)) {
		//$data = $this->getItem();
	}
	return $data;
*/}
	
	
	function getProjectList(){

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__donorforce_project'));
		$query->where($db->quoteName('published').' = 1');
		$query->order('ordering ASC');
		$db->setQuery($query);
		return $results = $db->loadObjectList();
		

			
	}
	
	
	function getUserFromEmail($email){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true); 
			$query->select($db->quoteName('id')); 
			$query->from($db->quoteName('#__users'));
			$query->where($db->quoteName('email') . ' = ' . $db->quote($email));
			$db->setQuery($query, 0, 1);
			$data = $db->loadResult();
			
			//echo " <pre>   data  ";  print_r( $data  ); echo " </pre> ";   
			
			if( !empty($data)){
					$data = DonorForceHelper::getFullUserInfo( $data );	
			}
			
			//echo " <pre>  data   ";  print_r( $data ); echo " </pre> ";   
			
			return $data; 
		
	}


	function insertEasyDonation($data){
		
			//echo " <pre>  insertEasyDonation  data = ";  print_r( $data ); echo " </pre> ";    	
			//echo " <br />    data[status] = ".$data['status']; 		
					
			$db = JFactory::getDbo(); 
			$query = $db->getQuery(true);
			$columns = array(
									'donor_id', 
									'project_id', 
									'cms_user_id', 
									'date',
									'amount',
									'status',
									'donation_type',
									'Reference' );
			
			$values = array(
									$db->quote($data['donor_id']),
									$db->quote($data['project_id']),
									$db->quote($data['cms_user_id']),
									$db->quote(date('Y-m-d H:i')),
									$db->quote($data['amount']),
									$db->quote($data['status']),
									"'onceoff'",
									$db->quote($data['Reference'])
									);
			$query->insert($db->quoteName('#__donorforce_history')); 
			$query->columns($db->quoteName($columns)); 
			$query->values(implode(',', $values)); 
			$db->setQuery($query);
			$db->execute();
		 	$history_id = $db->insertid(); 
		 
			// echo " <pre>  dumpQuery  ";  print_r(    $query->dump() ); echo " </pre> ";   exit; 		
		 	// echo " <pre>   history_id  ";  print_r( $history_id ); echo " </pre> ";   exit; 
			if( $history_id > 0 ){				
					$params = JComponentHelper::getParams('com_donorforce');
	 				$OrderQuery = $db->getQuery(true);
					$OrderColumns = array(
												'user_info_id', 
												'name', 
												'email', 
												'cdate',
												'original_amount',
												'amount',
												'processor',
												'currency',
												'donation_history_id',
												'extra'
						);
						
						$OrderValues = array(
												$db->quote($data['cms_user_id']),
												$db->quote($data['name']),
												$db->quote($data['email']),
												$db->quote(date('Y-m-d H:i')),
												$db->quote($data['amount']),
												$db->quote($data['amount']),
												"'paygate'",
												$db->quote($params->get("addcurrency","USD")), 
												$db->quote($db->insertid()),
												$db->quote('PAY_REQUEST_ID='.$data['PAY_REQUEST_ID'])
						);
						
						$OrderQuery->insert($db->quoteName('#__donorforce_orders')); 
						$OrderQuery->columns($db->quoteName($OrderColumns)); 
						$OrderQuery->values(implode(',', $OrderValues)); 
						$db->setQuery($OrderQuery);
						$db->execute();
			}
			
			return $history_id; 

	}


	function returnPaygate($post){
			 
			  
			/* 	$post['option'] = 'com_donorforce';
				$post['task'] = 'returnPaygate';
				$post['reference'] = 'eg_testing123_1490082589';
				$post['PAY_REQUEST_ID'] = 'F66A41C1-02C7-B83D-9F15-97D90265F721';
				$post['TRANSACTION_STATUS'] = '1';
				$post['CHECKSUM'] = 'c89ce0846560ff4550d3f83e06d6f4f9'; 
    	 */
			 
			   // echo " <pre> returnPaygate  post =  ";  print_r(  $post ); echo " </pre> ";   // exit; 
				
				
					require_once JPATH_COMPONENT."/assets/payweb3/paygate.payweb3.php";				
					if(JPluginHelper::isEnabled('payment', 'paygate')){
							$paygate = 	JPluginHelper::getPlugin('payment', 'paygate');
							$paygate->params = json_decode(	$paygate->params,	true	);
					}
					 
					$data = array(
						'PAYGATE_ID'     => $paygate->params['paygate_id'],// $post['PAYGATE_ID'],
						'PAY_REQUEST_ID' => $post['PAY_REQUEST_ID'],
						'REFERENCE'      => $post['reference']
					);
			
				 //echo " <pre>   data  ";  print_r( $data  ); echo " </pre> ";    
				
					$encryption_key = $paygate->params['secret'];
					$PayWeb3 = new PayGate_PayWeb3();
					$PayWeb3->setEncryptionKey($encryption_key);
					$PayWeb3->setQueryRequest($data);
					$returnData = $PayWeb3->doQuery();
				 	//echo " <pre>  returnData  ";  print_r(  $returnData ); echo " </pre> ";  exit;  
					 
					if($returnData){
						$result_data = $PayWeb3->queryResponse; 
						if( !empty($result_data)){
							//	echo " <pre> result_data   ";  print_r( $result_data ); echo " </pre> ";   
								
								$name = $result_data['USER1'];
								$email = $result_data['USER2'];
								
								 
								$user_info  = $this->getUserFromEmail($email);
								// echo " <pre>  user_info  ";  print_r( $user_info ); echo " </pre> "; exit;   
								
								$result_data['project_id'] = (!empty($result_data['USER3']))? $result_data['USER3'] : '' ;
								$result_data['Reference'] = (!empty($result_data['REFERENCE']))? $result_data['REFERENCE'] : '' ;
								$result_data['amount'] = (!empty($result_data['AMOUNT']))? $result_data['AMOUNT'] : '' ;
								$result_data['donation_type'] =  'onceoff' ;
								$result_data['status'] = ($result_data['TRANSACTION_STATUS'] == '1')? 'successful' : 'pending' ;								
								$result_data['donor_id'] = $insert_Data['cms_user_id'] = '';
							 	$result_data['name'] = $result_data['USER1'];
								$result_data['email'] = $result_data['USER2']; 
								$result_data['amount'] = $result_data['AMOUNT']/100; 
								 	
								if(!empty($user_info) && $user_info->donor_id != '' ){
									$result_data['user_info']  = $user_info;
								}
								
								//$history_id = $easyGivingModel->insertEasyDonation($insert_Data);
								//if($history_id){
									//	$insert_Data['history_id'] = $history_id; 
										// $easyGivingModel->sendThankYou($insert_Data);
								// }
								
								
								//  echo " <pre>   result_data  ";  print_r( $result_data ); echo " </pre> ";   
								  return $result_data; 
						 

						 
								

								
						}
						
					}
							
					
				return ''; 
				
				
				
			}	

			function returnPayfast($post){
			 
			  
				
				//echo " <pre>  post ";  print_r( $post ); echo " </pre> "; 
				 	
					// require_once JPATH_COMPONENT."/assets/payweb3/paygate.payweb3.php";				
					if(JPluginHelper::isEnabled('payment', 'payfast')){
							$payfast = 	JPluginHelper::getPlugin('payment', 'payfast');
							$payfast->params = json_decode(	$payfast->params,	true	);
					}
					 
					 
					if($post['reference']){
							//	echo " <pre> result_data   ";  print_r( $result_data ); echo " </pre> ";   
								
								$result_data = ''; 
								$db = JFactory::getDbo();
								$query = $db->getQuery(true);
								$query->select('*');
								$query->from($db->quoteName('#__donorforce_history'));
							 
								$query->where($db->quoteName('Reference').	'='. $db->quote($post['reference']));
							 
								$db->setQuery($query);
								$result_data = $db->loadAssoc();
							
							// echo " <pre>  result_data    ";  print_r( $result_data  ); echo " </pre> ";   
								
								
								
								
								$result_data['project_id'] = (!empty($result_data['project_id']))? $result_data['project_id'] : '' ;
								$result_data['Reference'] = (!empty($result_data['Reference']))? $result_data['Reference'] : '' ;
								$result_data['amount'] = (!empty($result_data['amount']))? $result_data['amount'] : '' ;
								$result_data['donation_type'] =  'onceoff' ;
								$result_data['status'] = ($result_data['payment_status'] == '1')? 'successful' : 'pending' ;								
								$result_data['donor_id'] = $insert_Data['cms_user_id'] = '';
							 	
								$name_parts = explode('_',$post['reference'] ); 
								
								
								$result_data['name'] = $name_parts['1']."  ".$name_parts['2'];;
								$result_data['email'] = $post['email']; 
								$user_info  = $this->getUserFromEmail($result_data['email']);
								
								 	
								if(!empty($user_info) && $user_info->donor_id != '' ){
									$result_data['user_info']  = $user_info;
								}
								
								//$history_id = $easyGivingModel->insertEasyDonation($insert_Data);
								//if($history_id){
									//	$insert_Data['history_id'] = $history_id; 
										// $easyGivingModel->sendThankYou($insert_Data);
								// }
								
								 
								//  echo " <pre>   result_data  ";  print_r( $result_data ); echo " </pre> ";   
								  return $result_data; 
					}
							
					
				return ''; 
			}




	function sendThankYou($data){
		
		//echo " <pre> sendThankYou  data ";  print_r( $data ); echo " </pre> ";   exit; 
		/* 
		jimport('joomla.application.component.model');
		JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_donorforce/models');
		$paymentModel = JModelLegacy::getInstance( 'Payment', 'DonorforceModel' ); 
		$paymentModel->sendemail();
		*/
				
		 jimport('joomla.application.component.helper');
			if(JComponentHelper::getParams('com_donorforce')->get('send_thankyou') == 0){
				return false;	
		 }
					
			require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';
			$item = DonorForceHelper::getPdfTemplate(); 
			$don  = DonorForceHelper::getLatetsDonationByID($data['history_id']); 
			
			//check if donor not exist then use its name enter during easygiving donation 
			$don->name_first = ($don->name_first == '' && $don->name_last == '')?  $data['name'] : $don->name_first ; 
		  /*-- Tax changes --*/
			if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
					DonorforceHelper::sendTaxReceiptPDF($don); 
			}
			/*-- Tax changes end --*/
			
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
			
			
			
			$html = '<html>
			<style>
			@page {	}
			body {	margin-top: -20px;}
			</style>
			<body>';	
			$html.='<table width="100%"><tr><td  valign="top">';	
			if(!empty($item->head_logo)) {$html.='<img style="min-height:50px;" src="'.JPATH_ROOT.'/'.$item->head_logo .'" name="" />';}
			else{	
				$html.="Logo";
			}
			$html.=' </td><td align="right">';
			if(!empty($item->head_addresses)) { $html.= $item->head_addresses;
			}else{  $html.= "Addresses will be Here";}
			$html .= '</td> </tr>';
			$html .='<tr>
			<td>&nbsp;</td>
			<td align="right"> <h3>';
			if(!empty($item->main_title)) { $html.= $item->main_title;}
			else{  $html.= "Thank You";}
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
			$html .= '</td><td>&nbsp;</td></tr>';
			$html .= '
			<tr>
			<td colspan="2" align="left" style="border-top:1px solid #999">
			Dear '.$don->name_first.' '.$don->name_last.'<br><br>
			Thank you for the donation of '.DonorForceHelper::getCurrency().' '.($don->amount).' to our project '.$don->name.' Below are the details of your donation.
			</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
			<tr>
			<td>'; 
			
			
			$html .='</td><td>&nbsp;</td></tr>';
			// Body Content will go here .  
			if(!empty($item->thankyou_body))
			{
				$html .=$item->thankyou_body; 	
			}
			// Body Content end here . 
			$html .='<tr><td colspan="2">';
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
			if(!empty($item->footer_slogan)) { $html.= $item->footer_slogan;}
			 else
			 $html.= "Footer Slogan Text will be Here";
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
			$html = str_replace('{donation_type}',$don->donation_type, $html);
			$dompdf->load_html($html);
			$dompdf->render();
			
			$output = $dompdf->output();
			file_put_contents(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf", $output);
			/**-------------------Send Email--------------------------------**/
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$mailer->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
			$params->get('admin_name',$app->getCfg('fromname'))));
			$mailer->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));
			$recipient = ($don->email != '')? $don->email  : $data['email'];
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
			return true; 
			/*
			if ( $send !== true || $send2 !== true ) {
					//echo 'Error sending email: ';
			} else {
					//echo 'Mail sent';
			}*/
			/**---------------------------------------------------------**/ 
			
	}


}