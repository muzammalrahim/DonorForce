<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component recdonation
 */

class DonorforceViewPayment extends JViewLegacy
{
	protected $projects;
	
	
      // Overwriting JView display method
	function display($tpl = null) 
	{
		// Assign data to the view
		//$this->items = $this->get('Items');
 		//$this->state = $this->get('state');
		//$this->pagination	= $this->get('Pagination');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Create a shortcut to the parameters.
		$app = JFactory::getApplication();
		
		$this->app = $app;
		
		// Load the parameters.
		$this->params	= $app->getParams();
		
		$this->_prepareDocument();
		// Display the view
		parent::display($tpl);
	}
	
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

	
		$head = JText::_('Payment');
	

		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', $head);
		}

		$title = $this->params->def('page_title', $head);
		if ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

			if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}