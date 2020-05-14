<?php
/*
 * @package   Joomla.Framework
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$document = JFactory::getDocument();

jimport('joomla.filesystem.file'); 
$document->addScript( JURI::root(true)."components/com_donorforce/assets/jquery.table2excel.js" );


$app    = JFactory::getApplication();
$user   = JFactory::getUser();
$userId   = $user->get('id');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_donorforce.donors');
$saveOrder  = $listOrder=='a.donor_id';

?>
<script type="text/javascript">

Joomla.submitbutton = function(task)
 { 
  if ( (task == 'reports.export')&& (document.getElementsByName('task')[0].value=='')){
        Joomla.submitform(task, document.getElementById('adminForm')); 
  }else if ( (task == 'reports.exportuser')&& (document.getElementsByName('task')[0].value=='')){
        Joomla.submitform(task, document.getElementById('adminForm')); 
  }  
    document.getElementsByName('task')[0].value=''
 }

</script>
<!-- <button class="test-button">Export</button> -->

  <!--Tabs  --> 
   <form action="<?php echo JRoute::_('index.php?option=com_donorforce&view=reports'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" >  
    
  <?php $active_tab = JRequest::getVar('Tab'); 
     if($active_tab){ 
    echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => $active_tab));    
     }
     else{ echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'search'));  }
   ?>
   <!-- Tab Search  -->
  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'search', JText::_('Search', true)); ?>  
    <div class="row-fluid">
     <div class="tab-content">                                
        <!-- Filters -->
        <div id="filter-bar" class="btn-toolbar">
         <div class="block_content">
         <select data-placeholder="Choose a Donor"  name="donor_list" id="donor_list" class="chosen-select">
          <option value=""></option>
           <?php  if(!empty($this->Donor_list)):
           foreach ($this->Donor_list as $Donor_list) : ?>
              <option value="<?php if(!empty($Donor_list->donor_id)) echo $Donor_list->donor_id ; ?>" <?php if($this->donor_id == $Donor_list->donor_id){  echo "selected='selected'"; }  ?> ><?php if(!empty($Donor_list->name_first)){ echo $Donor_list->name_first;} if(!empty($Donor_list->name_last)) { echo " ".$Donor_list->name_last; }?></option>
           <?php endforeach; endif; ?>
         </select>
         </div>
      <div class="block_content">
        <select data-placeholder="Choose a Project"  style="padding:25px;" name="project_list" id="project_list"  class=" chosen-select"> 
          <option value=""></option>
           <?php  if(!empty($this->Project_list)):
            foreach ($this->Project_list as $Project_list) : ?>
              <option value="<?php if(!empty($Project_list->project_id)) echo $Project_list->project_id; ?>" <?php if($this->project_id == $Project_list->project_id){  echo "selected='selected'"; }?>><?php  if(!empty($Project_list->name)) echo $Project_list->name;?></option>
           <?php endforeach; endif;  ?>
         </select>
      </div>  
        <div class="block_content">
      <select data-placeholder="Status"  style="padding:25px;" name="donation_status" id="donation_status"  class=" chosen-select"> 
          <option value="">Select status</option>
          <option <?php if($this->donation_status == "pending"){ echo "selected='selected'"; } ?> value="pending">Pending</option>
          <option <?php if($this->donation_status == "successful"){ echo "selected='selected'"; } ?> value="successful">Successful</option>
         </select>
      </div>
      
     
      
      <div class="block_content">
        <label id="jform_search_datefrom-lbl" for="search_datefrom" class="">Select Date From </label> 
        <?php echo JHTML::calendar($this->searchDateFrom,'search_datefrom', 'search_datefrom', '%Y-%m-%d',
          array('size'=>'4','maxlength'=>'5','class'=>' '));?>
      </div>
      <div class="block_content">
        <label id="jform_search_dateto-lbl" for="search_dateto" class="">Select Date To </label> 
        <?php echo JHTML::calendar($this->searchDateTo,'search_dateto', 'search_dateto', '%Y-%m-%d',
          array('size'=>'4','maxlength'=>'5','class'=>' '));?>                    
      </div>
      
      <div class="block_content">
      <div class="btn-group pull-leftX hidden-phone">
        
            <button onclick="document.adminForm.Tab.value='search';this.form.submit();"  class="btn tip" type="submit" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
          
            
            <button class="btn tip" type="button" onclick="resetForm();this.form.submit();" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
        
        </div> 
       </div>        
    </div>
   <!-- Filters End -->        
        <div style="clear:both;"></div>      
            <div class="form-horizontal">
              <table class="table table-striped" id="HistoryTable">
                <thead>
                <tr>
                <th><?php echo JHtml::_('grid.sort', 'ID', 'dh.donor_history_id', $listDirn, $listOrder); ?></th>
                <th>Donor<?php //echo JHtml::_('grid.sort', 'Donor', 'dh.donor_id', $listDirn, $listOrder); ?></th>
                <th width="10%" ><?php echo JHtml::_('grid.sort', 'Date', 'dh.date', $listDirn, $listOrder); ?></th>
                <th><?php echo JHtml::_('grid.sort', 'Project', 'dh.project_id', $listDirn, $listOrder); ?></th>
                <th><?php echo JHtml::_('grid.sort', 'Reference', 'dh.Reference', $listDirn, $listOrder); ?></th>
                <th width="100"><?php echo JHtml::_('grid.sort', 'Amount', 'dh.amount', $listDirn, $listOrder); ?></th>
                <th><?php echo JHtml::_('grid.sort', 'Donation Status', 'dh.status', $listDirn, $listOrder); ?></th>
                </tr>
                </thead>
                <tbody>
         <?php  //echo "<pre>";  print_r(  $this->history[0] );                  
                 if(!empty($this->history))
          //echo "<pre> history = "; print_r($this->history); 
                 //$counter = 0; $number = 0;
                 foreach($this->history as $history)
                 { 
                  ?>
                  <tr>
                  <td><?php if(!empty($history->donor_history_id))echo $history->donor_history_id; ?> </td>
                  <td><?php //if(!empty($history->name_title))echo $history->name_title." ";  
                if(!empty($history->name_first))echo $history->name_first." "; 
                if(!empty($history->name_last))echo $history->name_last." ";
              ?> </td>
                  <td><?php if(!empty($history->date))echo date('Y-m-d',strtotime($history->date)); ?></td>
                  <td><?php if(!empty($history->project_name))echo $history->project_name; ?></td>
                  <td><?php if(!empty($history->Reference))echo $history->Reference; ?></td>
                  <td><?php if(!empty($history->amount)) echo DonorforceHelper::getCurrency().' '.number_format($history->amount, 2, '.', ',');
                              /*  $total_donation +=  $history->amount;*/  ?></td>
                  <td><?php echo ucwords($history->status); ?></td>                      
                  </tr>                              
                  <?php //$counter++;  
          } ?>
               </tbody>               
            </table>  
              <?php echo $this->pagination->getListFooter(); ?>                  
             </div>
           <?php /*?> <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="" />
            <input type="hidden" name="filter_order_Dir" value="" />
            <input type="hidden" name="SubmitForm" value="Search" />
       <?php echo JHtml::_('form.token'); ?>
            </form> <?php */?>
        </div>
      </div>
        
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
  <!--  Tab Search End -->    
  <!-- Tab Reports Display -->
  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'reports_display', JText::_('Display Reports', true)); ?>   
     <div class="row-fluid">
      <div class="tab-content">                          
       <!-- Reports Filters -->
       <div id="" class="btn-toolbar">
      <?php  
    $DonationDateFrom = ''; $DonationDateTo = ''; 
    if(!empty( $this->DonationDateFrom )) $DonationDateFrom = $this->DonationDateFrom; 
    if(!empty( $this->DonationDateTo )) $DonationDateTo = $this->DonationDateTo; 
    ?>
    <div class="block_content">
         <label id="jform_donor_id-lbl" for="datefrom" class="">Select Date From </label> 
      <?php echo JHTML::calendar($DonationDateFrom,'datefrom', 'datefrom', '%Y-%m-%d',
          array('size'=>'8','maxlength'=>'10','class'=>' '));?> 
    </div>
    <div class="block_content">
        <label id="jform_donor_id-lbl" for="dateto" class="">Select Date To</label>                
        <?php echo JHTML::calendar($DonationDateTo,'dateto', 'dateto', '%Y-%m-%d',
          array('size'=>'8','maxlength'=>'10','class'=>' ')); ?>
    </div>
    <div class="block_content" style="width: 220px;">
       <label id="jform_donor_id-lbl" for="displaycategory" class=""> Display Categories </label>        
      <select   style="padding:25px;" name="displaycategory" id="displaycategory"  class="chosen-select">
        <option value="project" <?php if($this->displaycategory == 'project') echo "selected='selected'";?>>project</option>
        <option  value="donor" <?php if($this->displaycategory == 'donor') echo "selected='selected'";?> >donor</option>
        <option  value="donor_lastdonation" <?php if($this->displaycategory == 'donor_lastdonation') echo "selected='selected'";?> >donor -last donation</option>
        <option  value="mail_only" <?php if($this->displaycategory == 'mail_only') echo "selected='selected'";?> >Mail only</option>
        <option  value="not_mail_only" <?php if($this->displaycategory == 'not_mail_only') echo "selected='selected'";?> >Not mail only</option>
      </select>
     </div>

   <div class="block_content" style="width: 220px;">
       <label id="jform_donor_id-lbl" for="Project_list" class=""> Project Data </label>        
      <select   style="padding:25px;" name="displayproject" id="displayproject"  class="chosen-select">
        <option value="Project ID and project Name" >Project ID and project Name</option>
        <?php foreach ($this->Project_list as $Project_list) : ?>
        <option value="<?php if(!empty($Project_list->project_id)) echo $Project_list->project_id; ?>" <?php if($this->displayproject == $Project_list->project_id)  { echo "selected='selected'"; } ?> ><?php  if(!empty($Project_list->name)) echo $Project_list->project_id; echo " ";echo $Project_list->name;?>
        <?php endforeach;?></option>
      </select>
     </div>
       
     <div class="block_content" style="margin-top: 23px;">
        
        <div class="btn-group  hidden-phone">
            <button onclick="document.adminForm.Tab.value='reports_display'; document.adminForm.TotalDonationlimitstart.value='0'; this.form.submit();"  class="btn tip" type="submit" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button class="btn tip" type="button" onclick="resetDisplay();this.form.submit();" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
        </div> 
       </div>
       
       <!-- Limit -->
        <div class="block_content" style="margin-top: 23px;">  
          <div class="btn-group  hidden-phone">
             <select <?php echo ($this->displaycategory == 'donor_lastdonation')? 'disabled': ''?> style="padding:25px;max-width:60px;" name="select_limit" id="select_limit">
                  <option value="5"  <?php echo ($this->select_limit == 5)?  "selected='selected'":'' ?>>5</option>
                  <option value="10" <?php echo ($this->select_limit == 10)? "selected='selected'":'' ?>>10</option>
                  <option value="15" <?php echo ($this->select_limit == 15)? "selected='selected'":'' ?>>15</option>
                  <option value="20" <?php echo ($this->select_limit == 20)? "selected='selected'":'' ?>>20</option>
                  <option value="25" <?php echo ($this->select_limit == 25)? "selected='selected'":'' ?>>25</option>
                  <option value="30" <?php echo ($this->select_limit == 30)? "selected='selected'":'' ?>>30</option>
                  <option value="50" <?php echo ($this->select_limit == 50)? "selected='selected'":'' ?>>50</option>
                  <option value="100"<?php echo ($this->select_limit == 100)?"selected='selected'":'' ?>>100</option>
                  <option value="0"  <?php echo ($this->select_limit == 0)?  "selected='selected'":'' ?>>All</option>       
           </select>
          </div> 
        </div>
       <!-- Limit end  -->
      </div>
      <!-- Reports Filters End -->
        <div class="form-horizontal">
              <table class="table table-striped" id="TotalDonationList">
                <thead>
                <tr>
                  <?php if($this->displaycategory == 'donor_lastdonation')  $this->displaycategory = 'donor';  ?>
                
                  <th>First Name</th>
                  <th>Sur Name</th>
                  <th>Status</th>
                  <th>Telephone NO.</th>
                  <th>Moible No.</th>
                  <?php if($this->displaycategory == 'donor' || $this->displaycategory == 'donor_lastdonation' || $this->displaycategory == 'mail_only' || $this->displaycategory == 'not_mail_only'  ){ echo '<th>Last Donation Date</th>';}?>                 
                  
                </tr>
                </thead>
                <tbody>
         <?php  //echo "<pre>";  print_r(  $this->history[0] );                  
                 if(!empty($this->TotalDonationList)){
          //echo "<pre> history = "; print_r($this->history); 
                 //$counter = 0; $number = 0;
                 $number_counter = 0; 
                 foreach($this->TotalDonationList as $TotalDonation)
                 { 

                  

                  $number_counter++; 
                  ?>
                  <tr>
                               
                
                  <?php if(!empty($TotalDonation->Name)):
                            $edit_link = ''; 
                            if($this->displaycategory == 'project'): 
                              $edit_link = JURI::base().'index.php?option=com_donorforce&view=project&layout=edit&project_id='.$TotalDonation->ID;
                            elseif($this->displaycategory == 'donor' || $this->displaycategory == 'donor_lastdonation'):
                              $edit_link = JURI::base().'index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$TotalDonation->ID;
                           endif;?>

                        
                  <?php endif;?>
                
                  
                  <?php if($this->displaycategory == 'donor' || $this->displaycategory == 'donor_lastdonation' || $this->displaycategory == 'mail_only' || $this->displaycategory == 'not_mail_only'){ 
                    
                   // echo "<br /> Donor_list = "; print_r($Donor_list);     
                  $name_array = explode(' ', $TotalDonation->Name); 
                    echo '<td>'; 
                         
                         if(!empty($TotalDonation->Donation_LastDate)) ?>
                         <a href="<?php echo $edit_link;?>">    <?php echo $name_array[0] ?></a><?php
                    echo '</td>';

                    echo '<td>';

                             echo $name_array[1]; 

                    echo '</td>';

                    echo '<td>' ;

                      echo $TotalDonation->Status;

                    echo '</td>';

                    echo '<td>' ;

                      echo $TotalDonation->tel;

                    echo '</td>';

                    echo '<td>' ;

                      echo $TotalDonation->mob;

                    echo '</td>';

                    echo '<td>' ;

                      echo $TotalDonation->Donation_LastDate;

                    echo '</td>';
                  }?>  
                   
                  
                                 
                  </tr>                              
                  <?php //$counter++;  
          } 
          
         }else{ echo "<tr> <td colspan='3' style='color:red; '> No Data To Display </td> </tr>";  }  ?>
            
               </tbody> 
               </table> 
               </table>
                <?php 
        if(!empty($this->OverallDonations)){
          echo "<h3 class='highlight'> Overall Donations = ". DonorforceHelper::getCurrency().' '.number_format($this->OverallDonations, 2, '.', ' '). " </h3>";
        } ?>  
        
         <?php 
              echo $this->Donation_pagination->getListFooter(); ?>                   
          </div>
        </div>
      </div>
            
       
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <!-- This section of code is to add the new tab of Project Association -->

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'project_association', JText::_('Project Associations', true)); ?>   
     <div class="row-fluid">
      <div class="tab-content">                          
       <!-- Reports Filters -->
       <div id="" class="btn-toolbar">
      <?php  
    $DonationDateFrom_mail = ''; $DonationDateTo_mail = ''; $displaycontactall_mail = '';
    if(!empty( $this->DonationDateFrom_mail ) && $this->DonationDateFrom_mail > 0) $DonationDateFrom_mail = $this->DonationDateFrom_mail; 
    if(!empty( $this->DonationDateTo_mail ) && $this->DonationDateTo_mail > 0) $DonationDateTo_mail = $this->DonationDateTo_mail; 
    ?>

    <div class="block_content">
      <label id="jform_donor_mail" for="dateto_mail" class="">Select Status</label>
      <select data-placeholder="Status"  style="padding:25px;" name="displaydonation_status" id="donation_status"  class=" chosen-select"> 
          <option value="">Select status</option>
          <option <?php if($this->displaydonation_status == "active"){ echo "selected='selected'"; } ?>  value="active">Active</option>
          <option <?php if($this->displaydonation_status == "not_active"){ echo "selected='selected'"; } ?>  value="not_active">Not Active</option>
          <option <?php if($this->displaydonation_status == "dormant"){ echo "selected='selected'"; } ?>  value="dormant">Dormant</option>
          <option <?php if($this->displaydonation_status == "Missionary Supporter"){ echo "selected='selected'"; } ?>  value="Missionary Supporter">Missionary Supporter</option>
          <option <?php if($this->displaydonation_status == "Repeat Donor"){ echo "selected='selected'"; } ?>  value="Repeat Donor">Repeat Donor</option>
          <option <?php if($this->displaydonation_status == "Direct to Missionary"){ echo "selected='selected'"; } ?>  value="Direct to Missionary">Direct to Missionary</option>
          <option <?php if($this->displaydonation_status == "Never Given"){ echo "selected='selected'"; } ?>  value="Never Given">Never Given</option>
          <option <?php if($this->displaydonation_status == "First Gift"){ echo "selected='selected'"; } ?>  value="First Gift">First Gift</option>
          <option <?php if($this->displaydonation_status == "Donors in Kind"){ echo "selected='selected'"; } ?>  value="Donors in Kind">Donors in Kind</option>
          <option <?php if($this->displaydonation_status == "Prayer Partner"){ echo "selected='selected'"; } ?>  value="Prayer Partner">Prayer Partner</option>
          <option <?php if($this->displaydonation_status == "Deceased"){ echo "selected='selected'"; } ?>  value="Deceased">Deceased</option>
          <option <?php if($this->displaydonation_status == "Immigrated"){ echo "selected='selected'"; } ?>  value="Immigrated">Immigrated</option>
          <option <?php if($this->displaydonation_status == "Archived"){ echo "selected='selected'"; } ?>  value="Archived">Archived</option>
          <option <?php if($this->displaydonation_status == "pending"){ echo "selected='selected'"; } ?> value="pending">Pending</option>
          <option <?php if($this->displaydonation_status == "successful"){ echo "selected='selected'"; } ?> value="successful">Successful</option>
         </select>
    </div>
    

      <div class="block_content" style="width: 220px;">
       <label id="jform_donor_mail" for="Project_list_mail" class=""> Project Data </label>        
      <select   style="padding:25px;" name="displayproject_mail" id="displayproject_mail"  class="chosen-select">
        <option value="">All projects</option>
        <?php foreach ($this->Project_list as $Project_list) : ?>
        <option value="<?php if(!empty($Project_list->project_id)) echo $Project_list->project_id; ?>" <?php if($this->displayproject_mail == $Project_list->project_id)  { echo "selected='selected'"; } ?> ><?php  if(!empty($Project_list->name)) echo $Project_list->project_id; echo " ";echo $Project_list->name;?>
        <?php endforeach;?></option>
      </select>
     </div>
     <div class="block_content" style="width: 220px;">
        <label id="jform_donor_mail" for="displaycategory_mail" class=""> Project Associations </label>        
        <select   style="padding:25px;" name="displaycategory_mail" id="displaycategory_mail"  class="chosen-select">
          <option selected="selected" value=""> All donors </option>
          <option <?php if($this->displaycontact == 1)  { echo "selected='selected'"; } ?> value="1"> E-mail only </option>
          <option <?php if($this->displaycontact == 2)  { echo "selected='selected'"; } ?> value="2"> Mail only </option>
        </select>
     </div>

