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
$document->setTitle("User Personal Information in Boxon Pobox Software");
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$pass=$session->get('user_casillero_password');
$session->clear( 'userData');
$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$domainName =  $domainDetails[0]->Domain;

$menuAccessStr=Controlbox::getMenuAccess($user,$pass);
$menuCustData = explode(":",$menuAccessStr);
$maccarr=array();
foreach($menuCustData as $menuaccess){
    
    $macess = explode(",",$menuaccess);
    $maccarr[$macess[0]]=$macess[1];
 
}

$menuCustType=end($menuCustData);


if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';
$UserView= UserprofileHelpersUserprofile::getUserpersonalDetails($user);
//$UserDocumentView= UserprofileHelpersUserprofile::getDocumentList($user);

// echo '<pre>';
// var_dump($UserView);exit;

$imgpt=explode('/com_userprofile/',$UserView->imagePath);
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
// var_dump($assArr['merchant_name']);
   
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

    $joomla('#DoumentDiv').html('<img src="<?php echo JURI::base(); ?>/components/com_userprofile/images/loader.gif"></div>');
	$joomla.ajax({
		url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&userid=<?php echo $user;?>&documentsflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
		data: { "userid": "<?php echo $user;?>" },
		dataType:"html",
		type: "get",
		async:true,
		success: function(data){
		  $joomla('#DoumentDiv').html(data);
		}
	});
			
    $joomla('.modal').on('hidden.bs.modal', function(){
        if($joomla(this).find('form')[0])
        $joomla(this).find('form')[0].reset();
    });
    if($joomla('#exampleModal').css('display')=="block"){
        $joomla('#exampleModal').modal({backdrop: 'static', keyboard: false})  
    }

   $joomla('.nav-tabs li a').click(function(){
     $joomla('.panel-body #formone').hide();
     $joomla('.panel-body #formtwo').hide();
     $joomla('.nav-tabs li a').removeClass('active');
      if( $joomla(this).html()=="<?php echo Jtext::_('COM_USERPROFILE_PI_TITLE2');?>"){
        $joomla('.panel-body #formtwo').show();
        $joomla('.nav-tabs li:last a').addClass('active');
      }
      else{
        $joomla('.panel-body #formone').show();
        $joomla('.nav-tabs li:first a').addClass('active');
      }
      return false;
    });

    var getC="<?php echo $_GET['c'];?>"; 
    if(getC==2){
       $joomla('.nav-tabs li a').removeClass('active');
       $joomla('.nav-tabs li:last a').addClass('active');
       $joomla('#formone').css('display','none');
       $joomla('#formtwo').css('display','block');
    }
     
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
			  firstName:{
					required: true
			  },
			  lastName:{
					required: true
			  },
			  dialcodeTxt:{
					required: true,
				selectBox: true
			  },
			  phoneTxt: {
                required: true,
                minlength:7,
                maxlength:10,
                number: true
             },
              aemailTxt: {
                // Specify that email should be validated
                // by the built-in "email" rule
                email: true
			  },
			  addressTxt: "required",
            countryTxt: {
 				required: true,
				selectBox: true
			  },
            stateTxt: {
 				required: true,
				selectBox: true
			  }/*,
            cityTxt: {
 				required: true,
				selectBox: true
			  }*/,
			  zipTxt: {
                  /*required: true,
			      minlength: 5,*/
			      number: true
              },
              anumberTxt:{
                  alphanumeric5:true
              },
              faxTxt:{
                  alphanumeric5:true
              }
            },
			// Specify validation error messages
			messages: {
			    firstName:{
				required: "<?php echo Jtext::_('Please Enter First Name');?>"
			  },
			  lastName:{
				required: "<?php echo Jtext::_('Please Enter Last Name');?>"
			  },
			   dialcodeTxt:{
				required: "<?php echo Jtext::_('COM_USERPROFILE_PI_DIAL_CODE_ERROR');?>",
				selectBox: "<?php echo Jtext::_('COM_USERPROFILE_PI_DIAL_CODE_ERROR');?>"
			  },
			  passwordTxt: {
                  required: "<?php echo Jtext::_('COM_USERPROFILE_PI_PASSWORD_ERROR');?>",
                  minlength: "<?php echo Jtext::_('COM_USERPROFILE_PI_PASSWORD_VALIDATION_ERROR');?>"
              }, 
               addressTxt:{
			      required: "<?php echo Jtext::_('COM_USERPROFILE_PI_SHIPPING_ADDRESS_ERROR');?>"
			  },
			  countryTxt: {
				required: "<?php echo Jtext::_('COM_USERPROFILE_PI_COUNTRY_ERROR');?>",
				selectBox: "<?php echo Jtext::_('COM_USERPROFILE_PI_COUNTRY_ERROR');?>"
			  },
              stateTxt: {
				required: "<?php echo Jtext::_('COM_USERPROFILE_PI_STATE_ERROR');?>",
				selectBox: "<?php echo Jtext::_('COM_USERPROFILE_PI_STATE_ERROR');?>"
			  }/*,
              cityTxt: {
				required: "Please select city",
				selectBox: "Please select city"
			  },
			  zipTxt:{
			      required: "Please enter zip code"
			  }*/,
              anumberTxt:{
                  alphanumeric5:"<?php echo Jtext::_('COM_USERPROFILE_PI_ALPHANUMERIC_ERROR');?>"
              },
              faxTxt:{
                  alphanumeric5:"<?php echo Jtext::_('COM_USERPROFILE_PI_ALPHANUMERIC_ERROR');?>"
              }
      
			},
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			submitHandler: function(form) {
			    $joomla('.page_loader').show();
				    //alert( $joomla('#stateTxt').val())
					// Returns successful data submission message when the entered information is stored in database.
					/*
					
					/*,
                remote:{
                	url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&emailid="+$joomla("#emailTxt").val() +"&emailflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
					type: "get"
              	}
					$.post("http://boxon.justfordemo.biz/index.php/register", {
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
	$joomla.validator.addMethod("alphanumeric5", function(value, element) {
            return this.optional(element) || /^[0-9/-]+$/.test(value);
        });
        
        //validation for email fields
	jQuery.validator.addMethod(
	"validateEmail", 
	function(value, element) {
	    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(value);
		},
		"<?php echo JText::_('COM_USERPROFILE_PLEASE_ENTER_VALID_EMAIL_ADDRESS');?>"
	);
 
		// Initialize form validation on the registration form.
		// It has the name attribute "registration"
		$joomla("form[name='userprofileFormThree']").validate({
			
			// Specify validation rules
			rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
    			fnameTxt:{
    				required: true,
    			    alphanumeric:true 
    			},
    			lnameTxt:{
    				required: true,
    			    alphanumeric:true 
    			},
    			typeuserTxt:{
    				required: true
    			},
                country2Txt: {
                 required: true,
                 selectBox: true
                },
                state2Txt: {
                 required: true,
                 selectBox: true
                }/*,
                city2Txt: {
                 required: true
                }*/,
                addressTxt: "required",
                zipTxt: {
                  required: true,
                  number: true
                },
                idtypeTxt:{
                    required: true
                },
                idvalueTxt:{
                    required: true
                },
                 emailTxt: {
                    validateEmail : true
                 }
            },
			// Specify validation error messages
			messages: {
    			fnameTxt:{
    			    required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_FIRST_NAME_ERROR');?>",
    			    alphanumeric: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_ALPHABET_ERROR');?>"
    			},
    			lnameTxt:{
    			    required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_LAST_NAME_ERROR');?>",
    			    alphanumeric: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_ALPHABET_ERROR');?>"
    			},
    			typeuserTxt:{
    			    required: "<?php echo Jtext::_('Please select user type');?>"
    			},
                country2Txt: {
                 required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_COUNTRY_ERROR');?>",
                 selectBox: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_COUNTRY_ERROR');?>"
                },
                state2Txt: {
                 required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_STATE_ERROR');?>",
                 selectBox: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_STATE_ERROR');?>"
                },
                idtypeTxt: {
                 required: "Please select Identity Type"
                },
                idvalueTxt: {
                 required: "Please enter Identity Value"
                },
                addressTxt:{
                  required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_ADDRESS_ERROR');?>"
                },
                zipTxt:{
                  required: "Please enter zip code"
                }
                
			},
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			submitHandler: function(form) {
			    $joomla('.page_loader').show();
                $joomla("input[name=fnameTxt] #errorTxt-error").html('');
     		    $joomla.ajax({
    				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&userid=<?php echo $user;?>&fnameid="+$joomla("#fnameTxt").val() +"&lnameid="+$joomla("#lnameTxt").val() +"&fnameflag=1&jpath=%2Fhome%2Fdemodelivery%2Fpublic_html&pseudoParam="+new Date().getTime(),
    				data: { "lnameid": $joomla("#lnameTxt").val(),"fnameid": $joomla("#fnameTxt").val() },
    				dataType:"html",
    				type: "get",
    				cache: false,             
                    processData: false, 
    				success: function(data){
    				    console.log(data);
    				    if(data==1){
    				      if($joomla("#fnameTxt #errorTxt-error")){
    				       $joomla('#fnameTxt').after('<label id="errorTxt-error" class="error" for="errorTxt"><?php echo Jtext::_('COM_USERPROFILE_PI_ADDITIONAL_FULL_NAME_EXISTED');?> </label>');    
    				      }    				      
    				    }else{
    				      if($joomla("#fnameTxt #errorTxt-error")){
    				       $joomla("#fnameTxt #errorTxt-error").html('');
    				      }
    				      form.submit(); 
    				    }
    				}
    			});
			}
		});


