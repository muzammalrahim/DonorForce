<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class DonorforceViewReports extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
	protected $pagination;
	
	/**
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	
	 
	function display($tpl=null) 
	{		
		JToolBarHelper::title(   JText::_( 'Reports Management' ), 'banners.png' );
		JToolBarHelper::custom('reports.export','new','icon-new icon-white','Export XLS','');
		JToolBarHelper::custom('reports.exportuser','new','icon-new icon-white','Export user','');
		 
		// Get data from the model
		$jinput = JFactory::getApplication()->input;
		$SubmitForm = $jinput->get('SubmitForm', 'Search');
		$this->select_limit = $jinput->get('select_limit', '0');
		$Tab = $jinput->get('Tab', 'search');
		$donor_id = $jinput->get('donor_list', '');
		$project_id = $jinput->get('project_list', '');
		$donation_status = $jinput->get('donation_status', '');
		
		$this->searchDateFrom  = $jinput->get('search_datefrom', '');
		$this->searchDateTo  = $jinput->get('search_dateto', '');
 		
		
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
 		$Donor_list   =  $this->get('DonorList');
		$Project_list =  $this->get('ProjectList');
		$display_project = $jinput->get('displayproject');
		
		$model = $this->getModel( 'reports' );
		$displaycategory = $jinput->get('displaycategory', 'project');
		$donor_id2 = $jinput->get('donor_list2', '');
		$Donationlimitstart = $jinput->get('TotalDonationlimitstart', '0');
		$DonationDateFrom = $jinput->get('datefrom','');
		$DonationDateTo = $jinput->get('dateto','');
		
		 
		$parameters = array(
					'donor_id2' => $donor_id2,
					'DonationDateFrom' => $DonationDateFrom,
					'DonationDateTo' => $DonationDateTo,
					'displaycategory' => $displaycategory,
					'displayproject'  => $display_project
		);
					
		$Count_Donation = $model->CountTableWhere($parameters);
		$limit = 20;  $limit = ($this->select_limit > 0)? $this->select_limit : 999999999; 
		$Donation_pagination = new JPagination($Count_Donation, $Donationlimitstart, $limit, 'TotalDonation');
		
		
		$Limits = ' LIMIT '.$limit. " OFFSET " . $Donationlimitstart;
		$parameters['Limits'] = $Limits; 
		
		
		$TotalDonationList = $model->getTotalDonationList($parameters);	
		$OverallDonations = $model->getOverall_donaiton($parameters);

		//this code is for the new tab Project Association

		$this->select_limit_mail = $jinput->get('select_limit_mail', '0');
		$Donationlimitstart_mail  = $jinput->get('TotalDonationMaillimitstart', '0');
		$project_id_mail = 		$jinput->get('displayproject_mail', '');
		$DonationDateFrom_mail 	 = $jinput->get('datefrom_mail','');		
		$DonationDateTo_mail = $jinput->get('dateto_mail','');
		$displaycontact 	 = $jinput->get('displaycategory_mail','');
		$displaydonation_status   = $jinput->getString('displaydonation_status','default_value');
		$displaycontactall 	 = 3;
		$displaymembership = $jinput->get('displaymembership','');
		
		$parameters_mail = array(
					'displaycategory_mail' => $displaycontact,
					'displaycontactall' => $displaycontactall,
					'DonationDateTomail' => $DonationDateTo_mail,
					'DonationDateFrommail' => $DonationDateFrom_mail,
					'displayproject_mail' => $project_id_mail,
					'displaydonation_status' => $displaydonation_status,
					'displaymembership' => $displaymembership
		);

		$Count_Donation_mail = $model->CountTableWhere_mail($parameters_mail);
		$limit_mail = 20;  $limit_mail = ($this->select_limit_mail > 0)? $this->select_limit_mail : 999999999; 
		$Donation_pagination_mail = new JPagination($Count_Donation_mail, $Donationlimitstart_mail, $limit_mail, 'TotalDonationMail');
		
		
		$Limits_mail = ' LIMIT '.$limit_mail. " OFFSET " . $Donationlimitstart_mail;
		$parameters_mail['Limits'] = $Limits_mail; 
		$TotalDonationList_mail = $model->getTotalDonationListmail($parameters_mail);
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		
		$this->assignRef( 'history', $this->items );
		$this->assignRef( 'Tab', $Tab);
		$this->assignRef( 'Donor_list', $Donor_list );
		$this->assignRef( 'Project_list', $Project_list );
		$this->assignRef( 'donor_id', $donor_id );
		$this->assignRef( 'donor_id2', $donor_id2 );
		$this->assignRef( 'project_id', $project_id);
		$this->assignRef( 'donation_status', $donation_status);
		
		$this->assignRef( 'TotalDonationList', $TotalDonationList); 
		$this->assignRef( 'Donation_pagination', $Donation_pagination);
		$this->assignRef( 'displaycategory', $displaycategory);
		$this->assignRef( 'displayproject', $display_project );
		
		$this->assignRef( 'DonationDateFrom', $DonationDateFrom);
		$this->assignRef( 'DonationDateTo', $DonationDateTo);
		$this->assignRef( 'OverallDonations', $OverallDonations );

		//These assignings are done for the new tab Project Association

		$this->assignRef( 'displaymembership', $displaymembership);
		$this->assignRef( 'displaycategory_mail', $displaycontact );
		$this->assignRef( 'displaydonation_status', $displaydonation_status );
		$this->assignRef( 'displayproject_mail', $project_id_mail );
		$this->assignRef( 'displaycontactall', $displaycontactall );
		$this->assignRef( 'DonationDateFrommail', $DonationDateFrom_mail);
		$this->assignRef( 'DonationDateTomail', $DonationDateTo_mail);
		$this->assignRef( 'TotalDonationMail', $TotalDonationList_mail);
		$this->assignRef( 'LIMIT', $Limits_mail);
		

		//For tab3 
		$this->assignRef( 'Donationlimitstart_mail', $Donationlimitstart_mail );
		$this->assignRef( 'project_id_mail', $project_id_mail );
		$this->assignRef( 'DonationDateFrom_mail', $DonationDateFrom_mail );
		$this->assignRef( 'DonationDateTo_mail', $DonationDateTo_mail );
		$this->assignRef( 'displaycontact', $displaycontact );
		$this->assignRef( 'displaycontactall', $displaycontactall );
		$this->assignRef( 'Donation_pagination_mail', $Donation_pagination_mail );
		$this->assignRef( 'TotalDonationList_mail', $TotalDonationList_mail );
		
		parent::display($tpl);
		
	}
	
	protected function _form( $tpl=null )
	{
		/*$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		
		$lists 	= array();
		
		$user 		= JFactory::getUser();
		$model		= $this->getModel();
		
		$this->addToolbar();*/
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		/*JRequest::setVar('hidemainmenu', true);
		$isNew		= ($this->item->donor_history_id == 0);
		$user	= JFactory::getUser();
		JToolBarHelper::title(JText::_('Donation Management'), 'newsfeeds.png');
		$canDo		= $this->getActions($this->state->get('filter.id'));
		
		JToolBarHelper::apply('adddonation.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('adddonation.save', 'JTOOLBAR_SAVE');
		
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('adddonation.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('adddonation.cancel', 'JTOOLBAR_CLOSE');
		}
		//JToolBarHelper::divider();*/
	}
	
	public static function getActions($classId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		
		if (empty($id)) {
			$assetName = 'com_donorforce';
		} else {
			$assetName = 'com_donorforce.adddonations.'.(int) $id;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}  
	  
}
?>