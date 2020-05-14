<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.modal');

$document = JFactory::getDocument();


$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_donorforce.subscriptions');
$saveOrder	= $listOrder=='a.subscription_id';
		
//Get the Subscription Type 
$subscription_type	= $this->escape($this->state->get('filter.subscription_type'));
//Get the Deduction Date 
$Deduction_Date	= $this->escape($this->state->get('filter.Deduction_Date')); 
?>
<style type="text/css">
	.js-stools-container-list{display: none !important; }
</style>
<script type="text/javascript">
Joomla.submitbutton = function(task)
 { 
 console.log(' task =  '+task);
  if ( (task == 'subscriptions.export') && (document.getElementsByName('task')[0].value=='')){
        Joomla.submitform(task, document.getElementById('adminForm')); 
  }else if( (task == 'subscriptions.exportDO') && (document.getElementsByName('task')[0].value=='')){
        Joomla.submitform(task, document.getElementById('adminForm')); 
  }else if(task == 'subscriptions.import'){
			Joomla.submitform(task, document.getElementById('adminForm')); 
	}else if( (task=='subscription.add') || (task=="subscription.edit")  || (task=="subscriptions.delete") || (task=="subscriptions.unpublish") ||  (task=="subscriptions.publish") ){
		
		Joomla.submitform(task, document.getElementById('adminForm')); 
	}	 
		
		document.getElementsByName('task')[0].value=''
		
 }
</script>



<form action="<?php echo JRoute::_('index.php?option=com_donorforce&view=subscriptions'); ?>" method="post" name="adminForm" id="adminForm">
	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
  <div id="filter-bar">
		<div class="filter-select fltrt pull-right">
      <select name="filter_subscription_type" class="inputbox" onchange="this.form.submit()">
        <option value=""><?php echo "Subscription Type"; ?></option>
        <option value="RecurringDO" <?php echo ($subscription_type == 'RecurringDO')? 'selected="selected"':''; ?>>
						<?php echo 'RecurringDO'; ?>
         </option>        
         <option value="RecurringCO" <?php echo ($subscription_type == 'RecurringCO')? 'selected="selected"':''; ?>>
						<?php echo 'RecurringCO'; ?>
         </option>                      	
			</select>   
      
       <select name="filter_Deduction_Date" class="inputbox" onchange="this.form.submit()">
       		<option value=""><?php echo "Deduction Date"; ?></option>      
          <option value="1"  <?php echo ($Deduction_Date == '1')? 'selected="selected"':''; ?>>1st</option>
          <option value="6"  <?php echo ($Deduction_Date == '6')? 'selected="selected"':''; ?>>6th</option>
          <option value="10" <?php echo ($Deduction_Date == '10')? 'selected="selected"':''; ?>>10th</option>
          <option value="25" <?php echo ($Deduction_Date == '25')? 'selected="selected"':''; ?>>25th</option>
          <option value="30" <?php echo ($Deduction_Date == '30')? 'selected="selected"':''; ?>>30th</option>
			</select>  
        
		</div>
	</div>

  
  
	<div class="clr"> </div>
	

	<div id="editcell">
		<table class="table table-striped" id="categoryList">
			<thead>
				<tr>
					
					<th width="2%">
                    	<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                    </th>
					
					<th class="title" width="10%" align="left" style="text-align:left;">                    
                    	<?php echo JHTML::_( 'grid.sort', 'ID', 's.subscription_id', $listDirn, $listOrder); ?>
                    </th>
                    <th class="title" width="20%" align="left" style="text-align:left;">Name</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Subscription Type</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Amount</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Project Name</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Start Date</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">End Date</th>
                    <!--<th class="title" width="10%" align="left" style="text-align:left;">Status</th>-->
                    				
				</tr>
			</thead>
			
			<tbody><?php
				
$j = 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
	
	//We can unset the values in array or filter here
	//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
		//$j++;
$item->max_ordering = 0; //??		
$ordering	= ($listOrder == 'a.ordering');			
$canCreate	= $user->authorise('core.create', 'com_donorforce.subscriptions.');
$canEdit	= $user->authorise('core.edit', 'com_donorforce.subscriptions.');
$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange	= $user->authorise('core.edit.state', 'com_donorforce.subscriptions.'.$item->subscription_id);
$linkEdit 	= JRoute::_( 'index.php?option=com_donorforce&task=subscription.edit&subscription_id='. $item->subscription_id );

					
echo '<tr class="row'. $i % 2 .'">';
					
echo '<td class="center">'. JHtml::_('grid.id', $i, $item->subscription_id) . '</td>';

echo '<td>';
echo  $item->subscription_id;
echo '</td>';					

echo '<td>'; 
if ($canCreate || $canEdit) {
	echo '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->name).'</a>';
} else {
	echo $this->escape($item->name);
}
echo '</td>'; 

echo '<td>';
echo  ucwords($item->donation_type);
echo '</td>';

echo '<td>';
if(!empty($item->amount))  echo  DonorforceHelper::getCurrency().' '.DonorforceHelper::displayAmount($item->amount);
echo '</td>';

echo '<td>';
echo $item->pname;
echo '</td>';

echo '<td>';
//echo $item->date_start;
echo $item->donation_start_date;
echo '</td>';

echo '<td>';
//echo $item->date_end;
echo $item->donation_end_date;
echo '</td>';

/*echo '<td>';
echo ucwords($item->status);
echo '</td>';*/

/*
echo '<td class="center">'. JHtml::_('jgrid.published', $item->published, $i, 'subscriptions.', $canChange) . '</td>';

*/
?>
<?php
//echo '<td align="center">'. $item->hits.'</td>';

echo '</tr>';						
		//}
	}
}
echo '</tbody>';
?>			
			<tfoot>
				<tr>
					<td colspan="45"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>