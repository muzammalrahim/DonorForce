<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//JHtml::_('behavior.modal');
$document = JFactory::getDocument();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'subscription.cancel' || document.formvalidator.isValid(document.id('class-form')))
		{
			<?php //echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task, document.getElementById('class-form'));
		}
	}
 
 jQuery(document).ready(function(e) {
	jQuery('<button type="button" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_amount');
 });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_donorforce&layout=edit&subscription_id='.(int) $this->item->subscription_id); ?>" method="post" name="adminForm" id="class-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">
    		
    <div class="span9 form-horizontal">
		
        <fieldset class="adminform">
                	<legend>Recurring Donation Subscription</legend>
            
          
    
    
       		<div class="tab-content">
				<div class="tab-pane active" id="details">                
				<?php     
                 foreach($this->form->getFieldset('subscription') as $field)
                 { 
                     if ($field->hidden)
                     { 
                         echo $field->input; 
                     }
                     else
                     { 
                        echo '<div class="control-group">
								<div class="control-label">'.$field->label. '</div>
                      			<div class="controls">'. $field->input.' </div>
							  </div>' . "\n";
                        echo '<div class="clr"></div>';
                     }                     
                 } 
                ?>       			
                
              
              
                
                </div>
                
               
                
                </div>
                
  
            
    </fieldset>
    
	</div>
    
</div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="currency" id="currency" value="<?php echo DonorforceHelper::getCurrency(); ?>"  />
<?php echo JHtml::_('form.token'); ?>
</form>
<script>

var org;
var lat;
var lng;

jQuery(document).ready(function(e) {
    
	org=jQuery('#jform_org_type');
	lat=jQuery('#jform_org_latitude').closest('.control-group');
	lng=jQuery('#jform_org_longitude').closest('.control-group');
	
	if(jQuery('#jform_donor_id').val()==0){
		jQuery('#jform_password').attr('required','required');
		jQuery('#jform_password2').attr('required','required');
	}
	
	jQuery('#jform_org_type').on('change',function(){
			toggleLatLng();
		});
	
	toggleLatLng();
	
	jQuery('#isSame').change(function(){ 
		if(this.checked)
		{
		 jQuery('#jform_post_address').val(jQuery('#jform_phy_address').val());		 
		 jQuery('#jform_post_city').val(jQuery('#jform_phy_city').val());
		 jQuery('#jform_post_zip').val(jQuery('#jform_phy_zip').val());
		 jQuery('#jform_post_state').val(jQuery('#jform_phy_state').val());
		 jQuery('#jform_post_country').val(jQuery('#jform_phy_country').val())
		}
		else
		{
		 jQuery('#jform_post_address').val('');
		 jQuery('#jform_post_city').val('');
		 jQuery('#jform_post_zip').val('');
		 jQuery('#jform_post_state').val('');
		 jQuery('#jform_post_country').val('');
		}
	 });
	
	jQuery('.history_status').on('change',function(){	
		var id=this.id;
		var status=jQuery('#'+id).val();
		url='index.php?option=com_donorforce&task=ajax.changeHistoryStatus&format=raw';
		jQuery.post(url, {history_id:id,status:status},function(data){
			if(data!='updated')
			alert('History record updation failed.');
		});
	});
	
	jQuery('.subscription_type').on('change',function(){	
		var id=this.id;
		
		var sub_type=jQuery('#'+id).val();
		
		var sub_id=id.split('sub_'); 
		
		url='index.php?option=com_donorforce&task=ajax.changeSubscriptionType&format=raw';
		
		jQuery.post(url, {"sub_type" : sub_type, "subscription_id" : sub_id[1] },function(data){
			if(data!='updated')
			alert('Subscription record updation failed.');
		});
		
	});
		
});


function toggleLatLng()
{	
	if(org.val()=='church')
	{
		lat.show();
		lng.show();
	}
	else
	{
		lat.hide();
		lng.hide();
	}
}



</script>