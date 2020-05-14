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
		
		jQuery('<button type="button" style="margin-top: -10px;" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_donationamount');
		
		jQuery( "#jform_donationamount" ).each(function() {
 			 //jQuery('<button type="button" style="margin-top: -10px;" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore(jQuery(this));
			 console.log(' each  ');
			 
		});
		
		

		

		jQuery('<button type="button" style="margin-top: -10px;" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_otheramount');

});
</script>




<!-- -->
<div id="donation">
<button class="accordion" id="btn_donation-selection" data-panel="donation-selection"><h2>Select Type of Donation</h2></button>
<div class="panel">
   <!-- Donation Selection -->
	<div id="donation-selection" class="borderbox">
  	<!--<h2>Select Type of Donation</h2>-->
		<h5 class="h5_descrip">A special gift is a once-off gift made using your credit card or debit card. 
    	Payment is made through our secure payment gateway.
    </h5>
    <input type="radio" name="donationtype" value="onceoff" id="onceoff" />
    <label for="onceoff">Special Gift</label>
    <br />	
		<?php  //echo "<pre> "; print_r( $this->params  ); echo "</pre>";  
	    if($this->params->get('usecc') == 0 || $this->params->get('usecc') == 2 ){ ?>   
      <input type="radio" name="donationtype" value="recurringDO" id="recurring" />   
      <label for="recurring">Recurring Donation (Debit Order)</label>  
	  <br />
    <?php  
		 }
	
	  if($this->params->get('usecc') == 1 || $this->params->get('usecc') == 2 )
		{ ?>
    <input type="radio" name="donationtype" value="recurringCO" id="recurringcc" />   
    <label for="recurringcc">Recurring Donation (Credit Card)</label>  
		<br />
    <?php 
		} ?>
		<input type="radio" name="donationtype" value="bequest" id="radio_bequest" />     
		<label for="radio_bequest">Bequest</label>
   
   </div>
  <!-- Donation Selection End -->
</div>

<button class="accordion" id="btn_amount" data-panel="amount"><h2>Select Donation Amount</h2></button>
<div class="panel">                
<?php
$user = JFactory::getUser();
$userinfo = DonorForceHelper::getFullUserInfo($user->id);
//if(true || $donationtype == 'onceoff'){ 
?>
<div id="amount">
<div id="onceoff" >

<div class="wellX container-custom borderbox">
   <!-- <h2>Select Donation Amount</h2>-->
    <h5 class="h5_descrip">Please select the amount you would like to give. If you would like to give more than ZAR 1000.00 select "Other Amount" and insert the amount you would like to give. Then click the "Submit" button."</h5>
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
    <label for="other">Other Amount:</label><b  id="oBx"><?php echo DonorForceHelper::getCurrency()?> 
    <input class="form-control input-sm" type="text" name="otheramount" id="otheramount" /></b><br />
 	<div>
  		<!--<button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>-->
    	<?php //echo JHtml::_('form.token'); ?>
  </div>                     
</div>
</div><!-- onceoff end here -->
<?php
//} elseif($donationtype == 'recurringDO') {?>
<div id="recurringDO">
<div class="wellX container-custom recurringDO">
    <h2>Debit Order Form</h2>
     <fieldset>
     <?php     
				 foreach($this->form->getFieldset('recurring_info') as $field)
				 { 
					 if ($field->hidden){  echo $field->input; }
					 else{?> 
                <div class="control-group">                            
                  <div class="control-label"><?php echo $field->label ?></div>
                  <div class="controls"><?php echo $field->input; 
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
				} // end debit info IF			   
 		 //end if*/ 
			?>
     	</div>
     </div><!-- recurringDO end -->        
    
		<?php //}else if($donationtype == 'recurringCO'){ ?>
    
	<div id="recurringCO">
    <div class="wellX container-custom recurringCO">
 		   <h2>Credit Order Form</h2>
			<?php	if($this->params->get('usecc') == 1 || $this->params->get('usecc') == 2 ){?>
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
       <?php } ?>
			</div>
      </div><!-- recurringCO end  -->
   		<?php  //}else if($donationtype == 'bequest') { ?>
    	<div id="bequest">
        <div class="wellX container-custom borderbox">
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
       <div><input type="hidden" name="option" value="com_donorforce" />      
       <input type="hidden" name="task" value="donation.savebequest" /></div>
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
<h5 class="h5_descrip">You're almost there! Just a couple more clicks and you're done. Please choose a payment option below and click the "Submit Form" button.</h5>
  
  
  	<h5 style="display:inline-block; vertical-align:top;"><?php echo JText::_( 'Select Payment Gateway' ); ?>:	</h5>					
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
      
            
  
  
</div><!-- payment end  -->
</div>


 <div style="text-align:center;">
 		<button type="submit" class="btn btn-large btn-primary">Submit</button>
    <?php echo JHtml::_('form.token'); ?>
 </div>

</div>

<script>
/*var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
  }
}*/
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
		//console.log(' triggering  ');
		//jQuery('.donation-selection donationtype')
		//jQuery('input[type=radio][name=donationtype]').change(function() {
		jQuery('input[name="donationtype"]:radio' ).change(	function() {
			
			console.log(' radio change ');
			jQuery('#recurringDO,#recurringCO,#bequest').css('display','none');
				
			var d_type = jQuery(this).val(); console.log(' d_type '+d_type);
			if(d_type == 'recurringDO'){ jQuery('#'+d_type).css('display','block');   }
			if(d_type == 'recurringCO'){ jQuery('#'+d_type).css('display','block');   }
			if(d_type == 'bequest'){ jQuery('#'+d_type).css('display','block');   }
					
		});
		
		
});
</script>
<!-- -->

<style type="text/css">
#recurringDO,#recurringCO,#bequest{display:none;}
button.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

button.accordion.active, button.accordion:hover {
    background-color: #ddd;
}

div.panel {
    padding: 0 18px;
    display: none;
    background-color: white;
}

div.panel.show {
    display: block !important;
}
.borderbox{
	    /* background-color: #385661; */
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    padding: 19px;
	}
.donaiton_plugin label{ display:block !important; }		
.donaiton_plugin{ display:inline-block; margin:12px; }
.donaiton_plugin .controls input[type="radio"]{ margin-top:4px !important; }
</style>