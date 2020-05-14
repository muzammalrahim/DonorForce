<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

JLoader::register('BannersHelper', JPATH_COMPONENT.'/helpers/churchadmin.php');

/**
 * View to edit a banner.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_churchadmin
 * @since		1.5
 */
class DonorforceViewTemplate extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $template;
	protected $state;
    protected $text_prefix = 'COM_DONORFORCE';
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form			= $this->get('Form');
		$this->item			= $this->get('Item');
		$this->template		= $this->get('Template');
		
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		//JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		//$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		// Since we don't track these assets at the item level, use the category id.
        
		JToolBarHelper::title($isNew ? JText::_('Edit Mailing Templates') : JText::_('COM_DONORFORCE_Donorforce_GROUP_MANAGER_EDIT'), 'Donorforce-group-Donorforce');

		// If not checked out, can save the item.
	//	if (!$checkedOut) {
			JToolBarHelper::apply('template.saveDesign');
		   	//JToolBarHelper::save('template.saveDesign');
			JToolBarHelper::cancel('template.cancel', 'JTOOLBAR_CLOSE');
			JToolBarHelper::custom('template.emailThankyou','mail','icon-mail icon-white','Thank You Email','');
			JToolBarHelper::custom('template.emailReceipt','mail','icon-mail icon-white','Receipt Email','');
			//JToolBarHelper::save2new('template.save2new');
	//	}

		// If an existing item, can save to a copy.
		/*if (!$isNew) {
			JToolBarHelper::save2copy('template.save2copy');
		}*/

	/*	if (empty($this->item->id))  {
			JToolBarHelper::cancel('template.cancel');
		}
		else {
			JToolBarHelper::cancel('template.cancel', 'JTOOLBAR_CLOSE');
		}*/
	}
}
