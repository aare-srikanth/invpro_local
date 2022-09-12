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
?>
<div id="custnavtp" class="dashboard_nav">
  <?php //if($_GET['option']=="com_userprofile"){?>
    <nav class="navbar">
<div class="container-fluid">
<div class="navbar-header"><button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#dashboard_nav"> <span class="sr-only"><?PHP echo Jtext::_('MOD_TOPNAVIGATION_TOGGLE_NAVIGATION');?></span> </button></div>
<div id="dashboard_nav" class="collapse navbar-collapse">
<ul class="nav navbar-nav">
<li><a href="index.php?option=com_userprofile&amp;view=user&amp;layout=personalinformation"><?PHP echo Jtext::_('MOD_TOPNAVIGATION_MYACCOUNT');?></a></li>
<li><a href="index.php?option=com_userprofile&amp;view=user&amp;layout=orderprocess">My Prealerts</a></li>
<li><a href="index.php?option=com_userprofile&amp;view=user&amp;layout=orderprocess&c=2">Pending Shippments</a></li>
<li><a href="index.php?option=com_userprofile&amp;view=user&amp;layout=orderprocess&amp;c=3"><?PHP echo Jtext::_('MOD_TOPNAVIGATION_SHIPPMENTSHISTORY');?></a></li>
<li><a href="index.php?option=com_userprofile&amp;view=user&amp;layout=shopperassist"><?PHP echo Jtext::_('MOD_TOPNAVIGATION_SHOPPERASSIST');?></a></li>

<!--
<li><a href="index.php?option=com_userprofile&amp;view=user&amp;layout=viewshipments"><?PHP echo Jtext::_('MOD_TOPNAVIGATION_SHIPPMENTS');?></a></li>
<li><a href="index.php/calculator"><?PHP echo Jtext::_('MOD_TOPNAVIGATION_CALCULATER');?></a></li>
<li><a href="index.php?option=com_userprofile&amp;view=user&amp;layout=changepassword"><?PHP echo Jtext::_('MOD_TOPNAVIGATION_CHANGEPASSWORD');?></a></li>
--> 
</ul>

</div>
</div>
</nav>
<?php //} ?>
</div>