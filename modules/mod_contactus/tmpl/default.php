<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_contactus
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if($_POST['btnCalculate']){
	$resWp=ModContactusHelper::getMailContact();
}
?>
<div class="contactus<?php echo $moduleclass_sfx; ?>" <?php if ($params->get('backgroundimage')) : ?> style="background-image:url(<?php echo $params->get('backgroundimage'); ?>)"<?php endif; ?> > <?php echo $module->content; ?> </div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function(){

    /*$joomla("input[name='btnCalculate']").click(function(e){
        e.preventDefault();
          $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatingtype=1&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+$joomla('select[name="txtShipperType"]').val() +"&stype="+$joomla('select[name="txtShipperType"]').val() +"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLength"]').val() +"&width="+$joomla('input[name="txtWidth"]').val() +"&height="+$joomla('input[name="txtHeight"]').val() +"&qty="+$joomla('input[name="txtQuantity"]').val() +"&gwt="+$joomla('input[name="txtWeight"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val()+"&volume="+$joomla('input[name="txtVolume"]').val()+"&valuemetric="+$joomla('input[name="VolumetricWeight"]').val()+"&getcalculatingflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "getpackagetype": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#loading-image").show();
           },success: function(data){
              //alert(data);
              var cospor=data;
		      cospor=cospor.split(":");
		      $joomla('input[name=txtChargeableWeight]').val(cospor[0]);
		      $joomla('input[name=txtShippingCost]').val(cospor[1]);
		    }
		});
    });*/  
   
    // Wait for the DOM to be ready
    $joomla(function() {
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormOne']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          txtName: {
            required: true
          },
          txtEmail: {
            required: true,email:true
          },
          txtPhone: {
            required: true
          },
          txtMessage: {
            required: true
          }
        },
        // Specify validation error messages
        messages: {
          txtName: "Please enter your Full Name",
          txtEmail:{required:"Please enter your Email"},
          txtPhone: "Please enter your Phone Number",
          txtMessage: "Please enter your Description"
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
<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading">Contactus</div>
    <div class="panel-body">
      <form name="userprofileFormOne" id="userprofileFormOne" method="post" action="">
        <div class="row">
        <div class="col-sm-6 col-md-6">
          <div class="form-group">
          <p>How can we help you?</p>
          <div class="row">
            <div class="col-sm-12">
              <h4 class="sub_title"><strong>Dimensional Weight Contactus</strong></h4>
            </div>
          </div>            
          <div class="row">
              <div class="col-sm-12 col-md-12 text-left">
                <label>Full Name <span class="error">*</span></label>
                <input type="text" name="txtName" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-12 text-left">
                <label>Email <span class="error">*</span></label>
                <input type="text" name="txtEmail" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-12 text-left">
                <label>Phone <span class="error">*</span></label>
                <input type="text" name="txtPhone" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-12 text-left">
                <label>Message <span class="error">*</span></label>
                <textarea name="txtMessage" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12 text-center">
              <input type="submit" name="btnCalculate" value="Submit" class="btn btn-primary">
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-6">
          <div class="form-group"><div class="row">
            <div class="contact-addright">
              <div class="crgt-in">
                <h3 class="sub_title">OUR OFFICE ADDRESS</h3>
                <h4>Main Office</h4>
                <p>Boxon Logistics Inc.</p>
                <p>7801 NW 37th St.</p>
                <p>Miami, FL 33195</p>
                <p>Tel. 305-908-7957</p>
                <p>E-mail: support@boxonlogistics.com</p>
              </div>
              <div class="crgt-in">
                <h4>Office Hours :</h4>
                <p>Monday through Friday</p>
                <p>9:00 AM to 5:00 PM</p>
              </div>
              <div class="crgt-in">
                <h4>Customer Service :</h4>
                <p>Telephone: 786-693-9049</p>
                <p>FAX:305-594-4456</p>
                <p>E-mail: support@boxonlogistics.com</p>
              </div>
            </div>
            </div>
          </div>
        </div>
     
    </div>
      </form>
  </div>
</div>
</div>