$joomla('#accounttypeTxt').on('change',function(){
	    if($joomla(this).val()=="Individual"){
	        $joomla('.col-xs-12 label:first').html('<label>FIRST NAME <span class="error">*</span></label>');
	        $joomla('.col-xs-12 label:nth-last-child(2)').text('LAST NAME');
	    }else{
	        $joomla('.col-xs-12 label:first').html('<label>BUSINESS NAME <span class="error">*</span></label>');
	        $joomla('.col-xs-12 label:nth-last-child(2)').text('CONTACT PERSON');
	    }
	});


	$joomla('#countryTxt').on('change',function(){
		var countryID = $joomla(this).val();
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#countryTxt").val() +"&stateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				beforeSend : function(){
				    $joomla(".page_loader").show();
				},
				success: function(data){
					$joomla('#stateTxt').html(data);
					$joomla(".page_loader").hide();
				}
			});
		}
		$joomla('#stateTxt').html('<option value="">Select State</option>');
		//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		$joomla('#zipTxt').val(''); 


	});
	if($joomla("#stateTxt")){
    	$joomla.ajax({
    		url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#stateTxt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    		data: { "state": $joomla("#countryTxt").val() },
    		dataType:"html",
    		type: "get",
    		success: function(data){
    		   $joomla("#cityTxt").append(data);
    		}
    	}); 
	}
// 	$joomla("input[name='cityTxt']").blur(function(){
        
//         var val = $joomla(this).val()
//         var xyz = $joomla('#cityTxt option').filter(function() {
//             return this.value == val;
//         }).data('xyz');
//         if(xyz){
//             $joomla(this).val(val);
//         }
//         $joomla("input[name='cityTxtdiv']").val(xyz);
//     });

	$joomla('#stateTxt').on('change',function(){
	    $joomla('input[name="cityTxt"]').val('');
		$joomla('#cityTxt').html('');
		var stateID = $joomla(this).val();
		if(stateID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#stateTxt").val()+"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				beforeSend:function(){
					$joomla(".page_loader").show();
				},
				success: function(data){
					$joomla("#cityTxt").append(data);
					$joomla(".page_loader").hide();
				}
			}); 
		}
		//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		$joomla('#zipTxt').val(''); 

	});   
	$joomla('#country2Txt').on('change',function(){
	   
    	$joomla('input[name="city2Txt"]').val('');
		$joomla('#state2Txt').val(0);
		$joomla('#city2Txt').html('');
		var countryID = $joomla(this).val();
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#country2Txt").val() +"&stateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				beforeSend:function(){
				    $joomla('.page_loader').show();
				},
				success: function(data){
					$joomla('#state2Txt').html(data);
					$joomla('.page_loader').hide();
					//$joomla('#city2Txt').html('<option value="">Select City</option>'); 
				}
			});
		}
		$joomla('#state2Txt').html('<option value="">Select State</option>');
		//$joomla('#city2Txt').html('<option value="">Select City</option>'); 
		$joomla('#zip2Txt').val(''); 
	});

	$joomla('#state2Txt').on('change',function(){
	    $joomla('input[name="city2Txt"]').val('');
		$joomla('#city2Txt').html('');
		var stateID = $joomla(this).val();
		if(stateID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#state2Txt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				beforeSend:function(){
				    $joomla('.page_loader').show();
				},
				success: function(data){
					$joomla('#city2Txt').append(data);
					$joomla('.page_loader').hide();
				}
			}); 
		}else{
			//$joomla('#city2Txt').html('<option value="">Select City</option>'); 
		}
	});  
	
	$joomla('#country3Txt').on('change',function(){
    	$joomla('input[name="city3Txt"]').val('');
		$joomla('#state3Txt').val(0);
		$joomla('#city3Txt').html('');
		var countryID = $joomla(this).val();
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#country3Txt").val() +"&stateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#country3Txt").val() },
				dataType:"html",
				type: "get",
				beforeSend:function(){
				    $joomla('.page_loader').show();
				},
				success: function(data){
				    $joomla('#state3Txt').html(data);
					$joomla('#state3Txt').val($joomla('#state3Txtdiv').val());
					$joomla('.page_loader').hide();
				}
			});
		}
		$joomla('#state3Txt').html('<option value="">Select State</option>');
		//$joomla('#city3Txt').html('<option value="">Select City</option>'); 
	});

	$joomla('#state3Txt').on('change',function(){
	    $joomla('input[name="city3Txt"]').val('');
		$joomla('#city3Txt').html('');
		var stateID = $joomla(this).val();
		if(stateID==0){
		    stateID=$joomla('#state3Txtdiv').val();
		}
		if(stateID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+stateID +"&cityid="+$joomla('#city3Txtdiv').val()+"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#country3Txt").val() },
				dataType:"html",
				type: "get",
				beforeSend:function(){
				    $joomla('.page_loader').show();
				},
				success: function(data){
					console.log(stateID+'maddy:'+$joomla('#city3Txtdiv').val());
					$joomla('#city3Txt').append(data);
					$joomla('.page_loader').hide();
					//$joomla('#city3Txt').val($joomla('#city3Txtdiv').val());
				}
			}); 
		}else{
			//$joomla('#city3Txt').html('<option value="">Select City</option>'); 
		}
	});
	
    
	$joomla("input[name='city2Txt']").blur(function(){
        
        var val = $joomla(this).val()
        var xyz = $joomla('#city2Txt option').filter(function() {
            return this.value == val;
        }).data('xyz');
        if(xyz){
            $joomla(this).val(val);
        }
        $joomla("input[name='city2Txtdiv']").val(xyz);
     });

