<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die();
//jimport('joomla.application.component.controllerform');
jimport('joomla.application.component.controllerlegacy');

class DonorforceControllerAjax extends JControllerLegacy
{

	public function changeHistoryStatus()
	{
		$id=JRequest::getVar('history_id',0);
		$status=JRequest::getVar('status');	
		$id=substr($id, 3);	
		
		if(empty($id) || empty($status))return false;
				
		$db = JFactory::getDbo();
		
		$query="
			update #__donorforce_history 
			set status = '$status'
			where donor_history_id = ".$id;
		
		$db->setQuery($query);
				
		if (!$db->query()) 
		{
			JError::raiseError(500, $db->getErrorMsg());
			echo 'History record updation failed.';
				return false;
		}
		else		
			echo 'History record updated ';
		
		if( ($status == 'Successful') || ($status == 'successful') ){
			$adddonation_model = JModelLegacy::getInstance('Adddonation', 'DonorforceModel'); 
			//echo "<pre>"; print_r($adddonation_model ); echo "</pre>";  
			$adddonation_model->sendemail($id); 			
		}	
		
	}
	
	
	public function gelete_gift(){		
		
		$jinput = JFactory::getApplication()->input;
		$donor_id = $jinput->get('donor_id', '');
		$gift_id = $jinput->get('gift_id', '');
		
		if($donor_id != '' && $gift_id != ''){			
				$db=JFactory::getDbo();			
				$query=" DELETE FROM `#__donorforce_gift` WHERE  `gift_id` = ".$gift_id." AND donor_id = ".$donor_id; 
				$db->setQuery($query);			 
				$result = $db->execute();
				echo $result;				
				//return $result;
		}
		
	}
	
	
	
	
	
	//-------------------------------- Change the Status of gift ------------------------------//
	public function changeGiftStatus()
	{
		$id=JRequest::getVar('gift_id',0);
		$status=JRequest::getVar('status');	
		$id=substr($id, 3);	
		if(empty($id) || empty($status))return false;				
		$db = JFactory::getDbo();		
		$query="
			update #__donorforce_gift 
			set status = '$status'
			where gift_id = ".$id;
			
		$db->setQuery($query);				
		if (!$db->query()) 
		{
			JError::raiseError(500, $db->getErrorMsg());
			echo 'Gift Record Updation Failed.';
				return false;
		}
		else		
			echo 'Gift Record Updated ';
		
		if( ($status == 'Successful') || ($status == 'successful') ){
			$addgift_model = JModelLegacy::getInstance('Addgift', 'DonorforceModel'); 
			$addgift_model->sendemail($id);			
		}	
		
	}
	
	
	
