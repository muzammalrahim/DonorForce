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

class DonorforceModelDonations_record extends JModelList
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
	 
	 public function getDonorSubscriptions()
	{
					
		$db        =  JFactory::getDbo();
		
		$user      =  JFactory::getUser();
		$id        =  $user->get('id');
		$donor     =  DonorForceHelper::getFullUserInfo($id);
		
		$query="
			SELECT
				#__donorforce_donor_subscriptions.subscription_id,
				#__donorforce_donor_subscriptions.project_id,
				#__donorforce_donor_subscriptions.donation_type,
				#__donorforce_donor_subscriptions.amount,
				#__donorforce_donor_subscriptions.source,
				#__donorforce_project.`name` AS project_name
			FROM
				#__donorforce_donor_subscriptions
			LEFT JOIN #__donorforce_project ON #__donorforce_donor_subscriptions.project_id = #__donorforce_project.project_id
			INNER JOIN #__donorforce_donor ON #__donorforce_donor_subscriptions.donor_id = #__donorforce_donor.cms_user_id
			WHERE
				#__donorforce_donor.donor_id = $donor->donor_id			
	
	";
	
		//echo $query;
		
		$db->setQuery($query);
		
		$items=$db->loadObjectList();
		
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}

		return $items;
		


		
	}
	
	public function getDonorHistory()
	{
					
		$db=JFactory::getDbo();
		$user      =  JFactory::getUser();
		$id        =  $user->get('id');
		$donor     =  DonorForceHelper::getFullUserInfo($id);
		
		$query="
		SELECT
			#__donorforce_project.`name` AS project_name,
			#__donorforce_history.date,
			#__donorforce_history.amount,
			#__donorforce_history.`status`,
			#__donorforce_history.donor_history_id
		FROM
			#__donorforce_history
		INNER JOIN #__donorforce_project ON #__donorforce_history.project_id = #__donorforce_project.project_id
		INNER JOIN #__donorforce_donor ON #__donorforce_donor.donor_id = #__donorforce_history.donor_id
		WHERE
			#__donorforce_donor.donor_id = $donor->donor_id				
	
	";
	
		//echo $query;
		
		$db->setQuery($query);
		
		$items=$db->loadObjectList();
		
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}

		return $items;
		


		
	}
	public function deleteSubscription($id)
	{
		$db      = JFactory::getDbo();
		$query   = "delete from #__donorforce_donor_subscriptions where subscription_id=".$id;
		$db->setQuery($query);
		if (!$db->query()) {
		 JError::raiseError(500, $db->getErrorMsg());
		  return false;
		} else {
		  return true;
		  }
	}
}