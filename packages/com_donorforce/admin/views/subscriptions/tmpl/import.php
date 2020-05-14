<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//JHtml::_('behavior.modal');
$document = JFactory::getDocument();
?>
<script type="text/javascript">
Joomla.submitbutton = function(task)
 { 
  if ( (task == 'subscriptions.cancel')){
          window.location = "<?php echo $this->baseurl ?>";
  } 
 }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_donorforce&task=subscriptions.uploadfile'); ?>" method="post" name="adminForm" id="project-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">
    <!-- Begin Newsfeed -->
    <div class="span9 form-horizontal">
        
        <fieldset class="adminform">	
       
       			<h4>Select XLS file to Upload</h4>  
           <div class="form-group"> 
                <input class="file_field" type="file" name="file_upload" required = 'required'  accept="application/vnd.ms-excel" />
                <input class="btn  btn-success button_upload" type="submit" value="Upload" /> 
           </div>   
            
            
            
        </fieldset>
        
    </div>
    
</div>
<input type="hidden" name="task" value="subscriptions.uploadfile" />
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