<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Register
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$res=JRequest::getVar('res');
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$domainName =  $domainDetails[0]->Domain;

//var_dump($domainDetails);exit;

if($user){
$app =& JFactory::getApplication();
$app->redirect('index.php?option=com_userprofile&view=user');
//$this->setRedirect(JRoute::_('', false));
}
require_once JPATH_ROOT.'/components/com_register/helpers/register.php';


$canEdit = JFactory::getUser()->authorise('core.edit', 'com_register');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_register'))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}

 
?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {

	$joomla(":reset").on('click',function(){
        $joomla('label.error').hide();
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
			  unameTxt: "required",
			  passwordTxt: {
				required: true,
				minlength: 4
              }
			},
			// Specify validation error messages
			messages: {
			  unameTxt: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_USERNAME'); ?>",
			  passwordTxt: {
                  required: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_PASSWORD'); ?>",
                  minlength: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_MUST_5_CHARACTERS'); ?>"
              },
              emailTxt: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_VALID_EMAIL'); ?>"
      
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

	// clear text start
	
	$joomla(".clearable__clear").hide();
	
	$joomla("input").on('keyup',function(){
	    
	    in_length = $joomla(this).val().length;
	    
	    if(in_length > 0){
	    
	    $joomla(this).parent().find(".clearable__clear").show();
	    
	    }else{
	        
	        $joomla(this).parent().find(".clearable__clear").hide();
	    }
	    
	});
	
	$joomla(".clearable__clear").on('click',function(){
        $joomla(this).parent().find("input").val("");
        $joomla(this).hide();
	});
	
	$joomla(".btn-danger").on('click',function(){
	    $joomla(".clearable__clear").hide();
	});

	
	// clear text end


});
</script>

<style>

.clearable__clear{
  font-style: normal;
    font-size: 2em;
    user-select: none;
    cursor: pointer;
    position: absolute;
    top: 24px;
    right: 10px;
    opacity:0.2;
}
.lognew_sec{
    position: relative;
}

</style>

<div class="item_fields">


  <form name="registerFormOne" id="registerFormOne" method="post" action="">
    <!-- LogIn Page -->
      <div class="container">
          
          
          
         <div class="col-md-4 col-sm-12">
            <div class="loggin_view">
          <div class="main_panel">
            <div class="main_heading"> <?php echo JText::_('COM_REGISTER_LOGIN_LABEL'); ?> </div>
            
            <div class="panel-body">
                
                 <!--Error Msg-->
            
            <?php 
            
            if($res == '0' ){
                $errorMsg = JText::_('COM_REGISTER_LOGIN_ERROR');
           ?>
             
             <div class="alertmsgsec" >   
                <div class="alert alert-danger alert-dismissible"  role="alert">
                    <span class="login-errormsg"><?php echo $errorMsg;  ?></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div> 
            
            <?php
            
            }
            
            ?>
                
              <div class="form-group lognew_sec">
                <label><?php echo JText::_('COM_REGISTER_USERNAME_LABEL'); ?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="unameTxt" id="unameTxt">
                <i class="clearable__clear">&times;</i>
              </div>
              <div class="form-group lognew_sec">
                <label><?php echo JText::_('COM_REGISTER_PASSWORD_LABEL'); ?> <span class="error">*</span></label>
                <input type="password" class="form-control" name="passwordTxt" id="passwordTxt">
                <i class="clearable__clear">&times;</i>
              </div>
              <div class="form-group">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"><?php echo JText::_('COM_REGISTER_LOGIN_LABELS'); ?></button>
                <!--<button class="btn btn-danger btn-block" type="reset"><?php echo JText::_('COM_REGISTER_CLEAR'); ?></button>-->
               <a class="btn btn-link btn-block pageloader_link" href="<?php echo JRoute::_('index.php?option=com_register&view=forgotpassword'); ?>"><?php echo JText::_("COM_REGISTER_FORGOT_PASSWORD"); ?></a>
                <!-- <a href="<?php //echo JRoute::_('index.php?option=com_register&view=agentregister'); ?>" class="btn btn-primary btn-block">Agent Registration</a>-->
                <!-- <a href="<?php //echo JRoute::_('index.php?option=com_register&view=register'); ?>" class="btn btn-primary btn-block">New User?</a>-->
              </div>
            </div>
          </div>
        </div>
        </div>
         <div class="col-md-8 col-sm-12 ntfctin-blk">
               <div class="">
               <div class="main_panel login-frm">
            <div class="main_heading">
               <?php echo JText::_('COM_REGISTER_NOTIFICATIONS_TITLE'); ?>
            </div>
            <div class="panel-body notification_panel" >
               <?php
               $mainPageDetails = RegisterHelpersRegister::getmainpagedetails();
               
               if(!isset($mainPageDetails)){
                  echo '<img src="'.JURI::base().'/images/cmg-soon-image.png" >';
               }
               
               $config = JFactory::getConfig();
               
              if(strtolower($domainName) != "kupiglobal"){
               
                   foreach($mainPageDetails as $data){
                        $str = '$id';
                       echo '<div class="row ntifiction-info"><a href="index.php/en/component/register/notifications?Itemid=131#'.$data->$str.'" >'.$data->Heading.'</a></div>';
                       
                            // $doc = new DOMDocument();
                            // $doc->loadHTML($data->Content);
                            // $tags = $doc->getElementsByTagName('img');
                            
                            // foreach ($tags as $tag) {
                            //     $oldSrc = $tag->getAttribute('src');
                            //     $newScrURL = $config->get('backend_url').$oldSrc;
                            //     $tag->setAttribute('src', $newScrURL);
                            //     $tag->setAttribute('data-src', $oldSrc);
                            // } 
                            
                            // $htmlString = $doc->saveHTML();
                            // print($htmlString);
                        
                   }
               
              }else{
                  foreach($mainPageDetails as $data){
                    $str = '$id';
                   echo '<div id="'.$data->$str.'" class="ntifiction-info" id="notification"><h4>'.$data->Heading.'</h4>';
                   
                        $doc = new DOMDocument();
                        $doc->loadHTML($data->Content);
                        $tags = $doc->getElementsByTagName('img');
                        
                        foreach ($tags as $tag) {
                            $oldSrc = $tag->getAttribute('src');
                            $newScrURL = $config->get('backend_url').$oldSrc;
                            $tag->setAttribute('src', $newScrURL);
                            $tag->setAttribute('data-src', $oldSrc);
                        } 
                        
                        $htmlString = $doc->saveHTML();
                        echo '<p>'.$htmlString.'</p></div>';
                    
               }
              }
               ?>
            </div>
          </div>
          </div>
          </div>
      </div>
    <!-- LogIn Page -->
    <input type="hidden" name="task" value="register.login">
    <input type="hidden" name="id" value="0" />
    <input type="hidden" name="itemid" value="<?php echo $_GET['Itemid'];?>" />
  </form>
</div>
<?php if($canEdit): ?>
<a class="btn" href="<?php echo JRoute::_('index.php?option=com_register&task=register.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_REGISTER_EDIT_ITEM"); ?></a>
<?php endif; ?>
<?php if (JFactory::getUser()->authorise('core.delete','com_register.register.'.$this->item->id)) : ?>
<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal"> <?php echo JText::_("COM_REGISTER_DELETE_ITEM"); ?> </a>
<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?php echo JText::_('COM_REGISTER_DELETE_ITEM'); ?></h3>
  </div>
  <div class="modal-body">
    <p><?php echo JText::sprintf('COM_REGISTER_DELETE_CONFIRM', $this->item->id); ?></p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal"><?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_USERNAME'); ?>Close</button>
    <a href="<?php echo JRoute::_('index.php?option=com_register&task=register.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger"> <?php echo JText::_('COM_REGISTER_DELETE_ITEM'); ?> </a> </div>
</div>
<?php endif; ?>
