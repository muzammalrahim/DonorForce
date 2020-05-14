<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die();
//jimport('joomla.application.component.controllerform');
//require_once JPATH_COMPONENT."/assets/dompdf/dompdf_config.inc.php";
require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';

class DonorforceControllerAjax extends JControllerForm
{

	
function genratePdf()
{

$item = DonorForceHelper::getPdfTemplate(); 
$don  = DonorForceHelper::getLatetsDonation(); 
//print_r($don); exit;
$app    = JFactory::getApplication();
$params = DonorForceHelper::getParams();
 
$dompdf = new DOMPDF();
 
$html = '<html>
<style>
@page {
	
}
body {
	margin-top: -20px;
}</style>
<body>
';

	$html.='<table width="100%">
	         <tr>
      			<td  valign="top">';
        
					if(!empty($item->head_logo)) 
					{
						$html.='<img style="min-height:50px;" src="'.JPATH_ROOT.'/'.$item->head_logo .'" name="" />';
					}
					else
				{	$html.="Logo";}
 	$html.=' </td>
            <td align="right">';
        
					 if(!empty($item->head_addresses)) 
					 {
						$html.= $item->head_addresses;
					 }
					 else
				     {  $html.= "Addresses will be Here";}
 
     $html .= '</td> </tr>';
	
$html .='<tr>
<td>&nbsp;</td>
<td align="right"> <h3>';
					if(!empty($item->main_title)) 
					 {
						$html.= $item->main_title;
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
/*if(!empty($item->upper_body_sign)) 
 {
	 $html.= $item->upper_body_sign;
 }
 else
 $html.= "Upper Body Text will be Here";*/
 
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
Thank you for the donation of '.DonorForceHelper::getCurrency().' '.DonorForceHelper::displayAmount($don->amount).' to our project '.$don->name.' Below are the details of your donation.
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>

<td>
<table width="100%" border="1">
<tr>
<td width="33%" align="center"><b>Project</b></td>
<td width="33%" align="center"><b>Date</b></td>
<td width="33%" align="center"><b>Amount (ZAR)</b></td>
</tr>
<tr>
<td align="center">'.$don->name.'</td>
<td align="center">'.date('F j, Y').'</td>
<td align="center">'.DonorForceHelper::displayAmount($don->amount).'</td>
</tr>
<tr>

</tr>
</table>
</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

';

$html .='<tr>
<td colspan="2">';
if(!empty($item->bottom_body_txt)) 
 {
	 $html.=$item->bottom_body_txt;
 }
 else
 $html.= "Bottom Body Text will be Here";

 $html.='</td>

</tr>


<tr>
<td>';
if(!empty($item->footer_slogan)) 
 {
 $html.= $item->footer_slogan;
 }
 else
 $html.= "Footer Slogan Text will be Here";
 $html.='</td>
<td align="right" valign="middle">';
if(!empty($item->footer_addresses)) 
 {
	 $html.= $item->footer_addresses;
 }
 else
 $html.= "Footer Addresses Text will be Here";

 $html.='</td>
</tr>';

$html.='</table></body></html>';
//print_r($html); exit;
//$customPaper = array(0,0,0,900);
//$dompdf->set_paper($customPaper);
$dompdf->load_html($html);
$dompdf->render();

$output = $dompdf->output();
file_put_contents(JPATH_ROOT."/images/ThankYou.pdf", $output);
//$dompdf->stream(JPATH_COMPONENT."hello.pdf");
/**-------------------Send Email--------------------------------**/
$mailer = JFactory::getMailer();
$config = JFactory::getConfig();
 
$mailer->setSender(array($params->get('admin_email',$app->getCfg('mailfrom')), 
				$params->get('admin_name',$app->getCfg('fromname'))));
				
$mailer->setSubject('Donation Confirmation on '.$app->getCfg('sitename'));

$recipient =$don->email;
 
$mailer->addRecipient($recipient);

$body   = '<h2>Donation Receipt</h2>'
    . '<div>Thankyou for your Donation, Please find the attached Donation Receipt</div>';

$mailer->isHTML(true);
$mailer->setBody($body);
// Optional file attached
$mailer->addAttachment(JPATH_ROOT."/images/ThankYou.pdf");

$send = $mailer->Send();
if ( $send !== true ) {
    echo 'Error sending email: ' . $send->__toString();
} else {
    echo 'Mail sent';
}
/**---------------------------------------------------------**/

}  
	
}