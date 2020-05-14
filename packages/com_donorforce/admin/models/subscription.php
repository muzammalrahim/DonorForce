<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');

class DonorforceModelSubscription extends JModelAdmin
{
	
	protected	$option 		= 'com_donorforce';
	protected 	$text_prefix	= 'com_donorforce';
	
    function __construct()
    {
        parent::__construct();
    }
	
    /*protected function prepareTable(&$table)
	{
	}*/
	 
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_donorforce.subscription', 'subscription', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_donorforce.edit.subscription.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	function save ($data)
	{
		
		return parent::save($data);
		
	}
}
?>