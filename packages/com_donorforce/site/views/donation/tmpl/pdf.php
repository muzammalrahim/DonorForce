<?php 
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//require_once JPATH_COMPONENT."/assets/dompdf/dompdf_config.inc.php";
require_once JPATH_LIBRARIES . '/dompdf/library/dompdf_config.inc.php';
$item=DonorForceHelper::getPdfTemplate(); 

//print_r($data); exit;


$app = JFactory::getApplication();
 
$dompdf = new DOMPDF();

$html = '
<html>
<head>

</head>
 <body>
 
 ';

	$html.='<table width="100%" border="1">
	         <tr>
      			<td  valign="top">';
        
					if(!empty($item->head_logo)) 
					{
						$html.='<img style="min-height:50px;" src="'.JURI::root().'/'.$item->head_logo .'" name="" />';
					}
					else
				{	$html.="Logo";}
 	$html.=' </td>
            <td>';
        
					 if(!empty($item->head_addresses)) 
					 {
						$html.= $item->head_addresses;
					 }
					 else
				     {  $html.= "Addresses will be Here";}
 
     $html .= '</td> </tr>';
	
$html .='<tr>
<td>&nbsp;</td>
<td> <h1>Tax Certificate</h1>
<h3>&nbsp;&nbsp;Receipt No: ';
$html .=(isset($item->id))?$item->id:'';
$html .='</h3>
</td>
</tr>';

$html.='<tr><td valign="top">';
if(!empty($item->upper_body_sign)) 
 {
	 $html.= $item->upper_body_sign;
 }
 else
 $html.= "Upper Body Text will be Here";

  $html .= '</td>
<td>&nbsp;</td>
</tr>';
$html .= '<tr>
<td>1</td>
<td>2</td>
</tr>';

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
<td>';
if(!empty($item->footer_addresses)) 
 {
	 $html.= $item->footer_addresses;
 }
 else
 $html.= "Footer Addresses Text will be Here";

 $html.='</td>
</tr>';

$html.='</table> </body>
</html>';
print_r($html); 
 $dompdf->load_html($html);
$dompdf->render();
 
$dompdf->stream("hello.pdf");
//$app->redirect('index.php?option=com_timesheet&view=reports');
?>