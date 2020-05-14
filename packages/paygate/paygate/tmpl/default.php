<?php 
/**
 *  @license    GNU General Public License version 2, or later
 */
	
// no direct access
defined('_JEXEC') or die('Restricted access'); 
?>
<div class="akeeba-bootstrap">
<?php   
	$VERSION = '21';
	$plugin = JPluginHelper::getPlugin('payment', 'paygate');
	$params = json_decode($plugin->params);
	
	//echo "<pre> paygate params = "; print_r( $params );   
	//echo "<pre> "; print_r( $vars  ); echo "</pre>";  
	$PAYGATE_ID = $params->paygate_id; 
	$REFERENCE = $vars->Reference;//'CUST-'.$vars->user_id.'-'.$vars->donation;
	
	
	$TRANSACTION_DATE = date('Y-m-d H:i');
	$KEY =  $params->secret; 
	
	$AMOUNT = intval($vars->amount); 
	if( $AMOUNT == 100 || $AMOUNT == 200 || $AMOUNT == 350 || $AMOUNT == 500 || $AMOUNT == 750 || $AMOUNT == 1000 ){
		$AMOUNT = $AMOUNT.'00'; 
	}else{
		$AMOUNT = str_replace('.', '', $vars->amount);	
	}
	//echo "<br /> AMOUNT =  $AMOUNT ";
	//echo "<pre>";  print_r($vars);
	
?>



<form action="<?php echo $vars->action_url; ?>" name="adminForm" id="adminForm" onSubmit="" class="form-validate form-horizontal"  method="post">
     
    <input type="hidden" name="PAYGATE_ID" value="<?php echo $PAYGATE_ID;  ?>">
    <input type="hidden" name="REFERENCE" value="<?php echo $REFERENCE;?>">
    <input type="hidden" name="AMOUNT" value="<?php echo $AMOUNT;?>">
    <input type="hidden" name="CURRENCY" value="<?php echo  $vars->currency_code;?>">
    <input type="hidden" name="RETURN_URL" value="<?php echo $vars->return;?>">
    <input type="hidden" name="TRANSACTION_DATE" value="<?php echo $TRANSACTION_DATE; ?>">
    <input type="hidden" name="EMAIL" value="<?php echo $vars->user_email; ?>">  
    <input type="hidden" name="encryption_key" value="<?php echo $KEY; ?>">

<?php 
 $checksum_source = $PAYGATE_ID."|".$REFERENCE."|".$AMOUNT."|".$vars->currency_code."|".$vars->return."|".$TRANSACTION_DATE."|".$vars->user_email."|".$KEY;  
  $CHECKSUM = md5($checksum_source);
?>
  <input type="hidden" name="CHECKSUM" value="<?php echo $CHECKSUM; ?>">
  <input type="submit" class="btn btn-success" value="Confirm">
 <input type="button" value="Cancel" class="btn btn-danger"  onclick="window.location.href='index.php?option=com_donorforce&view=projects'" />
</form>
</div>
