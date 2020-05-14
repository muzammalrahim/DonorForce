<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'Donorforcetemplate.cancel' || document.formvalidator.isValid(document.id('Donorforcetemplate-form'))) {
			Joomla.submitform(task, document.getElementById('Donorforcetemplate-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=COM_DONORFORCE&view=Donorforcetemplate&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="Donorforcetemplate-form" class="form-validate">
  <div class="width-60 fltlft">
    <fieldset class="adminform">
      <legend><?php echo empty($this->item->id) ? JText::_('Donorforce Template Fields Details') : JText::sprintf('COM_DONORFORCE_Donorforce_GROUP_FIELDS_DETAIL', $this->item->id); ?></legend>
     
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
          <div class="controls"> <?php echo $this->form->getInput('id'); ?></div>
        </div>
         <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
          <div class="controls"> <?php echo $this->form->getInput('name'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('text'); ?> </div>
          <div class="controls"><?php echo $this->form->getInput('text'); ?></div>
        </div>
      
       <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
          <div class="controls"> <?php echo $this->form->getInput('published'); ?></div>
        </div>
            <div class="clr"> </div>
    </fieldset>
  </div>
  <input type="hidden" name="task" value="" />
  <?php echo JHtml::_('form.token'); ?>
  <div class="clr"></div>
</form>
