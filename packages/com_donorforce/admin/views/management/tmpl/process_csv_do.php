<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.filesystem.folder' );
jimport('joomla.filesystem.file');
JHtml::_('formbehavior.chosen', '.chosen select');
JHTML::_('behavior.modal');
?>

<script type="text/javascript">
Joomla.submitbutton = function(task)
{
	if (task == ''){
		return false;
	}
	else if(task == 'management.clear_data'){
		 console.log( ' management.clear_data ' );
		 jQuery('.import_table tbody tr.tr_disabled').remove(); 
		 jQuery('.import_table tbody tr td.dublicate').parent().remove();
		 jQuery('.import_table tbody tr td.tr_info').parent().remove();
		
	}else if(task == 'management.process_successful'){
		//console.log(jQuery('.import_table tbody tr td.status select option[value="successful"][selected="selected"]').closest('tr'))
		 jQuery('.import_table tbody tr td.status select option[value="successful"][selected="selected"]').
		 closest('tr').find('.process_row_do.btn').trigger('click'); 	

	}else if(task == 'management.process_ignore'){
		  jQuery('.import_table tbody tr td.status select option[value="ignore"][selected="selected"]').
		  closest('tr').find('.process_row.btn').trigger('click'); 					 		 		
	}else
	{
		var isValid=true;
		var action = task.split('.');
		if (action[1] != 'cancel' && action[1] != 'close')
		{
			var forms = $$('form.form-validate');
			for (var i=0;i<forms.length;i++)
			{
				if (!document.formvalidator.isValid(forms[i]))
				{
					isValid = false;
					break;
				}
			}
		}
		if (isValid)
		{
			Joomla.submitform(task);
			return true;
		}
		else
		{
			alert(Joomla.JText._('ERROR_UNACCEPTABLE',
			                     'Some values are unacceptable'));
			return false;
		}
	}
}
</script>

<style>
 
</style>

<h1> Debit Order CSV Processing  </h1>

 <?php




//This code hsa to be removed after testing 100 == 1

  
 	if( JComponentHelper::getParams('com_donorforce')->get('enable_import_duplicate') == 100){
		$bank = DonorforceHelper::getDebitOrderCSVImported('DOCSV');
	}else{
		$bank = ''; 	
	}
	 
	if(!empty($bank)){
		echo '<h4>Previous Debit Order CSV imported on '. date('M d-m-Y H:i:s',(str_replace('DOCSV-','',$bank->Reference))).'</h4>'; 	
		echo '<h4>Previous Imported UP to Date '.$bank->date.'</h4>'; 	
		
	}
  ?>
  
