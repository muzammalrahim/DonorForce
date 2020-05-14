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
 	<?php if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){?>
      <li class=""><a href="#receipt" data-toggle="tab">Receipt Fields</a></li>
      <li class=""><a href="#receipt_view" data-toggle="tab">Receipt View</a></li>
	<?php } ?>  
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="fields">
    <fieldset class="adminform">          
            <legend><?php echo empty($this->item->id) ? JText::_('Donorforce Template Fields Details') : JText::sprintf('COM_DONORFORCE_Donorforce_GROUP_FIELDS_DETAIL', $this->item->id); ?></legend>
            
            <p><b>Please note that images inserted will align left only.</b></p>
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
              <div class="control-label"><?php echo $this->form->getLabel('thankyou_body'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('thankyou_body','',(isset($this->template->thankyou_body))?$this->template->thankyou_body:''); ?></div>
            </div>
            
            
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
            if(!empty($this->template->head_logo)){ ?>
            	<img src="<?php echo JURI::root().'/'.$this->template->head_logo ?>" name="" />
            <?php 
						}else{
  					 //echo "Logo will be  Here";
            }
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
        </div><!--temp_header-->
        
        <div style="text-align:right" class="top_thankyou">
          <h1>Thank You</h1>
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
          
          <?php
     if(!empty($this->template->thankyou_body)) 
     {
        $thankyou_body = $this->template->thankyou_body;
        echo str_replace('src="', 'src="'.JURI::root().'/', $thankyou_body);
     }
     else
     echo "<b>System genrated Invoice will be show here</b>";
     ?>


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
      <style><?php echo $this->template->custom_style; ?></style>
      
    </div>    
	</div><!-- Tab pane end -->
	
  
  
  
   <div class="tab-pane" id="shortcode">
  	<legend><?php echo JText::_('Shortcode For Template Layout'); ?></legend>     
    <ul>
        <li> <span> Donor Number =  {donor_number} </span> </li>
    		<li> <span> Donor Name =  {donor_name}  </span> </li>
        <li> <span> Address 1 =  {address_1} </span> </li>
        <li> <span> Address 2 =  {address_2}  </span> </li>
        <li> <span> City =  {city} </span> </li>
        <li> <span> Country  =  {country}  </span> </li>
        <li> <span> Donation Reference =  {donation_reference} </span> </li>
        <li> <span> Donation Date =  {donation_date}  </span> </li>
        <li> <span> Donation Type =  {donation_type} </span> </li>
        <li> <span> Donation Amount  =  {donation_amount}  </span> </li>
        <li> <span> Project Number =  {project_number} </span> </li>
        <li> <span> Project Name =  {project_name} </span> </li>
        <li> <span> Project Description =  {project_description} </span> </li>
    </ul>
	</div><!-- shortcode pan end -->
   <?php if( JComponentHelper::getParams('com_donorforce')->get('enable_tax_pdf') == 1){?>
  
   <div class="tab-pane" id="receipt">
  	<fieldset class="adminform">
           
            <legend><?php echo JText::_('Donorforce Receipt Fields Details'); ?></legend>
            
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('id','',(isset($this->template->id))?$this->template->id:''); ?></div>
            </div>
            
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('org_name'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('org_name','',(isset($this->template->org_name))?$this->template->org_name:''); ?></div>
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
              <div class="control-label"><?php echo $this->form->getLabel('pobox'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('pobox','',(isset($this->template->pobox))?$this->template->pobox:''); ?></div>
            </div>
            <!-- <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('receipt_text'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('receipt_text','',(isset($this->template->receipt_text))?$this->template->receipt_text:''); ?></div>
            </div> -->
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
              <div class="control-label"><?php echo $this->form->getLabel('chairman_title'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('chairman_title','',(isset($this->template->chairman_title))?$this->template->chairman_title:''); ?></div>
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
  	   
    <div class="tax_pdf">
     <div class="tax_cont">
     	<div class="tax_header">
      	<div class="temp_logo">         
         <?php
					 if(!empty($this->template->head_logo2)) { ?>
							<img src="<?php echo JURI::root().'/'.$this->template->head_logo2 ?>" name="" />
							<?php 
					 }else {
             //echo "Logo will be  Here"; 
           }
				 ?>
         <span><b>
              <?php
                if(!empty($this->template->pobox)) 
                {
                  echo $this->template->pobox;
                }
                else
                echo "NPO / PBO Number will be Here";
              ?>        
              </b></span>
        </div>
        <div class="address_div">
          <h1 style="text-align: center;">
              <?php
                if(!empty($this->template->org_name)) 
                {
                  echo $this->template->org_name;
                }
                else
                echo "Organisation Name";
              ?>     
              </h1>
          <div class="address1">
            <h4>Physical Address</h4>
              <?php
              if(!empty($this->template->physical_address)) 
              {
                echo $this->template->physical_address;
              }
              else
              echo "Postal Addresses will be Here";
            ?>
            
          </div>
          <div class="address2"> 
            <h4>Postal Address</h4>
            <?php
                if(!empty($this->template->postal_address)) 
                {
                  echo $this->template->postal_address;
                }
                else
                echo "Physical Addresses will be Here";
              ?>        
          </div>     
        </div>
        <div class="receipt">
          <h1 style="width: 50%; margin-left: 50%; padding-left: 5px; margin-right: -5px;">Receipt</h1>
          <div><span class="receipt_label">Receipt:</span> <span class="receipt_value">156434545</span></div>
          <div><span class="receipt_label">Date:</span> <span class="receipt_value">20-10-2019</span></div>
          <div><span class="receipt_label">VAT Number:</span> <span class="receipt_value">879789796469</span></div>
          <div><span class="receipt_label">Company Name:</span> <span class="receipt_value">testing</span></div>
          <div><span class="receipt_label">Receipt:</span> <span class="receipt_value">address1 <br> address2 <br> city <br> country</span></div>
        </div>             
      </div><!-- tax_header end -->
      <!-- 
       <div class="header_empty"></div>
     	 <div class="donation_recpt">
       		<h3>Donations Receipt</h3>
          <span><?php 
							 if(!empty($this->template->receipt_text)) 
               {
                 echo $this->template->receipt_text;
               }
               else
               echo "Donation Receipt Text will be here";
							
					?></span>
       </div> --><!-- donation_recpt end -->
        
       <!-- <div class="recpt_no">
        		<span>Receipt No.</span>
            <span>xxx</span>
            
        </div> -->
        
        <div class="tax_body">
        
          
          
        
        		<?php
						 if(!empty($this->template->receipt_body)) 
						 {
							  //echo $this->template->receipt_body;
                $receipt_body = $this->template->receipt_body;
                echo str_replace('src="', 'src="'.JURI::root().'/', $receipt_body);
						 }
						 else
						 echo "Receipt Body Text will be here";							          
          ?>
        </div>
        
        <div class="tax_intent">
					<?php
						 if(!empty($this->template->statement_intent)) 
						 {
							 echo $this->template->statement_intent;
						 }
						 else
						 echo "Statement Intent Text will be here";							          
          ?>
        </div><!-- tax_intent end -->
        <div class="chairman_image">
        <?php 
           if(!empty($this->template->chairman_image)) { ?>
              <span><img src="<?php echo JURI::root().'/'.$this->template->chairman_image ?>" name="" /></span>
              <?php 
           }else {
            echo "<span>Logo will be  Here</span>";
          }
        ?>
          <span></span>
        </div>
        <div class="chairman">
        	<span><?php echo (!empty($this->template->chairman_title))? $this->template->chairman_title : ''?></span>
          <span></span>
        </div>
        
        
     </div><!-- content end here-->
     
     <div class="tax_footer">
     		<div class="footer_row">
        	<span><?php echo (!empty($this->template->footer1))? $this->template->footer1 : ''?></span>  				
          <span style="text-align: center;"><?php echo (!empty($this->template->footer2))? $this->template->footer2 : ''?></span>  				
          <span class="last"><?php echo (!empty($this->template->footer3))? $this->template->footer3 : ''?></span>         
        </div>
        
        <div class="footer_row">
        	<span><?php echo (!empty($this->template->footer4))? $this->template->footer4 : ''?></span>  
        	<span style="text-align: center;"><?php echo (!empty($this->template->footer5))? $this->template->footer5 : ''?></span>  
        	<span class="last"><?php echo (!empty($this->template->footer6))? $this->template->footer6 : ''?></span>         
        </div>
        
     </div>
     
    </div><!-- tax_pdf end here-->
        
	</div>
  
	<?php } ?>
  
  
</div><!-- Tab content end -->


 <input type="hidden" name="task" value="" />
  <?php echo JHtml::_('form.token'); ?>
</form>
</div>



<style type="text/css">
.temp_header {display: inline-block;}
 

span.blable {
    width: 150px;
    display: inline-block;
}
.tax_pdf{     max-width: 60%; border:1px solid black;  }
.tax_cont{ 
		padding: 10px; border: 1px solid black;}
.tax_header{     
    display: inline-block;
    width: 100%;  
}
#receipt_view .temp_logo{ 
	float: left;
  display: inline-block;
  width: 25%;
  text-align: center;
  margin-top: 12px;
}
.temp_logo span{
  display: block;
  margin-top: 11px;
}
.address_div{
  width: 40%;
  float: left;
}
.address1{    
	float: left;
  display: inline-block;
  padding-left: 25px;
  /* width: 50%; */
}
.address2{
  display: inline-block;
  padding-left: 25px;
  /* width: 50%; */
}
.address1 p, .address2 p{ margin-bottom:4px;  }
.receipt{
  width: 35%;
  float: left;
}
.receipt .receipt_label {
    text-align: right;
    width: 50%;
    float: left;
    display: block;
}
.receipt .receipt_value {
    width: 50%;
    float: left;
    padding-left: 5px;
    margin-right: -5px;
}
.header_empty {
    border: 1px solid black;
    padding: 5px;
    border-right: 0px;
    border-left: 0px;
}
.recpt_no{     
	border-top: 2px solid black;
  border-bottom: 1px solid black;
	text-align:right;
}
.recpt_no span{    
		border-left: 2px solid black;
    display: inline-block;
    padding: 5px;
    min-width: 100px;
    text-align: left; 
	}		
.tax_intent {
    border-bottom: 1px solid black;
    margin: 0px -10px;
}
.chairman_image {
    border-bottom: 1px solid black;    
    margin: 0 -10px;
}
.chairman .date,.chairman_image .date{
	float: right;
  min-width: 200px;
}
.tax_footer{ 
	/* display: table;
  width: 100%; */
  border: 1px solid #000;
  border-top: none;
  padding: 10px;
}			
.footer_row {display: table;
    width: 100%;}		
.footer_row span { display: table-cell;}		
.footer_row span.last{
	text-align: right;
  padding-right: 5px;
}
.tax_body{    
	border-top: 1px solid black;
  border-bottom: 1px solid black;
  margin: 10px -10px;
  padding: 5px 10px;
}
.tax_body p {
  margin: 0px !important;
}
.temp_header {
    width: 70%;
}
.top_thankyou{
		display: inline-block;
    margin-right: 10px; 
	width: 25%;  
}
<?php echo $this->template->custom_style2; ?>
</style>