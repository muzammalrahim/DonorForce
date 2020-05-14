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
class DonorforceModelReports extends JModelList // <Component-name>View<Model-name>
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'donor_history_id', 'dh.donor_history_id'
							
			);
		}
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		//echo "<br /> M populateState"; //exit; 
		
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Adjust the context to support model layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}
		
		/*$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);*/
		
		$filter_order = JRequest::getCmd('filter_order', 'dh.donor_history_id');
		$this->setState($this->context.'filter_order', $filter_order);
		
		$filter_order_Dir = JRequest::getCmd('filter_order_Dir','asc');
		$this->setState($this->context.'filter_order_Dir', $filter_order_Dir);
		
		$filter_donor = JRequest::getCmd('donor_list');
		$this->setState($this->context.'filter_donor', $filter_donor);
		
		$filter_donor = JRequest::getCmd('project_list');
		$this->setState($this->context.'project_list', $filter_donor);
		
		$filter_donation_status = JRequest::getCmd('donation_status');
		$this->setState($this->context.'donation_status', $filter_donation_status);
		
		
		$filter_search_date_from = JRequest::getCmd('search_datefrom');
		$this->setState($this->context.'search_datefrom', $filter_search_date_from);
		
		$filter_search_date_to = JRequest::getCmd('search_dateto');
		$this->setState($this->context.'search_dateto', $filter_search_date_to);

/*
echo " <br />  filter_order = ".$filter_order; 
echo " <br />  filter_order_Dir = ".$filter_order_Dir; */

