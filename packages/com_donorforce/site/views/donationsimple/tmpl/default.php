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
?>
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
		
		jQuery('<button type="button"  class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_donationamount');
		
		jQuery('<button type="button" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_otheramount');
		
		
		
		jQuery('<button type="button" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_co_donationamount');
		
		jQuery('<button type="button" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_co_otheramount');
		

});
</script>


<?php
$user = JFactory::getUser();
$userinfo = DonorForceHelper::getFullUserInfo($user->id);
//echo "<pre> userinfo = "; print_r( $userinfo  ); echo "</pre>";  
$donor_id = $userinfo->donor_id;
?>

<!-- -->
<div id="donation">
<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="onepage_Donation" name="onepage_Donation_Form">
<button class="accordion" id="btn_donation-selection" data-panel="donation-selection"><h2>Select Type of Donation</h2></button>
<div class="panel">
   <!-- Donation Selection -->
	<div id="donation-selection" class="borderbox">
		<h5 class="h5_descrip">A special gift is a once-off gift made using your credit card or debit card. 
    	Payment is made through our secure payment gateway.
    </h5>
    <input type="radio" name="donationtype" value="onceoff" id="onceoff" checked="checked"  />
    <label for="onceoff">Special Gift (Debit Card, Credit Card)</label>
    <br />	
		<?php  //echo "<pre> "; print_r( $this->params  ); echo "</pre>";  
	    if($this->params->get('usecc') == 0 || $this->params->get('usecc') == 2 ){ ?>   
      <input type="radio" name="donationtype" value="recurringDO" id="recurring" />   
      <label for="recurring">Recurring Donation (Recurring Debit Subscription)</label>  
	  <br />
    <?php  
		 }
	
	  if($this->params->get('usecc') == 1 || $this->params->get('usecc') == 2 )
		{ ?>
    <input type="radio" name="donationtype" value="recurringCO" id="recurringcc" />   
    <label for="recurringcc">Recurring Donation (Credit Card Subscription)</label>  
		<br />
    <?php 
		} ?>
		<input type="radio" name="donationtype" value="bequest" id="radio_bequest" />     
		<label for="radio_bequest">I would like to leave a gift in my will</label>
   
   </div>
  <!-- Donation Selection End -->
</div>

<button class="accordion" id="btn_amount" data-panel="amount"><h2>Select Donation Amount</h2></button>
<div class="panel">                
<div id="amount">
<div id="onceoff" >
<div class="container-custom borderbox">
    <h5 class="h5_descrip">Please select the amount you would like to give. If you would like to give more than ZAR 1000.00 select "Other Amount" and insert the amount you would like to give.</h5>
    <p class="error_message" ></p>
    <input type="radio" name="donationamount" value="100.00" id="r100"  />
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
    <label for="other">Other Amount:</label><b  id="oBx"><?php echo DonorForceHelper::getCurrency()?> 
    <input class="form-control input-sm" type="text" name="otheramount" id="otheramount" /></b><br />                     
</div>
</div><!-- onceoff end here -->


