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
  
$jinput = JFactory::getApplication()->input;
$return = $jinput->get('return');

/*$Itemid = $jinput->get('Itemid');
	$redirectUrl = urlencode(base64_encode(JRoute::_('index.php?option=com_donorforce&view=donationsimple&project_id'.$project_id))); 
 	$redirectUrl = '&return='.$redirectUrl;
  $joomlaLoginUrl = 'index.php?option=com_users&view=login';
  $finalUrl = $joomlaLoginUrl . $redirectUrl;
*/
$login_url = JRoute::_('index.php?option=com_users&amp;task=user.login&amp;Itemid='.$Itemid);

?>
    
 <div class="loginform">
   <form action="<?php echo $login_url;?>" method="post" class="form-validate form-horizontal well">

		<fieldset>
												<div class="control-group">
						<div class="control-label">
							<label id="username-lbl" for="username" class="required">
	Username<span class="star">&nbsp;*</span></label>
						</div>
						<div class="controls">
							<input type="text" name="username" id="username" value="" class="validate-username required" size="25" required="" aria-required="true" autofocus="">						</div>
					</div>
																<div class="control-group">
						<div class="control-label">
							<label id="password-lbl" for="password" class="required">
	Password<span class="star">&nbsp;*</span></label>
						</div>
						<div class="controls">
							<input type="password" name="password" id="password" value="" class="validate-password required" size="25" maxlength="99" required="" aria-required="true">						</div>
					</div>
							
			
						<div class="control-group">
				<div class="control-label"><label>Remember me</label></div>
				<div class="controls"><input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"></div>
			</div>
			
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary art-button">
						Log in					</button>
				</div>
			</div>

			<input type="hidden" name="return" value="<?php echo $return; ?>">
			<?php echo JHtml::_('form.token'); ?>
      </fieldset>
	</form>
    <div class="clr"></div>
   </div> 
	
<script type="text/javascript">
  
jQuery(document).ready(function() {});  
  
</script>  
  
  