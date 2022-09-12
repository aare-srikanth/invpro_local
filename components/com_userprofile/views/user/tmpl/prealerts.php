<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->setTitle("Alerts in Boxon Pobox Software");
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
?>
<?php include 'dasboard_navigation.php' ?>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
});    
    
</script>
<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading">Alerts</div>
		<div class="panel-body">

<div class="row">
	          <div class="col-sm-12">
	            <h3 class="mx-1"><strong>View Alerts Details</strong></h3>
	          </div>
	        </div>
	        <div class="row">
	        	<div class="col-md-12">
	        		<table class="table table-bordered theme_table" id="j_table">
	        			<thead>
							<tr>
								<th>SNO</th>
								<th>Id#</th>
								<th>Message#</th>
								<th>DATE</th>
							</tr>
	        			</thead>
	        			<tbody><?php echo UserprofileHelpersUserprofile::getPreAlertsList($user);?>	
						</tbody>
	        		</table>
	        	</div>
	        </div>
</div>
</div>
</div>
