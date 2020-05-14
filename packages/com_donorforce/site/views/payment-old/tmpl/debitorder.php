<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
$session = JFactory::getSession();
	
$p=DonorForceHelper::getProject($session->get('com_donorforce.project_id'));


$PNAME = $p->name;

 ?>
<form action="<?php echo 'index.php?option=com_donorforce&view=payment&layout=thankyoudebit';?>" method="POST" id="paymentform" >


 <p>You have selected to donate <b><?php echo DonorForceHelper::getCurrency().'   '. DonorForceHelper::displayAmount($this->data['amountdisp']); ?></b> to the <b><?php echo $PNAME; ?></b> as a Recurring Debit Order.
By clicking the “confirm button” you are confirming your donation and that you have read the terms and conditions for use of our website. 
<br />

</p>
<input type="hidden" name="AMOUNT" value="<?php echo DonorForceHelper::displayAmount($this->data['amountdisp']); ?>">
<input type="hidden" name="PNAME" value="<?php echo $PNAME; ?>">
  <input type="submit" value="Confirm">
  <input type="button" value="Cancel" onclick="window.location.href='index.php?option=com_donorforce&view=projects'" />
</form>