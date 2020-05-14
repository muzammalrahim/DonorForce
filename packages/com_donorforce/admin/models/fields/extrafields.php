<?php
/**
 * @version     1.0.0
 * @package     com_donorforce
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Arsalan <arsalan7720@gmail.com> - http://www.creativetech-solutions.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldExtrafields extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'extrafields';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	 
	 protected function getInput()
	{  
		jimport('joomla.application.component.helper');
		$com_donorforce = JComponentHelper::getParams('com_donorforce');
		$extrafields = $com_donorforce->get('extrafields');
		$html[] = '<table class="table table-striped" id="domains_table" width="100%">';
		$html[] = '<thead>';		
		$html[] =	'<tr>
		<th class="center" width="35%">Name(DB Variable)</th>
		<th class="center" width="35%">Title</th>
		<th class="center" width="60%">Required</th>
		</tr>';
		$html[] = '</thead>';
		$html[] = '<tbody>';
		
		 
		$new_doamin = '';
		$new_doamin[] = '<tr><td class="center"><span class="count"></span>'; 
		$new_doamin[] = '<input type="text" name="jform[extrafields][name][]" value=""></td>';
		$new_doamin[] = '<td class="center"><input type="text" name="jform[extrafields][title][]" value=""></td>';
		$new_doamin[] = '<td class="center"><select name="jform[extrafields][required][]">';
		$new_doamin[] = '	<option value="1">Required</option>';
		$new_doamin[] = '	<option value="0">Non-Required</option>';
		$new_doamin[] = '</select>';
		$new_doamin[] = '<td></tr>';
		
		
		 if(!empty($extrafields->title)){
			foreach($extrafields->title  as $ef_key=>$ef_title){
			$html[] = '<tr>
			<td class="center"><span class="count">'.$ef_key.'</span>
			<input type="text" name="jform[extrafields][name][]" value="'.$extrafields->name[$ef_key].'"></td>
			<td><input type="text" name="jform[extrafields][title][]" value="'.$ef_title.'"></td>
			<td class="center domain_status">
			 <select name="jform[extrafields][required][]" >
				<option value="1"  '.(($extrafields->required[$ef_key])?('selected="selected"'):('')).' >Required</option>
				<option value="0" '.(!($extrafields->required[$ef_key])?('selected="selected"'):('')).'>Non-Required</option>
			</select>
			</td>';
			
			//delete
			$html[] =	'<td align="center" class="center" width="10%"><a href="javascript:void(0);" title="remove" onclick="removeDomainField(this)">';
			$html[] =	'<span class="icon-trash" style="font-size:20px;"></span></a></td>			
			</tr>';
 
			}
		 }
				
				
				$html[] = '<script type="text/javascript">
					function addDomain(){
							
						if(jQuery("#domains_table").find("tbody tr").length ){
						var last_row = parseInt(jQuery("#domains_table").find("tbody tr").last().find("td span.count").html());										
						 }else{ var last_row = 0; }
							
							var html = \''.implode($new_doamin).'\';
							var ele = document.getElementById("domains_table").getElementsByTagName("tbody");
							ele[0].insertAdjacentHTML("beforeend", html);
							//jQuery("#domains_table").find("tbody tr").last().find("td span.count").html(last_row+1);
							//jQuery(".chzn-done").chosen();
							return false;
						}
						
						function removeDomainField(ele){
							var tr = ele.parentNode.parentNode;
							tr.parentNode.removeChild(tr);
						}
				 
				</script>';	
		
	 	$html[] = '</tbody>';
		$html[] = '</table>';		
		$html[] = '<br /><br /><button type="button" class="btn btn-small" onclick="addDomain(this)"><span class="icon-save-new"></span>Add New Field</button>';
		
		
		
		$html[] = '<style>#extrafields .controls{margin-left:0;}span.count {visibility: hidden;}</style>';
		
		return implode($html);
		
		 
	}
	
	 
}
