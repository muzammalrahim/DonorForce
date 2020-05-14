<style>
<?php /*?>#logo {
	width: 200px;
	float: left;
}
#head_addresses {
	float: right;
}
#bottom_body_txt{
	
	margin-top:10px;
	text-align:justify
	}
.temp {
	width: 50%;
}
.main_body
{
	height:500px;
	border-top:1px solid #999;
	border-bottom:1px solid #999;
	}
.slogan{ width:70%; float:left}
.footer{margin-top:30px}
.editor iframe{ height:100px !important}<?php */?>
</style>



<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>

<div>
<form action="<?php echo JRoute::_('index.php?option=com_donorforce&task=template.saveDesign'); ?>" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-validate">

<ul class="nav nav-tabs">			
 	<li class="active"><a href="#fields" data-toggle="tab">Template Fields</a></li>
 	<li class=""><a href="#view" data-toggle="tab">Template View</a></li>
  <li class=""><a href="#shortcode" data-toggle="tab">Shortcode</a></li>
  <li class=""><a href="#receipt" data-toggle="tab">Receipt Fields</a></li>
  <li class=""><a href="#receipt_view" data-toggle="tab">Receipt View</a></li>
</ul>

<div class="tab-content">

	<div class="tab-pane active" id="fields">
    <fieldset class="adminform">
           
            <legend><?php echo empty($this->item->id) ? JText::_('Donorforce Template Fields Details') : JText::sprintf('COM_DONORFORCE_Donorforce_GROUP_FIELDS_DETAIL', $this->item->id); ?></legend>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('id','',(isset($this->template->id))?$this->template->id:''); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('head_logo'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('head_logo','',(isset($this->template->head_logo))?$this->template->head_logo:''); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('head_addresses'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('head_addresses','',(isset($this->template->head_addresses))?$this->template->head_addresses:''); ?></div>
            </div>
           <!-- <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('upper_body_sign'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('upper_body_sign','',(isset($this->template->upper_body_sign))?$this->template->upper_body_sign:''); ?></div>
            </div>-->
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('bottom_body_txt'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('bottom_body_txt','',(isset($this->template->bottom_body_txt))?$this->template->bottom_body_txt:''); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('footer_slogan'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('footer_slogan','',(isset($this->template->footer_slogan))?$this->template->footer_slogan:''); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('footer_addresses'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('footer_addresses','',(isset($this->template->footer_addresses))?$this->template->footer_addresses:''); ?></div>
            </div>
            
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('custom_style'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('custom_style','',(isset($this->template->custom_style))?$this->template->custom_style:''); ?></div>
            </div>
            
          </fieldset>     
	</div><!-- Tab pan end -->
  
  <div class="tab-pane" id="view">
    <div class="fltlft  pull-left temp">
      <legend><?php echo empty($this->item->id) ? JText::_('Donorforce Template View') : JText::sprintf('COM_DONORFORCE_Donorforce_GROUP_FIELDS_DETAIL', $this->item->id); ?></legend>
      <div style="border-left: 1px solid #ccc;padding-left: 16px;">
        <div class="temp_header">
          <div id="logo">
            <?php
     if(!empty($this->template->head_logo)) 
     {
     ?>
            <img src="<?php echo JURI::root().'/'.$this->template->head_logo ?>" name="" />
            <?php 
     }
     else
     echo "Logo will be  Here";
     ?>
          </div>
          <div id="head_addresses">
            <?php
     if(!empty($this->template->head_addresses)) 
     {
       echo $this->template->head_addresses;
     }
     else
     echo "Addresses will be Here";
     ?>
          </div>
          <div style="clear:both"></div>
        </div><!---/temp_header--->
        
        <div style="text-align:right">
          <h1>Tax Certificate</h1>
        <h3>&nbsp;&nbsp;Receipt No:</h3>
        </div>
        <div>
        <?php
      echo date('F j, Y').'<br>';
      echo '<b>Donor Name </b><br>';
      echo '<b>Donor Address </b><br><br>';
    /* if(!empty($this->template->upper_body_sign)) 
     {
       echo $this->template->upper_body_sign;
     }
     else
     echo "Upper Body Text will be Here";*/
     ?>
        </div>
        <div class="main_body">
          <b>System genrated Invoice will be show here</b>
        </div>
        <div id="bottom_body_txt">
        <?php
     if(!empty($this->template->bottom_body_txt)) 
     {
       echo $this->template->bottom_body_txt;
     }
     else
     echo "Bottom Body Text will be Here";
     ?>
        </div>
        <div class="footer">
        <div class="slogan">
         <?php
     if(!empty($this->template->footer_slogan)) 
     {
       echo $this->template->footer_slogan;
     }
     else
     echo "Footer Slogan Text will be Here";
     ?>
        </div>
         <div class="footer_addresses">
         <?php
     if(!empty($this->template->footer_addresses)) 
     {
       echo $this->template->footer_addresses;
     }
     else
     echo "Footer Addresses Text will be Here";
     ?>
        </div>
        </div>
      </div>
    </div>    
	</div><!-- Tab pane end -->
	
  <div class="tab-pane" id="shortcode">
  	<legend><?php echo JText::_('Shortcode For Template Layout'); ?></legend>     
	</div><!-- shortcode pan end -->
  
  <div class="tab-pane" id="receipt">
  	<fieldset class="adminform">
           
            <legend><?php echo JText::_('Donorforce Receipt Fields Details'); ?></legend>
            
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('id','',(isset($this->template->id))?$this->template->id:''); ?></div>
            </div>
            
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('head_logo2'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('head_logo2','',(isset($this->template->head_logo2))?$this->template->head_logo2:''); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('postal_address'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('postal_address','',(isset($this->template->postal_address))?$this->template->postal_address:''); ?></div>
            </div>
           
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('physical_address'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('physical_address','',(isset($this->template->physical_address))?$this->template->physical_address:''); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('receipt_text'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('receipt_text','',(isset($this->template->receipt_text))?$this->template->receipt_text:''); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('receipt_body'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('receipt_body','',(isset($this->template->receipt_body))?$this->template->receipt_body:''); ?></div>
            </div>
            
           <?php /*?> <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('postal_address'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('postal_address','',(isset($this->template->postal_address))?$this->template->postal_address:''); ?></div>
            </div><?php */?>
            
            
             <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('statement_intent'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('statement_intent','',(isset($this->template->statement_intent))?$this->template->statement_intent:''); ?></div>
            </div>
            
            
              <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('chairman_image'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('chairman_image','',(isset($this->template->chairman_image))?$this->template->chairman_image:''); ?></div>
            </div>
            
            
              <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('footer1'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('footer1','',(isset($this->template->footer1))?$this->template->footer1:''); ?></div>
            </div>
            
             <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('footer2'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('footer2','',(isset($this->template->footer2))?$this->template->footer2:''); ?></div>
            </div>
            
            
             <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('footer3'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('footer3','',(isset($this->template->footer3))?$this->template->footer3:''); ?></div>
            </div>
            
               <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('footer4'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('footer4','',(isset($this->template->footer4))?$this->template->footer4:''); ?></div>
            </div>
            
            
              <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('footer5'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('footer5','',(isset($this->template->footer5))?$this->template->footer5:''); ?></div>
            </div>
            
             <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('footer6'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('footer6','',(isset($this->template->footer6))?$this->template->footer6:''); ?></div>
            </div>
            
            
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('custom_style2'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('custom_style2','',(isset($this->template->custom_style2))?$this->template->custom_style2:''); ?></div>
            </div>
                        
          </fieldset>    
  </div><!-- receipt pan end -->
  
  <div class="tab-pane" id="receipt_view">
  	    
       <?php /*?><div class="fltlft  pull-left temp">
      <legend><?php echo JText::_('Donorforce Receipt View'); ?></legend>
      <div style="border-left: 1px solid #ccc;padding-left: 16px;">
        <div class="temp_header">
          <div id="logo">
           <?php
						 if(!empty($this->template->head_logo2)) { ?>
								<img src="<?php echo JURI::root().'/'.$this->template->head_logo2 ?>" name="" />
								<?php 
						 }else echo "Logo will be  Here"; ?>
          </div>
        <div id="head_addresses">
         <?php
						 if(!empty($this->template->head_addresses)) 
						 {
							 echo $this->template->head_addresses;
						 }
						 else
						 echo "Addresses will be Here";
				  ?>
         </div>
         <div style="clear:both"></div>
        </div><!---/temp_header--->
        
        <div style="text-align:right">
          <h1>Tax Certificate</h1>
        <h3>&nbsp;&nbsp;Receipt No:</h3>
        </div>
        <div>
        <?php
      echo date('F j, Y').'<br>';
      echo '<b>Donor Name </b><br>';
      echo '<b>Donor Address </b><br><br>';
     ?>
        </div>
        <div class="main_body">
          <b>System genrated Invoice will be show here</b>
        </div>
        <div id="bottom_body_txt">
        <?php
     if(!empty($this->template->bottom_body_txt)) 
     {
       echo $this->template->bottom_body_txt;
     }
     else
     echo "Bottom Body Text will be Here";
     ?>
        </div>
        <div class="footer">
        <div class="slogan">
         <?php
     if(!empty($this->template->footer_slogan)) 
     {
       echo $this->template->footer_slogan;
     }
     else
     echo "Footer Slogan Text will be Here";
     ?>
        </div>
         <div class="footer_addresses">
         <?php
     if(!empty($this->template->footer_addresses)) 
     {
       echo $this->template->footer_addresses;
     }
     else
     echo "Footer Addresses Text will be Here";
     ?>
        </div>
        </div>
      </div>
    </div><?php */?> 
    
    <table class="tax_pdf">
     <tbody class="tax_cont">
     	<tr>
      	<td class="temp_logo">         
         <?php
					 if(!empty($this->template->head_logo2)) { ?>
							<img src="<?php echo JURI::root().'/'.$this->template->head_logo2 ?>" name="" />
							<?php 
					 }else echo "Logo will be  Here"; 
				 ?>
        </td>
        <td class="address1">
        		 <?php
						 if(!empty($this->template->postal_address)) 
						 {
							 echo $this->template->postal_address;
						 }
						 else
						 echo "Postal Addresses will be Here";
				  ?>
          
        </td>
        <td class="address2"> 
					 <?php
               if(!empty($this->template->physical_address)) 
               {
                 echo $this->template->physical_address;
               }
               else
               echo "Physical Addresses will be Here";
            ?>
        
         </td>
       
        
      </tr><!-- tax_header end -->
      
       <tr class="header_empty"><td style="padding: 10px;"></td></tr>
       
       <tr><td>Donations Receipt</td></tr>
       <tr><td><?php 
							 if(!empty($this->template->receipt_text)) 
               {
                 echo $this->template->receipt_text;
               }
               else
               echo "Donation Receipt Text will be here";
							
					?>
        </td>
        </tr>
        
        <tr class="recpt_no">
        		<td></td>
            <td>Receipt No.</td>
            <td>xxx</td>            
        </tr>
        
        <tr class="tax_body"><td>
        		<?php
						 if(!empty($this->template->receipt_body)) 
						 {
							 echo $this->template->receipt_body;
						 }
						 else
						 echo "Receipt Body Text will be here";							          
          ?>
          </td>
        </tr>
        
        <tr class="tax_intent"><td>
					<?php
						 if(!empty($this->template->statement_intent)) 
						 {
							 echo $this->template->statement_intent;
						 }
						 else
						 echo "Statement Intent Text will be here";							          
          ?></td>
        </tr><!-- tax_intent end -->
        
        <tr class="chairman_image"><td>
        <?php 
					 if(!empty($this->template->chairman_image)) { ?>
							<img src="<?php echo JURI::root().'/'.$this->template->chairman_image ?>" name="" />
							<?php 
					 }else echo "Logo will be  Here"; 
				?></td>
        </tr>
        <tr class="chairmanX">
        	<td>Chairman / Secretary</td>
          <td></td>
          <td class="date">Date</td>
        </tr>
        
        
     </tbody><!-- content end here-->
     
     <tfoot class="tax_footer">
     		<tr class="footer_row">
        	<td><?php echo (!empty($this->template->footer1))? $this->template->footer1 : ''?></td>  				
          <td><?php echo (!empty($this->template->footer2))? $this->template->footer2 : ''?></td>  				
          <td class="last"><?php echo (!empty($this->template->footer3))? $this->template->footer3 : ''?></td>         
        </tr>
        
        <tr class="footer_row">
        	<td><?php echo (!empty($this->template->footer4))? $this->template->footer4 : ''?></td>  
        	<td><?php echo (!empty($this->template->footer5))? $this->template->footer5 : ''?></td>  
        	<td class="last"><?php echo (!empty($this->template->footer6))? $this->template->footer6 : ''?></td>         
        </tr>
        
     </tfoot>
     
    </table><!-- tax_pdf end here-->
        
	</div>
  
</div><!-- Tab content end -->


 <input type="hidden" name="task" value="" />
  <?php echo JHtml::_('form.token'); ?>
</form>
</div>



<style type="text/css">



<?php //echo $this->template->custom_style2; ?>
</style>