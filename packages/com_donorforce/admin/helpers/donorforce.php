<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


defined('_JEXEC') or die;


abstract class DonorforceHelper
{
	
	public static function addSubmenu($submenu) 
		{
			JSubMenuHelper::addEntry(
				JText::_('cPanel'), 
				'index.php?option=com_donorforce&view=donorforce', 
				$submenu == 'cpanel'
			);
			
			JSubMenuHelper::addEntry(
				JText::_('Donors'), 
				'index.php?option=com_donorforce&view=donors', 
				$submenu == 'donors'
			);


			JSubMenuHelper::addEntry(
				JText::_('Project Categories'), 
				'index.php?option=com_donorforce&view=projectcategories', 
				$submenu == 'projectcategories'
			);
			
			JSubMenuHelper::addEntry(
				JText::_('Projects'), 
				'index.php?option=com_donorforce&view=projects', 
				$submenu == 'projects'
			);
			
			
			JSubMenuHelper::addEntry(
				JText::_('Debit Order Forms'), 
				'index.php?option=com_donorforce&view=donations', 
				$submenu == 'donations'
			);
			
			JSubMenuHelper::addEntry(
				JText::_('Bequest Management'), 
				'index.php?option=com_donorforce&view=bequests', 
				$submenu == 'bequests'
			);
			
			JSubMenuHelper::addEntry(
				JText::_('Donation Subscriptions'), 
				'index.php?option=com_donorforce&view=subscriptions', 
				$submenu == 'subscriptions'
			);
			
			JSubMenuHelper::addEntry(
				JText::_('Add Once-Off Donation'), 
				'index.php?option=com_donorforce&view=adddonation', 
				$submenu == 'adddonation'
			);
			
			JSubMenuHelper::addEntry(
				JText::_('Add Gifts in Kind'), 
				'index.php?option=com_donorforce&view=addgift', 
				$submenu == 'addgift'
			);
			
			
			JSubMenuHelper::addEntry(
				JText::_('Templates'), 
				'index.php?option=com_donorforce&view=template&layout=template_genrate', 
				$submenu == 'Templates'
			);
			
			JSubMenuHelper::addEntry(
				JText::_('Reports'), 
				'index.php?option=com_donorforce&view=reports', 
				$submenu == 'reports'
			);
			
			
			JSubMenuHelper::addEntry(
				JText::_('Donation Management'), 
				'index.php?option=com_donorforce&view=management', 
				$submenu == 'management'
			);
			
			
		}
		
	
	/*
	
	public function sendEmailToDonor($reportId)
	{		
		$db = JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query = "
				SELECT
					#__users.email,
					#__ts_reports.report_id,
					#__ts_reports.main_supplier,
					#__ts_reports.date_of_visit,
					#__ts_reports.sub_supplier
				FROM
					#__ts_reports
				INNER JOIN #__ts_donations ON #__ts_reports.donation_id    = #__ts_donations.donation_id
				INNER JOIN #__ts_donors ON #__ts_donations.donor_id = #__ts_donors.id
				INNER JOIN #__users ON #__ts_donors.cms_user_id  = #__users.id
				WHERE
					report_id =".$reportId;
		$db->setQuery($query);
		$db->query();
		$file = $db->loadObject();		
		//Now send Email to the donor		
		$toEmail=$file->email;
		$email =& JFactory::getMailer();
		$email->addRecipient($toEmail);
		$email->setSubject('Report has been published for you   ');
		$content  = "Following report has published for  you, Please login to view details<br>";
		$content .= "<b> Report Number : </b>".$file->report_id."<br>";
		$content .= "<b> Main Supplier : </b>".$file->main_supplier."<br>";
		$content .= "<b> Sub Supplier  : </b>".$file->sub_supplier."<br>";
		$content .= "<b> Date of visit : </b>".$file->date_of_visit."<br>";		
		$email->setBody($content);
		$email->IsHTML(true);
		if($toEmail != '' )
		{
			$email->Send();
		}		
	}*/	
public static function getCurrency()
{
	$componentParams = JComponentHelper::getParams('com_donorforce');
	$param = $componentParams->get('addcurrency');	
	return $param;
}

public static function displayAmount($amount)
{
				
				$pattern = '~^-?[0-9]+(\.[0-9]+)$~xD';
					
				if(preg_match($pattern,$amount))
				{
					//do something 
				} else {
					
					$afterdecimal = substr($amount, -2);
					
					$beforedecimal = substr($amount, 0, -2);
					
					$amount = $beforedecimal.'.'.$afterdecimal;
					
				}
				
		return $amount;
	}
	
	
public static function getPdfTemplate()
{
		$db = JFactory::getDbo();
		$query = "select * from #__donorforce_invoice_temp LIMIT 1";
		$db->setQuery($query);
		$result= $db->loadObject();
		return $result;
}	


public static function getLatetsDonation($history_id)
	{
		$db = JFactory::getDbo();
		$user =JFactory::getUser();
		$query = "
					SELECT dh.*, 
					p.`name` as project_name,
					p.`description` as project_description, 
					d.*, u.email,p.`description` As project_description
					FROM #__donorforce_history AS dh 
					Right JOIN #__donorforce_project AS p ON dh.project_id = p.project_id 
					RIGHT JOIN #__donorforce_donor AS d ON d.donor_id = dh.donor_id
					RIGHT JOIN #__users AS u ON u.id = d.cms_user_id
					WHERE
						dh.donor_history_id = ".$history_id;
				
		$db->setQuery($query);
		$result= $db->loadObject();		
		return $result;
	}
	
