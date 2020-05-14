<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');
// import Joomla controller library

/**
 * ChefsNews Component Controller
 */
class DonorforceController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = false)
	  {
		//echo 'Welcome To the Component \'s main Interface. ';
		parent::display();
		
		// Set the submenu
		DonorforceHelper::addSubmenu(JRequest::getCmd('view'));
	  }
}

?>