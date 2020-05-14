<?php
/**
 *  @package    Quick2Cart
 *  @copyright  Copyright (c) 2009-2013 TechJoomla. All rights reserved.
 *  @license    GNU General Public License version 2, or later
 */
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.view');


class DonorforceViewOrders extends JViewLegacy
{

	function display($tpl = null)
	{
		$jinput=JFactory::getApplication()->input;
		$layout=$jinput->get("layout",'order');     		
		$app = JFactory::getApplication();
  		$session = JFactory::getSession();	
		$post =   JRequest::get('post');
		$status = $jinput->get("status"); 
		
	if($session->get('com_donorforce.donationtype') == 'onceoff'){
		$donation_model = JModelLegacy::getInstance('Donation', 'DonorforceModel'); 
		$donation_history_data = $donation_model->get_donation_history($session->get('donation_history_id')); 
		$this->assignRef( 'donation_history_data', $donation_history_data );
	}
		
	if( $status == "C"   ){
		$donation_model = JModelLegacy::getInstance('Donation', 'DonorforceModel'); 
		//echo " <pre> donation_model   = "; print_r( $donation_model ); 
		//$onceoffresult  = $donation_model->saveOnceOffNotity_2($data);
	}		
	if($layout=="order")
	{
		$tjcpgHelper=new tjcpgHelper;
		$order_id=$jinput->get("orderid",'');



		if(!empty($order_id)){ 

			$this->orderinfo = $tjcpgHelper->getOrderInfo($order_id); 
			

			if($this->orderinfo->processor == 'eft'){   				
				$eft_plugin = JPluginHelper::getPlugin('payment', 'eft');
				$eft_params = new JRegistry($eft_plugin->params);
				$eft_message = $eft_params->get('plugin_message','');
				//$this->orderinfo->eft_message =  $eft_message; 
				//$this->eft_message =  $eft_message ;
				
				$message_html = "<h5 class='h5_descrip'>".$eft_message."</h5>";
				
				$this->Plugin_message = $message_html ;
			 }
			 else if($this->orderinfo->processor == 'snapscan'){
				 
				$snapscan_plugin = JPluginHelper::getPlugin('payment', 'snapscan');
				$snapscan_params = new JRegistry($snapscan_plugin->params);
				$snapscan_message = $snapscan_params->get('plugin_message','');
				$snapscan_image = $snapscan_params->get('Snapscan','');
				$snapscan_image = JURI::base().$snapscan_image; 
				
				//echo "snapscan_image = ".$snapscan_image; 
				
				//$this->orderinfo->eft_message =  $eft_message; 
				//echo "<h5 class='h5_descrip'>". $this->Plugin_message."</h5>";  
				$message_html = "<h5 class='h5_descrip'>".$snapscan_message."</h5>"; 
				
				$message_html = $message_html.'<div><img src="'.$snapscan_image.'"></div><br />' ; 
				
				$this->Plugin_message =  $message_html ;
					
			}
			else if(!empty($this->orderinfo->donation_history_id)){  

				$donation_model = JModelLegacy::getInstance('Donation', 'DonorforceModel');
				$donation_history_data = $donation_model->get_donation_history( $this->orderinfo->donation_history_id  ); 


				if(($donation_history_data->donation_type =='onceoff')  && 
					(JComponentHelper::getParams('com_donorforce')->get('onceoff_status',0)) ){
 
					$message_html = "<h5 class='h5_descrip'>".(JComponentHelper::getParams('com_donorforce')->get('onceoff_message',''))."</h5>";
				//echo "<pre>"; 	print_r($donation_history_data);print_r($message_html); exit; 
					$this->Plugin_message =  $message_html;
				}
			
				/*
				if(($donation_history_data->donation_type =='recurringDO')  && 
					(JComponentHelper::getParams('com_donorforce')->get('recurring_debit_subscription_status',0)) ){
 
					$message_html = "<h5 class='h5_descrip'>".(JComponentHelper::getParams('com_donorforce')->get('recurring_donation_debit_order_message',''))."</h5>";
					$this->Plugin_message =  $message_html;
				}
				if(($donation_history_data->donation_type =='recurring')  && 
					(JComponentHelper::getParams('com_donorforce')->get('credit_card_subscription_status',0)) ){
 
					$message_html = "<h5 class='h5_descrip'>".(JComponentHelper::getParams('com_donorforce')->get('recurring_donation_credit_order_message',''))."</h5>";
					$this->Plugin_message =  $message_html;
				}
				*/

 
			 }
			 else if(!empty($this->orderinfo->rec_donation_debitorder_id)){  



				$donation_model = JModelLegacy::getInstance('Donation', 'DonorforceModel');
				$subscrition_history_data = $donation_model->get_rec_donation_subscription( $this->orderinfo->rec_donation_debitorder_id  ); 

				if(($subscrition_history_data->donation_type =='recurringDO')  && 
					(JComponentHelper::getParams('com_donorforce')->get('recurring_debit_subscription_status',0)) ){
 
					$message_html = "<h5 class='h5_descrip'>".(JComponentHelper::getParams('com_donorforce')->get('recurring_donation_debit_order_message',''))."</h5>";
					$this->Plugin_message =  $message_html;
				}
 
			 }

			 else if(!empty($this->orderinfo->rec_donation_subscription_id)){  



				$donation_model = JModelLegacy::getInstance('Donation', 'DonorforceModel');
				$subscrition_history_data = $donation_model->get_rec_donation_subscription( $this->orderinfo->rec_donation_subscription_id  ); 

				if(($subscrition_history_data->donation_type =='recurringCO')  && 
					(JComponentHelper::getParams('com_donorforce')->get('credit_card_subscription_status',0)) ){
 
					$message_html = "<h5 class='h5_descrip'>".(JComponentHelper::getParams('com_donorforce')->get('recurring_donation_credit_order_message',''))."</h5>";
					$this->Plugin_message =  $message_html;
				}
 
			 }
			 
		}
		else{ echo JText::_('COM_DONORFORCE_ILLEGAL_ORDERID'); }
	}
			
	parent::display($tpl);		
	}//function display ends here
	
	
	
}// class
