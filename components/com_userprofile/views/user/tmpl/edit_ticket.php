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
<style>
    textarea.form-control {
    height: auto !important;
}
</style>

<?php  
        $ticketList = Controlbox::getetTicketDetailsById($_GET['tid']);
       
        // echo '<pre>';
        // var_dump($ticketList);
       
        $ticketTitle = '';
        $ticketCreated = '';
        $email= '';
        $custId = '';
        foreach($ticketList as $ticket){
                $ticketTitle = $ticket->reason_ticket;
                $ticketCreated = $ticket->dti_created;
                $email = $ticket->EmailId;
                $custId = $ticket->id_cust;
                $tktId = $ticket->number_ticket;
                $trackingNo = $ticket->TrackingNo;
                $status = $ticket->status_ticket;
        }
        
?>

<div class="container">
    
	<div class="main_panel persnl_panel">
		<div class="main_heading">Edit</div>
		<div class="panel-body">
	       <!-- <a href="index.php?option=com_userprofile&&view=user&layout=ticket_system_2"><button>List</button></a> -->
	        
	        <!-- All Tickets Grid Start -->
	       
	        <div class="row">
<form action="" method="post" id="ticketEditForm" name="ticketEditForm">
<div class="col-sm-12">
<div class="panel panel-default">
    <div class="panel-body">
        <div class="">
          
          <!-- Create Ticket Form Start -->
                    <div class="col-sm-12">
                        <label>Title : &nbsp;</label><?php echo $ticketTitle; ?><br>
	                    <label>Created On : &nbsp; </label><?php echo $ticketCreated; ?>
	                </div>
	                
	                <input type="hidden" name="email" value="<?php echo $email; ?>" >
	                <input type="hidden" name="custId" value="<?php echo $custId; ?>" >
	                <input type="hidden" name="ticketId" value="<?php echo $tktId; ?>" >
	                <input type="hidden" name="trackingId" value="<?php echo $trackingNo; ?>" >
	                <input type="hidden" name="tktDesc" value="<?php echo $ticketTitle; ?>" >
	                <input type="hidden" name="tktStatus" value="<?php echo $status; ?>" >
	                
                    <div class="col-sm-6">
                    <div class="row">
                              <div class="col-sm-12 form-group">
                                    <label>Comments</label>
                                    <textarea  name="tktCmnts" class="form-control" cols="80" rows="5"></textarea>
                              </div>
                    </div>
                    </div>
          
          <!-- Create Ticket Form End -->
        
        </div>
       
    </div>
    <div class="panel-footer text-right">
         <input type="hidden" name="cmdtype" value="Update">
         <input type="hidden" name="task" value="user.addnewticket">
         <input type="submit" class="btn btn-primary" value="Update">
    </div>
   
</div>
</div>
</form >
   </div>
   <div class="row">
       <div class="col-sm-12">
             <div class="panel panel-default history-chart">
                 <div class="panel-heading">
                     <h4>Show History</h4>
                 </div>
                <div class="panel-body">
              <?php
                foreach($ticketList as $ticket){
                    
                        foreach($ticket->GetTickets as $ticketDetails){
                            if($ticketDetails->op_created == "PORTAL"){
                                $class="col-lg-6 col-md-6 col-sm-7 col-xs-12 row-odd";
                                $sent = "sent";
                                $sentClass= "col-lg-12 col-md-12 col-sm-12 col-xs-12 snt-msg";
                                $dateClass= "col-sm-12 custmr-dte";
                                $name = "Portal";
                                
                            }else{
                                $class="col-lg-6 col-md-6 col-sm-7 col-xs-12 row-even";
                                $sent = $ticketDetails->op_created;
                                $sentClass= "col-lg-12 col-md-12 col-sm-7 col-xs-12 admin-msg";
                                $dateClass= "col-sm-12 adm-dte";
                                $name = "Admin";
                            }
                
                ?>
                <div class="col-sm-12 cht-htry">
                       <div class=" <?php echo $dateClass; ?>">
                           <span><?php echo $ticketDetails->dti_created; ?></span>
                       </div>
                        
                      <div class="<?php echo $class; ?>" >
                          <p><span><?php echo $name; ?> </span></p>
                          <p><?php echo $ticketDetails->comments_ticket; ?></p>
                      </div>
                      <div class="<?php echo $sentClass; ?>">
                           <span><i><?php echo $sent;  ?></i></span>
                       </div>
               </div>
               <div class="clearfix"></div>
              <?php  }  } ?>
              
          </div>
           <div class="clearfix"></div>
            </div>
       </div>
   </div>
    <!-- All Tickets Grid End --> 
	        </div>
		</div>
	</div>
</div>

<script type="text/javascript">
   var $joomla = jQuery.noConflict(); 
   $joomla(document).ready(function() {
        // start create ticket form validation
         $joomla("form[name='ticketEditForm']").validate({
           
           // Specify validation rules
           rules: {
             tktCmnts: {
              required: true
             }
           },
            messages: {
             tktCmnts: "<?php echo Jtext::_('Please enter comments')  ?>"
           },
           submitHandler: function(form) {
               form.submit();
           }
           }); 
         // End
   });
   
</script>   