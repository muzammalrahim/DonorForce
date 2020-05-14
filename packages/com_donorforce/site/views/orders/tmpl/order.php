<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined( '_JEXEC' ) or die( ';)' );
/*$post = JRequest::get('post');
print"<pre>"; print_r($post);
//// for getting current tab status one page chkout::
$session =JFactory::getSession();
$ses_var = $session->get('processpayment');
var_dump($ses_var);*/

$db = JFactory::getDbo();
?>


<?php
	 if(!empty($this->orderinfo))
	 {
		 $ordInfo=$this->orderinfo;

//echo "<pre>"; print_r($ordInfo); exit; 
$donation_history_id = $ordInfo->donation_history_id;
//echo " <pre>   ordInfo  ";  print_r( $ordInfo ); echo " </pre> ";   
//$com_donorforce = JComponentHelper::getParams('com_donorforce') ;
//echo " <pre> com_donorforce   ";  print_r( $com_donorforce ); echo " </pre> ";  
$donation_type =  ''; 

	if(!empty( $ordInfo->donation_history_id) && $ordInfo->donation_history_id > 0 ){
		$donation_history_id = $ordInfo->donation_history_id;
	
		$query = "select project_id, donor_id from #__donorforce_history WHERE `donor_history_id` = '".$donation_history_id."' LIMIT 1";
		$db->setQuery($query);
		$item= $db->loadObject();

		$donation_type = 'Special Gift (Debit Card, Credit Card)'; 
		$mess_status = JComponentHelper::getParams('com_donorforce')->get('onceoff_status'); 
		if($mess_status){ 
			echo JComponentHelper::getParams('com_donorforce')->get('onceoff_message'); 
		}
	}else if(!empty( $ordInfo->rec_donation_subscription_id) && $ordInfo->rec_donation_subscription_id > 0 ){		
		$donation_type = 'Recurring Donation (Credit Card Subscription)'; 
		$mess_status = JComponentHelper::getParams('com_donorforce')->get('credit_card_subscription_status'); 
		if($mess_status){ 
			echo JComponentHelper::getParams('com_donorforce')->get('recurring_donation_credit_order_message'); 
		} 
		
	}else if(!empty( $ordInfo->rec_donation_debitorder_id) && $ordInfo->rec_donation_debitorder_id > 0 ){
		$donation_type = 'Recurring Donation (Recurring Debit Subscription)'; 
		$mess_status = JComponentHelper::getParams('com_donorforce')->get('recurring_debit_subscription_status'); 
		if($mess_status){
			echo JComponentHelper::getParams('com_donorforce')->get('recurring_donation_debit_order_message'); 
		}
	}
?>

  

<?php  if(isset($this->Plugin_message) && ($this->Plugin_message != '') ) { 
			echo  $this->Plugin_message;  
		}
?>
<table width="400" border="3" cellpadding="5" cellspacing="2">
    <tr  >
        <td width="150"><?php echo JText::_('DF_ORDER_ORDERID'); ?></td>
				<td align="left" > <?php echo $ordInfo->id;?></td>
		</tr>
    <tr >
        <td width="150"><?php echo JText::_('DF_ORDER_USER_ID'); ?></td>
				<td align="left" > <?php echo $ordInfo->user_info_id;?></td>
    </tr>
    <tr >
        <td width="150"><?php echo JText::_('DF_ORDER_NAME'); ?></td>
				<td align="left" > <?php echo $ordInfo->name;?></td>
    </tr>
    <tr >
        <td width="150"><?php echo JText::_('DF_ORDER_EMAIL'); ?></td>
				<td align="left" > <?php echo $ordInfo->email;?></td>
    </tr>
    <tr >
        <td width="150"><?php echo JText::_('DF_ORDER_CDATE'); ?></td>
				<td align="left" > <?php echo $ordInfo->cdate;?></td>
    </tr>
    
    <tr >
        <td width="150"><?php echo JText::_('DF_ORDER_AMOUNT'); ?></td>
				<td align="left" > <?php echo $ordInfo->amount;?></td>
    </tr>
    <tr >
        <td width="150">Project ID</td>
				<td align="left" > <?php echo 'P'.str_pad($item->project_id, 5, '0', STR_PAD_LEFT);?></td>
    </tr>
    
    <tr >
        <td width="150">Donor ID</td>
				<td align="left" > <?php echo 'D'.str_pad($item->donor_id, 5, '0', STR_PAD_LEFT);?></td>
    </tr>
    <?php  if(isset($ordInfo->status) && ($ordInfo->status != '')){ ?>
    <tr >
     <td width="150"><?php echo JText::_('DF_ORDER_STAUS'); ?></td>
				<td align="left" > <?php  
					if($ordInfo->status == 'C'){ echo "Completed"; }
					else if($ordInfo->status == 'P') { echo "Pending"; } 
					else if($ordInfo->status == 'D') { echo "Denied"; } 
					else if($ordInfo->status == 'E') { echo "Failed"; } 
					else{ $ordInfo->status;  }
					?> 
        </td>
    </tr>
    <?php } ?>
    <?php if(isset($this->donation_history_data->Reference) && ($this->donation_history_data->Reference != '') ){ //print_r($this->donation_history_data);
		echo "<tr>
				<td>Reference</td>
		 		<td>".$this->donation_history_data->Reference."</td>
			  </tr>";
		} ?>
   
   <?php if(isset($ordInfo->processor) && ($ordInfo->processor != '' ) && ($ordInfo->processor != 'noplugin') ) { ?>
    <tr> 
        <td width="150"><?php echo JText::_('DF_ORDER_PROCESSOR'); ?></td>
				<td align="left" > <?php echo $ordInfo->processor;?></td>
    </tr>
   <?php } ?>
    <tr >
        <td width="150"><?php echo JText::_('DF_ORDER_IP'); ?></td>
				<td align="left" > <?php echo $ordInfo->ip_address;?></td>
    </tr>
    


</table>
<?php
}
else
{
	echo JText::_('COM_DONORFORCE_ORDERDETAIL_NOT_FOUND'); 
}
?>


