<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Register
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Register', JPATH_COMPONENT);
JLoader::register('RegisterController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Register');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
