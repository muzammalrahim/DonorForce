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
Joomla.submitbutton = function(task){
	if (task == ''){
		return false;
	}else if(task == 'management.clear_data'){
		 console.log( ' management.clear_data ' );
		 jQuery('.import_table tbody tr.tr_disabled').remove(); 
		 jQuery('.import_table tbody tr td.dublicate').parent().remove();
		 jQuery('.import_table tbody tr td.tr_info').parent().remove();
	}else if(task == 'management.process_successful'){
		 jQuery('.import_table tbody tr td.status select option[value="successful"][selected="selected"]').
		 closest('tr').find('.process_row.btn').trigger('click'); 		
	}else if(task == 'management.process_ignore'){
		  jQuery('.import_table tbody tr td.status select option[value="ignore"][selected="selected"]').
		  closest('tr').find('.process_row.btn').trigger('click'); 					 		 		
	}else{
		var isValid=true;
		var action = task.split('.');
		if (action[1] != 'cancel' && action[1] != 'close'){
			var forms = $$('form.form-validate');
			for (var i=0;i<forms.length;i++){
				if (!document.formvalidator.isValid(forms[i])){
					isValid = false;
					break;
				}
			}
		}
		if (isValid){
			Joomla.submitform(task);
			return true;
		}else{
			alert(Joomla.JText._('ERROR_UNACCEPTABLE',
			                     'Some values are unacceptable'));
			return false;
		}
	}
}
</script>
<h1> Bank CSV Processing....</h1>
 <?php
 	if( JComponentHelper::getParams('com_donorforce')->get('enable_import_duplicate') == 1){
		$bank = DonorforceHelper::getLastDonationCSVImported('BCSV');
	}else{
		$bank = ''; 	
	}
	//echo " <pre>  bank   ";  print_r($bank  ); echo " </pre> ";   
	if(!empty($bank)){
		echo '<h4>Previous Bank CSV imported on '. date('M d-m-Y H:i:s',(str_replace('BCSV-','',$bank->Reference))).'</h4>'; 	
		echo '<h4>Previous Imported UP to Date '.$bank->date.'</h4>'; 	
		
	}
  ?>
