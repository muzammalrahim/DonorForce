<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TablePcategory extends JTable
{
	
	function __construct(& $db) {
		parent::__construct('#__donorforce_pcategory', 'pcategory_id', $db);
	}
}

?>