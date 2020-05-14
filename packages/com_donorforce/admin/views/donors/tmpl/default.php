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

$input	= JFactory::getApplication()->input;
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_donorforce.donors');

$saveOrder	= $listOrder=='a.donor_id';

$searchF = $input->get('search');

?>

<?php  //if(!empty($input->get('search'))){ $searchF = $input->get('search'); }

?>
<form action="<?php echo JRoute::_('index.php?option=com_donorforce&view=donors'); ?>" method="post" name="adminForm" id="adminForm">
	
  
	<div class="clr"> </div>
	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
	<div id="editcell">
		<table class="table table-striped" id="categoryList">
			<thead>
				<tr>
					
					<th width="3%">
                    	<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);;" />
                    </th>
					
					<th class="title" width="7%" align="left" style="text-align:left;">
                    	<?php echo JHtml::_('grid.sort', 'ID', 'a.donor_id', $listDirn, $listOrder); ?>
                    </th>
                    
                    <th class="title" width="20%" align="left" style="text-align:left;">
                    	<?php echo JHtml::_('grid.sort', 'Name', 'u.name', $listDirn, $listOrder); ?>
                    </th>
                    
                    <th class="title" width="15%" align="left" style="text-align:left;">Email</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Telephone</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Mobile Phone</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Birthday</th>
                    <th class="title" width="15%" align="left" style="text-align:left;">Last Donation</th>
					<th width="5%">Published</th>
                    					
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
$canCreate	= $user->authorise('core.create', 'com_donorforce.donors.'.$item->donor_id);
$canEdit	= $user->authorise('core.edit', 'com_donorforce.donors.'.$item->donor_id);
$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange	= $user->authorise('core.edit.state', 'com_donorforce.donors.'.$item->donor_id) && $canCheckin;
$linkEdit 	= JRoute::_( 'index.php?option=com_donorforce&task=donor.edit&donor_id='. $item->donor_id );

					
echo '<tr class="row'. $i % 2 .'">';
					
echo '<td class="center">'. JHtml::_('grid.id', $i, $item->donor_id) . '</td>';

echo '<td>';
echo $item->donor_id;
echo '</td>';

echo '<td>';
echo '<a href="'. JRoute::_($linkEdit).'">'.  $item->name_first.' '.$item->name_last .'</a>';
echo '</td>';

echo '<td>';
echo $item->email;
echo '</td>';

echo '<td>';
echo $item->phone;
echo '</td>';

echo '<td>';
echo $item->mobile;
echo '</td>';

echo '<td>';
echo $item->dateofbirth;
echo '</td>';

echo '<td>';
echo $item->Donation_LastDate;
echo '</td>';

echo '<td class="center">'. JHtml::_('jgrid.published', $item->published, $i, 'donors.', $canChange) . '</td>';

echo '</tr>';						
		
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
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
</form>