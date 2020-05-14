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
$content_params = JComponentHelper::getParams( 'com_donorforce' );
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

$reg_step = 'regstep2';

if($donationtype != '' && $donationtype == 'onceoff')
{
	$reg_step = 'regstep3';	
}
  
?>
 <div class="regstep1_cont">
 
 <?php 
 
 $project_id = JRequest::getVar('project_id');
 $redirectUrl = base64_encode(JRoute::_(JURI::base().'index.php?option=com_donorforce&view=donationsimple&project_id='.$project_id)); 
 $redirectUrl = '&return='.$redirectUrl;
 $joomlaLoginUrl = 'index.php?option=com_users&view=login';
  //$joomlaLoginUrl = 'index.php?option=com_donorforce&view=donation&layout=login';
$finalUrl = $joomlaLoginUrl . $redirectUrl;
  
 ?>
 
    <h2>Already Registered?! <a href="<?php echo $finalUrl;?>">Sign In</a></h2>
 
    
    <h5 class="h5_descrip">If you have given before please click "sign in" and proceed with your donation. If you are giving for the first time, please fill in the registration form to enable us to assign your gift accurately. <span style="display:block; margin: 5px 0px; "> Thank you for your generosity.</span></h5>
    
 
<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="regstep1" name="regform">
                <fieldset>

                <!--test-->
                
              <div class="control-group">
                <div><label>Name</label></div>
                <div style="width:50%;">
                		<input type="text" name="name" id="name" class="form-control input-sm required" required="required" />
                 </div>
              </div>
                
                               
              <div class="control-group">
                <div><label>User Name</label></div>
                <div style="width:50%;">
               	  	<input type="text" name="username" id="username" class="form-control input-sm required" />
                </div>
              </div>
                
              <div class="control-group">
                <div><label>Email</label></div>
                <div style="width:50%;">
                	<input type="text" name="email" id="email"  class="form-control input-sm validate-email required" />
                 </div>
              </div>
                
              <div class="control-group">
                <div><label>Password</label></div>
                <div style="width:50%;">
                		<input type="password" name="password" id="password"  class="form-control input-sm required" />
                </div>
              </div>
                
              <div class="control-group">
                <div><label>Confirm Password</label></div>
                <div style="width:50%;">
                	<input type="password" name="password2" id="password2" class="form-control input-sm required validate-passverify" />
                </div>
              </div>
              <div class="control-group">
                <div id="html_element"></div>
              </div>
              <div class="control-group">
                <input type="hidden" name="donationtype" value="<?php echo $donationtype; ?>" />
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
                <input type="hidden" name="option" value="com_donorforce" />
                <input type="hidden" name="task" value="donation.saveuser" />
              </div>
                
              <div class="control-group">
                	<button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
                  <?php echo JHtml::_('form.token'); ?>
              </div>
        </fieldset>
    </form>
    <div class="clr"></div>
   </div> 
	
<script type="text/javascript">
  
jQuery(document).ready(function() {
console.log('d ready ');	

/*jQuery('input[type=submit]').click(function(){
    $('.form-validate').valid();
});

jQuery('.form-validate').validate({
            rules : {
                password : {
                    minlength : 5
                },
                password1 : {
                    minlength : 5,
                    equalTo : "#password"
                }
  			}
});*/

jQuery(document).ready(function(){ //alert('form vlidation ');
    document.formvalidator.setHandler('passverify', function (value) {
        return (jQuery('input[type=password]').value == value); 
    });
});


});  
</script>  
   <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
  <script type="text/javascript">
      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '<?= $content_params->get('recaptcha_site_key'); ?>'
        });
      };
    </script>
  