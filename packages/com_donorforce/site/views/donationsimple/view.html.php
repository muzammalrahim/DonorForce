<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component recdonation
 */

class DonorforceViewDonationSimple extends JViewLegacy
{
	protected $projects;
	
	
      // Overwriting JView display method
	function display($tpl = null) 
	{
		// Assign data to the view
		
		//
		
	
		$app = JFactory::getApplication();	
		$data = JRequest::get('post');
		$data = JRequest::get('post');
		$project_id = JRequest::getVar('project_id');
		if( $project_id != ''){ $data['project_id']= JRequest::getVar('project_id');}
		
				
		if($data['project_id'] > 0)
		{			
			$session = JFactory::getSession();			
			$session->clear('com_donorforce.project_id');
			$session->clear('com_donorforce.donationtype');			
			$session->set('com_donorforce.project_id', $data['project_id']);
			$session->set('com_donorforce.donationtype', $data['donationtype']);			
			$user = JFactory::getUser();			
			if($user->get('guest'))
			{
				//$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep1&project_id='.$data['project_id'].'&donationtype='.$data['donationtype']);
				$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep1&project_id='.$data['project_id']);
			} else {				
				$userinfo = DonorForceHelper::getFullUserInfo($user->id);
				if($userinfo->donor_id > 0)
				{
					//$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep3&project_id='.$data['project_id'].'&donationtype='.$data['donationtype']);
				} else {
					//$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep2&project_id='.$data['project_id'].'&donationtype='.$data['donationtype']);
					$app->redirect('index.php?option=com_donorforce&view=donation&layout=regstep2&project_id='.$data['project_id']);	
				}
			}
						
		} else {
			$app->redirect('index.php?option=com_donorforce&view=projects',"Please Select a Project First!");	
		}
		
	
		$this->form		= $this->get('Form');		
		//START :: getting payment gateway data
		$params = JComponentHelper::getParams('com_donorforce');
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('payment'); 
		if(!is_array($params->get( 'gateways' )) ){
			$gateway_param[] = $params->get( 'gateways' );
		}
		else{
			$gateway_param = $params->get( 'gateways' ); 	
		}
		
		if(!empty($gateway_param)) $gateways = $dispatcher->trigger('onTP_GetInfo',array($gateway_param));
			
		$this->gateways = $gateways;
		
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

	
		$head = JText::_('Donation');
	

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