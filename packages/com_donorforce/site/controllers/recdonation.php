<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die();


class DonorforceControllerRecdonation extends DonorforceController
{
		
	function display() {
		parent::display();
    }
	
	
	function saverecdonation()
	{   
	
	
	$app = JFactory::getApplication();
  
  
    $model  = $this->getModel('recdonation');
 
               
   $data = JRequest::get('post');
   
      
   
		$save  = $model->saverecdonation($data);
	
 	
        // check if ok and display appropriate message.  This can also have a redirect if desired.
        if ($save) {
    
           
  
 
  $app->redirect('index.php?option=com_donorforce&view=recdonation&layout=edit',"Thanks");
   
        } else {
   
   
            JError::raiseWarning(1,"failed to be saved");
   
   // Get the validation messages.
   $errors = $model->getErrors();

   // Push up to three validation messages out to the user.
   for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
   {
    if ($errors[$i] instanceof Exception)
    {
     $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
    } else {
     $app->enqueueMessage($errors[$i], 'warning');
    }
   }
   //$app->redirect('index.php?option=com_teaching&view=parents&layout=standardreg');
        }
  
  
 

     return true;}
}
?>