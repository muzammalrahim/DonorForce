<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

// The class name must always be the same as the filename (in camel case)
class JFormFieldMembershipList extends JFormFieldList {

	//The field class must know its own type through the variable $type.
	protected $type = 'MembershipList';

	public function getInput() {
        // code that returns HTML that will be shown as the form field
        $memberships = $this->getOptions();
        return JHtml::_('select.genericlist', $memberships, $this->name, 'class="inputbox ptag"', 'value', 'text', $this->value, $this->value);
        //return implode($html);
    }
    
    public function getOptions() {
        $params = JComponentHelper::getParams('com_donorforce');
        $membershipTypes = $params->get('membershipType');
        $total      = count($membershipTypes->title);
        $options[]  = JHTML::_('select.option', '','Select option', "value", "text");
        for ($i = 0; $i < ($total); $i++)
        {
            // $options[]  = $membershipTypes->title[$i];
            $options[]  = JHTML::_('select.option', $membershipTypes->title[$i],JText::_($membershipTypes->title[$i]), "value", "text");
        }
        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);
        return $options;
    }
}