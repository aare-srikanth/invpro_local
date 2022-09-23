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
   $res=JRequest::getVar('res');
   
   require_once JPATH_ROOT.'/components/com_register/helpers/register.php';
   require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
   
    $hostnameStr = $_SERVER['HTTP_HOST'];
    $hostnameStr = str_replace("www.","",$hostnameStr);
    $hostnameArr = explode(".",$hostnameStr);
    $domainurl = $hostnameArr[1].".".$hostnameArr[2];
   
   $clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
   $clientConf = json_decode($clientConfigObj, true);
   $clients = $clientConf['ClientList'];
   
   
   $domainDetails = ModProjectrequestformHelper::getDomainDetails();
   $CompanyId = $domainDetails[0]->CompanyId;
   $companyName = $domainDetails[0]->CompanyName;
   $domainEmail = $domainDetails[0]->PrimaryEmail;
   $domainName =  $domainDetails[0]->Domain;
   
   $AgencyId=JRequest::getVar('agn');
  
   $accTypeDet = RegisterHelpersRegister::getaccounttype();
   $accountTypeDef = array();
   foreach($accTypeDet as $accType){
       $accountTypeDef[$accType->id_values] = $accType->is_default;
   }
   
   
 //var_dump($accountTypeDef);exit;
   
   // dynamic elements
   
   $res = Controlbox::dynamicElements('Registration');
   $elem=array();
   foreach($res as $element){
      $elem[trim($element->ElementId)]=array($element->ElementDescription,$element->ElementStatus,$element->is_mandatory,$element->is_default,$element->ElementValue);
   }
   
