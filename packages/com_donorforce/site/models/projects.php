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

class DonorforceModelProjects extends JModelList
{
	
	function getListQuery(){
	  
		  $db = JFactory::getDBO();
		  $query = $db->getQuery(true);
		  
		  $query="SELECT
						*
					FROM
						#__donorforce_project WHERE published = 1";
		  
	  return $query;
	 
	 }
	 
	 
	 
	 	public function getTotalRaised($pid){
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
			var_dump ($objlist); //exit;
			return $objlist; 
	 }	
}