<!--This section f code will add a new a dropdown right after the project association one.--> 

         <div class="block_content" style="width: 220px;">
        <label id="jform_donor_mail" for="displaymembership" class=""> Project Associations </label>        
        <select   style="padding:25px;" name="displaymembership" id="displaymembership"  class="chosen-select">
          <option selected="selected" value=""> All donors </option>
          <option <?php if($this->displaymembership == 1)  { echo "selected='selected'"; } ?> value="1"> Member </option>
          <option <?php if($this->displaymembership == 2)  { echo "selected='selected'"; } ?> value="0"> Non-member </option>
        </select>
     </div>
      <div class="block_content">
         <label id="jform_donor_mail" for="datefrom_mail" class="">Select Date From </label> 
      <?php echo JHTML::calendar($DonationDateFrom_mail,'datefrom_mail', 'datefrom_mail', '%Y-%m-%d',
          array('size'=>'8','maxlength'=>'10','class'=>' '));?> 
    </div>
    <div class="block_content">
        <label id="jform_donor_mail" for="dateto_mail" class="">Select Date To</label>                
        <?php echo JHTML::calendar($DonationDateTo_mail,'dateto_mail', 'dateto_mail', '%Y-%m-%d',
          array('size'=>'8','maxlength'=>'10','class'=>' ')); ?>
    </div>
      <div class="block_content" style="margin-top: 23px;"> 
        <div class="btn-group  hidden-phone">
            <button onclick="document.adminForm.Tab.value='project_association'; document.adminForm.TotalDonationlimitstart.value='0'; this.form.submit();"  class="btn tip" type="submit" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button class="btn tip" type="button" onclick="resetDisplay();this.form.submit();" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
        </div> 
      </div>
       
        <div class="block_content" style="margin-top: 23px;">  
          <div class="btn-group  hidden-phone">
             <select <?php echo ($this->displaycategory == 'donor_lastdonation')? 'disabled': ''?> style="padding:25px;max-width:60px;" name="select_limit_mail" id="select_limit_mail">
                  <option value="5"  <?php echo ($this->select_limit_mail == 5)?  "selected='selected'":'' ?>>5</option>
                  <option value="10" <?php echo ($this->select_limit_mail == 10)? "selected='selected'":'' ?>>10</option>
                  <option value="15" <?php echo ($this->select_limit_mail == 15)? "selected='selected'":'' ?>>15</option>
                  <option value="20" <?php echo ($this->select_limit_mail == 20)? "selected='selected'":'' ?>>20</option>
                  <option value="25" <?php echo ($this->select_limit_mail == 25)? "selected='selected'":'' ?>>25</option>
                  <option value="30" <?php echo ($this->select_limit_mail == 30)? "selected='selected'":'' ?>>30</option>
                  <option value="50" <?php echo ($this->select_limit_mail == 50)? "selected='selected'":'' ?>>50</option>
                  <option value="100"<?php echo ($this->select_limit_mail == 100)?"selected='selected'":'' ?>>100</option>
                  <option value="0"  <?php echo ($this->select_limit_mail == 0)?  "selected='selected'":'' ?>>All</option>       
           </select>
          </div> 
        </div>

      </div>

        <div class="form-horizontal">
              <table class="table table-striped noExl" id="TotalDonationList_mail">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>First Name</th>
                    <th>Sur Name</th>
                    <th>Postal Address</th>
                    <th>City</th>
                    <th>Zip/Postal Code</th>
                    <th>Country Code</th>
                    <th>Country</th>    
                  </tr>
                </thead>
                <tbody>
                  <?php 
                     //echo "<pre>";print_r($this->TotalDonationList_mail);echo "</pre>";             
                     if(!empty($this->TotalDonationList_mail)){
                    
                     $number_counter = 0; 
                     foreach($this->TotalDonationList_mail as $TotalDonation_mail)
                     { 

                      $number_counter++; 
                      ?>
                      <tr>
                                   
                    
                      <?php if(!empty($TotalDonation_mail->Name)):
                                $edit_link = ''; 
                                if($this->displaycontact_mail == 1): 
                                  $edit_link = JURI::base().'index.php?option=com_donorforce&view=project&layout=edit&project_id='.$displaycontact_mail;
                                elseif($this->displaycontact_mail == 2):
                                  $edit_link = JURI::base().'index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$displaycontact_mail;
                                elseif($this->displaycontact_mail == 0):
                                  $edit_link = JURI::base().'index.php?option=com_donorforce&view=donor&layout=edit&donor_id='.$displaycontact_mail;
                               endif;?>

                      <?php endif;?>
                    
                      
                      <?php if($this->displaycontact ==1 || $this->displaycontact ==0 || $this->displaycontact ==2 || $this->displaycontact == 3){ 
                         
                          echo '<td>' ;

                          echo $TotalDonation_mail->name_title;

                        echo '</td>';

                            $name_array_mail = explode(' ', $TotalDonation_mail->Name); 
                        echo '<td>'; 
                             
                              ?>

                            <?php echo $name_array_mail[0]; ?>
                             <?php
                        echo '</td>';

                        echo '<td>';
                                  
                                 echo $name_array_mail[1]; 

                        echo '</td>';

                        echo '<td>' ;

                          echo $TotalDonation_mail->post_address;

                        echo '</td>';

                        echo '<td>' ;

                          echo $TotalDonation_mail->post_city;

                        echo '</td>';

                        echo '<td>' ;

                          echo $TotalDonation_mail->post_zip;

                        echo '</td>';

                        echo '<td>' ;

                          echo $TotalDonation_mail->post_country;

                        echo '</td>';

                        echo '<td>' ;

                          echo $TotalDonation_mail->country_name;

                        echo '</td>';

                       
                      }?>  
                                      
                      </tr>              
                                                   
                      <?php //$counter++;  
              } 
          
         }else{ echo "<tr> <td colspan='12' style='color:red; '> No Data To Display </td> </tr>";  }  ?>
            
               </tbody> 
               </table>
                
        
         <?php 
              echo $this->Donation_pagination->getListFooter(); ?>                   
          </div>
        </div>
      </div>     
       
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        
  
  
        
        
   <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <input type="hidden" name="SubmitForm" value="Display" />
    <input type="hidden" name="Tab" value="<?php echo $this->Tab;?>" />
    <?php echo JHtml::_('form.token'); ?>                
  <!-- Tabs End -->   
       
 


