<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Position controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_churchadmin
 * @since       1.6
 */
class DonorforceControllerTemplate extends JControllerForm
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_DONORFORCE';

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user		= JFactory::getUser();
		$categoryId	= JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
		$allow		= null;

		if ($categoryId)
		{
			// If the category has been passed in the URL check it.
			$allow	= $user->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absence of better information, revert to the component permissions.
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
		$user		= JFactory::getUser();
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId)
		{
			//$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId)
		{
			// The category has been set. Check the category permissions.
			return $user->authorise('core.edit', $this->option . '.category.' . $categoryId);
		}
		else
		{
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}
	/*
	*   Mehtod get all data to save for design invoice
	*/
	
	public function saveDesign()
	{
			
		$data = $_REQUEST['jform']; 
		//	print_r($data['head_logo']); exit;
		$db = JFactory::getDbo();
		//echo "<pre> data = "; print_r( $data  ); echo "</pre>";  
		
		if($data['id']==0)	
		{
			
		$query="
			insert into #__donorforce_invoice_temp 
			    (head_logo,head_addresses,upper_body_sign,thankyou_body,bottom_body_txt,footer_slogan,footer_addresses,custom_style, org_name, head_logo2, postal_address, physical_address, pobox, receipt_text, receipt_body, statement_intent, chairman_title, chairman_image, footer1, footer2, footer3, footer4, footer5, footer6, custom_style2)
				values("
					.$db->quote($data['head_logo']).
					",".$db->quote($data['head_addresses']).
					",".$db->quote($data['upper_body_sign']).
					",".$db->quote($data['thankyou_body']).					
					",".$db->quote($data['bottom_body_txt']).
					",".$db->quote($data['footer_slogan']).
					",".$db->quote($data['footer_addresses']).
					",".$db->quote($data['custom_style']).
					",".$db->quote($data['org_name']).
					",".$db->quote($data['head_logo2']).
					",".$db->quote($data['postal_address']).
					",".$db->quote($data['physical_address']).
					",".$db->quote($data['pobox']).
					",".$db->quote($data['receipt_text']).
					",".$db->quote($data['receipt_body']).
					",".$db->quote($data['statement_intent']).
					",".$db->quote($data['chairman_title']).
					",".$db->quote($data['chairman_image']).
					",".$db->quote($data['footer1']).
					",".$db->quote($data['footer2']).
					",".$db->quote($data['footer3']).
					",".$db->quote($data['footer4']).
					",".$db->quote($data['footer5']).
					",".$db->quote($data['footer6']).
					",".$db->quote($data['custom_style2']).
				")";
		}
		else
		{
			$query="update #__donorforce_invoice_temp 
			set 
			head_logo=".$db->quote($data['head_logo']).",
			head_addresses=".$db->quote($data['head_addresses']).",
			upper_body_sign=".$db->quote($data['upper_body_sign']).",
			thankyou_body=".$db->quote($data['thankyou_body']).",
			bottom_body_txt=".$db->quote($data['bottom_body_txt']).",
			footer_slogan=".$db->quote($data['footer_slogan']).",
			footer_addresses=".$db->quote($data['footer_addresses']).",
			
			custom_style=".$db->quote($data['custom_style']).", 
			org_name=".$db->quote($data['org_name']).",  
			head_logo2=".$db->quote($data['head_logo2']).",  
			postal_address=".$db->quote($data['postal_address']).",  
			physical_address=".$db->quote($data['physical_address']).", 
			pobox=".$db->quote($data['pobox']).", 
			receipt_text=".$db->quote($data['receipt_text']).", 
			receipt_body=".$db->quote($data['receipt_body']).", 
			statement_intent=".$db->quote($data['statement_intent']).", 
			chairman_title=".$db->quote($data['chairman_title']).", 
			chairman_image=".$db->quote($data['chairman_image']).", 
			footer1=".$db->quote($data['footer1']).", 
			footer2=".$db->quote($data['footer2']).", 
			footer3=".$db->quote($data['footer3']).", 
			footer4=".$db->quote($data['footer4']).", 
			footer5=".$db->quote($data['footer5']).", 
			footer6=".$db->quote($data['footer6']).",
			custom_style2=".$db->quote($data['custom_style2']) 						
			." where id=".$data['id'];
		}
		//echo "<pre> query = "; print_r( $query  ); echo "</pre>";  exit; 
		//echo "<pre> data = "; print_r( $data  ); echo "</pre>";  exit; 
		
		$db->setQuery($query);
				
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
				return false;
		}
		else	
		{
			$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
			$this->setRedirect($link);
	
		}
	}

	function emailReceipt (){
		
		$app    = JFactory::getApplication();
		$params = DonorForceHelper::getParams();
		$db = JFactory::getDbo();
		$user =& JFactory::getUser();
		$id = $params->get('testdonation_id');
		$admin_email = $params->get('admin_email');
		if(empty($admin_email)){
			$this->setMessage(JText::_('Add Admin Email in settings for testing emails'), 'error');
			$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
			$this->setRedirect($link);
			return false;
		}
		if(empty($id)){
			$this->setMessage(JText::_('Add Donation ID in settings for testing emails'), 'error');
			$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
			$this->setRedirect($link);
			return false;
		}

		//$id = 219;
		$query = "
				SELECT
					s.*, u.email,
					p.`name`,
					h.amount,
					h.*
				FROM
				 #__donorforce_history AS h
				
				LEFT JOIN #__users AS u ON h.cms_user_id = u.id
				LEFT JOIN #__donorforce_donor AS s ON s.cms_user_id = u.id
				LEFT JOIN #__donorforce_project AS p ON h.project_id = p.project_id
				
				WHERE
					h.donor_history_id = ".$id."
				ORDER BY
					donor_history_id DESC
				LIMIT 1
		";		
		//echo "<pre> sendTaxReceiptPDF don = "; print_r( $don  ); echo "</pre>";  exit; 
		//echo $query;
		$db->setQuery($query);
		$don= $db->loadObject();
		//print_r($result);

		if(count($don) < 1){
			$this->setMessage(JText::_('The Donation ID you entered in settings for testing emails does not exist in the database'), 'error');
			$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
			$this->setRedirect($link);
			return false;
		}
		$query = "select * from #__donorforce_invoice_temp LIMIT 1";
		$db->setQuery($query);
		$pdf_template= $db->loadObject();
			
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
					width: 40%;
					float: left;
					display: inline-block;
				}
				.receipt .receipt_value {
					width: 60%;
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
				.tax_intent p{/* line-height: 30px; */}
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
									$Taxhtml2 .= 'Logo will be  Here'; 
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
								<h1 style="width: 60%; margin-left: 40%; padding-left: 5px; margin-right: -5px;">Receipt</h1>
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
		//print_r($output_tax); exit;
		file_put_contents(JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf", $output_tax); 
		
		
		//return $Taxhtml2;		
		$mailer2 = JFactory::getMailer();
		$mailer2->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
		$params->get('admin_name',$app->getCfg('fromname'))));				
		$mailer2->setSubject('Testing Donation Confirmation on '.$app->getCfg('sitename'));
		$mailer2->addRecipient($params->get('admin_email'));
		$admin_body ='<h2>Testing Donation Receipt</h2>'
				. '<div>Testing A Donation from  "'.$don->name_first.' '.$don->name_last.'" has been received, Please find the attached Donation Receipt</div>';
		$mailer2->isHTML(true);
		$mailer2->setBody($admin_body); 
		
		
		/* if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
		$mailer2->addAttachment(
				array(
					JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf",
					JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf"
				)
			);
		}else{ */
			$mailer2->addAttachment(JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf");
		//}
		
		$send2 = $mailer2->Send();
		//return true; 
		$this->setMessage(JText::_('Receipt Email sent'), 'success');
		$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
		$this->setRedirect($link);
	
	}

	function emailThankyou(){
		
			$app    = JFactory::getApplication();
			$params = DonorForceHelper::getParams();
			$db = JFactory::getDbo();
			$user =& JFactory::getUser();
			$id = $params->get('testdonation_id');
			$admin_email = $params->get('admin_email');
			if(empty($admin_email)){
				$this->setMessage(JText::_('Add Admin Email in settings for testing emails'), 'error');
				$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
				$this->setRedirect($link);
				return false;
			}
			if(empty($id)){
				$this->setMessage(JText::_('Add Donation ID in settings for testing emails'), 'error');
				$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
				$this->setRedirect($link);
				return false;
			}

			//$id = 219;
			$query = "
					SELECT
						s.*, u.email,
						p.`name`,
						h.amount,
						h.*
					FROM
					 #__donorforce_history AS h
					
					LEFT JOIN #__users AS u ON h.cms_user_id = u.id
					LEFT JOIN #__donorforce_donor AS s ON s.cms_user_id = u.id
					LEFT JOIN #__donorforce_project AS p ON h.project_id = p.project_id
					
					WHERE
						h.donor_history_id = ".$id."
					ORDER BY
						donor_history_id DESC
					LIMIT 1
			";		
			//echo "<pre> sendTaxReceiptPDF don = "; print_r( $don  ); echo "</pre>";  exit; 
			//echo $query;
			$db->setQuery($query);
			$don= $db->loadObject();
			//print_r($result);
	
			if(count($don) < 1){
				$this->setMessage(JText::_('The Donation ID you entered in settings for testing emails does not exist in the database'), 'error');
				$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
				$this->setRedirect($link);
				return false;
			}
			$query = "select * from #__donorforce_invoice_temp LIMIT 1";
			$db->setQuery($query);
			$item= $db->loadObject();


					
			require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';
			
			//check if donor not exist then use its name enter during easygiving donation 
			//$don->name_first = ($don->name_first == '' && $don->name_last == '')?  $data['name'] : $don->name_first ; 
			
			$dompdf = new DOMPDF();
			
			
			
$html = '<html><style>'; 
if(!empty($item->custom_style)){
	$html .= $item->custom_style; 	
}
$html .='</style><body>';

$html.='
<table width="100%">
	<tr>
	<td  valign="top">';
	if(!empty($item->head_logo)) { 	$html.='<img style="min-height:50px;" src="'.JPATH_ROOT.'/'.$item->head_logo .'" name="" />';}
	else{	$html.="Logo";}
	$html.='
	</td>
	<td align="right">';
	 if(!empty($item->head_addresses)){ $html.= $item->head_addresses;  }
	 else{$html.= "Addresses will be Here";}
	 $html .= '
	 </td>
	 </tr>';	
	$html .='
	<tr>
	<td>&nbsp;</td>
	<td align="right">
		<h3>';
		if(!empty($item->main_title)){ $html.= $item->main_title; }
		else{  $html.= "Thank You";}
		$html .='
		</h3>
		<h4>&nbsp;&nbsp;';
				$html.= "Receipt No:";
				$html .=(isset($don->Reference))?($don->Reference):'';
				$html .='
		</h4>
</td>
</tr>';

$html.='
<tr>
	<td valign="top">';
	$country = '';
	if($don->phy_country == 'ZA'){	$country = 'South Africa';} 
	else { $country = $don->phy_country;	 }
	$html.= date('F j, Y').'<br>';
	$html.= '<strong>'.$don->name_first.' '.$don->name_last.'</strong><br>';
	$html.= $don->phy_address.'<br>'.$don->phy_city.'<br>'.$don->phy_state.'<br>'.$don->phy_zip.'<br>'.$country;
	$html .= '
	</td>
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
	<td>';$html .='</td>
	<td>&nbsp;</td>
</tr>
';


// Body Content will go here .  
if(!empty($item->thankyou_body))
{
	$thankyou_body =  str_replace('src="', 'src="'.JURI::root().'/', $item->thankyou_body);
	$html .= $thankyou_body;
	//$html .=$item->thankyou_body; 	
}
// Body Content end here . 

$html .='
<tr>
	<td colspan="2">';
	if(!empty($item->bottom_body_txt)) 
 	{
	 $html.=$item->bottom_body_txt;
 	}
 	else{ 
		$html.= "Bottom Body Text will be Here";
	}
 	$html.='
	</td>
</tr>

<tr>
	<td>';
	if(!empty($item->footer_slogan)){
			$html.= $item->footer_slogan;
		}else{
			$html.= "Footer Slogan Text will be Here";
	}
	$html.='
	</td>
	<td align="right" valign="middle">';
		if(!empty($item->footer_addresses)){
			$html.= $item->footer_addresses;
		}else{
			$html.= "Footer Addresses Text will be Here";
		}
 	$html.='
	</td>
</tr>';
$html.='</table></body></html>';
/*=========================== Old html Layout end ===============================*/

$html ='<html><head>'; 
$html .='<style>';
$html .='
body{position: relative; }
.temp_header {display: inline-block;}
 

span.blable {
    width: 150px;
    display: inline-block;
}
.tax_pdf{     max-width: 60%; border:1px solid black;  }
.tax_cont{ border: 1px solid black;}
.tax_header{     
		margin: 10px;
    display: inline-block;
    width: 100%;  
}
#receipt_view .temp_logo{ 
	float: left;
  display: inline-block;
  width: 50%;
}
.address1{    
	float: left;
  display: inline-block;
  width: 20%;
	margin-top: 50px;
}
.address2{
	 display: inline-block;
   width: 20%;
	 margin-top: 50px;
}
.address1 p, .address2 p{ margin-bottom:4px;  }

.header_empty {
    border: 1px solid black;
    padding: 5px;
    border-right: 0px;
    border-left: 0px;
}
.recpt_no{     
	border-top: 2px solid black;
  border-bottom: 1px solid black;
	text-align:right;
}
.recpt_no span{    
		border-left: 2px solid black;
    display: inline-block;
    padding: 5px;
    min-width: 100px;
    text-align: left; 
	}		
.tax_intent {
    border-bottom: 1px solid black;
}
.chairman_image {
    border-bottom: 1px solid black;
}
.chairman .date{
	float: right;
  min-width: 200px;
}
.tax_footer{ 
	display: table;
  width: 100%;
}			
.footer_row {display: table-row;}		
.footer_row span { display: table-cell;}		
.footer_row span.last{
	text-align: right;
  padding-right: 5px;
}
.main_body{   
	position: relative; 
  margin: 10px 0px;
  padding: 5px;
}
.temp_header {
    width: 70%;
}
.top_thankyou{
		display: inline-block;
    margin-right: 10px; 
	width: 25%;  
}
body{ overflow: hidden; }

';   
$html .= (!empty($item->custom_style)) ? ($item->custom_style) : (''); 
$html .='</style>
<body class="pdf">'; 
$html .='<div class="temp_header">
          <div id="logo">';             
          	//$html .= (!empty($item->head_logo))? ( '<img style="min-height:50px;" src="'.JPATH_ROOT.'/'.$item->head_logo .'" name="" />' ) : ('Logo will be  Here');
          	$html .= (!empty($item->head_logo))? ( '<img style="min-height:50px;" src="'.JPATH_ROOT.'/'.$item->head_logo .'" name="" />' ) : ('');
						$html .=	'
					</div>
          <div id="head_addresses">';
						$html .= (!empty($item->head_addresses)) ? ($item->head_addresses) : ('Addresses will be Here'); 
						$html .=  '
					</div>
          <div style="clear:both"></div>
        </div>         
        
				<div class="top_thankyou">
          <h3>'; 
					$html .= (!empty($item->main_title))? ($item->main_title) : ("Thank You"); 
					$html .='
					</h3>
        	<h4>'; 
						$html.= "Receipt No:";
						$html .=(isset($don->Reference))?($don->Reference):'';
						$html .='
					</h4>
        </div>
				
        <div>';          
      		$html .= date('F j, Y').'<br>';
      		$html .= $don->name_first.' '.$don->name_last.'<br>';
      		$html .= $don->phy_address.', '.$don->phy_city.', '.$don->phy_state.', '.$don->phy_zip.', '.$country.'<br>';
					$html .='
        </div>
        
				<div class="main_body">'; 
				$thankyou_body =  str_replace('src="', 'src="'.JURI::root().'/', $item->thankyou_body);
         $html .=    (!empty($thankyou_body))? ($thankyou_body) : ('<b>System genrated Invoice will be show here</b>'); 
         $html .='
				</div>
        
				<div id="bottom_body_txt">'; 
				 $html .= (!empty($item->bottom_body_txt)) ? ($item->bottom_body_txt) : ('Bottom Body Text will be Here');
				 $html .='    
        </div>
        
				<div class="footer">
        <div class="slogan">'; 
         	$html .= (!empty($item->footer_slogan)) ? ($item->footer_slogan)  : ('Footer Slogan Text will be Here');   
        	$html .='
				</div>
        <div class="footer_addresses">'; 
					$html .= (!empty($item->footer_addresses))? ($item->footer_addresses) : ('Footer Addresses Text will be Here'); 
					$html .='
				</div>        
				</div>
			</body></html>'; 
			
			//echo $html; exit;
 // echo " <pre>   html  ";  print_r( $html  ); echo " </pre> ";   		exit; 	

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
$html = str_replace('{project_name}',$don->project_name, $html);		
$html = str_replace('{donation_type}',$don->donation_type, $html);
			$dompdf->load_html($html);
			$dompdf->render();
			
			$output = $dompdf->output();
			file_put_contents(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf", $output);
			/**-------------------Send Email--------------------------------**/
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$mailer->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
			$params->get('admin_name',$app->getCfg('fromname'))));
			$mailer->setSubject('Testing Donation Confirmation on '.$app->getCfg('sitename'));
			//$recipient = ($don->email != '')? $don->email  : $data['email'];
			$recipient = $params->get('admin_email');
			$mailer->addRecipient($recipient);
			$body   = '<h2>Testing Donation Thank You</h2>'
							. '<div>Testing Thankyou for your Donation, Please find the attached Donation Receipt</div>';
			
			$mailer->isHTML(true);
			$mailer->setBody($body);
			// Optional file attached
			
			/* if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
				$mailer->addAttachment(
					array(
						JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf",
						JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf"
					)
				);
			}else{ */
						$mailer->addAttachment(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf");
			//}
			
			$send = $mailer->Send();

			$this->setMessage(JText::_('Thank You Email sent'), 'success');
			$link = JRoute::_('index.php?option=com_donorforce&view=template&layout=template_genrate',false);
			$this->setRedirect($link);
	}

	function cancel(){
		$link = JRoute::_('index.php?option=com_donorforce&view=donorforce',false);
		$this->setRedirect($link);
	}
}
