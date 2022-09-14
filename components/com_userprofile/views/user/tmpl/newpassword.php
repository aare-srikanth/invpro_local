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
$session = JFactory::getSession();
$document->setTitle("Change Password in Boxon Pobox Software");
defined('_JEXEC') or die;

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
				minlength: 4
              },
			  cnewpasswordTxt: {
				required: true,
				minlength: 4,
				equalTo:"#newpasswordTxt"
              }
			},
			// Specify validation error messages
			messages: {
			  passwordTxt: "Please enter Old Password",
			  newpasswordTxt: {
                  required: "<?php echo Jtext::_('COM_USERPROFILE_PROVIDE_NEW_PASSWORD');  ?>",
                  minlength: "<?php echo Jtext::_('COM_USERPROFILE_PASSWORD_VALIDATION');  ?>"
              },
              cnewpasswordTxt: "<?php echo Jtext::_('COM_USERPROFILE_CONFIRM_PASSWORD_ERROR');  ?>"
      
			},
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			submitHandler: function(form) {
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

<div class="item_fields">


  <form name="registerFormOne" id="registerFormOne" method="post" action="">
    <!-- LogIn Page -->
      <div class="container">
        <div class="loggin_view">
          <div class="main_panel">
            <div class="main_heading"> <?php echo Jtext::_('COM_USERPROFILE_NEW_PASSWORD_TITLE'); ?> </div>
            <div class="panel-body">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_NEW_PASSWORD'); ?><span class="error">*</span></label>
                <input type="password" class="form-control" name="newpasswordTxt" id="newpasswordTxt">
              </div>
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_CONFIRM_PASSWORD'); ?> <span class="error">*</span></label>
                <input type="password" class="form-control" name="cnewpasswordTxt" id="cnewpasswordTxt">
              </div>
              <div class="form-group">
                
                
              </div>
              <div class="form-group newpswdbtn">
                <button type="submit" class="btn btn-primary"><?php echo Jtext::_('COM_USERPROFILE_SUBMIT'); ?></button>
                <button class="btn btn-danger" type="reset"><?php echo Jtext::_('COM_USERPROFILE_CLEAR'); ?></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- LogIn Page -->
    <input type="hidden" name="task" value="user.newpassword">
    <input type="hidden" name="id" value="0" />
    <input type="hidden" name="itemid" value="<?php echo $_GET['Itemid'];?>" />
    <input type="hidden" name="resetToken" value="<?php echo $_GET['resetToken'];?>" />
    <input type="hidden" name="user" value="<?php echo $_GET['CustId'];?>" />
    <input type="hidden" name="companyId" value="<?php echo base64_decode($_GET['ci']);?>" />
  </form>
</div>
