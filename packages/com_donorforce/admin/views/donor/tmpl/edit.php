<script>
jQuery(document).ready(function() 
  {
    
  });
</script>
<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select.chosen');
//echo " <pre>  donor_query = ";  print_r( $this->item ); echo " </pre> ";  

//JHtml::_('behavior.modal');
$document = JFactory::getDocument();
//token input
$document->addStyleSheet(JURI::root().'/administrator/components/com_donorforce/assets/token-input-facebook.css' );
$document->addScript(JURI::root().'/administrator/components/com_donorforce/assets/jquery.tokeninput.js' );
$total_donation = ''; 
?>
<script type="text/javascript">
  var admin_base_url = '<?php echo JURI::base()?>';
  Joomla.submitbutton = function(task)
  {
    if (task == 'donor.cancel' || document.formvalidator.isValid(document.id('donor-form')))
    {
      <?php //echo $this->form->getField('articletext')->save(); ?>
      Joomla.submitform(task, document.getElementById('donor-form'));
    }
  }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_donorforce&layout=edit&donor_id='.(int) $this->item->donor_id); ?>" method="post" name="adminForm" id="donor-form" class="form-validate" enctype="multipart/form-data">

<div class="row-fluid">
    <!-- Begin Newsfeed -->
    <div class="span10 form-horizontal">
    <fieldset class="adminform">  
            <ul class="nav nav-tabs">     
                <li class="active">            
                 <a href="#details" data-toggle="tab">Donor Details</a>
                </li>
                <li>            
                 <a href="#addresses" data-toggle="tab">Addresses</a>
                </li>
                <li>            
                 <a href="#additional_info" data-toggle="tab">Additional Info</a>
                </li>
                <li>            
                 <a href="#donations" data-toggle="tab">Donations</a>
                </li>
                <li>            
                 <a href="#notes" data-toggle="tab">Notes</a>
                </li>
                
                 <li>            
                 <a href="#email" data-toggle="tab">Emails</a>
                </li>
                
            </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="details">
                      
        <?php     
         foreach($this->form->getFieldset('info') as $field)
         {            
          if ($field->hidden)
          { 
            echo $field->input; 
          }else if($field->id == 'jform_donor_id'){
              
          ?> 
          <div class="control-group">                        
            <div class="control-label"><?php echo $field->label ?></div>
            <div class="controls">
            <input type="text" name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" value="<?php echo 'D'.str_pad($field->value, 5, '0', STR_PAD_LEFT);?>" class="readonly" readonly="" aria-invalid="false">
                <?php //echo $field->input ?>
             </div>                           
           </div>
          <?php     
          }else{
        ?> 
          <div class="control-group">                        
              <div class="control-label">
                  <?php echo $field->label ?>
              </div>
              
              <div class="controls">
                  <?php echo $field->input ?>
              </div>                           
           </div>
       <?php
           }
           if($field->id == 'jform_name_first'){
                     ?>
           <script type="text/javascript">
              jQuery(document).ready(function($){
                $(".autocomplete").tokenInput(admin_base_url + "index.php?option=com_donorforce&task=ajax.searchUserByName&format=raw",{
                  method:"POST",
                  theme:"facebook",
                  <?php if($field->value != ''):?>
                  prePopulate: [{id:$('#jform_name_first').val(), name:$('#jform_name_first').val()}],
                  <?php endif;?>
                  hintText:"Start typing name",
                  resultsLimit: 10,
                  tokenLimit: 1,
                  preventDuplicates: true,
                  onAdd: function(item){
                    $('#jform_name_first').val(item.name);
                    $('#jform_name_first').attr('data-id',item.id);
                    //$('#jform_id').val(item.id);
                    if(item.result == 0){
                      $('#jform_is_hb').val('0');
                      $(".autocomplete").tokenInput("clear");
                    }else{
                      $.ajax({
                        url: admin_base_url + "index.php?option=com_donorforce&task=ajax.getUserInformationById&format=raw",
                        type:"POST",
                        cache:false,
                        data:{item_id:item.id},
                        dataType: ($.browser.msie) ? "text" : "json",
                        accepts: {
                          text: "application/json",
                        },
                        success: function(response){
                          $('#jform_is_hb').val('1');
                          $.each(response,function(idx,ele){
                            if(idx === 'org_type'){
                              if(ele === 'individual'){
                                var va = ele;
                              }else{
                                var va = 'business';
                              }
                              $('#jform_'+idx).val(va);
                            }else{
                              $('#jform_'+idx).val(ele);
                            }
                          });
                          console.log(response);
                        },
                        error: function(response){
                          console.log(response);
                        },
                        complete: function(response){
                          console.log(response);
                        }
                      });
                    }
                  }
                });
              });
                         </script>
           <?php
           }
                 } 
                ?>
      
    
    
               
    
                <fieldset class="adminform">
                    <legend>Login Details</legend>
                    <?php
						
						
						
            if(JRequest::getVar('donor_id')>0)
            {
               
              if($this->item->cms_user_id != 0)
              {
                $this->form->setFieldAttribute( 'cms_user_id', 'type', 'hidden' );
                $this->form->setFieldAttribute( 'username', 'readonly', 'true' );
              }
              foreach($this->form->getFieldset('login_details') as $field) :
                if($field->id == 'jform_cms_user_id' && $this->item->cms_user_id != 0)
                {
                  
                }
                else{
              ?>
                  <div class="control-group"><div class="control-label">
                      <?php echo $field->label. '</div>
                    <div class="controls">'. $field->input; ?>
                  </div></div>     
                <?php }
                
              endforeach;
             
             
            } else {
              foreach($this->form->getFieldset('login_details') as $field) :?>
                        <div class="control-group"><div class="control-label">
                            <?php echo $field->label. '</div>
                          <div class="controls">'. $field->input; ?>
                        </div></div>     
                    <?php endforeach;
              ?>
              
            <?php }
          
           ?>
                    <?php     
                   /*   foreach($this->form->getFieldset('login_details') as $field)
                     { 
                         if ($field->hidden)
                         { 
                             echo $field->input; 
                         }
                         else
                         {
               ?> 
                            <div class="control-group">
                            
                                <div class="control-label">
                                    <?php echo $field->label ?>
                                </div>
                                
                                <div class="controls">
                                    <?php echo $field->input ?>
                                </div>
                                
                            </div>
                            <?php 
                    
              
                         }
                         
                     }  */
           
            /*if(JRequest::getVar('id')>0)
               {?>
                 <div class="control-group"><div class="control-label">
                               Password
                               </div>
                          <div class="controls">
                          <input type="password" name="jform[password]" id="jform_password" value="" autocomplete="off" class="inputbox validate-password" size="30" maxlength="99" >
                          </div></div>
                          
                           <div class="control-group"><div class="control-label">
                               Confirm Password
                               </div>
                          <div class="controls">
                          <input type="password" name="jform[password2]" id="jform_password" value="" autocomplete="off" class="inputbox validate-password" size="30" maxlength="99" >
                          </div></div>
               <?php } else { ?>
                  <div class="control-group"><div class="control-label">
                               Password *
                               </div>
                          <div class="controls">
                          <input type="password" name="jform[password]" id="jform_password" value="" autocomplete="off" class="inputbox " size="30" maxlength="99" >
                          </div></div>
                          
                           <div class="control-group"><div class="control-label">
                              Confirm Password *
                               </div>
                          <div class="controls">
                         <input type="password" name="jform[password2]" id="jform_password2" value="" autocomplete="off" class="inputbox " size="30" maxlength="99" >
                          </div></div>
                 
                <?php }*/
                    ?>
                   
                </fieldset>
          
                </div>
                
                <div class="tab-pane" id="addresses">
                    <fieldset class="adminform">
                        <legend>Physical Address</legend>
                        
                        <?php     
                         foreach($this->form->getFieldset('phy_address') as $field)
                         { 
                             if ($field->hidden)
                             { 
                                 echo $field->input; 
                             }
                             else
                             {
                ?> 
                                <div class="control-group">
                                
                                    <div class="control-label">
                                        <?php echo $field->label ?>
                                    </div>
                                    
                                    <div class="controls">
                                        <?php echo $field->input ?>
                                    </div>
                                    
                                </div>
                                <?php
                             }
                             
                         } 
                        ?>
                        
                    </fieldset>
    
                    <fieldset class="adminform">
                     <div class="control-group"> 
                      <div class="control-label">Same as physical address</div>
                        <div class="controls"><input type="checkbox" name="isSame"  id="isSame" /></div>
                     </div>
                        <legend>Postal Address</legend>
                      
                        <?php     
                         foreach($this->form->getFieldset('postal_address') as $field)
                         { 
                             if ($field->hidden)
                             { 
                                 echo $field->input; 
                             }
                             else
                             {
                ?> 
                                <div class="control-group">
                                
                                    <div class="control-label">
                                        <?php echo $field->label ?>
                                    </div>
                                    
                                    <div class="controls">
                                        <?php echo $field->input ?>
                                    </div>
                                    
                                </div>
                                <?php
                             }
                             
                         } 
                        ?>
                      
                    </fieldset>
                </div>

              <div class="tab-pane" id="additional_info">
                  <fieldset class="adminform">
                    <legend>Additional Info</legend>
                    <?php
                    $com_donorforce_entries = JComponentHelper::getParams('com_donorforce')->get('entries'); 
                    $entries = json_decode($this->item->entries,true); 

                    foreach($com_donorforce_entries->title as $key=>$title){?>

                         <div class="control-group">
                            <div class="control-label"><label readonly><?php echo $title; ?></label></div>
                            <div class="controls">
                              <div class="center dname"><span class="count"></span><input type="text" name="jform[entries][values][]" value="<?php echo $entries['values'][$key]; ?>"></div>
                            </div>
                         </div>
                     <?php 
                     }
                      ?>     
                  </fieldset>
                </div>
                
                <div class="tab-pane" id="donations">
                  <fieldset class="adminform">
                        <legend>Donation Subscription</legend>                       
                        <table class="table table-striped">
                          <thead>
                              <tr>
                                  <th>Project ID</th>
                                    <th>Project Name</th>
                                    <th>Donation Type</th>
                                    <th>Source</th>
                                    <th>Amount</th>
                                    <!--<th>Transaction ID</th>-->
                                </tr>
                            </thead>
                           <tbody>
                            <?php
                   //var_dump($this->subscriptions);                   
                   if(!empty($this->subscriptions))
                   foreach($this->subscriptions as $sub)
                   {
                    ?>
                              <tr>
                                <td>
                        <?php if(!empty($sub->project_id))echo $sub->project_id; ?>
                                 </td>
                                 <td>
                        <?php if(!empty($sub->project_name))echo $sub->project_name; ?>
                                 </td>                                 
                                 <td>
                                 <?php 
                 echo ucwords($sub->donation_type);
                 /* ?>
                                  <select class="subscription_type" id="sub_<?php echo @$sub->subscription_id; ?>" >
                                      <option value="once-off" <?php if($sub->donation_type=='once-off')echo ' selected="selected" '; ?>>
                                        Once-off
                                       </option>
                                       <option value="monthly" <?php if($sub->donation_type=='monthly')echo ' selected="selected" '; ?>>
                                        Monthly
                                       </option>
                                       <option value="six-monthly" <?php if($sub->donation_type=='six-monthly')echo ' selected="selected" '; ?>>
                                        Six Monthly
                                       </option>
                                       <option value="annually" <?php if($sub->donation_type=='annually')echo ' selected="selected" '; ?>>
                                        Annually
                                       </option>
                                       <option value="bequest" <?php if($sub->donation_type=='bequest')echo ' selected="selected" '; ?>>
                                        Bequest
                                       </option>
                                    </select>
                  <?php */ ?>
                                 </td>
                                 <td>


                        <?php if(!empty($sub->source))
                        echo ucwords($sub->source);
                        ?>

                                 </td>
                                 <td>
                        <?php if(!empty($sub->amount))echo  DonorforceHelper::getCurrency().' '.DonorforceHelper::displayAmount($sub->amount); ?>
                                 </td>
                                 
                                 
                                 <td> <?php //if(!empty($sub->transaction_id)) echo $sub->transaction_id;?></td>
                              </tr>                              
                              <?php 
                   }
                   ?>
                            </tbody>
                            
                        </table>
                        
                    </fieldset>
                    <fieldset class="adminform">
                        <legend>Donation History</legend>
                        
                        <table class="table table-striped" id="HistoryTable">
                          <thead>
                              <tr>
                                <th>No</th>
                                <th width="10%">Date</th>
                                <th>Project</th>
                                <th>Reference</th>
                                <th width="15%">Amount</th>
                                <th>Donation Status</th>
                                <th>Reallocate</th>
                                <th>Delete Donation</th>
                                <th style="text-align: center;">Resend Thank You</th>
                                <th style="text-align: center;">Resend Donation Receipt</th>
                              </tr>
                            </thead>
                            <tbody>
                   <?php                   
                   if(!empty($this->history))
                   $counter = 0; $number = 0;
                   foreach($this->history as $i=>$history)
                   {
                    ?>
                              <tr>
                                <td><?php $number++;  echo  $number;  ?></td>
                                <td>
                        <?php if(!empty($history->date))echo date('Y-m-d',strtotime($history->date)); ?>
                                 </td>
                                 <td>
                        <?php if(!empty($history->project_name))echo $history->project_name; ?>
                                 </td>
                                 <td><?php if(!empty($history->Reference))echo $history->Reference; ?></td>
                                 <td><?php if(!empty($history->amount)) echo  DonorforceHelper::getCurrency().' '.number_format($history->amount,2,"."," ");
                 if($history->status !='pending' && $history->status !='Pending' ){
                      $total_donation +=  $history->amount;
                 }?>
                                            
                                            </td>
                                 
                                 <?php 
                 echo '<td id="tr'.$history->donor_history_id.'">';
                

                  if($history->status=='pending')//pending
                  {
                    //var_dump($history);
                                        
                     echo'<select class="history_status" id="hid'.$history->donor_history_id.'" >
                      <option value="pending">Pending</option>
                      <option  value="successful">Successful</option>
                     </select>
                     ';
                  } 
                  else
                  {
                     echo ucwords($history->status); 
                  }
                  echo '</td>';
                  ?>
                <td>                                 
                                     <button onclick="" id="relocate-<?php echo $counter;  ?>" class="btn btn-small relocate">
                                        Reallocate
                                     </button>
                                     <button id="update_donor-<?php echo $counter; ?>" class="btn btn-small update_donor"> Update Record   </button>
                                     <input type="hidden" id="donor_history_id-<?php echo $counter;  ?>" value="<?php echo $history->donor_history_id; ?>" />
                                     <div id="ajaxresult-<?php echo $counter;  ?>">  </div>
                                   </td>
                                   <td style="text-align: center; " >                                 
                                 <?php echo '<a href="index.php?option=com_donorforce&task=Donor.delete_donation&donor_id='.$this->item->donor_id.'&id='.$history->donor_history_id.'">';    
                     echo '<span class="icon-trash" style="font-size:20px;" >  </span> </a>'; ?>                                 
                   </td> 
                   
                   
                   <td style="text-align: center; " >                                 
                      <?php echo '<a href="index.php?option=com_donorforce&task=donor.resend_tankyou&donor_id='.$this->item->donor_id.'&id='.$history->donor_history_id.'">';    
                     echo '<span class="icon-undo" style="font-size:20px;" ></span>
                            <span class="icon-mail" style="font-size:20px;"></span>
                     </a>'; ?>  
                     </td> 
                   <td style="text-align: center; " >                                 
                       <?php echo '<a href="index.php?option=com_donorforce&task=donor.resend_receipt&donor_id='.$this->item->donor_id.'&id='.$history->donor_history_id.'">';    
                     echo '<span class="icon-undo" style="font-size:20px;" ></span>
                            <span class="icon-mail" style="font-size:20px;"></span>
                     </a>'; ?>                                 
                   </td> 
                              </tr>                              
                              <?php 
                  $counter++; }
                   ?>
                            </tbody>                            
                        </table>
                        <?php  //---------------- Pagination ------------------- //
                if(isset($this->pagination_list)){  
                //echo "<pre>"; print_r($this->pagination_list); echo "</pre>";  
                  echo "<div class='paginator'> <span>Pagination </span>";
                  foreach($this->pagination_list['pages'] as $Pages){
                    echo "<td><a class='pag' id='$Pages' >  ".$Pages."</a></td>";
                    }
                   echo "</div>";  
                  }
                //---------------- Pagination End------------------- // ?>
               <script type="text/javascript">
                jQuery(document).ready(function(e) {
                jQuery('.paginator .pag:eq(0)').addClass('page_select');
                jQuery(".paginator .pag").on( "click", function(e) {
                    
                  //-----AJAX call to display list of donors for donation Realocation.    
                     e.preventDefault();  //return false;   
                    
                     var offset = jQuery(this).attr('id');
                     
                     jQuery.ajax({
                       url : 'index.php?option=com_donorforce&task=ajax.DonorHistory&format=raw',
                       type: "GET",
                       data: { donor_id: '<?php echo JRequest::getVar('donor_id'); ?>',
                          offset: offset
                        },
                       success: function(data) {
                       //jQuery('#ajaxresult-'+counter).html( data  );
                       //jQuery("#update_donor-"+counter).show(); 
                      }
                    }).done(function(data){
                      var page = offset - 1; 

                      jQuery('#HistoryTable').html(data);
                      jQuery('.paginator .pag').removeClass('page_select');
                      jQuery('.paginator .pag:eq('+page+')').addClass('page_select');
                      
                     });;//done end           
                  });
                  
                  //-----AJAX call . 
                
                
                
                });
               </script>
                    </fieldset>
                    <?php if(!empty($total_donation)){ ?>
                  <h4>Total Donation = <?php echo DonorforceHelper::getCurrency().' '.number_format($total_donation, 2,'.', ' ');           ?> </h4><?php  } ?>
                  
                  
   <?php /*---------------------------------------------- Gift in Kind --------------------------------------------------------*/?>
                  <fieldset class="adminform">
                      <legend>Gifts in Kind History</legend>                       
                      <table class="table table-striped" id="GiftTable">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Gift</th>
                        <th>Reference</th>
                        <th>Reallocate</th>
                        <th>Delete Gift</th>
                      </tr>
                      </thead>
                      <tbody>
                   <?php                   
                   if(!empty($this->gifts))
                   $gcounter = 0; $gnumber = 0;
                   foreach($this->gifts as $i=>$gift)
                   {
                    ?>
                     <tr>
                      <td><?php $gnumber++;  echo  $gnumber;  ?></td>
                      <td><?php if(!empty($gift->date))echo date('Y-m-d',strtotime($gift->date)); ?></td>
                       <?php /*?> <td><?php if(!empty($gift->project_name))echo $gift->project_name; ?></td><?php */?>
                      <td><?php if(isset($gift->desc)) echo $gift->desc; ?></td>
                      <td><?php if(!empty($gift->reference))echo $gift->reference; ?></td>
                      <?php 
                      /*  echo '<td id="tr'.$gift->gift_id.'">';
                        if(strtolower($gift->status) =='pending' )
                        {                                       
                         echo'<select class="gift_status" id="gid'.$gift->gift_id.'" >
                          <option value="pending">Pending</option>
                          <option  value="successful">Successful</option>
                         </select>
                         ';
                        } 
                        else{ echo ucwords($gift->status); }
                        echo '</td>';*/
                       ?>
                      
                      <td>                                 
                       <button onclick="" id="relocate-<?php echo $gcounter;  ?>" class="btn btn-small gift_relocate">
                          Reallocate
                       </button>
                       <button id="update_gift-<?php echo $gcounter; ?>" class="btn btn-small update_gift">Update Record</button>
                       <input type="hidden" id="gift_id-<?php echo $gcounter;  ?>" value="<?php echo $gift->gift_id; ?>" />
                       <div id="giftajaxresult-<?php echo $gcounter;  ?>"></div>
                      </td>
                       
                      <td style="text-align: center; " >  
                        <a class="delete_gift" data-gift_id="<?php echo $gift->gift_id;?>" data-donor_id="<?php echo $this->item->donor_id;?>">
                        <span class="icon-trash" style="font-size:20px;"></span> 
                        </a>                                                                                        
                      </td>
                       
                     </tr>                              
                  <?php 
                  $gcounter++; }
                   ?>
                   </tbody>         
                   </table>   
                   
                    <?php  //---------------- Pagination ------------------- //
                          if(isset($this->gpagination_list)){  
                            echo "<div class='gpaginator'> <span>Gift Pagination </span>";
                            foreach($this->gpagination_list['pages'] as $GPages){
                              echo "<td><a class='pag' id='$GPages' >  ".$GPages."</a></td>";
                              }
                             echo "</div>";  
                            }
                          //---------------- Pagination End------------------- // ?>
                       <script type="text/javascript">
                            jQuery(document).ready(function(e) {
                            jQuery('.gpaginator .pag:eq(0)').addClass('gpage_select');
                            jQuery(".gpaginator .pag").on( "click", function(e) {
                                
                              //-----AJAX call to display list of donors for donation Realocation.    
                                 e.preventDefault();  //return false;   
                                 var goffset = jQuery(this).attr('id');
                                 jQuery.ajax({
                                   url : 'index.php?option=com_donorforce&task=ajax.DonorGifts&format=raw',
                                   type: "GET",
                                   data: { donor_id: '<?php echo JRequest::getVar('donor_id'); ?>',
                                      offset: goffset
                                    },
                                   success: function(data) {
                                   //jQuery('#ajaxresult-'+counter).html( data  );
                                   //jQuery("#update_donor-"+counter).show(); 
                                  }
                                }).done(function(data){
                                  var gpage = goffset - 1; 
            
                                  jQuery('#GiftTable').html(data);
                                  jQuery('.gpaginator .pag').removeClass('gpage_select');
                                  jQuery('.gpaginator .pag:eq('+gpage+')').addClass('gpage_select');
                                  
                                 });;//done end           
                              });
                              
                              //-----AJAX call . 
                            });
                           </script>    
                          
                          
                                    
                   </fieldset>
                  
          <?php /*---------------------------------------------- Gift in Kind End -----------------------------------------------------*/?>
          </div><!-- Donation Tab end--> 

          <div class="tab-pane" id="notes">
            <fieldset class="adminform">
              <table id="aj3_iproperty_pl_seasons" class="display table table-sm" cellspacing="0" width="100%" style="display: table;">
                <thead style="background: #dedddd;">
                  <tr>
                    <th>Action</th>
                    <th>Title</th>
                    <th>Notes</th>
                    <th>Date Created</th>
                    <th>Date Last Modified</th>
                  </tr>
                </thead>
                <tbody id="div_notes">
                  <tr class="" id="donorRow-<?php echo $this->item->donor_id ?>">
                    <td>
                      <a href="javascript:void(0)" class="editRow donor" data-pkey="<?php echo $this->item->donor_id ?>" data-toggle="modal" data-target="#Edit_newRow"><span class="icon-edit"></span></a>
                    </td>
                    <td>
                      <?php echo $this->item->note_title ?>
                    </td>
                    <td>
                      <?php echo $this->item->notes ?>
                    </td>
                  </tr>
                  <?php
                  $db = JFactory::getDbo();
                  $query = $db->getQuery(true);
                  $query = "SELECT * FROM #__donorforce_donornotes WHERE `donor_id` =".(int) $this->item->donor_id;
                  $db->setQuery($query);
                  $notes = $db->loadObjectList();
                  foreach($notes as $row){
                  ?>
                    <tr class="" id="noteRow-<?php echo $row->id ?>">
                      <td>
                        <a href="javascript:void(0)" class="editRow" data-pkey="<?php echo $row->id ?>" data-toggle="modal" data-target="#Edit_newRow"><span class="icon-edit"></span></a>
                        <a href="javascript:void(0)" class="delete-note" data-pkey="<?php echo $row->id ?>"><span class="icon-delete"></span></a>
                      </td>
                      <td>
                          <?php echo $row->title ?>
                      </td>
                      <td>
                          <?php echo $row->notes ?>
                      </td>
                      <td>
                          <?php echo $row->date ?>
                      </td>
                      <td>
                          <?php echo $row->date_modified ?>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>

              </table>
              <a href="javascript:void(0)" style="border-radius:0;" class="btn btn-small btn-outline-primary" id="newRowButton" data-toggle="modal" data-target="#Add_newRow"><i class="fa fa-plus"></i>Add New</a>
               
            </fieldset>
          </div><!-- Notes tab end -->
                
                
                <!-- Email Tab-->
                 <div class="tab-pane" id="email">
                    <fieldset class="adminform">
                      
                      <legend>Email Lists</legend>
                      
                      <?php if (($this->acymailing == 1) && (JComponentHelper::getComponent('com_acymailing',true)->enabled)){ ?>
                      
                      <div>  
                      <p> Please select the email lists that you would like to which you would like to subscribe this donor.</p>
                        <table class="table table-striped acymailing_bridge">
                          <thead>
                            <tr>
                              <th align="center" style="text-align:center"> Status </th>
                              <th class=""> </th>
                              <th class="" nowrap="nowrap"> List Name </th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php 
                          if(isset($this->acymailing_list)){
                          foreach( $this->acymailing_list as $i => $list ){                           
                            $checked = ''; $default = '';                             
                            if (in_array($list->listid, $this->user_sub)) {  $checked = 'checked="checked"'; }                            
                            if( ($list->listid == $this->default_subscription) && ($this->item->donor_id == '') ){
                               
                                $checked = 'checked="checked"';  
                                $default = ' (Default)'; 
                             }
                                                        
                              echo '<tr><td align="center" style="text-align:center">                               
                              <input type="checkbox" id="cb'.$i.'" name="sid[]"  '.$checked.'  value="'.$list->listid.'" onclick="Joomla.isChecked(this.checked);" aria-invalid="false"></td>'; 
                              echo '<td width="12">
                                      <div class="roundsubscrib" style="background-color:'.$list->color.'"> </div>
                                    </td>';
                              echo '<td><span class="hasTooltip">'.$list->name.$default.'</span></td>';     
                                      
                              } 
                            }//end if set?>                            
                          </tbody>
                        </table>
                      </div>
                      
                      
                          
                    </fieldset>
                </div>
                <?php }else{ echo "<p> Component Acy Mailing not Enabled or Installed </p>"; } ?>
                <!-- Email Tab end-->
                
                
            </div>    
      </fieldset>
    
    </div>
    
</div>

<!--comma seprated user groups-->
<input type="hidden" name="user_group" value="2" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
<!-- add Note Modal -->
<div id="Add_newRow" class="modal hide" role="dialog">
	<div class="modal-dialog"><!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Add new Note<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
			</div>
			<form class="form-inline" id="addNewModalForm">
				<div class="modal-body">
				<div style="padding: 0 15px;">
					<div class="row-fluid form-horizontal">
					<div class="control-group">
						<div class="control-label">
							<label for="season_rooms">Note Title:</label>
						</div>
						<div class="controls">
              <input class="TitleModal inputbox" type="text" name="note_title[]" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<label for="season_notes">Notes:</label>
						</div>
						<div class="controls">
							<textarea class="NotesModal inputbox" rows="7" name="notes[]"></textarea>
						</div>
					</div>
				</div>
				</div>
				</div>
				<div class="clearfix"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success addrow" data-dismiss="modal">Save</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Edit Note Modal -->
<div id="Edit_newRow" class="modal hide" role="dialog">
	<div class="modal-dialog"><!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Edit Note<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
			</div>
			<form class="form-inline" id="addNewModalForm">
				<div class="modal-body">
        <input type="hidden" class="noteIDEdit" name="noteIDEdit" value="" />
				<div style="padding: 0 15px;">
					<div class="row-fluid form-horizontal">
					<div class="control-group">
						<div class="control-label">
							<label for="season_rooms">Note Title:</label>
						</div>
						<div class="controls">
              <input class="TitleModalEdit inputbox" type="text" name="note_title[]" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<label for="season_notes">Notes:</label>
						</div>
						<div class="controls">
							<textarea class="NotesModalEdit inputbox" rows="7" name="notes[]"></textarea>
						</div>
					</div>
				</div>
				</div>
				</div>
				<div class="clearfix"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success saveEditRow" data-dismiss="modal">Save</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<link rel="stylesheet" type="text/css"  href="<?php echo JURI::root(); ?>administrator/components/com_donorforce/assets/chosen/chosen.css" />
<script  src="<?php echo JURI::root(); ?>administrator/components/com_donorforce/assets/chosen/chosen.jquery.js"></script>
<script>


var org;
var lat;
var lng;

jQuery(document).ready(function(e) {
  
  jQuery(document).on('click', '#newRowButton', function(){
    //$('#addNewModalForm').reset();
    jQuery("#addNewModalForm").trigger('reset'); //jquery
    document.getElementById("addNewModalForm").reset();
  });

  
  jQuery(document).on('click', '.addrow', function(){
    var TitleModal = jQuery('.TitleModal').val();
    var NotesModal = jQuery('.NotesModal').val();
    //alert(TitleModal);
    //var formData = $('#addNewModalForm').serializeArray();
    jQuery.ajax({
      data: {
        'TitleModal': TitleModal,
        'NotesModal': NotesModal
      },
      url: "index.php?option=com_donorforce&task=ajax.AddNotes&format=raw&donor_id=<?= $this->item->donor_id; ?>",
      type: 'post'
    })
    .done(function(Data) {
      jQuery('#div_notes').append(Data);
    });
  });
    
  jQuery(document).on('click', '.editRow', function(){
    var noteID = jQuery(this).attr('data-pkey');
    //alert(TitleModal);
    //var formData = $('#addNewModalForm').serializeArray();
    
    if(jQuery(this).hasClass("donor")){
      jQuery.ajax({
        data: {
          'noteID': noteID,
          'donor_id': '<?= $this->item->donor_id; ?>',
          'type': 1
        },
        url: "index.php?option=com_donorforce&task=ajax.getNoteData&format=raw",
        type: 'post'
      })
      .done(function(Data) {
        var noteData = jQuery.parseJSON(Data);
        jQuery('.noteIDEdit').val('donor');
        jQuery('.TitleModalEdit').val(noteData.note_title);
        jQuery('.NotesModalEdit').val(noteData.notes);
      });
    }
    else{
      jQuery.ajax({
        data: {
          'noteID': noteID,
          'donor_id': '<?= $this->item->donor_id; ?>'
        },
        url: "index.php?option=com_donorforce&task=ajax.getNoteData&format=raw",
        type: 'post'
      })
      .done(function(Data) {
        var noteData = jQuery.parseJSON(Data);
        jQuery('.noteIDEdit').val(noteID);
        jQuery('.TitleModalEdit').val(noteData.title);
        jQuery('.NotesModalEdit').val(noteData.notes);
      });
    }
    
    
  });

  jQuery(document).on('click', '.saveEditRow', function(){
    var donor_id = '<?= $this->item->donor_id; ?>';
    var noteID = jQuery('.noteIDEdit').val();
    var TitleModal = jQuery('.TitleModalEdit').val();
    var NotesModal = jQuery('.NotesModalEdit').val();
    //alert(TitleModal);
    //var formData = $('#addNewModalForm').serializeArray();
    jQuery.ajax({
      data: {
        'TitleModal': TitleModal,
        'NotesModal': NotesModal
      },
      url: "index.php?option=com_donorforce&task=ajax.editNotes&format=raw&note_id="+noteID+"&donor_id="+donor_id,
      type: 'post'
    })
    .done(function(Data) {
      if(noteID == 'donor'){
        jQuery('#donorRow-'+donor_id).empty().append(Data);
      }
      else{
        jQuery('#noteRow-'+noteID).empty().append(Data);
      }
    });
    
  });

  jQuery(document).on('click','a.delete-note', function(e) {
    var noteID = jQuery(this).attr('data-pkey');
    var $thisrow = jQuery(this);
    if(confirm("Are you sure you want to delete this note?"))
    {
      jQuery.ajax({
        type: "POST",
        url: 'index.php?option=com_donorforce&task=ajax.deleteNote&format=raw&note_id='+noteID+'&donor_id=<?php echo (int)$this->item->donor_id; ?>',
        success: function(resp)
        {
          resp = jQuery.parseJSON(resp);
          //console.log(resp.status);
          if(resp.status == 'success'){
            $thisrow.closest('tr').remove();	
          }
        },
        error: function(request, status, error_message){
          console.log(status+' - '+error_message);						
          var errortext = '<div class="alert alert-error fade in">';
          errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
          errortext += 	'<strong><?php echo addslashes(JText::_('COM_IPROPERTY_WARNING')); ?></strong> '+error_message;
          errortext += 	'</div>';
          jQuery('#ip_message').html(errortext);
        }
      });
    }				
  });
			
  /*--- user get ---*/
  jQuery(document).on("change","#jform_cms_user_id", function(e){
      //e.preventDefault();     
      var id=jQuery(this).val(); 
      //console.log(' id =  '+id+' status = '+status); return false;    
      jQuery.ajax({
        data: {id:id}, 
        url : 'index.php?option=com_donorforce&task=ajax.GetJoomlaUser&format=raw',
        type: "POST"
      })
      .done(function(Data) {
        var noteData = jQuery.parseJSON(Data);
        var name = noteData.name;
        var names = name.split(' ');
        var first_name = names[0];
        var last_name = names[1];
        var email = noteData.email;
        var username = noteData.username;
        jQuery('#jform_name_first').val(first_name);
        jQuery('#jform_name_last').val(last_name);
        jQuery('#jform_email').val(email);
        jQuery('#jform_username').val(username);
      });
    });

  //testing search drop down
  /*var config = {
      '.chosen-select'           : {}
     }
    for (var selector in config) {
      jQuery(selector).chosen(config[selector]);
    }*/
  //testing search drop down end
  
  jQuery("#jform_level").chosen({disable_search_threshold: 5});
  jQuery("#jform_phy_country").chosen();
  jQuery("#jform_post_country").chosen();
  
  
  // testing level
  if (jQuery("#jform_level").length){
    
    var Selected_level = jQuery( "#jform_level" ).val();    
    jQuery("#jform_level").after( '<span id="levelshow" class="level'+Selected_level+'"></span>' );     
  }
  
  jQuery( "#jform_level" ).change(function() {
      var Selected_level = jQuery( "#jform_level" ).val();    
    jQuery("#levelshow").removeClass();
    jQuery("#levelshow").addClass('level'+Selected_level);      
  });
  //testing level end 
  
    
  org=jQuery('#jform_org_type');
  org=jQuery('#jform_user_created');
  org=jQuery('#jform_mail_only');
  lat=jQuery('#jform_org_latitude').closest('.control-group');
  lng=jQuery('#jform_org_longitude').closest('.control-group');
  
  if(jQuery('#jform_donor_id').val()==0){
    jQuery('#jform_password').attr('required','required');
    jQuery('#jform_password2').attr('required','required');
  }
  
  jQuery('#jform_org_type').on('change',function(){
      toggleLatLng();
    });
  
  toggleLatLng();
  
  jQuery('#isSame').change(function(){ 
    if(this.checked)
    {
     jQuery('#jform_post_address').val(jQuery('#jform_phy_address').val()); 
     jQuery('#jform_post_address2').val(jQuery('#jform_phy_address2').val());  
     jQuery('#jform_post_city').val(jQuery('#jform_phy_city').val());
     jQuery('#jform_post_zip').val(jQuery('#jform_phy_zip').val());
     jQuery('#jform_post_state').val(jQuery('#jform_phy_state').val());
     jQuery('#jform_post_country').val(jQuery('#jform_phy_country').val())
     
     jQuery('#jform_post_country').trigger('chosen:updated');
     
    }
    else
    {
     jQuery('#jform_post_address').val('');
     jQuery('#jform_post_address2').val('');
     jQuery('#jform_post_city').val('');
     jQuery('#jform_post_zip').val('');
     jQuery('#jform_post_state').val('');
     jQuery('#jform_post_country').val('');
     
     jQuery('#jform_post_country').trigger('chosen:updated');
     
    }
   });
  
  //jQuery('.history_status').on('change',function(){ 
  jQuery("#donations").on("change", ".history_status", function(e){
     e.preventDefault();  
  //console.log("changing history status"); return false; 
    var id=this.id;
    var status=jQuery('#'+id).val();
    url='index.php?option=com_donorforce&task=ajax.changeHistoryStatus&format=raw';
    jQuery.post(url, {"history_id":id,"status":status},function(data){
        alert(data);
    });
  });
  
  jQuery('.status_btn').on('click',function()
  { 
    var $btn=jQuery(this);
   ($btn.data('historyid'));
    var id=$btn.data('historyid');
    var status= jQuery('#hid'+id).val()
    //console.log(id,status); return;
    url='index.php?option=com_donorforce&task=ajax.changeHistoryStatus&format=raw';
    jQuery.post(url,
    {
      
      history_id:id,
      status:status
    },
      function(data)
    {
      if(data!='updated')
      alert('History record updation failed.');
      else
      {
         jQuery('#tr'+id).text('Successful');
        }
    });
  });
  
  jQuery('.subscription_type').on('change',function(){  
    var id=this.id;   
    var sub_type=jQuery('#'+id).val();    
    var sub_id=id.split('sub_');    
    url='index.php?option=com_donorforce&task=ajax.changeSubscriptionType&format=raw';    
    jQuery.post(url, {"sub_type" : sub_type, "subscription_id" : sub_id[1] },function(data){
      if(data!='updated')
      alert(data);
      alert('Subscription record updation failed.');
    });   
  });
  
  
  
//-----AJAX call to display list of donors for donation Realocation. 
  //jQuery(".relocate").click(function(e){ 
  jQuery("#donations").on("click", ".relocate", function(e){
    //console.log("Relocation"); return false;
  // event.preventDefault();  return false;   
   e.preventDefault();  //return false;   
  
   var counter = jQuery(this).attr('id');
   var arr = counter.split('-');
   counter = arr[1]; 
   
   jQuery.ajax({
     url : 'index.php?option=com_donorforce&task=ajax.GetDonorsData&format=raw',
  //   type: "POST",
       type: "GET",
     dataType: 'text',
     success: function(data) {
     jQuery('#ajaxresult-'+counter).html( data  );
     jQuery("#update_donor-"+counter).show();      
     jQuery("#ajaxresult-"+counter+" .chosen-select").chosen();
     
    }
  });           
});


//----AJAX call to Reallocate the donation to another selected donor.
//jQuery(".update_donor").click(function(e){ 
  jQuery("#donations").on("click", ".update_donor", function(e){
    e.preventDefault();
  var counter = jQuery(this).attr('id');
    var arr = counter.split('-');
    counter = arr[1]; 
            
  jQuery.ajax({
     url : 'index.php?option=com_donorforce&task=ajax.update_donor_history&format=raw',
     type: "POST",
   data: { donor_id: jQuery("#ajaxresult-"+counter+" select#jform_donor_id option").filter(":selected").val(),
       project_id: jQuery("#ajaxresult-"+counter+" select#jform_project_id option").filter(":selected").val(),  
           donor_history_id: jQuery("#donor_history_id-"+counter).val() 
      },
  //   dataType: 'text',
     success: function(data) {
       if(data == '1'){  
          alert('Donation Updated Successfully');
          jQuery("#ajaxresult-"+counter).parent( "td" ).parent( "tr" ).html('');
        }
       else{
        alert('Error Updated Donation '+data);
       }
    }
  });
    
    
});
  
//-- Hide all update button on default
jQuery(".update_donor").hide();
jQuery(".update_gift").hide();



/*--- Gift status changed ---*/
jQuery("#donations").on("change", ".gift_status", function(e){
    e.preventDefault();     
    var id=this.id; 
    var status=jQuery('#'+id).val();
    //console.log(' id =  '+id+' status = '+status); return false;    
    url='index.php?option=com_donorforce&task=ajax.changeGiftStatus&format=raw';
    jQuery.post(url, {"gift_id":id,"status":status},function(data){
        alert(data);
    });
  });
  
//-----AJAX call to display list of donors for Gift Realocation. 
  jQuery("#donations").on("click", ".gift_relocate", function(e){       
   e.preventDefault();  //return false;     
   var counter = jQuery(this).attr('id');
   var arr = counter.split('-');
   counter = arr[1]; 
   
   jQuery.ajax({
     url : 'index.php?option=com_donorforce&task=ajax.GetDonorsData&format=raw',
     type: "GET",
     dataType: 'text',
     success: function(data) {
     jQuery('#giftajaxresult-'+counter).html( data  );
     jQuery("#update_gift-"+counter).show();       
     jQuery("#giftajaxresult-"+counter+" .chosen-select").chosen();
     
    }
  });           
}); 


//----AJAX call to Reallocate the Gift to another selected donor.
 jQuery("#donations").on("click", ".update_gift", function(e){
   e.preventDefault();
   var counter = jQuery(this).attr('id');
   var arr = counter.split('-');
   counter = arr[1]; 
            
  jQuery.ajax({
     url : 'index.php?option=com_donorforce&task=ajax.update_gift&format=raw',
     type: "POST",
     data: { donor_id: jQuery("#giftajaxresult-"+counter+" select#jform_donor_id option").filter(":selected").val(),
             project_id: jQuery("#giftajaxresult-"+counter+" select#jform_project_id option").filter(":selected").val(),  
             gift_id: jQuery("#gift_id-"+counter).val() 
      },
     success: function(data) {
       if(data == '1'){  
          alert('Gift Updated Successfully');
          jQuery("#giftajaxresult-"+counter).parent( "td" ).parent( "tr" ).html('');
        }
       else{
        alert('Error Updated Gift '+data);
       }
    }
  }); 
});

//Ajax call to detete the Gift 
jQuery('#donations').on('click','.delete_gift',function(e){
    e.preventDefault();
    var gift_id = jQuery(this).data('gift_id');
    var donor_id= jQuery(this).data('donor_id');
  //  console.log(' gift_id = '+gift_id+'  donor_id = '+donor_id);
    var url='index.php?option=com_donorforce&task=ajax.gelete_gift&format=raw';   
    jQuery.post(url, {"gift_id":gift_id,"donor_id":donor_id},function(data){
    
    if(data == '1'){
      alert(" Gift Deleted Successfully ");
      var ht = jQuery(this).html();
      console.log('  this = '+ht);   //.parent( "td" ).parent( "tr" ).html('');
      if( jQuery("#GiftTable tbody tr td a.delete_gift[data-gift_id='"+gift_id+"']").parent('td').parent("tr").length){
            jQuery("#GiftTable tbody tr td a.delete_gift[data-gift_id='"+gift_id+"']").parent('td').parent("tr").html('');
      }
      
      
    }else{
      alert(' Error Deleting Gift '+data);  
    }
        
        
        
    });
});



      
});


function toggleLatLng()
{ 
  if(org.val()=='church')
  {
    lat.show();
    lng.show();
  }
  else
  {
    lat.hide();
    lng.hide();
  }
}
</script>
<style>
.paginator .pag,
.gpaginator .pag{         
    cursor: pointer;
    text-decoration: none;
    padding: 6px;
    font-size: 16px;
    font-weight: bold;
   /* -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;*/
    background-color: #fff;
    border: 1px solid #ddd;
   /* border-left-width: 0;*/
    display: inline-block;
    margin: 2px;}
    
.paginator .pag:hover,
.gpaginator .pag:hover{    
  -moz-transform: scale(1.3);
   -webkit-transform: scale(1.3);
   -o-transform: scale(1.3);
   transform: scale(1.3);
    background-color: #9BE2A7;
    color: #E67524; }
    
.page_select,.gpage_select{ color:red;  }
#notes table{ display:block;  }


/*#jform_level{height:200px; }*/
.circle {
  background-repeat: no-repeat;
    padding: 4px;
    margin-left: 5px;
    background-position: 2px 8px;
    padding-left: 20px !important;
}
.green{ background-image: url('../administrator/components/com_donorforce/assets/images/greendot.png'); }
.yellow{ background-image: url('../administrator/components/com_donorforce/assets/images/yellowdot.png');  }
.dorange{ background-image: url('../administrator/components/com_donorforce/assets/images/orangedot.png');  }
.brown{ background-image: url('../administrator/components/com_donorforce/assets/images/browndot.png');  }

#levelshow{ 
  width: 20px;
  height: 20px;
  border-radius: 50%;
  margin-left: 10px;
  display: inline-block;
  margin-bottom: 0;
  vertical-align: middle; 
  }
.level-1{ border:1px solid black;   }
.level0{  background: brown;   }
.level1{  background: green; }
.level2{  background: yellow; }
.level3{  background: darkorange;}
#jform_level_chosen{ float:left;  }
.chosen-container.chosen-container-single {  min-width:220px; }
 .chosen-drop{ margin-bottom: 50px; }
 
.acymailing_bridge thead tr{ background-color: #91acd7; color: #fff;} 
.acymailing_bridge thead th {
    padding: 5px 5px;
    font-style: normal;
    font-weight: bold;
    font-size: 12px;
  
}
.acymailing_bridge thead th, .acymailing_bridgetable table td{border: 2px solid white; }
.acymailing_bridge .roundsubscrib{ margin: 0px 10px;
    height: 16px;
    width: 16px;
    border-radius: 20px;
    float: left;}
@media (min-width: 768px) { 
  .acymailing_bridge { width:50%;  }
  .acymailing_bridge input[type="checkbox"]{ width: 18px;
    height: 18px;}  
}   
.delete_gift{ cursor:pointer; }   

</style>