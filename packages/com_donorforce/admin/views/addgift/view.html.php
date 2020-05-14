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

class DonorforceViewAddgift extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
	
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
		$lists 	= array();		
		$user 		= JFactory::getUser();
		$model		= $this->getModel();
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', false);
		$isNew		= ($this->item->gift_id == 0);
		$user	= JFactory::getUser();
		JToolBarHelper::title(JText::_('Add Gift'), 'newsfeeds.png');
		$canDo		= $this->getActions($this->state->get('filter.id'));
		
		JToolBarHelper::apply('addgift.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('addgift.save', 'JTOOLBAR_SAVE');
		
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('addgift.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('addgift.cancel', 'JTOOLBAR_CLOSE');
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
			$assetName = 'com_donorforce.addgift.'.(int) $id;
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