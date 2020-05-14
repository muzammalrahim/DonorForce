<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.framework');
$items = $this->items;
$currency = DonorForceHelper::getCurrency();
$DFparems = DonorForceHelper::getParams();
?>

<div>
<h2 style="margin:10px 0px;">Projects</h2>        
<?php
foreach ( $items as $i ) 
{
	$perc = ''; $perc_bottom = '';  $perc_style =  '' ;  
	$perc = ($i->total_raised/$i->fundraising_goal)*100;
	$bar_perc = ($perc >100)? '100' : $perc ; 
	$perc = number_format($perc, 2, '.', ' ');
	if($bar_perc < 15 || $bar_perc > 76 ){ $perc_bottom = "bottom: -35px;";  }
	if($bar_perc > 85 ){ $perc_style = " right:0; ";  }
	else if( $bar_perc < 10   ){ $perc_style = " left:".($bar_perc+1)."%; ";  }
	else{ $perc_style = " left:".($bar_perc-3)."%; ";   }	
	?>
    <!-- START Project Container -->
    <div class="project_container mainlist">
    <div class="col-md-4 project_detail_cont">
			
			<?php // Do not show image if disabled from DFparems or no image uploaded for this project
    		if(!empty($i->image) &&  $DFparems->get('show_project_image') != 0 ){ 
          echo '  <div class="image">
              <img src="'.JUri::base().$i->image.'" width="100" height="100" />
            </div>';
        } //end if 
			 ?>
    	
      <div class="project_details">        
   		 <?php  // Do not show Title if disabled from DFparems		
				if($DFparems->get('show_project_title') != 0){ echo "<h3>". $i->name."</h3>"; } 
			 ?>
    
			 <?php // Do not show the project start and end date if disabled from DFparems 
				if($DFparems->get('show_start_end_date') != 0){ 
						echo '<strong>Start Date: </strong>'. date('F j, Y',strtotime($i->date_start)).'<br />
    							<strong>End Date: </strong>'.date('F j, Y',strtotime($i->date_end));
     		} ?>
    
     		<div class="donate_button" align="left" style="margin-top:5px;">
     				<a href="<?php echo JRoute::_('index.php?option=com_donorforce&view=donationsimple&project_id='.$i->project_id); ?>" class="btn btn-primary"><?php echo JText::_('Donate Now!'); ?></a>
     		</div>
    	</div><!-- project_details end -->
    
   </div><!-- col-md-4 end -->
    
    <div style="padding:3px;"  <?php echo (($DFparems->get('show_snapscan_projects') == 1) && ($i->snapscan_image != ''))? 'class="col-md-6"' : ''; ?> >	
			<?php $description = $i->description;	 echo $description;?>
    </div>
    
    <?php if(($DFparems->get('show_snapscan_projects') == 1) && ($i->snapscan_image != '')){ ?>
    <!-- Snap scam -->
    	<div class="col-md-2 snapscam">
			<?php echo '<img src="'.JUri::base().$i->snapscan_image.'" width="100" height="100" />'; ?>
      </div>
    <!-- Snap scam End -->
    <?php } ?>
    
    
	<div style="clear:both;"></div>
    <?php  if($DFparems->get('show_amount_raised') == 1){ ?>
    <!-- Progress bar -->
    <div class="progress">
    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $bar_perc;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $bar_perc;?>%">
      
     <?php  //if($DFparems->get('show_amount_raised') == 1){ ?>
      <span class="amount_raized">
	  	<?php echo $currency ." ". number_format($i->total_raised, 2, '.', ' ');?><i>Raised</i></span>     
      <?php //} ?>
      
      <span class="amount_percent" style=" <?php echo $perc_style.' '.$perc_bottom; ?>"><?php echo $perc; ?>%<i>Donated</i></span>
      <span class="amount_goal">
	  	<?php echo $currency." ".number_format($i->fundraising_goal, 2, '.', ' ');?><i>Goal</i></span>
    </div>
  </div>
  <!-- Progress bar End -->
  <?php } ?>
       
  
	<?php 
	// } //end if
	
	
	?>
    <div style="clear:both;"></div>
   
	</div>
    <!-- END Project Container -->
<?php
}
?> 
<script type="text/javascript">    
jQuery(document).ready(function () {        
	// The height of the content block when it's not expanded
	/*var adjustheight = 93;
	// The "more" link text
	var moreText = "+ more";
	// The "less" link text
	var lessText = "- less";	
	// Sets the .more-block div to the specified height and hides any content that overflows
	jQuery(".more-less .more-block").css('height', adjustheight).css('overflow', 'hidden');	
	// The section added to the bottom of the "more-less" div
	//$(".more-less").append('[...]'); 
	// Set the "More" text 
	jQuery("a.adjust").text(moreText); 
	jQuery(".adjust").toggle(function() 
     { 
		jQuery(this).parents("div:first").find(".more-block").css('height', 'auto').css('overflow', 'visible'); 
		// Hide the [...] when expanded 
		jQuery(this).parents("div:first").find("p.continued").css('display', 'none'); 
		jQuery(this).text(lessText); }, function() { 
			jQuery(this).parents("div:first").find(".more-block").css('height', adjustheight).css('overflow', 'hidden'); 
			jQuery(this).parents("div:first").find("p.continued").css('display', 'block'); jQuery(this).text(moreText); 
	 }); */
	 
});
</script>

