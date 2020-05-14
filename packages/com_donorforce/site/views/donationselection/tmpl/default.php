
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
<div>
<h2>Select Type of Donation</h2>
<h5 class="h5_descrip">A special gift is a once-off gift made using your credit card or debit card. Payment is made through our secure payment gateway.</h5>
    
<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="donation-selection" name="donationselection">
       
    <input type="radio" name="donationtype" value="onceoff" id="onceoff" />
    <label for="onceoff">Special Gift (Debit Card, Credit Card)</label>
    <br />	
	<?php
	    if($this->params->get('usecc') == 0 || $this->params->get('usecc') == 2 ){ ?>   
    <input type="radio" name="donationtype" value="recurringDO" id="recurring" />   
    <label for="recurring">Recurring Donation (Debit Order)</label>  
	<br />
    <?php 
	}
	
	    if($this->params->get('usecc') == 1 || $this->params->get('usecc') == 2 )
		{ 
	?>
    <input type="radio" name="donationtype" value="recurringCO" id="recurringcc" />   
    <label for="recurringcc">Recurring Donation (Credit Card)</label>  
	<br />
    <?php 
		}
	?>
	<input type="radio" name="donationtype" value="bequest" id="bequest" />     
	<label for="bequest">Bequest</label>




	<input type="hidden" name="option" value="com_donorforce" />
	<input type="hidden" name="project_id" value="<?php echo $this->app->input->getCmd('project_id');?>" />
    <input type="hidden" name="task" value="donation.save" />
 
   <div style="margin-top:10px;"><button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
   <?php echo JHtml::_('form.token'); ?></div>
                                        
</form>
</div>