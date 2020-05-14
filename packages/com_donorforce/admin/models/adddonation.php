<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');

class DonorforceModelAdddonation extends JModelAdmin
{	
	protected	$option 		= 'com_donorforce';
	protected 	$text_prefix	= 'com_donorforce';
	
    function __construct()
    {
        parent::__construct();
    }
	
  
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_donorforce.adddonation', 'adddonation', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_donorforce.edit.adddonation.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	function save ($data)
	{
		//echo "<br /> save in model "; exit;  
		return parent::save($data);
		
	}
	
	// Send Email for onceoff donation  
function sendemail($history_id){

jimport('joomla.application.component.helper');
if( JComponentHelper::getParams('com_donorforce')->get('send_thankyou') == 0){
	return false;	
}	
	
//echo $history_id;exit;
//echo "<br /> Email Start ";  exit; 	
//require_once JPATH_SITE."/components/com_donorforce/assets/dompdf/dompdf_config.inc.php"; 
require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';
//JLoader::register('DonorForceHelper', JPATH_SITE."/components/com_donorforce/helpers/donorforce.helper.php");	
//echo  JPATH_SITE."/components/com_donorforce/helpers/donorforce.helper.php"; 	
$item = DonorforceHelper::getPdfTemplate(); 
//echo "<pre> item "; print_r( $item ); echo "</pre>";  exit; 
$don  = DonorforceHelper::getLatetsDonation($history_id); 
//echo "<pre> addonation sendemail "; print_r($don  ); echo "</pre>";  exit; 

/*-- Tax changes --*/
if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
	DonorforceHelper::sendTaxReceiptPDF($don); 
}
/*-- Tax changes end --*/

//echo "<br /> item"; print_r($item);
//echo "<br /> don <pre>"; print_r($don);
$app    = JFactory::getApplication();
$params = DonorForceHelper::getParams();
$dompdf = new DOMPDF();

/*=========================== Old html Layout ==================================*/

$html = '<html>
<style>'; 
if(!empty($item->custom_style)){
	$html .= $item->custom_style; 	
}
$html .='</style><body>';

$html.='<table width="100%">
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
.tax_body{    
	border-top: 1px solid black;
  border-bottom: 1px solid black;
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

	
		



//print_r(  $html   );  exit; 
//print_r($html); exit;
//$customPaper = array(0,0,0,900);
//$dompdf->set_paper($customPaper);
$dompdf->load_html($html);
$dompdf->render();
$output = $dompdf->output();
file_put_contents(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf", $output);
//$dompdf->stream(JPATH_COMPONENT."hello.pdf");
/**-------------------Send Email--------------------------------**/
$mailer = JFactory::getMailer();
$config = JFactory::getConfig();
$mailer->setSender(array(
				$params->get('admin_email',$app->getCfg('mailfrom')), 
				$params->get('admin_name',$app->getCfg('fromname'))
				));
				
$mailer->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));
$recipient = $don->email; 
/*$recipient = array(
	$params->get('admin_email'), 
	$don->email
); */

//$recipient =$don->email;
 
$mailer->addRecipient($recipient);
$body   = '<h2>Donation Receipt</h2>'
    . '<div>Thankyou for your Donation, Please find the attached Donation Receipt</div>';
$mailer->isHTML(true);
$mailer->setBody($body);
// Optional file attached

if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
	$mailer->addAttachment( 
		array(
			JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf",
			JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf"	
		)
	);
}else{
	$mailer->addAttachment(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf");
}


//echo JPATH_ROOT."/images/ThankYou.pdf"; exit; 

$send = $mailer->Send();


// Send email to admin
$mailer2 = JFactory::getMailer();
$mailer2->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
				$params->get('admin_name',$app->getCfg('fromname'))));				
$mailer2->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));
$mailer2->addRecipient($params->get('admin_email'));
$admin_body ='<h2>Donation Receipt</h2>'
    . '<div>A Donation from  "'.$don->name_first.' '.$don->name_last.'" has been received, Please find the attached Donation Receipt</div>';
$mailer2->isHTML(true);
$mailer2->setBody($admin_body); 
if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){
	$mailer2->addAttachment( 
		array(
			JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf",
			JPATH_ROOT."/images/DonationReceipt_".$don->donor_id.".pdf"	
		)
	);
}else{	
	$mailer2->addAttachment(JPATH_ROOT."/images/ThankYou_".$don->donor_id.".pdf");
}

$send2 = $mailer2->Send();
// Send email to admin end 


//echo "<hr /><pre>"; print_r($mailer); exit;  

if ( $send !== true || $send2 !== true ) {    
		echo 'Error sending email: ';		
} else {
    echo 'Mail sent';
}
/**---------------------------------------------------------**/

return true; 
	
	
	}

	
}
?>