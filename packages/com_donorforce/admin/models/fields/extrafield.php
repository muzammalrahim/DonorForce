<?php
/**
 * @version     1.0.0
 * @package     com_timesheet
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Arsalan&Ateeb <arsalan7720@gmail.com> - http://www.creativetech-solutions.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldExtrafield extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'extrafield';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		
		$formData = $this->form->getData()->toArray(); 
		$entriesData = !empty($formData['entries'])?(json_decode($formData['entries'],true)):""; 
		$com_donorforce = JComponentHelper::getParams('com_donorforce');
		$entries = $com_donorforce->get('entries');

		// echo "<pre> entries "; print_r($entries ); echo "</pre>"; 
		//This section of code is for the purpose of fetching the entries from database I dont need it
		
		$html[] = '<table class="table table-striped" id="extra_field_table" width="70%">';
		$html[] = '<thead>';		
		$html[] =	'<tr>							 	
							 	<th class="center" width="35%">Field Name</th>
							 	
								<th ></th>
							 </tr>';
		$html[] = '</thead>';
		$html[] = '<tbody>'; 
		$increment = 1;		 
		if(!empty($entries)){
			foreach ($entries->title as $key => $entry_title) {
				$html[] = '<tr>';
				$html[] = '<td class="center dname"><span class="count">'.$increment.'</span><input type="text" name="jform[entries][title][]" value="'.$entry_title.'"></td>';
				$html[] =	'<td align="center" class="center" width="10%" ><a href="javascript:void(0);" title="remove field" onclick="removextrafield(this)">';
				$html[] =	'<span class="icon-trash" style="font-size:20px;"></span></a></td>';
					
                $html[] = '</tr>';
                $increment++;
			}
		}
		 

		$html[] = '</table>';		
		$html[] = '<br /><br /><button type="button" class="btn btn-small" onclick="addextra_field(this)"><span class="icon-save-new"></span>Add New Custom Field</button>';
		
		$html[] = '<style>
					@media only screen and (max-width: 1024px) {  
						.domain_cont{ min-width: 600px; overflow-x:scroll;}
					}					
					#extra_field_table thead tr { background: #ddd;}
					#extra_field_table tbody tr:nth-child(even){  background: rgba(0,0,0,0.075); }
					#extra_field_table tbody tr{ vertical-align: top; }
					#extra_field_table tbody tr td { padding: 5px; padding-bottom: 10px !important; }
					#extra_field_table textarea{ width:80%; }
					.extra_field_table .chzn-drop,.domain_status .chzn-container,.domain_status .chzn-done,.domain_status .chzn-done option{ max-width:170px !important;  }
					#extra_field_table .icon-trash{ padding: 5px; }
					#extra_field_table .count{ float: left;
															width: 20px; width:10%; 
															padding: 4px 6px;
															border: 1px solid #ccc;
															box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
															border-radius: 20px 0px 0px 20px;
															border-right: 0px;
															margin-right: -1px;
					}
					#extra_field_table .dname input{ float:left;  width:70%;  } 
					 
			</style>';
			
			
				$new_field[] = array();
				$new_field[] = '<tr>';
			
				$new_field[] = '<td class="center dname"><span class="count"></span><input type="text" name="jform[entries][title][]" value=""></td>';				
				
				$new_field[] =	'<td align="center" class="center" width="10%" ><a href="javascript:void(0);" title="remove field" onclick="removextrafield(this)">';
				$new_field[] =	'<span class="icon-trash" style="font-size:20px;"></span></a></td>';					
				$new_field[] = '</tr>';		
			
			$html[] = '<script type="text/javascript">
			function addextra_field(){
					
				if(jQuery("#extra_field_table").find("tbody tr").length ){
         		var last_row = parseInt(jQuery("#extra_field_table").find("tbody tr").last().find("td span.count").html());										
				 }else{ var last_row = 0; }
					
					var html = \''.implode($new_field).'\';

					var ele = document.getElementById("extra_field_table").getElementsByTagName("tbody");
					//ele[0].insertAdjacentHTML("beforeend", html);
					
					jQuery("#extra_field_table tbody").append(html); 
					jQuery("#extra_field_table").find("tbody tr").last().find("td span.count").html(last_row+1);
					return false;
				}
				
				function removextrafield(ele){
					var tr = ele.parentNode.parentNode;
					tr.parentNode.removeChild(tr);
				}
				 
				</script>';	
		
		
		
		return implode($html);
	
	}
}