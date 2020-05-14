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

class DonorforceModelDonation extends JModelAdmin
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
		$form 	= $this->loadForm('com_donorforce.donation', 'donation', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_donorforce.edit.donation.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	function save ($data)
	{
		
		/*$toEmail=DonorforceHelper::getInspectorEmail($data['inspector_id']);
		$email =& JFactory::getMailer();
		$email->addRecipient($toEmail);
		$email->setSubject('Donation Assign  ');
		$content  = "Following Donation has Assign to you, Please login to view your donations<br>";
		$content .= "<b>Donation Title : </b>".$data['title']."<br>";
		$content .= "<b> Donation Reference : </b>".$data['donation_reference']."<br>";
		
		$content .= "<b>Donation Description : </b>".$data['description']."<br>";
		
		$email->setBody($content);
		$email->IsHTML(true);
		if($toEmail != '' )
		{
			$email->Send();
		}*/
		return parent::save($data);
		
	}
}
?>