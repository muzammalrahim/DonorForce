<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class DonorforceControllerAdddonation extends JControllerForm
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

	protected function allowEdit($data = array(), $key = 'donor_history_id') {
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
		//$model= $this->getModel( 'adddonation');
		//echo "<br /> postSaveHook  <pre>"; print_r($validData);  exit;  
		$model->sendemail($item->donor_history_id);
		
		$app = JFactory::getApplication();
		if(isset($validData[action]) && $validData[action] == 'adddonation.save2new'){
			$app->redirect('index.php?option=com_donorforce&view=adddonation', 'Donation Added Successfully');
		}
		$app->redirect('index.php?option=com_donorforce', 'Donation Added Successfully');
		//print_r();exit;
	}
	
	/*function save(){
		
	}*/
	
	function cancel(){
		$app = JFactory::getApplication();
		$app->redirect('index.php?option=com_donorforce');
	}
	
	
	
	
	
}