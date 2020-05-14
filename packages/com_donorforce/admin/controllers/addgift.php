<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class DonorforceControllerAddgift extends JControllerForm
{

	protected	$option 		= 'com_donorforce';
	
	function __construct($config=array()) {
		parent::__construct($config);
	}
	
	protected function allowAdd($data = array()) {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.create', 'com_donorforce');
		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'gift_id') {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow		= $user->authorise('core.edit', 'com_donorforce');
		
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}
	
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$item = $model->getItem();
		$model->sendemail($item->gift_id);		
		$app = JFactory::getApplication();
		$app->redirect('index.php?option=com_donorforce', 'Gift Added Successfully');		
	}
	

	function cancel(){
		$app = JFactory::getApplication();
		$app->redirect('index.php?option=com_donorforce');
	}
	
	
	
	
	
}