<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class DonorforceViewDonor extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
	protected $history;
	protected $subscriptions;
	
	/**
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function display($tpl=null) 
	{
		$layout = $this->getLayout();
		switch(strtolower($layout))
		{
			case "form":
				$this->_form($tpl);
			  break;
			case "default":
			default:
				//$this->_default($tpl);
				$this->_form($tpl);
			  break;
		}
	}
	
	protected function _form( $tpl=null )
	{
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		
		$this->history	= $this->get('DonorHistory');
		$this->subscriptions	= $this->get('DonorSubscriptions');
		$this->gifts = $this->get('GiftHistory');
		
		
		$model = &$this->getModel();
		
		$CountRows = $this->get('CountDonorHistory');
		$No_of_rows = $CountRows->total_rows;
		if($No_of_rows > 20){ 
			require_once JPATH_COMPONENT_ADMINISTRATOR . '/lib/paginator2.php';
			$paginator = new pagination();
			$this->pagination_list = $paginator->calculate_pages($No_of_rows, 20, 0);
		}
		
		$Count_Gifts = $this->get('CountDonorGifts');
		$Count_Gifts = $Count_Gifts->total_rows;
  
		if( $Count_Gifts > 20 ){
			require_once JPATH_COMPONENT_ADMINISTRATOR . '/lib/paginator2.php';
			$gpaginator = new pagination();
			$this->gpagination_list = $gpaginator->calculate_pages($Count_Gifts, 20, 0);
		} 
		
		
		
		$db = JFactory::getDbo();
		$db->setQuery("SELECT enabled FROM #__extensions WHERE element ='com_acymailing'");
		$this->acymailing = $db->loadResult();

	 	if (($this->acymailing == 1) && (JComponentHelper::getComponent('com_acymailing',true)->enabled)){	  

					
					$acy_query = $db->getQuery(true); 
					$acy_query->select($db->quoteName(array('listid','name','color')));
					$acy_query->from($db->quoteName('#__acymailing_list'));	
					$acy_query->order('name ASC');   
					$db->setQuery($acy_query);		
					$this->acymailing_list = $db->loadObjectList(); 		
					
					
					//query to gell the list assign to user
					//echo "<pre> this->item = ";  print_r($this->item); echo  "</pre>"; 					
					
				$this->user_sub = array();
				if($this->item->cms_user_id != ''){
				 
				 //Get the current subscription of the user
					$user_sub_query = $db->getQuery(true);
					$cms_user_id = $this->item->cms_user_id; 					
					$user_sub_query = 'SELECT listsub.listid
														 FROM #__acymailing_listsub As listsub
														 LEFT JOIN #__acymailing_subscriber As subscriber on subscriber.subid = listsub.subid
														 Where subscriber.userid = '.$cms_user_id;
					
					$db->setQuery($user_sub_query);		
					$user_sub_result = $db->loadAssocList(); 
					
				
					foreach($user_sub_result as $user_sub){						
							array_push( $this->user_sub, $user_sub['listid']);
					}
				}
					
					//echo "<pre> this->item = "; print_r($this->item); echo "</pre>";   
					
				//Check the default subscription for all users	
				$app = JFactory::getApplication();
				$componentParams = JComponentHelper::getParams('com_donorforce');
				$this->default_subscription = $componentParams->get('acy_mailing_bridge');
				
				/*if($this->default_subscription  != 0){
						array_push( $this->user_sub, $this->default_subscription );
				}*/
				
					
				
				/*echo "<pre> user_sub_result  = "; print_r($user_sub_result ); echo "</pre>"; 	
				echo "<pre> this->user_sub  = "; print_r($this->user_sub ); echo "</pre>"; */
						
		}
				
		
		//echo "<pre> acymailing_list  = "; print_r($acymailing_list ); echo "</pre>"; 
		
		
		$lists 	= array();		
		$user 		= JFactory::getUser();
		$model		= $this->getModel();
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		$isNew		= ($this->item->donor_id == 0);
		$user	= JFactory::getUser();
		JToolBarHelper::title(JText::_('Donor Edit'), 'newsfeeds.png');
		$canDo		= $this->getActions($this->state->get('filter.id'), $this->item->donor_id);
		
		JToolBarHelper::apply('donor.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('donor.save', 'JTOOLBAR_SAVE');
		JToolbarHelper::save2new('donor.save2new');
		
		if (empty($this->item->donor_id))  {
			JToolBarHelper::cancel('donor.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('donor.cancel', 'JTOOLBAR_CLOSE');
		}
		//JToolBarHelper::divider();
	}
	
	public static function getActions($classId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		
		if (empty($id)) {
			$assetName = 'com_donorforce';
		} else {
			$assetName = 'com_donorforce.donors.'.(int) $id;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}    
}
?>