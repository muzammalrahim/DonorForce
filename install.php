<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @version $Id: com_donorforce.php 599 2015-04-20 23:26:33Z brent $
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file'); 

/**
 */
class pkg_donorforceInstallerScript
{
        /**
         * method to install the component
         *
         * @return void
         */
        public function install($parent) 
        {
        
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
        public function uninstall($parent)
        {
        	
			
        	
        }
 
        /**
         * method to update the component
         *
         * @return void
         */
        public function update($parent) 
        {
               
        }
 
        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         **/
        function preflight($type, $parent)
        {
			
			  
        }
 
        /**
         * method to run after an install/update/uninstall method
         *
         * @return void
         **/
        public function postflight($type, $parent) 
        {	
			
                $db = JFactory::getDBO();
                $columns = $db->getTableColumns('#__donorforce_donor');
		$columns7 = $db->getTableColumns('#__donorforce_invoice_temp');
		$columns8 = $db->getTableColumns('#__donorforce_donornotes');
                if(!isset($columns['note_title'])){ 
			$alterQuery3 = "ALTER TABLE `#__donorforce_donor` ADD `note_title` text NOT NULL"; 
			$db->setQuery($alterQuery3);
			$db->execute();
	
		}
                if(!isset($columns['vat_number'])){ 
			$alterQuery4 = "ALTER TABLE `#__donorforce_donor` ADD `vat_number` text NOT NULL"; 
			$db->setQuery($alterQuery4);
			$db->execute();
	
		}
		if(!isset($columns8['date'])){ 
			$alterQuery36 = "ALTER TABLE `#__donorforce_donornotes` ADD `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `notes`;"; 
			$db->setQuery($alterQuery36);
			$db->execute();
			
		}
		if(!isset($columns8['date_modified'])){ 
			$alterQuery37 = "ALTER TABLE `#__donorforce_donornotes` ADD `date_modified` text AFTER `date`;"; 
			$db->setQuery($alterQuery37);
			$db->execute();
			
		}
		if(!isset($columns7['pobox'])){ 
   			$alterQuery38 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `pobox` varchar(255) NOT NULL";
			$db->setQuery($alterQuery38 );
			$db->execute();  			
		}
	 
					//$app = JFactory::getApplication();
					//$url  = JRoute::_('index.php?option=com_donorforce&view=donor&layout=installtest', false);
                                        //$app->redirect($url);
                                        $parent->getParent()->setRedirectURL('index.php?option=com_donorforce&view=donor&layout=installtest');
			
			
			
			
			
        }
}
