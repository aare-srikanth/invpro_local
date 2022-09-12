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

$agentId=RegisterHelpersRegister::getAgentId();

$companyId=base64_decode(JRequest::getVar('ci'));
if($companyId != null){
    
    $companyName=Controlbox::getCompanyName($companyId);
    $domainName=explode(" ",$companyName);
    $domainEmail="support@".strtolower($domainName[0]).".com";
    
}else{
    
        $serverName = explode(".",$_SERVER['SERVER_NAME']);
        $domainName = explode("-",$serverName[0]);
        $domainName = $domainName[0];
        $domainEmail= "support@".$domainName.".com";
        $companyName = $domainName;
        
}

?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    
    
     //validation for email fields
	jQuery.validator.addMethod(
	"validateEmail", 
	function(value, element) {
	    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(value);
		},
		"<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_VALID_EMAIL_ADDRESS');?>"
	);
    
	// Wait for the DOM to be ready
	$joomla(function() {
	
		// Initialize form validation on the registration form.
		// It has the name attribute "registration"
		$joomla("form[name='registerFormOne']").validate({
			
			// Specify validation rules
				ignore: ".ignore",
			rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                fnameTxt: {
                  required: true,
                  alphanumeric:true
                },     
                lnameTxt: {
                  required: true,
                  alphanumeric:true
                },     
                addressTxt: "required",
                zipTxt: {
                  /*required: true,
                  minlength: 5,*/
                  number: true
                },
                dialcodeTxt: {
                    required: true,
                    selectBox: true
                },
                phoneTxt: {
                    required: true,
                    minlength:7,
                    maxlength:10,
                    number: true
                },
                emailTxt: {
                    required: true,
                    // Specify that email should be validated
                    // by the built-in "email" rule
                    validateEmail: true,
                    remote:{
                        url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=agentregister.get_ajax_data&emailid="+$joomla("#emailTxt").val() +"&emailflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                        type: "get"
                    }    
                },
                countryTxt: {
                    required: true,
                    selectBox: true
                },
                stateTxt: {
                    required: true,
                    selectBox:true
                }/*,
                cityTxt: {
                    required: true
                }*/,
                passwordTxt: {
                    required: true,
                    minlength: 5
                },
                confirmpasswordTxt: {
                    required: true,
                    minlength: 5,
                    equalTo: "#passwordTxt"
                },
                termsTxt: {
                    required: true
                },
                hiddenRecaptcha: {
                required: function () {
                    if (grecaptcha.getResponse() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
             }
			},
			// Specify validation error messages
			messages: {
			  fnameTxt: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_AGENCY_NAME');?>",
			  lnameTxt: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_PERSONAL_NAME');?>",
			  addressTxt:{
			      required: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_SHIPPING_ADDRESS');?>"
			  }/*,
			  zipTxt:{
			      required: "Please enter postal code"
			  }*/,
			  phoneTxt:{
			      required: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_PHONE_NUMBER');?>",
			      minlength: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_MUST_7_NUMBERS');?>"
			  },
			  passwordTxt: {
                  required: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_PASSWORD');?>",
                  minlength: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_MUST_5_CHARATERS');?>"
              },
              confirmpasswordTxt: {
                  required: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_CONFIRM_PASSWORD');?>",
                  minlength: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_CONFIRM_MUST_5_CHARATERS');?>",
                  equalTo: "<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_CONFIRM_NOT_MATCH');?>"
              },
              emailTxt:{
                  required:"<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_VALID_EMAIL_ADDRESS');?>",
                  remote:"<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_ALREADY');?>"
              }, 
              dialcodeTxt:{
                  selectBox:"<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_DIAL_CODE');?>"
              },
              countryTxt: {
				selectBox: "<?php echo Jtext::_('COM_REGISTER_PLEASE_SELECT_COUMTRY');?>"
			  },
              stateTxt: {
				required: "<?php echo Jtext::_('COM_REGISTER_PLEASE_SELECT_STATE');?>",
				selectBox: "<?php echo Jtext::_('COM_REGISTER_PLEASE_SELECT_STATE');?>"
			  }/*,
              cityTxt: {
				required: "Please enter city"
			  }*/,
			  termsTxt: {
                    required: "<?php echo Jtext::_('COM_REGISTER_PLEASE_CHECKED');?>"
              }
      
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

	$joomla('#countryTxts').on('change',function(){
		$joomla('input[name="cityTxt"]').val('');
		$joomla('#stateTxt').val(0);
		$joomla('#cityTxt').html('');
		var countryID = $joomla(this).val();
		countryID=countryID.split(":");
		$joomla('#dialcodeTxt').val("+"+countryID[1]);
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=agentregister.get_ajax_data&countryid="+countryID[0]+"&stateflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
					$joomla('#stateTxt').html('<option value=""><?php echo Jtext::_('COM_REGISTER_PLEASE_SELECT_STATE');?></option>'+data);
				}
			});
		}else{
			$joomla('#stateTxt').html('<option value=""><?php echo Jtext::_('COM_REGISTER_PLEASE_SELECT_STATE');?></option>');
		}
	});


	
    //validation for all fields
	$joomla.validator.addMethod(
	"alphanumeric", 
	function(value, element) {
			return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
		},
		"<?php echo JText::_('ENTER_THE_NAME_ALPHANUMARIC_CHARACTORES');?>"
	);

    
		 /** validating the select box **/
	$joomla.validator.addMethod(
		"selectBox",
		function(value, element) {
			if (element.value == "none" || element.value == "0")
			{
				return false;
			}
			else {
				return true;
			}
		},
		"<?php //echo JText::_('Please select ');?>"
	);
$joomla("input[name='zipTxt']").keypress(function (e) {
      if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
      }
      if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    });
    $joomla("input[name='phoneTxt']").keypress(function (e) {
      if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
      }
      if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    });



	$joomla('#countryTxt').on('change',function(){
	    $joomla('input[name="cityTxt"]').val('');
		$joomla('#stateTxt').val(0);
		$joomla('#cityTxt').html('');
		var countryID = $joomla(this).val();
		countryID=countryID.split(":");
		$joomla('#dialcodeTxt').val("+"+countryID[1]);
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&countryid="+countryID[0] +"&stateflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
					$joomla('#stateTxt').html(data);
					//$joomla('#cityTxt').html(''); 
				}
			});
		}else{
			//$joomla('#stateTxt').html('<option value="">Select State</option>');
			//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		}
	});

	$joomla('#stateTxt').on('change',function(){
	    $joomla('input[name="cityTxt"]').val('');
		$joomla('#cityTxt').html('');
		var stateID = $joomla(this).val();
		if(stateID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&stateid="+$joomla("#stateTxt").val() +"&cityflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
					$joomla("#cityTxt").append(data);
				}
			}); 
		}else{
			//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		}
	});

    $joomla("input[name='cityTxt']").change(function(){
        var val = $joomla(this).val()
        var xyz = $joomla('#cityTxt option').filter(function() {
            return this.value == val;
        }).data('xyz');
        if(xyz){
            $joomla(this).val(val);
        }
        $joomla("input[name='cityTxtdiv']").val(xyz);
    });	
    
});
</script>

