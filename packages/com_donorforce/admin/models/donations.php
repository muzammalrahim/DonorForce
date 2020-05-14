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
class DonorforceModelDonations extends JModelList // <Component-name>View<Model-name>
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'donation_id', 'a.donation_id',
				'name_first', 'd.name_first',
				'name_last', 'd.name_last',
				'org_type', 'd.org_type',	
				'org_name', 'd.org_name',	
			);
		}
		parent::__construct($config);
		
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

		$filter_order = JRequest::getCmd('filter_order', 'a.donation_id');
		$this->setState($this->context.'filter_order', $filter_order);
		
		$filter_order_Dir = JRequest::getCmd('filter_order_Dir');
		$this->setState($this->context.'filter_order_Dir', $filter_order_Dir);

		// List state information.
		parent::populateState($filter_order, $filter_order_Dir);
	}

	
	function getListQuery(){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		//Filter
		$search = $this->getState('filter.search');


			$query="
			SELECT
				a.donation_id,
				d.name_first,
				d.name_last,
				d.org_type,
				d.org_name
			FROM
				#__donorforce_rec_donation AS a
			INNER JOIN #__donorforce_donor AS d ON a.donor_id = d.cms_user_id";	


		if (!empty($search)){
			$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));

			$query.=" WHERE d.name_first LIKE ".$search . "OR d.name_last LIKE ".$search ;

		}
		
		$orderCol	= $this->getState('list.ordering','a.donation_id');
		$orderDirn	= $this->getState('list.direction', 'asc');
		
		$query .= " ORDER BY ".$orderCol." ".$orderDirn;

		return $query;
	
	}

}