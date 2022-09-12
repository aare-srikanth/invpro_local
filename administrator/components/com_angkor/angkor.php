<?php

//--------------------------------------------
//NO DIRECT ACCESS
//--------------------------------------------
function noDirectAccess()
{
	defined( '_JEXEC' ) or die( 'Restricted access' );
}
//--------------------------------------------
noDirectAccess();
define('DS','/');
require_once (JPATH_COMPONENT.DS.'helper'.DS.'helper.php');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

// Create the controller
$controller	= new AngkorController();

$view_name=JRequest::getCmd('view','angkor');
if($view_name!='')
{
	require_once(JPATH_COMPONENT.DS.'controllers'.DS.$view_name.".php");
	$controllerClass = 'AngkorController'.ucfirst($view_name);
	if (class_exists($controllerClass)) {
		$controller = new $controllerClass();
	} else {
		JError::raiseError(500, 'Invalid Controller Class');
	}
}

$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>