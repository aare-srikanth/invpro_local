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

require_once JPATH_ROOT.'/components/com_register/helpers/register.php';

$companyId=base64_decode(JRequest::getVar('ci'));

        $hostnameStr = $_SERVER['HTTP_HOST'];
        $hostnameStr = str_replace("www.","",$hostnameStr);
        $hostnameArr = explode(".",$hostnameStr);
        $domainurl = $hostnameArr[1].".".$hostnameArr[2];
        
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
			  unameTxt: "required"
			},
			// Specify validation error messages
			messages: {
			  unameTxt: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_EMAIL_USERNAME_FIELD'); ?>",
              emailTxt: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_VALID_EMAIL'); ?>"
      
			},
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			submitHandler: function(form) {
			    $joomla(".page_loader").show();
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
      <div class="container">
        <div class="loggin_view frgt-pswd">
          <div class="main_panel">
            <div class="main_heading"> <?php echo JText::_('COM_REGISTER_FORGOT_PASSWORD'); ?> </div>
            <div class="panel-body">
                <div class="form-group">
                  <label><?php echo JText::_('COM_REGISTER_USERNAME_LABEL'); ?><span class="error">*</span></label>
                  <input type="text" class="form-control" name="unameTxt" id="unameTxt">
                </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary"><?php echo JText::_('COM_REGISTER_SUBMIT'); ?></button>
                <a class="btn btn-danger pageloader_link" href="<?php echo JRoute::_('index.php?option=com_register&view=login'); ?>"><?php echo JText::_("COM_REGISTER_CANCEL"); ?></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    <input type="hidden" name="task" value="register.forgotpassword">
    <input type="hidden" name="id" />
    <input type="hidden" name="domainurl" value="<?php echo $domainurl;  ?>" />
    <input type="hidden" name="itemid" value="<?php echo $_GET['Itemid'];?>" />
    <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'];?>" />
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
    <button class="btn" data-dismiss="modal">Close</button>
    <a href="<?php echo JRoute::_('index.php?option=com_register&task=register.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger"> <?php echo JText::_('COM_REGISTER_DELETE_ITEM'); ?> </a> </div>
</div>
<?php endif; ?>
