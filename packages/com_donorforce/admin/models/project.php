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

class DonorforceModelProject extends JModelAdmin
{
	
	protected	$option 		= 'com_donorforce';
	protected 	$text_prefix	= 'com_donorforce';
	
    function __construct()
    {
        parent::__construct();
    }
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_donorforce.project', 'project', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_donorforce.edit.project.data', array());

		if (empty($data)) {
			$data = $this->getItem();			
		}
		//echo "<pre> data "; print_r( $data  ); echo "</pre>";  		
		return $data;
	}
	
	public function save($data)
	{	
		if(empty($data['date_start']))$data['date_start']=date('Y-m-d H:i:s');
		
		return parent::save($data);
	}
	
	public function TotalRaised($pid){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query = "SELECT sum(dh.amount) As total_raised
						  FROM #__donorforce_history AS dh
						  Where dh.project_id = ".$pid."
							AND dh.status != 'pending' 
							AND dh.status != 'Pending' ";
		$db->setQuery($query);		
		return $db->loadObject()->total_raised; 
		 
	} 
	
}
?>