<div class="item_fields">
  <form name="registerFormOne" id="registerFormOne" method="post" action="">
      <div class="container">
        <div class="register_view">
          <div class="main_panel">
            <div class="main_heading"> <?php echo Jtext::_('COM_REGISTER_AGENTREGISTRATION');?> </div>
            <div class="panel-body">
              <h4 class="heading"><?php echo Jtext::_('COM_REGISTER_PERSONAL_INFORMATION');?></h4>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_AGENCY_NAME_LABEL');?><span class="error">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="20">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12 lname">
                        <label><?php echo Jtext::_('COM_REGISTER_PERSON_NAME_LABEL');?></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" name="lnameTxt" id="lnameTxt" maxlength="20">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_ADDRESS_1_LABEL');?> <span class="error">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" name="addressTxt" id="addressTxt">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_ADDRESS_2_LABEL');?></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" name="address2Txt" id="address2Txt">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_ZIP_LABEL');?> </label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" name="zipTxt" id="zipTxt">
                      </div>
                    </div>
                  </div>
                  
                  

                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_AGENT_ID_LABEL');?></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                         <input type="text" class="form-control" name="agentidTxt" id="agentidTxt" value="<?php echo $agentId;?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_EMAIL_LABEL');?>  <span class="error">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" name="emailTxt" id="emailTxt" maxlength="50">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_COUNTRY_LABEL');?>  <span class="error">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <?php
					       $countryView= RegisterHelpersRegister::getCountriesList();
					       $arr = json_decode($countryView); 
                           $countries='';
					       foreach($arr->Data as $rg){
                               $countries.= '<option value="'.$rg->CountryCode.':'.preg_replace('/[^0-9]/', '', $rg->CountryDailCodes).'">'.$rg->CountryDesc.'</option>';
                           }
             
    					?>
                        <select class="form-control" id="countryTxt" name="countryTxt">
                          <option value="0"><?php echo Jtext::_('COM_REGISTER_SELECT_COUNTRY_LABEL');?></option>
                          <?php echo $countries;?>
                        </select>
                      </div>
                    </div>
                  </div>
      
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_STATE_LABEL');?> <span class="error">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <select class="form-control" id="stateTxt" name="stateTxt">
                        </select>
                      </div>
                    </div>
                  </div>
 
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_CITY_LABEL');?> </label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="text" class="form-control"  name="cityTxt" list="cityTxt" />
                    	<datalist id="cityTxt"></datalist><input type="hidden" name="cityTxtdiv" id="cityTxtdiv">
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_PHONE_NUMBER_LABEL');?> <span class="error">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <div class="row">
                          <div class="col-sm-6 col-md-6">
                            <?php
                             $dialcodeView= RegisterHelpersRegister::getdialcodeList();
                             $arr = json_decode($dialcodeView); 
                             $dialcode='';
                               foreach($arr->Data as $rg){
                               if($rg->Description)
                                  $dialcode.= '<option value="'.$rg->DailCode.'">'.$rg->Description.'</option>';
                              } ?>
                            <select class="form-control" id="dialcodeTxt" name="dialcodeTxt">
                              <option value="0">Select Dialcode</option>
                              <?php echo $dialcode;?>
                            </select>
                          </div>
                          <div class="col-sm-6 col-md-6">
                            <input type="text" class="form-control" name="phoneTxt" id="phoneTxt">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
          
                </div>
              </div>
              <h4 class="heading"><?php echo Jtext::_('COM_REGISTER_INFORMATION_LABEL');?></h4>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_PASSWORD_LABEL');?> <span class="error">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="password" class="form-control" name="passwordTxt" id="passwordTxt">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-xs-12">
                        <label><?php echo Jtext::_('COM_REGISTER_CONFIRM_LABEL');?> <span class="error">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-6 col-xs-12">
                        <input type="password" class="form-control" name="confirmpasswordTxt" id="confirmpasswordTxt">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <h4 class="heading"><?php echo str_replace('XXXX',strtoupper($companyName),Jtext::_('COM_REGISTER_TERM_LABEL'));?></h4>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <textarea class="form-control" cols="20" readonly="readonly" rows="2" style="width: 100%; height: 180px !important;" aria-invalid="false" name="checkedTxt" id="checkedTxt"><?php echo str_replace('XXXX',strtoupper($companyName),Jtext::_('COM_REGISTER_TERM_TEXT_LABEL')); ?></textarea>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group acpt_trms_err">
                    <label>
                    <input type="checkbox" name="termsTxt" id="termsTxt" value=1>
                    <?php echo Jtext::_('COM_REGISTER_ACCEPT_TERM_LABEL');?> <span class="error">*</span></label>
                  </div>
                </div>
                
                 <div class="col-sm-12">
                <div class="g-recaptcha" data-sitekey="6LcRd4waAAAAAP91YPspA6aVpXbtbKEt_U1D-Z1Y" data-callback="recaptchaCallback"></div>
                <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                </div>
                
                <div class="col-sm-12">
                  <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">CREATE ACCOUNT</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <input type="hidden" name="task" value="agentregister.save">
    <input type="hidden" name="id" value="0" />
  </form>
</div>

<script>
function recaptchaCallback() {
  $joomla('#hiddenRecaptcha').valid();
};
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