<div id="recurringDO">
<div class="container-custom recurringDO">
    <h2>Debit Order Form</h2>
     <p class="error_message"> </p>
     <fieldset>
     <?php     
				 foreach($this->form->getFieldset('recurring_info') as $field)
				 { 
				 if ($field->hidden){  echo $field->input;
				  }else
					 	{?> 
            	    <div class="control-group">                            
                	  <div class="control-label"><?php echo $field->label ?></div>
                  		<div class="controls"><?php echo $field->input; 
				if($field->getAttribute('name') == 'donation_end_date')
					{ ?><br />
                     <span style="color:#F00; font-size:11px;">
                     		By leaving this empty the debit order will continue monthly until you request us to discontinue.
                     </span>
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
			if(empty($debitinfo))
			{			
        if($this->params->get('usecc') == 0 || $this->params->get('usecc') == 2 ){?>
     		<fieldset>
     		<?php     
				 foreach($this->form->getFieldset('debit_info') as $field)
				 { 
					if ($field->hidden){ echo $field->input;}
					else{ ?> 
             <div class="control-group">               
                <div class="control-label"><?php echo $field->label ?></div>
                <div class="controls"><?php echo $field->input ?>
                <?php 
								if($field->getAttribute('name') == 'donation_end_date'){ ?><br />
                    <span style="color:#F00; font-size:11px;">
                    		By leaving this empty the debit order will continue monthly until you request us to discontinue.
                    </span>
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
				} 
			?>
     	</div>
     </div><!-- recurringDO end -->        
     <div id="recurringCO">
    <div class="container-custom recurringCO">
 		   <h2>Credit Order Form</h2>
       <p class="error_message"> </p>
			<?php	if($this->params->get('usecc') == 1 || $this->params->get('usecc') == 2 ){?>
       <fieldset>
       <legend>If you wish us to debit your monthly donation from your credit card please fill in your credit card details below.</legend>
     <?php     
	 		foreach($this->form->getFieldset('recurringco_info') as $field)
			 { 
					 if ($field->hidden)
					 { 
							 echo $field->input; 
					 }
					 else
					 {
			?> 
        <div class="control-group">
          	<div class="control-label"><?php echo $field->label ?></div>                    
            <div class="controls"><?php echo $field->input ?>
            <?php 
						if($field->getAttribute('name') == 'donation_end_date'){ ?><br />
              <span style="color:#F00; font-size:11px;">
              		By leaving this empty the debit order will continue monthly until you request us to discontinue.
              </span>
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
      if(empty($debitinfo))
      {     
        if($this->params->get('usecc') == 0 || $this->params->get('usecc') == 2 ){?>
        <fieldset>
        <?php     
         foreach($this->form->getFieldset('ccinfo') as $field)
         { 
          if ($field->hidden){ echo $field->input;}
          else{ ?> 
             <div class="control-group">               
                <div class="control-label"><?php echo $field->label ?></div>
                <div class="controls"><?php echo $field->input ?>
                <?php 
                if($field->getAttribute('name') == 'donation_end_date'){ ?><br />
                    <span style="color:#F00; font-size:11px;">
                        By leaving this empty the debit order will continue monthly until you request us to discontinue.
                    </span>
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
        } 
      ?>
      </div>
     </div><!-- recurringDO end -->  
    
		
    
	<!-- <div id="recurringCO">
    <div class="container-custom recurringCO">
 		   <h2>Credit Order Form</h2>
       <p class="error_message"> </p>
			<?php	//if($this->params->get('usecc') == 1 || $this->params->get('usecc') == 2 ){?>
       <fieldset>
       <legend>If you wish us to debit your monthly donation from your credit card please fill in your credit card details below.</legend>
     <?php     
	 		//foreach($this->form->getFieldset('recurringco_info') as $field)
			 { 
					 //if ($field->hidden)
					 { 
						//	 echo $field->input; 
					 }
					 //else
					 {
			?> 
        <div class="control-group">
          	<div class="control-label"><?php //echo $field->label ?></div>                    
            <div class="controls"><?php //echo $field->input ?>
            <?php 
						//if($field->getAttribute('name') == 'donation_end_date'){ ?><br />
              <span style="color:#F00; font-size:11px;">
              		By leaving this empty the debit order will continue monthly until you request us to discontinue.
              </span>
            <?php } ?>
            </div>                               
        </div>
        <?php 
           }                         
         }                      
				?>
             
       </fieldset>
        
			</div>
      </div><!-- recurringCO end  -->
   		
    	<div id="bequest">
        <div class="container-custom borderbox">
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
                    <div class="control-label"><?php echo $field->label ?></div>                                
                    <div class="controls"><?php echo $field->input ?></div>                    
                </div>
							<?php 
                }
            } 
			?>                      
      <div>
      	<input type="hidden" name="option" value="com_donorforce" />      
       	<input type="hidden" name="task" value="donation.savebequest" />
      </div>
      </fieldset>
      </div>
     </div><!-- bequest end -->
    <?php //} ?>
	<input type="hidden" name="currency" id="currency" value="<?php echo DonorForceHelper::getCurrency(); ?>"  />
    <!-- Amount selection  -->
</div>
</div><!-- Donation amount end  -->

<button class="accordion" id="btn_payment" data-panel="payment"><h2><?php echo JText::_('Choose a payment option'); ?></h2></button>
<div class="panel">
<div id="payment" class="borderbox">
		<h5 class="h5_descrip">You're almost there! Just a couple more clicks and you're done. 
    Please choose a payment option below and click the "Submit Form" button.</h5>
  	<p class="error_message"></p>
  	<h5 style="display:inline-block; vertical-align:top;"><?php echo JText::_( 'Select Payment Gateway' ); ?>:	</h5>					
    <div class="donaiton_plugin">
			<?php									
        $default="";
        if(empty($this->gateways)) 
          echo JText::_( 'NO_PAYMENT_GATEWAY' ); 
        else 
        {
          // SETTING FIRST AS DEFAULT 
          $default= ''; //$this->gateways[0]->id;
          $pg_list = JHtml::_('select.radiolist', $this->gateways, 'gateways', 'class="inputbox required" ', 'id', 'name',$default,false);
          echo $pg_list;
        }
      ?>
    </div>
</div><!-- payment end  -->
</div>


 <div style="text-align:center;">
 		<input type="hidden" name="task" value="payment.saveonepage" /> 
    <input type="hidden" name="donor_id" value="<?php echo $donor_id;  ?>"  />
    <input type="hidden" name="project_id" value="<?php echo JRequest::getVar('project_id');  ?>"  />
    <input type="hidden" name="cms_user_id" value="<?php echo $user->id;  ?>"  />         
 		<button type="submit" id="btn_submit" class="btn btn-large btn-primary">Submit</button>
    <?php echo JHtml::_('form.token'); ?>
 </div>
</form>
</div>

<script>
jQuery('document').ready(function(e){
		
		jQuery('.accordion').on('click', function(e){
			console.log(' accordion click  ');
			jQuery('.accordion').each(function() {
  				jQuery( this ).removeClass( "active" );
					jQuery( '#'+jQuery( this ).data('panel') ).parent('.panel').removeClass( "show" );
			});
 					jQuery( this ).addClass( "active" );
					jQuery( '#'+jQuery( this ).data('panel') ).parent('.panel').addClass( "show" );
				
		});
		
		jQuery('#btn_donation-selection').click();
		jQuery('input[name="donationtype"]:radio' ).change(	function() {
			
			console.log(' radio change ');
			jQuery('#recurringDO,#recurringCO,#bequest').css('display','none');
				
			var d_type = jQuery(this).val(); console.log(' d_type '+d_type);
				
			if(d_type == 'bequest'){ 
						jQuery('#'+d_type).css('display','block');
						jQuery('#btn_payment').css('display','none');   
						jQuery('.panel #amount #onceoff').css('display','none');
						jQuery('#onepage_Donation input[name="task"]').val('donation.savebequest');  						
						
			 }else{
						jQuery('#onepage_Donation input[name="task"]').val('payment.saveonepage');  						   
				}
				
				
				if(d_type == 'onceoff'){ 
						jQuery('.panel #amount #onceoff').css('display','block');
						jQuery('#btn_payment').css('display','block');     
				}			
				if(d_type == 'recurringDO'){ 
						jQuery('#'+d_type).css('display','block'); 
						jQuery('.panel #amount #onceoff').css('display','none');
						jQuery('#btn_payment').css('display','none');    
			 }
			 if(d_type == 'recurringCO'){ 
						jQuery('#'+d_type).css('display','block');  
						jQuery('.panel #amount #onceoff').css('display','none');
						jQuery('#btn_payment').css('display','none');  
			 }
			 
					
		});
		
		
	jQuery('#btn_submit').on('click',function(event){
		event.preventDefault();
		jQuery('.error_message').html('');
		jQuery('.req').remove();
		var sumbit_btn = true;
		
		var donation_type = jQuery('input[type=radio][name=donationtype]:checked').val();
		
		
		var donation_amount = ''; 		
	if( donation_type == 'onceoff' ){
		donation_amount = jQuery("input[name='donationamount']:checked").val();
	}else if(donation_type == 'recurringDO'){
		donation_amount = (jQuery('#recurringDO #jform_otheramount').val() != '')? jQuery('#recurringDO #jform_otheramount').val() : jQuery('#recurringDO #jform_donationamount').val() ;
	}else if( donation_type == 'recurringCO' ){
		donation_amount = (jQuery('#recurringCO #jform_co_otheramount').val() != '')? jQuery('#recurringCO #jform_co_otheramount').val() : jQuery('#recurringCO #jform_co_donationamount').val() ;	
	}
	
		var payment_gatway = jQuery('input[type=radio][name=gateways]:checked').val(); 
	
		if(	donation_type == undefined){
				jQuery('#btn_donation-selection').click();
				alert('Please Select Donation Type');
		}else if(donation_type == 'onceoff'){
				if(donation_amount == '' || donation_amount == undefined){
							jQuery("#amount .error_message").append('<span> Please Select Amount  </span>'); 
							sumbit_btn = false;			
							jQuery("#btn_amount").trigger('click');	
				}
				else if(payment_gatway == ''  || payment_gatway == undefined){
					jQuery('#payment .error_message').append('<span> Please Select Payment Gateway </span>');
					sumbit_btn = false;			
					jQuery("#btn_payment").trigger('click');	
				}
			
		}else if(	donation_type == 'recurringDO'){
				jQuery(".recurringDO :input").prop('required',null);
				var req = []; var req_status = false; 
					req[0] = 		{ 'id' : "jform_bank_name", 'name' : "Bank Name"},
					req[1] = 		{ 'id' : "jform_account_number", 'name' : "Account Number"},
					req[2] = 		{ 'id' : "jform_account_name", 'name' : "Account Name"},
					req[3] = 		{ 'id' : "jform_branchcode", 'name' : " Branch Code "},
					req[4] = 		{ 'id' : "jform_donation_start_date", 'name' : "Start Date"},
					// req[5] = 		{ 'id' : "jform_donation_end_date", 'name' : "End Date"},
					req[5] = 		{ 'id' : "jform_donationamount", 'name' : "Donation Amount"}
							

					for ( var index=0; index<req.length; index++ ) {
							//console.log( req[index].id+'  '+req[index].name  );
							if(req[index].id == 'jform_donationamount'){
								if(donation_amount == '' || donation_amount == undefined){
									sumbit_btn = false;	req_status = true;	
									jQuery('#recurringDO #'+req[index].id).after( '<span class="req" style="color:red;">Required</span>' );				
								}
							}else{
							
							if(jQuery('#recurringDO #'+req[index].id).length && jQuery('#recurringDO #'+req[index].id).val() == ''){
								jQuery('#recurringDO #'+req[index].id).after( '<span class="req" style="color:red;">Required</span>' );
								sumbit_btn = false;		req_status = true; 
								jQuery("#recurringDO .error_message").html('<span> * Please fill up all the Required fields. </span>');
							}
							}
					}
					
			if(req_status){
				jQuery("#btn_amount").trigger('click');			
			}/*else if(payment_gatway == '' || payment_gatway == undefined ){
					jQuery('#payment .error_message').append('<span> Please Select Payment Gateway </span>');
					sumbit_btn = false;			
					jQuery("#btn_payment").trigger('click');	
			}*/
				
		}else if(	donation_type == 'recurringCO'){
				jQuery(".recurringDO :input").prop('required',null);
				var req = []; var req_status = false; 
					req[0] = 		{ 'id' : "jform_co_donation_start_date", 'name' : "Start Date"},
					// req[1] = 		{ 'class' : "jform_co_donation_end_date", 'name' : "End Date"},
					req[1] = 		{ 'id' : "jform_co_donationamount", 'name' : "Donation Amount"}		

					for ( var index=0; index<req.length; index++ ) {
							//console.log( req[index].id+'  '+req[index].name  );
							if(req[index].id == 'jform_co_donationamount'){
								if(donation_amount == '' || donation_amount == undefined){								
									sumbit_btn = false;	req_status = true;	
									jQuery('#recurringCO #'+req[index].id).after( '<span class="req" style="color:red;">Required</span>' );				
								}
							}else{
							
							if(jQuery('#recurringCO #'+req[index].id).length && jQuery('#recurringCO #'+req[index].id).val() == ''){
								jQuery('#recurringCO #'+req[index].id).after( '<span class="req" style="color:red;">Required</span>' );
								sumbit_btn = false;		 req_status = true; 
								jQuery("#recurringCO .error_message").html('<span> * Please fill up all the Required fields. </span>');
							}
							
							}
					}
				
				if(req_status == true){
					jQuery("#btn_amount").trigger('click');			
			  }/*else if(payment_gatway == '' || payment_gatway == undefined ){
					jQuery('#payment .error_message').append('<span> Please Select Payment Gateway </span>');
					sumbit_btn = false;			
					jQuery("#btn_payment").trigger('click');	
				}*/
			
		}
		
		console.log(' sumbit_btn '+sumbit_btn);
		
		if( sumbit_btn == true){ jQuery("#onepage_Donation").submit(); }
		
	});	
	
		
		var current = jQuery('input[type=radio][name=donationtype]:checked').val();
		jQuery('input:radio[name="donationtype"][value="'+current+'"]').change();
		
		console.log('current = '+current);
		if(current == 'onceoff'){
				
				var donationamount = jQuery('input[type=radio][name=donationamount]:checked').val();
				if(donationamount == 'other'){
						jQuery('input:radio[name="donationamount"][value="'+donationamount+'"]').change();
		
				}
				
			
		}else if(current == 'recurringDO' ){
			
		}else if( current == 'recurringCO' ){
			
		}
		
	
		
});
</script>
<!-- -->

<style type="text/css">
.error_message {
	color: red;
  margin: 10px 0px;
  font-weight: normal;
}

#recurringDO,#recurringCO,#bequest{display:none;}
button.accordion,button.accordion.active, button.accordion:hover{ background-color: #607D8B !important; }
button.accordion.active{    background-color: #8BC34A !important; }
button.accordion h2{ color: #1a282d;}
button.accordion {
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
		margin-top:0px;
		
}

button.accordion.active, button.accordion:hover {
    background-color: #ddd;
}

div.panel {
    padding: 0 18px;
    display: none;
    background-color: white;
		margin-bottom: 10px;
		color: black;
		padding: 10px;
}
div.panel.show {
    display: block !important;
}
.borderbox{
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    padding: 19px;
		margin-bottom:0px;
	}
.borderbox h5{color:black;}	
.donaiton_plugin label{ display:block !important; }	 	
.donaiton_plugin{ display:inline-block; margin:12px; }
.donaiton_plugin .controls input[type="radio"]{ margin-top:4px !important; }
#jform_date_img{ margin-bottom: 9px; }
#donation button.accordion h2{
		margin-top: 5px;
		margin-bottom: 5px;
		font-size: 22px; 
	}
.borderbox{padding: 5px 20px; margin: 10px 0px;}
.btn-large,.btn-large:hover,.btn-large.active{font-weight:bold; padding: 11px 19px; font-size: 16.25px; } 
#jform_donation_start_date, #jform_donation_end_date, #jform_co_donation_start_date, #jform_co_donation_end_date{ 
	display:block !important; 
	float:left;	
}

#recurringDO label, #recurringCO label{display:block !important;}

#recurringDO .input-append .req,
#recurringCO .input-append .req{ float:left; font-size:14px; margin:4px; float: right;  }
.req{ font-size:14px; margin:4px; }

#recurringDO .inputbox,
#recurringCO .inputbox {display:inline-block;}

</style>