	public static function getLatetsGift($gift_id)
	{
		$db = JFactory::getDbo();
		$user =JFactory::getUser();
		$query = "
					SELECT gf.gift_id,gf.date,gf.status as gift_status,gf.desc,					
					 p.`name` , d.*, u.email
					FROM #__donorforce_gift AS gf 
					RIGHT JOIN #__donorforce_project AS p ON p.project_id = gf.project_id 
					RIGHT JOIN #__donorforce_donor AS d ON d.donor_id = gf.donor_id
					RIGHT JOIN #__users AS u ON u.id = d.cms_user_id
					WHERE
						gf.gift_id = ".$gift_id;
				
		$db->setQuery($query);
		$result= $db->loadObject();		
		return $result;
	}
	
	
	
	function getParams(){
		$params = JComponentHelper::getParams('com_donorforce');
		return $params;
	}

	
	
	
	function sendTaxReceiptPDF($don){
				
			//echo "<br /> sendTaxReceiptPDF = "; 
			//echo "<pre> don "; print_r( $don  ); echo "</pre>"; //exit; 
			$pdf_template = DonorforceHelper::getPdfTemplate();
			//echo "<pre> pdf_template "; print_r( $pdf_template  ); echo "</pre>";  
			
			
			/*-- Tax PDF template --*/

			
			$Taxhtml2 = '<html style=""><body style="" >
			
				<style>
					.tax_pdf * {
						/*	border : 1px solid black; */
					}
			
					.tax_header{ 
						border-bottom: 1px solid black;
						display: table; 
						width:100%; 
						/* margin: 10px 0px; */
					}
					.header_row{
						display: table-row;
					}
					.temp_logo{ 
						float: left;
						display: table-cell;
						width: 25%;
						text-align: center;
					}
					.temp_logo img{ 
						width: 150px; 
						margin-top: 20px;
					}
					.temp_logo span{
						display: block;
						margin-top: 11px;
					}
					.address_div{
						display: table-cell;
						width: 40%;
						float: left;
					}
					.address_div h4{
						margin-top: 0px;
					}
					.address1{    
						width: 50%;
						float: left;
						display: inline-block;
						padding-left: 25px;
					}
					.address2{
						width: 50%;
						float: left;
						display: inline-block;
						padding-left: 10px;
					}
					.address1 p, .address2 p {
						margin-bottom: 4px;
						margin-top: 0px;
						padding:0px;
					}
					.receipt{
						display: table-cell;
						width: 35%;
						float: left;
					}
					.receipt div{
						/* margin-top: -5px; */
					}
					.receipt .receipt_label {
						text-align: right;
						width: 50%;
						float: left;
						display: inline-block;
					}
					.receipt .receipt_value {
						width: 50%;
						float: left;
						padding-left: 5px;
						margin-right: -5px;
					}
					.header_empty {
						/* border: 1px solid black; */
						height:8px;
						border-right: 0px;
						border-left: 0px;
					}
					.donation_recpt{ /* border: 1px solid black; */}
					.donation_recpt h2 {
						margin: 0px;
						padding: 5px;
					}
					.donation_recpt p{ line-height: 30px;}
					.tax_intent{ /*  margin: 0px -10px; */}
					.tax_intent p{line-height: 30px;}
					.recpt_no {
						/* border-top: 1px solid black; */
						/* border-bottom: 1px solid black; */
						display: inline-block;
						width: 100%;
					}
					.recpt_no span{    
						border-left: 1px solid black;
						display: inline-block;
						padding: 5px;
						min-width: 100px;
						text-align: left; 
					}
					.tax_body{    
						/* border-top: 1px solid black; */
						border-bottom: 1px solid black;
						/* margin: 10px -10px; */
						padding: 5px 10px;
					}
					.tax_body p {
						margin: 0px !important;
					}		
					.tax_intent {
						border-bottom: 1px solid black;
					}
					.empty_no{
						width: 60%;
						display: inline-block;
						padding: 5px;
					}
					.chairman_image {
						/* border-bottom: 1px solid black; */
						display: block;	
						/* margin: 0 -10px; */
						padding: 10px;
					}
					.chairman{  
						/* border-top: 1px solid black; */
						border-bottom: 1px solid black;
						display: block;	
						padding: 10px;	
					}
					.tax_footer{ 
						display: table;
						width: 100%;
						/* border: 1px solid #000;
						border-top: none; */
						padding: 10px;
					}			
					.footer_row {display: table-row; width: 100%;}		
					.footer_row span { display: table-cell;}		
					.footer_row span.last{
						text-align: right;
						padding-right: 5px;
					}
					img{margin:4px;}';
					$Taxhtml2 .= (!empty($pdf_template->custom_style2))? $pdf_template->custom_style2 : ''; 
					$Taxhtml2 .='
				</style>
			
				<div class="tax_pdf">
					<div class="tax_cont">
						<div class="tax_header">
							<div class="header_row">
								<div class="temp_logo">';                 
									if(!empty($pdf_template->head_logo2)) { 
										$Taxhtml2 .= '<img src="'.JURI::root().$pdf_template->head_logo2 .'"/>';						
									}else{
										//$Taxhtml2 .= 'Logo will be  Here'; 
									}
									if(!empty($pdf_template->org_name)){
										$org_name = $pdf_template->org_name;
									}
									else{
										$org_name = 'Organization Name';
									}
									$Taxhtml2 .='
									<span><b>'.$pdf_template->pobox.'</b></span>
								</div>
								<div class="address_div">
									<h1 style="text-align: center;">'.$org_name.'</h1>
									<div class="address1">
										<h4>Physical Address</h4>';        		 
										if(!empty($pdf_template->physical_address)) 
										{
											$Taxhtml2 .= $pdf_template->physical_address;
										}
										else
										$Taxhtml2 .='Physical Addresses will be Here';
										$Taxhtml2 .='          
									</div>
									<div class="address2">
										<h4>Postal Address</h4>'; 					 
										if(!empty($pdf_template->postal_address)) 
										{
											$Taxhtml2 .= $pdf_template->postal_address;
										}
										else $Taxhtml2 .='Postal Addresses will be Here';
										$Taxhtml2 .='        
									</div>   
								</div>
								<div class="receipt">
									<h1 style="width: 50%; margin-left: 50%; padding-left: 5px; margin-right: -5px;">Receipt</h1>
									<div><span class="receipt_label">Receipt:</span> <span class="receipt_value">'.$don->Reference.'</span></div>
									<div><span class="receipt_label">Date:</span> <span class="receipt_value">'.date('d/m/Y').'</span></div>
									<div><span class="receipt_label">VAT Number:</span> <span class="receipt_value">'.$don->vat_number.'</span></div>
									<div><span class="receipt_label">Company Name:</span> <span class="receipt_value">'.$don->org_name.'</span></div>
									<div><span class="receipt_label">Address:</span> <span class="receipt_value" style="display: inline-block;">{address_1} <br> {address_2} <br> {city} <br> {country}</span></div>
								</div> 
							</div>
						</div><!-- tax_header end -->
						<!-- 	<div class="header_empty"></div>
						<div class="donation_recpt">
							<h2>Donations Receipt</h2>
							<span>';
								
								if(!empty($pdf_template->receipt_text)) 
								{
									$Taxhtml2 .= $pdf_template->receipt_text;
								}
								else $Taxhtml2 .='Donation Receipt Text will be here';
								$Taxhtml2 .='		
							</span>
						</div>--><!-- donation_recpt end -->
						<!-- <div class="recpt_no">
							<span class="empty_no" style="padding:2px;"></span>
							<span style="width: 18%;display: inline-block; border-left:1px solid black; padding:2px;">Receipt No.</span>
							<span style="width: 18%;display: inline-block;border-left:1px solid black;padding:2px;">'; 
								$Taxhtml2 .= $don->Reference; 
								$Taxhtml2 .='</span>            
						</div> -->
						<div class="tax_body">';
						
						
								if(!empty($pdf_template->receipt_body)) 
							{
								$receipt_body =  str_replace('src="', 'src="'.JURI::root().'/', $pdf_template->receipt_body);
								$Taxhtml2 .= $receipt_body;
							}
							else $Taxhtml2 .='Receipt Body Text will be here';							          
							$Taxhtml2 .='
						</div>
						<div class="tax_intent">';
							if(!empty($pdf_template->statement_intent)) 
							{
								$Taxhtml2 .= $pdf_template->statement_intent;
							}
							else $Taxhtml2 .=' Statement Intent Text will be here';							          
							$Taxhtml2 .='
						</div><!-- tax_intent end -->
						<div class="chairman_image">';         
							if(!empty($pdf_template->chairman_image)){
								$Taxhtml2 .='<span style="display:inline-block;width:500px;"><img src="'.JURI::root().$pdf_template->chairman_image.'" /></span>';
							}
							$Taxhtml2 .='
						</div>
						<div class="chairman">
							<span style="display:inline-block;width:500px;">'; 
							$Taxhtml2 .= (!empty($pdf_template->chairman_title))? $pdf_template->chairman_title : ''; 
							$Taxhtml2 .='
							</span>
						</div> 
					</div><!-- tax_cont end -->
					
					
					<div class="tax_footer">
						<div class="footer_row">
							<span>';
								$Taxhtml2 .= (!empty($pdf_template->footer1))? $pdf_template->footer1 : '' ; 
								$Taxhtml2 .='
							</span>  				
							<span style="text-align: center;">'; 
								$Taxhtml2 .=  (!empty($pdf_template->footer2))? $pdf_template->footer2 : '';
								$Taxhtml2 .='
							</span>  				
							<span class="last">'; 
								$Taxhtml2 .= (!empty($pdf_template->footer3))? $pdf_template->footer3 : ''; 
								$Taxhtml2 .='
							</span>         
						</div>
					
						<div class="footer_row">
							<span>'; 
								$Taxhtml2 .= (!empty($pdf_template->footer4))? $pdf_template->footer4 : ''; 
								$Taxhtml2 .='
							</span>  
							<span style="text-align: center;">'; 
								$Taxhtml2 .= (!empty($pdf_template->footer5))? $pdf_template->footer5 : ''; 
								$Taxhtml2 .='
							</span>  
							<span class="last">'; 
								$Taxhtml2 .=(!empty($pdf_template->footer6))? $pdf_template->footer6 : ''; 
								$Taxhtml2 .='
							</span>         
						</div>
					</div>
				</div>
			</body></html>'; 
		
		
			/*-- Change shortcode to dynamic data --*/
			//echo "<pre> don = "; print_r( $don ); echo "</pre>";  
			$donor_name  = $don->name_first. ' '.$don->name_last;
			$donor_address =  '
									<span style="display:block;">'.$don->phy_address.'</span>
					<span style="display:block;">'.$don->phy_city.' '.$don->phy_state.'</span>
					<span style="display:block;">'.$don->phy_zip.' '.$don->phy_country.' </span>';
			$Taxhtml2 .='<span style="display:block;">123'.$don->donation_type.' 123</span>  ';
			$Taxhtml2 = str_replace('{donation_reference}',$don->Reference, $Taxhtml2);
			$Taxhtml2 = str_replace('{donor_name}', $donor_name, $Taxhtml2);
			$Taxhtml2 = str_replace('{donor_address}', $donor_address, $Taxhtml2);
			$Taxhtml2 = str_replace('{donation_date}', $don->date, $Taxhtml2);
			$amount  =  DonorforceHelper::getCurrency(). " ".$don->amount;
			$Taxhtml2 = str_replace('{donation_amount}',$amount, $Taxhtml2);
			$Taxhtml2 = str_replace('{project_description}',$don->project_description, $Taxhtml2);
			$Taxhtml2 = str_replace('{donation_type}',$don->donation_type, $Taxhtml2);
			$Taxhtml2 = str_replace('{project_name}',$don->project_name, $Taxhtml2);
			$Taxhtml2 = str_replace('{donor_number}','D'.str_pad($don->donor_id, 5, '0', STR_PAD_LEFT), $Taxhtml2);
			$Taxhtml2 = str_replace('{project_number}','P'.str_pad($don->project_id, 5, '0', STR_PAD_LEFT), $Taxhtml2);
			$Taxhtml2 = str_replace('{address_1}',$don->phy_address, $Taxhtml2);
			$Taxhtml2 = str_replace('{address_2}',$don->phy_address2, $Taxhtml2);
			$Taxhtml2 = str_replace('{city}',$don->phy_city, $Taxhtml2);
			$Taxhtml2 = str_replace('{country}',$don->phy_country, $Taxhtml2);
			
			//echo $Taxhtml2; exit;
			
			require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';	
			$dompdf_tax = new DOMPDF();
			$dompdf_tax->load_html($Taxhtml2);
			$dompdf_tax->render();
			$output_tax = $dompdf_tax->output();
			file_put_contents(JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf", $output_tax); 
		
		
//return $Taxhtml2;		


}




	
	function sendThankYouPDF($don){ 
	
	//echo "<pre>sendThankYouPDF "; print_r( $don   ); echo "</pre>";  exit; 
	
			$pdf_template = DonorforceHelper::getPdfTemplate();
			
			$html = '<html><style>'; 
				if(!empty($item->custom_style)){
					$html .= $item->custom_style; 	
				}
				$html .='</style><body>';
				
		
		$html.='<table width="100%">
			<tr>
				<td  valign="top">';			
				if(!empty($pdf_template->head_logo)) 
				{
					$html.='<img style="min-height:50px;" src="'.JPATH_ROOT.'/'.$pdf_template->head_logo .'" name="" />';
				}
				else
				{	$html.="Logo";}
			$html.='
			</td>
			<td align="right">';						
			 if(!empty($pdf_template->head_addresses)) 
			 {
				$html.= $pdf_template->head_addresses;
			 }
			 else
				 {  $html.= "Addresses will be Here";}		 
			$html .= '</td> </tr>';
			
			$html .='<tr>
			<td>&nbsp;</td>
				<td align="right"> <h3>';
					if(!empty($pdf_template->main_title)) 
					 {
						$html.= $pdf_template->main_title;
					 }
					 else
						 {  $html.= "Thank You";}
			$html .='</h3>
				<h4>&nbsp;&nbsp;';
			  $html.= "Receipt No:";
			$html .=(isset($don->Reference))?($don->Reference):'';
			
			$html .='</h4>
			</td>
			</tr>';
		
		$html.='<tr><td valign="top">';
		 $country = '';		 
		if($don->phy_country == 'ZA')
		{
			$country = 'South Africa';
		} else {
			 $country = $don->phy_country;	
		}
		 
		 $html.= date('F j, Y').'<br>';
		 $html.= '<strong>'.$don->name_first.' '.$don->name_last.'</strong><br>';
		 $html.= $don->phy_address.'<br>'.$don->phy_city.'<br>'.$don->phy_state.'<br>'.$don->phy_zip.'<br>'.$country;
		
			$html .= '</td>
		<td>&nbsp;</td>
		</tr>';
		
		$html .= '
		<tr>
		<td colspan="2" align="left" style="border-top:1px solid #999">
		Dear '.$don->name_first.' '.$don->name_last.'<br><br>
		Thank you for the donation of '.DonorForceHelper::getCurrency().' '.($don->amount).' to our project '.$don->name.' Below are the details of your donation.
		</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		
		<td>'; 
		$html .='</td>
		<td>&nbsp;</td>
		</tr>
		';
		
		// Body Content will go here .  
		if(!empty($item->thankyou_body))
		{
			$html .=$item->thankyou_body; 	
		}
		// Body Content end here . 
		
		$html .='<tr>
		<td colspan="2">';
		if(!empty($pdf_template->bottom_body_txt)) 
		 {
			 $html.=$pdf_template->bottom_body_txt;
		 }
		 else
		 $html.= "Bottom Body Text will be Here";
		
		 $html.='</td>
		
		</tr>
		
		
		<tr>
		<td>';
		if(!empty($pdf_template->footer_slogan)) 
		 {
		 $html.= $pdf_template->footer_slogan;
		 }
		 else
		 $html.= "Footer Slogan Text will be Here";
		 $html.='</td>
		<td align="right" valign="middle">';
		if(!empty($pdf_template->footer_addresses)) 
		 {
			 $html.= $pdf_template->footer_addresses;
		 }
		 else
		 $html.= "Footer Addresses Text will be Here";
		
		 $html.='</td>
		</tr>';
		
		$html.='</table></body></html>';
		
		
		
/*-- Change shortcode to dynamic data --*/
$donor_name  = $don->name_first. ' '.$don->name_last; 
$donor_address =  '
						<span style="display:block;">'.$don->phy_address.'</span>
						<span style="display:block;">'.$don->phy_city.' '.$don->phy_state.'</span>
						<span style="display:block;">'.$don->phy_zip.' '.$don->phy_country.' </span>  ';		
$html = str_replace('{donation_reference}',$don->Reference, $html);
$html = str_replace('{donor_name}', $donor_name, $html);		
$html = str_replace('{donor_address}', $donor_address, $html);		
$html = str_replace('{donation_date}', $don->date, $html);	
$amount  =  DonorforceHelper::getCurrency(). " ".$don->amount;	
$html = str_replace('{donation_amount}',$amount, $html);			
$html = str_replace('{project_description}',$don->project_description, $html);		
$html = str_replace('{donation_type}',$don->donation_type, $html);
$html = str_replace('{project_name}',$don->project_name, $html);


		
		
		require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';	
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$output = $dompdf->output();
		$dompdf->set_option('enable_remote', TRUE);
		file_put_contents(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf", $output);
	}
	
	


	public static function Return_ProjectsList( $project_no = '' ){
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
		$return_html .= '<div class="project_list">
		<label class="labl_select_project"> Select Project </label>
		<select   class="select_project"  name="jform[project_id][]" class="inputbox" size="1" aria-invalid="false">'; 
		
			foreach($items as $item){
				if( $project_no == $item->project_id ){ 
					$return_html .= '<option value="'.$item->project_id.'" selected="selected"> '.$item->name.' </option>'; 
				}else{ 
					$return_html .= '<option value="'.$item->project_id.'"> '.$item->name.' </option>';
				}
			}
	 $return_html .='</select></div>'; 
		 return $return_html   ; 
	}
	
	
	public static function Return_DonorsList( $donor_no = '' ){
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
		
		$return_html .= '<div class="donor_list"> 
			<label class="labl_select_donor"> Select Donor </label>
			<select   class="select_donor"  name="jform[donor_id][]" class="inputbox chosen-select" size="1" aria-invalid="false">';
		
			foreach($items as $item){ 
				if($item->donor_id == $donor_no ){
					$return_html .= '<option value="'.$item->donor_id.'" selected="selected"> '.$item->name_first." ".$item->name_last.' </option>';
				}else{
					$return_html .= '<option value="'.$item->donor_id.'"> '.$item->name_first." ".$item->name_last.' </option>';
				}
			}
		$return_html .='</select></div>';
		 return $return_html; 
		}
	
	
public static function getLastDonationCSVImported($csv_type)
	{
		$db = JFactory::getDbo();
		$user =JFactory::getUser();
		$query = "
					SELECT dh.*, p.`name` , d.*,p.`description` As project_description
					FROM #__donorforce_history AS dh 
					LEFT JOIN #__donorforce_project AS p ON dh.project_id = p.project_id 
					LEFT JOIN #__donorforce_donor AS d ON d.donor_id = dh.donor_id
					WHERE
						dh.Reference  LIKE '%".$csv_type."%'						
					ORDER BY dh.date DESC
					LIMIT 1
					";
						
						
				
		$db->setQuery($query);
		
		
		$result= $db->loadObject();		 
		
		return $result;
	}

	//This function getDebitOrderCSVImported is for the Importation of Debit order CSV file
	public static function getDebitOrderCSVImported($csv_type)
	{
		$db = JFactory::getDbo();
		$user =JFactory::getUser();
		$query = "
					SELECT dh.*, p.`name` , d.*,p.`description` As project_description
					FROM #__donorforce_history AS dh 
					LEFT JOIN #__donorforce_project AS p ON dh.project_id = p.project_id 
					LEFT JOIN #__donorforce_donor AS d ON d.donor_id = dh.donor_id
					WHERE
						dh.Reference  LIKE '%".$csv_type."%'						
					ORDER BY dh.date DESC
					LIMIT 1
					";
						
						
				
		$db->setQuery($query);
		
		
		$result= $db->loadObject();		 
		
		return $result;
	}

	public static function ProcessImport($data){
		$db=JFactory::getDbo();
		 
		$donor_id = $data['donor_id'];
		$project_id = $data['project_id']; 
		$date = $data['date'];
		$amount = $data['amount'];
		$ref = $data['ref'];
	 
	 	$cms_query="SELECT cms_user_id FROM #__donorforce_donor WHERE donor_id=".$donor_id; 
		$db->setQuery($cms_query);
		$cms_result = $db->loadResult(); 
	  
		//echo "<pre> cms_result = "; print_r( $cms_result  ); echo "</pre>"; 
		    
		
		if( !empty($donor_id) && !empty($project_id)){
			$insert_query="
			INSERT INTO #__donorforce_history (`donor_id`, `project_id`, `cms_user_id`, `date`, `amount`, `status`, `donation_type`, `Reference`) VALUES ( '$donor_id', '$project_id', '$cms_result', '$date' , '$amount', 'successful', 'onceoff', '$ref' )";
			$db->setQuery($insert_query);
			$result = $db->execute();

			echo $result; 
		}
		else{  echo "ERROR ";  }			
	}

		//The function ProcessImportDO is for the processing of Debit Order data after the importation

	public static function ProcessImportDO($data){
		$db=JFactory::getDbo();
		 
		$donor_id = $data['donor_id'];
		$project_id = $data['project_id'];
		$fname = $data['name_first'];
		$lname = $data['name_last'];
		$bname = $data['bank_name'];
		$brname = $data['branch_name'];
		$brcode = $data['branch_code'];
		$acname = $data['account_name'];
		$acnumber = $data['account_number'];
		$amount = $data['amount'];
		$ref = $data['ref'];
	 
	 	$cms_query="SELECT cms_user_id FROM #__donorforce_donor WHERE donor_id=".$donor_id; 
		$db->setQuery($cms_query);
		$cms_result = $db->loadResult(); 
	  
		//echo "<pre> cms_result = "; print_r( $cms_result  ); echo "</pre>"; 
		    
		
		if( !empty($donor_id) && !empty($project_id)){
			$insert_query="
			INSERT INTO #__donorforce_history (`donor_id`, `project_id`, `cms_user_id`, `date`, `amount`, `status`, `donation_type`, `Reference`) VALUES ( '$donor_id', '$project_id', '$cms_result', 'NOW()' , '$amount', 'successful', 'onceoff', '$ref' )";
			$db->setQuery($insert_query);
			$result = $db->execute();

			echo $result; 
		}
		else{  echo "ERROR ";  }		  			
				
	}
	
	
	
}
?>