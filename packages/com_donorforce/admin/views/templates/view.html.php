<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

/**
 *
 * @package     Joomla.Administrator
 * @subpackage  com_churchadmin
 * @since       1.6
 */
class DonorforceViewTemplates extends JViewLegacy
{
	protected $categories;
	protected $items;
	protected $pagination;
	protected $state;
    protected $text_prefix = 'COM_DONORFORCE';
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->categories	= $this->get('CategoryOrders');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		// Include the component HTML helpers.

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
        JToolBarHelper::title(JText::_('Donorforce Templates'), 'churchadmin-group-Donorforce');
     
		
        JToolBarHelper::addNew('Template.add');
        JToolBarHelper::editList('Template.edit');
        JToolBarHelper::divider();
        JToolBarHelper::publish('templates.publish', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::unpublish('templates.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		
		JToolBarHelper::deleteList('', 'templates.delete', 'Delete');
			//JToolBarHelper::divider();
		
        JToolBarHelper::divider();
        JToolBarHelper::preferences('COM_DONORFORCE');
	}
	
}
