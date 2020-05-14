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

$str = "merchant_id=10004918&merchant_key=46f0cd694581a";
$md5 = md5($str);

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

<?php 

/*JPluginHelper::importPlugin('payment', 'payfast');
$dispatcher = JDispatcher::getInstance();
$result = $dispatcher->trigger('getCallbackURL');
echo " <pre>  result  ";  print_r( $result  ); echo " </pre> "; */  
			


?>

<div id="donation">
<?php /*?><form class="form-validate" action="<?php echo $url; ?>"  method="post" id="easygiving_Donation" name="easygivingDonation">	<?php */?>
<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="easygiving_Donation" name="easygivingDonation">			
          <fieldset> 
          
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
                <div><input type="text" name="amount" id="amount"  class="form-control required" /></div>
              </div>
                  
                  
              <div class="control-group">
                <div><label>Payment Method: </label>
                		<span>PayFast</span>
                </div>
              </div>    
                  
                  
            	<div class="control-group">
                <div>
                 <label><input type="checkbox" name="check00" id="check00" value="">Accept  <a onclick="popupCenter('<?php echo JRoute::_('index.php?option=com_content&view=article&id='.JComponentHelper::getParams('com_donorforce')->get('terms_articleid').'&tmpl=component'); ?>', 'myPop1',450,450);" href="javascript:void(0);" style="color:#FC6;"> 
                 	Terms and Conditions</a></label>
                 </div>
              </div>
 
   
              <input type="hidden" name="task" value="easygiving.donate_payfast" /> 
             	<?php echo JHtml::_('form.token'); ?>
               
                
              <div class="control-group">
                	<button type="submit"  class="btn btn-small btn-primary" ><?php echo JText::_('Submit'); ?></button>
              </div>
       				
 
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

</style>