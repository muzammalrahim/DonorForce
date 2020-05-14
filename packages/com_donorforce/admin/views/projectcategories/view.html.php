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

class DonorforceViewProjectCategories extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $tmpl;
	
    function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
		$this->state = $this->get('State');
 
 		/*print_r($items);
		exit();*/
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
		
 
		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
	
	protected function addToolBar() 
	{
		$user	= JFactory::getUser();
		JToolBarHelper::title(JText::_('Project Categories Management'), 'newsfeeds.png');
		$canDo	= $this->getActions($this->state->get('filter.id'));
		
		JToolBarHelper::publish('projectcategories.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('projectcategories.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		
		if ($canDo->get('core.create')) 
		{
			JToolBarHelper::addNew('projectcategory.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) 
		{
			JToolBarHelper::editList('projectcategory.edit', 'JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.delete')) 
		{
			JToolBarHelper::deleteList('', 'projectcategories.delete', 'Delete');
			//JToolBarHelper::divider();
		}
		
		//JToolBarHelper::preferences('com_donorforce');
	}
	public static function getActions($classId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		
		if (empty($classId)) {
			$assetName = 'com_donorforce';
		} else {
			$assetName = 'com_donorforce.projectcategories.'.(int) $classId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	/**
	 * Method to set up the document properties
	 * This will sets the browser title as "Parts Administration".
	 * @return void
	 */
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('Project Category Administration'));
	
	}
}

?>