<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// no direct access
defined('_JEXEC') or die;
?>

<script type="text/javascript">
jQuery(document).ready(function(){  
  
  
  var donation_type = '<?php echo $this->donation_history_infor->donation_type;  ?>';
	var project = '<?php  echo $this->donation_history_infor->project_name;  ?>'; 
	var amount = '<?php echo $this->donation_history_infor->amount;  ?>'; 
	//console.log("project = "+project);
		  
	if(donation_type == 'recurringDO'){
		//jQuery('form').attr('action', "index.php?option=com_donorforce&view=payment&layout=thankyoudebit&amount="+amount+"&project="+project);
		//console.log("recurringDO action changed");
		//console.log("donation_type = "+donation_type);
	}	  
		  
  
  
  jQuery('form').submit(function( e ) {
			if ( jQuery('#check00').is(":checked")) 
			{
			  //$('submit').disabled = false;
			  //return true;
			}
			 else 
			 {
			  //$('submit').disabled = true;
			  alert('Please accept Terms and Conditions');
			  e.preventDefault();
			 // return false;
			}
		});
});


function popupCenter(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}
</script>


<!-- Cart -->
<?php 
//Base URL
$base=JURI::base();
$session =JFactory::getSession();
//echo "<pre> donation_history_infor = "; print_r($this->donation_history_infor); echo "</pre>";  
$params  = JComponentHelper::getParams('com_donorforce');

$jinput = JFactory::getApplication()->input;
$donationtype = $jinput->get('donationtype',''); 
if($donationtype == ''){ 
	$donation_type = $session->get('com_donorforce.donationtype');
}	
$Dtype = "Subscription";		
?>


<?php //echo "<hr /> donation_history_infor = <pre> "; print_r( $this->donation_history_infor) ; exit;  ?>

<div class="donation_cart">
<h2>Donation Cart</h2>
 <p> You have selected to donate to the following Project. By clicking the <b>Confirm</b> button you are confirming your donation and that you have read the <b>Terms and Conditions</b> for use of our website.</p>
<p>
<a onclick="popupCenter('<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$params->get('terms_articleid').'&tmpl=component'); ?>', 'myPop1',450,450);" href="javascript:void(0);" style="color:#FC6;">Terms and Conditions</a>
</p>

<table class="table table-bordered table-condensed">
  <tr>
    <th scope="col"></th>
    <th scope="col">Project Name</th>
    <th scope="col">Donation Type</th>
    <th scope="col">Frequency of Payment</th>
    <th scope="col">Amount</th>
    <!--<th scope="col">Status</th>-->
   <!-- <th scope="col">Payment Gateway</th>-->
    
  </tr>
  <tbody>
  <?php //echo "<pre> orderinfo = "; print_r($orderinfo);
  echo "<tr>";
  echo "<td><img src='$base".$this->donation_history_infor->project_image."' width='100' height='100'/></td>";
  echo "<td>"  .$this->donation_history_infor->project_name. "</td>";  
  echo "<td>"  .$Dtype. "</td>";
  echo "<td>"  .$this->donation_history_infor->donation_type. "</td>";  
  echo "<td>"  .DonorForceHelper::getCurrency()." ".$this->donation_history_infor->amount. "</td>"; 
  //echo "<td>"  .$this->donation_history_infor->status. "</td>"; 
 // echo "<td>"  .ucfirst($this->processor). "</td>";  
  echo "</tr>"
  ?>
  </tbody>
</table>
 <br />
 <div class="checkbox">
  <label><input type="checkbox" name="check00" id="check00" value="">Accept Terms and Condition</label>
</div> 
<!-- Cart End-->


<form action="<?php echo JURI::root(); ?>index.php?option=com_donorforce&task=payment.processpayment&order_id=<?php echo $jinput->get('order_id');?>" name="adminForm" id="adminForm" onsubmit="" class="form-validate form-horizontal" method="post">



<?php /*?><form action="<?php echo JURI::root(); ?>index.php?option=com_donorforce&view=payment&layout=thankyoudebit&amount=<?php echo $this->donation_history_infor->amount; ?>&project=<?php echo  rawurlencode($this->donation_history_infor->project_name); ?>" name="adminForm" id="adminForm" onsubmit="" class="form-validate form-horizontal" method="post"><?php */?>


<?php /*?><form action="<?php echo JURI::root(); ?>index.php?option=com_donorforce&view=orders&layout=order&orderid=".($order_id)."&status=C" name="adminForm" id="adminForm" onsubmit="" class="form-validate form-horizontal" method="post"><?php */?>
  <input type="hidden" name="donationtype" value="recurringDO" >
  <input type="hidden" name="processor" value="no_plugin" >
  <input type="submit" class="btn btn-success art-button" value="Confirm" style="zoom: 1;">
  
  <input type="button" value="Cancel" class="btn btn-danger art-button" onclick="window.location.href='index.php?option=com_donorforce&amp;view=projects'" style="zoom: 1;">

</form>


</div><!-- Donation cart end -->