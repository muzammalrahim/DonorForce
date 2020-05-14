<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class DonorforceControllerReports extends JControllerLegacy
{

	protected	$option 		= 'com_donorforce';
	
	function __construct($config=array()) { //echo "C constructor";  exit; 
		parent::__construct($config);
	}
	
	protected function allowAdd($data = array()) { //echo "C allowAdd";  exit; 
		$user		= JFactory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.create', 'com_donorforce');
		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'donation_id') {  echo "C allowEdit";  exit;
		$user		= JFactory::getUser();
		$allow		= null;
		$allow		= $user->authorise('core.edit', 'com_donorforce');
		
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}
	
	/**
	 * Overrides parent save method 
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function export(){
		//echo "<br /> export "; exit; 
		$model  = $this->getModel('reports');
		$model->exportxls();
	  return true; 	 	 
		}

	public function exportuser(){
		$model  = $this->getModel('reports');
		$model->exportuser();
	  return true; 	 	 
	}
	
}