// 	$joomla("input[name='city3Txt']").blur(function(){
        
//         var val = $joomla(this).val()
//         var xyz = $joomla('#city3Txt option').filter(function() {
//             return this.value == val;
//         }).data('xyz');
//         if(xyz){
//             $joomla(this).val(val);
//         }
//         $joomla("input[name='city3Txtdiv']").val(xyz);
//     });
    

	
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
			else 
				return true;
		},
		"<?php echo JText::_('PLEASE_SELECT_THE_COUNTRY');?>"
	);
	$joomla('#formtwo .btn-primary:last').on('click',function(){
	   
       
        $joomla("#errorTxt-error").html('');
        if($joomla('input:file[name="photoFile"]').val()!="" || $joomla('input:file[name="formFile"]').val()!="" || $joomla('input:file[name="utilityFile"]').val()!="" || $joomla('input:file[name="otherFile"]').val()!="" ){
            if($joomla('input:file[name="photoFile"]').val()!=""){
                var ext = $joomla('input[name=photoFile]').val().split('.').pop();
               
                if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf']) == -1) {
                $joomla('input[name=photoFile]').after('<label id="errorTxt-error" class="error" for="errorTxt"><?php echo Jtext::_('COM_USERPROFILE_PI_INVALID_EXT_ERROR');?>!</label>');
                 return false;
                }else{
                    //$joomla(this).attr(false);
                    $joomla("input[name=photoFile] #errorTxt-error").html('');   
                    
                }
            }
            if($joomla('input:file[name="formFile"]').val()!=""){
                var ext2 = $joomla('input[name=formFile]').val().split('.').pop().toLowerCase();
                if($joomla.inArray(ext2, ['gif','png','jpg','jpeg','pdf']) == -1) {
                $joomla('input[name=formFile]').after('<label id="errorTxt-error" class="error" for="errorTxt"><?php echo Jtext::_('COM_USERPROFILE_PI_INVALID_EXT_ERROR');?>!</label>');
                  alert("test2");
                return false;
                }else{
                  $joomla("input[name=formFile] #errorTxt-error").html('');    
                }
            }
            if($joomla('input:file[name="utilityFile"]').val()!="" ){
                var ext3 = $joomla('input[name=utilityFile]').val().split('.').pop().toLowerCase();
                if($joomla.inArray(ext3, ['gif','png','jpg','jpeg','pdf']) == -1) {
                $joomla('input[name=utilityFile]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please check File invalid extension!</label>');
                  alert("test3");
                return false;
                }else{
                  $joomla("input[name=utilityFile] #errorTxt-error").html('');    
                }
            }
            if($joomla('input:file[name="otherFile"]').val()!=""){
                var ext4 = $joomla('input[name=otherFile]').val().split('.').pop().toLowerCase();
                if($joomla.inArray(ext4, ['gif','png','jpg','jpeg','pdf']) == -1) {
                $joomla('input[name=otherFile]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please check File invalid extension!</label>');
                 alert("test4");
                return false;
                }else{
                  $joomla("input[name=otherFile] #errorTxt-error").html('');    
                }
            } 
            
            $joomla('.page_loader').show();    
            $joomla('#userprofileFormTwo').submit();
           
         
        }else
        $joomla(this).after('<label id="errorTxt-error" class="error" for="errorTxt"><?php echo Jtext::_('COM_USERPROFILE_PI_UPLOAD_DOCUMENTS_ERROR');?></label>');
    });
	$joomla('#formtwo .btn-danger').on('click',function(){
	    if($joomla("input[name='downfile']:checked").val()){ 
            var user='<?php echo $user;?>';
    	    var rst=$joomla("input[name='downfile']:checked");
            var rsd=$joomla("input[name='downfile']:checked").val();
			rsd.split(":");
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&userid="+user+"&deletedowntype="+rsd[0]+"&deletedownflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "deletedown": rsd[0]},
				dataType:"html",
				type: "get",
				beforeSend:function(){
				    $joomla(".page_loader").show();
				},
				success: function(data){
				    $joomla("input[name='downfile']:checked").closest('.form-group').hide();
				    $joomla("input[name='downfile']").prop('checked',false);
				    $joomla(".page_loader").hide();
				    alert("<?php echo Jtext::_('COM_USERPROFILE_MYDOC_DELETE_SUCCESS') ?>");
				}
			}); 
        }else{
            
          $joomla('#myAlertModal').modal("show");
          alert("<?php echo Jtext::_('COM_USERPROFILE_MYDOC_SELECT_FILE_ALERT') ?>");
        } 
        return false;
        
	});
	$joomla('#formtwo .btn-primary:first').on('click',function(){
        if($joomla("input[name='downfile']:checked").val()){
            //var rsd=$joomla("input[name='downfile']:checked").val();
			//rsd=rsd.substring(2);
			//;
			$joomla('.page_loader').animate({width: 'toggle'});
			$joomla('#userprofileFormFour').submit();
			$joomla('.page_loader').animate({width: 'toggle'});
        }else
          $joomla('#myAlertModal').modal("show");
          $joomla('#error').html("Please select file names to download");
        
        return false;
	});
	$joomla('#a_table .btn-primary').on('click',function(){
	    var user='<?php echo $user;?>';
	    var fid=$joomla(this).data('id');
		$joomla.ajax({
	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&userid="+user+"&useridtype="+$joomla(this).data('id')+"&getadduserflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "useridtype":$joomla(this).data('id')},
			dataType:"html",
			type: "get",
			beforeSend: function(){
			    $joomla(".page_loader").show();
			},
			success: function(data){
			  $joomla(".page_loader").hide();
			  var dsd=data;
			  dsd=dsd.split(":");  
			 
			  $joomla("input[name='typeuserTxt']").val(dsd[0]);
			  $joomla("input[name='idTxt']").val(dsd[1]);
			  $joomla("input[name='fnameTxt']").val(dsd[2]);
			  $joomla("input[name='lnameTxt']").val(dsd[3]);
			  $joomla('#country3Txt').val(dsd[4]).change();
			  $joomla('#city3Txtdiv').val(dsd[6]);
			  $joomla('#state3Txtdiv').val(dsd[5]);
			  $joomla('#state3Txt').change();
			  //$joomla('#city3Txt').change();
			  $joomla("input[name='zipTxt']").val(dsd[7]);
			  $joomla("input[name='emailTxt']").val(dsd[8]);
			  $joomla("textarea[name='addressTxt']").val(dsd[9]);
			  $joomla("textarea[name='address2Txt']").val(dsd[10]);
			  $joomla("#userprofileFormFive select[name='idtypeTxt']").val(dsd[11]).change();
			  $joomla("#userprofileFormFive input[name='idvalueTxt']").val(dsd[12]);
			  $joomla("input[name='fidTxt']").val(fid);
	  		 
			}
		}); 
        
	});
