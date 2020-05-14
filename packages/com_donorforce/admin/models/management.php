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
class DonorforceModelmanagement extends JModelList // <Component-name>View<Model-name>
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'subscription_id', 'a.subscription_id',
				'date', 'dh.date',				
			);
		}
		
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
		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$search_datefrom = $this->getUserStateFromRequest($this->context.'.search.datefrom', 'search_datefrom');
		$this->setState('search.datefrom', $search_datefrom);

		$search_dateto = $this->getUserStateFromRequest($this->context.'.search.dateto', 'search_dateto');
		$this->setState('search.dateto', $search_dateto);

		$donor_list = $this->getUserStateFromRequest($this->context.'.donor_list', 'donor_list');
		$this->setState('donor_list', $donor_list);

		$project_list = $this->getUserStateFromRequest($this->context.'.project_list', 'project_list');
		$this->setState('project_list', $project_list);

		$donation_status = $this->getUserStateFromRequest($this->context.'.donation_status', 'donation_status');
		$this->setState('donation_status', $donation_status);

		$filter_order = $this->getUserStateFromRequest($this->context.'.filter.order', 'filter_order','dh.date');
		$this->setState('filter.order', $filter_order);

		$filter_order_Dir = $this->getUserStateFromRequest($this->context.'.filter.order_Dir', 'filter_order_Dir','desc');
		$this->setState('filter.order_Dir', $filter_order_Dir);
	
		
		/* $filter_order = JRequest::getCmd('filter_order', 'dh.date');
		$this->setState($this->context.'.filter_order', $filter_order);

		$filter_order_Dir = JRequest::getCmd('filter_order_Dir','desc');
		$this->setState($this->context.'.filter_order_Dir', $filter_order_Dir);
		
		$filter_donor = JRequest::getCmd('donor_list');
		$this->setState($this->context.'.filter_donor', $filter_donor); 
		
		$filter_donor = JRequest::getCmd('project_list');
		$this->setState($this->context.'.project_list', $filter_donor);
		
		$filter_donation_status = JRequest::getCmd('donation_status');
		$this->setState($this->context.'.donation_status', $filter_donation_status);*/
		
		
		/* $filter_search_date_from = JRequest::getCmd('search_datefrom');
		$this->setState($this->context.'.search_datefrom', $filter_search_date_from);
		
		$filter_search_date_to = JRequest::getCmd('search_dateto');
		$this->setState($this->context.'.search_dateto', $filter_search_date_to); */
		
		// $filter_search = JRequest::getCmd('search');
		// $this->setState($this->context.'search', $filter_search);

		/* $filter_search = urldecode(JRequest::getVar('search', JREQUEST_ALLOWHTML));
		$this->setState($this->context.'.search', $filter_search);
		$this->setState('search', $filter_search); */
		

		//$this->setState('list.ordering', $filter_order);
		//$this->setState('list.direction', $filter_order_Dir);
		
		//echo " <br />  filter_order = ".$filter_order; 
		//echo " <br />  filter_order_Dir = ".$filter_order_Dir; 
		//echo " <br />  this->context = ".$this->context; 
		//echo " <pre> this->setState = "; print_r($this->setState); 
		// List state information.
		//parent::populateState('a.name_first', 'asc');
		//parent::populateState();
		parent::populateState($filter_order, $filter_order_Dir);

		$this->setState('list.direction',$filter_order_Dir);
		// List state information.
		//parent::populateState('a.name_first', 'asc');
		//parent::populateState($filter_order, $filter_order_Dir);
		 // parent::populateState('dh.donor_history_id','asc');
	}

	
	function getListQuery(){
		$input	= JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('dh.*,d.name_title,d.name_first,d.name_last,p.name as project_name');
		$query->from('#__donorforce_history AS dh');
		$query->join('LEFT', '#__donorforce_donor AS d ON d.donor_id=dh.donor_id');
		$query->join('LEFT', '#__donorforce_project AS p ON p.project_id=dh.project_id');
		/*if($filter_org_type){
			$query->where('a.org_type = "'.$filter_org_type.'"');
		}*/
		// Filter by access level.
		if ($filter_donor = $this->getState('donor_list'))
		{
			//echo "<hr />filter_donor = ".$filter_donor; 
			$query->where('dh.donor_id = ' . (int) $filter_donor);
		} 
		if ($filter_project = $this->getState('project_list'))
		{
			$query->where('dh.project_id = ' . (int) $filter_project);
		}
		if ($filter_donation_status = $this->getState('donation_status'))
		{
			$query->where('dh.status = "' . $filter_donation_status.'"');
		}
		
		$filter_search_date_from = $this->getState('search.datefrom');		
		$filter_search_date_to = $this->getState('search.dateto');
		if( $filter_search_date_from != '' &&  $filter_search_date_to != ''){
			//$query = $query. " AND dh.date BETWEEN '".$Date_from."' AND  '".$Date_to."' "; 
			$query->where('dh.date BETWEEN "'.$filter_search_date_from.' 00:00:00" AND "'.$filter_search_date_to.' 23:59:59"');			
		}else if($filter_search_date_from != '' ){
			$query->where('dh.date >= "' . $filter_search_date_from.' 00:00:00"');
		}else if($filter_search_date_to != ''){
			$query->where('dh.date <= "' . $filter_search_date_to.' 23:59:59"');
		}
		
		//$search = $this->getState('com_donorforce.managementsearch');	
		$search = $this->getState('filter.search');
		if($search != ''){				
			 $search_where= " ( d.name_first like '%".$search."%'
				 OR
				 d.name_last like '%".$search."%'
				 OR
				 p.name like '%".$search."%'
				 OR
				 dh.Reference like '%".$search."%'
				)";
			$query->where($search_where);					
		}
		
		
		//echo " <pre> this->state = "; print_r($this->state); echo "</pre>"; 
		$orderCol	= $this->state->get('list.ordering','dh.date');
		$orderDirn	= $this->state->get('list.direction', 'desc');
		if($orderCol == '') $orderCol='dh.date';
		if($orderDirn == '') $orderDirn='desc';
 	 
		/*echo " <br />  orderCol = ".$orderCol; 
		echo " <br />  orderDirn = ".$orderDirn; */
		$query->order($db->escape($orderCol.' '.$orderDirn));
		//echo "<br />  query = " . $query;
	 
		return $query;	
	}
	
	 
	 
		
		public function Upload_File(){
				$return = '';
				jimport('joomla.filesystem.file');
				jimport( 'joomla.filesystem.folder' );
				$file = JRequest::getVar('file_upload', null, 'files', 'array');
				//Clean up filename to get rid of strange characters like spaces etc
				$filename = JFile::makeSafe($file['name']);
				$filename =  preg_replace('/\s+/', '_', $filename);	
				 
				//Set up the source and destination of the file
				$src = $file['tmp_name'];
				//check if bank csv then folder would be UploadCSV 
				//else if debit order then folder =  UploadCSVDO
				if(JRequest::getVar('import_type') == 'bank_csv'){ 
					$dest = JPATH_ROOT . DS . "media".DS."UploadCSV";
				}else{
					$dest = JPATH_ROOT . DS . "media".DS."UploadCSVDO";
				}
				//The CSVDO folder is where the Debit Order files are saved
				if(!JFolder::exists($dest)){ JFolder::create($dest); }
				$dest = $dest . DS . $filename;
				//echo "<br> Destination = ".$dest;  
				 
				//First check if the file has the right extension, we need jpg only
				$file_extension = strtolower(JFile::getExt($filename) );
				if($file_extension == 'csv'){
					 if(JFile::upload($src, $dest)){					
								echo "<br> <h2> File Succesfully Uploaded </h2>"; 
								//$return = $dest;
								//$this->Read_XLSFile($dest);
								//$dest_encode = urlencode($dest);
								 
								 
								 
								
								if(JRequest::getVar('import_type') == 'bank_csv'){
								
								JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=management&layout=process_csv&csv_file='.$filename); 
		 						$app->redirect($url,'CSV File Processing','success');	
								}else if(JRequest::getVar('import_type') == 'debitorder_csv'){
									JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=management&layout=process_csv_do&csv_file='.$filename);
									$app->redirect($url,'CSV File Processing','success');	
								}else{
									JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=management'); 
		 							$app->redirect($url,'CSV File Processing','success');	
											
								}
								
								
								
					 }else{						
								echo "<br>Error uploading";
								JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=subscriptions&layout=import'); 
		 						$app->redirect($url,'Error uploading','error');								 
					 }
				}else{
					 echo "<br>Wrong file formate only allowed .csv  " ; 						 		
					JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=subscriptions&layout=import'); 
					$app->redirect($url,'Wrong file formate allowed only .csv  or .xls','error');				
				}
				//return $return;
		}
		
		
		
		public function exportCSV(){
			
				$post = JRequest::get();
				$cid = $post['cid'];
			
			  
			  $db = JFactory::getDbo();
				$csv_query = $db->getQuery(true);
				$csv_query->select('dh.*,d.name_title,d.name_first,d.name_last,p.name as project_name');
				$csv_query->from('#__donorforce_history AS dh');
				$csv_query->join('LEFT', '#__donorforce_donor AS d ON d.donor_id=dh.donor_id');
				$csv_query->join('LEFT', '#__donorforce_project AS p ON p.project_id=dh.project_id');
				$csv_query->where('dh.donor_history_id IN ('.implode(',',$cid).')');
				$orderCol	= $post['filter_order'];
				$orderDirn	= $post['filter_order_Dir']; 
				if($orderCol == '') $orderCol='dh.donor_history_id';
				if($orderDirn == '') $orderDirn='asc';
			 	$csv_query->order($db->escape($orderCol.' '.$orderDirn));
				
				$db->setQuery($csv_query);
			 	$csvData = $db->loadAssocList();
				// echo "<pre>  csvData "; print_r($csvData); echo "</pre> ";  
				
				 
					 
				$filename = "export.csv"; $delimiter=",";
				header('Content-Type: application/csv');
				header('Content-Disposition: attachement; filename="'.$filename.'";');
				$f = fopen('php://output', 'w');
				
				$temp = array(
					'Donation ID',
					'Date',
					'Reference',
					'Debit',						
					'Amount',
					'Donor',
					'Project', 
					'Donation status'
				);
				fputcsv($f,$temp, $delimiter);
		
				
				foreach ($csvData as $csvSingle) {
					$line =  array(
						$csvSingle['donor_history_id'],
						$csvSingle['date'],
						$csvSingle['Reference'],
						$csvSingle['Debit'],
						$csvSingle['amount'],
						'D'.str_pad($csvSingle['donor_id'], 5, '0', STR_PAD_LEFT),
						'P'.str_pad($csvSingle['project_id'], 5, '0', STR_PAD_LEFT),
						$csvSingle['status'],
						$csvSingle['name_title'].' '.$csvSingle['name_first'].' '.$csvSingle['name_last']				
					);
												 
				//print_r($line); exit; 
					fputcsv($f, $line, $delimiter);
			
				}
						
				 	
						
						
			fclose($f);	
		exit; 
		}
	
	
	public function deleteDonations(){
		
		//echo " <pre>   JRequest::get = ";  print_r( JRequest::get() ); echo " </pre> "; exit;
		$cid = JRequest::getVar('cid');
		if($cid != ''):
			echo " <pre> cid = ";  print_r( $cid ); echo " </pre> ";
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query = 'DELETE FROM #__donorforce_history WHERE `donor_history_id` IN ('.implode(',',$cid).')'; 
			$db->setQuery($query);
			$result = $db->execute();
			return $result;
		endif; 
	}	
		
}