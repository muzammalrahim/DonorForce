<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.form.formvalidator' );
jimport('joomla.html.pane');
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.tooltip');
JHtmlBehavior::framework();
jimport( 'joomla.html.parameter' );

//Load admin language file
$lang = JFactory::getLanguage();
//$lang->load('COM_DONORFORCE', JPATH_ADMINISTRATOR);


$user=JFactory::getUser();
if(!$user->id){
?>
<div  style="background-color:#F5F5F5;padding:15px;border-radius:4px;" >
<div align="center" style="color:#FF0000;" >
		<span ><?php echo JText::_('DONORFORCE_LOGIN_MSG'); ?> </span>
</div>
</div>
<?php
	return false;
}
?>

<script type="text/javascript">

function myValidate(f)
{ //console.log("myValidate funcion");
	var msg = "<?php echo JText::_( "COM_DONORFORCE_ONLY_NUMERIC_VALUE_R_ACCEPTABLE")?>";
	if (document.formvalidator.isValid(f)) {
			f.check.value='<?php echo JSession::getFormToken(); ?>'; 
			//console.log("returning ture");
			//return true; 
		}
		else {
			
			alert(msg);
		}
	
	
	//return false;
}	
</script>
<h2><?php echo JText::_('Choose a payment option'); ?></h2>
<h5 class="h5_descrip">You're almost there! Just a couple more clicks and you're done. Please choose a payment option below and click the "Submit Form" button.</h5>

<?php //echo "<pre> this->data = "; print_r($this->data); echo "</pre>"; 
	   //echo "<pre> this->userinfo = "; print_r($this->userinfo);
		//echo DonorForceHelper::displayAmount($this->data['amountdisp']);
		

?>

    <form method="post" name="adminForm" class="form-validate payment_option" onSubmit="myValidate(this);" >
      <table>
       
        <tr>
					<td> <span> <?php echo JText::_('Amount'); ?>: </span> 
                    	 <span style="float: right; padding-right: 10px; "> <?php echo DonorForceHelper::getCurrency(); ?> </span> 
                    </td>
          <td>
          
		 <?php /*?> <input type="hidden" name="amount" value="<?php echo $this->data['donationamount'];?>" class="required validate-numeric"  /><?php */?>
          
          <input type="hidden" name="display-amount" value="<?php echo DonorForceHelper::displayAmount($this->data['amountdisp']); ?>" class="required validate-numeric"  />
          
          <input name="amount" value="<?php echo DonorForceHelper::displayAmount($this->data['amountdisp']); ?>" class="required" readonly />
          <input type="hidden" name="donor_id" value="<?php echo $this->data['donor_id'];  ?>"  />
          <input type="hidden" name="project_id" value="<?php echo $this->data['project_id'];  ?>"  />
          <input type="hidden" name="cms_user_id" value="<?php echo $this->data['cms_user_id'];  ?>"  />
        
        
        <?php  
		//echo "<pre>"; print_r($this->data); exit;  
		if(isset($this->data['jform'])){ 
			?>
            
          <input type="hidden" name="donation_start_date" value="<?php echo $this->data['jform']['donation_start_date'];  ?>"  />
          <input type="hidden" name="donation_end_date" value="<?php echo $this->data['jform']['donation_end_date'];  ?>"  />
          <input type="hidden" name="deduction_day" value="<?php echo $this->data['jform']['deduction_day'];  ?>"  />
          <input type="hidden" name="frequency" value="<?php echo $this->data['jform']['frequency'];  ?>"  />
          
          <input type="hidden" name="bank_name" value="<?php echo $this->data['jform']['bank_name'];  ?>"  />
          <input type="hidden" name="account_number" value="<?php echo $this->data['jform']['account_number'];  ?>"  />
          <input type="hidden" name="account_name" value="<?php echo $this->data['jform']['account_name'];  ?>"  />
          <input type="hidden" name="account_type" value="<?php echo $this->data['jform']['account_type'];  ?>"  />
          <input type="hidden" name="branchcode" value="<?php echo $this->data['jform']['branchcode'];  ?>"  />
          <input type="hidden" name="branch_name" value="<?php echo $this->data['jform']['branch_name'];  ?>"  />          
          <input type="hidden" name="comp_code" value="<?php echo $this->data['jform']['comp_code'];  ?>"  />
          <input type="hidden" name="beneficiary_reference" value="<?php echo $this->data['jform']['beneficiary_reference'];  ?>"  />
          
            
            
        <?php     
		}
		
		?>
        
        
          </td>
        </tr>
        
        <tr>
						<td><?php echo JText::_( 'Select Payment Gateway' ); ?>:</td>
						<td colspan="3">
            	<div class="donaiton_plugin">
									<?php									
										$default="";
										if(empty($this->gateways)) 
											echo JText::_( 'NO_PAYMENT_GATEWAY' ); 
										else 
										{
											// SETTING FIRST AS DEFAULT 
											$default=$this->gateways[0]->id;
											$pg_list = JHtml::_('select.radiolist', $this->gateways, 'gateways', 'class="inputbox required" ', 'id', 'name',$default,false);
											echo $pg_list;
										}
									?>
									</div>
								</div>  		
						 </div>
            </td>
        </tr>
      </table>
      <div>
      			<input type="hidden" name="donationtype" value="<?php echo $this->donationtype;  ?>"  />
                            
				<input type="hidden" name="task" value="payment.save" />
				<input type="submit" class="button art-button" value="<?php echo JText::_('Submit Form'); ?>" />
				
			</div>
      
    </form>
