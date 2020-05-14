<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class DonorforceViewPayment extends JViewLegacy {

    /**
     * Display the view
     */ 
    public function display($tpl = null) { 	
			$jinput=JFactory::getApplication()->input;
      $layout=$jinput->get("layout",'default');      
			$user		= JFactory::getUser();
			$params = JComponentHelper::getParams('com_donorforce');
	
			
			if($layout=="default")
			{
				//START :: getting payment gateway data
				$dispatcher = JDispatcher::getInstance();
				JPluginHelper::importPlugin('payment'); 
				if(!is_array($params->get( 'gateways' )) ){
					$gateway_param[] = $params->get( 'gateways' );
				}
				else{
					$gateway_param = $params->get( 'gateways' ); 	
				}
				if(!empty($gateway_param))
					$gateways = $dispatcher->trigger('onTP_GetInfo',array($gateway_param));
				$this->gateways = $gateways;
				//Checking recurring payment				
				if( $this->donationtype == 'recurringCO'){ 
					foreach( $this->gateways as $key => $gat ){
						$recurring_path = JPATH_PLUGINS.'/payment/'.$gat->id.'/'.$gat->id.'/tmpl/recurring.php'; 					  
						 if(!file_exists($recurring_path)){
								unset($this->gateways[$key]);
							}
					}						
				}			
			}else
			{
					// getting order id
					$order_id=$jinput->get("order_id",'');					
					$jinput = JFactory::getApplication()->input;
					$donation_type = $jinput->get('donationtype');
					$this->assignRef( 'donation_type', $donation_type );
					 													
					if(!empty($order_id))
					{
						$model= $this->getModel('payment');					
						// GETTING ORDER INFO
						$orderinfo=$model->getOrderInfo($order_id);					
						$this->processor=$orderinfo->processor;
						
						// xeshan changes 
						if($donation_type == 'recurringCO' && false){   								
								$Recdonationid=$jinput->get("Recdonationid",'');	 
								$model= $this->getModel('payment');	 			
								$donation_history_infor = $model->getRecDonationInfo($Recdonationid);	
								$this->payhtml = $model->getHTML($orderinfo->processor,$order_id,$donation_type,$donation_history_infor);
						}else if($donation_type == 'onceoff'){	
								// GETTING USER PAYMENT HTML
								$donation_history_id2=$jinput->get("donation",'');					
								$paymentmodel= $this->getModel('payment');					
								$donation_history_infor2 = $paymentmodel->getDonationHistoryInfo($donation_history_id2);						
								$this->payhtml = $model->getHTML($orderinfo->processor,$order_id,$donation_type,$donation_history_infor2);
						}
					}				
					if($layout == 'pay' || $layout == 'debit'){
						
						
						// GETTING Donation INFO if layout is pay
						//$this->processor=$session->get('com_donorforce.donationtype');	
						if($donation_type == 'onceoff'){
							
						$donation_history_id=$jinput->get("donation",'');					
						$model= $this->getModel('payment');					
						$donation_history_infor = $model->getDonationHistoryInfo($donation_history_id);
						$this->assignRef( 'donation_history_infor', $donation_history_infor );
							
						}else if($donation_type == 'recurringCO' || $donation_type == 'recurringDO'  ){
							
							//echo "<hr /> recurringCO  recurringDO = "; 
							
						$Recdonationid=$jinput->get("Recdonationid",'');	 
						//echo "<br /> Recdonationid = ".$Recdonationid; 				
						$model= $this->getModel('payment');	 			
						$donation_history_infor = $model->getRecDonationInfo($Recdonationid);						
						//echo "<br /> donation_history_infor = <pre>"; print_r( $donation_history_infor ); echo "</pre>"; //exit;  						
						//echo "<pre> <hr /> this->payhtml ";  echo htmlspecialchars($this->payhtml[0]); echo "<hr /> </pre>"; 
						
						$this->assignRef( 'donation_history_infor', $donation_history_infor );
							
					 
							
						}
						
						
					}
					
			}
			
			parent::display($tpl);

    }

    
}
