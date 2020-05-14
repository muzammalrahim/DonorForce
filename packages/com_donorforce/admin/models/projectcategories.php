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
 * Projects Model
 * The donorforceViewProjects class asks the model for data using the get method of the JView class:
 ** site/views/inspectors/view.html.php
 */
class DonorforceModelProjectCategories extends JModelList  // <Component-name>View<Model-name>
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'pcategory_id', 'a.pcategory_id'				
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
		
		$filter_order = JRequest::getCmd('filter_order', 'a.pcategory_id');
		$this->setState($this->context.'filter_order', $filter_order);
		
		$filter_order_Dir = JRequest::getCmd('filter_order_Dir');
		$this->setState($this->context.'filter_order_Dir', $filter_order_Dir);

		// List state information.
		parent::populateState($filter_order, $filter_order_Dir);
	}

	
	function getListQuery(){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('a.*');
		// From the hello table
		$query->from('#__donorforce_pcategory AS a');
		
		$orderCol	= $this->state->get('list.ordering', 'a.pcategory_id');
		$orderDirn	= $this->state->get('list.direction', 'asc');
				
		$query->order($db->escape($orderCol.' '.$orderDirn));
		
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