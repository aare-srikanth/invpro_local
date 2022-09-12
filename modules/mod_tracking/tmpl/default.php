<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>


<div class="tracking<?php echo $moduleclass_sfx; ?>" <?php if ($params->get('backgroundimage')) : ?> style="background-image:url(<?php echo $params->get('backgroundimage'); ?>)"<?php endif; ?> >
	<?php echo $module->content; ?>
</div>
<div style="text-align :center;">
    <div id="as-root" class="track-htxt"><h1>Package Tracker</h1></div>
    <br>

    <script>
// <![CDATA[
    (function (e, t, n) { var r, i = e.getElementsByTagName(t)[0]; if (e.getElementById(n)) return; r = e.createElement(t); r.id = n; r.src = "//apps.aftership.com/all.js"; i.parentNode.insertBefore(r, i) })(document, "script", "aftership-jssdk")
// ]]></script>
    <div id="as-container-154686854673268" style="width: 100%; height: 64px;"><iframe id="as-154686854673268" style="width: 100%; overflow: hidden; border: 0px none; max-width: 500px; height: 64px;" scrolling="no" src="http://button.aftership.com/button_widget.html?&amp;size=large&amp;id=154686854673268&amp;slug=&amp;width=400&amp;hide_tracking_number=false&amp;tracking_number=&amp;responsive=true&amp;origin=http%3A%2F%2Fstaging-boxonmvc.boxonlogistics.com%2FHome%2FTracking&amp;domain=track.aftership.com" frameborder="0"></iframe></div>
    <div style="color: #23bbed; text-align :center; font-size:20px; padding-top: 10px;">Start tracking your package today by entering a tracking number!</div>
</div>