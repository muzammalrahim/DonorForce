<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');
define('DS', DIRECTORY_SEPARATOR);
if (!JFactory::getUser()->authorise('core.manage', 'com_donorforce')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
 
// require helper file
JLoader::register('DonorforceHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'donorforce.php');



// Get an instance of the controller prefixed by ChefsNews
// this line will create donorforceController.
// Joomla will look for the declaration of that class in an aptly named file called controller.php (it's a default behavior).
$controller = JControllerLegacy::getInstance('donorforce');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
?>