<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.modal');

$document = JFactory::getDocument();


$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_donorforce.donations');

$saveOrder	= $listOrder=='a.donation_id';

?>
<style type="text/css">
	.js-stools-container-list{display: none !important; }
</style>

<form action="<?php echo JRoute::_('index.php?option=com_donorforce&view=donations'); ?>" method="post" name="adminForm" id="adminForm">
	
	<div class="clr"> </div>
	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

	<div id="editcell">
		<table class="table table-striped" id="categoryList">
			<thead>
				<tr>

					<th width="2%">
                    	<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                    </th>
					
					<th class="title" width="10%" align="left" style="text-align:left;">                    
                    	<?php echo JHTML::_( 'grid.sort', 'Banking instruction No', 'a.donation_id', $listDirn, $listOrder); ?>
                    </th>

                    <th class="title" width="10%" align="left" style="text-align:left;">                    
                    	<?php echo JHTML::_( 'grid.sort', 'First Name', 'd.name_first', $listDirn, $listOrder); ?>
                    </th>

                    <th class="title" width="10%" align="left" style="text-align:left;">                    
                    	<?php echo JHTML::_( 'grid.sort', 'Last Name', 'd.name_last', $listDirn, $listOrder); ?>
                    </th>

                    <th class="title" width="10%" align="left" style="text-align:left;">                    
                    	<?php echo JHTML::_( 'grid.sort', 'Organization Type', 'd.org_type', $listDirn, $listOrder); ?>
                    </th>

                    <th class="title" width="10%" align="left" style="text-align:left;">                    
                    	<?php echo JHTML::_( 'grid.sort', 'Organization Name', 'd.org_name', $listDirn, $listOrder); ?>
                    </th>

				</tr>
			</thead>
			
			<tbody><?php
				
$j = 0;
if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
	
	//We can unset the values in array or filter here
	//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
		//$j++;
$item->max_ordering = 0; //??		
$ordering	= ($listOrder == 'a.ordering');			
$canCreate	= $user->authorise('core.create', 'com_donorforce.donations.');
$canEdit	= $user->authorise('core.edit', 'com_donorforce.donations.');
$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange	= $user->authorise('core.edit.state', 'com_donorforce.donations.'.$item->donation_id);
$linkEdit 	= JRoute::_( 'index.php?option=com_donorforce&task=donation.edit&donation_id='. $item->donation_id );

					
echo '<tr class="row'. $i % 2 .'">';
					
echo '<td class="center">'. JHtml::_('grid.id', $i, $item->donation_id) . '</td>';

echo '<td>';
echo  $item->donation_id;
echo '</td>';					

echo '<td>'; 
if ($canCreate || $canEdit) {
	echo '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->name_first).'</a>';
} else {
	echo $this->escape($item->name_first);
}
echo '</td>'; 

echo '<td>';
echo  $item->name_last;
echo '</td>';

echo '<td>';
echo $item->org_type;
echo '</td>';

echo '<td>';
echo $item->org_name;
echo '</td>';

/*
echo '<td class="center">'. JHtml::_('jgrid.published', $item->published, $i, 'donations.', $canChange) . '</td>';

*/
?>
<?php
//echo '<td align="center">'. $item->hits.'</td>';

echo '</tr>';						
		//}
	}
}
echo '</tbody>';
?>			
			<tfoot>
				<tr>
					<td colspan="45"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>