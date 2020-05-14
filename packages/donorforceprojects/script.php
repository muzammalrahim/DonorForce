<?php
/**
 * @package Component Donor Force for Joomla! 3
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
 * Script file of DonorForce component
 */
class PlgContentDonorForceProjectsInstallerScript
{
        /**
         * method to install the component
         *
         * @return void
         */
        function install($parent) 
        {
			
			//$installer = new JInstaller;
		
        	/*if($installer->install(dirname(__FILE__).'/admin/plugins/plg_donorforceprojects')){
           	 echo 'Plugin install success', '<br />';
        	} else{
          		echo 'Plugin install failed', '<br />';
        	}
			*/
				// Install the packages
				//$parent->install($parent->getParent()->getPath('source').'/admin/plugins/plg_donorforceprojects/');
			    // $parent is the class calling this method
               // $parent->getParent()->setRedirectURL('index.php?option=com_helloworld');
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
        function uninstall($parent){
			
        }
		
		function preflight( $type, $parent ) {
			if(JFolder::exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'plg_donorforceprojects')){
				
				$db = JFactory::getDBO();
				$query = "DELETE FROM #__extensions WHERE `element` = 'plg_donorforceprojects'";
				$db->setQuery($query);
				$result = $db->execute();
				if($result){
					JFolder::delete(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'plg_donorforceprojects')	;
				}
				/*Jerror::raiseWarning(null, 'folder exists');
				return false;*/
			}
		} 
 		
		function postflight($type, $parent) 
		{
			//if($type == 'install'){
				$db = JFactory::getDBO();
				$query = "UPDATE #__extensions SET `enabled` = 1 WHERE `element` = 'donorforceprojects'";
				$db->setQuery($query);
				$db->execute();		
			//}				
		}
 
 
}