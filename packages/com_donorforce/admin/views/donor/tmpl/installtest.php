<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

		
		echo '<div class="alert alert-info j-jed-message">
				<h3>Payment plugins must be installed before using. Click <a href="index.php?option=com_plugins&view=plugins&filter_folder=payment" target="_blank">here</a> to enable them.
			</h3></div>
			';
			
		echo '<div id="loading">
					<h2 style="color:green;">Donorforce has been installed succesfully <br /> 
							Please Wait for Installing Custom Libraries (PHPexcell and domPDF) requied by Donorforce</h2>
				  <span id="loading_icon"><img style="max-width:400px; max-height:400px;" src="'.JURI::Root().'administrator/components/com_donorforce/assets/images/loading.gif" /></span>				
		</div>';
		
		echo "<div><h4><span>Progress</span></h4></div>";
		
		require_once JPATH_ADMINISTRATOR . '/components/com_installer/helpers/installer.php';
		JClientHelper::setCredentialsFromRequest('ftp');
		$app = JFactory::getApplication();
		// Load installer plugins for assistance if required:
		JPluginHelper::importPlugin('installer');
		$dispatcher = JEventDispatcher::getInstance();	
		
		/* --------------------------------- dompdf library ---------------------------------*/
		$url = 'http://demo2.netwisedemo2.co.za/DonorForceCustomLibrary/dompdf.zip';
		// Did you give us a URL?
		// Handle updater XML file case:
		if (preg_match('/\.xml\s*$/', $url))
		{
			jimport('joomla.updater.update');
			$update = new JUpdate;
			$update->loadFromXml($url);
			$package_url = trim($update->get('downloadurl', false)->_data);
			if ($package_url)
			{
				$url = $package_url;
			}
			unset($update);
		}
		
		echo "<div><h4><p>Downloading Dompdf library from url = ".$url." </p></h4></div>";
		// Download the package at the URL given.
		$p_file = JInstallerHelper::downloadPackage($url);
		// Was the package downloaded?
		if (!$p_file)
		{
			JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL'));
			return false;
		}
		$config   = JFactory::getConfig();
		$tmp_dest = $config->get('tmp_path');

		echo "<div><h4><p>Unpack the downloaded package file to tmp_path( ".$tmp_dest." ).</p></h4></div>";
		// Unpack the downloaded package file.
		$package = JInstallerHelper::unpack($tmp_dest . '/' . $p_file, true);
		
		
		// Get an installer instance.
		$installer = JInstaller::getInstance();

		echo "<div><h4><p>Installing Dompdf library.</p></h4></div>";
		// Install the package.
		if (!$installer->install($package['dir']))
		{
			// There was an error installing the package.
			$msg = JText::sprintf('COM_INSTALLER_INSTALL_ERROR', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
			$result = false;
			$msgType = 'error';
			echo "<div><h4><p>Error installing Dompdf Library.</p></h4>".$msgType." </div>";
		}
		else
		{
			// Package installed sucessfully.
			$msg = JText::sprintf('COM_INSTALLER_INSTALL_SUCCESS', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
			$result = true;
			$msgType = 'message';
			
			echo "<div><h2><p style='color: green;'>Library Dompdf Installed Succesfully.</p></h2></div>";
		}
		
		
		/* --------------------------------- PhpExcel library ---------------------------------*/
		$url2 = 'http://demo2.netwisedemo2.co.za/DonorForceCustomLibrary/phpexcel.zip';
		// Did you give us a URL?
		// Handle updater XML file case:
		if (preg_match('/\.xml\s*$/', $url2))
		{
			jimport('joomla.updater.update');
			$update2 = new JUpdate;
			$update2->loadFromXml($url2);
			$package_url2 = trim($update2->get('downloadurl', false)->_data);
			if ($package_url2)
			{
				$url2 = $package_url2;
			}
			unset($update2);
		}
		
		echo "<div><h4><p>Downloading PhpExcel library from url = ".$url2." </p></h4></div>";
		// Download the package at the URL given.
		$p_file2 = JInstallerHelper::downloadPackage($url2);
		// Was the package downloaded?
		if (!$p_file2)
		{
			JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL'));
			return false;
		}
		

		echo "<div><h4><p>Unpack the downloaded package file to tmp_path( ".$tmp_dest." ).</p></h4></div>";
		// Unpack the downloaded package file.
		$package2 = JInstallerHelper::unpack($tmp_dest . '/' . $p_file2, true);


		echo "<div><h4><p>Installing PhpExcel library.</p></h4></div>";
		// Install the package.
		if (!$installer->install($package2['dir']))
		{
			// There was an error installing the package.
			$msg = JText::sprintf('COM_INSTALLER_INSTALL_ERROR', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package2['type'])));
			$result = false;
			$msgType = 'error';
			echo "<div><h4><p>Error installing PhpExcel Library.</p></h4>".$msgType." </div>";
		}
		else
		{
			// Package installed sucessfully.
			$msg = JText::sprintf('COM_INSTALLER_INSTALL_SUCCESS', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package2['type'])));
			$result = true;
			$msgType = 'message';
			
			echo "<div><h2><p style='color: green;'>Library PhpExcel Installed Succesfully.</p></h2></div>";
			
		}
		
		echo "<script>jQuery('#loading_icon').remove(); </script>"; 
	
		echo '<div class="alert alert-info j-jed-message">
				Installation Process has been Completed <a style="font-size: 20px;color: red;font-weight: bold;margin: 10px;" href="index.php?option=com_donorforce"> Click Here to view DonorForce </a>.
			</div>
			';
			
	
?>