<div id="cpanel" style="float:left; width:90%;">
  
  <?php
	 $csvFile = $dest = JPATH_ROOT ."/media/UploadCSVDO/".JRequest::getVar('csv_file');
	  
	 if (!file_exists($csvFile)){
		 echo " <h2> File Does Not Exist  </h2> "; 
		 exit; 
	 }	 

		include_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_donorforce' . DS . 'controllers'. DS .'ajax.raw.php');
    $ajax_controller =  new DonorforceControllerAjax();
		$Project_Donors_list = $ajax_controller->GetDonorsData_return(); 
	 
				$file_handle = fopen($csvFile, 'r');
				while (!feof($file_handle) ) {
					$line_of_text[] = fgetcsv($file_handle, 1024);
				}
				fclose($file_handle);
				 
				$import_data = $line_of_text ;  
				//echo "<pre>"; print_r($import_data); exit;
				echo '<div>';
				echo '<table class="table table-striped import_table">
				<thead>
				<tr>
					<th> CSV Row </th>
					<th> Donor Number </th>
					<th> Project Number </th>
					<th> Donor First Name </th>
					<th> Donor Last Name </th>				
					<th> Method </th>
					<th> Bank Name </th>
					<th> Branch Name </th>
					<th> Branch Code </th>
					<th> Account Name </th>
					<th> Account Number </th>
					<th> Amount </th>
					<th> Select Donor </th>
					<th> Select Project </th>
					<th> Select Status </th>
					<th> Allocation </th>
				</tr>
				</thead>
				<tbody>'; 
						 
				  $increment = 1; 
				  
				foreach($import_data as $key => $data){
					if(!empty($data['0']) && (int) str_replace('D','',$data['0'])){
						//echo "<pre>"; print_r($data); exit;
					//if($increment == 1){ $increment++;  continue; }

					$f_donor = $f_project = $f_split = ''; 
						// $f_donor = (int) str_replace('D','',$csv_donor);
						// $f_project = (int) str_replace('P','',$csv_project);
					
					 $row = '<tr class="'.$increment.'">';
					
						$csv_donor = $data['0'];
						$csv_project = $data['1'];
						$csv_fname = $data['2'];
						$csv_lname = $data['3'];
						$csv_method = $data['4'];
						$csv_bank_name = $data['5'];
						$csv_branch_name = $data['6'];
						$csv_branch_code = $data['7'];						
						$csv_account_name = $data['8'];
						$csv_account_number = $data['9']; 
						$csv_amount = $data['10'];  
						$csv_donation_status = $data['11']; 

						$f_donor = (int) str_replace('D','',$csv_donor);
						$f_project = (int) str_replace('P','',$csv_project); 

						$g_amount =$csv_amount;		
						
						$date = new DateTime();
						$c_ref =  'DOCSV-'.$date->getTimestamp().$key;  
						
						if( ($bank != '' && $bank->date != '') && $bank_donation_date >= $donation_date){ 
							echo "<td class='dublicate' colspan='9'>".$increment.
								" Dublicate Found Date $donation_date </td></tr>";
							$increment++;
							continue; 
						} 

						$row .= "<td>$increment </td> 
							<td>".$csv_donor." </td>
							<td>".$csv_project." </td>
							<td>".$csv_fname." </td>
							<td>".$csv_lname." </td>
							<td>".$csv_method." </td>
							<td>".$csv_bank_name." </td>
							<td>".$csv_branch_name." </td>
							<td>".$csv_branch_code." </td>
							<td>".$csv_account_name." </td>
							<td>".$csv_account_number." </td>
							<td>".$csv_amount." </td>


							<td class='chosen'> ".$ajax_controller->Return_DonorsList($f_donor)."  </td> 
							<td class='chosen'> ".$ajax_controller->Return_ProjectsList($f_project)." </td>";

							if($f_donor != '' && $f_project != ''){	
							
							
							/*DonorforceHelper::ProcessImport(array(
								'donor_id' => $f_donor,
								'project_id' => $f_project,
								'date' => $donation_date,
								'amount' => $g_amount,
								'ref' => $c_ref,
								'project_id' => $f_project,
								'project_id' => $f_project,							
								));*/
							 
							//$row .= "<td class='status successful'>Successful</td>";
							
							$row .='<td class="status"><select class="" id="csv_row'.$increment.'" >
										  <option value="pending" >Pending</option>
										  <option  value="successful" selected="selected" >Successful</option>
										  <option  value="ignore" >Ignore</option>
									   </select>
									   </td>
									   ';
							
							
						}else{
							//$row .= "<td class='status'>Pending</td>";
							
							$row .='<td class="status"><select class="history_status" id="csv_row'.$increment.'" >
										  <option value="pending" >Pending</option>
										  <option  value="successful" >Successful</option>
										  <option  value="ignore" >Ignore</option>
									   </select>
									   </td>
									   ';
							
						}

							$row .= "<td> <button class='process_row_do btn btn-small' data-row='$increment' data-c_ref='".$c_ref."' data-c_amount='".$csv_amount."' data-donor='".$f_donor."' >Allocate</button>  </td>
							 
							</tr>"; 
	  
						echo  $row;

						$increment++;
					}
				} 
				echo '</tbody></table></div>'; 	
	?>
  
  </div>
