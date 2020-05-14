<script type="text/javascript">
 
 // stop the code executing 
// until the page is loaded in the browser
window.addEvent('load', function() {
 // function to enable and disable the submit button
  function agree() {
    if ( $('check00').checked == true ) {
      $('submit').disabled = false;
    } else {
      $('submit').disabled = true;
    }
  };
  // disable the submit button on load
  $('submit').disabled = true;
  //execute the function when the checkbox is clicked
  $('check00').addEvent('click', agree);
});
</script>
<script>
function popupCenter(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}
</script>


<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @version $Id: com_donorforce.php 599 2015-04-20 23:26:33Z brent $
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
$session = JFactory::getSession();


//Parameters
$params  = JComponentHelper::getParams('com_donorforce');

//Donation Type
$donation_type = $session->get('com_donorforce.donationtype');

//Project Details
$p=DonorForceHelper::getProject($session->get('com_donorforce.project_id'));

//Amount
$AMOUNT = $this->data['amount'];
$frequency=$this->data['frequency'];
$donation_start_date=$this->data['donation_start_date'];
$donation_end_date=$this->data['donation_end_date'];
$donation = DonorForceHelper::getCurrency().'   '. DonorForceHelper::displayAmount($AMOUNT);

//Base URL
$base=JURI::base();


if($donation_type 	== 'recurringDO')
{
	$type = "Subscription";	
}
 else 
 
 {
	$type = "Single Payment";	
}

 ?>
<form action="<?php echo 'index.php?option=com_donorforce&view=payment&layout=thankyoudebit';?>" method="POST" id="paymentform" >

<p>You have selected to donate to the following project. By clicking the <b>Confirm</b> button you are confirming your donation and that you have read the <b>Terms and Conditions</b> for use of our website. 
<br />

</p>
<p><a onclick="popupCenter('https://www.paygate.co.za/tac.php#.VV8HskbXu1o', 'myPop1',450,450);" href="javascript:void(0);" style="color:#FC6;">Terms and Conditions</a></p>

<table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <th scope="col"></th>
    <th scope="col">Project Name</th>
    <th scope="col">Donation Type</th>
    <th scope="col">Donation Start Date</th>
    <th scope="col">Donation End Date</th>
    <th scope="col">Frequency of Payment</th>
    <th scope="col">Amount</th>
  </tr>
  <tbody>
  <?php
  echo "<tr>";
  echo "<td><img src='$base".$p->image."' width='100' height='100'/></td>";
  //echo "<td><img src='$base/Images" .$p->image. </td>";
 echo  "<td>" .$p->name. "</td>";
  echo "<td>"  .$type. "</td>";
  echo "<td>"  .date("D, j F Y g: i A ", strtotime($donation_start_date)); "</td>";
  echo "<td>"  .date("D, j F Y g: i A ", strtotime($donation_end_date));   "</td>";
 	echo "<td>"   .$frequency. "</td>";
	 echo "<td>" .$donation.  "</td>";
  
  echo "</tr>"
  ?>
  </tbody>
</table>
<br />
  <div class="checkbox">
  <label><input type="checkbox" name="check00" id="check00">Accept Terms and Condition</label>
</div>
   
 	<input type="button" value="Cancel" onclick="window.location.href='index.php?option=com_donorforce&view=projects'"  style="float:right;" class="btn btn-danger">
    
    
     <input type="submit" id="submit" value="Confirm" style="float:right; margin-right: 5px;" class="btn btn-success">
    
    
    
    <input type="hidden" name="AMOUNT" value="<?php echo DonorForceHelper::displayAmount($this->data['amountdisp']); ?>" >
<input type="hidden" name="PNAME" value="<?php echo $PNAME; ?>">
  
</form>






