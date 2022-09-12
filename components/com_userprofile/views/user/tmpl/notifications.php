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
$document->setTitle("Ticket in Boxon Pobox Software");
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');

?>
<?php include 'dasboard_navigation.php' ?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->  
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

<div class="container">
	<div class="main_panel persnl_panel">
	    
		<div class="main_heading">Notifications</div>
		<div class="panel-body">
		    <div class="col-sm-8">
		       <div class="panel">
		           <div class="panel-body">
		               <div class="ntifiction-info" id="notification">
		               <h4>Lorem ipsum is dummy text <span>14 Apr ,2021 at 3.10</span></h4>
		               <p  class="collapse" id="collapseExample" aria-expanded="false">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
		               Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
		               Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
		                <a role="button" class="collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></a>
		               </div>
		               <div class="ntifiction-info">
		               <h4>Lorem ipsum is dummy text <span>14 Apr ,2021 at 3.10</span></h4>
		               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
		               <a href="#">Read More</a>
		              
		               </div>
		               <div class="ntifiction-info">
		               <h4>Lorem ipsum is dummy text <span>14 Apr ,2021 at 3.10</span></h4>
		               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
		               <a href="#">Read More</a>
		               </div>
		           </div>
            </div>
		    </div>
		    <div class="col-sm-4 nitif-link">
		        <div class="panel">
		            <div class="panel-body">
		                <a href="#">Lorem ipsum is dummy text</a>
		                <a href="#">Lorem ipsum is dummy text</a>
		                <a href="#">Lorem ipsum is dummy text</a>
		            </div>
		        </div>
		    </div>
		</div>
	      
	        
		</div>
	</div>
</div>