<script type="text/javascript">
  
  function resetForm(){
    //document.getElementById('donor_list')
    jQuery("#donor_list option:selected").attr("selected", false);
    jQuery("#project_list option:selected").attr("selected", false);
    jQuery("#donation_status option:selected").attr("selected", false);
    
    jQuery("#search_datefrom").val('');
    jQuery("#search_dateto").val('');
    
  }
  
  function resetDisplay(){
    jQuery("#datefrom").val('');
    jQuery("#dateto").val('');    
  }
  
   jQuery(document).ready(function(e) { 
    
    jQuery("#Export_NoDonation").click(function(event){  event.preventDefault();
      console.log("Export no Doantion clicked ");
      var datefrom = jQuery("#datefrom").val();
      console.log('datefrom = '+datefrom);
      var dateto = jQuery("#dateto").val();
      console.log('dateto = '+dateto);
      
      //index.php?option=com_donorforce&task=ajax.export_donor_nodonation&format=raw
      window.location.href = "index.php?option=com_donorforce&task=ajax.export_donor_nodonation&datefrom="+datefrom+"&dateto="+dateto+"&format=raw";
      
      });
    

    jQuery(".pagination-list li").click(function() {
      console.log('paginaitno click ');
      var id = jQuery("#myTabContent div.tab-pane.active").attr('id');
      console.log(' id =  '+id);
      document.adminForm.Tab.value='search'; 
      
    }); 
    
    jQuery("#myTabTabs li").click(function(){
       console.log(" tab clicked ");
       var tab = jQuery(this).find('a').attr('href').replace('#',"");  
       jQuery('input[name="Tab"]').val(tab);
      });
    
  }); 
