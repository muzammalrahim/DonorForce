<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');

?>

<style>
tr.calendar-head-row {
    font-size: 13px;
}
.icon
{
	margin-top: 20px;
	margin-left: 20px;
}
.icon-wrapper {
	width: 280px;
	margin: 10px;
	background-color: #f3f3f3;
	padding: 10px;
	color: #fff;
	border: 1px solid #fff;
	border-radius: 5px;
	height:80px;
	border-bottom: 1px solid #5dbb46;
}
.icon-wrapper:hover {
	-moz-box-shadow: 0 0 5px rgba(0,0,0,0.5);
	-webkit-box-shadow: 0 0 5px rgba(0,0,0,0.5);
	box-shadow: 0 0 5px rgba(0,0,0,0.5);
}
</style>

<div id="cpanel" style="float:left; width:90%;">
  
   <?php
	if( !(JFolder::exists(JPATH_LIBRARIES.'/dompdf')) || !(JFolder::exists(JPATH_LIBRARIES.'/phpexcel'))  ){  ?>		
    <div class="" style="padding: 20px; color: red; font-size: 20px;">
    <p><span class="icon-warning"></span>  
    		Dompdf or Phpexcel Library Missing 
    		<a href="index.php?option=com_donorforce&view=donor&layout=installtest">Click here to install </a> 
    </p>
    </div>
  <?php 
	}
	?>
  
  
  <?php
  
 
	if( JComponentHelper::getParams('com_donorforce')->get('enable_import_duplicate') == 1){
		$bank = DonorforceHelper::getLastDonationCSVImported('BCSV');
	}else{
		$bank = ''; 	
	}
	
 	if(!empty($bank)){
		echo '<h4>Previous Bank CSV imported on '. date('M d-m-Y H:i:s', (str_replace('BCSV-','',$bank->Reference))).'</h4>'; 	
		echo '<h4>Previous Donation Imported UP to Date '.$bank->date.'</h4>'; 	
		
	}
  ?>
  
  
  
  
  
  <form action="<?php echo JRoute::_('index.php?option=com_donorforce&task=management.uploadfile'); ?>" method="post" name="adminForm" id="project-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">
    <!-- Begin Newsfeed -->
    <div class="span9 form-horizontal">
        
        <fieldset class="adminform">	
       
       			 
            <h4>Select Type of CSV Import</h4> 
            <div class="form-group"> 
            <select id="jform_import_csv" name="import_type" class="required" required="required" aria-required="true">
                <option value="bank_csv">Bank CSV </option>	
                <!-- <option value="paygate_csv"> Paygate CSV </option>	 -->
                <option value="debitorder_csv">Debitorder CSV </option>	
            </select>
            </div> 
  
  				<br /><br />
  				<h4>Select CSV file to Upload</h4> 
          
           <div class="form-group"> 
                <input class="file_field" type="file" name="file_upload" required = 'required'  accept=".csv" />
                <input class="btn  btn-success button_upload" type="submit" value="Upload" /> 
           </div>   
            
            
            
        </fieldset>
        
    </div>
    
</div>
<input type="hidden" name="task" value="management.uploadfile" />

<?php echo JHtml::_('form.token'); ?>
</form>

 <style>
  .file_field{background-color: #E5E5E5;
  padding: 6px 12px;
  border: 1px solid #B0A3A3;
  border-radius: 4px 0px 0px 4px;
  width: 40% !important;}
  .button_upload{
	  margin-left: -5px;
  padding: 11px 15px;
  border-radius: 0px 4px 4px 0px;}
 .import_cont{ margin-left: 5%;
  padding: 10px; }
  .import_cont a{   padding: 10px 15px; }
 </style> 
  
  
  
  
  
  
  
  
  
   
 
 
   
  
  
  
  


  
  </div>
<div style="clear:both;"></div>

    <div align="right">
        Powered by Netwise Multimedia <br />
        Version <?php echo $this->donorForceVersion; ?>
    </div>