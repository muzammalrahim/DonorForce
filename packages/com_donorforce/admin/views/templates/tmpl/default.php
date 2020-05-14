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
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'COM_DONORFORCE.category');
$saveOrder	= $listOrder=='ordering';
$params		= (isset($this->state->params)) ? $this->state->params : new JObject();
?>
<?php // echo $this->loadTemplate('Donorforce3menus'); ?>
<form action="<?php echo JRoute::_('index.php?option=COM_DONORFORCE&view=templates'); ?>" method="post" name="adminForm" id="adminForm">
	<!--<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BANNERS_SEARCH_IN_TITLE'); ?>" />
         
            <?php
            $options[] = JHTML::_('select.option','', JText::_('COM_CHURCH_ADMIN_POSITION_SEARCH_ALL'));
            $options[] = JHTML::_('select.option','name', JText::_('COM_CHURCH_ADMIN_POSITION_SEARCH_NAME'));
            $options[] = JHTML::_('select.option','description', JText::_('COM_CHURCH_ADMIN_POSITION_SEARCH_DESCRIPTION'));
            echo JHTML::_('select.genericlist', $options, 'filter_search_type', 'class="inputbox"', 'value', 'text', $this->state->get('filter.search_type'));
            ?>
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';document.id('filter_search_type').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
            
			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
			</select>
		</div>
	</fieldset>-->
	<div class="clr"> </div>

	<table class="table table-striped" id="categoryList">
		<thead>
			<tr>
            
				<th width="2%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
                	<th width="5%" >
					<?php echo JHtml::_('grid.sort', 'ID', 'id', $listDirn, $listOrder); ?>
				</th>
				<th align="left" width="30%">
					<?php echo JHtml::_('grid.sort',  'Name', 'name', $listDirn, $listOrder); ?>
				</th>
				<th align="left" width="50%">
					<?php echo JHtml::_('grid.sort', 'Template Text', 'text', $listDirn, $listOrder); ?>
				</th>
                <th align="left" width="10%">
					<?php echo JHtml::_('grid.sort', 'Status', 'published', $listDirn, $listOrder); ?>
				</th>
			
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'ordering');
			$canCreate	= $user->authorise('core.create',		'COM_DONORFORCE.templates.'.$item->id);
			$canEdit	= $user->authorise('core.edit',			'COM_DONORFORCE.templates.'.$item->id);
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state',	'COM_DONORFORCE.templates.'.$item->id) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
            
				<td >
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td><td>
					<?php echo $item->id; ?>
				</td>
				<td>
					
					<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=COM_DONORFORCE&task=template.edit&id='.(int) $item->id); ?>">
							<?php echo $this->escape($item->name); ?></a>
					<?php else : ?>
							<?php echo $this->escape($item->name); ?>
					<?php endif; ?>
				</td>
				
                <td class="">
					<?php echo $item->text;?>
				</td>
                <td >
					<?php echo  JHtml::_('jgrid.published', $item->published, $i, 'templates.', $canChange);?>
				</td>
				
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