	public function changeSubscriptionType()
	{
		
		$id=JRequest::getVar('subscription_id',0);
		
		$type=JRequest::getVar('sub_type');		
		
		if(empty($id) || empty($type))return false;
				
		$db = JFactory::getDbo();
		
		$query="
			update #__donorforce_donor_subscriptions 
			set donation_type = '$type'
			where subscription_id = ".$id;
		
		$db->setQuery($query);
				
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
				return false;
		}
		else		
			echo 'updated';
		
	}
	
	public function deleteFile()
	{
		$id=JRequest::getVar('opt',0);
		$path=JRequest::getVar('path',0);
		
		if(empty($id)||empty($path))return false;
		
		//JURI::root()."/images/donorforceFiles/".$file->path
				
		$db = JFactory::getDbo();
		
		$query="delete from #__ts_digital_files where digital_id=".$id;
		
		$db->setQuery($query);
		
		//Override End
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
				return false;
		} 
		
		$deleted=unlink(JPATH_SITE.DS."images".DS."donorforceFiles".DS.$path);
		
		//if($deleted)
			echo 'deleted';
		
	}
	
	public function donationActivities()
	{
		$donationId=JRequest::getVar('donationId',0);
		if(empty($donationId))return false;
		$db = JFactory::getDbo();
		
		$query="select * from #__ts_donations where donation_id=".$donationId;
		
		$db->setQuery($query);
		
		
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
				return false;
		} 
		else {
				 $opt = $db->loadObject();

					$myAr= array(
								"donor"    =>  $opt->donor_id,
								"inspector" =>  $opt->inspector_id,
								"project"  =>  $opt->project_id,
								"order"  =>  $opt->order_number
							   );
				 echo json_encode($myAr);
		}

		
	}
	
	
	//check if user as a donor or handbook user exists
	public function searchUserByName(){
		$post = JRequest::get('post');
		$name = isset($post['q']) ? $post['q'] : '';
		$db = JFactory::getDbo();
		$sql = "SELECT `member_id` AS id, `name` FROM `#__bt_members` WHERE `name` LIKE '%{$name}%'";
		$db->setQuery($sql);
		$res = $db->loadObjectList();
		if(!empty($res)){
			$in = 0;
			foreach($res as $data){
				$hbquery = "SELECT donor_id FROM #__donorforce_donor WHERE `name_first` LIKE '%{$data->name}%' OR `name_last` LIKE '%{$data->name}%'";	
				$db->setQuery($hbquery);
				$db->execute();
				$num_rows = $db->getNumRows();
				if($num_rows > 0){
					unset($res[$in]);
				}
				$in++; 
			}
			echo json_encode($res);
		}else{
			$hbquery = "SELECT donor_id FROM #__donorforce_donor WHERE `name_first` LIKE '%{$name}%' OR `name_last` LIKE '%{$name}%'";	
			$db->setQuery($hbquery);
			$db->execute();
			$num_rows = $db->getNumRows();
			if($num_rows > 0){
				echo json_encode(array(array('result'=>'0','name'=>'Already a Donor.')));	
			}else{
				echo json_encode(array(array('id'=>ucwords($name),'name'=>ucwords($name))));
			}
		}
	}
	
	//country code and names array 
	public function getCountriesList(){
		return array (
		  'AD' => 'Andorra',
		  'AE' => 'United Arab Emirates',
		  'AF' => 'Afghanistan',
		  'AG' => 'Antigua and Barbuda',
		  'AI' => 'Anguilla',
		  'AL' => 'Albania',
		  'AM' => 'Armenia',
		  'AN' => 'Netherlands Antilles',
		  'AO' => 'Angola',
		  'AQ' => 'Antarctica',
		  'AR' => 'Argentina',
		  'AS' => 'American Samoa',
		  'AT' => 'Austria',
		  'AU' => 'Australia',
		  'AW' => 'Aruba',
		  'AX' => 'Åland Islands',
		  'AZ' => 'Azerbaijan',
		  'BA' => 'Bosnia and Herzegovina',
		  'BB' => 'Barbados',
		  'BD' => 'Bangladesh',
		  'BE' => 'Belgium',
		  'BF' => 'Burkina Faso',
		  'BG' => 'Bulgaria',
		  'BH' => 'Bahrain',
		  'BI' => 'Burundi',
		  'BJ' => 'Benin',
		  'BL' => 'Saint Barthélemy',
		  'BM' => 'Bermuda',
		  'BN' => 'Brunei',
		  'BO' => 'Bolivia',
		  'BQ' => 'British Antarctic Territory',
		  'BR' => 'Brazil',
		  'BS' => 'Bahamas',
		  'BT' => 'Bhutan',
		  'BV' => 'Bouvet Island',
		  'BW' => 'Botswana',
		  'BY' => 'Belarus',
		  'BZ' => 'Belize',
		  'CA' => 'Canada',
		  'CC' => 'Cocos [Keeling] Islands',
		  'CD' => 'Congo - Kinshasa',
		  'CF' => 'Central African Republic',
		  'CG' => 'Congo - Brazzaville',
		  'CH' => 'Switzerland',
		  'CI' => 'Côte d`Ivoire',
		  'CK' => 'Cook Islands',
		  'CL' => 'Chile',
		  'CM' => 'Cameroon',
		  'CN' => 'China',
		  'CO' => 'Colombia',
		  'CR' => 'Costa Rica',
		  'CS' => 'Serbia and Montenegro',
		  'CT' => 'Canton and Enderbury Islands',
		  'CU' => 'Cuba',
		  'CV' => 'Cape Verde',
		  'CX' => 'Christmas Island',
		  'CY' => 'Cyprus',
		  'CZ' => 'Czech Republic',
		  'DD' => 'East Germany',
		  'DE' => 'Germany',
		  'DJ' => 'Djibouti',
		  'DK' => 'Denmark',
		  'DM' => 'Dominica',
		  'DO' => 'Dominican Republic',
		  'DZ' => 'Algeria',
		  'EC' => 'Ecuador',
		  'EE' => 'Estonia',
		  'EG' => 'Egypt',
		  'EH' => 'Western Sahara',
		  'ER' => 'Eritrea',
		  'ES' => 'Spain',
		  'ET' => 'Ethiopia',
		  'FI' => 'Finland',
		  'FJ' => 'Fiji',
		  'FK' => 'Falkland Islands',
		  'FM' => 'Micronesia',
		  'FO' => 'Faroe Islands',
		  'FQ' => 'French Southern and Antarctic Territories',
		  'FR' => 'France',
		  'FX' => 'Metropolitan France',
		  'GA' => 'Gabon',
		  'GB' => 'United Kingdom',
		  'GD' => 'Grenada',
		  'GE' => 'Georgia',
		  'GF' => 'French Guiana',
		  'GG' => 'Guernsey',
		  'GH' => 'Ghana',
		  'GI' => 'Gibraltar',
		  'GL' => 'Greenland',
		  'GM' => 'Gambia',
		  'GN' => 'Guinea',
		  'GP' => 'Guadeloupe',
		  'GQ' => 'Equatorial Guinea',
		  'GR' => 'Greece',
		  'GS' => 'South Georgia and the South Sandwich Islands',
		  'GT' => 'Guatemala',
		  'GU' => 'Guam',
		  'GW' => 'Guinea-Bissau',
		  'GY' => 'Guyana',
		  'HK' => 'Hong Kong SAR China',
		  'HM' => 'Heard Island and McDonald Islands',
		  'HN' => 'Honduras',
		  'HR' => 'Croatia',
		  'HT' => 'Haiti',
		  'HU' => 'Hungary',
		  'ID' => 'Indonesia',
		  'IE' => 'Ireland',
		  'IL' => 'Israel',
		  'IM' => 'Isle of Man',
		  'IN' => 'India',
		  'IO' => 'British Indian Ocean Territory',
		  'IQ' => 'Iraq',
		  'IR' => 'Iran',
		  'IS' => 'Iceland',
		  'IT' => 'Italy',
		  'JE' => 'Jersey',
		  'JM' => 'Jamaica',
		  'JO' => 'Jordan',
		  'JP' => 'Japan',
		  'JT' => 'Johnston Island',
		  'KE' => 'Kenya',
		  'KG' => 'Kyrgyzstan',
		  'KH' => 'Cambodia',
		  'KI' => 'Kiribati',
		  'KM' => 'Comoros',
		  'KN' => 'Saint Kitts and Nevis',
		  'KP' => 'North Korea',
		  'KR' => 'South Korea',
		  'KW' => 'Kuwait',
		  'KY' => 'Cayman Islands',
		  'KZ' => 'Kazakhstan',
		  'LA' => 'Laos',
		  'LB' => 'Lebanon',
		  'LC' => 'Saint Lucia',
		  'LI' => 'Liechtenstein',
		  'LK' => 'Sri Lanka',
		  'LR' => 'Liberia',
		  'LS' => 'Lesotho',
		  'LT' => 'Lithuania',
		  'LU' => 'Luxembourg',
		  'LV' => 'Latvia',
		  'LY' => 'Libya',
		  'MA' => 'Morocco',
		  'MC' => 'Monaco',
		  'MD' => 'Moldova',
		  'ME' => 'Montenegro',
		  'MF' => 'Saint Martin',
		  'MG' => 'Madagascar',
		  'MH' => 'Marshall Islands',
		  'MI' => 'Midway Islands',
		  'MK' => 'Macedonia',
		  'ML' => 'Mali',
		  'MM' => 'Myanmar [Burma]',
		  'MN' => 'Mongolia',
		  'MO' => 'Macau SAR China',
		  'MP' => 'Northern Mariana Islands',
		  'MQ' => 'Martinique',
		  'MR' => 'Mauritania',
		  'MS' => 'Montserrat',
		  'MT' => 'Malta',
		  'MU' => 'Mauritius',
		  'MV' => 'Maldives',
		  'MW' => 'Malawi',
		  'MX' => 'Mexico',
		  'MY' => 'Malaysia',
		  'MZ' => 'Mozambique',
		  'NA' => 'Namibia',
		  'NC' => 'New Caledonia',
		  'NE' => 'Niger',
		  'NF' => 'Norfolk Island',
		  'NG' => 'Nigeria',
		  'NI' => 'Nicaragua',
		  'NL' => 'Netherlands',
		  'NO' => 'Norway',
		  'NP' => 'Nepal',
		  'NQ' => 'Dronning Maud Land',
		  'NR' => 'Nauru',
		  'NT' => 'Neutral Zone',
		  'NU' => 'Niue',
		  'NZ' => 'New Zealand',
		  'OM' => 'Oman',
		  'PA' => 'Panama',
		  'PC' => 'Pacific Islands Trust Territory',
		  'PE' => 'Peru',
		  'PF' => 'French Polynesia',
		  'PG' => 'Papua New Guinea',
		  'PH' => 'Philippines',
		  'PK' => 'Pakistan',
		  'PL' => 'Poland',
		  'PM' => 'Saint Pierre and Miquelon',
		  'PN' => 'Pitcairn Islands',
		  'PR' => 'Puerto Rico',
		  'PS' => 'Palestinian Territories',
		  'PT' => 'Portugal',
		  'PU' => 'U.S. Miscellaneous Pacific Islands',
		  'PW' => 'Palau',
		  'PY' => 'Paraguay',
		  'PZ' => 'Panama Canal Zone',
		  'QA' => 'Qatar',
		  'RE' => 'Réunion',
		  'RO' => 'Romania',
		  'RS' => 'Serbia',
		  'RU' => 'Russia',
		  'RW' => 'Rwanda',
		  'SA' => 'Saudi Arabia',
		  'SB' => 'Solomon Islands',
		  'SC' => 'Seychelles',
		  'SD' => 'Sudan',
		  'SE' => 'Sweden',
		  'SG' => 'Singapore',
		  'SH' => 'Saint Helena',
		  'SI' => 'Slovenia',
		  'SJ' => 'Svalbard and Jan Mayen',
		  'SK' => 'Slovakia',
		  'SL' => 'Sierra Leone',
		  'SM' => 'San Marino',
		  'SN' => 'Senegal',
		  'SO' => 'Somalia',
		  'SR' => 'Suriname',
		  'ST' => 'São Tomé and Príncipe',
		  'SU' => 'Union of Soviet Socialist Republics',
		  'SV' => 'El Salvador',
		  'SY' => 'Syria',
		  'SZ' => 'Swaziland',
		  'TC' => 'Turks and Caicos Islands',
		  'TD' => 'Chad',
		  'TF' => 'French Southern Territories',
		  'TG' => 'Togo',
		  'TH' => 'Thailand',
		  'TJ' => 'Tajikistan',
		  'TK' => 'Tokelau',
		  'TL' => 'Timor-Leste',
		  'TM' => 'Turkmenistan',
		  'TN' => 'Tunisia',
		  'TO' => 'Tonga',
		  'TR' => 'Turkey',
		  'TT' => 'Trinidad and Tobago',
		  'TV' => 'Tuvalu',
		  'TW' => 'Taiwan',
		  'TZ' => 'Tanzania',
		  'UA' => 'Ukraine',
		  'UG' => 'Uganda',
		  'UM' => 'U.S. Minor Outlying Islands',
		  'US' => 'United States',
		  'UY' => 'Uruguay',
		  'UZ' => 'Uzbekistan',
		  'VA' => 'Vatican City',
		  'VC' => 'Saint Vincent and the Grenadines',
		  'VD' => 'North Vietnam',
		  'VE' => 'Venezuela',
		  'VG' => 'British Virgin Islands',
		  'VI' => 'U.S. Virgin Islands',
		  'VN' => 'Vietnam',
		  'VU' => 'Vanuatu',
		  'WF' => 'Wallis and Futuna',
		  'WK' => 'Wake Island',
		  'WS' => 'Samoa',
		  'YD' => 'People\'s Democratic Republic of Yemen',
		  'YE' => 'Yemen',

		  'YT' => 'Mayotte',
		  'ZA' => 'South Africa',
		  'ZM' => 'Zambia',
		  'ZW' => 'Zimbabwe',
		  'ZZ' => 'Unknown or Invalid Region',
		);
	}
	
	public function GetDonorsData(){
		$db=JFactory::getDbo();
		$query="
			SELECT donor_id, name_first, name_last
			FROM #__donorforce_donor ORDER BY name_first ";
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		
		?><label id="" for="jform_donor_id" class="" style="margin-top: 10px; margin-bottom: 5px; "> Select Donor </label>
		<select id="jform_donor_id" name="jform[donor_id]" class="inputbox chosen-select" size="1" aria-invalid="false">
		    
		<?php
			foreach($items as $item){ 
				echo '<option value="'.$item->donor_id.'"> '.$item->name_first." ".$item->name_last.' </option>';
			}
		?> 
		</select>
		<?php 
		$query2 = "SELECT project_id, name 
				 FROM #__donorforce_project ORDER BY name ";
		$db->setQuery($query2);
		$items2 = $db->loadObjectList();
		if ($error2 = $db->getErrorMsg()) 
		{
			$this->setError($error2);
			return false;				
		}?>
        <label id="" for="jform_project_id" class="" style="margin-top: 10px; margin-bottom: 5px; "> Select Project </label>
		<select id="jform_project_id" name="jform[project_id]" class="inputbox chosen-select" size="1" aria-invalid="false">
			<?php foreach($items2 as $item2){ 
			  echo '<option value="'.$item2->project_id.'"> '.$item2->name.' </option>';
			}?> 
		</select>
	<?php }
	
	
	
	
	public function GetDonorsData_return(){
		$return_html = ''; 
		
		$db=JFactory::getDbo();
		$query="
			SELECT donor_id, name_first, name_last
			FROM #__donorforce_donor ORDER BY name_first ";
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		
		$return_html .= '<div class="donor ib"><label  style="margin-top: 10px; margin-bottom: 5px; "> Select Donor </label><select id="jform_donor_id" name="jform[donor_id]" class="inputbox chosen-select" size="1" aria-invalid="false">';
		
			foreach($items as $item){ 
				$return_html .= '<option value="'.$item->donor_id.'"> '.$item->name_first." ".$item->name_last.' </option>';
			}
		$return_html .='</select></div>';
	 
		$query2 = "SELECT project_id, name 
				 FROM #__donorforce_project ORDER BY name ";
		$db->setQuery($query2);
		$items2 = $db->loadObjectList();
		if ($error2 = $db->getErrorMsg()) 
		{
			$this->setError($error2);
			return false;				
		}
		
		$return_html .='<div class="project ib"><label style="margin-top: 10px; margin-bottom: 5px; "> Select Project </label><select id="jform_project_id" name="jform[project_id]" class="inputbox chosen-select" size="1" aria-invalid="false">';
		  foreach($items2 as $item2){ 
			  
					$return_html .= '<option value="'.$item2->project_id.'"> '.$item2->name.' </option>';
			}
				$return_html .='</select></div>';
	
			return $return_html;	
	}
	
	
	
	
	public function GetProjectsData(){
		$db=JFactory::getDbo();
		$query="
			SELECT project_id, name 
			FROM #__donorforce_project ORDER BY name ";
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		
		?><label id="" for="jform_project_id" class="" style="margin-top: 10px; margin-bottom: 5px; "> Select Project </label>
		<select id="jform_project_id" name="jform[project_id]" class="inputbox" size="1" aria-invalid="false">
		    
		<?php
			foreach($items as $item){ 
				echo '<option value="'.$item->project_id.'"> '.$item->name.' </option>';
			}
		?> 
		</select>
		<?php //return $retur_data   ; 
	}
	
	
	public function Return_ProjectsList( $project_no = '' ){
		$db=JFactory::getDbo();
		$query="
			SELECT project_id, name 
			FROM #__donorforce_project ORDER BY name ";
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		$return_html = ''; 
		$return_html .= '
		<label class="labl_select_project"> Select Project </label>
		<select id="jform_project_id" class="select_project"  name="jform[project_id]" class="inputbox" size="1" aria-invalid="false">'; 
		
			$first	= ($project_no == '')? true : false;   
			foreach($items as $item){
				if($first){
					$return_html .= '<option value="0" selected="selected">-- Select Project --</option>';
					$first = false; 	
				}else if( $project_no == $item->project_id ){ 
					$return_html .= '<option value="'.$item->project_id.'" selected="selected"> '.$item->name.' </option>'; 
				}else{ 
					$return_html .= '<option value="'.$item->project_id.'"> '.$item->name.' </option>';
				}
			}
	 $return_html .='</select>'; 
		 return $return_html   ; 
	}
	
	
	public function Return_DonorsList( $donor_no = '' ){
		$return_html = ''; 
		$db=JFactory::getDbo();
		$query="
			SELECT donor_id, name_first, name_last
			FROM #__donorforce_donor ORDER BY name_first ";
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		
		$return_html .= '<div class="donor ib">
			<label class="labl_select_donor"> Select Donor </label>
			<select id="jform_donor_id" class="select_donor"  name="jform[donor_id]" class="inputbox chosen-select" size="1" aria-invalid="false">';
		 $first = ($donor_no == '')? true : false; 
		  
			foreach($items as $item){
				if($first){
					$return_html .= '<option value="0" selected="selected">-- Select Donor --</option>';
					$first = false; 
					
				}else if($item->donor_id == $donor_no ){
					$return_html .= '<option value="'.$item->donor_id.'" selected="selected"> '.$item->name_first." ".$item->name_last.' </option>';
				}else{
					$return_html .= '<option value="'.$item->donor_id.'"> '.$item->name_first." ".$item->name_last.' </option>';
				}
			}
		$return_html .='</select></div>';
		 return $return_html; 
		}
	
	
	
	public function update_donor_history(){
		$db=JFactory::getDbo();
		$jinput = JFactory::getApplication()->input;
		$donor_id = $jinput->get('donor_id', '');
		$project_id = $jinput->get('project_id', '');
		$donor_history_id = $jinput->get('donor_history_id', '');
		
		$donor_query = "SELECT cms_user_id 
						FROM #__donorforce_donor WHERE `donor_id` = $donor_id";
		$db->setQuery($donor_query);
		$donor_data = $db->loadObject();
		$cms_user_id = $donor_data->cms_user_id;
		
		if( !empty($donor_id) && !empty($project_id) && !empty($cms_user_id) ){
			$query="
			UPDATE  #__donorforce_history  SET `donor_id` = $donor_id, `project_id` = $project_id, `cms_user_id` = $cms_user_id
			WHERE  `donor_history_id` = $donor_history_id";
			$db->setQuery($query);
			$result = $db->execute();	
			echo $result; 
		}elseif( empty($cms_user_id) ){ echo "CMS_USER_ID of Donor $donor_id is Empty"; }
		else{  echo "ERROR ";  }		  
			
	}
	
	
	//------------------------ Update Gift function  -----------------------//
	public function update_gift(){
		$db=JFactory::getDbo();
		$jinput = JFactory::getApplication()->input;
		$donor_id = $jinput->get('donor_id', '');
		$project_id = $jinput->get('project_id', '');
		$gift_id = $jinput->get('gift_id', '');
		
		if( !empty($donor_id) && !empty($project_id)){
			$query="
			UPDATE  #__donorforce_gift  SET `donor_id` = $donor_id, `project_id` = $project_id
			WHERE  `gift_id` = $gift_id";
			$db->setQuery($query);
			$result = $db->execute();	
			echo $result; 
		}elseif( empty($cms_user_id) ){ echo "CMS_USER_ID of Donor $donor_id is Empty"; }
		else{  echo "ERROR ";  }		  			
	}
	
	
	
	
	public function DonorHistory()
	{		
		$db=JFactory::getDbo();			
		$donor_id =		JRequest::getVar('donor_id',0);
		$offset =		JRequest::getVar('offset',0);
		$offset = ($offset-1)*20; 
		$query="
		SELECT
			#__donorforce_project.`name` AS project_name,
			#__donorforce_history.date,
			#__donorforce_history.amount,
			#__donorforce_history.`status`,
			#__donorforce_history.Reference,
			#__donorforce_history.donor_history_id
		FROM
			#__donorforce_history
		INNER JOIN #__donorforce_project ON #__donorforce_history.project_id = #__donorforce_project.project_id
		INNER JOIN #__donorforce_donor ON #__donorforce_donor.donor_id = #__donorforce_history.donor_id
		WHERE
			#__donorforce_donor.donor_id = $donor_id			
		ORDER BY #__donorforce_history.`date` DESC
		LIMIT 20 OFFSET $offset	
	";
		$db->setQuery($query);
		$items=$db->loadObjectList();
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		if(!empty($items)){ ?>
        <table class="table table-striped" id="HistoryTable">
        <thead>
          <tr><th>No</th>
            <th>Date</th>
            <th>Project</th>
            <th>Reference</th>
            <th width="15%">Amount</th>
            <th>Donation Status</th>
            <th>Reallocate</th>
            <th>Delete Donation</th>
          </tr>
        </thead>
         <tbody>
			<?php 
             $counter = 0; $number = 0;
             foreach($items as $history)
             { ?>
                <tr><td><?php $number++;  echo  $number;  ?></td>
        		<td><?php if(!empty($history->date))echo date('Y-m-d',strtotime($history->date)); ?></td>
         		<td><?php if(!empty($history->project_name))echo $history->project_name; ?></td>
                <td><?php if(!empty($history->Reference))echo $history->Reference; ?></td>
         		<td><?php if(!empty($history->amount))
                echo  DonorforceHelper::getCurrency().' '.number_format($history->amount,2,"."," ");
                //$total_donation +=  $history->amount;  ?>
        		</td>
        		<!--<td> <?php //echo ucwords($history->status); ?> </td>-->
                <!-- status -->
                <?php 
								 echo '<td id="tr'.$history->donor_history_id.'">';
								

									if($history->status=='pending')//pending
									{
										//var_dump($history);
																			  
									   echo'<select class="history_status" id="hid'.$history->donor_history_id.'" >
										  <option>'.ucwords(($history->status)).'</option>
										  <option>Successful</option>
									   </select>
									   ';
									} 
									else
									{
									   echo ucwords($history->status); 
									}
									echo '</td>';
									?>
                
                <!-- Status end -->
                
                
       			<td><button onclick="" id="relocate-<?php echo $counter;  ?>" class="btn btn-small relocate">
               			 Reallocate </button>
             		<button id="update_donor-<?php echo $counter; ?>" style="display:none;" class="btn btn-small update_donor"> 
                		Update Record </button>
             		<input type="hidden" id="donor_history_id-<?php echo $counter;  ?>" value="<?php echo $history->donor_history_id; ?>" />
             		<div id="ajaxresult-<?php echo $counter;  ?>">  </div>
          	   </td>
         		<td style="text-align: center; " >                                 
         			<?php echo '<a href="index.php?option=com_donorforce&task=Donor.delete_donation&donor_id='.$donor_id.'&id='. $history->donor_history_id.'">';    
               		echo '<span class="icon-trash" style="font-size:20px;" >  </span> </a>'; ?>                              
         		</td> 
      			</tr>                              
      		<?php $counter++;  } ?>
           </tbody>                            
        </table>
			
	   <?php }
		//return $items;	
		
			
	}//DonorHistory End
	