<div id="cpanel" style="float:left; width:90%;">
  <?php
	 $csvFile = $dest = JPATH_ROOT ."/media/UploadCSV/".JRequest::getVar('csv_file'); 
	 if(!file_exists($csvFile)){
		 echo " <h2> File Not Exist  </h2> "; 
		 exit; 
	 }	 
	include_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_donorforce' . DS . 'controllers'. DS .'ajax.raw.php');
    $ajax_controller =  new DonorforceControllerAjax();
	$Project_Donors_list = $ajax_controller->GetDonorsData_return(); 
	$file_handle = fopen($csvFile, 'r');
		while(!feof($file_handle)){
					$line_of_text[] = fgetcsv($file_handle, 1024);
		}
		fclose($file_handle);
		$import_data = $line_of_text;  
		/* echo "<pre>";
		print_r($import_data);
		echo "</pre>"; */
		echo '<div>';
		echo '<table class="table table-striped import_table"><thead><tr>
		<th>CSV Row </th>
		<th> Date </th>
		<th> Donor - Project </th>
		<th> Reference </th>
		<th> Amount </th>				
		<th> Donor </th>
		<th> Project </th>
		<th> Status </th>
		<th> Action </th></tr></thead><tbody>'; 
		try{
		$increment = 0;
		if(count($import_data) <= 1){
			echo '<tr/><td style="color:red;" colspan="9">Empty row</td></tr>'; 
		}else{
			foreach($import_data as $key => $data){
				if(count($import_data) == $key+1){

				}
				else if(trim($data['1']) == '' && trim($data['5'])  == '' && trim($data['6'])  == ''){
					echo '<tr/><td style="color:red;" colspan="9">Empty row</td></tr>';  continue; 
				}else{
					if($increment == 0){ 
						$increment++;  
						continue; 
					}
					$row = '<tr class="'.$increment.'">';
					$donation_date =  $data['1']; 
					//$donation_date =  '20190601'; 
					//$donation_date =  '01 06 2019';
					if (strpos(trim($donation_date), ' ') !== false){
						$donation_date_array = explode(" ", $donation_date);
						$donation_date = $donation_date_array[2].$donation_date_array[1].$donation_date_array[0];
					}
					$donation_date = substr_replace($donation_date, '-', 4, 0);
					$donation_date = substr_replace($donation_date, '-', 7, 0);
					$DataDPArray = explode(" ", trim($data[5]));
					$f_donor = 0;
					$f_project = 0;
					$pos = strpos($DataDP, 'D');
					foreach ($DataDPArray as $DataDP) {
					    if(strstr($DataDP, 'D', 0)){
					        if(is_numeric(str_replace('D','',$DataDP))){
					           	$f_donor = (int) str_replace('D','',$DataDP);
					        }
					    }
					    if(strstr($DataDP, 'P', 0)){
					        if(is_numeric(str_replace('P','',$DataDP))){
					           	$f_project = (int) str_replace('P','',$DataDP);
					        }
					    }
					}
					if($data['1'] != ''){
						$donation_date = DateTime::createFromFormat('Y-m-d', $donation_date);					
						$donation_date = $donation_date->format('Y-m-d');
					}else{
						$row = "<td class='tr_info' colspan='4'> Wrong Date In CSV Row $increment </td> ";
						$row .= "<td colspan='5' > For this Row Current Date has been Considered </td> ";
						$donation_date = date('Y-m-d');
						$row .='</tr><tr>'; 	
					}
						
					if($bank != '' && $bank->date != ''){
						$bank_donation_date = DateTime::createFromFormat('Y-m-d H:i:s', $bank->date);					
						$bank_donation_date = $bank_donation_date->format('Y-m-d');
					}
						
					$csv_donor = trim($data['5']);
					$csv_project = trim($data['6']);						
					$g_amount =$data['3'];						
					$date = new DateTime();
					$c_ref =  'BCSV-'.$date->getTimestamp().$key;  
					if(($bank != '' && $bank->date != '') && $bank_donation_date >= $donation_date){ 
						echo "<td class='dublicate' colspan='9'>".$increment." Dublicate Found Date $donation_date </td></tr>";
						$increment++;
						continue; 
					} 
					if($f_split != ''){
						echo "<td>$increment </td>
						<td>".$donation_date." </td>
						<td>".$csv_donor." ".$csv_project."</td>  
						<td>".$c_ref." </td>
						<td colspan='3'>".$g_amount." </td>
						<td class='chosen' style='display:none;'> ".$ajax_controller->Return_DonorsList($f_donor).$ajax_controller->Return_ProjectsList($f_project)." </td>
						<td style='color: red;'>Split Required</td>	
						<td><button class='split_now btn btn-small' data-row='$increment' data-c_date='$donation_date' data-c_ref='".$c_ref."' data-c_amount='".$g_amount."' data-donor='".$f_donor."' data-project='".$f_project."' >Split</button></td></tr>";  
						$increment++;
						continue; 	
					}								
					$row .= "<td>$increment </td> 
							<td>".$donation_date." </td>
							<td>".$f."</td>  
							<td>".$c_ref." </td>
							<td>".$g_amount." </td>
							<td class='chosen'> ".$ajax_controller->Return_DonorsList($f_donor)." </td> 
							<td class='chosen'> ".$ajax_controller->Return_ProjectsList($f_project)." </td>"; 
					if($f_donor != '' && $f_project != ''){	
						$row .='<td class="status"><select class="" id="csv_row'.$increment.'" >
							<option value="pending" >Pending</option>
							<option  value="successful" selected="selected" >Successful</option>
							<option  value="ignore" >Ignore</option>
							</select></td>';
					}else{
						$row .='<td class="status"><select class="history_status" id="csv_row'.$increment.'" >
								<option value="pending" >Pending</option>
								<option  value="successful" >Successful</option>
								<option  value="ignore" >Ignore</option></select>
								</td>';
					}
					$row .= "<td> <button class='process_row btn btn-small' data-row='$increment' data-c_date='$donation_date' data-c_ref='".$c_ref."' data-c_amount='".$g_amount."' data-donor='".$f_donor."' data-project='".$f_project."' >Allocate</button>  </td>
							 
							</tr>";  
					echo $row;
					$increment++;
				}
			}
		}
		}catch(Exception $e){
			print_r($e);
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
		jQuery('.process_row').live( 'click', function() {
		
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
			console.log(' c_amount '+c_amount);
			
			if(donor != "undefined" && donor !== null && donor != 0){
				jQuery.ajax({
					url : 'index.php?option=com_donorforce&task=ajax.ProcessImport&format=raw',
					type: "POST",
					data: { donor_id: donor,  project_id: project,amount: c_amount, date:c_date, ref:c_ref, status:c_status  },
					//dataType: 'text',
					success: function(data) {
						var data = jQuery.parseJSON( data );
						
						if(data.insert == '1'){  
									console.log('Donation Updated Successfully');
								jQuery('.import_table tr.'+csv_row+' .status').html('Succesfull');
								jQuery('.import_table tr.'+csv_row).addClass('tr_disabled');
									jQuery('.import_table tr.'+csv_row+' select').prop("disabled", true).trigger("liszt:updated"); 
									// jQuery('.import_table tr.'+csv_row+' select').trigger("chosen:updated");
							}
						else{
								console.log('Error Updated Donation '+data);
						}
					}
				});
			}
			else{
				jQuery('.import_table tr.'+csv_row+' .status').html('Succesfull');
				jQuery('.import_table tr.'+csv_row).addClass('tr_disabled');
				jQuery('.import_table tr.'+csv_row+' select').prop("disabled", true).trigger("liszt:updated"); 
			}
			
			
			/*var  donor 	= jQuery(this).data('donor'); 
			var  project 	= jQuery(this).data('project'); 
				
			console.log(' donor '+donor);
			console.log(' project '+project);
			
			jQuery('tr.'+jQuery(this).data('row')+' .d_p_list ').css('display','block');
			
			if( (donor != '') && (donor != '-NaN-') ){
				jQuery( 'tr.'+jQuery(this).data('row')+' .d_p_list #jform_donor_id').val(donor);
			}
			
			if( (project != '') && (project != '-NaN-') ){
				jQuery( 'tr.'+jQuery(this).data('row')+' .d_p_list #jform_project_id').val(project);
			}*/
			
			/*
			var  c_date 	= jQuery(this).data('c_date'); 
			var  c_ref 		= jQuery(this).data('c_ref'); 
			var  c_amount = jQuery(this).data('c_amount'); 
			var  c_form 	= jQuery('#ajaxresult_'+jQuery(this).data('row')+' form '); 
			console.log(' c_form =  '+c_form);
			
			c_form.append( jQuery('#smaple_donor_project_list form').html() );
			jQuery('#ajaxresult_'+jQuery(this).data('row')+' .c_date').val(c_date);
			
			var no_csv_row = jQuery('#ajaxresult_'+jQuery(this).data('row')+' form .csv_row').length; 
			console.log(' no_csv_row '+no_csv_row);
			
			//c_form.find('.csv_row').eq(no_csv_row+1).css('border','2px solid red;');
			c_form.find('.csv_row').eq(no_csv_row-1).find('.c_amount').val('abc ='+no_csv_row); //.css('border','2px solid red;'); //.html('xyz');//
			
			//console.log(' csv_row  '+c_form.find('.csv_row').html()); 
			if(no_csv_row == 1){
				//jQuery('#ajaxresult_'+jQuery(this).data('row')+'  .c_amount').val(c_amount);
				c_form.find('.csv_row').eq(no_csv_row-1).find('.c_amount').val(c_amount);
			}else{
				//jQuery('#ajaxresult_'+jQuery(this).data('row')+' .c_amount').val('');
				c_form.find('.csv_row').eq(no_csv_row-1).find('.c_amount').val('');
			}
			//jQuery('#ajaxresult_'+jQuery(this).data('row')+' .c_amount').val(c_amount);
			//jQuery('#ajaxresult_'+jQuery(this).data('row')+' .c_amount').attr('data-amount',c_amount);							
			*/
		 
		 });
	
		jQuery('.process_split').live( 'click', function() {
		
			var split_row = jQuery(this).closest('.split_row');
			var donor = jQuery(split_row).find('.select_donor').val();			
			var project = jQuery(split_row).find('.select_project').val();
			var c_amount = jQuery(split_row).find('.split_amount').val();			
			var c_date = jQuery(this).data('c_date');
			var c_ref = 'BCSV-SPLIT-'+jQuery.now();
			var c_status = jQuery(split_row).find('.status select').val();
			
			
			 
			console.log(' donor '+donor);
			console.log(' project '+project);
			console.log(' c_amount '+c_amount);
			
			
			jQuery.ajax({
				 url : 'index.php?option=com_donorforce&task=ajax.ProcessImport&format=raw',
				 type: "POST",
			 	 data: { donor_id: donor,  project_id: project,amount: c_amount, date:c_date, ref:c_ref, status:c_status  },
				 //dataType: 'text',
				 success: function(data) {
					 if(data == '1'){  
								 console.log('Donation Updated Successfully');
							   jQuery(split_row).find('.status').html('Succesfull');
							   jQuery(split_row).addClass('tr_disabled');
								// jQuery('.import_table tr.'+csv_row+' select').trigger("chosen:updated");
						}
					 else{
							console.log('Error Updated Donation '+data);
					 }
				}
			});
		 
		 });
	
		
		jQuery('.c_amount').live( 'change', function() {	
				console.log(' c_amount change  ');
				
				var csv_amount = jQuery(this).data('amount');
				var current_amount = jQuery(this).val();
				
				console.log(' csv_amount = '+csv_amount);
				console.log(' current_amount = '+current_amount);
				
			/*	if( current_amount > csv_amount ){
					jQuery(this).val(csv_amount);	
				}else{
						console.log(' append after row ');
						jQuery(this).closest('.row').after( jQuery('#smaple_donor_project_list').html());
						 	
				}*/
			   
				 
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
		
		
		//jQuery(this).closest('tbody').prepend(split_html);
		
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