</script>    
            
<style type="text/css">
tr.calendar-head-row {
    font-size: 13px;
}
.chzn-container-single .chzn-single{     
  height: 26px;
    line-height: 26px;
    font-weight: bold;
    color: green; 
  border-radius:0px; 
  width: 220px; 
 }
.btn-group.hidden-phone button{ border-radius:0px !important;   }  
.chzn-single { border-radius:0px !important; height:26px;  }
.block_content{ display:inline-block; vertical-align: top; padding: 4px; }
.btn-toolbar{ margin-left: 10px; }
.highlight{ color: green; padding:10px 0px; }
#TotalDonationList thead th { text-transform: capitalize; color: #08c; font-weight: bolder;}
.align_right{ text-align:right !important;  }
#Export_NoDonation{ background-color: #e6e6e6;padding: 4px 10px; border-radius: 10px; font-weight: bold;font-size: 13px; }
#select_limit_chzn{ max-width: 60px !important; }

#search_datefrom, #search_dateto { max-width: 100px;}
#jform_search_datefrom-lbl, #jform_search_dateto-lbl{ margin-top:-20px; }
#search #filter-bar{ height:auto !important; }

</style> 
<script type="text/javascript">
jQuery(".test-button").on('click', function(e){
  e.preventDefault();
  console.log('test');
  jQuery("#TotalDonationList_mail").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "Project_Association" //do not include extension
  }); 
});

</script>