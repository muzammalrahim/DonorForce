<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select.chosen');
?>
<style>
</style>
<?php

$donaiton_history_id =  JRequest::getVar('donaiton_history_id',0);	 
if($donaiton_history_id > 0){
	$db = JFactory::getDBO();
	$db->getQuery(true);
	$query = $db->getQuery(true);
	$query->select('dh.*,d.name_title,d.name_first,d.name_last,p.name as project_name');
	$query->from('#__donorforce_history AS dh');
	$query->join('LEFT', '#__donorforce_donor AS d ON d.donor_id=dh.donor_id');
	$query->join('LEFT', '#__donorforce_project AS p ON p.project_id=dh.project_id');
	$query->where('dh.donor_history_id = ' . (int) $donaiton_history_id);
	$db->setQuery($query);
	$donation_data = $db->loadAssoc();
	
	//echo "<pre> return_data "; print_r( $return_data  ); echo "</pre>";  
	
	?>
  <div class="split_cont"> 
	<div> <p> Donation Details </p>  
		<p> Date = <?php echo $donation_data['date'];  ?></p>
		<p> Total Amount = <?php echo  $donation_data['amount']; ?></p> 
		<p> Reference = <?php echo  $donation_data['Reference'];  ?></p> 	
    
	</div> 
	</div> 
	
	<script type="text/javascript">
	jQuery(document).ready(function(event){
		jQuery('.row_list select').chosen();
		
		var total_amount = parseInt('<?php echo $donation_data['amount']; ?>'); 
		
		
		 
		jQuery('.row_list input:text.amount').live('focusout', function() {
			
			jQuery('.row_list .amount').css({ 'border': '0px' }); 
			 //jQuery(this).css({ 'background': 'red' }); 
			 var amount_count = 0;
			 jQuery('.row_list input:text.amount').each(function(){
					var amount  = parseInt( jQuery(this).val() );
					if(!isNaN(amount)){
						console.log(' this value =  '+amount_count );
						amount_count += amount; 
					}
				});
				console.log(' amount_count = '+amount_count);		
				
				if( amount_count  > total_amount ){
						//alert('Maximun Amount limit Exceed');
						jQuery('.row_list .amount:last').css({ 'border': '1px solid red' }); 
						//jQuery('.row_list .amount:last').val(''); 
						
				} 
    });
   
		
		
		/*jQuery('input:text.amount').live('blur',(function(){
			jQuery(this).css({ 'background': 'red' });
			 var amount_count = 0; 
			 jQuery('input:text.amount').each(function(){
					amount_count += jQuery(this).val();
					console.log(' this value =  '+amount_count );
				});
			
		})); 
		*/
		 
		
	
	}); 
	
	function removeRow(element){
			jQuery(element).closest('div.row').remove();	
	}
	
	function addRow(){ 
		//document.getElementById("row_list").innerHTML += document.getElementById("add_row_sample").innerHTML;
		jQuery('.row_list').append(jQuery('#add_row_sample').html());
		
		jQuery('.row_list select').chosen();		
		
		jQuery('.row_list .chzn-done').each(function(){
			//jQuery(this).trigger('chosen:updated');		
				
		});
	} 
	</script> 
	
	<?php
	 $Return_DonorsList = DonorforceHelper::Return_DonorsList();
	 $Return_ProjectsList = DonorforceHelper::Return_ProjectsList(); 
	?>
	<div id="add_row_sample" style="display:none;">
  	<div class="row">
		<?php  
				echo $Return_DonorsList;
				echo $Return_ProjectsList; 
		?>
    	<div class="amount_list">
      		<label class="labl_select_amount"> Amount </label>
      		<input type="text"  value=""  name="jform[amount][]"  class="amount" />
      	</div>
        
        <div class="status_list"> 
              <select name="jform[status][]" class="status" >
              	<option value="pending">Pending</option>
                <option value="successful" selected="selected">Successful</option>
                <option value="ignore">Ignore</option>
              </select>
        </div>
        
      <button name="remove" class="remove_btn" onclick="removeRow(this); return false; ">
      	<span class="icon-delete "></span>
      </button>
       <hr/>
    </div>
  
  </div> 
	<hr />
  <?php
}
?>
 


  <form action="<?php echo JRoute::_('index.php?option=com_donorforce&task=ajax.split_test&format=raw'); ?>" method="post" name="adminForm" id="project-form" class="form-validate" enctype="multipart/form-data">
<div class="row-fluid">
    <div class="span9 form-horizontal">        
        <fieldset class="adminform">	
        	<button onclick=" addRow() ;return false; ">Add Row </button>
           
           <div class="row_list">
           		<div class="row">
				<?php 
					echo $Return_DonorsList;
                 	echo $Return_ProjectsList; 
				?>
              <div class="amount_list">
              <label class="labl_select_amount"> Amount </label>
              <input type="text"  value=""  name="jform[amount][]" class="amount"  />
              </div>
              
              <div class="status_list">
              <select name="jform[status][]" class="status" >
              	<option value="pending">Pending</option>
                <option value="successful" selected="selected">Successful</option>
                <option value="ignore">Ignore</option>
              </select>
              
              </div>
              
              <button name="remove" class="remove_btn" onclick="removeRow(this); return false; ">
              <span class="icon-delete "></span>
              </button>
               <hr />
              </div>
             
           </div> 
                           
        </fieldset>
    </div>
</div>
<input type="hidden" name="history_id" value="<?php echo $donaiton_history_id; ?>" />
<input class="btn btn-success" type="submit" value="Submit"/>
<input type="hidden" name="task" value="ajax.split_test" />
<?php echo JHtml::_('form.token'); ?>
</form>
  
</div>

<style>
body{  margin:00px; }
tr.calendar-head-row {
    font-size: 13px;
}
.donor_list, .project_list, .amount_list, .status_list{display: inline-block; margin-bottom:10px;}
.amount_list .amount {max-width: 100px;    background: rgba(0,0,0,.05);}
.status_list .chzn-container{ width: 100px !important; }
.remove_btn{/* float:right;*/ }
</style>