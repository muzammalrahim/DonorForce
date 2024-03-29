<?php
/**
 *  @copyright  Copyright (c) 2009-2013 TechJoomla. All rights reserved.
 *  @license    GNU General Public License version 2, or later
 */
defined( '_JEXEC' ) or die( ';)' );
jimport('joomla.html.html');
jimport( 'joomla.plugin.helper' );
class plgPaymentPaygateHelper
{ 		
	//gets the action URL
	function buildPaygateUrl($secure = true)
	{
		
 		/*$pmconfigs['host'] = "https://www.paygate.co.za/paywebv2/process.trans";
		$pmconfigs['hostsubs'] = "https://www.paygate.co.za/paysubs/process.trans";
		$pmconfigs['notify_url'] = JUri::base().'index.php?option=com_donorforce&task=donation.notify';
		$pmconfigs['paygate_id'] = $param->get('paygate_id');
		$pmconfigs['secret'] = $param->get('secret');
		$pmconfigs['currency'] = $param->get('currency');*/
		
		
		/*//1. check for https or http to use
		$secure_post = $this->params->get('secure_post');  
		
		//2. check whether sandbox mode is ON /OFF
		$url = $this->params->get('sandbox') ? 'www.sandbox.paygate.com' : 'www.paygate.com';
		if ($secure_post) 
			$url = 'https://' . $url . '/cgi-bin/webscr';
		else
			$url = 'http://' . $url . '/cgi-bin/webscr';*/
		
	
		$url = 	"https://www.paygate.co.za/paywebv2/process.trans";
			
		return $url;
		
	}

/*Function for Logging payment data */
	function Storelog($name,$logdata)
	{
	/*	jimport('joomla.error.log');
		$options = array('format' => "{DATE}\t{TIME}\t{USER}\t{DESC}");
		$path = dirname(__FILE__);
		$my = &JFactory::getUser();
		$logs = &JLog::getInstance($logdata['JT_CLIENT'].'_'.$name.'.log',$options,$path);
		$logs->addEntry(array('user' => $my->name.'('.$my->id.')','desc'=>json_encode($logdata['raw_data'])));*/
		
		jimport('joomla.error.log');
		$options = "{DATE}\t{TIME}\t{USER}\t{DESC}";
		$path = dirname(__FILE__);
		$my = JFactory::getUser();     
	
		JLog::addLogger(
			array(
				'text_file' => $logdata['JT_CLIENT'].'_'.$name.'.log',
				'text_entry_format' => $options ,
				'text_file_path' => $path
			),
			JLog::INFO,
			$logdata['JT_CLIENT']
		);

		$logEntry = new JLogEntry('Transaction added', JLog::INFO, $logdata['JT_CLIENT']);
		$logEntry->user = $my->name.'('.$my->id.')';
		$logEntry->desc = json_encode($logdata['raw_data']);

		JLog::add($logEntry);
		
		
	}
	/**
	 * Validates the incoming data.
	 */
	function validateIPN( $data)
	{
	 // parse the paygate URL
     $url = plgPaymentPaygateHelper::buildPaygateUrl();	      
     $this->paygate_url = $url;
      $url_parsed = parse_url($url);        

      // generate the post string from the _POST vars aswell as load the
      // _POST vars into an arry so we can play with them from the calling
      // script.
       // append ipn command
      // open the connection to paygate
      $fp = fsockopen($url_parsed['host'],"80",$err_num,$err_str,30); 
     // $fp = fsockopen ($this->paygate_url, 80, $errno, $errstr, 30);

      if(!$fp) {
           
         // could not open the connection.  If loggin is on, the error message
         // will be in the log.
         $this->last_error = "fsockopen error no. $errnum: $errstr";
         plgPaymentPaygateHelper::log_ipn_results(false);       
         return false;
         
      } else { 
				$post_string = '';    
				foreach ($data as $field=>$value) { 
					 $this->ipn_data["$field"] = $value;
					 $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
				}
				$post_string.="cmd=_notify-validate";
				
         // Post the data back to paygate
         fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
         fputs($fp, "Host: $url_parsed[host]\r\n"); 
         fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
         fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
         fputs($fp, "Connection: close\r\n\r\n"); 
         fputs($fp, $post_string . "\r\n\r\n"); 

         // loop through the response from the server and append to variable
         while(!feof($fp)) { 
            $this->ipn_response .= fgets($fp, 1024); 
         } 

         fclose($fp); // close connection

      }

      if (eregi("verified",$post_string)) {
  
         // Valid IPN transaction.
         plgPaymentPaygateHelper::log_ipn_results(true);
         return true;       
         
      } else {
  
         // Invalid IPN transaction.  Check the log for details.
         $this->last_error = 'IPN Validation Failed.';
         plgPaymentPaygateHelper::log_ipn_results(false);   
         return false;
         
      }
	
	}
	// Log the IPN data
	function log_ipn_results($success) {
       
      if (!$this->ipn_log) return; 
      
      // Timestamp
      $text = '['.date('m/d/Y g:i A').'] - '; 
      
      // Success or failure being logged?
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";
      
      // Log the POST variables
      $text .= "IPN POST Vars from payment server:\n";
      foreach ($this->ipn_data as $key=>$value) {
         $text .= "$key=$value, ";
      }
 
      // Log the response from the payment server
      $text .= "\nIPN Response from payment Server:\n ".$this->ipn_response;
      // Write to log
      $fp = fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n");
      fclose($fp);  // close file
   }
	
		
		
}
