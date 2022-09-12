<?php
// Check to ensure this file is included in Joomla!
noDirectAccess();
jimport('joomla.language.helper');
jimport('joomla.application.component.model');

class AngkorModelCSS extends JModelLegacy
{
	function __construct()
	{
		parent::__construct();

	}	
	function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_angkor';		

		$actions = array('core.admin', 'core.manage',  'core.edit');

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
		return $result;
	}
	function getCSS()
	{
		$css = angkor_Helper::getCSS();		
		return $css;
	}
	function saveCSS(){
		$data_css = angkor_Helper::getCSS();		
		
		$css = JTable::getInstance('css', 'JTable',array());		
		$css->bind(JRequest::get('post'));
		if($data_css)	
			$css->id = $data_css->id;
		$css->store();		
		return $css;
	}
}