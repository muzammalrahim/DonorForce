<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @version $Id: com_donorforce.php 599 2015-04-20 23:26:33Z brent $
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

jimport('joomla.utilities.utility');
class PlgContentDonorForceProjects extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin that adds a pagebreak into the text and truncates text at that point
	 *
	 * @param   string   $context  The context of the content being passed to the plugin.
	 * @param   object   &$row     The article object.  Note $article->text is also available
	 * @param   mixed    &$params  The article params
	 * @param   integer  $page     The 'page' number
	 *
	 * @return  mixed  Always returns void or true
	 *
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		$canProceed = $context == 'com_content.article';

		if (!$canProceed)
		{
			return;
		}

$document =& JFactory::getDocument();
$document->addStyleSheet(JUri::base().'components/com_donorforce/assets/css/style.css');
// require helper file

//echo JPATH_BASE.'/components/com_donorforce/helpers/donorforce.php';
//exit;
require_once(JPATH_BASE.'/components/com_donorforce/helpers/donorforce.helper.php');
$DFparems = DonorForceHelper::getParams();
$Plug_params = $this->params;

/*echo "<pre> Plug_params = "; print_r($Plug_params); 
echo "<pre>  params = "; print_r($params); 
echo "<pre> DFparems = "; print_r($DFparems); */

		// Expression to search for.
		//$regex = '#{project(.*)id="(.*)\/}#iU';
		
		$regex = '/{project}(.*?){\/project}/';		
		$input = JFactory::getApplication()->input;
		$view = $input->getString('view');
		$full = $input->getBool('fullview');

		if (!$page)
		{
			$page = 0;
		}

		if ($view != 'article')
		{
			$row->text = preg_replace($regex, '', $row->text);

			return;
		}

		// Find all instances of plugin and put in $matches.
		$matches = array();
		preg_match_all($regex, $row->text, $matches, PREG_SET_ORDER);
		
		// Split the text around the plugin.
		//$text = preg_split($regex, $row->text);
		// Count the number of pages.
		//$n = count($text);
		
		if( !empty($matches) ){		
		if($matches[0][1] > 0)
		{
			foreach($matches as $match)
			{								
				$html = '';					
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);				  
				  $query="SELECT
								*
							FROM
								#__donorforce_project WHERE project_id = ".$match[1];
					$db->setQuery($query);		   
					$project= $db->loadObject();	
					
					$project->total_raised  = $this->getTotalRaised($project->project_id);
					
					
									
					$html .= '<!-- START Project Container -->
			<div class="project_container DF_Content_plug">';
			
			$currency = DonorForceHelper::getCurrency();			
			$symbol = $currency;
			$total_raised = $project->total_raised; //number_format($project->total_raised, 2, '.', ' ');			
			$fundraising_goal = $project->fundraising_goal; // number_format($project->fundraising_goal, 2, '.', ' ');
			$perc = ''; $perc_bottom = '';  $perc_style =  '' ; 
			$perc =  ($total_raised/$fundraising_goal)*100;  //number_format(  , 2, '.', ' ');	
			$bar_perc = ($perc >100)? '100' : $perc ; 					
			
			$total_raised = number_format($total_raised, 2, '.', ' ');
			$fundraising_goal =  number_format($fundraising_goal, 2, '.', ' ');
			$perc = number_format($perc , 2, '.', ' ');	
			
			$show_project_title  = $Plug_params->get('show_project_title'); 
			$show_start_end_date = $Plug_params->get('show_start_end_date');
			$show_project_image  = $Plug_params->get('show_project_image'); 
		
		$status_bar_width = 12;
		$project_detail_width = 4; 
		
		if( $Plug_params->get('show_snapscan_image') == 1 ){  
						$status_bar_width =	$status_bar_width -2 ;
		}		
		
		
		
		if( ($show_project_title == 1) && ($show_start_end_date == 1) && ($show_project_image == 1)  ){	
				     $status_bar_width = $status_bar_width -4;
		}else if( ($show_project_title != 1) && ($show_start_end_date != 1) && ($show_project_image != 1) ){			
			
		}else if( (($show_project_image == 1) && ($show_start_end_date != 1)) && ($show_project_title != 1)){
				
				//if(($show_project_image == 1)){
				 //		$status_bar_width = $status_bar_width -2; $project_detail_width = 2;
				 //} else{ 
				 $project_detail_width = 2;  
				 $status_bar_width = $status_bar_width -2;  
	} else{
				  $status_bar_width = $status_bar_width -4; 
		}	/*else if( ($show_start_end_date == 1) && ($show_project_image == 1) ){			
					$status_bar_width = $status_bar_width -4; 
					$project_detail_width = 4;
		}	*/	
										
		
			if($bar_perc < 15 || $bar_perc > 76 ){ $perc_bottom = "bottom: -35px;";  }			
			if($bar_perc > 85 ){ $perc_style = " right:0; ";  }
			else if( $bar_perc < 10   ){ $perc_style = " left:".($bar_perc+1)."%; ";  }
			else{ $perc_style = " left:".($bar_perc-3)."%; ";   }	
		
			
		if( ($show_project_title == 1) || ($show_start_end_date == 1) || ($show_project_image == 1) ){			
					if ($currency == 'ZAR')  $symbol = 'R';
					
					/*if(($show_project_title == 1) && ($show_start_end_date == 1) && ($show_project_image == 1)){
						$html .= '<div class="project_detailsX col-md-4" id="plg_pd">'; 	
					}else {				
						$html .= '<div class="project_detailsX col-md-2" id="plg_pd">'; 						
					}*/
					
					$html .= '<div class="project_detailsX col-md-'.$project_detail_width.'" id="plg_pd">'; 
					
						if(!empty($project->image) && ($show_project_image == 1) )
						{			
							$html .= '<div class="image"><img src="'.JUri::base().$project->image.'" width="100" height="100" /></div>';
						}
						
						if($show_project_title == 1){			
								$html .='<h3>'.$project->name.'</h3>'; 
						}
						 
						if( $show_start_end_date == 1 ){  		
								$html .='
								<strong>Start Date: </strong>'.date('F j, Y',strtotime($project->date_start)).'<br />
								<strong>End Date: </strong>'.date('F j, Y',strtotime($project->date_end));
						}
						
						$html .= '</div><!-- plg_pd end -->';
			}
			if($Plug_params->get('show_amount_raised') == 1){ 
				// snapscam enabled then width = col-md-6 (50%) else col-md-8 (60%)
				/*if( $Plug_params->get('show_snapscan_image') == 1 ){   
							$html .='<div class="col-md-6">';	
					}else{ 
						$html .='<div class="col-md-8">'; 
					}*/
				
				$html .='<div class="col-md-'.$status_bar_width.'">
				<!-- Progress bar -->	     		
				<div class="progress">
				<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.$bar_perc.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$bar_perc.'%"> 			
						<span class="amount_raized">'.$currency.' '.$total_raised.'<i>Raised</i></span> 
						<span class="amount_percent" style="'.$perc_style.$perc_bottom.'";?>'.$perc.'%<i>Donated</i></span>
		  			<span class="amount_goal">'.$currency.' '.$fundraising_goal.'<i>Goal</i></span>
				</div>
	  		</div>
				<!-- Progress bar End-->'; 
	    }else{
				 $html .='<div class="col-md-6">';
			}  
		
		$html .='	
		<div style="clear:both;"></div>
				<div  class="donate_button"  align="center" style="margin-top:25px;">
					<a href="'.JRoute::_('index.php?option=com_donorforce&view=donationsimple&project_id='.$project->project_id).'" class="btn btn-primary">'.JText::_('Donate Now!').'</a>
			</div>	  
    </div>';
		
		// show the snap scam image if enabled from Plug_params
		if( $Plug_params->get('show_snapscan_image') == 1 ){   
			$html .='<!-- Snap scam -->
			<div class="col-md-2 snapscam">
				<img src="'.JUri::base().$project->snapscan_image.'" width="100" height="100">
			</div>
			<!-- Snap scam End-->';	
		}
		$html .='
		</div>
	
  <!-- END Project Container -->';
			
			
			

		          
			$row->text = str_replace($match[0], $html, $row->text);			
			}	 
		}
	}// if isset end 
		return true;
	}
	
	
	
	
	 public function getTotalRaised($pid){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query = "SELECT sum(dh.amount) As total_raised
						  FROM #__donorforce_history AS dh
						  Where dh.project_id = ".$pid."
							AND dh.status != 'pending' 
							AND dh.status != 'Pending' ";
		$db->setQuery($query);		
		return $db->loadObject()->total_raised; 
		 
	}
	
}
