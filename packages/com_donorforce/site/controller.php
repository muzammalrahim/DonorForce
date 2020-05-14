<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');


/**
 * timesheet Component Controller
 */
class DonorforceController extends JControllerLegacy
{		

	function __construct(){
		parent::__construct();
	}
	
	public function display($cachable = false, $urlparams = false)
	{
		
		/*$user=JFactory::getUser();
		
		if(empty($user->id))
		{			
			$app = JFactory::getApplication();
			//$app->enqueueMessage(JText::_('Your are not authorized to access this area.'), 'message');
			$app->redirect(JUri::base() . 'index.php?option=com_donorforce&view=login', '', 302);

			return false;
		}
		*/
		
		parent::display();
	}
	
	

}


?>