public function DonorGifts()
	{		
		$db=JFactory::getDbo();			
		$donor_id =		JRequest::getVar('donor_id',0);
		$offset =		JRequest::getVar('offset',0);
		$offset = ($offset-1)*20; 
		$query="
		SELECT
			p.`name` AS project_name,
			gf.date,
			gf.`status`,
			gf.reference,
			gf.gift_id,
			gf.desc
		FROM
			#__donorforce_gift As gf
		INNER JOIN #__donorforce_project As p ON gf.project_id = p.project_id
		INNER JOIN #__donorforce_donor As d ON gf.donor_id = d.donor_id
		WHERE
			d.donor_id = $donor_id			
		ORDER BY gf.`date` DESC
		LIMIT 20 OFFSET $offset	
	";
	//echo "<br /> query  = ".$query; 
		$db->setQuery($query);
		$gifts=$db->loadObjectList();
		//echo "<pre> gifts = "; print_r( $gifts  ); echo "</pre>";  
		
		if ($error = $db->getErrorMsg()) 
		{
			$this->setError($error);
			return false;				
		}
		 ?>
        <table class="table table-striped" id="GiftTable">
        <thead>
         <tr>
          <th>No</th>
          <th>Date</th>
          <th>Gift</th>
          <th>Reference</th>
          <th>Reallocate</th>
          <th>Delete Gift</th>
         </tr>
        </thead>
         <tbody>
				 <?php									 
         if(!empty($gifts))
         $gcounter = 0; $gnumber = 0;
         foreach($gifts as $i=>$gift)
         {
          ?>
           <tr>
            <td><?php $gnumber++;  echo  $gnumber;  ?></td>
            <td><?php if(!empty($gift->date))echo date('Y-m-d',strtotime($gift->date)); ?></td>
            <?php /*?><td><?php if(!empty($gift->project_name))echo $gift->project_name; ?></td><?php */?>
            <td><?php if(isset($gift->desc)) echo $gift->desc; ?></td>
            <td><?php if(!empty($gift->reference))echo $gift->reference; ?></td>
            <?php 
             /* echo '<td id="tr'.$gift->gift_id.'">';
              if(strtolower($gift->status) =='pending' )
              {																			  
               echo'<select class="gift_status" id="gid'.$gift->gift_id.'" >
                <option value="pending">Pending</option>
                <option  value="successful">Successful</option>
               </select>
               ';
              } 
              else{ echo ucwords($gift->status); }
              echo '</td>';*/
             ?>
            
            <td>                                 
             <button onclick="" id="relocate-<?php echo $gcounter;  ?>" class="btn btn-small gift_relocate">
                Reallocate
             </button>
             <button id="update_gift-<?php echo $gcounter; ?>" style="display:none;" class="btn btn-small update_gift">Update Record</button>
             <input type="hidden" id="gift_id-<?php echo $gcounter;  ?>" value="<?php echo $gift->gift_id; ?>" />
             <div id="giftajaxresult-<?php echo $gcounter;  ?>"></div>
            </td>
             
            <td style="text-align: center; " >  
              <a class="delete_gift" data-gift_id="<?php echo $gift->gift_id;?>" data-donor_id="<?php echo $donor_id;?>">
              <span class="icon-trash" style="font-size:20px;"></span> 
              </a>                                                                                        
            </td>
             
           </tr>                              
        <?php 
        $counter++; }
         ?>
         </tbody>                            
        </table>			
	   <?php
	}//GiftHistory End 
	
	



	
	
	public function export_donor_total(){
		
		$where = ' 1 '; 
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);		
		$query = 'SELECT 
									d.donor_id As ID, 
									CONCAT_WS(" ",d.name_first,d.name_last) AS Name,
									( SELECT sum(dh.amount) FROM `#__donorforce_history` AS dh
          					WHERE dh.donor_id=d.donor_id  
										AND dh.status != "pending" 
										AND dh.status != "Pending"
									) As total_donation 	
									
						FROM `#__donorforce_donor` As d'; 
			$query = $query . " WHERE d.published != 0"; 	 				
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
		$db->setQuery($query);
		$export_data = $db->loadAssocList();
		
		$header = array('Donor ID', 'Donor Name', 'Donor Total Donation');
		$header_type= array( 'ID' =>'Number', 'Name'=> 'Label', 'total_donation'=>'Label');
		//print_r(	$export_data); exit; 		
		$this->export_xls('DonorsTotalDonations',$header,$header_type,$export_data);
	
	 }
		
		
		
		
		
	public function export_xls($filename, $header,$header_type,  $Export_Data){
		
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
		}
		
		
		
		
	function export_donor_nodonation(){
			
		$where = ' 1 '; 
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$datefrom  =		JRequest::getVar('datefrom');
		$dateto   =		JRequest::getVar('dateto');
		
		
		$query = 'SELECT 
									d.donor_id As ID, 
									CONCAT_WS(" ",d.name_first,d.name_last) AS Name,
									
									( SELECT sum(dh.amount) FROM `#__donorforce_history` AS dh
          					WHERE dh.donor_id=d.donor_id '; 
										
											if($datefrom != '' && $dateto != ''  ){			
												$query = $query. " AND dh.date BETWEEN '".$datefrom."' AND  '".$dateto."' "; 
											}else if($datefrom != ''){
												$query = $query. " AND dh.date >= '".$datefrom."' "; 	
											}else if($dateto != '' ){
												$query = $query. " AND dh.date <= '".$dateto."' "; 	
											}						
							
        						$query =$query.') As total_donation 
				
						FROM `#__donorforce_donor` As d ';  		
						
	/*	if($datefrom != '' && $dateto != ''  ){			
				$query = $query. " AND dh.date BETWEEN '".$datefrom."' AND  '".$dateto."' "; 
			}else if($datefrom != ''){
				$query = $query. " AND dh.date >= '".$datefrom."' "; 	
			}else if($dateto != '' ){
				$query = $query. " AND dh.date <= '".$dateto."' "; 	
			}						
			*/
						
		//	$query = $query .' GROUP BY dh.donor_id'; 
			$query = $query . " WHERE d.published != 0"; 	 		
			$query = $query . " ORDER BY  d.donor_id  ASC "; 	 
		$db->setQuery($query);
		
		
		
		 $export_data  = array();
		 $return_data= $db->loadAssocList();
	
		foreach($return_data as $edata ){
			if($edata['total_donation'] <= 0 ){ $export_data[] = $edata;   }
			
		}
	
	/*	echo "<br /> query = $query  "; 
		echo "<pre> export_data = "; print_r($export_data);  exit;  */
		
		$header = array('Donor ID', 'Donor Name', 'Donor Total Donation');
		$header_type= array( 'ID' =>'Number', 'Name'=> 'Label', 'total_donation'=>'Label');
		$this->export_xls('Donors_No_Donation',$header,$header_type,$export_data);
				
		}
		
	
	function ProcessImport(){
		$db=JFactory::getDbo();
		$jinput = JFactory::getApplication()->input;
		
		$donor_id = $jinput->get('donor_id', '');
		$project_id = $jinput->get('project_id', '');
		$date = $jinput->get('date', '');
		$amount = $jinput->get('amount', '');
		$ref = $jinput->get('ref', '');
		$status = $jinput->get('status', '');
	 
	 	$cms_query="SELECT cms_user_id FROM #__donorforce_donor WHERE donor_id=".$donor_id; 
		$db->setQuery($cms_query);
		$cms_result = $db->loadResult(); 
	  
		//echo "<pre> cms_result = "; print_r( $cms_result  ); echo "</pre>"; 
		    
		
		if( !empty($donor_id) && !empty($project_id)){
			$insert_query="
			INSERT INTO #__donorforce_history (`donor_id`, `project_id`, `cms_user_id`, `date`, `amount`, `status`, `donation_type`, `Reference`) VALUES ( '$donor_id', '$project_id', '$cms_result', '$date' , '$amount', '$status', 'onceoff', '$ref' )";
			$db->setQuery($insert_query);
			$result['insert'] = $db->execute();
			$insertID = $db->insertid();
			ob_start();
			$this->sendEmail($insertID);
			$result['email'] = ob_get_contents();
      ob_end_clean();
			
			echo json_encode($result);
		}
		else{  echo "ERROR ";  }		  			
	
				
	}

	//The section of code is for the Donation management(Debit orde import) section

	function ProcessImportDO(){
		$database = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		//echo "<pre>"; print_r($jinput); exit;
		$donor_id = $jinput->get('donor_id', '');
		$project_id = $jinput->get('project_id', '');
		$amount = $jinput->get('amount', '');
		$ref = $jinput->get('ref', ''); 
		
		$database->setQuery("SELECT cms_user_id FROM #__donorforce_donor WHERE donor_id=".$donor_id);
		$cms_result = $database->loadObjectList();

		if( !empty($donor_id) && !empty($project_id)){
			$insert_query="
			INSERT INTO #__donorforce_history (`donor_id`, `project_id`, `cms_user_id`, `date`, `amount`, `status`, `donation_type`, `Reference`) VALUES ( '$donor_id', '$project_id', '$cms_result', NOW() , '$amount', 'successful', 'onceoff', '$ref' )";
			$database->setQuery($insert_query);
			$result['insert'] = $database->execute();
			$insertID = $database->insertid();
			ob_start();
			$this->sendEmail($insertID);
			$result['email'] = ob_get_contents();
      		ob_end_clean();
			
			echo json_encode($result); 
		}
		else{  echo "ERROR ";  }	
			
	}
	
	function split_donor_history(){
			$donaiton_history_id =  JRequest::getVar('donaiton_history_id',0);	 
			if($donaiton_history_id > 0){
				$db = JFactory::getDBO();
				$db->getQuery(true);
				$query = $db->getQuery(true);
				$query->select('dh.*,d.name_title,d.name_first,d.name_last,p.name as project_name');
				$query->from('#__donorforce_history AS dh');
				$query->join('LEFT', '#__donorforce_donor AS d ON d.donor_id=dh.donor_id');
				$query->join('LEFT', '#__donorforce_project AS p ON p.project_id=dh.project_id');
		 		$query->where('dh.donor_history_id = ' . (int) $donaiton_history_id);
				$db->setQuery($query);
			  $donation_data = $db->loadAssoc();
				
				//echo "<pre> return_data "; print_r( $return_data  ); echo "</pre>";  
				
				$return_html = '<div class="split_cont">';
				 $return_html .= JHtml::_('jquery.framework');
				$return_html .='<div> <p> Donation Details </p> '; 
					$return_html .='<br/><span> Date = '.$donation_data['date'].'</span>';
					$return_html .='<br/><span> Total Amount = '.$donation_data['amount'].'</span>';
					$return_html .='<br/><span> Reference = '.$donation_data['Reference'].'</span>';	
				$return_html .='</div>'; 
				$return_html .= '</div>'; 
				
				$return_html .= '<script>
				 
				
				function addRow(){ console.log(" hellow add another row "); 
					
					
					document.getElementById("row_list").innerHTML += document.getElementById("add_row_sample").innerHTML ;
					 
						
				}
				'; 
				$return_html .= '</script>'; 
				
				
				$Return_DonorsList = $this->Return_DonorsList(); 
				$Return_ProjectsList = $this->Return_ProjectsList();
				
				$return_html .= '<div id="add_row_sample" style="display:none;"><div class="row">'; 
							$return_html .= $Return_DonorsList;
							$return_html .= $Return_ProjectsList;
				$return_html .= '</div><hr/></div>'; 
				
				
				$return_html .= '<hr /> <form id="split_form" action="index.php?option=com_donorforce&task=ajax.split_test&format=raw" method="post">'; 
				
				$return_html .='<button onclick=" addRow();return false; ">Add Row </button>';
				
				
				$return_html .= '<div id="row_list"><div class="row">'; 
							$return_html .= $Return_DonorsList;
							$return_html .= $Return_ProjectsList;
				$return_html .= '</div><hr /></div>'; 
						
				$return_html .= ' <input type="submit"  value="submit form test" /> </form>'; 
				
				
				
				echo  $return_html;
					
			}
	}
	
	function split_test(){
		$db = JFactory::getDbo();	
		
		//echo "<pre> REQUEST "; print_r( $_REQUEST  ); echo "</pre>";    
		$postData = JRequest::get();
		$history_id = $postData['history_id'];
		$donation_history_q = "SELECT * FROM #__donorforce_history WHERE `donor_history_id`=".$history_id;
		$db->setQuery($donation_history_q);
		$donation_history = $db->loadAssoc();
		//echo " <pre>   donation_history = ";  print_r( $donation_history ); echo " </pre> ";   	
	 
		for($i=0; $i<count($postData['jform']['amount']); $i++ ){
							 
		$query = $db->getQuery(true);
		$columns = array('donor_id', 'project_id', 'cms_user_id', 'date','amount','status','donation_type','Reference');
		$values = array();
		
		$donor_query = "SELECT cms_user_id 
						FROM #__donorforce_donor WHERE `donor_id` =".$postData['jform']['donor_id'][$i];
		$db->setQuery($donor_query);
		//echo " <pre>  donor_query = ";  print_r( $donor_query ); echo " </pre> ";   
			
		$donor_data = $db->loadObject();
		$cms_user_id = $donor_data->cms_user_id;
		$values = array(
					$db->quote($postData['jform']['donor_id'][$i]),
					$db->quote($postData['jform']['project_id'][$i]),
					$db->quote($cms_user_id),
					$db->quote($donation_history['date']),
					((int)$postData['jform']['amount'][$i]),
					$db->quote($postData['jform']['status'][$i]),
					$db->quote($donation_history['donation_type']),
					$db->quote($donation_history['Reference'])
					);	
		$query->insert($db->quoteName('#__donorforce_history'));
		$query->columns($db->quoteName($columns));
		$query->values(implode(',',$values));
		//echo " <pre> query->dump   ";  print_r( $query->dump() ); echo " </pre> ";   exit; 
		$db->setQuery($query);
		$db->execute(); 
		$insertID[] = $db->insertid();
			
		}
		
		 	$history_delete = "DELETE FROM #__donorforce_history WHERE `donor_history_id`=".$history_id;
			$db->setQuery($history_delete);
			$db->execute();	
			
			$app = JFactory::getApplication();
			$message = JText::sprintf('JERROR_SAVE_FAILED');
			
			
			
	$app->redirect(JRoute::_('index.php?option=com_donorforce&view=management&layout=split_complete&tmpl=component&sid%5B%5D='.
		implode('&sid%5B%5D=',$insertID), false), $message, 'error'		
	);
	 
			 
			/*if(!empty($insertID)){
				
				$inserted_Data = "			
				SELECT
				#__donorforce_project.`name` AS project_name,
				#__donorforce_history.*,
				#__donorforce_donor.name_first,
				#__donorforce_donor.name_last
				
				FROM
					#__donorforce_history
				INNER JOIN #__donorforce_project ON #__donorforce_history.project_id = #__donorforce_project.project_id
				INNER JOIN #__donorforce_donor ON #__donorforce_donor.donor_id = #__donorforce_history.donor_id
				WHERE
					#__donorforce_history.donor_history_id IN (".implode(',',$insertID).")";
					
				$db->setQuery($inserted_Data);
				$insertedRecords = $db->loadAssocList();
				//echo " <pre>  inserted_Data  ";  print_r( $inserted_Data ); echo " </pre> ";   	
				//echo " <pre>  insertedRecords  ";  print_r( $insertedRecords ); echo " </pre> ";
				   
				
				echo '<h3>Splitting Done succesfully</h3>';
				echo '<p>New Donation Created</p>
					<thead>
					  <tr>
					  <th>ID</th>
					  <th>Date</th>
					  <th>Reference</th>
					  <th>Project</th>
					  <th>Donor</th>
					  <th>Amount</th>
					  <th>Donation Status</th></tr>
					</thead><tbody>';
				
				foreach($insertedRecords as $insertedRecord){
					echo '<tr><td>'.$insertedRecord['donor_history_id'].'</td>';
					echo '<td>'.$insertedRecord['date'].'</td>';
					echo '<td>'.$insertedRecord['Reference'].'</td>';
					echo '<td>'.$insertedRecord['project_name'].'</td>';
					echo '<td>'.$insertedRecord['name_first'].' '.$insertedRecord['name_last'].'</td>';
					echo '<td>'.$insertedRecord['amount'].'</td>';
					echo '<td>'.$insertedRecord['status'].'</td>';
					echo '</tr>'; 
				}
				echo '</tbody></table>';  
			}*/
		
						
	}
	
	
	function sendEmail($hid=''){
		$id = ($hid != '')? $hid : JRequest::getVar('hid');
		if($id != ''):
			$adddonation_model = JModelLegacy::getInstance('Adddonation', 'DonorforceModel'); 
			$result = $adddonation_model->sendemail($id);
			return $result; 			
		endif; 		
	}
	
	function AddNotes(){
		$db = JFactory::getDbo();
		//echo "<pre>"; print_r($_REQUEST); exit;
		if(isset($_POST)){
			$query = $db->getQuery(true);
			$columns = array(
				'donor_id',
				'title',
				'notes'
			);
			$values = array(
				$db->quote($_GET['donor_id']),
				$db->quote($_POST['TitleModal']),
				$db->quote($_POST['NotesModal'])
			);
			$query->insert($db->quoteName('#__donorforce_donornotes')); 
			$query->columns($columns);
			$query->values(implode(',', $values));
			$db->setQuery($query);
			$insert_status = $db->execute();
			if($insert_status){
				$insertid = $db->insertid();
				$query = $db->getQuery(true);
				$query = "SELECT * FROM #__donorforce_donornotes WHERE `id` =".(int) $insertid;
				$db->setQuery($query);
				$row = $db->loadObject();
				?> 
				<tr class="" id="noteRow-<?php echo $insertid ?>">
					<td>
						<a href="javascript:void(0)" class="editRow" data-pkey="<?php echo $insertid ?>" data-toggle="modal" data-target="#Edit_newRow"><span class="icon-edit"></span></a>
						<a href="javascript:void(0)" class="delete-note" data-pkey="<?php echo $insertid ?>"><span class="icon-delete"></span></a>
					</td>
					<td>
						<?php echo $_POST['TitleModal'] ?>
					</td>
					<td>
						<?php echo $_POST['NotesModal'] ?>
					</td>
                      <td>
                          <?php echo $row->date ?>
                      </td>
                      <td>
                          
                      </td>
				</tr>
				<?php
			}else{
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo "error";	
			}
			
		}
	}

	function getNoteData(){
		$db = JFactory::getDbo();
		//echo "<pre>"; print_r($_REQUEST); exit;
		if(isset($_POST)){
			if(isset($_POST['type']) && $_POST['type'] == 1){
				$donor_id = $_POST['donor_id'];
				$query = $db->getQuery(true);
				$query = "SELECT note_title, notes FROM #__donorforce_donor WHERE `donor_id`=".$donor_id;
				$db->setQuery($query);
				$noteData = $db->loadObject();
				if($noteData){
					echo json_encode($noteData);
				}else{
					header('HTTP/1.0 400 Bad Request', true, 400);
					echo "error";	
				}
			}
			else{
				$donor_id = $_POST['donor_id'];
				$noteID = $_POST['noteID'];
				$query = $db->getQuery(true);
				$query = "SELECT * FROM #__donorforce_donornotes WHERE `id`=".$noteID;
				$db->setQuery($query);
				$noteData = $db->loadObject();
				if($noteData){
					echo json_encode($noteData);
				}else{
					header('HTTP/1.0 400 Bad Request', true, 400);
					echo "error";	
				}
			}
		}
	}

	function editNotes(){
		$db = JFactory::getDbo();
		//echo "<pre>"; print_r($_REQUEST); exit;
		if(isset($_POST)){
			$note_id = $_GET['note_id'];
			$donor_id = $_GET['donor_id'];
			$query = $db->getQuery(true);
			// Fields to update.
			if($note_id == 'donor'){
				$fields = array(
					$db->quoteName('note_title') . ' = "' . $_POST['TitleModal'].'"',
					$db->quoteName('notes') . ' = "'.$_POST['NotesModal'].'"'
				);
	
				// Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('donor_id') . ' = '.$donor_id
				);
				$query->update($db->quoteName('#__donorforce_donor')); 
				$query->set($fields);
				$query->where($conditions);
				$db->setQuery($query);
				$insert_status = $db->execute();
				if($insert_status){
					?>
					<td>
						<a href="javascript:void(0)" class="editRow donor" data-pkey="<?php echo $donor_id ?>" data-toggle="modal" data-target="#Edit_newRow"><span class="icon-edit"></span></a>
					</td>
					<td>
						<?php echo $_POST['TitleModal'] ?>
					</td>
					<td>
						<?php echo $_POST['NotesModal'] ?>
					</td>
                      <td>
                          
                      </td>
                      <td>
                         
                      </td>
					<?php
				}else{
					header('HTTP/1.0 400 Bad Request', true, 400);
					echo "error";	
				}
			}
			else{
				$fields = array(
					$db->quoteName('title') . ' = "' . $_POST['TitleModal'].'"',
					$db->quoteName('notes') . ' = "'.$_POST['NotesModal'].'"',
					$db->quoteName('date_modified') . ' = "'.date("Y-m-d H:i:s").'"'
				);
	
				// Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('id') . ' = '.$note_id
				);
				$query->update($db->quoteName('#__donorforce_donornotes')); 
				$query->set($fields);
				$query->where($conditions);
				$db->setQuery($query);
				$insert_status = $db->execute();
				if($insert_status){
					$query = $db->getQuery(true);
                  	$query = "SELECT * FROM #__donorforce_donornotes WHERE `id` =".(int) $note_id;
                  	$db->setQuery($query);
                  	$row = $db->loadObject();
					?>
					<td>
						<a href="javascript:void(0)" class="editRow" data-pkey="<?php echo $note_id ?>" data-toggle="modal" data-target="#Edit_newRow"><span class="icon-edit"></span></a>
						<a href="javascript:void(0)" class="delete-note" data-pkey="<?php echo $note_id ?>"><span class="icon-delete"></span></a>
					</td>
					<td>
						<?php echo $_POST['TitleModal'] ?>
					</td>
					<td>
						<?php echo $_POST['NotesModal'] ?>
					</td>
                      <td>
                          <?php echo $row->date ?>
                      </td>
                      <td>
                          <?php echo $row->date_modified ?>
                      </td>
					<?php
				}else{
					header('HTTP/1.0 400 Bad Request', true, 400);
					echo "error";	
				}
			}
		}
	}
	
	public function deleteNote(){
		$db = JFactory::getDbo();
		$note_id = $_GET['note_id'];
		$donor_id = $_GET['donor_id'];
		if($note_id){
			//echo json_encode(DonorForceHelper::createReturnObject('success', JText::_('COM_IPROPERTY_SEASON_DELETED_SUCCESSFULLY')));
				//die();
			$query = 'DELETE FROM #__donorforce_donornotes WHERE `id` = '.$note_id.' AND `donor_id` = '.$donor_id;
			$db->setQuery($query);
			$result = $db->execute();
			if($result){
				echo json_encode($this->createReturnObject('success', 'Row Deleted Succesfully'));
				die();
			}else{
				echo json_encode($this->createReturnObject('error', 'Error Deleting Row'));
				die();	
			}
		}else{
			echo json_encode($this->createReturnObject('error', 'Row not selected'));
			die();
		}
	}

	public static function createReturnObject($status, $message, $data = false)
	{
		$return = new StdClass();
		$return->status = $status;
		$return->message = $message;
		$return->data = $data;
		return $return;
	}

	public function GetJoomlaUser(){
		$db = JFactory::getDbo();
		$user_id = $_POST['id'];
		if(isset($user_id) && $user_id > 0){
			$query = $db->getQuery(true);
			$query = "SELECT `name`,`email`,`username` FROM #__users WHERE `id`=".$user_id;
			$db->setQuery($query);
			$noteData = $db->loadObject();
			if($noteData){
				echo json_encode($noteData);
			}else{
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo "error";	
			}
		}
	}
}