// echo '<pre>';   
// var_dump($elem);exit;
//end


   
   
   // if($companyId != null){
       
   //     $CompanyDetails = Controlbox::getCompanyDetails($CompanyId);
   //     $domainName=$CompanyDetails->CompanyName;
   //     $domainEmail=$CompanyDetails->PrimaryEmail;
       
   // }else{
       
   //         $serverName = explode(".",$_SERVER['SERVER_NAME']);
   //         $domainName = explode("-",$serverName[0]);
   //         $domainName = $domainName[0];
   //         $domainEmail= "support@".$domainName.".com";
   //         $companyName = $domainName;
           
   // }
   
   
   
   $canEdit = JFactory::getUser()->authorise('core.edit', 'com_register');
   
   if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_register'))
   {
   	$canEdit = JFactory::getUser()->id == $this->item->created_by;
   }
   $regView = $this->Response;
   
   
   //get labels
   
   if(strpos($_SERVER['REQUEST_URI'], '/index.php/') !== false){
    $strplace = strpos($_SERVER['REQUEST_URI'], '/index.php/');
    $langplace = $strplace + 11;
    $language = substr($_SERVER['REQUEST_URI'],$langplace,2);
}
    $res=Controlbox::getlabels($language);
    $assArr = [];
    
    foreach($res->data as $response){
    $assArr[$response->id]  = $response->text;
    
     }
   
      
   	if(($regView !="" && $regView->Response !="0") || $session->get('userData')!=''){
   	    
           if($regView !=""){
               $session->set('userData', $regView);
           }else{
               $regView = $session->get('userData');
           }
           
    
               
                  
   ?>
<style>ul.nav.menu.nav.navbar-nav.mod-list { display: none; }</style>
<script type="text/javascript">
   var $joomla = jQuery.noConflict(); 
   $joomla(document).ready(function() {
       history.pushState(null, null, location.href);
       window.onpopstate = function () {
           history.go(1);
       };
       
    
       
   });
</script>
<script>
   $(function () {
     $('[data-toggle="tooltip"]').tooltip()
   })
</script>
<?php
   $successMsg = str_replace('##whatsapp##', $CompanyDetails->PrimaryPhone, Jtext::_('COM_REGISTER_SUCCESS_MESSAGE'));
   $successMsg = str_replace('##telephone##', $CompanyDetails->PrimaryPhone, $successMsg);
   ?>
<div class="item_fields">
   <div class="container">
      <div class="main_panel wlcme_usrs">
         <div class="panel-body">
            <div class="row">
               <div class="col-sm-6">
                  <h3>Dear <?php echo  $regView->Data->UserName;?>,</h3>
               </div>
               <div class="col-sm-6 text-right">
                  <a href="index.php?option=com_userprofile&view=user" class="btn btn-primary"><?php echo Jtext::_('COM_REGISTER_GO_TO_DASHBOARD');?></a>
               </div>
            </div>
            <p><?php echo str_replace('####',$domainEmail,str_replace('XXXX',strtoupper($companyName),$successMsg) ); ?></p>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h5><?php echo Jtext::_('COM_REGISTER_SUCCESS_HEADING');?></h5>
               </div>
               <div class="panel-body">
                  <ul class="srvc_blck">
                     <li><?php echo Jtext::_('COM_REGISTER_PERSONAL_ADDRESS_LABEL');?></li>
                     <li><?php echo Jtext::_('COM_REGISTER_CONSOLIDATION_SMART_KIT_LABEL');?></li>
                     <li><?php echo Jtext::_('COM_REGISTER_PERSONAL_MONITORING_AND_NOTIFICATIONS_LABEL');?></li>
                     <li><?php echo Jtext::_('COM_REGISTER_PERSONAL_SUPERIOR_CUSTOMER_SERVICE_LABEL');?></li>
                     <li><?php echo Jtext::_('COM_REGISTER_PERSONAL_PERSONALIZED_SHOPPING_ASSISTANCE_LABEL');?></li>
                  </ul>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h5><?php echo Jtext::_('COM_REGISTER_DETAILS_LABEL');?> </h5>
               </div>
               <div class="panel-body">
                  <ul>
                     <li><?php echo Jtext::_('COM_REGISTER_ACCOUNT_NUMBER_LABEL');?>: <span class="hlght_txt"><?php echo $regView->Data->CustId; ?></span></li>
                     <li><?php echo Jtext::_('COM_REGISTER_TO_ACCESS_YOUR_ACCOUNT_LABEL');?></li>
                     <li><?php echo Jtext::_('COM_REGISTER_ENTER_YOUR_ID_LABEL');?>: <span class="hlght_txt"><?php echo $regView->Data->CustId; ?></span></li>
                  </ul>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h3><?php echo Jtext::_('COM_REGISTER_TEXT_1_LABEL');?></h3>
               </div>
               <div class="panel-body">
                  <ul>
                     <li><?php echo  $regView->Data->UserName;?></li>
                     <li><?php echo  str_replace(",","",strtoupper($regView->Data->Address1.' '. $regView->Data->Address2));?></li>
                     <li> <?php echo  $regView->Data->CustId;?></li>
                     <li><?php echo  strtoupper($regView->Data->City);?> <?php echo  $regView->Data->State;?></li>
                     <li><?php echo  $regView->Data->Country;?> &nbsp;- &nbsp;<?php echo  $regView->Data->PostalCode;?></li>
                     <li><?php echo Jtext::_('COM_REGISTER_PHONE_NUMBER_LABEL');?> - &nbsp;<?php echo  $regView->Data->PhoneCell;?></li>
                     <!--<li>FAX - &nbsp;<?php echo  $regView->Data->PhoneFax;?></li>-->
                  </ul>
               </div>
            </div>
            <div class="ftr_blck">
               <p><?php echo Jtext::_('COM_REGISTER_TEXT_2_LABEL');?></p>
               <p><?php echo str_replace('XXXX',strtoupper($companyName),Jtext::_('COM_REGISTER_TEXT_3_LABEL'));?></p>
               <h5><?php echo str_replace('XXXX',strtoupper($companyName),Jtext::_('COM_REGISTER_WELCOME_LABEL'));?></h5>
               <h5><?php echo str_replace('XXXX',strtoupper($companyName),Jtext::_('COM_REGISTER_WELCOME2_LABEL'));?></h5>
            </div>
            <div class="text-center">
               <p><?php echo strtoupper($companyName); ?> | P. <?php echo $CompanyDetails->PrimaryPhone; ?> | <a href="mailto:<?php echo $domainEmail; ?>">E.<?php echo $domainEmail; ?></a></p>
               <p><?php echo Jtext::_('COM_REGISTER_LET_LABEL');?></p>
            </div>
         </div>
      </div>
   </div>
   <!-- Begin Content -->
   <jdoc:include type="modules" name="position-3" style="xhtml" />
   <jdoc:include type="message" />
   <jdoc:include type="component" />
   <div class="clearfix"></div>
   <jdoc:include type="modules" name="position-2" style="none" />
   <!-- End Content -->
   </main>
   <?php if ($position7ModuleCount) : ?>
   <div id="aside" class="span3">
      <!-- Begin Right Sidebar -->
      <jdoc:include type="modules" name="position-7" style="well" />
      <!-- End Right Sidebar -->
   </div>
   <?php endif; ?>
   </section>
   <!-- Page Content -->
   <!-- Footer -->
   <footer class="footer" role="contentinfo">
      <jdoc:include type="modules" name="footer" style="none" />
      <!-- <div class="container footer_bottom">
         <div class="row">
           <div class="col-sm-6"><a href="#" target="_blank" rel="noopener noreferrer">Terms &amp; Conditions</a> <span class="line">|</span> <a href="#" target="_blank" rel="noopener noreferrer">Privacy Policy</a></div>
           <div class="col-sm-6 text-right">
           </div>
         </div>
         </div> -->
      <!--<div class="footer_bottom">-->
      <!--  <div class="container">-->
      <!--  <div class="row">-->
      <!--   <div class="col-sm-6"><a href="#<?php //echo JRoute::_('https://www.myboxsvd.com/terms-and-conditions'); ?>" target="_blank" rel="noopener noreferrer">Terms &amp; Conditions</a> <span class="line">|</span> <a href="#" target="_blank" rel="noopener noreferrer">Privacy Policy</a></div> -->
      <!--    <div class="col-sm-6 text-right">-->
      <!--      <p>&copy; <?php //echo date('Y'); ?> <?php echo $sitename; ?></p>-->
      <!--    </div>-->
      <!--  </div>-->
      <!--  </div>-->
      <!--</div>-->
      <!-- <a class="" href="#top" id="back-top"> <?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?> </a> -->
   </footer>
   <!-- Footer -->
</div>
<?php
   }else{
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
   			ignore: ".ignore",
   			rules: {
                   // The key name on the left side is the name attribute
                   // of an input field. Validation rules are defined
                   // on the right side
                   accounttypeTxt:{
                   },
                   fnameTxt: {
                     alphanumeric:true
                   },     
                   lnameTxt: {
                     alphanumeric:true
                   },     
                   emailTxt: {
                       validateEmail : true,
                   },
                   dialcodeTxt: {
                       required: false,
                       
                   },
                   phoneTxt: {
                       minlength:7,
                       number: true
                   },
                   countryTxt: {
                       
                   },
                   stateTxt: {
                       
                   },
                   zipTxt: {
                     /*required: true,
                     minlength: 5,*/
                     number: true
                   },
                   passwordTxt: {
                       minlength: 8,
                       validatePassword: true
                   },
                   confirmpasswordTxt: {
                       minlength: 8,
                       equalTo: "#passwordTxt"
                   },
                   termsTxt: {
                   },
                   idtypeTxt:{
                       
                   },        
                   idvalueTxt:{
                       remote:{
                           url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&idvalueflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                           data: { idtypeTxt: function() { return $joomla('#idtypeTxt').val(); } },
                           type: "get"
                       }
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
   			// Specify validation error messages ""
   			messages: {
   			  accounttypeTxt : {required:"<?php echo Jtext::_('Please select account type'); ?>"},
   			  fnameTxt: {required:"<?php echo $assArr['first_Name_error'];?>",alphanumeric:"<?php echo Jtext::_('COM_REGISTER_ALPHABET_ERROR');?>"},
                 lnameTxt: {required:"<?php echo $assArr['last_Name_error'];?>",alphanumeric:"<?php echo Jtext::_('COM_REGISTER_ALPHABET_ERROR');?>"},
   			  addressTxt:{
   			      required: "<?php echo $assArr['address_1_error'];?>"
   			  }/*,
   			  zipTxt:{
   			      required: "Please enter postal code"
   			  }*/,
   			  phoneTxt:{
   			      required: "<?php echo $assArr['PHONE_NUMBER'];?>",
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
                     required:"<?php echo $assArr['Email_error'];?>",
                     email:"<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_VALID_EMAIL_ADDRESS');?>",
                     remote:"<?php //echo Jtext::_('COM_REGISTER_PLEASE_ENTER_ALREADY'); ?>"
                 }, 
                 dialcodeTxt:{
                     selectBox:"<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_DIAL_CODE');?>"
                 },
                 countryTxt: {
   				required:"<?php echo $assArr['country_error'];?>",  
   				selectBox: "<?php echo Jtext::_('COM_REGISTER_PLEASE_SELECT_COUMTRY');?>"
   			  },
                 stateTxt: {
                   required:"<?php echo $assArr['state_error'];?>",  
   				selectBox: "<?php echo Jtext::_('COM_REGISTER_PLEASE_SELECT_STATE');?>"
   			  }/*,
                 cityTxt: {
   				required: "Please enter city"
   			  }*/,
   			  termsTxt:{
   			     required: "<?php echo Jtext::_('COM_REGISTER_PLEASE_CHECKED');?>" 
   			  },
   			  idtypeTxt:{
   			     selectBox: "<?php echo $assArr['identification_type_error'];?>" 
   			  },
   			  idvalueTxt:{
   			      required: "<?php echo $assArr['identification_value_error'];?>",
   			      remote:"<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_IDEN_VALUE');?>"
   			  }
         
   			},
   			// Make sure the form is submitted to the destination defined
   			// in the "action" attribute of the form when valid
   			submitHandler: function(form) {
   			     $joomla(".page_loader").show();
   					// Returns successful data submission message when the entered information is stored in database.
   					/*$.post("/index.php/register", {
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
   					
                       // if($joomla("input[name=userphotoTxt]").val()!=""){
                       //     $joomla("input[name=userphotoTxt] #errorTxt-error").html('');
                       //     var ext = $joomla('input[name=userphotoTxt]').val().split('.').pop().toLowerCase();
                       //         if($joomla.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
                       //             $joomla('input[name=userphotoTxt]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please check File invalid extension!</label>');
                                 
                       //             return false;
                       //         }else{
                       //         $joomla("input[name=userphotoTxt] #errorTxt-error").html('');    
                       //         }
                       // }
                       
                       form.submit();
                
   			},
               errorPlacement: function(error, element) {
                  
                   if(element.attr("name")=="fnameTxt" && $joomla('#accounttypeTxt').val()=="CUST" ){
                      error.text('<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_FIRST_NAME');?>');
                      error.insertAfter(element);
                   }
                   else if(element.attr("name")=="fnameTxt" && $joomla('#accounttypeTxt').val()!="CUST"){
                      error.text('<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_BUSINESS_NAME');?>');
                      error.insertAfter(element);
                   }
                   else {
                       //error.insertAfter(#cc);
                       //error.appendTo(element.parent('div').after());            
                       error.insertAfter(element);
                   }
                   if(element.attr("name")=="lnameTxt" && $joomla('#accounttypeTxt').val()=="CUST"){
                      error.text('<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_LAST_NAME');?>');
                      error.insertAfter(element);
                   }
                   else if(element.attr("name")=="lnameTxt" && $joomla('#accounttypeTxt').val()!="CUST"){
                      error.text('<?php echo Jtext::_('COM_REGISTER_PLEASE_ENTER_CONTACT_NAME');?>');
                      error.insertAfter(element);
                   }
                   else {
                       //error.insertAfter(#cc);
                       //error.appendTo(element.parent('div').after());            
                       error.insertAfter(element);
                   }
               }
   			
   		});
   	});
   	
       //var countryID2=$joomla('#countryTxt').val('US:1');
    //   var countryID2=$joomla('#countryTxt').val('0');
   	// if(countryID2){
   	// 		$joomla.ajax({
   	// 			url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&countryid=US&stateflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
   	// 			data: { "country": $joomla("#countryTxt").val() },
   	// 			dataType:"html",
   	// 			type: "get",
   	// 			success: function(data){
   	// 				$joomla('#stateTxt').html(data);
   	// 				//$joomla('#cityTxt').html(''); 
   	// 			}
   	// 		});
   	// }
   	
   	$joomla('#accounttypeTxt').on('change',function(){
           $joomla('#fnameTxt').parent('div').html('<input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="25" <?php if($elem['FIRSTNAME'][2]){ ?> required <?php } ?> >');
   	    $joomla('#lnameTxt').parent('div').html('<input type="text" class="form-control" name="lnameTxt" id="lnameTxt" maxlength="25" <?php if($elem['LASTNAME'][2]){ ?> required <?php } ?> >');
   	    if($joomla(this).val()=="CUST"){
   	        $joomla('.fname').html('<label><?php echo Jtext::_('COM_REGISTER_FIRST_NAME_LABEL'); ?><?php if($elem['FIRSTNAME'][2]){ ?> <span class="error">*</span> <?php } ?></label>');
   	        $joomla('.lname').html('<label>LAST NAME </label><?php if($elem['FIRSTNAME'][2]){ ?><span class="error">*</span><?php } ?>');
   	        $joomla('.rdo_rd1').parent('div').show();
   	        $joomla('#idtypeTxt').parent().parent().find('div').show();
   	        $joomla('#idvalueTxt').parent().parent().find('div').show();
   	        $joomla('#idtypeTxt').attr("name","idtypeTxt");
   	        $joomla('#idvalueTxt').attr("name","idvalueTxt");
           }
           else {
   	        $joomla('.rdo_rd1').parent('div').hide();
   	        $joomla('#idtypeTxt').parent().parent().find('div').hide();
   	        $joomla('#idvalueTxt').parent().parent().find('div').hide();
   	        $joomla('#idtypeTxt').attr("name","");
   	        $joomla('#idvalueTxt').attr("name","");
   	        $joomla('.fname').html('<label><?php echo Jtext::_('COM_REGISTER_BUSINESS_NAME_LABEL'); ?><?php if($elem['FIRSTNAME'][2]){ ?><span class="error">*</span><?php } ?></label>');
   	        $joomla('.lname').html('<label>CONTACT NAME </label><?php if($elem['LASTNAME'][2]){ ?><span class="error">*</span><?php } ?>');
           }
   	});
   	
   	var accountTypeDef = "<?php echo $accountTypeDef['COMP']; ?>";
   	
   	if(accountTypeDef){
   	    $joomla('#accounttypeTxt').val("COMP");
   	    $joomla('#accounttypeTxt').trigger("change");
   	}
   	
   	var accountTypeDef = "<?php echo $accountTypeDef['CUST']; ?>";
   	
   	if(accountTypeDef){
   	    $joomla('#accounttypeTxt').val("CUST");
   	    $joomla('#accounttypeTxt').trigger("change");
   	}
   	
   	   var domainName = '<?php echo strtolower($domainName);  ?>';
       
       jQuery.getJSON('<?php echo JURI::base(); ?>/client_config.json', function(jd) {
              console.log(jd.ClientList);
              var iterator = jd.ClientList.values();
                for (let elements of iterator) {
                    if(elements.Domain.toLowerCase() == domainName ){
                        if(elements.Default_destcountry_calc.length > 0){
                            $joomla("select[name=dialcodeTxt]").val(elements.Default_destcountry_dialcode);
                        }
                    }
                    
                }
                
       });
   
   
   	$joomla('#countryTxt').on('change',function(){
   
        $joomla('input[name="cityTxt"]').val('');
   		$joomla('#stateTxt').val(0);
   		$joomla('#cityTxt').html('');
   		var countryID = $joomla(this).val();
   		countryID=countryID.split(":");
   		countryTxt=$joomla('#countryTxt option:selected').text().toLowerCase();
   		countryTxt1=countryTxt.charAt(0).toUpperCase() + countryTxt.slice(1);
   		
   		if(countryID[0]=="US"){
      	 $joomla('#dialcodeTxt').val("+"+countryID[1]);
            $joomla("#dialcodeTxt option:selected").text("USA(+1)");
   		}
   		else if(countryID[0]=="CA"){
       	 $joomla('#dialcodeTxt').val("+"+countryID[1]);
            $joomla("#dialcodeTxt option:selected").text("CANADA(+1)");
   		}else{
   		 $joomla('#dialcodeTxt').val("+"+countryID[1]);
   		 $joomla("#dialcodeTxt option:selected").text(""+countryTxt1+" (+"+countryID[1]+")");
   		 
   		}    	
   
   		if(countryID){
   			$joomla.ajax({
   				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&countryid="+countryID[0] +"&stateflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
   				data: { "country": $joomla("#countryTxt").val() },
   				dataType:"html",
   				type: "get",
   				beforeSend: function() {
                        $joomla('.page_loader').show();
                   },
   				success: function(data){
   					$joomla('#stateTxt').html(data);
   					$joomla('.page_loader').hide(); 
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
   				beforeSend: function() {
                        $joomla('.page_loader').show();
                   },
   				success: function(data){
   					$joomla("#cityTxt").html(data);
   					$joomla('.page_loader').hide();
   				}
   			}); 
   		}else{
   			//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
   		}
   	});
   	
   	var domainName = '<?php echo strtolower($domainName); ?>';
   	
   	if(domainName == 'kupiglobal'){
   	    
   	    $joomla.ajax({
   				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&stateid=BandH&cityflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
   				data: { "state": $joomla("#countryTxt").val() },
   				dataType:"html",
   				type: "get",
   				beforeSend: function() {
                        $joomla('.page_loader').show();
                   },
   				success: function(data){
   					$joomla("#cityTxt").html(data);
   					$joomla('.page_loader').hide();
   				}
   			}); 
   	    
   	}
   	
   	$joomla("input[name='emailTxt']").blur(function(){
   	    if($joomla(this).val() !=''){
   	    $joomla.ajax({
   	            url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&emailTxt="+$joomla("#emailTxt").val() +"&emailflag=1&clientid=<?php echo $companyId; ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                  type: "get",
                  dataType: 'text',
                  beforeSend: function() {
                    $joomla('.page_loader').show();
                    },
                  success: function(data) {
                      res = data.split(":");
                      if(res[0]==1){
                                $joomla('.page_loader').hide();
                              // $joomla('#emailTxt-error').hide();
                                return true;
                      }else{
                          
                                if(res[1] == "do_not_mail Email"){
                                    res[1] = "Invalid Email"
                                }
                                
                                alert("Error : "  + res[1] + ' ! Please try again with a valid Email Id.');
                                $joomla("#emailTxt").val("");
                                $joomla('.page_loader').hide();
                                return false;
                      }
                  }
   	        });
   	    }
   	        
   	});
   	
   
       // $joomla("input[name='cityTxt']").change(function(){
         
       //     var val = $joomla(this).val();
           
       //     var xyz = $joomla('#cityTxt option').filter(function() {
       //         return this.value == val;
       //     }).data('xyz');
       //     if(xyz){
       //         $joomla(this).val(val);
       //     }
       //     $joomla("input[name='cityTxtdiv']").val(xyz);
           
          
           
       // });	
   	
   	
       //validation for all fields
   	jQuery.validator.addMethod(
   	"alphanumeric", 
   	function(value, element) {
   	    accType = $joomla("#accounttypeTxt").val();
   	    //console.log(this.optional(element) || /^[a-zA-Z ]+$/i.test(value));
   	    if(accType == "COMP")
   	        return this.optional(element) || /^[a-zA-Z !@#$%^&*()_+<>?{:;"'}]+$/i.test(value);
   	    else
   		return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
   		},
   		"<?php echo JText::_('ENTER_THE_NAME_ALPHANUMARIC_CHARACTORES');?>"
   	);
   	
   	
   	 //validation for email fields
   	jQuery.validator.addMethod(
   	"validateEmail", 
   	function(value, element) {
   	    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
           return re.test(value);
   		},
   		"<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_VALID_EMAIL_ADDRESS');?>"
   	);
   	
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
       
   });
</script>
<div class="item_fields">
   <form name="registerFormOne" id="registerFormOne" method="post" action="" autocomplete="off" enctype="multipart/form-data">
       <input autocomplete="false" name="hidden" type="text" style="display:none;">
      <input name="agentId" id="agentId"  value='<?php echo base64_decode($AgencyId); ?>' type="hidden">
      <div class="container">
         <div class="register_view">
            <div class="main_panel">
               <div class="main_heading"> <?php echo Jtext::_('COM_REGISTER_REGISTRATION');?> </div>
               <div class="panel-body">
                  <?php
                     if($res == '0' ){
                          $errorMsg = $_GET['msg'];
                         
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
                  <h4 class="heading"><?php echo Jtext::_('COM_REGISTER_PERSONAL_INFORMATION');?></h4>
                  <div class="row">
                     <div class="col-sm-6">
                        <?php if($elem['ACCOUNTTYPE'][1] == "ACT"){  ?> 
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo Jtext::_('COM_REGISTER_ACCOUNT_TYPE_LABEL');?></label><?php if($elem['ACCOUNTTYPE'][2]){ ?><span class="error">*</span><?php } ?>
                              </div>
                              
                             
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <select class="form-control" name="accounttypeTxt" id="accounttypeTxt" <?php if($elem['ACCOUNTTYPE'][2]){ ?> required <?php } ?> >
                                    <?php 
                                        foreach($accTypeDet as $accType){
                                            if($accType->status == "ACT"){
                                                echo '<option value="'.$accType->id_values.'" data-default="'.$accType->is_default.'">'.$accType->desc_vals.'</option>';
                                            }
                                        }
                                    ?>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <?php } if($elem['FIRSTNAME'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12 fname">
                                 <label><?php echo $assArr['first_name'];?><?php if($elem['FIRSTNAME'][2]){ ?><span class="error">*</span><?php } ?></label>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="25" <?php if($elem['FIRSTNAME'][2]){ ?> required <?php } ?> >
                              </div>
                           </div>
                        </div>
                       <?php } if($elem['LASTNAME'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12 lname">
                                  <label><?php echo $assArr['last_Name'];?><?php if($elem['LASTNAME'][2]){ ?><span class="error">*</span><?php } ?></label>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="text" class="form-control" name="lnameTxt" id="lnameTxt" maxlength="25" <?php if($elem['LASTNAME'][2]){ ?> required <?php } ?> >
                              </div>
                           </div>
                        </div>
                        <?php } if($elem['EMAIL'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['email'];?><?php if($elem['EMAIL'][2]){ ?><span class="error">*</span><?php } ?></label>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="text" class="form-control" name="emailTxt" id="emailTxt" maxlength="50" <?php if($elem['EMAIL'][2]){ ?> required <?php } ?> >
                              </div>
                           </div>
                        </div>
                         <?php } if($elem['GENDER'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo Jtext::_('COM_REGISTER_GENDER_LABEL');?></label><?php if($elem['GENDER'][2]){ ?><span class="error">*</span><?php } ?>
                              </div>
                              <div class="rdo_rd1 col-md-6 col-sm-6 col-xs-12">
                                 <input type="radio" checked name="genderTxt" value="Male" <?php if($elem['GENDER'][2]){ ?> required <?php } ?> >
                                 <label><?php echo Jtext::_('COM_REGISTER_MALE_LABEL');?></label>
                                 <input type="radio" name="genderTxt" value="Female" <?php if($elem['GENDER'][2]){ ?> required <?php } ?> >
                                 <label><?php echo Jtext::_('COM_REGISTER_FEMALE_LABEL');?> </label>
                              </div>
                           </div>
                        </div>
                         <?php } if($elem['PROFILEPICTURE'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['Profile_picture'];?></label><?php if($elem['PROFILEPICTURE'][2]){ ?><span class="error">*</span><?php } ?>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="file" class="form-control" name="userphotoTxt" id="userphotoTxt" <?php if($elem['PROFILEPICTURE'][2]){ ?> required <?php } ?> >
                                 <?php echo Jtext::_('COM_REGISTER_PIC_TEXT_LABEL');?>
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                     </div>
                     <div class="col-sm-6">
                          <?php if($elem['ADDRESS1'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['address_1'];?><?php if($elem['ADDRESS1'][2]){ ?><span class="error">*</span><?php } ?></label>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="text" class="form-control" name="addressTxt" id="addressTxt" maxlength="50" <?php if($elem['ADDRESS1'][2]){ ?> required <?php } ?> >
                              </div>
                           </div>
                        </div>
                         <?php } if($elem['ADDRESS2'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['address_2'];?></label><?php if($elem['ADDRESS2'][2]){ ?><span class="error">*</span><?php } ?>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="text" class="form-control" name="address2Txt" id="address2Txt" maxlength="50" <?php if($elem['ADDRESS2'][2]){ ?> required <?php } ?> >
                              </div>
                           </div>
                        </div>
                        <?php } if($elem['COUNTRY'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['country'];?><?php if($elem['COUNTRY'][2]){ ?><span class="error">*</span><?php } ?></label>
                              </div>
                              
                             <?php  if(strtolower($domainName) != 'kupiglobal'){ ?>
                              
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <?php
                                    $countryView= RegisterHelpersRegister::getCountriesList();
                                    $arr = json_decode($countryView); 
                                                  $countries='';
                                                 // if($arr->ResCode ==1 ){
                                                     
                                                  foreach($arr->Data as $rg){
                                        
                                                     $countries.= '<option  value="'.$rg->CountryCode.':'.preg_replace('/[^0-9]/', '', $rg->CountryDailCodes).'" >'.$rg->CountryDesc.'</option>';
                                                  }
                                                  
                                                //  }
                                                   
                                    ?>
                                 <select class="form-control" id="countryTxt" name="countryTxt" <?php if($elem['COUNTRY'][2]){ ?> required <?php } ?> >
                                    <option value="0"><?php echo Jtext::_('COM_REGISTER_SELECT_COUNTRY_LABEL');?></option>
                                    <?php echo $countries;?>
                                 </select>
                              </div>
                              
                              <?php }else{ ?>
                              
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <?php
                                    $countryView= RegisterHelpersRegister::getCountriesList();
                                    $arr = json_decode($countryView); 
                                                   $countries='';
                                                      if($arr->ResCode ==1){
                                    foreach($arr->Data as $rg){
                                        
                                        if($rg->CountryCode == "BiH"){
                                            //$countries.='<option value="0">'.Jtext::_('COM_REGISTER_SELECT_COUNTRY_LABEL').'</option>';
                                             $countries.= '<option value="BiH:" selected>'.$rg->CountryDesc.'</option>';
                                        }
                                                     
                                    }
                                                     }
                                    ?>
                                 <select class="form-control" id="countryTxt" name="countryTxt" <?php if($elem['COUNTRY'][2]){ ?> required <?php } ?> >
                                    <?php echo $countries;?>
                                 </select>
                              </div>
                              
                              <?php }  ?>
                              
                              
                              
                             
                              
                           </div>
                        </div>
                         <?php } if($elem['STATE'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['state'];?><?php if($elem['STATE'][2]){ ?><span class="error">*</span><?php } ?></label>
                              </div>
                              
                                 <?php  if(strtolower($domainName) != 'kupiglobal'){ ?>
                            
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <select class="form-control" id="stateTxt" name="stateTxt" <?php if($elem['STATE'][2]){ ?> required <?php } ?>>
                                    <option value="0"><?php echo Jtext::_('COM_REGISTER_SELECT_STATE_LABEL');?></option>
                                 </select>
                              </div>
                              
                              <?php }else{ ?>
                              
                               <div class="col-md-8 col-sm-6 col-xs-12">
                                 <select class="form-control" id="stateTxt" name="stateTxt" <?php if($elem['STATE'][2]){ ?> required <?php } ?> >
                                     <!--<option value="0"><?php echo Jtext::_('COM_REGISTER_SELECT_STATE_LABEL');?></option>-->
                                     <option selected  value="BandH">Bosnia and Herzegovina</option>
                                 </select>
                              </div>
                              
                              <?php }  ?>
                              
                           </div>
                        </div>
                         <?php } if($elem['CITY'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['city'];?> </label><?php if($elem['CITY'][2]){ ?><span class="error">*</span><?php } ?>
                              </div>
                              <!-- <div class="col-md-8 col-sm-6 col-xs-12">-->
                              <!--   <input type="text" class="form-control"  name="cityTxt" list="cityTxt" autocomplete="off" />-->
                              <!--<datalist id="cityTxt"></datalist><input type="hidden" name="cityTxtdiv" id="cityTxtdiv">-->
                              <!-- </div>-->
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <select type="text" class="form-control"  name="cityTxt" id="cityTxt" autocomplete="off" <?php if($elem['CITY'][2]){ ?> required <?php } ?> >
                                    <option value=""><?php echo Jtext::_('COM_REGISTER_SELECT_CITY_LABEL');?></option>
                                 </select>
                              </div>
                           </div>
                        </div>
                         <?php } if($elem['ZIPCODE'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['zip_code'];?> </label><?php if($elem['ZIPCODE'][2]){ ?><span class="error">*</span><?php } ?>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="text" class="form-control" name="zipTxt" id="zipTxt" minlength="3" maxlength="10" <?php if($elem['ZIPCODE'][2]){ ?> required <?php } ?> >
                              </div>
                           </div>
                        </div>
                        <?php } if($elem['PHONENUMBER'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo $assArr['Telephone_number'];?><?php if($elem['PHONENUMBER'][2]){ ?><span class="error">*</span><?php } ?></label>
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
                                               $dialcode.= '<option value="'.$rg->DailCode.'"';
                                               
                                             //   if($rg->DailCode == "+1"){
                                             //       $dialcode.='selected';
                                             //   }
                                               
                                                $dialcode.= '>'.$rg->Description.'</option>';
                                           } ?>
                                       <select class="form-control" id="dialcodeTxt" name="dialcodeTxt">
                                          <option value="0">Select Dialcode</option>
                                          <?php echo $dialcode;?>
                                       </select>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                       <input type="text" class="form-control" maxlength="10" name="phoneTxt" id="phoneTxt" <?php if($elem['PHONENUMBER'][2]){ ?> required <?php } ?> >
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        
                         <?php } ?> 
                        
                        <!-- Identification Type Block -->
                        
                        
                       
                        <?php if($elem['IDENTIFICATIONTYPE'][1] == "ACT"){ ?>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo  $assArr['identification_type'];?><?php if($elem['IDENTIFICATIONTYPE'][2]){ ?><span class="error">*</span><?php } ?></label>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <?php
                                    $idtypeView= RegisterHelpersRegister::getidentityList();
                                    $arr = json_decode($idtypeView); 
                                    $idtype='';
                                    foreach($arr as $rg){
                                     $idtype.= '<option value="'.$rg->id_values.'">'.$rg->id_values.'</option>';
                                    } 
                                    ?>
                                 <select class="form-control" id="idtypeTxt" name="idtypeTxt" <?php if($elem['IDENTIFICATIONTYPE'][2]){ ?> required <?php } ?> >
                                    <option value="0"><?php echo Jtext::_('COM_REGISTER_SELECT_IDENTITY_LABEL');?></option>
                                    <?php echo $idtype;?>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <?php } if($elem['IDENTIFICATIONVALUE'][1] == "ACT"){ ?>
                          <!-- Identification value Block -->
                        
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                  <label><?php echo $assArr['identification_value'];?><?php if($elem['IDENTIFICATIONVALUE'][2]){ ?><span class="error">*</span><?php } ?></label>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="text" class="form-control" name="idvalueTxt" id="idvalueTxt" maxlength="16" <?php if($elem['IDENTIFICATIONVALUE'][2]){ ?> required <?php } ?> >
                              </div>
                           </div>
                        </div>
                        
                         <?php } ?>
                         
                     </div>
                  </div>
                  <h4 class="heading"><?php echo Jtext::_('COM_REGISTER_INFORMATION_LABEL');?></h4>
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12 pswd-img">
                                 <label><?php echo Jtext::_('COM_REGISTER_PASSWORD_LABEL');?> <span class="error">*</span></label>
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
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="password" class="form-control" maxlength="32" name="passwordTxt" id="passwordTxt">
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4 col-sm-6 col-xs-12">
                                 <label><?php echo Jtext::_('COM_REGISTER_CONFIRM_LABEL');?><span class="error">*</span></label>
                              </div>
                              <div class="col-md-8 col-sm-6 col-xs-12">
                                 <input type="password" class="form-control" maxlength="32" name="confirmpasswordTxt" id="confirmpasswordTxt">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  
                  <?php
                  
                    $requrlArr = explode("/",$_SERVER['REQUEST_URI']);
                    
                    if(count($requrlArr) == 5){
                        $envfoldername = '/'.$requrlArr[1];
                        $language = $requrlArr[3];
                    }else if(count($requrlArr) == 4){
                        $envfoldername = '';
                        $language = $requrlArr[2];
                    }
                    
                   
                    if(file_exists($_SERVER['DOCUMENT_ROOT'].$envfoldername.'/templates/protostar/clients/'.strtolower($domainName).'/terms_conditions_'.$language.'.php')){
                        $html = file_get_contents(JURI::base().'templates/protostar/clients/'.strtolower($domainName).'/terms_conditions_'.$language.'.php');
                        $dom = new DOMDocument();
                        $dom->loadHTML($html);
                        $xpath = new DOMXPath($dom);
                        $div = $xpath->query('//div[@id="terms_conditions"]');
                        $div = $div->item(0);
                        $tandc_content = $dom->saveXML($div);
                    }else{
                        $tandc_content = "<center>No Data</center>";
                    }
                    
                    // echo $_SERVER['DOCUMENT_ROOT'].$envfoldername.'/templates/protostar/'.strtolower($domainName).'/terms_conditions_'.$language.'.html';
                    // echo JURI::base().'templates/protostar/'.strtolower($domainName).'/terms_conditions_'.$language.'.html';
                    
                  ?>
                  
                  <h4 class="heading"><?php echo str_replace('XXXX',strtoupper($companyName),Jtext::_('COM_REGISTER_TERM_LABEL'));?></h4>
                  <div class="row">
                     <div class="col-sm-12">
                        
                           <?php echo $tandc_content;  ?>
                       
                     </div>
                     
                     <div class="clearfix"></div>
                     
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
                           <button type="submit" class="btn btn-primary"><?php echo Jtext::_('COM_REGISTER_CREATE_ACCOUNT_LABEL');?></button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <input type="hidden" name="task" value="register.save">
      <input type="hidden" name="id" value="0" />
      <input type="hidden" name="domainurl" value="<?php echo $domainurl; ?>" />
   </form>
</div>
<?php
   }
   ?>
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
      <a href="<?php echo JRoute::_('index.php?option=com_register&task=register.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger"> <?php echo JText::_('COM_REGISTER_DELETE_ITEM'); ?> </a> 
   </div>
</div>
<?php endif; ?>
<script>
   function recaptchaCallback() {
     $joomla('#hiddenRecaptcha').valid();
   };
   
      
     $joomla('#userphotoTxt').on('change', function() {
           //this.files[0].size gets the size of your file.
           
           if($joomla("input[name=userphotoTxt]").val()!=''){
           
           $joomla("#errorTxt-error").html('');
           var ext = $joomla('input[name=userphotoTxt]').val().split('.').pop().toLowerCase();
               if($joomla.inArray(ext, ['gif','png','jpg','jpeg','GIF','PNG','JPG','JPEG']) == -1) {
               $joomla('input[name=userphotoTxt]').after('<p><label id="errorTxt-error" class="error" for="errorTxt"><?php echo Jtext::_('COM_REGISTER_INVALID_EXT_ERROR');?>!</label></p>');
               $joomla('#userphotoTxt').val('');
               return false;
           }else{
             $joomla("#errorTxt-error").parent().remove();    
           }
           if(this.files[0].size>1000000){
               $joomla('input[name=userphotoTxt]').after('<p><label id="errorTxt-error" class="error" for="errorTxt">Please upload below 1MB file size<br></label></p>');
               return false;
           }else{
             $joomla("#errorTxt-error").parent().remove();  
           }
           
       }
           
       });
   
   
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>