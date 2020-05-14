<script>
jQuery(document).ready(function(e) {
    jQuery('#oBx').css('display','none');
	jQuery('input[type=radio][name=donationamount]').on('change',function(e){
		if(this.value=='other')
		{
			jQuery('#oBx').css('display','');
		}
		else
		{
			jQuery('#oBx').css('display','none');
		}
		})
		
		jQuery('<button type="button" style="margin-top: -10px;" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_donationamount');

		jQuery('<button type="button" style="margin-top: -10px;" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_otheramount');

});
</script>

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
 
$user = JFactory::getUser();
//$DFH = new DonorForceHelper;

if($user->get('guest'))
{
	$this->app->redirect('index.php?option=com_donorforce&view=projects',"Please login First");
} 

$userinfo = DonorForceHelper::getFullUserInfo($user->id);

$session = JFactory::getSession();

$jinput = JFactory::getApplication()->input;
$donationtype = $jinput->get('donationtype'); 
$project_id = $jinput->get('project_id');

if($donationtype == ''){ 
	$donationtype = $session->get('com_donorforce.donationtype');
}
if($project_id == ''){ 
	$project_id = $session->get('com_donorforce.project_id');
}


?>

<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="donation" name="dForm">
                
<?php

if($donationtype == 'onceoff')
{
?>
<div class="well container-custom onceoff">
    <h2>Select Donation Amount</h2>
    <h5 class="h5_descrip">Please select the amount you would like to give. If you would like to give more than ZAR 1000.00 select "Other Amount" and insert the amount you would like to give. Then click the "Submit" button."</h5>
    
           
	<?php /*?><input type="radio" name="donationamount" value="10000" id="r100" />
    <label for="r100"><?php echo DonorForceHelper::getCurrency()?> 100.00</label>
    <br />	   
    <input type="radio" name="donationamount" value="20000" id="r200" />
    <label for="r200"><?php echo DonorForceHelper::getCurrency()?> 200.00</label>
	<br />
	<input type="radio" name="donationamount" value="35000" id="r350" />
    <label for="r350"><?php echo DonorForceHelper::getCurrency()?> 350.00</label>
    <br />
   	<input type="radio" name="donationamount" value="50000" id="r500" />
    <label for="r500"><?php echo DonorForceHelper::getCurrency()?> 500.00</label>
    <br />
    <input type="radio" name="donationamount" value="75000" id="r750" />
    <label for="r750"><?php echo DonorForceHelper::getCurrency()?> 750.00</label>
    <br />
    <input type="radio" name="donationamount" value="100000" id="r1000" />
    <label for="r1000"><?php echo DonorForceHelper::getCurrency()?> 1000.00</label><?php */?>
    
    
    
    
    <input type="radio" name="donationamount" value="100.00" id="r100" />
    <label for="r100"><?php echo DonorForceHelper::getCurrency()?> 100.00</label>
    <br />	   
    <input type="radio" name="donationamount" value="200.00" id="r200" />
    <label for="r200"><?php echo DonorForceHelper::getCurrency()?> 200.00</label>
	<br />
	<input type="radio" name="donationamount" value="350.00" id="r350" />
    <label for="r350"><?php echo DonorForceHelper::getCurrency()?> 350.00</label>
    <br />
   	<input type="radio" name="donationamount" value="500.00" id="r500" />
    <label for="r500"><?php echo DonorForceHelper::getCurrency()?> 500.00</label>
    <br />
    <input type="radio" name="donationamount" value="750.00" id="r750" />
    <label for="r750"><?php echo DonorForceHelper::getCurrency()?> 750.00</label>
    <br />
    <input type="radio" name="donationamount" value="1000.00" id="r1000" />
    <label for="r1000"><?php echo DonorForceHelper::getCurrency()?> 1000.00</label>
    
    
    
    
    <br />
    <input type="radio" name="donationamount" value="other" id="other" /> 
    <label for="other">Other Amount:</label><b  id="oBx"> <?php echo DonorForceHelper::getCurrency()?> <input class="form-control input-sm" type="text" name="otheramount" id="otheramount" /></b><br />

 <div>
 					<input type="hidden" name="donationtype" value="<?php echo $donationtype; ?>" />
                	<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
                    
 					<input type="hidden" name="option" value="com_donorforce" />
                    <!--<input type="hidden" name="task" value="donation.savedonation" /></div>-->
                    <input type="hidden" name="task" value="donation.ProcessToPaymentGatways" /></div>
                <div><button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
                     <?php echo JHtml::_('form.token'); ?></div>
                     
</div>
<?php
} elseif($donationtype == 'recurringDO') {
 
?>

<div class="well container-custom recurringDO">
    <h2>Debit Order Form</h2>

     <fieldset>
     <?php     
                     foreach($this->form->getFieldset('recurring_info') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         {
							 ?> 
                            <div class="control-group">
                            
                                <div class="control-label">
                                    <?php echo $field->label ?>
                                </div>
                                
                                <div class="controls">
                                    <?php echo $field->input ?>
                                     <?php 
									if($field->getAttribute('name') == 'donation_end_date'){ ?><br />

                                    <span style="color:#F00; font-size:11px;">By leaving this empty the debit order will continue monthly until you request us to discontinue.</span>
                                    <?php } ?>
                                </div>
                                
                            </div>
                            <?php 
                    
						  
                         }
                         
                     } 
				?>
               </fieldset>
            
            <?php 
			
			
			$debitinfo = DonorForceHelper::getUserDebitInfo($userinfo->cms_user_id);
			//echo "<br /> <pre> debitinfo = "; print_r($this->params);  
			
			
			if(empty($debitinfo))
			{
			
               if($this->params->get('usecc') == 0 || $this->params->get('usecc') == 2 ){?>
     		<fieldset>
     		<?php     
                     foreach($this->form->getFieldset('debit_info') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         {
							 ?> 
                            <div class="control-group">
                            
                                <div class="control-label">
                                    <?php echo $field->label ?>
                                </div>
                                
                                <div class="controls">
                                    <?php echo $field->input ?>
                                     <?php 
									if($field->getAttribute('name') == 'donation_end_date'){ ?><br />

                                    <span style="color:#F00; font-size:11px;">By leaving this empty the debit order will continue monthly until you request us to discontinue.</span>
                                    <?php } ?>
                                </div>
                                
                            </div>
                            <?php 
                    
						  
                         }
                         
                     } 
				?>
               </fieldset>
              <?php 
			   }
				} // end debit info IF
			  ?>
                <?php 
 //end if*/ 
				?>

                <div>
                <input type="hidden" name="donationtype" value="<?php echo $donationtype; ?>" />
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
                    
                <input type="hidden" name="option" value="com_donorforce" />
                    <!--<input type="hidden" name="task" value="donation.saverecdonation" />-->
                    <!--<input type="hidden" name="task" value="donation.ProcessToPaymentGatways" />-->
                    <input type="hidden" name="task" value="payment.save" />
                    </div>
        
                <div><button type="submit" class="btn btn-primary"><?php echo JText::_('Submit'); ?></button>
                                        <?php echo JHtml::_('form.token'); ?></div>
       </div>
      
   
    
    <?php } 
	else if($donationtype == 'recurringCO')
	{ ?>
		<div class="well container-custom recurringCO">
 		   <h2>Credit Order Form</h2>
	<?php	if($this->params->get('usecc') == 1 || $this->params->get('usecc') == 2 ){
				?>
               <fieldset>
               <legend>If you wish us to debit your monthly donation from your credit card please fill in your credit card details below.</legend>
     <?php     
	 
	 				 foreach($this->form->getFieldset('recurring_info') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         {
							 ?> 
                            <div class="control-group">
                            
                                <div class="control-label">
                                    <?php echo $field->label ?>
                                </div>
                                
                                <div class="controls">
                                    <?php echo $field->input ?>
                                     <?php 
									if($field->getAttribute('name') == 'donation_end_date'){ ?><br />

                                    <span style="color:#F00; font-size:11px;">By leaving this empty the debit order will continue monthly until you request us to discontinue.</span>
                                    <?php } ?>
                                </div>
                                
                            </div>
                            <?php 
                    
						  
                         }
                         
                     } 
                     /*foreach($this->form->getFieldset('ccinfo') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         {
							 ?> 
                            <div class="control-group">
                            
                                <div class="control-label">
                                    <?php echo $field->label ?>
                                </div>
                                
                                <div class="controls">
                                    <?php echo $field->input ?>
                                </div>
                                
                            </div>
                            <?php 
                    
						  
                         }
                         
                     } */
				?>
                  
                    <input type="hidden" name="isCredit" value="1" />
                    
                    <input type="hidden" name="donationtype" value="<?php echo $donationtype; ?>" />
                	<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
                    <input type="hidden" name="option" value="com_donorforce" />
                   <!-- <input type="hidden" name="task" value="donation.saverecdonation" />-->
                    <input type="hidden" name="task" value="donation.ProcessToPaymentGatways" />
              
                <div><button type="submit" class="btn btn-primary"><?php echo JText::_('Submit'); ?></button>
                                        <?php echo JHtml::_('form.token'); ?></div>
               </fieldset>
               <?php 
				}?>
				</div>
                <?php 
	}
	else if($donationtype == 'bequest') { ?>
    	<fieldset>		
                <legend>Considering leaving something to us in your will? Please fill in the form below and submit it. Someone will be in touch with you shortly to give you more information. </legend>
          <?php     
                     foreach($this->form->getFieldset('bequest') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         {
							 ?> 
                            <div class="control-group">
                            
                                <div class="control-label">
                                    <?php echo $field->label ?>
                                </div>
                                
                                <div class="controls">
                                    <?php echo $field->input ?>
                                </div>
                                
                            </div>
                            <?php 
                    
						  
                         }
                         
                     } 
				?>      
                
                <div><input type="hidden" name="option" value="com_donorforce" />
      
                    <input type="hidden" name="task" value="donation.savebequest" /></div>
                <div><button type="submit" class="btn btn-primary"><?php echo JText::_('Submit'); ?></button>
                                        <?php echo JHtml::_('form.token'); ?></div>
        </fieldset>
     
    <?php } ?>
	<input type="hidden" name="currency" id="currency" value="<?php echo DonorForceHelper::getCurrency(); ?>"  />
    </form>
    <div class="clr"></div>
<script type="text/javascript">

jQuery(document).ready(function(e) {
   
	jQuery(document).on('keyup','.numeric',function(){
		this.value=this.value.replace(/[^0-9\.]/g,'');
	});
	
});

</script>
