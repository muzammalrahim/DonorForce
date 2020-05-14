<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');
?>

<style>
.icon
{
	margin-top: 20px;
	margin-left: 20px;
}
.icon-wrapper {
	width: 280px;
	margin: 10px;
	background-color: #f3f3f3;
	padding: 10px;
	color: #fff;
	border: 1px solid #fff;
	border-radius: 5px;
	height:80px;
	border-bottom: 1px solid #5dbb46;
}
.icon-wrapper:hover {
	-moz-box-shadow: 0 0 5px rgba(0,0,0,0.5);
	-webkit-box-shadow: 0 0 5px rgba(0,0,0,0.5);
	box-shadow: 0 0 5px rgba(0,0,0,0.5);
}
</style>

<div id="cpanel" style="float:left; width:90%;">
  
   <?php
	if( !(JFolder::exists(JPATH_LIBRARIES.'/dompdf')) || !(JFolder::exists(JPATH_LIBRARIES.'/phpexcel'))  ){  ?>		
    <div class="" style="padding: 20px; color: red; font-size: 20px;">
    <p><span class="icon-warning"></span>  
    		Dompdf or Phpexcel Library Missing 
    		<a href="index.php?option=com_donorforce&view=donor&layout=installtest">Click here to install </a> 
    </p>
    </div>
  <?php 
	}
	?>
  
  <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=donors"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/donors.png" />
            <span>Donors</span>
        </a> 
    </div>
  </div>
  
  <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=projectcategories"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/categories.png" />
            <span>Project Categories</span>
        </a> 
    </div>
  </div>
  
  <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=projects"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/project.png" />
            <span>Projects</span>
        </a> 
    </div>
  </div>
  
  <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=donations"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/donations.png" />
            <span>Debit Order Form</span>
        </a> 
    </div>
  </div>
  
  <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=bequests"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/donors.png" />
            <span>Bequests</span>
        </a> 
    </div>
  </div>
  
  
  
  
   <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=adddonation"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/adddonation.png" />
            <span>Add Donations</span>
        </a> 
    </div>
  </div>
 
 <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=subscriptions"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/subscription.png" />
            <span>Donation Subscriptions</span>
        </a> 
    </div>
  </div>
   <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=template&layout=template_genrate"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/templates.png" />
            <span>Manage your Templates </span>
        </a> 
    </div>
  </div>
  
  <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=reports"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/reports.png" />
            <span>Reports</span>
        </a> 
    </div>
  </div>
  
  
  <div class="icon-wrapper">
    <div class="icon"> 
    	<a href="index.php?option=com_donorforce&view=management"> 
        	<img alt="" width="48" height="48"  src="<?php echo JURI::Root() ?>administrator/components/com_donorforce/assets/images/reports.png" />
            <span>Donation Management(Importer)</span>
        </a> 
    </div>
  </div>


  
  </div>
<div style="clear:both;"></div>

    <div align="right">
        Powered by Netwise Multimedia <br />
        Version <?php echo $this->donorForceVersion; ?>
    </div>