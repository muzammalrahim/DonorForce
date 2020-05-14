<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of banner records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_churchadmin
 * @since		1.6
 */
class  DonorforceModelTemplates extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'id',
				'name',
				'published', 
				'description', 'description',
				 
			);
		}

		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
     * Use in get list item in administrator
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		/*// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'*'
			)
		);
        // Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(state IN (0, 1))');
		}
        // Filter by search in title
		$search = $this->getState('filter.search');
        $searchType = $this->getState('filter.search_type');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                if (empty($searchType)) {
                    $query->where('(name LIKE '.$search.' or description LIKE '.$search.') ');
                } elseif ($searchType == 'name') {
                    $query->where('(name LIKE '.$search.')');
                } else {
                    $query->where('(description LIKE '.$search.')');
                }
				
			}
		}
        // Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'ordering');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		$query->order($db->escape($orderCol.' '.$orderDirn));
        //
		$query->from($db->quoteName('#__sms_smsgroup'));*/
		$query="select * from #__donorforce_templates";
		return $query;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id .= ':'.$this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Donorforcegroup', $prefix = 'DonorforceTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

        $search = $this->getUserStateFromRequest($this->context.'.filter.search_type', 'filter_search_type');
		$this->setState('filter.search_type', $search);
        
		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		// Load the parameters.
//		$params = JComponentHelper::getParams('com_churchadmin');
//		$this->setState('params', $params);

		// List state information.
		parent::populateState('name', 'asc');
	}
	
}
