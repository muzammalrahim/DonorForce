<script>
jQuery(document).ready(function(e) {
 jQuery( "#history").hide();
 var cancel=0;
 jQuery("#btn-history")  .on('click',function(e){
	 e.preventDefault();
	
	 jQuery('#subscriptions').slideUp("slow");
	 jQuery('#history').slideDown("slow");
	 })
	 
 jQuery("#btn-sub")  .on('click',function(e){
	 e.preventDefault();
	
	 jQuery('#subscriptions').slideDown("slow");
	 jQuery('#history').slideUp("slow");
	 })
 jQuery(".btn-danger").on("click",function(){
	 
	cancel=jQuery(this).data('id'); 
	
	 })
	jQuery("#yes-cancel").on("click",function(){
		
		var links ="index.php?option=com_donorforce&task=donations_record.deleteSubscription&id="+cancel;
		window.location.replace(links)
	})
});
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
if($user->get('guest'))
{
	$this->app->redirect('index.php?option=com_donorforce&view=projects',"Please login First");
	return;
} 
?>
<div id="subscriptions">
<h2>Donation Subscription</h2><div class="pull-right"><button id="btn-history" class="btn btn-primary">History</button></div>

 <table class="table table-striped">
                        	<thead>
                            	<tr>
                                	<th>Project ID</th>
                                    <th>Project Name</th>
                                    <th>Donation Type</th>
                                    <th>Source</th>
                                    <th>Amount</th>
                                     <th>&nbsp;</th>
                                </tr>
                            </thead>
                        	 <tbody>
                            <?php
									 //var_dump($this->subscriptions);									 
									 if(!empty($this->subscriptions))
									 foreach($this->subscriptions as $sub)
									 {
										?>
                              <tr>
                              	<td>
												<?php if(!empty($sub->project_id))echo $sub->project_id; ?>
                                 </td>
                                 <td>
												<?php if(!empty($sub->project_name))echo $sub->project_name; ?>
                                 </td>                                 
                                 <td>
                                 <?php 
								 echo ucwords($sub->donation_type);
								 /* ?>
                                 	<select class="subscription_type" id="sub_<?php echo @$sub->subscription_id; ?>" >
                                    	<option value="once-off" <?php if($sub->donation_type=='once-off')echo ' selected="selected" '; ?>>
                                       	Once-off
                                       </option>
                                       <option value="monthly" <?php if($sub->donation_type=='monthly')echo ' selected="selected" '; ?>>
                                       	Monthly
                                       </option>
                                       <option value="six-monthly" <?php if($sub->donation_type=='six-monthly')echo ' selected="selected" '; ?>>
                                       	Six Monthly
                                       </option>
                                       <option value="annually" <?php if($sub->donation_type=='annually')echo ' selected="selected" '; ?>>
                                       	Annually
                                       </option>
                                       <option value="bequest" <?php if($sub->donation_type=='bequest')echo ' selected="selected" '; ?>>
                                       	Bequest
                                       </option>
                                    </select>
									<?php */ ?>
                                 </td>
                                 <td>
												<?php if(!empty($sub->source))echo ucwords($sub->source); ?>
                                 </td>
                                 <td>
												<?php if(!empty($sub->amount))echo  DonorforceHelper::getCurrency().' '.DonorForceHelper::displayAmount($sub->amount); ?>
                                 </td>
                                 <td><a href="#" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="<?php echo $sub->subscription_id; ?>" class="btn btn-danger">Cancel </a></td>
                              </tr>                              
                              <? 
									 }
									 ?>
                                     
                            </tbody>
                            
                        </table>
    

</div>
 <!-----POP UP----->
      <div class="modal fade bs-example-modal-lg alert " id="myModal" style="width:350px; padding:50px"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
          <div class="modal-content ">
            <input type="hidden" id="hiddenPath" />
            <input type="hidden" id="fullPath" />
            
            <div id="hiddenId"></div>
            <b >Are you sure , you want to delete it?</b><br />
            <br />
            <button  onclick="" id="yes-cancel" type="button" class="btn btn-success">Yes</button>
            &nbsp;
            <button  class="btn btn-primary" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
      <!-----/POP UP----->
<div id="history">
<h2>Donation History</h2>
<div class="pull-right"><button id="btn-sub" class="btn btn-primary">Subscriptions</button></div>
  <table class="table table-striped">
                        	<thead>
                            	<tr>
                                	<th>Date</th>
                                    <th>Project</th>
                                    <th>Amount</th>
                                    <th>Donation Status</th>
                                </tr>
                            </thead>
                            <tbody>
									 <?php									 
									 if(!empty($this->history))
									 foreach($this->history as $history)
									 {
										?>
                              <tr>
                              	<td>
												<?php if(!empty($history->date))echo date('Y-m-d',strtotime($history->date)); ?>
                                 </td>
                                 <td>
												<?php if(!empty($history->project_name))echo $history->project_name; ?>
                                 </td>
                                 <td>
												<?php if(!empty($history->amount))echo  DonorforceHelper::getCurrency().' '.DonorForceHelper::displayAmount($sub->amount); ?>
                                 </td>
                                 <td>
                                 
                                 <?php echo ucwords($history->status); 
								 /*?>
                                 	<select class="history_status" id="<?php echo @$history->donor_history_id; ?>" >
                                    	<option value="pending" <?php if($history->status=='pending')echo ' selected="selected" '; ?>>
                                       	Pending
                                       </option>
                                       <option value="successful" <?php if($history->status=='successful')echo ' selected="selected" '; ?>>
                                       	Successful
                                       </option>
                                    </select>	
								<?php */ ?>											
                                 </td>
                              </tr>                              
                              <? 
									 }
									 ?>
                            </tbody>                            
                        </table>
                        </div>