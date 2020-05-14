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

$insertID = JRequest::getVar('sid','');
//echo " <pre>   insertID  ";  print_r( $insertID ); echo " </pre> ";   


if(!empty($insertID)){
			$db = JFactory::getDbo();		
				$inserted_Data = "			
				SELECT
				#__donorforce_project.`name` AS project_name,
				#__donorforce_history.*,
				#__donorforce_donor.name_first,
				#__donorforce_donor.name_last
				
				FROM
					#__donorforce_history
				INNER JOIN #__donorforce_project ON #__donorforce_history.project_id = #__donorforce_project.project_id
				INNER JOIN #__donorforce_donor ON #__donorforce_donor.donor_id = #__donorforce_history.donor_id
				WHERE
					#__donorforce_history.donor_history_id IN (".implode(',',$insertID).")";
					
				$db->setQuery($inserted_Data);
				$insertedRecords = $db->loadAssocList();
				//echo " <pre>  inserted_Data  ";  print_r( $inserted_Data ); echo " </pre> ";   	
				//echo " <pre>  insertedRecords  ";  print_r( $insertedRecords ); echo " </pre> ";
				   
				
				echo '<h3>Splitting Done succesfully</h3>';
				echo '<p>New Donation Created</p>
					<table class="table table-striped">
					<thead>
					  <tr>
					  <th>ID</th>
					  <th>Date</th>
					  <th>Reference</th>
					  <th>Project</th>
					  <th>Donor</th>
					  <th>Amount</th>
					  <th>Donation Status</th></tr>
					</thead><tbody>';
				
				foreach($insertedRecords as $insertedRecord){
					echo '<tr><td>'.$insertedRecord['donor_history_id'].'</td>';
					echo '<td>'.$insertedRecord['date'].'</td>';
					echo '<td>'.$insertedRecord['Reference'].'</td>';
					echo '<td>'.$insertedRecord['project_name'].'</td>';
					echo '<td>'.$insertedRecord['name_first'].' '.$insertedRecord['name_last'].'</td>';
					echo '<td>'.$insertedRecord['amount'].'</td>';
					echo '<td>'.$insertedRecord['status'].'</td>';
					echo '<td><button class="email_succesfull_split" id="email_'.$insertedRecord['donor_history_id'].'" data-dhid="'.$insertedRecord['donor_history_id'].'" >Send Email</button></td>';
					echo '</tr>'; 
				}
				echo '</tbody></table>';  
				
				
				 
				 
				
			}
?>
 
<script>
jQuery(document).on('click','.email_succesfull_split',function(event){
	 
	var dhid = jQuery(this).data("dhid"); 
	 
	   jQuery('#email_'+dhid).html('<span>Processing</span>');
	  
	   jQuery.ajax({
	   url : 'index.php?option=com_donorforce&task=ajax.sendEmail&format=raw',
	   data: { hid: dhid },
	   type: "POST",
	   dataType: 'text',
	   success: function(data) {
		   
		   jQuery('#email_'+dhid).html(data);
			
		   console.log( ' success  ' );
		    console.log( data ); 
			
		  
		   
		    
		}
	});			
	 
	
	
	
});
</script>
