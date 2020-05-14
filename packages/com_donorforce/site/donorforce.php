<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


defined('_JEXEC') or die('Restricted access'); 


$params = JComponentHelper::getParams('com_donorforce');

if($params->get('enable_bootstrap', 0))
{
	JHtml::_('bootstrap.tooltip'); 
	$doc = JFactory::getDocument(); 
	$doc->addStyleSheet(JUri::base() . '/media/jui/css/bootstrap.css');
	$doc->addStyleSheet(JUri::base() . '/media/jui/css/bootstrap.min.css');
	$doc->addStyleSheet(JUri::base() . '/media/jui/css/bootstrap-responsive.min.css');
	$doc->addStyleSheet(JUri::base() . '/media/jui/css/bootstrap-extended.min.css');
	$doc->addStyleSheet(JUri::base() . '/media/jui/css/icomoon.css');
}

// require helper file
JLoader::register('DonorForceHelper', dirname(__FILE__) . '/' . 'helpers' . '/' . 'donorforce.helper.php');
if(!class_exists('DonorForceHelper'))
{
  //require_once $path;
   JLoader::register('DonorForceHelper', dirname(__FILE__) . '/' . 'helpers' . '/' . 'donorforce.helper.php');
   JLoader::load('DonorForceHelper');
}
 
if(!class_exists('tjcpgHelper'))
{
  //require_once $path;
   JLoader::register('tjcpgHelper', dirname(__FILE__) . '/' . 'helpers' . '/' . 'tjcpg.php');
   JLoader::load('tjcpgHelper');
}
  

// Require the base controller
require_once( JPATH_COMPONENT.'/'.'controller.php' );

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base().'components/com_donorforce/assets/css/style.css');
// import Joomla controller library


// Get an instance of the controller prefixed by ChefsNews
// this line will create timesheetController.
// Joomla will look for the declaration of that class in an aptly named file called controller.php (it's a default behavior).
$controller = JControllerLegacy::getInstance('donorforce');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
?>