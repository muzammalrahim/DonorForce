<?php
// No direct access to this file

/*------------------------------------------------------------------------
# com_timesheet  mod_timesheet
# ------------------------------------------------------------------------
# author    Pixako Web Designs & Development
# copyright Copyright (C) 2010 http://www.pixako.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.pixako.com
# Technical Support:  Contact - http://www.pixako.com/contact.html
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
 

jimport('joomla.application.component.modellist');

class DonorforceModelDonationSimple extends JModelList
{



public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_donorforce.donation', 'donor', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) {
			return false;
		}
		return $form;
	}
protected function loadFormData()
{
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_donorforce.default.donation.data', array());
	if (empty($data)) {
		//$data = $this->getItem();
	}
	return $data;
}
	

}