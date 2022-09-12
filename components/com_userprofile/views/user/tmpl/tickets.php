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
$ticketNumber=UserprofileHelpersUserprofile::getTicketnumber();
?>
<?php include 'dasboard_navigation.php' ?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->  
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
        history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

 $joomla('.nav-tabs li a').click(function(){
    $joomla('.nav-tabs li a').removeClass('active');
    $joomla(this).addClass('active');     
    
    $joomla('.panel-body #tabs1').hide();
    $joomla('.panel-body #tabs2').hide();
    if( $joomla(this).html()=="Create Ticket"){
     $joomla('.panel-body #tabs1').show();
    }
    else{
     $joomla('.panel-body #tabs2').show();
    }
    return false;
 });
    
 $joomla(function() {
 
 		// Initialize form validation on the registration form.
		// It has the name attribute "registration"
		$joomla("form[name='userprofileFormOne']").validate({
			
			// Specify validation rules
			rules: {
			  // The key name on the left side is the name attribute
			  // of an input field. Validation rules are defined
			  // on the right side
			  ticketNumberStr:{
					required: true
			  },
			  ticketDescStr: {
                  required: true,
                  minlength:20
              }
            },
			// Specify validation error messages
			messages: {
			   ticketNumberStr:{
				required: "Please refresh browser for get ticket number"
			  },
			  ticketDescStr: {
                  required: "Please enter Ticket description",
                  minlength: "Please enter minimum words"
              }
      
			},
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			submitHandler: function(form) {
			    //alert( $joomla('#stateTxt').val())
				// Returns successful data submission message when the entered information is stored in database.
				/*$.post("http://boxon.justfordemo.biz/index.php/register", {
					name1: name,
					email1: email,
					task: register,
					id:  0
				}, function(data) {
					$joomla("#returnmessage").append(data); // Append returned message to message paragraph.
					if (data == "Your Query has been received, We will contact you soon.") {
						$joomla("#registerFormOne")[0].reset(); // To reset form fields on success.
					}
				});*/
			  form.submit();
			}
		});
});		
		
		
    
    
});
</script>
<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading">Support Tickets</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-12 tab_view">
					<ul class="nav nav-tabs">
					  <li>
					    <a class="active" id="tab1"  href="#tab1">Create Ticket</a>
					  </li>
					  <li>
					    <a class="" id="tab21" href="#tab2">View Tickets</a>
					  </li>
					</ul>
				</div>
			</div>
		<form name="userprofileFormOne" id="userprofileFormOne" method="post" action="">
		<input type="hidden" name="ticketNumberStr" value="<?php echo $ticketNumber;?>" />
		<div id="tabs1">
			<div class="row">
				<div class="col-sm-12">
					<h4 class="sub_title"><strong>Create Ticket</strong></h4>
				</div>
				<div class="col-sm-12">
					<p>Ticket Number: <strong><?php echo $ticketNumber;?></strong></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>Reason <span class="error">*</span></label>
						<textarea class="form-control" name="ticketDescStr"></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<input type="submit" class="btn btn-primary" value="Submit" name="submit">
				</div>
			</div>
		</div>
        <input type="hidden" name="task" value="user.createtickets">
        <input type="hidden" name="id" value="0" />
        <input type="hidden" name="user" value="<?php echo $user;?>" />

		</form>
		<div id="tabs2" style='display:none'>
		    
		   <div class="row">
	        	<div class="col-md-12">
	        		<table class="table table-bordered theme_table" id="j_table">
	        			<thead>
							<tr>
								<th>Customer Id</th>
								<th>Ticket Number</th>
								<th>Customer name</th>
								<th>Branch</th>
								<th>Status</th>
								<th>Email Id</th>
								<th>Ticket Reason</th>
								<th>Comments</th>
								<th>Created date</th>
							</tr>
	        			</thead>	<tbody>
<?php
    $ordersView= UserprofileHelpersUserprofile::getUserTickets($user);
    $arrOrders = json_decode($ordersView); 
    foreach($ordersView as $rg){
      echo '<tr><td>'.$rg->id_cust.'</td><td>'.$rg->number_ticket.'</td><td>'.$rg->name_cust.'</td><td>'.$rg->name_branch.'</td><td>'.$rg->status_ticket.'</td><td>'.$rg->EmailId.'</td><td>'.$rg->reason_ticket.'</td><td>'.$rg->comments_ticket.'</td><td>'.$rg->dti_created.'</td></td></tr>';
    }
?>						
						</tbody>
	        		</table>
	        	</div>
	        </div>
		</div>
		</div>
		</div>
	</div>
</div>