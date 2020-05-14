<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$jinput = JFactory::getApplication()->input;
$amount = $jinput->get('amount', '0');
$project = rawurldecode($jinput->get('project',''));
//echo "<br /> project = ".$jinput->get('project',''); 
//$amount = JRequest::getVar('amount'); 



if ( strpos( $amount , "." ) == false ) {
	echo "Thank you for your donation of <b> ".DonorForceHelper::getCurrency()." ".number_format(($amount/100),2)   ."</b>  as a Recurring Debit Order towards  <b>".$project."</b>";
}else{	   
	echo "Thank you for your donation of <b>".DonorForceHelper::getCurrency()." ". $amount."</b>  as a Recurring Debit Order towards  <b>".$project."</b>";
	   
}



//-------------------------
?>
<h2 style="padding:15px;border-radius:4px; padding-left: 0px; " ><?php echo JText::_('Order Summary'); ?></h2>
<h5 class="h5_descrip">Thank you <?php echo $ordInfo->name; ?> for your gift.</h5>

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
    
    <?php if(isset($this->donation_history_data->Reference) && ($this->donation_history_data->Reference != '') ){ //print_r($this->donation_history_data);
		echo "<tr>
				<td>Reference</td>
		 		<td>".$this->donation_history_data->Reference."</td>
			  </tr>";
		} ?>
    <tr >
        <td width="150"><?php echo JText::_('DF_ORDER_PROCESSOR'); ?></td>
				<td align="left" > <?php echo $ordInfo->processor;?></td>
    </tr>
    <tr >
        <td width="150"><?php echo JText::_('DF_ORDER_IP'); ?></td>
				<td align="left" > <?php echo $ordInfo->ip_address;?></td>
    </tr>
    


</table>

