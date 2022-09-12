<?php
// no direct access
noDirectAccess();

jimport('joomla.application.component.controller');

class angkorController extends JControllerLegacy
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

	}

	/**
	 * Displays a view
	 */
	function display( )
	{
		parent::display();
	}
	
	function ajax(){			
		$code = JRequest::getString('code');
		$lang = JRequest::getString('lang');
		
		$data = angkor_Helper::getEmail($code,$lang);
		
		$availableFields = angkor_Helper::getAvailableFieldParameters($code);
		if($availableFields=='')
			$data['parameters'] =  JText::_('VM_OTHER_MESSAGE_AVAILABLE_FIELS') . ' : '. JText::_('NO_FIELDS_PARAMETERS_AVAILABLE');
		else
			$data['parameters'] = JText::_('VM_OTHER_MESSAGE_AVAILABLE_FIELS') . ' : '. $availableFields;
		
		
		ob_clean();
		echo json_encode($data);
		exit(0);
	}
}
