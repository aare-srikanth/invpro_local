<?php

/**

 * Joomla! 3.8 module mod_topbutton

 * @author Iblesoft

 * @package Joomla

 * @subpackage TopButton

 * Module helper

 * @license GNU/GPL

 * This module structure created by madan chunchu.

 */

// no direct access

defined('_JEXEC') or die('Restricted access');   
$session = JFactory::getSession();
		
if($session->get('user_casillero_id')){
?>
<a class="btn btn-login" href="<?php echo JUri::base();?>index.php?option=com_userprofile&view=user&layout=prealerts">Alerts</a>&nbsp;
<a class="btn btn-login" href="<?php echo JUri::base();?>index.php?option=com_userprofile&view=user">Dashboard</a>&nbsp;
<a class="btn btn-login" href="<?php echo JUri::base();?>index.php?option=com_userprofile&task=user.logout">Logout</a>
<style>header .theme_nav .navbar-nav li a{display:none}</style>

<?php
}else{
?>
<a class="btn btn-login" href="<?php echo JUri::base();?>index.php?option=com_register&view=register">Register</a>
<a class="btn btn-login" href="<?php echo JUri::base();?>index.php?option=com_register&view=login">Login</a>
<?php
}
?>
<script type="text/javascript">
  jQuery(document).ready(function() {
      setTimeout(function() {
        jQuery('.alert-info').slideUp("slow");
    }, 10000);
  });    
</script>

