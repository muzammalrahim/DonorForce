<script>
jQuery(document).ready(function(e) {
    jQuery('<button type="button" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_amount')
});
jQuery(document).on('change','#jform_amount',function(e){
	var t=jQuery('#jform_amount').val();
	if(t)
	{
		
		jQuery('#jform_amount').val(Number (t).toFixed(2))	
	}
	})
</script>

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

//JHtml::_('behavior.modal');
$document = JFactory::getDocument();

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		jQuery('#jform_action').val(task);
		if (task == 'adddonation.cancel' || document.formvalidator.isValid(document.id('class-form')))
		{
			<?php //echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task, document.getElementById('class-form'));
		}
		else if (task == 'adddonation.save2new')
		{
			Joomla.submitform(task, document.getElementById('class-form'));
		}
	}
	
</script>

<form action="<?php echo JRoute::_('index.php?option=com_donorforce&layout=edit&subscription_id='.(int) $this->item->donor_history_id); ?>" method="post" name="adminForm" id="class-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">    		
    <div class="span9 form-horizontal">		
        <fieldset class="adminform">
           <legend>Once-Off Donation Subscription</legend>
       		<div class="tab-content">
				<div class="tab-pane active" id="details">                
				<?php
                 foreach($this->form->getFieldset('subscription') as $field){ 
                     if($field->hidden){ 
                     		$jformName 	=  $field->name;
                     		if($jformName == 'jform[Reference]'){
                     			$ref = 'BKS-'.$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);;
                     			$field->setValue($ref);
	                     	}
                         echo $field->input;       
                     }else{ 
                        echo '<div class="control-group">
								<div class="control-label">'.$field->label. '</div>
                      			<div class="controls">'. $field->input.' </div>
							  </div>' . "\n";
                        echo '<div class="clr"></div>';
                     }                     
                 } 
                ?>    
					<div class="control-group">
						<div class="controls"><p><b>If the donor is not selectable in the dropdown list then the donor is not a Donorforce listed donor. Please go back and add the individual as a donor before trying to add the donation here.</b></p></div>
					</div>
					<div class="clr"></div>  	   			               
               </div>                
           </div>
                
    </fieldset>   
	</div>   
</div>
<input type="hidden" name="task" value="" />
<input type="hidden" name="currency" id="currency" value="<?php echo DonorforceHelper::getCurrency(); ?>"  />
<?php echo JHtml::_('form.token'); ?>
</form>
<link rel="stylesheet" type="text/css"  href="<?php echo JURI::root(); ?>administrator/components/com_donorforce/assets/chosen/chosen.css" />
<script  src="<?php echo JURI::root(); ?>administrator/components/com_donorforce/assets/chosen/chosen.jquery.js"></script>
<script>
jQuery( document ).ready(function() {   
	jQuery("#jform_donor_id").chosen();
	jQuery("#jform_project_id").chosen();
});
</script>