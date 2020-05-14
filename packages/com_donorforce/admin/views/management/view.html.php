<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// import Joomla view library
jimport( 'joomla.application.component.view' );

/**
 * HTML View class for the DonorForce Component
 */
class DonorforceViewManagement extends JViewLegacy // <Component-name>View<View-name>
{
	
	protected $items;
	protected $pagination;
	protected $state;
	protected $tmpl;
	
    // Overwriting JView display method
	function display($tpl = null)
   {
			
		// Get data from the model
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
	
		$this->filterForm = $this->get('FilterForm');
 		$this->activeFilters = $this->get('ActiveFilters');
		
		$jinput = JFactory::getApplication()->input;
		$donor_id = $this->state->get('donor_list', '');
		$project_id = $this->state->get('project_list', '');
		$donation_status = $this->state->get('donation_status', '');
		$this->searchDateFrom  = $this->state->get('search.datefrom', '');
		$this->searchDateTo  = $this->state->get('search.dateto', '');
		$search = $this->state->get('filter.search', '');
		
		/* 
 		$Donor_list   =  $this->get('DonorList');
		$Project_list =  $this->get('ProjectList');
		*/
		
		$report_model =  JModelList::getInstance( 'Reports', 'DonorforceModel' );
  		$Donor_list   =  $report_model->getDonorList(); 
		$Project_list =  $report_model->getProjectList();
		
		
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
	 
	 
		if($this->getLayout() == 'process_csv') {
			JToolBarHelper::custom('management.clear_data','new','icon-new icon-white','Clear List','');	
			JToolBarHelper::custom('management.process_successful','new','','Allocate Success','');	
			JToolBarHelper::custom('management.process_ignore','new','','Clear Ignore','');	
		}

		if($this->getLayout() == 'process_csv_do') {
			JToolBarHelper::custom('management.clear_data','new','icon-new icon-white','Clear List','');	
			JToolBarHelper::custom('management.process_successful','new','','Allocate Success','');	
			JToolBarHelper::custom('management.process_ignore','new','','Clear Ignore','');	
		}
		
		if($this->getLayout() == 'default') {
			JToolBarHelper::custom('management.import','new','icon-new icon-white','Import','');
			JToolBarHelper::custom('management.exportCSV','new','icon-new icon-white','Export CSV',true);
			JToolBarHelper::custom('management.deleteDonations','new','icon-new icon-white','Delete Donations',true);
				
		}
		
		 
		
		JToolBarHelper::title(   JText::_( 'Donation Management' ), 'banners.png' );
		$donorForceConfig = simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR.DS.'donorforce.xml');
		$this->donorForceVersion = (string)$donorForceConfig->version;
		// Assign data to the view
		
		$this->assignRef( 'donor_id', $donor_id );
		$this->assignRef( 'project_id', $project_id);
		$this->assignRef( 'donation_status', $donation_status);
		$this->assignRef( 'search', $search);
		
		
		$this->assignRef( 'Donor_list', $Donor_list );
		$this->assignRef( 'Project_list', $Project_list );
		$this->assignRef( 'DonationDateFrom', $DonationDateFrom);
		$this->assignRef( 'DonationDateTo', $DonationDateTo);
		
		
		
			parent::display($tpl);
			JToolBarHelper::preferences('com_donorforce');
    }
		
		protected function getSortFields()
		{
			return array(
			'dh.donor_history_id' => JText::_('JGRID_HEADING_ID'),
			'dh.date' => JText::_('Date'),
			'dh.Reference' => JText::_('Reference'),
			'p.name' => JText::_('Project'),
			'd.name_first' => JText::_('Donor'),
			'dh.amount' => JText::_('Amount'),
			'dh.status' => JText::_('Donation Status'),
			);
	 }
		
}



?>