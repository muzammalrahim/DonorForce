<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// import Joomla view library
jimport( 'joomla.application.component.view' );

/**
 * HTML View class for the DonorForce Component
 */
class DonorforceViewDonorforce extends JViewLegacy // <Component-name>View<View-name>
{
    // Overwriting JView display method
	function display($tpl = null)
    {
        JToolBarHelper::title(   JText::_( 'DonorForce' ), 'banners.png' );
		
		$donorForceConfig = simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR.DS.'donorforce.xml');
		$this->donorForceVersion = (string)$donorForceConfig->version;
		
		// Assign data to the view
		$this->msg = 'Welcome To the Component\'s main Interface.';
		// Display the view
        parent::display($tpl);
		JToolBarHelper::preferences('com_donorforce');
    }
}



?>