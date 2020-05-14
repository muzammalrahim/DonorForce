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


class DonorforceControllerManagement extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Management', $prefix = 'donorforceModel',$config = array()) 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function export(){
		$model  = $this->getModel('subscriptions');
		$model->exportxls();
	  return true; 	 	 
	}
	
	public function import(){		
			 JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=management&layout=import');	
			/*
			$viewName = JRequest::getCmd('view', $this->getName());
			echo "<br /> viewName = ".$viewName; 
			$view =  $this->getView($viewName, 'html');
			$view->setModel($this->getModel($viewName), true); 
			$view->setLayout('import');				
			$view->import();
			*/
	}

	//This section of code is for the importation of Debit Oreder files
	public function import_DO(){		
			 JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=management&layout=import_DO');	
	}
	
 	public function exportCSV(){
		
		//echo "<pre> cid = "; print_r( $cid  ); echo "</pre>";  exit; 
		$model  = $this->getModel();
		$model->exportCSV();		
	}
	
	public function uploadfile(){
			$model = $this->getModel('management');		
			$dest  = $model->Upload_File();
			//$model->Read_XLSFile($dest); 
		}
	
	public function deleteDonations(){
		
		$model = $this->getModel('management');		
		$result  = $model->deleteDonations();
		
		if($result){
			  JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=management','Donations Deleted Succesfully','success'); 
			 
		}else{
			  JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=management','Error Deleted Donations','error'); 
		}
		
		 
	}
	
}
?>
