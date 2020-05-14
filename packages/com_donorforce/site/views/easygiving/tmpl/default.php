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
	
	jQuery('form').submit(function( e ) {
			if (! jQuery('#check00').is(":checked")) 
			{
			   alert('Please accept Terms and Conditions');
			  	e.preventDefault();
			}
		});
});
</script>


<div id="donation">
<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="easygiving_Donation" name="easygivingDonation">
 					
          <fieldset>
          	<?php echo  (JComponentHelper::getParams('com_donorforce')->get('easygiving_title') != '')? ('<h1>'.JComponentHelper::getParams('com_donorforce')->get('easygiving_title').'</h1>') : ''; ?>
            
            <?php echo  (JComponentHelper::getParams('com_donorforce')->get('easygiving_description') != '')? ('<div class="easygiving_desc"><p>'.JComponentHelper::getParams('com_donorforce')->get('easygiving_description').'</p></div>') : ''; ?>
            
            
              <div class="control-group">
                <div><label>Name</label></div>
                <div><input type="text" name="name" id="name" class="form-control input-sm required" required="required" /></div>
              </div>
              
              <div class="control-group">
                <div><label>Email</label></div>
                <div><input type="text" name="email" id="email"  class="form-control input-sm validate-email required" /></div>
              </div>
                
               <div class="control-group">
                <div><label>Project</label></div>
                <div> <?php 
                 foreach($this->project_list as $project) :
                    $options[] = JHTML::_('select.option', $project->project_id, $project->name);
                  endforeach;
                  echo JHTML::_('select.genericlist', $options, 'project', 'class="inputbox"', 'value', 'text', null);
                 ?>
                 </div>
              </div>
              
               <div class="control-group">
                <div><label>Amount</label></div>
                <div class="amount_input">
                <button type="button" class="btn curreny" aria-invalid="false"><i class=""><?php echo JComponentHelper::getParams('com_donorforce')->get('addcurrency') ?></i></button>
                <input type="text" name="amount" id="amount"  class="form-control required" />
                <button type="button" class="btn decimal"  aria-invalid="false"><i class="">.00</i></button>
                 
              </div>
              </div>
                  
                  
              <div class="control-group">
                <div><label>Payment Gateway : </label>
                		<span>PayGate</span>
                </div>
              </div>    
                  
                  
            	<div class="control-group">
                <div>
                 <label><input type="checkbox" name="check00" id="check00" value="">Accept  <a onclick="popupCenter('<?php echo JRoute::_('index.php?option=com_content&view=article&id='.JComponentHelper::getParams('com_donorforce')->get('terms_articleid').'&tmpl=component'); ?>', 'myPop1',450,450);" href="javascript:void(0);" style="color:#FC6;"> 
                 	Terms and Conditions</a></label>
                 </div>
              </div>
               
                
              <div class="control-group">
                	<button type="submit"  class="btn btn-small btn-primary" ><?php echo JText::_('Submit'); ?></button>
              </div>
       
       
              <input type="hidden" name="task" value="easygiving.donate" /> 
              <input type="hidden" name="donor_id" value="<?php //echo $donor_id;  ?>"  />
              <input type="hidden" name="project_id" value="<?php //echo JRequest::getVar('project_id');  ?>"  />
          		<?php echo JHtml::_('form.token'); ?>
	  </fieldset>
</form>
</div>

<script>

function popupCenter(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}

jQuery('document').ready(function(e){ 
	
});
</script>
<style type="text/css">
#easygiving_Donation input[type="email"],
#easygiving_Donation input[type="text"]{ width:auto;     min-width: 50%; }
#easygiving_Donation .control-group{  margin-bottom:20px;} 
button.btn.decimal {
	border-bottom-left-radius: 0px;
  border-top-left-radius: 0px; 
} 
button.btn.curreny {
	border-bottom-right-radius: 0px;
  border-top-right-radius: 0px; 
}
.amount_input input[type="text"] { 
		margin-right: -4px;
    margin-left: -4px;
    border-radius: 0px;
    height: 33px;
		min-width: 41.5% !important;
}  
.amount_input button,.amount_input input[type="text"]{  vertical-align:top; display:inline-block; }
 
</style>