<div style="clear:both;"></div>

    <div align="right">
        Powered by Netwise Multimedia <br />
        Version <?php echo $this->donorForceVersion; ?>
    </div>
 
 <script type="text/javascript">
 jQuery( document ).ready(function() {
	 
		console.log( 'ready!' );
		jQuery('.process_row_do.btn').on( 'click', function() {
		
			var csv_row = jQuery(this).data('row');
			var donor = jQuery('.import_table tr.'+csv_row+' .select_donor').val();
			var project = jQuery('.import_table tr.'+csv_row+' .select_project').val();
			var c_amount = jQuery(this).data('c_amount');
			var c_date = jQuery(this).data('c_date');
			var c_ref = jQuery(this).data('c_ref');	
			var c_status = jQuery('.import_table tr.'+csv_row+' .status select').val();

			console.log(' csv_row '+csv_row);
			console.log(' donor '+donor);
			console.log(' project '+project);
			// console.log(' c_amount '+c_amount);
			jQuery.ajax({
				 url : 'index.php?option=com_donorforce&task=ajax.ProcessImportDO&format=raw',
				 type: "POST",
			 	 data: { donor_id: donor,  project_id: project,amount: c_amount, ref:c_ref, status:c_status  },
				 //dataType: 'text',
				 success: function(data) {
					var data = jQuery.parseJSON( data );
					 console.log("test");
					 if(data.insert == '1'){  
								 console.log('Debit Order Updated Successfully');
							   jQuery('.import_table tr.'+csv_row+' .status').html('Succesfull');
							   jQuery('.import_table tr.'+csv_row).addClass('tr_disabled');
								 jQuery('.import_table tr.'+csv_row+' select').prop("disabled", true).trigger("liszt:updated"); 

						}
					 else{
							console.log('Error Updated Debit Order1 '+data);
					 }
				}
			});
			
			
		 });
	
		jQuery('.process_split').live( 'click', function() {
		
			var split_row = jQuery(this).closest('.split_row');
			var donor = jQuery(split_row).find('.select_donor').val();			
			var project = jQuery(split_row).find('.select_project').val();
			
			console.log(' donor '+donor);
			console.log(' project '+project);

			jQuery.ajax({
				 url : 'index.php?option=com_donorforce&task=ajax.ProcessImportDO&format=raw',
				 type: "POST",
			 	  data: { donor_id: donor,  project_id: project,amount: c_amount, date:c_date, ref:c_ref, status:c_status  },
				 //dataType: 'text',
				 success: function(data) {
					 if(data == true){  
								 console.log('Debit Order Updated Successfully');
							   jQuery(split_row).find('.status').html('Succesfull');
							   jQuery(split_row).addClass('tr_disabled');
								// jQuery('.import_table tr.'+csv_row+' select').trigger("chosen:updated");
						}
					 else{
							console.log('Error Updated Debit Order '+data);
					 }
				}
			});
		 
		 });

		/*
		jQuery('.process_row_do.btn').on( 'click', function() {
		
				console.log("test");
			var arr='';

			$('input.status: select=selected').each(function(){

				if ($this.text = "successful") {
					arr.push($(this).val());
				}
				console.log(arr.val());
				if (arr.val() == "successful") {
					var csv_row = jQuery(this).data('row');
					var donor = jQuery('.import_table tr.'+csv_row+' .select_donor').val();
					var project = jQuery('.import_table tr.'+csv_row+' .select_project').val();
					var c_amount = jQuery(this).data('c_amount');
					var c_date = jQuery(this).data('c_date');
					var c_ref = jQuery(this).data('c_ref');	
					var c_status = jQuery('.import_table tr.'+csv_row+' .status select').val();
		
					console.log(' csv_row '+csv_row);
					console.log(' donor '+donor);
					console.log(' project '+project);
					// console.log(' c_amount '+c_amount);
					jQuery.ajax({
				 	  url : 'index.php?option=com_donorforce&task=ajax.ProcessImportDO&format=raw',
				 	  type: "POST",
			 	 	  data: { donor_id: donor,  project_id: project,amount: c_amount, ref:c_ref, status:c_status  },
				 	  //dataType: 'text',
				 	    success: function(data) {
					 //var data = jQuery.parseJSON( data );
					 	console.log("test");
						 if(data == "true"){  
							console.log('Debit Order Updated Successfully');
							jQuery('.import_table tr.'+csv_row+' .status').html('Succesfull');
							jQuery('.import_table tr.'+csv_row).addClass('tr_disabled');
							jQuery('.import_table tr.'+csv_row+' select').prop("disabled", true).trigger("liszt:updated"); 

						
					 }else{
							console.log('Error Updated Debit Order1 '+data);
					 }
				}
			})

				}
			})
 
		});
		*/
	
	
		
		jQuery('.c_amount').live( 'change', function() {	
				console.log(' c_amount change  ');
				
				var csv_amount = jQuery(this).data('amount');
				var current_amount = jQuery(this).val();
				
				console.log(' csv_amount = '+csv_amount);
				console.log(' current_amount = '+current_amount);
				 
	 	}); 
		
		
	jQuery(document).on("change",".status select",function(){
	  jQuery("option[value=" + this.value + "]", this).attr("selected", true).siblings().removeAttr("selected")
	});
	
	jQuery(document).on('click','.split_now',function(){
		 console.log( ' split_now click  ' );
		 
		var select_donor =  jQuery(this).closest('tr').find('.select_donor')[0].outerHTML;
		var select_project =  jQuery(this).closest('tr').find('.select_project')[0].outerHTML; 
	
		var split_html = '<tr class="split_row"><td  colspan="3">Split </td>';
		 
		 split_html +='<td><input class="split_date" placeholder="Date" type="hidden" value="" /></td>';  
		 split_html +='<td><input class="split_amount" placeholder="Amount" type="text" value="" /></td>';  
		 split_html +='<td>'+select_donor+'</td>'; 
		 split_html +='<td>'+select_project+'</td>'; 
		 split_html +='<td class="status"><select><option value="pending">Pending</option><option value="successful" selected="selected">Successful</option><option value="ignore">Ignore</option></select></td>'; 
		 
		 split_html +='<td><button class="process_split btn btn-small"  data-c_date="'+jQuery(this).data('c_date')+'">Allocate Split</button></td>';
		
		jQuery(this).closest('tr').after(split_html);
		jQuery('tr.split_row .select_project,.select_donor').chosen();
		
	 
	 	
	});
		
	
	jQuery(document).on('click','.process_split',function(){
		var row = jQuery(this).closest('.split_row');
			
	});	
				
});	
</script>  
<style type="text/css">
.ib{ /*display:inline-block; */ }

.import_table tr .labl_select_project{ display:none;  }
.import_table tr .labl_select_donor{ display:none;  }
.tr_disabled{     opacity: 0.2;
    background: rgba(0,0,0,0.25); 
}
.tr_disabled td{ background: #ddd !important;}
.split_row {     border: 1px solid red; }
.split_row td{ 
	border-top: 3px solid #378137; 
	border-bottom: 1px solid #378137; 
}
tr.calendar-head-row {
    font-size: 13px;
}

</style> 