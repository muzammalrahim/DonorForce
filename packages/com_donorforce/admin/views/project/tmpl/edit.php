<script>
jQuery(document).ready(function(e) {
     jQuery('<button type="button" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#jform_fundraising_goal');
      jQuery('<button type="button" class="btn" id="jform_date_img" aria-invalid="false" ><i class="">'+jQuery('#currency').val()+'</i></button>').insertBefore('#total_raised')



    var goal=jQuery('#jform_fundraising_goal').val();
    if(goal)
    {
        
        jQuery('#jform_fundraising_goal').val(Number (goal).toFixed(2)) 
    }
    
    var t=jQuery('#jform_total_raised').val();
    if(t)
    {
        
        jQuery('#jform_total_raised').val(Number (t).toFixed(2))    
    }
});

</script>


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
        if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form')))
        {
            <?php //echo $this->form->getField('articletext')->save(); ?>
            Joomla.submitform(task, document.getElementById('project-form'));
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_donorforce&layout=edit&project_id='.(int) $this->item->project_id); ?>" method="post" name="adminForm" id="project-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">
    <!-- Begin Newsfeed -->
    <div class="span9 form-horizontal">
        
        <fieldset class="adminform">    
        
            <ul class="nav nav-tabs">           
                <li class="active">            
                 <a href="#details" data-toggle="tab">Project Details</a>
                </li>
            </ul>
        
            <div class="tab-content">
                <div class="tab-pane active" id="details"> 
                <?php       
                 foreach($this->form->getFieldset('info') as $field)
                 { //echo "<pre> "; print_r( $field->id ); echo "</pre>";
                     if ($field->hidden)
                     { 
                         echo $field->input; 
                     }
                     else if($field->id == 'jform_project_id')
                     {
                        ?> 
               <div class="control-group">
               <div class="control-label"> <?php echo $field->label ?></div>
           
               <div class="controls">
               <input type="text" name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" value="<?php echo 'P'.str_pad($field->value, 5, '0', STR_PAD_LEFT);?>" class="readonly" readonly="" aria-invalid="false">
               <!-- The changings are made to get the desired value of project key -->
                                <?php //echo $field->input ?>
                            </div>    
                        </div>
                            <?php           
                    }else{
                ?> 
        
         
                    <div class="control-group">                       
                     <div class="control-label"><?php echo $field->label ?></div>
                     <div class="controls"><?php echo $field->input ?></div>  
                  </div>    
        
        
                        <?php if($field->id == 'jform_fundraising_goal'){?>
                                                 <div class="control-group">                       
                            <div class="control-label"> Total Raised </div>                            
                            <div class="controls">
                                                        <input readonly  type="text" name="total_raised" id="total_raised" value="<?php echo number_format($this->total_raised, 2, '.', ' ');?>" class="inputbox readonly" size="30" aria-invalid="false">
                                                        </div>                            
                        </div>
                                                
                                                 <?php } ?>
                        
                        
                        <?php
                     }
                     
                 } 
                ?>
                </div>
            </div>
            
        </fieldset>
        
    </div>
    
</div>

<input type="hidden" name="currency" id="currency" value="<?php echo DonorforceHelper::getCurrency(); ?>"  />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>