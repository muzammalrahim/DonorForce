<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class DonorforceControllerDonor extends JControllerForm
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

	protected function allowEdit($data = array(), $key = 'donor_id') {
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
	public function save($key = null, $urlVar = null)
	{
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$data['donor_id'] = preg_replace('/[^0-9]/', '', $data['donor_id']);  //(int)$data['donor_id'];
		
		/* if($data['donor_id']<1)
		{
			if(empty($data['password']) || empty($data['password2']))
			{ 
				$this->setMessage(JText::_('Passwords fields are empty'), 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_donorforce&view=donor&layout=edit&id'.$data['id'], false));
				return false;
			}
		} */
	 
		// TODO: JForm should really have a validation handler for this.
		if (isset($data['password']) && isset($data['password2']))
		{
			// Check the passwords match.
			if ($data['password'] != $data['password2'])
			{
				$this->setMessage(JText::_('JLIB_USER_ERROR_PASSWORD_NOT_MATCH'), 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_users&view=user&layout=edit', false));
					return false;
			}

			unset($data['password2']);
		}
		
		return parent::save();
	}
	
	// function to delete the donation 
	function delete_donation(){
		$donorforce_history_id = $_REQUEST['id']; 
		$jinput = JFactory::getApplication()->input;
		$donor_id = $jinput->get('donor_id', '');
		$model = $this->getModel();
		$model->delete_donorforce_history($donorforce_history_id,$donor_id ); 
	}
	
	
	
	function resend_tankyou(){
		$donorforce_history_id = $_REQUEST['id']; 
		$jinput = JFactory::getApplication()->input;
		$donor_id = $jinput->get('donor_id', '');
		$model = $this->getModel();
		$model->resend_tankyou($donorforce_history_id,$donor_id ); 
	}
	
	function resend_receipt(){
		$donorforce_history_id = $_REQUEST['id']; 
		$jinput = JFactory::getApplication()->input;
		$donor_id = $jinput->get('donor_id', '');
		$model = $this->getModel();
		$model->resend_receipt($donorforce_history_id,$donor_id ); 
	}
	
	
	function GetDonors(){		 
		/*$jinput = JFactory::getApplication()->input;
		$donor_id = $jinput->get('donor_id', '');*/
		$model = $this->getModel();		
		return $model->GetDonorsData(); 		
	}	
	
}