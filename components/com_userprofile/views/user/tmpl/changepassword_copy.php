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
?>
<?php
$ch = curl_init();
$url="http://boxonsaasdev.inviewpro.com//api/ImgUpldFTP/ConvertResxXmlToJson?companyId=130&language=es";


curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resp = curl_exec($ch);

if($e= curl_error($ch)){
    echo $e;
}
else{
    $decoded = json_decode($resp,true);
    
    $res = json_decode($decoded['Data']);
    
    //echo '<pre>';
    //var_dump($res->data);
$assArr = [];

//$assArr[$res->data[0]->id] = $res->data[0]->text;

foreach($res->data as $response){

   $assArr[$response->id]  = $response->text;
   //echo $response->id;
  
}

//echo '<pre>';
//var_dump($assArr);
   
}  

curl_close($ch);

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
		"<?php echo JText::_('Enter Valid Password');?>"
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
			  passwordTxt: "<?php echo Jtext::_('COM_USERPROFILE_CHANGEPASS_CURR_PASS_ERR');?>",
			  newpasswordTxt: {
                  required: "<?php echo Jtext::_('COM_USERPROFILE_CHANGEPASS_NEW_PASS_ERR');?>",
                  minlength: "Your password must be at least 8 characters long"
              },
              cnewpasswordTxt: "<?php echo Jtext::_('COM_USERPROFILE_CHANGEPASS_CONFIRM_PASS_ERR');?>"
      
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
          alert("New Password should not be equal to Current Password");
          $joomla(this).val("");
          $joomla(this).focus();
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
            <div class="main_heading"> <?php echo Jtext::_('COM_USERPROFILE_CHANGEPASS_TITLE');?> </div>
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
                                <i class="fa fa-check" aria-hidden="true"></i> Atleast 1 uppercase letter <br>
                                <i class="fa fa-check" aria-hidden="true"></i> Atleast 1 digit <br>
                                <i class="fa fa-check" aria-hidden="true"></i> Atleast 1 special character <br>
                                <i class="fa fa-check" aria-hidden="true"></i> Atleast 8 charecters<br>
                                <i class="fa fa-check" aria-hidden="true"></i> Maximum 32 charecters<br>
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
                <button type="submit" class="btn btn-primary"><?php echo Jtext::_('COM_USERPROFILE_CHANGEPASS_BTN_TXT');?></button>
                <button class="btn btn-danger" type="reset"><?php echo Jtext::_('COM_USERPROFILE_CHANGEPASS_CLEAR');?></button>
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