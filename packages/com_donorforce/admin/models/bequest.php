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

class DonorforceModelBequest extends JModelAdmin
{
	
	protected	$option 		= 'com_donorforce';
	protected 	$text_prefix	= 'com_donorforce';
	
    function __construct()
    {
        parent::__construct();
    }
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_donorforce.bequest', 'bequest', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_donorforce.edit.bequest.data', array());

		if (empty($data)) {
			$data = $this->getItem();			
		}
		return $data;
	}
	
	public function save($data)
	{	
		if(empty($data['date_start']))$data['date_start']=date('Y-m-d H:i:s');
		
		$data['cms_user_id'] = $data['users'];
		
		
		return parent::save($data);
	}
	
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();

		if ($pk > 0)
		{			
			$db=JFactory::getDbo();
			
			$query="
				SELECT
					b.*,	
					u.username,
					u.email
				FROM
					#__donorforce_bequest AS b
				LEFT JOIN #__users as u ON u.id = b.cms_user_id
				WHERE
					b.bequest_id 	 = $pk ";
			
			$db->setQuery($query);
			
			$item=$db->loadObject();
			
			if ($error = $db->getErrorMsg()) 
			{
				$this->setError($error);
				return false;				
			}

			return $item;
		}

		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');

		return $item;
	}
}
?>