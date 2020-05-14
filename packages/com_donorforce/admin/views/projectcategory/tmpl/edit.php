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
		if (task == 'projectcategory.cancel' || document.formvalidator.isValid(document.id('projectc-form')))
		{
			<?php //echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task, document.getElementById('projectc-form'));
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_donorforce&layout=edit&pcategory_id='.(int) $this->item->pcategory_id); ?>" method="post" name="adminForm" id="projectc-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">
    <!-- Begin Newsfeed -->
    <div class="span9 form-horizontal">
        
        <fieldset class="adminform">	
        
            <ul class="nav nav-tabs">			
                <li class="active">            
                 <a href="#details" data-toggle="tab">Project Category Details</a>
                </li>
            </ul>
        
    		<div class="tab-content">
				<div class="tab-pane active" id="details"> 
				<?php     
                 foreach($this->form->getFieldset('info') as $field)
                 { 
                     if ($field->hidden)
                     { 
                         echo $field->input; 
                     }
                     else
                     {
						?> 
                        <div class="control-group">
                        
                            <div class="control-label">
                                <?php echo $field->label ?>
                            </div>
                            
                            <div class="controls">
                                <?php echo $field->input ?>
                            </div>
                            
                        </div>
                       	<?php
                     }
                     
                 } 
                ?>
         		</div>
            </div>
            
        </fieldset>
        
    </div>
    
</div>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>