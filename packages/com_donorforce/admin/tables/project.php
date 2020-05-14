<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableProject extends JTable
{
	
	function __construct(& $db) {
		parent::__construct('#__donorforce_project', 'project_id', $db);
	}
}

?>