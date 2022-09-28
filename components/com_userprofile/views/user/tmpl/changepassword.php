<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
$document = JFactory::getDocument();
$document->setTitle("Change Password in Boxon Pobox Software");
defined('_JEXEC') or die;
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$session->clear( 'userData');

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
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
   $joomla('input').on("cut copy paste",function(e) {
      e.preventDefault();
   });
	// Wait for the DOM to be ready
	$joomla(function() {
	    
	    //validation for password fields
	jQuery.validator.addMethod(
	"validatePassword", 
	function(value, element) {
	    return /^[A-Za-z0-9\d=!\#?!@$%^&*-_]*$/.test(value) // consists of only these
       && /[A-Z]/.test(value) // has a uppercase letter
       && /\d/.test(value) // has a digit
       && /[#?!@$%^&*-]/.test(value) // has a special char
		},
		"<?php echo $assArr['New_Password_rule1'];?>"
	);
	
		// Initialize form validation on the registration form.
		// It has the name attribute "registration"
		$joomla("form[name='registerFormOne']").validate({
			
			// Specify validation rules
			rules: {
			  // The key name on the left side is the name attribute
			  // of an input field. Validation rules are defined
			  // on the right side
			  passwordTxt: "required",
			  newpasswordTxt: {
				required: true,
				minlength: 8,
				validatePassword: true
              },
			  cnewpasswordTxt: {
				required: true,
				minlength: 8,
				equalTo:"#newpasswordTxt"
              }
			},
			// Specify validation error messages
			messages: {
			  passwordTxt: "<?php echo $assArr['current_Password_error'];?>",
			  newpasswordTxt: {
                  required: "<?php echo $assArr['new_Password_error'];?>",
                  minlength: "<?php echo $assArr['New_Password_rule1'];?>"
              },
              cnewpasswordTxt: "<?php echo $assArr['Confirm_new_password_error'];?>"
      
			},
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			submitHandler: function(form) {
			    $joomla('.page_loader').show();
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
	
   $joomla('#newpasswordTxt').on("blur",function() {
      var carpass = $joomla("#passwordTxt").val();
      var newpass = $joomla(this).val();
      
      if(carpass == newpass){
          alert("<?php echo $assArr['current_new_password_alert'];?>");
          $joomla(this).val("");
          $joomla(this).focusout();
      }
       
   });


});
</script>

<div class="item_fields">


  <form name="registerFormOne" id="registerFormOne" method="post" action="">
    <!-- LogIn Page -->
      <div class="container">
        <div class="loggin_view chng-pswd">
          <div class="main_panel">
            <div class="main_heading"> <?php echo $assArr['cHANGE_THE_PASSWORD'];?> </div>
            <div class="panel-body">
              <div class="form-group">
                <label><?php echo $assArr['current_Password'];?> <span class="error">*</span></label>
                <input type="password" class="form-control" name="passwordTxt" id="passwordTxt">
              </div>
              <div class="form-group">
                  <div class="pswd-img">
                        <label><?php echo $assArr['new_Password'];?> <span class="error">*</span></label>
                        <div class="tooltip"><img src="<?php echo JUri::base(); ?>/templates/protostar/images/i-icon.png">
                            <span class="tooltiptext">
                                <i class="fa fa-check" aria-hidden="true"></i> <?php echo $assArr['New_Password_rule3'];?> <br>
                                <i class="fa fa-check" aria-hidden="true"></i><?php echo $assArr['New_Password_rule4'];?>  <br>
                                <i class="fa fa-check" aria-hidden="true"></i> <?php echo $assArr['New_Password_rule5']?> <br>
                                <i class="fa fa-check" aria-hidden="true"></i><?php echo $assArr['New_Password_rule6'];?><br>
                                <i class="fa fa-check" aria-hidden="true"></i> <?php echo $assArr['New_Password_rule7'];?><br>
                            </span>
                        </div>
                        
                      </div>
                
                <input type="password" class="form-control" name="newpasswordTxt" id="newpasswordTxt" minlength="8">
              </div>
              <div class="form-group">
                <label><?php echo $assArr['Confirm_new_password'];?> <span class="error">*</span></label>
                <input type="password" class="form-control" name="cnewpasswordTxt" id="cnewpasswordTxt">
              </div>
              <div class="form-group">
                
                
              </div>
              <div class="form-group newpswdbtn">
                <button type="submit" class="btn btn-primary"><?php echo $assArr['submit'];?></button>
                <button class="btn btn-danger" type="reset"><?php echo $assArr['clear'];?></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- LogIn Page -->
    <input type="hidden" name="task" value="user.changepassword">
    <input type="hidden" name="id" value="0" />
    <input type="hidden" name="itemid" value="<?php echo $_GET['Itemid'];?>" />
    <input type="hidden" name="user" value="<?php echo $user;?>" />
  </form>
</div>
