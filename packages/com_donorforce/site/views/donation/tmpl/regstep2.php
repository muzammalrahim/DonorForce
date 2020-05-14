<script>

var org;
var lat;
var lng;
var orgname;

jQuery(document).ready(function(e) {
    
	org=jQuery('#jform_org_type');
	lat=jQuery('#jform_org_latitude').closest('.control-group');
	lng=jQuery('#jform_org_longitude').closest('.control-group');
	orgname=jQuery('#jform_org_name').closest('.control-group');
	
	if(jQuery('#jform_donor_id').val()==0){
		jQuery('#jform_password').attr('required','required');
		jQuery('#jform_password2').attr('required','required');
	}
	
	jQuery('#jform_org_type').on('change',function(){
			toggleOrgName();
		});
	
	toggleOrgName();
	
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
		
});


function toggleOrgName()
{	
	if(org.val()=='individual')
	{
		orgname.hide();
		//lat.show();
		//lng.show();
	}
	else
	{
		orgname.show();
		//lat.hide();
		//lng.hide();
	}
}



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

//$DFH = new DonorForceHelper;

if($user->get('guest'))
{
	$this->app->redirect('index.php?option=com_donorforce&view=projects',"Please login First");
	return;
} 

$userinfo = DonorForceHelper::getFullUserInfo($user->id);

if($userinfo->donor_id > 0)
{
$this->app->redirect('index.php?option=com_donorforce&view=donationsimple&project_id='.$project_id);
	return;
}



?>

<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="donation" name="dForm">
                

<div class="well container-custom ">
    <h2>Additional Account Information</h2>
    <h5 class="h5_descrip">We need a few more details so we can thank you for your gift. Please fill in the form below. You will only be asked to do this once. In future you will be able to log in and go straight to the donation cart.</h5>
    
    	<fieldset>
     <?php     
                     foreach($this->form->getFieldset('info') as $field)
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
                   
                </fieldset>
                
                <fieldset>
                <legend>Physical Address</legend>
     <?php     		
                     foreach($this->form->getFieldset('phy_address') as $field)
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
                   
                </fieldset>

				 <fieldset>
                <legend>Postal Address</legend>
                 <div class="control-group"> 
                     	<div class="control-label" style="float:left;"><label for="isSame">Same as physical address</label></div>
                      	<div class="controls"><input type="checkbox" name="isSame"  id="isSame" /></div>
                     </div>
     <?php     		
                     foreach($this->form->getFieldset('postal_address') as $field)
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
                   
                </fieldset>
				    
   
    <div>			<input type="hidden" name="donationtype" value="<?php echo $donationtype; ?>" />
                	<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
    				<input type="hidden" name="option" value="com_donorforce" />
                    <input type="hidden" name="task" value="donation.savedonor" /></div>
                <div><button type="submit" class="btn btn-primary"><?php echo JText::_('Submit'); ?></button>
                                        <?php echo JHtml::_('form.token'); ?></div>
    </div>
    </form>
    <div class="clr"></div>
		
				