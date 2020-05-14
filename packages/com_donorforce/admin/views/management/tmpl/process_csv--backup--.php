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

?>

<style>
 
</style>

<div id="cpanel" style="float:left; width:90%;">
  
  <?php
	 $csvFile = $dest = JPATH_ROOT ."/media/UploadCSV/".JRequest::getVar('csv_file');
	 
	 echo "<pre> csv_path = "; print_r( $csvFile  ); echo "</pre>";  
	 if (!file_exists($csvFile)){
		 echo " <h2> File Not Exist  </h2> "; 
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
				
				//echo "<pre> line_of_text "; print_r( $line_of_text  ); echo "</pre>"; 
				
				echo '<div id="smaple_donor_project_list"> ';
					echo '<form><div class="csv_row">  ';  
					echo $Project_Donors_list;
					echo '
					<div class="date ib">
					<label  style="margin-top: 10px; margin-bottom: 5px; "> Date </label>
					<input class="c_date" type="text" disabled  name="jform[amount]" class="inputbox chosen-select"  aria-invalid="false">
					</div>
					
					<div class="amount ib">
					<label  style="margin-top: 10px; margin-bottom: 5px; "> Amount </label>
					<input class="c_amount" type="text" name="jform[amount]" class="inputbox chosen-select"  aria-invalid="false">
					</div>
					
					';
				echo ' </div></form></div>'; 
				
				 
				$import_data = $line_of_text ;  
				echo '<div>';  $increment = 1; 
				foreach($import_data as $data){
					if( trim($data['1']) == '' && trim($data['5'])  == '' && trim($data['6'])  == '' ){
						 echo '<hr/><p style="color:red;">Empty row</p>';  continue; 
					}
					
					$row = '<hr/>';
					
					$donation_date =  $data['1']; 
					$donation_date = str_replace('/','-',$donation_date);
					$donation_date = str_replace(' ','',$donation_date);
					
					if($data['1'] != ''){
						$donation_date = DateTime::createFromFormat('Ymd', $donation_date);					
						$donation_date = $donation_date->format('Y-m-d');
					}else{
							 $row = "<h4 > Wrong Date In CSV Row $increment </h4> ";
							 $row .= "<h4 > For this Row Current Date has been Considered </h4> ";
							  
							$donation_date = date('Y-m-d');	
					}
 
					
					
						$row .= "<div>Row $increment => 
							<br/><span> Date = ".$donation_date." </span>
							<br/><span> Reference = ".$data['5']."</span>  
							<br/><span> Amount = ".$data['6']." </span>
							</div>"; 
					
						$row .= "<div><h4> Donation $increment Assigment </h4>
									 		<button class='process_row ' data-row='$increment' data-c_date='$donation_date' data-c_ref='".$data['5']."' data-c_amount='".$data['6']."'  >Create Row</button>
											<button class='create_donation' >Create Donation </button>
											<div id='ajaxresult_$increment'><form></form></div>  		
									 </div>";
						
						echo  $row;
						$increment++;
				}
				echo '</div>'; 	
				
			
	
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
			console.log(' Goodbye ');
			
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
				
		
		/*	console.log(' c_date =  '+c_date);
			console.log(' c_ref =  '+c_ref);
			console.log(' c_amount =  '+c_amount);*/
			
			
				  
				
							
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
		
				
	});	
</script>  
<style type="text/css">
.ib{ display:inline-block;  }
</style> 