//echo " <br />  this->context = ".$this->context; 
//echo " <pre> this->setState = "; print_r($this->setState); 

		// List state information.
		//parent::populateState('a.name_first', 'asc');
		//parent::populateState();
		parent::populateState($filter_order, $filter_order_Dir);
		

		// List state information.
		//parent::populateState('a.name_first', 'asc');
		//parent::populateState($filter_order, $filter_order_Dir);
		 // parent::populateState('dh.donor_history_id','asc');
	}

	
	function getListQuery(){
		//echo "<br />  M getListQuery";  //exit; 
		
		$input	= JFactory::getApplication()->input;
		//$search = $this->getState('filter.search');
		//$search = $input->get('search');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		//$filter_org_type = $this->getState('filter.org_type');	
		// Select some fields
		$query->select('dh.*,d.name_title,d.name_first,d.name_last,p.name as project_name');
		// From the hello table
		$query->from('#__donorforce_history AS dh');
		$query->join('LEFT', '#__donorforce_donor AS d ON d.donor_id=dh.donor_id');
		$query->join('LEFT', '#__donorforce_project AS p ON p.project_id=dh.project_id');

		// Filter by access level.
		if ($filter_donor = $this->getState('com_donorforce.reportsfilter_donor'))
		{
			//echo "<hr />filter_donor = ".$filter_donor; 
			$query->where('dh.donor_id = ' . (int) $filter_donor);
		}
		
		if ($filter_project = $this->getState('com_donorforce.reportsproject_list'))
		{
			$query->where('dh.project_id = ' . (int) $filter_project);
		}
		
		if ($filter_donation_status = $this->getState('com_donorforce.reportsdonation_status'))
		{
			$query->where('dh.status = "' . $filter_donation_status.'"');
		}
		
		$filter_search_date_from = $this->getState('com_donorforce.reportssearch_datefrom');		
		$filter_search_date_to = $this->getState('com_donorforce.reportssearch_dateto');
		
		if( $filter_search_date_from != '' &&  $filter_search_date_to != ''){
			//$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			$query->where('dh.date BETWEEN "'.$filter_search_date_from.'" AND "'.$filter_search_date_to.'"');
			
		}else if($filter_search_date_from != '' ){
			$query->where('dh.date >= "' . $filter_search_date_from.'"');
		}else if($filter_search_date_to != ''){
			$query->where('dh.date <= "' . $filter_search_date_to.'"');
		}

		$orderCol	= $this->state->get('list.ordering','dh.donor_history_id');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
		if($orderCol == '') $orderCol='dh.donor_history_id';
		if($orderDirn == '') $orderDirn='asc';
		
		$query->order($db->escape($orderCol.' '.$orderDirn));
		// echo "<br />  query = " . $query;
		return $query;	
	}
	
	
	 function getDonationHistory()
	{
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('dh.*');
		$query->from('#__donorforce_history AS dh ');
		//$query->where($db->quoteName('profile_key') . ' LIKE '. $db->quote('\'custom.%\''));
		//$query->order('ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		
		//echo "<br /> query = ".$query;  exit; 
		
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadObjectList();
		//echo "<pre> results = "; print_r($results); exit; 
		
		return $results; 
		
	}
	
	function getDonorList(){		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('d.donor_id,d.name_title,d.name_first,d.name_last');
		$query->from('#__donorforce_donor AS d ');
		$query->order($db->escape('d.name_first asc'));
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results; 
	}
	function getProjectList(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('p.project_id,p.name');
		$query->from('#__donorforce_project AS p ');
		$query->order($db->escape('p.name asc'));
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results; 
	}

	function getProjectListmail(){
		$db_mail = JFactory::getDBO();
		$query_mail = $db_mail->getQuery(true);
		$query_mail->select('p.project_id,p.name');
		$query_mail->from('#__donorforce_project AS p ');
		$query_mail->order($db_mail->escape('p.name asc'));
		$db_mail->setQuery($query_mail);
		$results_mail = $db_mail->loadObjectList();
		return $results_mail; 
	}

	function getTotalDonationListmail($parameters_mail){ 
		$Limits_mail = $parameters_mail['Limits'];
		$displaydonation_status = $parameters_mail['displaydonation_status'];
		$Date_from_mail = $parameters_mail['datefrom_mail'];
		$Date_to_mail = $parameters_mail['dateto_mail']; 
		$displaycategory_m_mail = $parameters_mail['displaycategory_mail'];  
		$$display_contact_all_mail = $parameters_mail['displaycontactall'];
		$project_id_mail = $parameters_mail['displayproject_mail'];
		$displaymembership = $parameters_mail['displaymembership'];
		
		$where_mail = ' 1 '; 
		$db_mail = JFactory::getDBO();
		$query_mail = $db_mail->getQuery(true);

			$query_mail = 'SELECT d.donor_id, d.status AS Status, d.post_country,d.mail_only AS Mail, d.phone AS tel,c.country_name, d.mobile AS mob,d.post_address,d.post_address2,d.post_city,d.name_title,d.post_state,d.post_zip,d.post_country, CONCAT_WS(" ",d.name_first,d.name_last) AS Name, dh.project_id,
						 ( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
							) AS Donation_LastDate 

						FROM `#__donorforce_donor` As d
						Right JOIN `#__donorforce_countries` As c
						ON d.post_country=c.country_code
						Right JOIN `#__donorforce_history` As dh
						ON d.donor_id = dh.donor_id'; 
			if ($displaydonation_status =='' && $Date_from_mail =='' && $Date_to_mail =='' && $displaycategory_m_mail =='' && $project_id_mail =='' && $displaymembership == '') {
					
					$query_mail = $query_mail. " WHERE d.donor_id = 0";
			}else{

			$query_mail = $query_mail. " WHERE d.donor_id != 0";
			if($Date_from_mail != '' && $Date_to_mail != ''  ){			
				$query_mail = $query_mail. " AND dh.date BETWEEN '".$Date_from_mail."' AND  '".$Date_to_mail."' "; 
			}else if($Date_from_mail != ''){
				$query_mail = $query_mail. " AND dh.date >= '".$Date_from_mail."' "; 	
			}else if($Date_to_mail != '' ){
				$query_mail = $query_mail. " AND dh.date <= '".$Date_to_mail."' "; 	
			}


			if($displaydonation_status != ''){			
				$query_mail = $query_mail. " AND d.status = '".$displaydonation_status."' "; 
			}

			if(is_numeric($displaymembership)){			
				$query_mail = $query_mail. " AND d.membership = '".$displaymembership."' "; 
			}

			if(is_numeric($displaycategory_m_mail)){			
				$query_mail = $query_mail. " AND d.mail_only = '".$displaycategory_m_mail."' "; 
			}

			$query_mail .= ($project_id_mail != '')?(" AND dh.project_id = '".$project_id_mail."' "):"";
			$query_mail = $query_mail .' GROUP BY d.donor_id'; 					
			$query_mail = $query_mail . " ORDER BY  d.donor_id  ASC "; 	 
			$query_mail = $query_mail .	$Limits_mail ;
		}
			//echo "query_mail : ". $query_mail; exit;		
			$db_mail->setQuery($query_mail);
			$results_mail = $db_mail->loadObjectList();   
			
			// echo "<pre>"; print_r($results_mail); echo "</pre>";
			// echo "<pre> The value for the mail: "; print_r($displaycategory_m_mail); echo "</pre>";

		return $results_mail; 

	}
	
	function getTotalDonationList($parameters){ 
		$Limits = $parameters['Limits'];
		$Date_from = $parameters['DonationDateFrom'];
		$Date_to = $parameters['DonationDateTo']; 
		$displaycategory = $parameters['displaycategory']; 
		$donor_id2 = $parameters['donor_id2']; 
		$displayproject = $parameters['displayproject'];
		
		$where = ' 1 '; 
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		if($displaycategory == 'donor'){
		//		$query = 'SELECT sum(dh.amount) As total_donation, d.donor_id As ID, d.name_first As Name	
		$query = 'SELECT dh.amount, dh.Reference, d.donor_id As ID, d.status AS Status, d.phone AS tel, d.mobile AS mob, CONCAT_WS(" ",d.name_first,d.name_last) AS Name,
						 ( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
							) AS Donation_LastDate
									
						FROM `#__donorforce_donor` As d 
						LEFT JOIN `#__donorforce_history` As dh
						ON d.donor_id=dh.donor_id WHERE dh.status != "pending"';   
			if($Date_from != '' && $Date_to != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			}else if($Date_from != ''){
				$query = $query. " AND dh.date >= '".$Date_from."' "; 	
			}else if($Date_to != '' ){
				$query = $query. " AND dh.date <= '".$Date_to."' "; 	
			}						
			$query = $query .' GROUP BY dh.donor_id'; 
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
			$query = $query .	$Limits ;
			$db->setQuery($query);
			$results = $db->loadObjectList();  

			/* echo "The query: "; echo $query; 
			echo "<pre> The data: "; print_r($results); echo "</pre>"; */
				 
		}elseif($displaycategory == 'project'){
			$query = 'SELECT d.donor_id As ID, d.status AS Status, d.phone AS tel, d.mobile AS mob, CONCAT_WS(" ",d.name_first,d.name_last) AS Name, dh.project_id,
						 ( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
							) AS Donation_LastDate	
						FROM `#__donorforce_donor` As d 
						LEFT JOIN `#__donorforce_history` As dh
						ON d.donor_id=dh.donor_id WHERE dh.status != "pending"'; 
			if($Date_from != '' && $Date_to != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			}else if($Date_from != ''){
				$query = $query. " AND dh.date >= '".$Date_from."' "; 	
			}else if($Date_to != '' ){
				$query = $query. " AND dh.date <= '".$Date_to."' "; 	
			}

			if ($displayproject != '') {
				$query = $query. " AND dh.project_id = '".$displayproject."' ";
			}
									
			$query = $query .' GROUP BY dh.project_id'; 	 
			$query = $query . " ORDER BY  dh.project_id ASC "; 
			$query = $query .	$Limits ;
			$db->setQuery($query);
			$results = $db->loadObjectList();
			/* echo "The query: "; echo $query; 
			echo "<pre> The data: "; print_r($results); echo "</pre>";  */
			
		}elseif($displaycategory == 'donor_lastdonation'){
					
			$query = 'SELECT  
									d.donor_id As ID, 
									CONCAT_WS(" ",d.name_first,d.name_last) AS Name,
																							
									
									( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
									) AS Donation_LastDate,
									
									( SELECT sum(dh.amount) FROM `#__donorforce_history` AS dh
          					WHERE dh.donor_id=d.donor_id '; 										
											if($Date_from != '' && $Date_to != ''  ){			
												$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
											}else if($Date_from != ''){
												$query = $query. " AND dh.date >= '".$Date_from."' "; 	
											}else if($Date_to != '' ){
												$query = $query. " AND dh.date <= '".$Date_to."' "; 	
											}													
        						$query =$query.') As total_donation 	
													
						FROM `#__donorforce_donor` As d ';  		
	
			$query = $query . " WHERE d.published != 0"; 	 		
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
			//$query = $query .	$Limits ;
			//echo "<br /> query = ".$query;exit;   
			$db->setQuery($query); 
			$all_data = $db->loadObjectList(); 
			$results  = array();
			foreach($all_data as $edata ){
				if($edata->total_donation <= 0 ){ $results[] = $edata;   }				
			} 
	
		}
		elseif ($displaycategory = "mail_only") {
				$query = 'SELECT sum(dh.amount) As total_donation, d.donor_id As ID, d.status AS Status, d.mail_only AS Mail, d.phone AS tel, d.mobile AS mob, CONCAT_WS(" ",d.name_first,d.name_last) AS Name,
						 ( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
							) AS Donation_LastDate
									
						FROM `#__donorforce_donor` As d 
						LEFT JOIN `#__donorforce_history` As dh
						ON d.donor_id=dh.donor_id WHERE d.mail_only = "1" ';   
			if($Date_from != '' && $Date_to != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			}else if($Date_from != ''){
				$query = $query. " AND dh.date >= '".$Date_from."' "; 	
			}else if($Date_to != '' ){
				$query = $query. " AND dh.date <= '".$Date_to."' "; 	
			}						
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
			$query = $query .	$Limits ;
			$db->setQuery($query);
			$results = $db->loadObjectList();
			// echo "The query: "; echo $query; 
			// echo "<pre> The data: "; print_r($results); echo "</pre>"; 
			}
			elseif ($displaycategory = "not_mail_only") {
				$query = 'SELECT sum(dh.amount) As total_donation, d.donor_id As ID, d.status AS Status, d.mail_only AS Mail, d.phone AS tel, d.mobile AS mob, CONCAT_WS(" ",d.name_first,d.name_last) AS Name,
						 ( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
							) AS Donation_LastDate
									
						FROM  `#__donorforce_donor` As d
						LEFT JOIN `#__donorforce_history` As dh
						ON d.donor_id=dh.donor_id WHERE d.mail_only = "0" ';   
			if($Date_from != '' && $Date_to != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			}else if($Date_from != ''){
				$query = $query. " AND dh.date >= '".$Date_from."' "; 	
			}else if($Date_to != '' ){
				$query = $query. " AND dh.date <= '".$Date_to."' "; 	
			}						
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
			$query = $query .	$Limits ;
			$db->setQuery($query);
			$results = $db->loadObjectList();
			/* echo "The query: "; echo $query; 
			echo "<pre> The data: "; print_r($results); echo "</pre>";  */
			}	
		// echo "<pre> results = "; print_r( $results); echo "</pre>";	
		// echo "<br /> query =  <br /> ".$query;echo "<br /> ";  		
		// 	echo "<br /> query =  <br /> ".$displaycategory;echo "<br /> ";  	 
		return $results; 
	}
	
	function CountTable($name){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(*) As Totalcount');
		$query->from('#__donorforce_'.$name);
		$db->setQuery($query);
		$results = $db->loadObject();
		return $results; 
	}
	
	function CountTableWhere($parameters){					
		$Date_from = $parameters['DonationDateFrom'];
		$Date_to = $parameters['DonationDateTo']; 
		$displaycategory = $parameters['displaycategory']; 
		$donor_id2 = $parameters['donor_id2']; 
		$where = ' 1 '; 
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		if($displaycategory == 'donor'){
		//		$query = 'SELECT sum(dh.amount) As total_donation, d.donor_id As ID, d.name_first As Name	
		$query = 'SELECT sum(dh.amount) As total_donation, d.donor_id As ID, d.status AS Status, d.mail_only AS Mail, d.phone AS tel, d.mobile AS mob, CONCAT_WS(" ",d.name_first,d.name_last) AS Name,
						 ( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
							) AS Donation_LastDate
									
						FROM `#__donorforce_history` As dh
						Right JOIN `#__donorforce_donor` As d 
						ON d.donor_id=dh.donor_id WHERE dh.status != "pending"';   
			if($Date_from != '' && $Date_to != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			}else if($Date_from != ''){
				$query = $query. " AND dh.date >= '".$Date_from."' "; 	
			}else if($Date_to != '' ){
				$query = $query. " AND dh.date <= '".$Date_to."' "; 	
			}						
			$query = $query .' GROUP BY dh.donor_id'; 
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
			$query = $query .	$Limits ;
			$db->setQuery($query);
			$results = $db->loadObjectList();  
				 
		}elseif($displaycategory == 'project'){
			$query = 'SELECT sum(dh.amount) As total_donation, p.project_id As ID, p.name As Name	
						FROM `#__donorforce_history` As dh
						Right JOIN `#__donorforce_project` As p 
						ON p.project_id=dh.project_id WHERE dh.status != "pending"'; 
			if($Date_from != '' && $Date_to != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			}else if($Date_from != ''){
				$query = $query. " AND dh.date >= '".$Date_from."' "; 	
			}else if($Date_to != '' ){
				$query = $query. " AND dh.date <= '".$Date_to."' "; 	
			}
									
			$query = $query .' GROUP BY dh.project_id'; 	 
			$query = $query . " ORDER BY  p.project_id ASC "; 
			$query = $query .	$Limits ;
			$db->setQuery($query);
			$results = $db->loadObjectList(); 
			
		}elseif($displaycategory == 'donor_lastdonation'){
			//	d.phone,d.mobile,d.name_title,d.phy_address,d.phy_address2,d.phy_city,d.phy_zip,d.phy_state,d.phy_country,
			//						d.post_address,d.post_address2,d.post_city,d.post_zip,d.post_state,d.post_country,
										
			$query = 'SELECT  
									d.donor_id As ID, 
									CONCAT_WS(" ",d.name_first,d.name_last) AS Name,
																							
									
									( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
									) AS Donation_LastDate,
									
									( SELECT sum(dh.amount) FROM `#__donorforce_history` AS dh
          					WHERE dh.donor_id=d.donor_id '; 										
											if($Date_from != '' && $Date_to != ''  ){			
												$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
											}else if($Date_from != ''){
												$query = $query. " AND dh.date >= '".$Date_from."' "; 	
											}else if($Date_to != '' ){
												$query = $query. " AND dh.date <= '".$Date_to."' "; 	
											}													
        						$query =$query.') As total_donation 	
													
						FROM `#__donorforce_donor` As d ';  		
	
			$query = $query . " WHERE d.published != 0"; 	 		
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
			//$query = $query .	$Limits ;
			//echo "<br /> query = ".$query;exit;   
			$db->setQuery($query); 
			$all_data = $db->loadObjectList(); 
			$results  = array();
			foreach($all_data as $edata ){
				if($edata->total_donation <= 0 ){ $results[] = $edata;   }				
			} 
	
		}
			
 		// echo "<pre> CountTableWhere  results= "; print_r( $results  ); echo "</pre>";  
		 
		return $results; 
	}

	function CountTableWhere_mail($parameters_mail){					
		$Date_from_mail = $parameters_mail['DonationDateFrommail'];
		$Date_to_mail = $parameters_mail['DonationDateTomail']; 
		$displaycategory_mail = $parameters_mail['displaycategory_mail']; 
		$displayproject_mail = $parameters_mail['displayproject_mail'];
		$displaycategory_m_mail = $parameters_mail['displaycategory_mail'];  

		$project_id_mail = $parameters_mail['displayproject_mail'];

		$where = ' 1 '; 
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		if($displaycategory_m_mail == 1){
			
			$query = 'SELECT COUNT(*) As Totalcount_mail	
					  FROM `#__donorforce_history` As dh 
					  Right JOIN `#__donorforce_donor` As d ON d.donor_id=dh.donor_id 
					  '; 
			if($Date_from_mail != '' && $Date_to_mail != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from_mail."' AND  '".$Date_to_mail."' "; 
			}else if($Date_from_mail != ''){
				$query = $query. " AND dh.date >= '".$Date_from_mail."' "; 	
			}else if($Date_to_mail != '' ){
				$query = $query. " AND dh.date <= '".$Date_to_mail."' "; 	
			}
			
			$query .= ($project_id_mail != '')?(" AND dh.project_id = '".$project_id_mail."' "):"";

			$query = $query .' group by dh.donor_id';
			$db->setQuery($query);
			$results = count( $db->loadObjectList() ); 	 
		
		}elseif($displaycategory_m_mail == 0){
			
			$query = 'SELECT COUNT(*) As Totalcount_mail	
					  FROM `#__donorforce_history` As dh 
					  Right JOIN `#__donorforce_donor` As d ON d.donor_id=dh.donor_id'; 
			if($Date_from_mail != '' && $Date_to_mail != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from_mail."' AND  '".$Date_to_mail."' "; 
			}else if($Date_from_mail != ''){
				$query = $query. " AND dh.date >= '".$Date_from_mail."' "; 	
			}else if($Date_to_mail != '' ){
				$query = $query. " AND dh.date <= '".$Date_to_mail."' "; 	
			}

			$query .= ($project_id_mail != '')?(" AND dh.project_id = '".$project_id_mail."' "):"";

			$query = $query .' group by dh.project_id';
			$db->setQuery($query);
			$results = count( $db->loadObjectList() );
		   
		}elseif($displaycategory_m_mail == 3){
				
			$query = 'SELECT COUNT(*) As Totalcount_mail	
					  FROM `#__donorforce_history` As dh 
					  Right JOIN `#__donorforce_donor` As d ON d.donor_id=dh.donor_id 
					  '; 
			if($Date_from_mail != '' && $Date_to_mail != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from_mail."' AND  '".$Date_to_mail."' "; 
			}else if($Date_from_mail != ''){
				$query = $query. " AND dh.date >= '".$Date_from_mail."' "; 	
			}else if($Date_to_mail != '' ){
				$query = $query. " AND dh.date <= '".$Date_to_mail."' "; 	
			}

			$query .= ($project_id_mail != '')?(" AND dh.project_id = '".$project_id_mail."' "):"";

			$db->setQuery($query);
			$all_data = $db->loadObjectList(); 
			$counting_data  = array();
			foreach($all_data as $edata ){
				if($edata->total_donation <= 0  ){ $counting_data[] = $edata;   }				
			}
			$results = count($counting_data);
			 
		}

		return $results; 
	}

	//Method used to get overall_donatin of donor or project 
	function getOverall_donaiton($parameters){	
		$Date_from = $parameters['DonationDateFrom'];
		$Date_to = $parameters['DonationDateTo']; 
		$displaycategory = $parameters['displaycategory']; 
		$donor_id2 = $parameters['donor_id2']; 
		$OverallDonations = 0;		
		
		$where = ' 1 '; 
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		if($displaycategory == 'donor'){
			$query = 'SELECT sum(dh.amount) As total_donation, d.donor_id As ID, d.name_first As Name	
						FROM `#__donorforce_history` As dh
						Right JOIN `#__donorforce_donor` As d 
						ON d.donor_id=dh.donor_id WHERE dh.status != "pending" AND dh.status != "Pending"'; 
			if($Date_from != '' && $Date_to != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			}else if($Date_from != ''){
				$query = $query. " AND dh.date >= '".$Date_from."' "; 	
			}else if($Date_to != '' ){
				$query = $query. " AND dh.date <= '".$Date_to."' "; 	
			}						
			$query = $query .' GROUP BY dh.donor_id'; 
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
			$db->setQuery($query);
			$results = $db->loadObjectList();	
			foreach( $results as $result){
				$OverallDonations =  $OverallDonations + $result->total_donation; 
			}
				 
		}elseif($displaycategory == 'project'){			
			$query = 'SELECT sum(dh.amount) As total_donation, p.project_id As ID, p.name As Name	
						FROM `#__donorforce_history` As dh
						Right JOIN `#__donorforce_project` As p 
						ON p.project_id=dh.project_id WHERE dh.status != "pending" AND dh.status != "Pending"'; 
			if($Date_from != '' && $Date_to != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			}else if($Date_from != ''){
				$query = $query. " AND dh.date >= '".$Date_from."' "; 	
			}else if($Date_to != '' ){
				$query = $query. " AND dh.date <= '".$Date_to."' "; 	
			}
									
			$query = $query .' GROUP BY dh.project_id'; 	 
			$query = $query . " ORDER BY  p.project_id ASC "; 
			$db->setQuery($query);
			$results = $db->loadObjectList();		
			foreach( $results as $result){
				$OverallDonations =  $OverallDonations + $result->total_donation; 
			}
		
		}				 

		return $OverallDonations; 
	
	}

	public function exportxls(){
		 $db = JFactory::getDBO();
		 //echo "<pre> _REQUEST = "; print_r( $_REQUEST  ); echo "</pre>";  
		 $donor_list 			 = JRequest::getVar('donor_list'); 
		 $project_list     = JRequest::getVar('project_list'); 
		 $donation_status  = JRequest::getVar('donation_status');
		 $DonationDateFrom = JRequest::getVar('datefrom');
		 $DonationDateTo   = JRequest::getVar('dateto');
		 $displaycategory  = JRequest::getVar('displaycategory');
		 $filter_order 		 = JRequest::getVar('filter_order');
		 $filter_order_Dir = JRequest::getVar('filter_order_Dir');
		 $tab 						 = JRequest::getVar('Tab');

		 if($tab == 'search'){
				
				$query_search =  $this->getListQuery();
				$db->setQuery($query_search);
				$search_data = $db->loadObjectList();	
				$export_data = array();	
			
				foreach($search_data as $single){  
						$exp_single = array( 'donor_history_id' => $single->donor_history_id,
												 'name' => $single->name_first.' '.$single->name_last,
												 'date' => $single->date,
												 'project_name' => $single->project_name,
												 'Reference' => $single->Reference,
												 'amount' => $single->amount,
												 'status' => $single->status
												);
						$export_data[] = $exp_single;					
			   }
					
				$header = array('Donation ID', 'Donor Name','Donation Date','Project','Reference','Amount','Status');
				$header_type = array( 
						'donor_history_id' =>'Number', 
						'name'=> 'Label', 
						'date'=> 'Label', 
						'project_name'=>'Label',
						'Reference' =>'Label', 
						'amount'=> 'Label', 
						'status' =>'Label'
				);				
				$this->xls('Donations_Export',$header,$header_type,$export_data);
			
		}elseif( $tab == 'reports_display'){
			$parameters = array(
					'donor_id2' => '',
					'DonationDateFrom' => $DonationDateFrom,
					'DonationDateTo' => $DonationDateTo,
					'displaycategory' => $displaycategory,
					'Limits' => ''
		   );
		
			$donatino_list  = $this->getTotalDonationList($parameters);	
			//echo "<pre> donatino_list = "; print_r( $donatino_list  ); echo "</pre>";   exit; 
			$export_donation = array();
			foreach($donatino_list as $singlelist){
				$single = array('ID'=> $singlelist->ID, 
					'Name' =>$singlelist->Name, 	
					'Status' =>$singlelist->Status, 
					'Telephone NO.' =>$singlelist->tel, 
					'Reference' =>$singlelist->Reference, 				
					'total_donation'=> $singlelist->amount );
				$export_donation[] = $single;
			 }
			 
			 if($displaycategory == 'donor_lastdonation'){ $displaycategory ='Donor'; }
			
			$header = array($displaycategory.' ID', $displaycategory.' Name', $displaycategory.' Status', $displaycategory.' Telephone NO.', $displaycategory.' Reference', $displaycategory.' Total Donation');
			$header_type = array( 
					'ID'=> 'Number', 
					'Name' =>'Label', 
					'Status' =>'Label', 
					'Telephone NO.' =>'Label', 
					'Reference' =>'Label', 
					'total_donation'=> 'Label'
			);				
			$this->xls('TotalDonations_Export',$header,$header_type,$export_donation);

		}elseif( $tab == 'project_association'){
			//echo "In working";
			//echo "<pre>"; print_r($_POST); echo "</pre>";
			
		$jinput     = JFactory::getApplication()->input;
		$project_id_mail   =   $jinput->get('displayproject_mail', '');
	   $DonationDateFrom_mail    = $jinput->get('datefrom_mail','');  
	   $DonationDateTo_mail   = $jinput->get('dateto_mail','');
	   $displaycontact     = $jinput->get('displaycategory_mail','');
	   $displaycontactall     = 3;
	   $displaymembership    = $jinput->get('displaymembership','');
	   $displaydonation_status  = $jinput->getString('displaydonation_status','default_value');
	   
	   $parameters_mail = array(
		  'Limits' => ' LIMIT 9999999999',
		  'displaycategory_mail' => $displaycontact,
		  'displaycontactall' => $displaycontactall,
		  'DonationDateTomail' => $DonationDateTo_mail,
		  'DonationDateFrommail' => $DonationDateFrom_mail,
		  'displayproject_mail' => $project_id_mail,
		  'displaydonation_status' => $displaydonation_status,
		  'displaymembership' => $displaymembership
	 
	   );
		$Count_Donation_mail = $this->CountTableWhere_mail($parameters_mail);
		$limit_mail = 20;  $limit_mail = ($this->select_limit_mail > 0)? $this->select_limit_mail : 999999999; 
		$Donation_pagination_mail = new JPagination($Count_Donation_mail, $Donationlimitstart_mail, $limit_mail, 'TotalDonationMail');
		
		
		/* $Limits_mail = ' LIMIT '.$limit_mail. " OFFSET " . $Donationlimitstart_mail;
		$parameters_mail['Limits'] = $Limits_mail;  */
			// echo "<pre>"; print_r($parameters_mail); echo "</pre>"; exit;
			$query_association =  $this->getTotalDonationListmail($parameters_mail);
			// echo "<pre>"; print_r($query_association); echo "</pre>"; exit;

			$export_association = array();

			foreach($query_association as $single){  
				//echo "<pre>";print_r($single);echo "</pre>";exit;
				$exp_association = array( 
										  'name_title' => $single->name_title,
										  'donor_name' => $single->Name,
										  'donor_post_address' => $single->post_address,
										  'donor_post_address2' => $single->post_address2,
										  'donor_post_city' => $single->post_city,
										  'donor_post_state' => $single->post_state,
										  'donor_post_zip' => $single->post_zip,
										  'donor_post_country' => $single->post_country,
										  'donor_country_name' => $single->country_name,
										 // 'donor_status' => $single->Status,
										 // 'dono_telephone_number' => $single->tel,
										 // 'donor_mobile_number' => $single->mob,
										 // 'last_donotion_date' => $single->Donation_LastDate
										);
				$export_association[] = $exp_association;					
			}
					
				//$header = array('Donor Name','Donor Status','Donor Telephone NO.','Donor Moible No.','Last Donation Date');
				$header = array('Donor Name Title','Donor Name','Donor Postal Address','Donor Postal Address2','Donor Postal City','Donor Postal State','Donor Postal / Zip Code','Donor Postal Country','Donor Country Name');
				$header_type = array( 
						'name_title'=> 'Label',
						'donor_name' =>'Label',
						'donor_post_address' =>'Label',
						'donor_post_address2' =>'Label',
						'donor_post_city' =>'Label',
						'donor_post_state' =>'Label',
						'donor_post_zip' =>'Number',
						'donor_post_country' =>'Label',
						'donor_country_name' =>'Label',
						// 'donor_status'=> 'Label', 
						// 'dono_telephone_number'=>'Label',
						// 'donor_mobile_number' =>'Label', 
						// 'last_donotion_date'=> 'Label'
				);				
				$this->xls('Project_Association',$header,$header_type,$export_association);

		}
			
	}
	
	public function exportuser(){

		 $db   = JFactory::getDBO();
		 $query="
				SELECT
					d.*,
					( SELECT ld.date
										FROM #__donorforce_history AS ld
										WHERE  ld.donor_id=d.donor_id 
										ORDER BY ld.date DESC 
										LIMIT 1
							) AS lsat_date
				FROM
					#__donorforce_donor AS d
				-- INNER JOIN #__donorforce_donor AS d ON s.donor_id = d.cms_user_id
				-- INNER JOIN #__users AS u ON d.cms_user_id = u.id 
				-- INNER JOIN #__donorforce_project AS p ON s.project_id = p.project_id 
				";
			//echo "<br /> query =  ".$query; 
			$db->setQuery($query);
			$data = $db->loadObjectList();	
			$export_data = array();	
			//echo "<pre> data = "; print_r( $query); echo "</pre>";  
			//echo "<pre> data = "; print_r( $data); echo "</pre>";  exit; 

	 
			foreach($data as $single){  
					$exp_single = array( 
											  'name_first' => $single->name_first,
											 'name_last' => $single->name_last,											 
											 'status' => $single->status,
											 'phone' => $single->phone,		
											 'mobile'=>$single->mobile,
											 'ld_date' => $single->lsat_date
											 											 
											);
					$export_data[] = $exp_single;
			 }
				
			$header = array('Donor first name', 'Donor Sur name', 'Donor status','Telephone number', 'ZMobile number', 'Last donantion date');
			
			$header_type = array( 
					'name_first' =>'Label',
					'name_last' => 'Label', 
					'status'=> 'Label', 
					'phone'=> 'Number', 
					'mobile'=> 'Number', 
					'ld_date'=> 'Label'
			);	

			$this->xls('User_contact_Export',$header,$header_type,$export_data);
			//$this->phpexcel('User_contact_information',$header,$header_type,$export_data);
	}//exportxls 

	public function phpexcel($filename, $header,$header_type,  $Export_Data){

					$user = JFactory::getUser();
					/** Include PHPExcel */
					//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
					//require_once dirname(__FILE__) . '/../assets/Classes/PHPExcel.php';
					require_once JPATH_LIBRARIES . '/phpexcel/library/PHPExcel.php';
					
					// Create new PHPExcel object
					$objPHPExcel = new PHPExcel();
					// Set document properties
					$objPHPExcel->getProperties()->setCreator($user->name)
												 ->setLastModifiedBy($user->name)
												 ->setTitle("Office 2007 XLS Document")
												 ->setSubject("Office 2007 XLS Document")
												 ->setDescription("Document for Office 2007 XLSX, generated by DonorForce")
												 ->setKeywords("office 2007 openxml php")
												 ->setCategory("Test result file");

											
				$alphacol = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

											
					$hcol = 0; 
					foreach ($header as $key=>$head)
					{						
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphacol[$hcol].'1',$head );
							$hcol++;	
					}
	
					$xlsRow = 2;
					foreach($Export_Data as $single)
					{		
						$column = 0;		
						foreach ($single as $key=>$data )
						{																	
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphacol[$column].$xlsRow,$data );			
							$column++;				
						}
						$xlsRow++;										
					}
							
					// Rename worksheet
					$objPHPExcel->getActiveSheet()->setTitle($filename);
					// Set active sheet index to the first sheet, so Excel opens this as the first sheet
					$objPHPExcel->setActiveSheetIndex(0);
					// Redirect output to a client's web browser (Excel5)
					header('Content-Type: application/vnd.ms-excel');
					header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');
					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
					$objWriter->save('php://output');
					exit;
		}

	public function xls($filename, $header,$header_type,  $Export_Data){		
			function xlsBOF(){	echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0); return; }
			function xlsEOF(){	echo pack("ss", 0x0A, 0x00);return; }
			function xlsWriteNumber($Row, $Col, $Value){ 
				echo pack("sssss", 0x203, 14, $Row, $Col, 0x0); echo pack("d", $Value); return;
			}
			
			function xlsWriteLabel($Row, $Col, $Value )
			{
				$L = strlen($Value);
				echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
				echo $Value;
				return;
			}
			
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");;
			header("Content-Disposition: attachment;filename=".$filename.".xls");
			header("Content-Transfer-Encoding: binary ");
			xlsBOF();
			 
		 $hcol = 0; 
		  foreach ($header as $key=>$head)
			{
			 	if(is_numeric($head)) { xlsWriteNumber(0,$key,$head);  }
				else{ 	xlsWriteLabel(0,$key,$head);  }   
				
			/*	if($head['type'] == 'Number'){  xlsWriteNumber(0,$hcol,$head['title']);  }
				else{ xlsWriteLabel(0,$hcol,$head['title']);  }
				$hcol++;*/
			}
			
			$xlsRow = 1;
			foreach($Export_Data as $single)
			{		
				$column = 0;		
				foreach ($single as $key=>$data )
				{				
				
				if($header_type[$key] == 'Number'){ xlsWriteNumber($xlsRow,$column,$data);}else{ xlsWriteLabel($xlsRow,$column,$data);}
				 //if(is_numeric ($data)) {  xlsWriteNumber($xlsRow,$column,$data); 	 }else{ xlsWriteLabel($xlsRow,$column,$data); }				
					$column++;
				}				
				$xlsRow++;
			}
			xlsEOF();	
			exit;			
		}//functoin xls end
	
	
}