// 	$joomla('#addusers').on('click',function(){
// 	    $joomla('#userprofileFormThree')[0].reset();
// 	    $joomla.ajax({
// 	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getadduserconsigflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
// 			data: { "useridtypes":1},
// 			dataType:"html",
// 			type: "get",
// 			beforeSend: function(){
// 			    $joomla(".page_loader").show();
// 			},
// 			success: function(data){
// 			  $joomla(".page_loader").hide();
// 			  var dsed=data;
// 			  dsed=dsed.split(":");  
// 			  console.log("consig data:"+data);
// 			  $joomla("input[name='typeuserTxt']").val(dsed[0]);
// 			  $joomla("input[name='idTxt']").val(dsed[1]);
// 			}
// 		}); 
// 	});

$joomla('#typeuserTxt').on('change',function(){
    
    user = $joomla(this).val();
    
    if(user !=''){
        
        if(user == "Consignee"){
            userType = "ConsigneeUser";
        }
        if(user == "Shipper"){
            userType = "ShipperUser";
        }
        if(user == "Delivery"){
            userType = "DeliveryUser";
        }
        
        $joomla.ajax({
	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getaddusertypeflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "user":user,"usertype":userType},
			dataType:"html",
			type: "get",
			beforeSend: function(){
			    $joomla(".page_loader").show();
			},
			success: function(data){
			    console.log(data);
			  $joomla(".page_loader").hide();
			  var dsed=data;
			  dsed=dsed.split(":");  
			  $joomla("input[name='idTxt']").val(dsed[1]);
			}
		}); 
    }
    
    
});



	$joomla('#formone .btn-danger').on('click',function(e){
	   
        e.preventDefault();
        var res=$joomla(this).data('id');
        var reshtml=$joomla(this);
        var cf=confirm("<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_CONFIRM_DELETE') ?>");
        if(cf==true){
            $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&deleteadduserid="+res +"&deleteadduserflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "orderdeletetype": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
                  $joomla(".page_loader").show();
               },success: function(data){
                    if(data==1){
                       reshtml.closest('tr').hide();
                        $joomla(".page_loader").hide();
                    }
                }
    		});
        }	
        return false;
	});
    $joomla('#photoFile,#formFile,#utilityFile,#otherFile').bind('change', function() {
        
        var fileSize = this.files[0].size/1024/1024;
        if(fileSize>1)
        {
         var n = fileSize.toFixed(2);
         alert('Your file size is: ' + n + "MB, and it is too large to upload! Please try to upload smaller file (1MB or less).");
         $joomla(this).val('');
         return false;
        }
    });  	


    $joomla("input[name='faxTxt']").keypress(function (e) {
      if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
      }
      if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    });
    $joomla("input[name='anumberTxt']").keypress(function (e) {
      if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
      }
      if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    });
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


    $joomla("form[name='userprofileFormFive']").validate({
			
			// Specify validation rules
			rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
    			fnameTxt:{
    				required: true,
    			    alphanumeric:true 
    			},
    			lnameTxt:{
    				required: true,
    			    alphanumeric:true 
    			},
                country3Txt: {
                 required: true,
                 selectBox: true
                },
                state3Txt: {
                 required: true,
                 selectBox: true
                },
                addressTxt: "required",
                zipTxt: {
                  number: true
                }, 
                idtypeTxt:{
                    required: true
                },
                idvalueTxt:{
                    required: true
                },
                emailTxt:{
                 required: true
                    
                }
            },
			// Specify validation error messages
			messages: {
    			fnameTxt:{
    			    required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_FIRST_NAME_ERROR');?>",
    			    alphanumeric: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_ALPHABET_ERROR');?>"
    			},
    			lnameTxt:{
    			    required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_LAST_NAME_ERROR');?>",
    			    alphanumeric: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_ALPHABET_ERROR');?>"
    			},
                country3Txt: {
                 required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_COUNTRY_ERROR');?>",
                 selectBox: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_COUNTRY_ERROR');?>"
                },
                state3Txt: {
                 required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_STATE_ERROR');?>",
                 selectBox: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_STATE_ERROR');?>"
                },
                addressTxt:{
                  required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_ADDRESS_ERROR');?>"
                },
                idtypeTxt: {
                 required: "Please select Identity Type"
                },
                idvalueTxt: {
                 required: "Please enter Identity Value"
                },
                emailTxt:{
                  required: "<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_EMAIL_ERROR');?>"
                    
                }
                
			},
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			submitHandler: function(form) {
			   $joomla('.page_loader').show();
			  form.submit();
			}
	});

	$joomla('#profilepicTxt').bind('change', function() {
	   
	    if($joomla(this).val().length > 0){
	    
        //this.files[0].size gets the size of your file.
        $joomla("input[name=profilepicTxt] #errorTxt-error").html('');
        var ext = $joomla('input[name=profilepicTxt]').val().split('.').pop().toLowerCase();
            if($joomla.inArray(ext, ['gif','png','jpg','jpeg','GIF','PNG','JPG','JPEG']) == -1) {
            $joomla('#errorTxt-error').html('<?php echo Jtext::_('COM_USERPROFILE_INVALID_EXT_ERROR');?>!');
            $joomla('#errorTxt-error').show();
            $joomla('#profilepicTxt').val('');
            $joomla('#userprofileFormOne .btn-primary:first').attr("disabled","disabled");
            return false;
        }else{
          $joomla("input[name=profilepicTxt] #errorTxt-error").html('');
        //   $joomla('#userprofileFormOne .btn-primary:first').attr("disabled",false);
        }
        if(this.files[0].size>1000000){
            $joomla('#errorTxt-error').html('Please upload below 1MB file size');
            $joomla('#errorTxt-error').show();
            //$joomla('input[name=profilepicTxt]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please upload below 1MB file size</label>');
            $joomla('#fileTxt').val('');
            $joomla('#userprofileFormOne .btn-primary:first').attr("disabled","disabled");
            return false;
        }else{
            $joomla("input[name=profilepicTxt] #errorTxt-error").html('');
            $joomla('#errorTxt-error').hide();
            $joomla('#userprofileFormOne .btn-primary:first').attr("disabled",false);
        }
	  }else{
	       alert($joomla(this).val().length);
	       //$joomla("input[name=profilepicTxt] #errorTxt-error").html('');
	  }
            
    });


});

