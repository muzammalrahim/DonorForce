<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');

$csvFile = JRequest::getVar('csvFile'); 

if(!file_exists($csvFile)){ echo " <h2> File Not Exist  </h2> ";  exit;  }
	 
	 $db = JFactory::getDbo();
	 $file_handle = fopen($csvFile, 'r');
		while (!feof($file_handle) ) {
			$line_of_text[] = fgetcsv($file_handle, 1024);
		}
		fclose($file_handle); 
		$import_data = $line_of_text ;   
		
     echo ' <div id="cpanel" style="float:left; width:90%;">
    
      <div>
      <table class="table table-striped import_table">
      <thead>
      <tr>
			<th>CSV Row </th>
			<th> Process Message </th>
			<th> Name </th>  
			<th> Username </th>
			<th> Email </th>
      </tr>
      </thead>
      <tbody>'; 
		 
		foreach($import_data as $i=>$row){
			if( $i == '0') continue; 
			if(!$row) continue;
			// echo "<pre>"; print_r($row); exit;
			echo "<tr><td>$i</td>"; 

			// changes according to the new import file
			$name_title = $row[0];
			$f_name = $row[1];
			$L_name = $row[2];
			$phone = $row[3];
			$mobile = $row[4];
			$email = $row[5];

			$dateofbirth = '';
			$mobile = '';

			$phy_address=$row[6];
			$phy_address2=$row[7];
			$phy_city=$row[8];
			$phy_state=$row[9];
			$phy_country=$row[10];
			$phy_zip='';

			$post_address=$row[11];
			$post_address2=$row[12];
			$post_city=$row[13];
			$post_zip=$row[14];
			$post_state=$row[15];
			$post_country=$row[16]; 
			
			$username=$row[17];
			$password=$row[18];

			$level = '';
			$org_name = '';

			/* $phy_zip=$row[10];


			$org_type =$row[7];
			$org_name =$row[8];
			$level=$row[9];
			$membership=$row[10];
			$status=$row[11]; */

	   /*New Donor Data*/
			 /* $name_title = $row[0]; 
			 $f_name = $row[1]; 
			 $L_name = $row[2]; 
			 //$company = $row[3];
			 $phone = $row[3];
			 $dateofbirth = $row[4];
			 $mobile = $row[5]; 
			 $email = $row[6]; 	
			 $org_type =$row[7];
			 $org_name =$row[8];
			 $level=$row[9];
			 $membership=$row[10];
			$status=$row[11];
			$username=$row[12];
			$password=$row[13];
			$phy_address=$row[14];
			$phy_address2=$row[15];
			$phy_city=$row[16];
			$phy_state=$row[17];
			$phy_zip=$row[18];
			
			$phy_country=$row[19];
			$post_address=$row[20];
			$post_address2=$row[21];
			$post_city=$row[22];
			$post_zip=$row[23];
			$post_state=$row[24];
			$post_country=$row[25];  */
			 
			 
		 /* Check for empty row */	
		 if(empty($username) || empty($email) || (empty($f_name) && empty($L_name))){
				echo "<td colspan='9'>Empty Username / Email / Name allowed at row $i</td></tr>";  
				continue;	 	
		 }
			
		 
		 /*check username + email  */
		 /* if email or username already exist then continue to next row in csv and echo message. */
			$query = $db->getQuery(true);
			$query->select($db->quote(array('username', 'email', 'name')));
			$query->from('#__users');	
			$query->where($db->quoteName('username') . '='. $db->quote($username),'OR');
			$query->where($db->quoteName('email') . '='. $db->quote($email));
			$db->setQuery($query);
			$user_check = $db->loadObjectList();
			//echo "<p>User name or Email already exist. at row $i </p>";  
			if( !empty($user_check)){ 
				echo "<td colspan='9'>Username / Email  already exits at row $i</td></tr>";  
				continue;
			}
 	 
 
		 /* Insert New User Data */			 
			$data = array(
				"name"=>$f_name.' '.$L_name,
				"username"=>$username,
				"password"=> $password,
				"password2"=> $password,
				"email"=>$email,
				"block"=>0,
				"groups"=>array("2")
			);

			//echo "<pre>"; 	 print_r($data); exit;  
		
    $user = new JUser;
    if(!$user->bind($data)) {
				echo "<td colspan='3'> Could not bind data. Error: " . $user->getError()." at row $i</td>"; 
    }
    if (!$user->save()) {
        echo "<td colspan='3'>  Could not save user. Error:" . $user->getError()." at row $i</td>"; 
    }
   
		/*Insert New Donor Data*/
		if($user->id){
						
						
				$query = $db->getQuery(true);
				$columns = array('user_id', 'profile_key', 'profile_value', 'ordering');
				$values = array();
				$values[] = $user->id.','.$db->quote('profile.address1').','.$db->quote($phy_address).','.'1)'; 
				$values[] = '('.$user->id.','.$db->quote('profile.address2').','.$db->quote($phy_address2).','.'2)'; 
				$values[] = '('.$user->id.','.$db->quote('profile.city').','.$db->quote($phy_city).','.'3)';
				$values[] = '('.$user->id.','.$db->quote('profile.country').','.$db->quote($phy_country).','.'5)';
				$values[] = '('.$user->id.','.$db->quote('profile.postal_code').','.$db->quote($phy_zip).','.'6)';
				$values[] = '('.$user->id.','.$db->quote('profile.aboutme').','.$db->quote($org_name).','.'10'; 
				
				$query->insert($db->quoteName('#__user_profiles'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
				$db->setQuery($query);
				$profile_status = $db->execute();
				 
				
				
				$query = $db->getQuery(true);
				
				$dcolumns = array(
				'name_first',
				'name_last', 
				'cms_user_id',
				'status', 
				'phone',
				'dateofbirth',			
				'mobile',
				'phy_address', 
				'phy_address2',
				'phy_city', 
				'phy_state',
				'phy_zip',
				'phy_country',
				'post_address', 
				'post_address2', 
				'post_city', 
				'post_state', 
				'post_zip',
				'post_country',
				'published',
				'name_title', 
				'level',
				'org_name'
				);
				
			 
				
			$dvalues =	array(
			 	$db->quote($f_name), $db->quote($L_name),
				$user->id, $db->quote('acitve'), $db->quote($phone), 
				$db->quote($dateofbirth), $db->quote($mobile),$db->quote($phy_address),
				$db->quote($phy_address2), $db->quote($phy_city),
				$db->quote($phy_zip), $db->quote($phy_state), $db->quote($phy_country), $db->quote($post_address),
				$db->quote($post_address2), $db->quote($post_city),$db->quote($post_state),
				$db->quote($post_zip), $db->quote($post_country),1,				
				$db->quote($name_title),$db->quote($level),
				$db->quote($org_name)
				);
				
			$query->insert($db->quoteName('#__donorforce_donor')); 
			$query->columns($db->quoteName($dcolumns));
			$query->values(implode(',', $dvalues));
			$db->setQuery($query);
			$donor_status = $db->execute();
			//echo " <pre>   donor_status  ";  print_r( $donor_status ); echo " </pre> ";   
			if(  $donor_status != 1 ){ echo " <td>  donor not inserted  </td>";   }				
			
			
			echo "<td> Import success </td>
						<td> $f_name $L_name </td>
						<td> $username </td>
						<td> $email </td>
					";				
		
		//if user id 	
		}else{ echo " <td> user not inserted </td>"; }
				
		echo "</tr>"; 			
		
		}// foreach end here 
		
		echo "</tbody>
		<tfoot><tr><td>CSV Processed End </td></tr></tfoot>
		</table></div></div>";  
		
?>		
  

<div style="clear:both;"></div>
<div align="right"> Powered by Netwise Multimedia <br /> Version <?php echo $this->donorForceVersion; ?>
</div>