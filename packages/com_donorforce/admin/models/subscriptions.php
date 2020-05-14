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
class DonorforceModelSubscriptions extends JModelList // <Component-name>View<Model-name>
{
	
	public function __construct($config = array())
	{
		
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'subscription_id', 's.subscription_id'				
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
		
		$subscription_type = $this->getUserStateFromRequest($this->context.'.filter.subscription_type', 'filter_subscription_type');
		$this->setState('filter.subscription_type', $subscription_type);
		
		$Deduction_Date = $this->getUserStateFromRequest($this->context.'.filter.Deduction_Date', 'filter_Deduction_Date');
		$this->setState('filter.Deduction_Date', $Deduction_Date);
		
		//$access = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		//$this->setState('filter.access', $access);

		//$cnewsId = $this->getUserStateFromRequest($this->context.'.filter.id', 'filter_NewsID');
		//$this->setState('filter.id', $cnewsId);
		
		$filter_order = JRequest::getCmd('filter_order', 's.subscription_id');
		$this->setState($this->context.'filter_order', $filter_order);
		
		$filter_order_Dir = JRequest::getCmd('filter_order_Dir');
		$this->setState($this->context.'filter_order_Dir', $filter_order_Dir);

		// List state information.
		parent::populateState($filter_order, $filter_order_Dir);
	}

	
	function getListQuery(){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$subscription_type = $this->getState('filter.subscription_type');
		$Deduction_Date = $this->getState('filter.Deduction_Date');
		$search = $this->getState('filter.search');
		
		// Select some fields
		$query="
			SELECT
				s.*,d.*,u.*, p.name as pname,p.date_start,p.date_end
			FROM
				#__donorforce_donor_subscriptions AS s
			INNER JOIN #__donorforce_donor AS d ON s.donor_id = d.cms_user_id
			INNER JOIN #__users AS u ON d.cms_user_id = u.id 
			INNER JOIN #__donorforce_project AS p ON s.project_id = p.project_id 
			";
			
			if($subscription_type != '' && $Deduction_Date != '') $query .= " WHERE s.donation_type='".$subscription_type."' AND 
				s.deduction_day = '".$Deduction_Date."'";
			elseif( $subscription_type != '' ) $query .= " WHERE s.donation_type='".$subscription_type."'";
			elseif( $Deduction_Date != ''  ) $query .= " WHERE s.deduction_day = '".$Deduction_Date."'";
			

		if (!empty($search)){
			$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query.=" WHERE d.name_first LIKE ".$search . " OR d.name_last LIKE ". $search;
		}

		//echo "<br /> Query = ".$query."<br />";		
		//echo "<pre> this->context = "; print_r( $this->context ); echo "</pre>";  		
		//$orderCol	= $this->state->get('list.ordering', 'a.project_id');
		//$orderDirn	= $this->state->get('list.direction', 'asc');				
		//$query->order($db->escape($orderCol.' '.$orderDirn));

		// Add the list ordering clause
		  // $listOrdering = $this->getState('list.ordering', 'a.lft');
		  // $listDirn = $db->escape($this->getState('list.direction', 'ASC'));

		  // if ($listOrdering == 'a.access')
		  // {
		  //  $query->order('a.access ' . $listDirn . ', a.lft ' . $listDirn);
		  // }
		  // else
		  // {
		  //  $query->order($db->escape($listOrdering) . ' ' . $listDirn);
		  // }
		//$fullordering	= $this->getState('list.fullordering','a.subscription_id ASC');
		$orderCol	= $this->getState('list.ordering', 's.subscription_id');
		$listDirn = $this->getState('list.direction', 'asc');
		$query .= " ORDER BY ".$orderCol. ' ' . $listDirn;
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
	
	
	public function exportxls(){
		 $db = JFactory::getDBO();
		 $subscription_type 			 = JRequest::getVar('filter_subscription_type'); 
		 $Deduction_Date     = JRequest::getVar('filter_Deduction_Date');		 
		 
		 $query="
				SELECT
					s.*,d.*,u.*, p.name as pname,p.project_id as pid, p.date_start,p.date_end
				FROM
					#__donorforce_donor_subscriptions AS s
				INNER JOIN #__donorforce_donor AS d ON s.donor_id = d.cms_user_id
				INNER JOIN #__users AS u ON d.cms_user_id = u.id 
				INNER JOIN #__donorforce_project AS p ON s.project_id = p.project_id 
				";
				
			if($subscription_type != '' && $Deduction_Date != '') $query .= " WHERE s.donation_type='".$subscription_type."' AND 
				s.deduction_day = '".$Deduction_Date."'";
			elseif( $subscription_type != '' ) $query .= " WHERE s.donation_type='".$subscription_type."'";
			elseif( $Deduction_Date != ''  ) $query .= " WHERE s.deduction_day = '".$Deduction_Date."'";

			//echo "<br /> query =  ".$query; 
			$db->setQuery($query);
			$data = $db->loadObjectList();	
			$export_data = array();	
			//echo "<pre> data = "; print_r( $data  ); echo "</pre>";  
	
			foreach($data as $single){  
					$exp_single = array( 
											 'name_title' => $single->name_title,
											 'donor_id' => $single->donor_id,											 
											 'name_first' => $single->name_first,
											 'name_last' => $single->name_last,		
											 'amount'=>$single->amount,				 					 
											 'pname' => $single->pname,
											 'pid' => $single->pid,
											 'donation_type' => $single->donation_type,
											 'donation_start_date' => $single->donation_start_date,
											 'donation_end_date' => $single->donation_end_date,
											 'deduction_day' => $single->deduction_day,
											 'frequency' => $single->frequency,
											 'subscription_id' => $single->subscription_id											 
											);
					$export_data[] = $exp_single;					
			 }
				
			$header = array('Name Title', 'Donor ID', 'Donor First Name', 'Donor Last Name','Amount','Project', 'Project ID', 'Donation Type','Donation Start Date','Donation End Date',
			'Deduction Day','Frequency','Subscription ID');
			
			$header_type = array( 
					'name_title' =>'Label',
					'donor_id' => 'Label', 
					'name_first'=> 'Label', 
					'name_last'=> 'Label', 
					'amount'=> 'Number', 
					'pname'=> 'Label', 
					'pid'=> 'Number', 
					'donation_type'=>'Label',
					'donation_start_date' =>'Label', 
					'donation_end_date'=> 'Label', 
					'deduction_day' =>'Label',
					'frequency' => 'Label',
					'subscription_id' => 'Number'
			);				
			//$this->xls('Subscription_Export',$header,$header_type,$export_data);
			$this->phpexcel('Subscription_Export',$header,$header_type,$export_data);
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
				$dest = JPATH_ROOT . DS . "media".DS."UploadXLS";
				if(!JFolder::exists($dest)){ JFolder::create($dest); }
				$dest = $dest . DS . $filename;
				//echo "<br> Destination = ".$dest; 
				 
				//First check if the file has the right extension, we need jpg only
				$file_extension = strtolower(JFile::getExt($filename) );
				if($file_extension == 'csv' || $file_extension == 'xls'){
					 if(JFile::upload($src, $dest)){					
								echo "<br> <h2> File Succesfully Uploaded </h2>"; 
								//$return = $dest;
								$this->Read_XLSFile($dest);
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
		
		
		
		public function Read_XLSFile($dest){			
			/** Include path **/
			//echo "<br /> get_include_path = ".get_include_path(). '../../../Classes/';  
			//set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
			/** PHPExcel_IOFactory */
			//include dirname(__FILE__) . '/../assets/Classes/PHPExcel/IOFactory.php';
			require_once JPATH_LIBRARIES . '/phpexcel/library/PHPExcel/IOFactory.php';
			
			
			//$inputFileName = './sampleData/example1.xls';
			$inputFileName = $dest;//realpath(dirname(__FILE__).'/../assets/Excel/Donations_Export_3.xls');				
			echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
			echo '<hr />';
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			//echo "<pre> sheetData = "; print_r( $sheetData  ); echo "</pre>";  
			//var_dump($sheetData);
			$Exp_data = array();
			
			foreach($sheetData as $sdata){
				 $empty = false;  
				foreach($sdata as $d) if($d == '') $empty = true; 				
				 if(!$empty){  $Exp_data[] = $sdata; }  
				}
			//Remove the header 	
		 	unset($Exp_data[0]);			
			//echo "<pre> Exp_data = "; print_r( $Exp_data  ); echo "</pre>";  
			$this->insert_xls($Exp_data);
			
		}
		
		
		
		public function insert_xls($xls_data){
			//echo "<pre> xls_data = "; print_r( $xls_data  ); echo "</pre>";  		
			$db = JFactory::getDbo();			
			echo '<table class="import_status table table-striped">
						<thead>
							<tr>
								<th>XLS Row No.</th>
								<th>Donor Names</th>
								<th>Donor ID</th>
								<th>CMS User ID</th>
								<th>Amount</th>
								<th>Donation Type</th>
								<th>Reference</th>
								<th>Donation History ID</th>
								<th>Subscription ID</th>
								<th>Status</th>
							</tr>
						</thead><tbody>
			'; 
			
			
			$row = 1;
			foreach($xls_data as $single){		
					
				// get cms_user_id  
				$uquery = " SELECT cms_user_id FROM #__donorforce_donor  WHERE donor_id=".$single['B'];
				$db->setQuery($uquery);
				$cms_user_id = $db->loadObject()->cms_user_id;
				$ref = 'RDO-'.$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);;
				
				// insert in to donorforce_history 
				$iquery = $db->getQuery(true);		
				$iquery = " 
				INSERT INTO #__donorforce_history 
				(`donor_id`, `project_id`, `cms_user_id`, `date`, `amount`, `status`, `donation_type`, `Reference`) VALUES
				( ".$single['B'].", ".$single['G'].",'".$cms_user_id."',NOW(),'".$single['E']."','successful','".$single['H']."',
					'".$ref."')																							
				";				
				$db->setQuery($iquery);
				$db->execute();		
				$lastinsertid = $db->insertid();
				
				// insert in to rdo_history 
				$rdoquery = " 
				INSERT INTO #__donorforce_rdo_history
				(`donor_history_id`, `subscriptions_id`) VALUES (".$lastinsertid.",".$single['M'].")";
				$db->setQuery($rdoquery);
				$db->execute();									
			
				
			//	echo "<br />  Row $row  Succesfully inserted ";
				echo "<tr>
								<td>$row</td>
								<td>".$single['A']." ".$single['C']." ".$single['D']."</td>
								<td>".$single['B']."</td>
								<td>".$cms_user_id."</td>
								<td>".$single['E']."</td>
								<td>".$single['H']."</td>
								<td>".$ref."</td>
								<td>".$lastinsertid."</td>
								<td>".$single['M']."</td>
								<td>Succes</td>
							</tr>";
				
				
				$row++; 
			
			}
			echo "</tbody></table>";
			echo "<h1>Data Succesfylly Imported </h1>"; 
			echo '<a href="index.php?option=com_donorforce&view=subscriptions"><button class="btn btn-large"><span> Exit </span></a></button>';
			
			//exit; 
		}

		public function exportDO(){
		 $db = JFactory::getDBO();	 
		 
		 $query="
				SELECT
					s.amount, d.donor_id, p.project_id, rd.bank_name, rd.branch_name, rd.account_name, rd.branchcode, rd.account_number, d.name_first as fName, d.name_last as lName
				FROM
					#__donorforce_donor_subscriptions AS s
				LEFT JOIN #__donorforce_donor AS d ON s.donor_id = d.cms_user_id
				LEFT JOIN #__donorforce_project AS p ON s.project_id = p.project_id
				LEFT JOIN #__donorforce_rec_donation rd ON rd.donor_id = s.donor_id 
				WHERE s.donation_type = 'recurring' AND s.deduction_day != 0
				GROUP BY d.donor_id
				";
				
			// if($subscription_type != '' && $Deduction_Date != '') $query .= " WHERE s.donation_type='".$subscription_type."' AND 
			// 	s.deduction_day = '".$Deduction_Date."'";
			// elseif( $subscription_type != '' ) $query .= " WHERE s.donation_type='".$subscription_type."'";
			// elseif( $Deduction_Date != ''  ) $query .= " WHERE s.deduction_day = '".$Deduction_Date."'";

			//echo "<br /> query =  ".$query; 
			$db->setQuery($query);
			$data = $db->loadObjectList();	
			$export_data = array();	
			//echo "<pre> data = "; print_r( $data  ); echo "</pre>";  exit; 
	
			foreach($data as $single){  
					$exp_single = array( 
											 'donor_id' => 'D'.str_pad($single->donor_id, 5, '0', STR_PAD_LEFT),
											 'project_id' => 'P'.str_pad($single->project_id, 5, '0', STR_PAD_LEFT),
											 'donor_fname' => $single->fName,
											 'donor_lname' => $single->lName,								 
											 'method' => "DO",
											 'bank_name' => $single->bank_name,
											 'branch_name' => $single->branch_name,											 
											 'branchcode' => $single->branchcode,
											 'account_name' => $single->account_name,		
											 'account_number'=>$single->account_number,				 					 
											 'amount' => $single->amount,
											 'donation_status' => "Succesfull"
											 										 
											);
					$export_data[] = $exp_single;					
			 }
				
			$header = array('Donor Number', 'Project Number', 'Donor first name', 'Donor last name', 'Method', 'Bank name', 'Branch name', 'Branch Code', 'Account name','Account Number','Amount', 'Donation Status');
			
			$header_type = array( 
					'donor_id' =>'Label',
					'project_id' => 'Label',
					'donor_fname' => 'Label', 
					'donor_lname' => 'Label',
					'method' => 'Label', 
					'bank_name' => 'Label', 
					'branch_name' => 'Label', 
					'branchcode' => 'Number', 
					'account_name' => 'Label', 
					'account_number' =>'Label',
					'amount'=>'Number',
					'donation_status' => 'Label'

			);				
			//$this->xls('Subscription_Export',$header,$header_type,$export_data);
			//$this->xls('Export_DO',$header,$header_type,$export_data);
			// echo "<pre> export_data = "; print_r($export_data); exit; 
			//echo "<pre> export_data = "; print_r($export_data); exit; 

			$this->csv_export('Export_DO',$header,$header_type,$export_data);

			
	}//The end of EXPORTDO

	public function csv_export($filename, $header, $header_type,$csvData){

				$filename = $filename.".csv"; $delimiter=",";
				header('Content-Type: application/csv');
				header('Content-Disposition: attachement; filename="'.$filename.'";');
				$f = fopen('php://output', 'w');
				fputcsv($f,$header,$delimiter);	
				
				foreach ($csvData as $csvSingle) {
					/*$line =  array(
						$csvSingle['donor_history_id'],
						$csvSingle['date'],
						$csvSingle['Reference'],
						$csvSingle['Debit'],
						$csvSingle['amount'],
						'D'.str_pad($csvSingle['donor_id'], 5, '0', STR_PAD_LEFT),
						'P'.str_pad($csvSingle['project_id'], 5, '0', STR_PAD_LEFT),
						$csvSingle['status'],
						$csvSingle['name_title'].' '.$csvSingle['name_first'].' '.$csvSingle['name_last']				
					);*/
					$line = array(); 
					foreach($header_type as $htk=>$htv){
						$sdv = ( isset($csvSingle[$htk]) && !empty($csvSingle[$htk]))?($csvSingle[$htk]):""; 
						array_push($line,$sdv); 
					}
					//print_r($line); exit; 
					fputcsv($f, $line, $delimiter);
				}		
			fclose($f);	
		exit; 
	}	
	
}