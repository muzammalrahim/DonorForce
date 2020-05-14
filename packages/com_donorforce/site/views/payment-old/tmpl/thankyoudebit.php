<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

echo "Thank you for your donation of <b>".JRequest::getVar('AMOUNT')."</b>  as a Recurring Debit Order towards  <b>".JRequest::getVar('PNAME')."</b>";