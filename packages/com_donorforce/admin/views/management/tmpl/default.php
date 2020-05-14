<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
defined('_JEXEC') or die;
 
JHTML::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select.chosen');
$document = JFactory::getDocument();
$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

$canOrder	= $user->authorise('core.edit.state', 'com_donorforce.donors');
$saveOrder	= $listOrder == 'dh.date';
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
function resetForm(){
		//document.getElementById('donor_list')
		jQuery("#donor_list option:selected").attr("selected", false);
		jQuery("#project_list option:selected").attr("selected", false);
		jQuery("#donation_status option:selected").attr("selected", false);		
		jQuery("#search_datefrom").val('');
		jQuery("#search_dateto").val('');		
}
</script> 
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		/* console.log('order'+ order);
		console.log('direction'+ direction); */
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
	jQuery(document).ready(function () {
		jQuery('#clear-search-button').on('click', function () {
			resetForm();
			jQuery('.js-stools-btn-clear').trigger('click');
		});
	});
</script>
<style>
.js-stools.clearfix {
    display: inline-block;
    width: auto;
	padding: 4px;
	margin-top: 4px;
}
.js-stools .btn-wrapper {
    margin: 0 5px 0 0;
}
.js-stools-btn-clear{
	display: none;
}
#jform_search_datefrom-lbl, #jform_search_dateto-lbl {
    margin-top: -15px;
}
label {
    margin-bottom: 0px;
}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_donorforce&view=management'); ?>" method="post" name="adminForm" id="adminForm">
  <!-- Filters -->
        <div id="filter-bar" class="btn-toolbar">
         <div class="block_content">
         <select data-placeholder="Choose a Donor"  name="donor_list" id="donor_list" class="chosen chosen-select">
         	<option value=""></option>
           <?php  if(!empty($this->Donor_list)):
		   		 foreach ($this->Donor_list as $Donor_list) : ?>
              <option value="<?php if(!empty($Donor_list->donor_id)) echo $Donor_list->donor_id ; ?>" <?php if($this->donor_id == $Donor_list->donor_id){  echo "selected='selected'"; }  ?> >
			  <?php 
			  if(!empty($Donor_list->name_first)){ echo $Donor_list->donor_id.' - '.$Donor_list->name_first;} 
			  if(!empty($Donor_list->name_last)) { echo " ".$Donor_list->name_last; }?>
              </option>
           <?php endforeach; endif; ?>
         </select>
         </div>
  		<div class="block_content">
  		<select data-placeholder="Choose a Project"  style="padding:25px;" name="project_list" id="project_list"  class=" chosen chosen-select"> 
          <option value=""></option>
           <?php  if(!empty($this->Project_list)):
		   		  foreach ($this->Project_list as $Project_list) : ?>
              <option value="<?php if(!empty($Project_list->project_id)) echo $Project_list->project_id; ?>" <?php if($this->project_id == $Project_list->project_id){  echo "selected='selected'"; }?>>
			  <?php if(!empty($Project_list->name)) echo $Project_list->project_id.' - '.$Project_list->name;?>
              </option>
           <?php endforeach; endif;  ?>
         </select>
  		</div>	
        <div class="block_content">
  		<select data-placeholder="Status"  style="padding:25px;" name="donation_status" id="donation_status"  class=" chosen chosen-select"> 
          <option value="">Select status</option>
          <option <?php if($this->donation_status == "pending"){ echo "selected='selected'"; } ?> value="pending">Pending</option>
          <option <?php if($this->donation_status == "successful"){ echo "selected='selected'"; } ?> value="successful">Successful</option>
          
          <option <?php if($this->donation_status == "ignore"){ echo "selected='selected'"; } ?> value="ignore">Ignore</option>
          
         </select>         
      </div>
      
     
      
      <div class="block_content" style="margin-top: 6px;">
  			<label id="jform_search_datefrom-lbl" for="search_datefrom" class="">Select Date From </label> 
				<?php echo JHTML::calendar($this->searchDateFrom,'search_datefrom', 'search_datefrom', '%Y-%m-%d',
					array('size'=>'4','maxlength'=>'5','class'=>' '));?>
      </div>
      <div class="block_content" style="margin-top: 6px;">
        <label id="jform_search_dateto-lbl" for="search_dateto" class="">Select Date To </label> 
				<?php echo JHTML::calendar($this->searchDateTo,'search_dateto', 'search_dateto', '%Y-%m-%d',
					array('size'=>'4','maxlength'=>'5','class'=>' '));?>                    
  		</div>
      
      
	<div class="block_content float_left" style="margin-top: 4px;">
	  	<button type="button" class="btn stools-btn-clear" title="" data-original-title="Clear" id="clear-search-button">
			Clear			
		</button>
	</div>
	<div class="block_content float_left" style="margin-top: 4px;">
		<button type="submit" class="btn hasTooltip" title="Search" aria-label="Search">
			Search
		</button>
	</div>
	  
	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
      <div class="block_content float_right">
        <div class="btn-group pull-right">
          <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
          <select name="sortTable" id="sortTable" class="chosen input-medium" onchange="Joomla.orderTable()">
            <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
            <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
          </select>
        </div>
      </div>
      
      <div class="block_content float_right">
       <div class="btn-group pull-right hidden-phone">
          <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
          <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
            <option value="asc" <?php if ($listDirn == 'asc')
            {
              echo 'selected="selected"';
            } ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
            <option value="desc" <?php if ($listDirn == 'desc')
            {
              echo 'selected="selected"';
            } ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
          </select>
        </div>
      </div>
     
      <!-- <div class="block_content float_right"> 
      <div class="btn-group pull-right hidden-phone">
          <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
          <?php echo $this->pagination->getLimitBox(); ?>
      </div>
      </div> -->
            
    </div>
   
  <div class="clr"> </div>
	<div class="form-horizontal">
  <table class="table table-striped" id="HistoryTable">
    <thead>
      <tr>
      <th width="3%" class="center"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);;" /></th>      <th><?php echo JHtml::_('grid.sort', 'ID', 'dh.donor_history_id', $listDirn, $listOrder); ?></th>
      <th width="10%" ><?php echo JHtml::_('grid.sort', 'Date', 'dh.date', $listDirn, $listOrder); ?></th>
      <th><?php echo JHtml::_('grid.sort', 'Reference', 'dh.Reference', $listDirn, $listOrder); ?></th>
      <th><?php echo JHtml::_('grid.sort', 'Project', 'p.name', $listDirn, $listOrder); ?></th>
      <th><?php echo JHtml::_('grid.sort', 'Donor', 'd.name_first', $listDirn, $listOrder); ?></th>
      <th width="100"><?php echo JHtml::_('grid.sort', 'Amount', 'dh.amount', $listDirn, $listOrder); ?></th>
      <th><?php echo JHtml::_('grid.sort', 'Donation Status', 'dh.status', $listDirn, $listOrder); ?></th>
      <th>Operation</th>
      </tr>
    </thead>
    <tbody>
		<?php	//echo "<pre>";  print_r(  $this->history[0] ); 								 
     if(!empty($this->items))
			//echo "<pre> history = "; print_r($this->history); 
     $counter = 0; //$number = 0;
     foreach($this->items as $i => $item)
     { 
	 
	 //echo " <pre>  item  ";  print_r( $item ); echo " </pre> ";   exit; 
		   
      ?>
      <tr>
      <?php echo '<td class="center">'. JHtml::_('grid.id', $i, $item->donor_history_id) . '</td>'; ?>
      <td><?php if(!empty($item->donor_history_id))echo $item->donor_history_id; ?> </td>
      <td><?php if(!empty($item->date))echo date('Y-m-d',strtotime($item->date)); ?></td>
      <td><?php if(!empty($item->Reference))echo $item->Reference; ?></td>
      <td><?php if(!empty($item->project_name))echo $item->project_id.' - '.$item->project_name; ?></td>
      <td><?php //if(!empty($history->name_title))echo $history->name_title." ";  
    		if(!empty($item->donor_id))echo $item->donor_id." - "; 
			if(!empty($item->name_first))echo $item->name_first." "; 
    		if(!empty($item->name_last))echo $item->name_last." "; ?>
      </td>
      <td><?php if(!empty($item->amount)) echo DonorforceHelper::getCurrency().' '.number_format($item->amount, 2, '.', ',');
                  /*  $total_donation +=  $history->amount;*/  ?></td>
      <td><?php //echo strtolower($item->status).'- '; 
	  			
				echo'<select class="history_status" id="hid'.$item->donor_history_id.'" >
					<option value="pending" '.((strtolower ($item->status) === "pending")?'selected':'').' >Pending</option>
					<option  value="successful" '.((strtolower ($item->status) === "successful")?'selected':'').' >Successful</option>
					<option  value="ignore" '.((strtolower ($item->status) === "ignore")?'selected':'').'>Ignore</option>
					</select>
					';									   
	  ?></td>   
      
      <td>
       <button onclick="" id="relocate-<?php echo $counter;  ?>" class="btn btn-small relocate">Reallocate</button>
		  
      <a href="index.php?option=com_donorforce&view=management&layout=split&donaiton_history_id=<?php echo $item->donor_history_id; ?>&tmpl=component" class="modal btn btn-small" rel="{size: {x: 750, y: 500}, handler:'iframe'}"> Split </a>
      
      <!-- <button onclick="" id="split-<?php //echo $counter;  ?>" data-donaitonID="<?php //echo $item->donor_history_id; ?>" class="btn btn-small split">Split</button>-->
      
       <button id="update_donor-<?php echo $counter; ?>" class="btn btn-small update_donor">Update Record</button>
       <button onclick="" id="relocate_cancel-0" class="btn btn-small relocate_cancel">Cancel</button>
       <input type="hidden" id="donor_history_id-<?php echo $counter; ?>" value="<?php echo $item->donor_history_id; ?>" />					
       <div id="ajaxresult-<?php echo $counter;  ?>"></div> 
      </td>
                                     
                       
      </tr>                              
      <?php $counter++;  
} ?>
   </tbody>               
