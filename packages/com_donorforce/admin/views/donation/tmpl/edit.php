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
JHtml::_('formbehavior.chosen', 'select');


$app = JFactory::getApplication();
$input = $app->input;
//JHtml::_('behavior.modal');
$document = JFactory::getDocument();

$params = $this->state->get('params');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'donation.cancel' || document.formvalidator.isValid(document.id('class-form')))
		{
			<?php //echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task, document.getElementById('class-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_donorforce&layout=edit&donation_id='.(int) $this->item->donation_id); ?>" method="post" name="adminForm" id="class-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">
    		
    <div class="span9 form-horizontal">
		
        <fieldset>
            
            <ul class="nav nav-tabs">			
                <li class="active">            
                 <a href="#details" data-toggle="tab">Donor Details</a>
                </li>
                <li>            
                 <a href="#debit_info" data-toggle="tab">Banks Details</a>
                </li>
            </ul>
    
    
       		<div class="tab-content">
				<div class="tab-pane active" id="details">                
				<?php     
				
				 if($input->getInt('donation_id') > 0)
				 {
					$this->form->setFieldAttribute('donor_id','class','readonly');
					$this->form->setFieldAttribute('donor_id','disabled','true');
					//$this->form->setFieldAttribute('donor_id','type','text');
					?>
                    <input type="hidden" name="jform[donor_id]" id="jform_donor_id" value="<?php echo $this->form->getValue('donor_id'); ?>" />
                    <?php					

				 }
				
                 foreach($this->form->getFieldset('donation') as $field)
                 { 
				 
                     if ($field->hidden)
                     { 
                         echo $field->input; 
                     }
                     else
                     { 
                        echo '<div class="control-group">
								<div class="control-label">'.$field->label. '</div>
                      			<div class="controls">'. $field->input.' </div>
							  </div>' . "\n";
                        echo '<div class="clr"></div>';
                     }                     
                 } 
                ?>       			
                
                <fieldset class="adminform">
                	<legend>Donation Instruction</legend>
					<?php     
                     foreach($this->form->getFieldset('donation_ins') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         { 
                            echo '<div class="control-group">
                                    <div class="control-label">'.$field->label. '</div>
                                    <div class="controls">'. $field->input.' </div>
                                  </div>' . "\n";
                            echo '<div class="clr"></div>';
                         }                     
                     } 
                    ?>
                </fieldset>
                
                </div>
                
                <div class="tab-pane" id="debit_info">
                 <?php
				if($params->get('usecc') == 0 || $params->get('usecc') == 2){
				?>
                <fieldset class="adminform">
                	<legend>Debit Order Information</legend>
					<?php     
                     foreach($this->form->getFieldset('debit_info') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         { 
                            echo '<div class="control-group">
                                    <div class="control-label">'.$field->label. '</div>
                                    <div class="controls">'. $field->input.' </div>
                                  </div>' . "\n";
                            echo '<div class="clr"></div>';
                         }                     
                     } 
                    ?>
                </fieldset>
                <?php
				}
				if($params->get('usecc') == 1 || $params->get('usecc') == 2){
				?>
                <fieldset class="adminform">
                	<legend>Credit Card Information</legend>
					<?php     
                     foreach($this->form->getFieldset('credit_info') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         { 
                            echo '<div class="control-group">
                                    <div class="control-label">'.$field->label. '</div>
                                    <div class="controls">'. $field->input.' </div>
                                  </div>' . "\n";
                            echo '<div class="clr"></div>';
                         }                     
                     } 
                    ?>
                </fieldset>     
                <?php 
				} //end if 
				?>
                </div>
                
            </div>
            
    </fieldset>
    
	</div>
    
</div>

<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>