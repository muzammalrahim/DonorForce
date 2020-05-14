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


//JToolBarHelper::custom('hellos.extrahello', 'extrahello.png', 'extrahello_f2.png', 'Extra Hello', true);
?>

<style>
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
.file_field{background-color: #E5E5E5;
  padding: 6px 12px;
  border: 1px solid #B0A3A3;
  border-radius: 4px 0px 0px 4px;
  width: 40% !important;
}
.button_upload{
	margin-left: -5px;
  padding: 11px 15px;
  border-radius: 0px 4px 4px 0px;
}
.import_cont{ 
	margin-left: 5%; 
	padding: 10px; 
}
.import_cont a{   padding: 10px 15px; }
</style>

<div id="cpanel" style="float:left; width:90%;">
  
   
  
  <form action="<?php echo JRoute::_('index.php?option=com_donorforce&task=donors.uploadfile'); ?>" method="post" name="adminForm" id="project-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">
    <!-- Begin Newsfeed -->
    <div class="span9 form-horizontal">      
     <fieldset class="adminform">
     <h4>Select CSV file to Upload</h4>
     <div class="form-group">
     	<input class="file_field" type="file" name="file_upload" required = 'required'  accept=".csv" />
     	<input class="btn  btn-success button_upload" type="submit" value="Upload" />
     </div>   
     </fieldset>      
    </div>   
</div>
<input type="hidden" name="task" value="donors.uploadfile" />

<?php echo JHtml::_('form.token'); ?>
<h4>Please upload the CSV File with Date of Birth 'Date' format as yyyy-mm-dd i.e.(1990-06-22)</h4>
</form>
 

  
  </div>
<div style="clear:both;"></div>

    <div align="right">
        Powered by Netwise Multimedia <br />
        Version <?php echo $this->donorForceVersion; ?>
    </div>