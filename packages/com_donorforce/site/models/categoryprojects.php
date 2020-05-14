<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 

jimport('joomla.application.component.modellist');

class DonorforceModelCategoryProjects extends JModelList
{
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_donorforce');
		$db		= $this->getDbo();
		// List state information
		$format = JRequest::getWord('format');
		if ($format=='feed') {
			$limit = $app->getCfg('feed_limit');
		}
		else {
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		}
		$this->setState('list.limit', $limit);

		$limitstart = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		// Get list ordering default from the parameters
		$menuParams = new JRegistry();
		if ($menu = $app->getMenu()->getActive()) {
			$menuParams->loadString($menu->params);
		}
		$mergedParams = clone $params;
		$mergedParams->merge($menuParams);

		$orderCol	= JRequest::getCmd('filter_order', $mergedParams->get('initial_sort', 'ordering'));
		if (!in_array($orderCol, $this->filter_fields)) {
			$orderCol = 'ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$listOrder	=  JRequest::getCmd('filter_order_Dir', 'ASC');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);

		
		$this->setState('filter.language', $app->getLanguageFilter());

		// Load the parameters.
		$this->setState('params', $params);
		
		$id = $app->input->get('pcategory_id', 0, 'int');
		$this->setState('pcategory.id', $id);	
	}

	
	function getListQuery(){
	  
		  $db = JFactory::getDBO();
		  $query = $db->getQuery(true);
		    
		  $query="SELECT
						p.*,c.title
					FROM
						#__donorforce_project AS p INNER JOIN #__donorforce_pcategory AS c ON c.pcategory_id = p.pcategory_id "
					."WHERE p.published = 1 AND p.pcategory_id = ".$this->getState('pcategory.id');
		  
	  return $query;
	 
	 }
	 
	 function getProject($pid)
	 {
		 
		  
		  $db = JFactory::getDBO();
		  $query = $db->getQuery(true);
		  
		  $query="  SELECT
						#__donorforce_project.project_id,
						#__donorforce_project.`name`,
						#__donorforce_project.description,
						#__donorforce_project.fundraising_goal,
						#__donorforce_project.total_raised
					FROM
						#__donorforce_project
					WHERE
						#__donorforce_project.project_id IN(".implode(",",$pid).") ";
		
			$db->setQuery($query);
   
			$objlist= $db->loadObjectList();
			//var_dump ($objlist); exit;
			return $objlist; 
	 }	
}