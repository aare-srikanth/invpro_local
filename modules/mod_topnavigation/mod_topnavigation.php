<?php

/**

 * Joomla! 3.8 module mod_topnavigation

 * @author Iblesoft

 * @package Joomla

 * @subpackage topnavigation

 * Module helper

 * @license GNU/GPL

 * This module structure created by madan chunchu.

 */

// no direct access

defined('_JEXEC') or die('Restricted access');   



// Include the syndicate functions only once

require_once( dirname(__FILE__).'/helper.php' );


//$hello = modtopnavigationHelper::getHello( $params );

require( JModuleHelper::getLayoutPath( 'mod_topnavigation' ) );

?> 