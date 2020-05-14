<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
$session = JFactory::getSession();
	
$p=DonorForceHelper::getProject($session->get('com_donorforce.project_id'));


$VERSION = '21';
$PAYGATE_ID = $this->paymentconfig['paygate_id'];
$REFERENCE = 'CUST-'.$this->data['cms_user_id'].'-'.$this->data['donationid'];

$AMOUNT = $this->data['amount'];

$CURRENCY = $this->paymentconfig['currency'];
if($CURRENCY =='R'){
	$CURRENCY = 'ZAR';
	}

$RETURN_URL = $this->paymentconfig['notify_url'];
$TRANSACTION_DATE = date('Y-m-d H:i');
$EMAIL = $this->data['email'];
$KEY =  $this->paymentconfig['secret'];

$HOST = $this->paymentconfig['host'];
//print_r($this->paymentconfig['hostsubs']); exit;
if(isset($this->data['cc'])){
	 $HOST = $this->paymentconfig['hostsubs'];	
}
 ?>
<form action="<?php echo $HOST;?>" method="POST" id="paymentform" >

<?php if(isset($this->data['cc'])) { ?>
<input type="hidden" name="VERSION" value="<?php echo $VERSION; ?>">
<?php } ?> 
<input type="hidden" name="PAYGATE_ID" value="<?php echo $PAYGATE_ID ?>">
<input type="hidden" name="REFERENCE" value="<?php echo $REFERENCE;?>">
<input type="hidden" name="AMOUNT" value="<?php echo $AMOUNT;?>">
<input type="hidden" name="CURRENCY" value="<?php echo $CURRENCY; ?>">
<input type="hidden" name="RETURN_URL" value="<?php echo $RETURN_URL;?>">
<input type="hidden" name="TRANSACTION_DATE" value="<?php echo $TRANSACTION_DATE; ?>">
<input type="hidden" name="EMAIL" value="<?php echo $EMAIL; ?>">  
<?php 	 if(isset($this->data['cc'])) {

$SUBS_START_DATE = $this->data['donation_start_date'];
$SUBS_END_DATE = $this->data['donation_end_date'];
$SUBS_FREQUENCY = '228';
$PROCESS_NOW = 'YES';
$PROCESS_NOW_AMOUNT = $AMOUNT;
?>
<input type="hidden" name="SUBS_START_DATE" value="<?php echo $SUBS_START_DATE; ?>">
<input type="hidden" name="SUBS_END_DATE" value="<?php echo $SUBS_END_DATE; ?>">
<input type="hidden" name="SUBS_FREQUENCY" value="<?php echo $SUBS_FREQUENCY; ?>">
<input type="hidden" name="PROCESS_NOW" value="<?php echo $PROCESS_NOW; ?>">
<input type="hidden" name="PROCESS_NOW_AMOUNT" value="<?php echo $PROCESS_NOW_AMOUNT; ?>">
<input type="hidden" name="CHECKSUM" value="<?php echo  md5("$VERSION|$PAYGATE_ID|$REFERENCE|$AMOUNT|$CURRENCY|$RETURN_URL|$TRANSACTION_DATE|$EMAIL|$SUBS_START_DATE|$SUBS_END_DATE|$SUBS_FREQUENCY|$PROCESS_NOW|$PROCESS_NOW_AMOUNT|$KEY"); ?>">
 
<?php } else { ?>
  <input type="hidden" name="CHECKSUM" value="<?php echo md5( "$PAYGATE_ID|$REFERENCE|$AMOUNT|$CURRENCY|$RETURN_URL|$TRANSACTION_DATE|$EMAIL|$KEY"); ?>">
 <?php } //end if CC ?> 
 <p> You have selected to donate <b><?php echo DonorForceHelper::getCurrency().'   '. DonorForceHelper::displayAmount($this->data['amountdisp']); ?></b> to the <b><?php echo $p->name ?></b> as a special gift;
By clicking the “confirm button” you are confirming your donation and that you have read the terms and conditions for use of our website. 
<br />

</p>

  <input type="submit" value="Confirm">
  <input type="button" value="Cancel" onclick="window.location.href='index.php?option=com_donorforce&view=projects'" />
</form>

<?php /* print 'Redirecting to the Payment page, please wait...'; ?><br>
<script type="text/javascript">document.getElementById('paymentform').submit();</script><?php*/ ?>