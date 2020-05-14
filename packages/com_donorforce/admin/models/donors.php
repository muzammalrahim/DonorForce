<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined( '_JEXEC' ) or die('Restricted access');
// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * Donors Model
 * The donorforceViewDonors class asks the model for data using the get method of the JView class:
 ** site/views/inspectors/view.html.php
 */
class DonorforceModelDonors extends JModelList // <Component-name>View<Model-name>
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'donor_id', 'a.donor_id', 
				'name_first','a.name_first'				
			);
		}
		
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Adjust the context to support model layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		//$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		//$this->setState('filter.search', $search);

		//$access = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		//$this->setState('filter.access', $access);

		//$cnewsId = $this->getUserStateFromRequest($this->context.'.filter.id', 'filter_NewsID');
		//$this->setState('filter.id', $cnewsId);
		
		$filter_order = JRequest::getCmd('filter_order', 'a.donor_id');
		$this->setState($this->context.'filter_order', $filter_order);
		
		$filter_order_Dir = JRequest::getCmd('filter_order_Dir','asc');
		$this->setState($this->context.'filter_order_Dir', $filter_order_Dir);

		// List state information.
		parent::populateState($filter_order, $filter_order_Dir);
	}

	
	function getListQuery(){
		
		
		$input	= JFactory::getApplication()->input;
		
		$search = $this->getState('filter.search');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('a.*,u.username,u.email,
			(
					SELECT dh.date
					FROM #__donorforce_history AS dh
					WHERE  dh.donor_id=a.donor_id 
					ORDER BY dh.date DESC 
					LIMIT 1
					) AS Donation_LastDate'
		);
		// From the hello table
		$query->from('#__donorforce_donor AS a');
		
		$query->join('LEFT', '#__users AS u ON u.id=a.cms_user_id');
		
		
		if($search != '')	
		{
		
		 $where= " ( u.name like '%".$search."%'
		 OR
		 u.email like '%".$search."%'
		 OR
		 u.username like '%".$search."%'
		 OR
		 a.name_first like '%".$search."%'
		 OR 
		 a.name_last like '%".$search."%'
		 OR 
		 a.phone like '%".$search."%'
		 OR 
		 a.mobile like '%".$search."%'
		)
		 ";
		
		$query->where($where);
		
		}
		
		
		
		$orderCol	= $this->state->get('list.ordering', 'a.donor_id');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
		if($orderCol == '')
		$orderCol = 'u.name';
				
		$query->order($db->escape($orderCol.' '.$orderDirn));
		
		//echo $query;
		//echo "<br />  query = " . $query;
		return $query;
	
	}
	
	/*
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.id');

		return parent::getStoreId($id);
	}
	*/
}