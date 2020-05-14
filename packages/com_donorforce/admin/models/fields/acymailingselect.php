<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldAcymailingSelect extends JFormField {
 
	protected $type = 'acymailingselect';
 
	// getLabel() left out
 
	public function getInput() {
	 
	$db = JFactory::getDbo();
	$db->setQuery("SELECT enabled FROM #__extensions WHERE element ='com_acymailing'");
	$is_enabled = $db->loadResult();

	 if (($is_enabled == 1) && (JComponentHelper::getComponent('com_acymailing',true)->enabled)){
	 
	 $app = JFactory::getApplication();
	 $componentParams = JComponentHelper::getParams('com_donorforce');
	 $acy_mailing_bridge = $componentParams->get('acy_mailing_bridge');
	  

	 
		$db = JFactory::getDbo();	 
		$query = $db->getQuery(true); 
		$query->select($db->quoteName(array('listid','name')));
		$query->from($db->quoteName('#__acymailing_list'));	
		$query->order('name ASC');   
		$db->setQuery($query);		
		$results = $db->loadObjectList(); 			
		$html = '<select id="jform_acy_mailing_bridge" name="jform[acy_mailing_bridge]" class="chzn-done">';
		$html .='<option value="0">Default None</option>';		 
		
		foreach( $results As $result){
			$selected = ''; 
			if($acy_mailing_bridge == $result->listid){ $selected = 'selected="selected"';  }
			
			$html .= ' <option value="'.$result->listid.'" '.$selected.'  > '.$result->name.' </option>'; 
		}		
		$html .='</select>'; 
		return $html; 		
		}else{ return ' Component Acy Mailing not enabled'; }
	}
}