</script>

<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_PI_MAIN_HEADING');?></div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12 tab_view">
          <ul class="nav nav-tabs">
            <li> <a class="active docpage" href="#"><?php echo Jtext::_('COM_USERPROFILE_PI_TITLE1');?></a> </li>
            <li> <a class="docpage" href="#"><?php echo Jtext::_('COM_USERPROFILE_PI_TITLE2');?></a> </li>
          </ul>
        </div>
      </div>
      
      <div id="formone" >
        <form name="userprofileFormOne" id="userprofileFormOne" method="post" action=""  enctype="multipart/form-data">
        <div class="row">
          <div class="col-sm-12 text-right">
			<input value="<?php echo Jtext::_('COM_USERPROFILE_PI_UPDATE_PROFILE');?>" style="" class="btn btn-primary" type="submit" >
		  </div>
        </div>  
        <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <h4 class="sub_title"><b><?php echo Jtext::_('COM_USERPROFILE_PI_PROFILE_PICTURE');?></b></h4>
              </div>
              <div class="row">
             <div class="col-sm-12 col-md-6">
              <div class="form-group">     
              <div class="prof-pic-sec">   
              
             
              
                <?php if($UserView->imagePath){?><img src="<?php echo str_replace('https:','http:',$UserView->imagePath);?>" class="img-prof"><?php }else{ echo '<img src="'.JURI::base().'/images/default_profile_pic.png" class="img-prof" />'; } ?>
                <div class="input-group finputfile">
                     <input type="hidden" class="form-control"  name="fileTxt" id="fileTxt" value="<?php  echo str_replace('https:','http:',$UserView->imagePath); ?>"> 
                    <span class="input-group-btn"> <span class="btn btn-file"> <?php echo Jtext::_('COM_USERPROFILE_PI_CHOOSE_FILE');?>                   
                    <input type="file" class="form-control"  name="profilepicTxt" id="profilepicTxt" >
                    </span> </span>
                </div>
                <span style="display:none;" id="errorTxt-error" class="error" for="errorTxt"></span>   
                </div>              
              
              </div>
              </div>
              </div>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                <h4 class="sub_title"><b><?php echo Jtext::_('COM_USERPROFILE_PI_NAME');?></b></h4>
                </div>
                </div>
              
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                <?php if($menuCustType == "CUST"){  ?>
                <label><?php echo $assArr['first_name'];?> <span class="error">*</span></label>
                <?php }else if($menuCustType == "COMP"){ ?>
                <label><?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_BUSINESS_NAME');?> <span class="error">*</span></label>
                <?php } ?>
                
                <input type="text" class="form-control"  name="firstName" id="firstName" value="<?php echo $UserView->AdditionalFirstName;?>" maxlength="25">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                <?php if($menuCustType == "CUST"){  ?>
                <label><?php echo $assArr['last_Name'];?> <span class="error">*</span></label>
                <?php }else if($menuCustType == "COMP"){ ?>
                <label><?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_CONTACT_PERSON');?> <span class="error">*</span></label>
                <?php } ?>
                <input type="text" class="form-control"  name="lastName" id="lastName" value="<?php echo $UserView->AdditionalLname;?>" maxlength="25">
              </div>
            </div>
            
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <h4 class="sub_title"><b><?php echo Jtext::_('COM_USERPROFILE_PI_TELEPHONE');?></b></h4>
              </div>
              <div class="form-group">
                <label><?php echo $assArr['primary_number'];?> <span class="error">*</span></label>
                <div class="row">
                  <div class="col-sm-6">
                    <?php 
					       $dialcodeView= UserprofileHelpersUserprofile::getDialCodeList($user);
					       $arrdialcode = json_decode($dialcodeView); 
                           $dialcode='';
                           
                                $countryView= UserprofileHelpersUserprofile::getCountriesList($user);
		                        $arr = json_decode($countryView);
		                        
		                      //  echo '<pre>';
		                      //  var_dump($arr->Data);
		                       
		                        foreach($arr->Data as $data){
                		           if($UserView->Country==$data->CountryCode){
                		               $dialCodeName = $data->CountryDailCodes;
                		           }
		                        }
		                        
		                       
					       foreach($arrdialcode->Data as $rg){
					           
					           if($UserView->DailCode==$rg->DailCode){
					               
					               $dialcodesel="selected";
					              
					           }
					           else
					           {
					               $dialcodesel="";
					           }
					           if($rg->Description)
                              $dialcode.= '<option value="'.$rg->DailCode.'" '.$dialcodesel. '>'.$rg->Description.'</option>';
                            
                           }
             
    					?>
                    <select class="form-control" name="dialcodeTxt" id="dialcodeTxt">
                      <option value="">Please Select</option>
                      <?php echo $dialcode;?>
                    </select>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control"  name="phoneTxt" id="phoneTxt" maxlength="10" value="<?php echo $UserView->PrimaryNumber;?>">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label><?php echo $assArr['alternative_number'];?></label>
                <div class="row">
                  <div class="col-sm-6">
                    <?php 
					       $dialcodeViews= UserprofileHelpersUserprofile::getDialCodeList($user);
					       $arrdialcodes = json_decode($dialcodeViews); 
                           $dialcodes='';
					       foreach($arrdialcodes->Data as $rg){
					           if($UserView->DialCodeOther==$rg->DailCode){
					               $dialcodesels="selected";
					           }
					           else
					           {
					               $dialcodesels="";
					           }
					           if($rg->Description)
                              $dialcodes.= '<option value="'.$rg->DailCode.'" '.$dialcodesels. '>'.$rg->Description.'</option>';
                           }
             
    					?>
                    <select class="form-control" name="dialcodealtTxt" id="dialcodealtTxt">
                      <option value=""><?php echo Jtext::_('COM_USERPROFILE_PI_PLEASE_SELECT');?></option>
                      <?php echo $dialcodes;?>
                    </select>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control"  name="anumberTxt" id="anumberTxt" maxlength="10" value="<?php echo $UserView->AlternativeNumber;?>">
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label><?php $assArr['fax']; ?></label>
                <input type="text" class="form-control"   name="faxTxt" id="faxTxt" value="<?php echo $UserView->Fax;?>" minlength="10" maxlength="20">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <h4 class="sub_title"><b><?php echo Jtext::_('COM_USERPROFILE_PI_EMAIL');?></b></h4>
              </div>
              <div class="form-group">
                <label><?php echo $assArr['primary_email'];?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="emailTxt" id="emailTxt" value="<?php echo $UserView->PrimaryEmail;?>" readonly>
              </div>
              <div class="form-group">
                <label><?php echo $assArr['alternative_email'];?></label>
                <input type="text" class="form-control"  name="aemailTxt" id="aemailTxt" maxlength="50" value="<?php echo $UserView->AlternativeEmail;?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <h4 class="sub_title"><b><?php echo Jtext::_('COM_USERPROFILE_PI_SHIPPING_ADDRESS');?></b></h4>
              </div>
              <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['address_1'];?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="addressTxt" id="addressTxt" value="<?php echo $UserView->AddressAccounts;?>" maxlength="50">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['address_2'];?> </label>
                <input type="text" class="form-control"  name="address2Txt" id="address2Txt" value="<?php echo $UserView->addr_2_name;?>" maxlength="50">
              </div>
            </div>
            </div>
            </div>
            <?php
		       $countryView= UserprofileHelpersUserprofile::getCountriesList($user);
		       $arr = json_decode($countryView);
		       
		       //var_dump($arr);exit;
		       
               $countries='';
		       foreach($arr->Data as $rg){


		           if($UserView->Country==$rg->CountryCode){
		               $sel="selected";
		           }
		           else
		           {
		               $sel="";
		           }
		           
		              if(strtolower($domainName) != 'kupiglobal'){ 
		                $countries .= '<option value="0">'.Jtext::_('COM_USERPROFILE_PI_SELECT_COUNTRY').'</option>';
                        $countries.= '<option value="'.$rg->CountryCode.'" '.$sel. '>'.$rg->CountryDesc.'</option>';
                  
                     }else{ 
                         
                         
                         if($rg->CountryCode == "BiH") { 
                    
                             $countries.= '<option  value="'.$rg->CountryCode.'" selected>'.$rg->CountryDesc.'</option>';
                        
                            }
                    
                     } 
                  
                  
               }
 
			?>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['country'];?>  <span class="error">*</span></label>
                <select class="form-control" id="countryTxt" name="countryTxt">
                    
                  <?php echo $countries;?>
                  
                
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['state'];?> <span class="error">*</span></label>
                <select class="form-control"  id="stateTxt" name="stateTxt">
                    
            <?php  if(strtolower($domainName) != 'kupiglobal'){  ?>
                    
                  <option value="0"><?php echo Jtext::_('COM_USERPROFILE_PI_SELECT_STATE');?> </option>
                  <?php
                  
		           echo UserprofileHelpersUserprofile::getStatesList($UserView->Country,$UserView->State);
		          
		         }else{
		              echo '<option value="BandH" selected>Bosnia and Herzegovina</option>';
		          }
		          
		      ?>
		          
                </select>
              </div>
            </div>
            
            <?php //var_dump($UserView->State);exit; ?>
            
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['city'];?> </label>
                
                <!--<input type="text" class="form-control"  name="cityTxt" list="cityTxt" value="<?php echo $UserView->desc_city;?>" />-->
                <!--    	<datalist id="cityTxt"></datalist><input type="hidden" name="cityTxtdiv" list="cityTxtdiv">-->
                
                 <select type="text" class="form-control"  name="cityTxt" list="cityTxt" />
                    <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT_CITY_LABEL');?></option>
                 <?php
		           echo UserprofileHelpersUserprofile::getCitiesList($UserView->Country,$UserView->State,$UserView->City);
		          ?>
                 </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['zip_code'];?></label>
                <input type="text" class="form-control" name="zipTxt" id="zipTxt" maxlength="10"  value="<?php echo $UserView->PostalCode;?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <h4 class="sub_title"><b><?php echo Jtext::_('COM_USERPROFILE_PI_ADDITIONAL_ADDRESS');?></b> </h4>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <input type="button" id="addusers" class="btn btn-primary" value="<?php echo Jtext::_('COM_USERPROFILE_PI_ADDITIONAL_ADDRESS');?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#exampleModal">
              </div>
            </div>

          <input type="hidden" name="task" value="user.userupdateprofile">
          <input type="hidden" name="id" value="0" />
          <input type="hidden" name="user" value="<?php echo $user;?>" />
        
        <?php  
            
            Controlbox::getAdditionalUsersDetailsCsv($user);
            
        ?>
        
         <div class="">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                        
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <a style="color:white;" href="<?php echo JURI::base(); ?>/csvdata/address_list.csv" class="btn btn-primary csvDownload export-csv">Export CSV</a>
                    </div>
                </div>
        </div>
        
        <div class="">
        	<div class="col-md-12">
        	    
        		<table class="table table-bordered theme_table" id="a_table">
        			<thead>
						<tr>
							<th><?php echo $assArr['sNo']; ?></th>
							<th><?php echo $assArr['first_name']; ?></th>
							<th><?php echo $assArr['primary_email']; ?></th>
							<th><?php echo Jtext::_('COM_USERPROFILE_PI_TABLE_USER_TYPE');?></th>
							<th><?php echo $assArr['country'];?></th>
							<th><?php echo $assArr['state'];?></th>
							<th><?php echo $assArr['city'];?></th>
							<th><?php echo $assArr['zip_code'];?></th>
							<th><?php echo $assArr['address_1'];?></th>
							<th><?php echo $assArr['address_2'];?></th>
							<th><?php echo Jtext::_('Identification Type');?></th>
							<th><?php echo Jtext::_('Identification Value');?></th>
							<th><?php echo Jtext::_('COM_USERPROFILE_PI_TABLE_ACTIONS');?></th>
							
						</tr>
        			</thead>
        			<tbody><?php echo UserprofileHelpersUserprofile::getAdditionalUsersDetails($user);?>	
					</tbody>
        		</table>
        	</div>
	     </div>
	        
	        
      </div>
       </form>         
      </div>

      <div id="formtwo" style="display:none" >
        <!--<div class="main_heading">Manage My Account</div>-->
        <form name="userprofileFormFour" id="userprofileFormFour" method="post" action="" enctype="multipart/form-data">
          <div class="row">
            <!-- <div class="col-sm-12">
            <h3 class="mx-1"><strong>Upload Documents</strong></h3>
          </div>-->
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="form-group">
                <h4 class="sub_title"><b><?php echo Jtext::_('COM_USERPROFILE_MYDOC_TITLE'); ?></b></h4>
              </div>
              <div class="form-group"> <a href="#" class="btn btn-primary"><?php echo Jtext::_('COM_USERPROFILE_MYDOC_DOWNLOAD');?></a> <a href="#" class="btn btn-danger"><?php echo Jtext::_('COM_USERPROFILE_MYDOC_DELETE');?></a> </div>
            </div>
          </div>
        <div id="DoumentDiv"></div>  
        <?php 
        if($UserDocumentView->identity_form_doc){ $fileExt=$UserDocumentView->identity_form_Name.'.'.end((explode('/', $UserDocumentView->identity_form_content_type)));?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label><?php echo $assArr['photographic_identification']; ?></label>
              </div>
              <div class="col-sm-10">
                <label class="radio-inline">
                <input type="radio" name="downfile" value="<?php echo '1:'.$UserDocumentView->identity_form_doc;?>" >
                <?php echo end((explode('/', $UserDocumentView->identity_form_Name)));?></label>
                <input type="hidden" name="downfilenameone" value="<?php echo $fileExt;?>" >
              </div>
            </div>
          </div>
        <?php } ?>
        <?php if($UserDocumentView->form_doc){$fileExt=$UserDocumentView->form_name.'.'.end((explode('/', $UserDocumentView->form_content_type)));?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label><?php echo $assArr['form_1583']; ?></label>
              </div>
              <div class="col-sm-10">
                <label class="radio-inline">
                <input type="radio" name="downfile" value="<?php echo '2:'.$UserDocumentView->form_doc;?>">
                <?php echo end((explode('/', $UserDocumentView->form_name)));?> </label>
                <input type="hidden" name="downfilenametwo" value="<?php echo $fileExt;?>" >
              </div>
            </div>
          </div>
        <?php } ?>
        <?php if($UserDocumentView->utility_doc){$fileExt=$UserDocumentView->utility_name.'.'.end((explode('/', $UserDocumentView->utility_content_type)));?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label><?php echo $assArr['utility_bills']; ?></label>
              </div>
              <div class="col-sm-10">
                <label class="radio-inline">
                <input type="radio" name="downfile" value="<?php echo '3:'.$UserDocumentView->utility_doc;?>">
                <?php echo end((explode('/', $UserDocumentView->utility_name)));?> </label>
                <input type="hidden" name="downfilenamethree" value="<?php echo $fileExt;?>" >
              </div>
            </div>
          </div>
        <?php } ?>
        <?php if($UserDocumentView->other_doc){$fileExt=$UserDocumentView->other_name.'.'.end((explode('/', $UserDocumentView->other_content_type)));?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label><?php echo $assArr['others']; ?></label>
              </div>
              <div class="col-sm-10">
                <label class="radio-inline">
                <input type="radio" name="downfile" value="<?php echo '4:'.$UserDocumentView->other_doc;?>">
                <?php echo end((explode('/', $UserDocumentView->other_name)));?> </label>
                <input type="hidden" name="downfilenamefour" value="<?php echo $fileExt;?>" >
              </div>
            </div>
          </div>
        <?php } ?>
         <input type="hidden" name="task" value="user.mydocumentsdownload">
        </form>
        <form name="userprofileFormTwo" id="userprofileFormTwo" method="post" action="" enctype="multipart/form-data"> 
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="form-group">
                <h4 class="sub_title"><b><?php echo Jtext::_('COM_USERPROFILE_MYDOC_UPLOAD_DOC');?></b></h4>
                <h5><strong><?php echo Jtext::_('COM_USERPROFILE_MYDOC_SELECT_DOC'); ?>: (<?php echo Jtext::_('COM_USERPROFILE_MYDOC_PHOTO_ID'); ?>, <?php echo Jtext::_('COM_USERPROFILE_MYDOC_FORM'); ?> 1583, <?php echo Jtext::_('COM_USERPROFILE_MYDOC_UTILITY_BILLS'); ?> , <?php echo Jtext::_('COM_USERPROFILE_MYDOC_OTHERS'); ?> )</strong></h5>
                <p><?php echo Jtext::_('COM_USERPROFILE_MYDOC_FILE_SIZE_LIMT_STR'); ?></p>
                <p>JPG,PNG,GIF,PDF <?php echo Jtext::_('COM_USERPROFILE_MYDOC_ACCEPTED_STR_STR'); ?></P>
              </div>
            </div>
          </div>
          <?php //if($UserDocumentView->identity_GUID==""){?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label><?php echo $assArr['photographic_identification'];?></label>
              </div>
              <div class="col-sm-10">
                <input type="file" name="photoFile" id="photoFile">
              </div>
            </div>
          </div>
        <?php //} ?>
        <?php //if($UserDocumentView->form_GUID==""){?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label><?php echo $assArr['form_1583'];?> 1583</label>
              </div>
              <div class="col-sm-10">
                <input type="file" name="formFile" id="formFile">
              </div>
            </div>
          </div>
        <?php //} ?>
        <?php //if($UserDocumentView->utility_GUID==""){?>
          
          <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label><?php echo $assArr['utility_bills'];?></label>
              </div>
              <div class="col-sm-10">
                <input type="file" name="utilityFile" id="utilityFile">
              </div>
            </div>
          </div>
        <?php //} ?>
        <?php //if($UserDocumentView->other_GUID==""){?>
          
          <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label><?php echo $assArr['others'];?></label>
              </div>
              <div class="col-sm-10">
                <input type="file" name="otherFile" id="otherFile">
              </div>
            </div>
          </div>
        <?php //} ?>
        
          <div class="row">
            <div class="col-sm-12">
              <input type="button" class="btn btn-primary" value="<?php echo Jtext::_('COM_USERPROFILE_MYDOC_UPLOAD');?>">
            </div>
          </div>
           <!--<input type="hidden" name="taskmethod" value="<?php echo $UserDocumentView->sendCommandType;?>" />-->
           <input type="hidden" name="task" value="user.mydocuments">
          <input type="hidden" name="id" value="0" />
          <input type="hidden" name="user" value="<?php echo $user;?>" />
        </form>
      </div>

    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">       
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">       
        <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_TITLE');?></strong></h4>
      </div>
      <form name="userprofileFormThree" id="userprofileFormThree" method="post" action=""  enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_USER_TYPE');?> <span class="error">*</span></label>
                <select type="text" class="form-control" name="typeuserTxt" id="typeuserTxt" readonly> 
                    <option value="">Select User Type</option>
                    <option value="Consignee">Consignee</option>
                    <option value="Shipper">Shipper</option>
                    <option value="Delivery">Third Party</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_ID');?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="idTxt" id="idTxt" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['first_name'];?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="25">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['last_Name']; ?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="lnameTxt" id="lnameTxt" maxlength="25">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['address_1'];?> <span class="error">*</span></label>
                <textarea type="text" class="form-control" name="addressTxt" id="addressTxt" maxlength="35"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['address_2']; ?> </label>
                <textarea type="text" class="form-control" name="address2Txt" id="address2Txt" maxlength="35"></textarea>
              </div>
            </div>
          </div>
        
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['country'];?> <span class="error">*</span></label>
                <?php
					       $countryView= UserprofileHelpersUserprofile::getCountriesList($user);
					       $arr = json_decode($countryView); 
                           $countries='';
					       foreach($arr->Data as $rg){
					          $countries.= '<option value="'.$rg->CountryCode.'">'.$rg->CountryDesc.'</option>';
                           }
             
    					?>
                <select class="form-control" name="country2Txt" id="country2Txt">
                  <option value=""><?php echo Jtext::_('COM_USERPROFILE_PI_SELECT_COUNTRY');?> </option>
                  <?php echo $countries;?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['state'];?> <span class="error">*</span></label>
                <select class="form-control"  name="state2Txt" id="state2Txt">
                  <option value="0"><?php echo Jtext::_('COM_USERPROFILE_PI_SELECT_STATE');?></option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['city'];?></label>
                     <!--   <input type="text" class="form-control"  name="city2Txt" list="city2Txt" />-->
                    	<!--<datalist id="city2Txt"></datalist>-->
                    	<!--<input type="hidden" name="city2Txtdiv" id="city2Txtdiv">-->
                    	
                    	<select type="text" class="form-control"  name="city2Txt" id="city2Txt" autocomplete="off">
                            <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT_CITY_LABEL');?></option>
                        </select>
                    	
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['zip_code'];?> <span class="error">*</span> </label>
                <input type="text" class="form-control" name="zipTxt" id="zipTxt" maxlength="10">
              </div>
            </div>
          </div>
          
          <div class="row">
              
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo Jtext::_('COM_REGISTER_IDENTIFICATION_LABEL');?><span class="error">*</span></label>
                        <?php
                                    $idtypeView= Controlbox::getidentityList();
                                    $arr = json_decode($idtypeView); 
                                    $idtype='';
                                    foreach($arr as $rg){
                                     $idtype.= '<option value="'.$rg->id_values.'">'.$rg->id_values.'</option>';
                                    } 
                                    ?>
                                 <select class="form-control" id="idtypeTxt" name="idtypeTxt">
                                    <option value=""><?php echo Jtext::_('COM_REGISTER_SELECT_IDENTITY_LABEL');?></option>
                                    <?php echo $idtype;?>
                                 </select>
                    </div>
                </div>    
                <div class="col-md-6">
                    <div class="form-group">
                                 <label><?php echo Jtext::_('COM_REGISTER_IDENTIFICATION_VALUE_LABEL');?><span class="error">*</span></label>
                                 <input type="text" class="form-control" name="idvalueTxt" id="idvalueTxt">
                              </div>
                </div>
                        
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_EMAIL');?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="emailTxt" id="emailTxt">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" value="<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_SAVE_BTN');?>" class="btn btn-primary">
              <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_PI_MODAL_CANCEL_BTN');?>" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>
        <input type="hidden" name="task" value="user.addusers">
        <input type="hidden" name="id" value="0" />
        <input type="hidden" name="user" value="<?php echo $user;?>" />
      </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ord_edit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">       
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">        
        <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_PI_ADDITIONAL_ADDRESS_TITLE'); ?></strong></h4>
      </div>
      <form name="userprofileFormFive" id="userprofileFormFive" method="post" action=""  enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_USER_TYPE'); ?>  <span class="error">*</span></label>
                <input type="text" class="form-control" name="typeuserTxt" id="typeuserTxt" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_ID'); ?> :  <span class="error">*</span></label>
                <input type="text" class="form-control"  name="idTxt" id="idTxt" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_FIRST_NAME'); ?>  <span class="error">*</span></label>
                <input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="25">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_LAST_NAME'); ?> :  <span class="error">*</span></label>
                <input type="text" class="form-control"  name="lnameTxt" id="lnameTxt" maxlength="25">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_ADDITIONAL_ADDRESS'); ?> <span class="error">*</span></label>
                <textarea type="text" class="form-control" name="addressTxt" id="addressTxt" maxlength="35"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_ADDITIONAL_ADDRESS'); ?> 2</label>
                <textarea type="text" class="form-control" name="address2Txt" id="address2Txt" maxlength="35"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_COUNTRY'); ?> <span class="error">*</span></label>
                <?php
			       $countryView= UserprofileHelpersUserprofile::getCountriesList($user);
			       $arr = json_decode($countryView); 
                   $countries='';
			       foreach($arr->Data as $rg){
			          $countries.= '<option value="'.$rg->CountryCode.'">'.$rg->CountryDesc.'</option>';
                   }
     
				?>
                <select class="form-control" name="country3Txt" id="country3Txt">
                  <option value="0">Select Country</option>
                  <?php echo $countries;?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_STATE'); ?> <span class="error">*</span></label>
                <select class="form-control"  name="state3Txt" id="state3Txt">
                  <option value="0">Select State</option>
                </select><input type="hidden" name="state3Txtdiv" id="state3Txtdiv">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_CITY'); ?> </label>
                    <!--<input type="text" class="form-control"  name="city3Txt" list="city3Txt" />-->
                    <!--<datalist id="city3Txt"></datalist>-->
                    <!--<input type="hidden" name="city3Txtdiv" id="city3Txtdiv">-->
                        <select  class="form-control"  name="city3Txt" id="city3Txt" autocomplete="off">
                            <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT_CITY_LABEL');?></option>
                        </select>
                        <input type="hidden" name="city3Txtdiv" id="city3Txtdiv">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_ZIPCODE'); ?> </label>
                <input type="text" class="form-control" name="zipTxt" id="zipTxt">
              </div>
            </div>
          </div>
          
           <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo Jtext::_('COM_REGISTER_IDENTIFICATION_LABEL');?><span class="error">*</span></label>
                        <?php
                                    $idtypeView= Controlbox::getidentityList();
                                    $arr = json_decode($idtypeView); 
                                    $idtypeformfive='';
                                    foreach($arr as $rg){
                                     $idtypeformfive.= '<option value="'.$rg->id_values.'">'.$rg->id_values.'</option>';
                                    } 
                                    ?>
                                 <select class="form-control" id="idtypeTxt" name="idtypeTxt">
                                    <option value=""><?php echo Jtext::_('COM_REGISTER_SELECT_IDENTITY_LABEL');?></option>
                                    <?php echo $idtypeformfive;?>
                                 </select>
                    </div>
                </div>    
                <div class="col-md-6">
                    <div class="form-group">
                                 <label><?php echo Jtext::_('COM_REGISTER_IDENTIFICATION_VALUE_LABEL');?><span class="error">*</span></label>
                                 <input type="text" class="form-control" name="idvalueTxt" id="idvalueTxt">
                              </div>
                </div>        
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_EMAIL'); ?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="emailTxt" id="emailTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_SAVE_BTN'); ?>" class="btn btn-primary">
              <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_CANCEL_BTN'); ?>" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>

        <input type="hidden" name="task" value="user.editaddusers">
        <input type="hidden" name="fidTxt" value="0" />
        <input type="hidden" name="id" value="0" />
        <input type="hidden" name="user" value="<?php echo $user;?>" />
      </form>
    </div> 
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="ord_delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">       
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">       
        <h4 class="modal-title"><strong>Additional User</strong></h4>
        <h4 class="modal-title"><strong>Additional User Dleted Success fully</strong></h4>        
      </div>
      
    </div>
  </div>
</div>
