<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');


class DonorforceControllerDonors extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	/**
	 * Proxy for getModel.
	 * @since	1.6 
	 */
	public function getModel($name = 'Donor', $prefix = 'donorforceModel') {
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	 
	public function importDonors(){
		 JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=donors&layout=import');	
		 		
	}
	
	public function backtodonors(){
			JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=donors');	
}
	
	public function exportDonors(){
		//echo "exportDonors ";  
		$post = JRequest::get();
		$cid = $post['cid'];
		// echo "<pre>";   print_r( $post );	exit;
		 $db = JFactory::getDbo();
		 // The following check allows the admin to export the data with or without selection
		if (!empty($cid)) {
			$query = $db->getQuery(true);
			$query->select('a.*,u.email as email,u.username as username,u.password');
			$query->from('#__donorforce_donor AS a');	
			$query->join('LEFT', '#__users AS u ON u.id=a.cms_user_id');
			$query->where('a.donor_id IN ('.implode(',',$cid).')');
			$orderCol	= $post['filter_order'];
			$orderDirn	= $post['filter_order_Dir']; 
			if($orderCol == '') $orderCol='a.name';
			if($orderDirn == '') $orderDirn='asc';
			$query->order($db->escape($orderCol.' '.$orderDirn));
			$db->setQuery($query);
			$csvData = $db->loadAssocList();
		}
		else{
			$query = $db->getQuery(true);
			$query->select('a.*,u.email as email,u.username as username,u.password');
			$query->from('#__donorforce_donor AS a');	
			$query->join('LEFT', '#__users AS u ON u.id=a.cms_user_id');
			// $query->where('a.donor_id IN ('.implode(',',$cid).')');
			$orderCol	= $post['filter_order'];
			$orderDirn	= $post['filter_order_Dir']; 
			if($orderCol == '') $orderCol='a.name';
			if($orderDirn == '') $orderDirn='asc';
			$query->order($db->escape($orderCol.' '.$orderDirn));
			$db->setQuery($query);
			$csvData = $db->loadAssocList();
		}
		// echo "<pre> csvData ";   print_r( $csvData ); echo "</pre>"; 	 exit;
		// return $csvData;
		
		// $db->setQuery($query);
		// $csvData = $db->loadAssocList();
		
		// echo "<pre> csvData ";   print_r( $csvData ); echo "</pre>"; 	 exit; 
		
				$filename = "doners.csv"; $delimiter=",";
				header('Content-Type: application/csv');
				header('Content-Disposition: attachement; filename="'.$filename.'";');
				$f = fopen('php://output', 'w');
				
				/*$temp = array(
					'Donation ID',
					'Date',
					'Reference',
					'Project', 
					'Donor',
					'Amount',
					'Donation Status'
				);*/
				
				
				$temp = array(
				'Title', 
				'First name', 
				'Surname', 
				'Telephone', 
				'Birthday', 
				'Mobile Phone', 
				'Email', 
				'Organisation Type', 
				'Organisation Name', 
				'Donor level', 
				'Membership', 
				'Status', 
				'Username', 
				'password', 
				'Physical - Address', 
				'Physical -Address Line 2', 
				'Physical -City', 
				'Physical -Zip / Postal Code', 
				'Physical -Province', 
				'Physical - Country', 
				'Postal - Address', 
				'Postal - Address Line 2', 
				'Postal - City', 
				'Postal - Zip / Postal Code', 
				'Postal - State / Province', 
				'Postal - Postal - Country'
				);


				fputcsv($f,$temp, $delimiter);
				
				foreach ($csvData as $csvSingle) {
						 $line =  array(
						 					$csvSingle['name_title'],
											$csvSingle['name_first'],
											$csvSingle['name_last'],
											$csvSingle['phone'],
											$csvSingle['dateofbirth'],
											$csvSingle['phone'],
											$csvSingle['email'],
											$csvSingle['org_type'],
											$csvSingle['org_name'],
											$csvSingle['level'],
											$csvSingle['membership'],
											$csvSingle['status'],
											$csvSingle['username'],
											$csvSingle['password'],
											$csvSingle['phy_address'],
											$csvSingle['phy_address2'],
											$csvSingle['phy_city'],
											$csvSingle['phy_zip'],
											$csvSingle['phy_state'],
											$csvSingle['phy_country'],
											$csvSingle['post_address'],
											$csvSingle['post_address2'],
											$csvSingle['post_city'],
											$csvSingle['post_zip'],
											$csvSingle['post_state'],
											$csvSingle['post_country']

						 				); 
					fputcsv($f, $line, $delimiter);
				}
				
			fclose($f);	
		exit; 
	 	
	}
	
	
	public function uploadfile(){
			 
				JToolBarHelper::custom('donors.backtodonors','new','icon-new icon-white','Back To Donors ','');
		 		//echo "<br /> donors upload files"; 
				$return = '';
				jimport('joomla.filesystem.file');
				jimport( 'joomla.filesystem.folder' );
				$file = JRequest::getVar('file_upload', null, 'files', 'array');
				//Clean up filename to get rid of strange characters like spaces etc
				$filename = JFile::makeSafe($file['name']);
				$filename =  preg_replace('/\s+/', '_', $filename);	
				 
				//Set up the source and destination of the file
				$src = $file['tmp_name'];
				$dest = JPATH_ROOT . DS . "media".DS."UploadCSV";
				if(!JFolder::exists($dest)){ JFolder::create($dest); }
				$dest = $dest . DS . $filename;
				//echo "<br> Destination = ".$dest; 
				 
				//First check if the file has the right extension, we need jpg only
				$file_extension = strtolower(JFile::getExt($filename) );
				if($file_extension == 'csv'){
					 if(JFile::upload($src, $dest)){	
					 				 echo "<h4>Processing CSV </h4>";
									?>  
                  <form action="/action_page.php" method="get">
<button type="submit" formaction="/index.php?option=com_donorforce&view=donors">Submit to another page</button>
</form>
                  
 <form action="http://localhost/wycliffe2/administrator/index.php?option=com_donorforce&view=donors"><input type="submit" value="Back" />
</form>
 <?php
								echo "<br><p> File Succesfully Uploaded</p>"; 
								 
								
								 JFactory::getApplication()->redirect('index.php?option=com_donorforce&view=donors&layout=process_donors&csvFile='.$dest); 
		 						$app->redirect($url,'CSV File Processing','success'); 
								
							  //$this->processDonorsCSV($dest);
 
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
	
	 
	

}
?>
