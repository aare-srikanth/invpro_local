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

$UserView= UserprofileHelpersUserprofile::getUserpersonalDetails($user);

// dynamic elements
   
   $res = Controlbox::dynamicElements('SupportTickets');
   $elem=array();
   foreach($res as $element){
      $elem[$element->ElementId]=array($element->ElementDescription,$element->ElementStatus,$element->is_mandatory,$element->is_default,$element->ElementValue);
   }
   
//   echo '<pre>';
//   var_dump($elem);
//   exit;

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
<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading">Create Ticket</div>
    <div class="panel-body">
    <!-- <a href="index.php?option=com_userprofile&&view=user&layout=ticket_system_2"><button>List</button></a> --> 
   
    <!-- All Tickets Grid Start -->
    <div class="row">
     <div class="col-sm-12">
      <div class="panel panel-default">
        <form action="" name="createTicketForm" id="createTicketForm" method="post"> 
        <input class="form-control" type="hidden" name="email" value="<?php echo $UserView->PrimaryEmail;?>" >
        <div class="panel-body">
          <!-- Create Ticket Form Start -->
          <div class="row">
        <?php if($elem['CustId'][1] == "ACT"){  ?> 
          <div class="col-sm-4 form-group">
            <label>Cust Id <?php if($elem['CustId'][2]){ ?><span class="error">*</span><?php } ?></label>
            <input class="form-control" name="custId" type="text" value="<?php echo $user; ?>" readonly >
          </div>
        <?php } if($elem['TicketId'][1] == "ACT"){ ?>  
          <div class="col-sm-4 form-group">
            <label>Ticket Id <?php if($elem['TicketId'][2]){ ?><span class="error">*</span><?php } ?></label>
            <input class="form-control" type="text" name="ticketId" id="ticketId"  readonly >
          </div>
        <?php } if($elem['TrackingId'][1] == "ACT"){ ?>  
          <div class="col-sm-4 form-group">
            <label><?php echo $assArr['tracking_ID'] ?> <?php if($elem['TrackingId'][2]){ ?><span class="error">*</span><?php } ?></label>
            <input class="form-control" type="text" id="trackingId" name="trackingId" maxlength="40" value="<?php if($elem['TrackingId'][3]){ echo $elem['TrackingId'][4];  }?>" <?php if($elem['TrackingId'][2]){ ?> required <?php } ?> >
          </div>
        <?php } ?>  
          </div>
          <div class="clearfix"></div>
        <?php if($elem['TicketTitle'][1] == "ACT"){ ?>  
           <div class="form-group">
            <label>Ticket Title <?php if($elem['TicketTitle'][2]){ ?><span class="error">*</span><?php } ?></label>
            <input class="form-control" type="text" name="tktDesc" maxlength="250" value="<?php if($elem['TicketTitle'][3]){ echo $elem['TicketTitle'][4];  }?>" <?php if($elem['TicketTitle'][2]){ ?> required <?php } ?> >
          </div>
        <?php } ?>  
          <div class="form-group">
            <input type="hidden"  value="Open" class="form-control" name="tktStatus" readonly>
          </div>
        <?php if($elem['Comments'][1] == "ACT"){ ?>  
          <div class="form-group">
            <label>Comments<?php if($elem['Comments'][2]){ ?><span class="error">*</span><?php } ?> </label>
            <textarea class="form-control" cols="80" rows="5" name="tktCmnts"  <?php if($elem['Comments'][2]){ ?> required <?php } ?> ><?php if($elem['Comments'][3]){ echo $elem['Comments'][4];  }?></textarea>
          </div>
        <?php } ?>    
          
          <!-- Create Ticket Form End -->
       
     
      <div class="row">
       <div class="col-sm-12 panel-footer">
         <input type="hidden" name="cmdtype" value="Insert">
         <input type="hidden" name="task" value="user.addnewticket">
         <input type="submit" class="btn btn-primary pull-right">
         </div>
         </div>
    </div>
    
     </form>
      </div>
     </div>
    </div>
    <!-- All Tickets Grid End --> 
    </div>
  </div>
</div>

<script type="text/javascript">
   var $joomla = jQuery.noConflict(); 
   $joomla(document).ready(function() {
       
       // start get ticket id
        userType = "Ticket";
        user = "Ticket";
            $joomla.ajax({
    	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getaddusertypeflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "user":user,"usertype":userType},
    			dataType:"html",
    			type: "get",
    			beforeSend: function(){
    			    $joomla(".page_loader").show();
    			},
    			success: function(data){
    			    
    			  $joomla(".page_loader").hide();
    			  var dsed=data;
    			  dsed=dsed.split(":");
    			  $joomla("#createTicketForm #ticketId").val(dsed[1]);
    			}
    		}); 
        // End
        
        // start create ticket form validation
         $joomla("form[name='createTicketForm']").validate({
           
           // Specify validation rules
           rules: {
           },
            messages: {
             tktDesc: "<?php echo Jtext::_('Please enter description')  ?>",
             tktCmnts: "<?php echo Jtext::_('Please enter comments')  ?>",
             trackingId: "<?php echo Jtext::_('Please enter trackig Id')  ?>"
           },
           submitHandler: function(form) {
               $joomla(".page_loader").show();
               form.submit();
           }
           }); 
         // End
         
         // start tracking id check
            $joomla('input[name="trackingId"]').on('blur',function(){
               
                var res=$joomla(this).val();
                if(res.indexOf(' ')<=0){
                 $joomla('#track_error').html("");
                 if(res!="")
                    $joomla.ajax({
            			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&trackingid="+res+"&trackexistticketflag=1&user=<?php echo $user; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
            			data: { "trackid": $joomla(this).val() },
            			dataType:"html",
            			type: "get",
            			beforeSend: function() {
                           $joomla('.page_loader').show();
                       },success: function(data){
                         if(data.length==11){
                           $joomla('.page_loader').hide();
                           $joomla('#track_error').html("");
                           $joomla("#trackingId").val("");
                           alert("Tracking Id does not exist. Please try again!");
                         }else{
                             $joomla('.page_loader').hide();
                         }
                             
                         }
            		});
                }else{
                    $joomla('#track_error').html("<label class='error'>Spaces are not allowed please enter again</label>");
                    $joomla(this).val("");
                }
            });
         
   });
    
</script>