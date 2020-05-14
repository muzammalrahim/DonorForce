<?php 
/**
 *  @copyright  Copyright (c) 2009-2013 TechJoomla. All rights reserved.
 *  @license    GNU General Public License version 2, or later
 */
defined('_JEXEC') or die('Restricted access'); 

$plugin = JPluginHelper::getPlugin('payment', 'paygate');
$params = json_decode($plugin->params);

$VERSION = '21';
$PAYGATE_ID = $params->paygate_id; 
$REFERENCE = 'CUST-'.$vars->user_id.'-ORD-'.$vars->order_id;
	
$AMOUNT = intval($vars->amount); 
if( $AMOUNT == 100 || $AMOUNT == 200 || $AMOUNT == 350 || $AMOUNT == 500 || $AMOUNT == 750 || $AMOUNT == 1000 ){
	$AMOUNT = $AMOUNT.'00'; 
}else{
	$AMOUNT = str_replace('.', '', $vars->amount);	
}
$TRANSACTION_DATE = date('Y-m-d H:i');	
$KEY =  $params->secret; 
	
	//$vars->currency_code = 'ZAR'; 
	
	//echo " <pre> vars =  "; print_r( $vars  ); echo "</pre>";  
	
	
	$SUBS_FREQUENCY = 228;
	//echo "<br /> freq = ".$vars->frequency; exit; 
	
	if($vars->frequency == 'monthly'){ //echo "<br /> monthly "; exit;
				switch ($vars->deduction_day ) {
				case 1:
						//$vars->frequency = 201;
						$SUBS_FREQUENCY = 201;
						break;
				case 6:
						$SUBS_FREQUENCY = 206;
						break;
				case 10:
						$SUBS_FREQUENCY = 210;
						break;
				case 25:
						$SUBS_FREQUENCY = 225;
						break;
				case 39:
						$SUBS_FREQUENCY = 229;	;
						break;				
				default:	
			}
	} 
	
?>

<div class="">

   
<form action="https://www.paygate.co.za/paysubs/process.trans" method="POST" >
 <input type="hidden" name="VERSION" value="<?php echo $VERSION; ?>">
 <input type="hidden" name="PAYGATE_ID" value="<?php echo $PAYGATE_ID;  ?>">
 <input type="hidden" name="REFERENCE" value="<?php echo $REFERENCE;?>">
 <input type="hidden" name="AMOUNT" value="<?php echo $AMOUNT;?>">
 <input type="hidden" name="CURRENCY" value="<?php echo $vars->currency_code; ?>">
 <input type="hidden" name="RETURN_URL" value="<?php echo $vars->return;?>">
 <input type="hidden" name="TRANSACTION_DATE" value="<?php echo $TRANSACTION_DATE; ?>">
  <input type="hidden" name="EMAIL" value="<?php echo $vars->user_email; ?>">  
 <input type="hidden" name="SUBS_START_DATE" value="<?php echo $vars->SUBS_START_DATE; ?>">
 <input type="hidden" name="SUBS_END_DATE" value="<?php echo $vars->SUBS_END_DATE; ?>">
 <input type="hidden" name="SUBS_FREQUENCY" value="<?php echo $SUBS_FREQUENCY; ?>">
 <input type="hidden" name="PROCESS_NOW" value="NO">
 <input type="hidden" name="PROCESS_NOW_AMOUNT" >
 <input type="hidden" name="encryption_key" value="<?php echo $KEY; ?>">
 
 <?php 
 
   $checksum_source = $VERSION."|".$PAYGATE_ID."|".$REFERENCE."|".$AMOUNT."|".$vars->currency_code."|".$vars->return."|".$TRANSACTION_DATE."|".$vars->user_email."|".$vars->SUBS_START_DATE."|".$vars->SUBS_END_DATE."|".$SUBS_FREQUENCY."|NO||".$KEY;  
  $CHECKSUM = md5($checksum_source);
 ?>
 
 
 <input type="hidden" name="CHECKSUM" value="<?php echo $CHECKSUM; ?>">
  <input type="submit" class="btn btn-success" value="Confirm">
 <input type="button" value="Cancel" class="btn btn-danger"  onclick="window.location.href='index.php?option=com_donorforce&view=projects'" />
 </form>


</div>
