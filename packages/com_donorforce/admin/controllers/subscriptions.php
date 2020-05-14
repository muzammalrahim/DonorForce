<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/



// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');


class DonorforceControllerSubscriptions extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Subscription', $prefix = 'donorforceModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function export(){
		$model  = $this->getModel('subscriptions');
		$model->exportxls();
	  return true; 	 	 
	}

	//This function exportDO is written for the importation of Debit order files
	public function exportDO(){
		$model  = $this->getModel('subscriptions');
		$model->exportDO();
	  return true; 	 	 
	}
	
	public function import(){		
				$viewName = JRequest::getCmd('view', $this->getName());
        $view = & $this->getView($viewName, 'html');
        $view->setModel($this->getModel($viewName), true); 
        $view->setLayout('import');				
        $view->import();
	}
	
	public function uploadfile(){
			$model = $this->getModel('subscriptions');		
			$dest  = $model->Upload_File();
			//$model->Read_XLSFile($dest); 
		}
	
}
?>
