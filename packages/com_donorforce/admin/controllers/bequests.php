<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');


class DonorforceControllerBequests extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Bequest', $prefix = 'donorforceModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
}
?>
