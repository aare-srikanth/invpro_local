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
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
// get labels
    $lang=$session->get('lang_sel');
    $res=Controlbox::getlabels($lang);
    $assArr = [];
    
    foreach($res->data as $response){
    $assArr[$response->id]  = $response->text;
    }

?>


<?php include 'dasboard_navigation.php' ?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->  
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

<div class="container">
	<div class="main_panel persnl_panel support_ticket">
	    
		<div class="main_heading">Support Tickets</div>
		<div class="panel-body">
    <div class="col-md-12 col-sm-12 btn-mrgn1">
	       <a class="create_ticket" href="index.php?option=com_userprofile&&view=user&layout=create_ticket" ><button class="btn btn-primary pull-right create-ticket">Create Ticket</button></a>
    </div>
   
	        <!-- All Tickets Grid Start -->
	        
	 <?php $ticketList = Controlbox::getTicketList($user,'');	 ?>      
	        
	        <div class="">
	            <div class="">
	        <div class="col-sm-12">
  <div class="table-responsive">
    <table class="table table-bordered theme_table">
      <thead>
        <tr role="row">
          <th>Ticket Id</th>
          <th>Ticket Description</th>
          <th><?php echo $assArr['tracking_ID']; ?></th>
          <th><?php echo $assArr['status']; ?></th>
          <th><?php $assArr['created_By'];?></th>
          <th><?php echo $assArr['creation_date']; ?></th>
          
        </tr>
      </thead>
      <tbody>
       
    <?php foreach($ticketList as $ticket){
        
        echo '<tr class="">
		  <td><a class="edit_ticket" href="index.php?option=com_userprofile&&view=user&layout=edit_ticket&tid='.$ticket->number_ticket.'" >'.$ticket->number_ticket.'</a></td>
          <td>'.$ticket->reason_ticket.'</td>
          <td>'.$ticket->TrackingNo.'</td>
          <td>'.$ticket->status_ticket.'</td>
          <td>'.$ticket->op_created.'</td>
          <td>'.$ticket->dti_created.'</td>
        </tr>';
	    
	 }
    ?>  
      </tbody>
    </table>
  </div>
</div>
	        </div>
	        </div>
	       </div>
	         <!-- All Tickets Grid End -->
	        
		</div>
	</div>
</div>

