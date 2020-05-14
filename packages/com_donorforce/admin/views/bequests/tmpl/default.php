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
$canOrder	= $user->authorise('core.edit.state', 'com_donorforce.bequests');

?>



<form action="<?php echo JRoute::_('index.php?option=com_donorforce&view=bequests'); ?>" method="post" name="adminForm" id="adminForm">
	
	<div class="clr"> </div>
	

	<div id="editcell">
		<table class="table table-striped" id="categoryList">
			<thead>
				<tr>
					
					<th width="3%">
                    	<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                    </th>
					
                    <th class="title" width="7%" align="left" style="text-align:left;">
                    	<?php echo JHtml::_('grid.sort', 'ID', 'a.bequest_id', $listDirn, $listOrder); ?>
                    </th>
                    
                    <th class="title" width="15%" align="left" style="text-align:left;">
                    	<?php echo JHtml::_('grid.sort', 'First Name', 'a.first_name', $listDirn, $listOrder); ?>
                    </th>                    
                    
                    <th class="title" width="15%" align="left" style="text-align:left;">Surname</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Followed Up</th>
                    					
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
$canCreate	= $user->authorise('core.create', 'com_donorforce.bequests.'.$item->bequest_id);
$canEdit	= $user->authorise('core.edit', 'com_donorforce.bequests.'.$item->bequest_id);
$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange	= $user->authorise('core.edit.state', 'com_donorforce.bequests.'.$item->bequest_id) && $canCheckin;
$linkEdit 	= JRoute::_( 'index.php?option=com_donorforce&task=bequest.edit&bequest_id='. $item->bequest_id );

					
echo '<tr class="row'. $i % 2 .'">';
					
echo '<td class="center">'. JHtml::_('grid.id', $i, $item->bequest_id) . '</td>';

echo '<td>';
echo $item->bequest_id;
echo '</td>';					

echo '<td>';
echo '<a href="'. JRoute::_($linkEdit).'">'. $item->first_name.'</a>';
echo '</td>';

echo '<td>';
echo $item->surname;
echo '</td>';

echo '<td>';
echo $item->follow;
echo '</td>';

//echo '<td class="center">'. JHtml::_('jgrid.published', $item->published, $i, 'bequests.', $canChange) . '</td>';


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