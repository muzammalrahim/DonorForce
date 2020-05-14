<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die();


class DonorforceControllerDonations_record extends DonorforceController
{
		
	function display() {
		parent::display();
    }
	
 public function deleteSubscription()
 {
	 $app      = JFactory::getApplication();
	 $model    = $this->getModel('donations_record');
	 $data     = JRequest::get('get');
	 
	 $del = $model->deleteSubscription($data['id']);
	 
	 if($del)
	 {
		$app->redirect('index.php?option=com_donorforce&view=donations_record',"Subscription deleted successfully");	 
	 }
	 else
	 {
		$app->redirect('index.php?option=com_donorforce&view=donations_record',"Sorry failed to delete ");	  
	 }
 }
}
?>