<?php
/**
 * Joomla! 3.8 module mod_banner
 * @author Iblesoft
 * @package Joomla
 * @subpackage Banner
 * Module helper
 * @license GNU/GPL
 * This module structure created by madan chunchu.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');  
/*$input = JFactory::getApplication()->input;
$id = $input->getInt('id'); //get the article ID
$article = JTable::getInstance('content');
$article->load($id);$article->get('title')
$menu = &Jsite::getMenu();
$menuname = $menu->getActive()->title;
*/
$mydoc =& JFactory::getDocument();
$title = $mydoc->getTitle();


//$app = JFactory::getApplication();
//$menu = $app->getMenu()->getActive()->title;
?>
  <!-- Page Title -->
  <div class="page-title">
      <div class="container">
          <div class="page-heading">
              <h1><?php echo $title ; // display the article title;?></h1>
              <ol class="breadcrumb">
                  <li><a href="/">Home</a></li>
                  <li class="active"><?php echo $title ; // display the article title;?></li>
              </ol>
          </div>
      </div>
  </div>
  <!-- Page Title -->
