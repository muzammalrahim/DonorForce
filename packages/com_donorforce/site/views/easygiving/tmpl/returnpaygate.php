<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
//$items = $this->items;
$params  = JComponentHelper::getParams('com_donorforce'); 


if(!empty($this->paygate_response) && $this->paygate_response != ''){ ?>
<div class="paygatereturn">
  <?php  	 
		//echo " <pre> this->paygate_response = ";  print_r(  $this->paygate_response ); echo " </pre> ";   	 
	?>
  
  <h2 style="padding:15px;border-radius:4px; padding-left: 0px; " >Summary</h2>
	<h5 class="h5_descrip">Thank you <?php echo $this->paygate_response['name']; ?> for your Donation.</h5>

  
  <table width="400" border="3" cellpadding="5" cellspacing="2">
    
    
    <tr>
    		<td width="150">Name</td>
				<td align="left" > <?php echo $this->paygate_response['name'];?></td>
    </tr>
     
    <tr>
    		<td width="150">Email</td>
				<td align="left" > <?php echo $this->paygate_response['email'];?></td>
    </tr>
    
    <tr>
        <td width="150">Status</td>
        <td align="left"><?php echo $this->paygate_response['status'];?></td>
    </tr>
    
    <tr>
        <td width="150">Donation Type</td>
        <td align="left"><?php echo $this->paygate_response['donation_type'];?></td>
    </tr>
    
    
    <tr >
        <td width="150">Amount</td>
				<td align="left"><?php echo $this->paygate_response['amount'];?></td>
    </tr>
    
    <tr>
				<td>Reference</td>
		 		<td align="left"><?php echo $this->paygate_response['REFERENCE'];?></td>
		</tr>
        
    <tr> 
        <td width="150">Processor</td>
				<td align="left">PayGate</td>
    </tr>
    

</table>
  
  
</div>
 
<?php 
}else{
		echo JText::_('COM_DONORFORCE_ORDERDETAIL_NOT_FOUND'); 	 
}?>
 
<style type="text/css">
.paygatereturn table td{ padding: 5px;  }
</style>