<style>
@media (max-width: 991px) {   

.amount_raized,
.amount_goal,
.amount_percent{ font-size:13px !important;  }  
}

.project_container .col-md-4,
.project_container .col-md-8 { margin: 10px 0px; }
.project_detail_cont{ margin-top:20px !important;  }

div.project_details{     float: none !important;
    width: 100%;
    max-width: 100% !important; }

.col-md-4, .col-md-8 {
  position: relative;
  min-height: 1px;
  width: 100%;
  display: inline-block;
}
 .donate_button{     
  	padding-right: 0px;
    text-align: left; 
	}
@media (min-width: 992px) {
 .col-md-4, .col-md-8{
    float: left;
  } 
  .col-md-8 {
    width: 60%;
    width: 66%;
	}  
  .col-md-4 {
    width: 39%;		
		width: 33%;
  }
	
}


.donate_button a{     margin-left: 25px; }

.more-block p{ margin-top: 0px;  }
.progress span i{ display:block; font-size:11px; color: #8E8C8F; font-style:normal;  }
.amount_raized{     
	position: absolute;
    bottom: 100%;
    left: 0;
    font-size: 18px;
    line-height: 18px;
    color: #45879B;
    font-weight: bold;
	 }
.amount_percent{     
	position: absolute;
    bottom: 100%;
	color: #4A894E;
	font-weight:bold;
	font-size: 16px;
    line-height: 16px;
	   }
.amount_goal{ 
	position: absolute;
    bottom: 100%; 
	right:0;
	font-weight:bold;
	font-size: 18px;
    line-height: 18px;
	color:#524445; 
	 }

.adjust{ float:right;  }
.project_details {
    max-width: 27% !important;    padding-right: 5px;
}
.donate_button{ /*text-align:center; padding-right: 10%;*/  }
.DFproject_detail{  }


progress {
  display: inline-block;
  vertical-align: baseline;
}
@-webkit-keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}
@-o-keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}
@keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}
.progress {
  position:relative;
  overflow: visible !important;
  height: 12px !important;
  margin-bottom:30px;
  margin-top: 38px;
  background-color: #f5f5f5;
  border-radius: 5px !important;
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}
.progress-bar {
  float: left;
  width: 0%;
  height: 100%;
  font-size: 12px;
  line-height: 20px;
  line-height: 12px;
  color: #ffffff;
  text-align: center;
  background-color: #337ab7;
  -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
  box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
  -webkit-transition: width 0.6s ease;
  -o-transition: width 0.6s ease;
  transition: width 0.6s ease;
	border-radius: 5px 0px 0px 5px;
}
.progress-striped .progress-bar,
.progress-bar-striped {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  -webkit-background-size: 40px 40px;
          background-size: 40px 40px;
}
.progress.active .progress-bar,
.progress-bar.active {
  -webkit-animation: progress-bar-stripes 2s linear infinite;
  -o-animation: progress-bar-stripes 2s linear infinite;
  animation: progress-bar-stripes 2s linear infinite;
}
.progress-bar-success {
  background-color: #5cb85c;
}
.progress-striped .progress-bar-success {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}
.progress-bar-info {
  background-color: #5bc0de;
}
.progress-striped .progress-bar-info {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}
.progress-bar-warning {
  background-color: #f0ad4e;
}
.progress-striped .progress-bar-warning {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}
.progress-bar-danger {
  background-color: #d9534f;
}
.progress-striped .progress-bar-danger {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

.project_container .col-md-4{ padding-right:5px; padding-left:0px; } 

@media (min-width: 992px){  .mainlist .col-md-6 { width: 55%; } 
.mainlist .col-md-6 p { }
} 
@media (max-width: 991px) {   .amount_raized,.amount_goal,.amount_percent{ font-size:13px !important;  }  }
@media (max-width:480px  ) { .amount_percent{     bottom: -28px; }
	.amount_raized,.amount_goal,.amount_percent{ font-size:11px !important; line-height: 12px;  }
	.donate_button { margin-top: 5px !important; } 
	
	.div.mainlist {     margin-right: 0px; }
	.project_container .col-md-4{ padding-right:5px; padding-left:5px;  }
	.project_container .col-md-4 .image, .project_container .col-md-4 .image img {    
		width: 100%; height: auto; float: none; max-width: 98% !important;
		}
		.donate_button a{     margin-left:0px; }
}
	

</style>