</table>  
  <?php echo $this->pagination->getListFooter(); ?>		  	           
</div>
             
 
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
</form>


<div class="split_ajax"></div>


<style type="text/css">
tr.calendar-head-row {
    font-size: 13px;
}

.chzn-container-single .chzn-single{     
  height: 26px; 
  line-height: 26px;
  font-weight: bold;
  color: green; 
  border-radius:0px; 
  width: 100%; 
}
.btn-group.hidden-phone button{ border-radius:0px !important;   }  
.chzn-single { border-radius:0px !important; height:26px;  }
.block_content{ display:inline-block; vertical-align: top; padding: 4px; margin-top: 5px;}
.btn-toolbar{ margin-left: 10px; }
#search_datefrom, #search_dateto { max-width: 100px;}
#jform_search_datefrom-lbl, #jform_search_dateto-lbl{ margin-top:-20px; }
#filter-bar{ height:auto !important; }

</style> 

<script type="text/javascript">
jQuery( document ).ready(function(){
	
	
//-----AJAX call to display list of donors for donation Realocation. 
jQuery("#HistoryTable").on("click", ".relocate", function(e){
	 e.preventDefault();  //return false;  	
	 var counter = jQuery(this).attr('id');
	 var arr = counter.split('-');
	 counter = arr[1]; 
	 jQuery.ajax({
	   url : 'index.php?option=com_donorforce&task=ajax.GetDonorsData&format=raw',
	   type: "GET",
	   dataType: 'text',
	   success: function(data) {
		 jQuery('#ajaxresult-'+counter).html( data  );
		 jQuery("#update_donor-"+counter).show();			 
		 jQuery("#relocate_cancel-"+counter).show();			 
		 jQuery("#ajaxresult-"+counter+" .chosen-select").chosen();
		}
	});						
});


//----AJAX call to Reallocate the donation to another selected donor.
jQuery("#HistoryTable").on("click", ".update_donor", function(e){
  e.preventDefault();
	var counter = jQuery(this).attr('id');
  var arr = counter.split('-');
  counter = arr[1]; 
          	
	jQuery.ajax({
	   url : 'index.php?option=com_donorforce&task=ajax.update_donor_history&format=raw',
	   type: "POST",
	 	 data: { donor_id: jQuery("#ajaxresult-"+counter+" select#jform_donor_id option").filter(":selected").val(),
	 		 			 project_id: jQuery("#ajaxresult-"+counter+" select#jform_project_id option").filter(":selected").val(),  
	         	 donor_history_id: jQuery("#donor_history_id-"+counter).val() 
			},	 
	   success: function(data) {
		   if(data == '1'){  
		   		//alert('Donation Updated Successfully');
					var current_donor = jQuery("#ajaxresult-"+counter+" select#jform_donor_id option").filter(":selected").text(); 
					var current_project  = jQuery("#ajaxresult-"+counter+" select#jform_project_id option").filter(":selected").text(); 
					
					jQuery("#ajaxresult-"+counter).parent( "td" ).parent( "tr" ).find('td:nth-child(4)').html(current_project);
					jQuery("#ajaxresult-"+counter).parent( "td" ).parent( "tr" ).find('td:nth-child(5)').html(current_donor);
					jQuery("#ajaxresult-"+counter).html('');
					jQuery('#update_donor-'+counter).hide();
					jQuery('#relocate_cancel-'+counter).hide();
					
		    }
		   else{
		 		alert('Error Updated Donation '+data);
		   }
		}
	});	
});
	
	
//Relocate Cancel Button 	
jQuery("#HistoryTable").on("click", ".relocate_cancel", function(e){
		e.preventDefault();
		var counter = jQuery(this).attr('id');
  	var arr = counter.split('-');
  	counter = arr[1]; 
		jQuery("#ajaxresult-"+counter).html('');
		jQuery('#update_donor-'+counter).hide();
		jQuery('#relocate_cancel-'+counter).hide();
}); 	
	
	
//-- Hide all update button on default
jQuery(".update_donor").hide();
jQuery(".relocate_cancel").hide();


//spliting of donation record functionality  
jQuery('.split').live('click',function(e){
	e.preventDefault();
	console.log(' split click ');
	var donaiton_history_id = jQuery(this).attr('data-donaitonid');
	console.log(' donaiton_history_id  ='+donaiton_history_id);
	jQuery.ajax({
	   url : 'index.php?option=com_donorforce&task=ajax.split_donor_history&format=raw',
	   type: "GET",
	 	 data: { donaiton_history_id: donaiton_history_id},	 
	   success: function(data) {
			  console.log(' success data  ');
				console.log(data);
				jQuery('.split_ajax').html(data);
				
		   if(data == '1'){  
		   		//alert('Donation Updated Successfully');				 
		    }
		   else{
		 		//alert('Error Updated Donation '+data);
		   }
		}
	});	
	
	
});
//split click function end 



jQuery("#HistoryTable").on("change", ".history_status", function(e){
	e.preventDefault();  
	//console.log("changing history status"); return false; 
	var id=this.id;
	var status=jQuery('#'+id).val();
	if(status == 'successful'){
		url='index.php?option=com_donorforce&task=ajax.changeHistoryStatus&format=raw';
		jQuery.post(url, {"history_id":id,"status":status},function(data){
			alert(data);
		});
	}
		
});


});//document ready  

SqueezeBox.addEvent('onClose', function() {
    window.location = 'index.php?option=com_donorforce&view=management';
});

</script>

<style>
#donor_list_chzn,#project_list_chzn{ /* min-width: 280px; */ }
.float_right{ float:right; }
</style>