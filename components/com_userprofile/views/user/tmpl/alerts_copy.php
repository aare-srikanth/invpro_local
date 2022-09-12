<?php
ini_set('memory_limit', '44M');
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
$document = JFactory::getDocument();
$document->setTitle("Order Process in Boxon Pobox Software");
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}

// get domain details start

   $clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
   $clientConf = json_decode($clientConfigObj, true);
   $clients = $clientConf['ClientList'];
   
   $domainDetails = ModProjectrequestformHelper::getDomainDetails();
   $CompanyId = $domainDetails[0]->CompanyId;
   $companyName = $domainDetails[0]->CompanyName;
   $domainEmail = $domainDetails[0]->PrimaryEmail;
   $domainName =  $domainDetails[0]->Domain;
   
   // get domain details end
   
   
   // dynamic elements
   
   $res = Controlbox::dynamicElements('PreAlerts');
   $elem=array();
   foreach($res as $element){
      $elem[$element->ElementId]=array($element->ElementDescription,$element->ElementStatus,$element->is_mandatory,$element->is_default,$element->ElementValue);
   }
   
// echo '<pre>';   
// var_dump($elem);exit;

// end

$language = $session->get('lang_sel');

// language transalation start

//   $url = "http://filerepstage.justfordemo.biz/Resource/".$CompanyId."/Resource_".$language.".xml";
//   $xml = simplexml_load_file($url);
  
// //   var_dump($url);
// //   var_dump($xml);exit;
  
//   if($xml){
//       $url = "http://filerepstage.justfordemo.biz/Resource/Default/Resource_".$language.".xml";
//       $xml = simplexml_load_file($url);
//   }
      
//     $dynamicLabel = array();
//     foreach ($xml->data as $xmldata){
        
//         $name = $xmldata['name'];
//         $labelname = (string)$name;
//         $labelval = (string)($xmldata->value);
//         $dynamicLabel[$labelname]=$labelval;
        
//     }



// language transalation end

// var_dump($dynamicLabel);
// exit;

// get labels

    $res=Controlbox::getlabels();
    $assArr = [];
    
    foreach($res->data as $response){
    $assArr[$response->id]  = $response->text;
    }

?>
<?php include 'dasboard_navigation.php' ?>
<script type="text/javascript" src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->


<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    var domainName = '<?php echo strtolower($domainName); ?>';
    var carrierReq = '';
    jQuery.getJSON('<?php echo JURI::base(); ?>/client_config.json', function(jd) {
              //console.log(jd.ClientList);
              var iterator = jd.ClientList.values();
                for (let elements of iterator) {
                    if(elements.Domain.toLowerCase() == domainName ){
                         carrierReq = elements.Carrier;
                    }
                }
    });
    
    $joomla('.page_loader').hide();
   
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    
    
    
    

   $joomla(document).on('change','input[name="txtFile"],#multxtFile,input[type="file"]', function() {
       
            if(this.files.length > 1){
                 alert('<?php echo Jtext::_('Should not exceed more than 1 file.') ?>');
                 $joomla(this).val('');
            }
       
             if(this.files[0].size > 2000000){
                 alert('<?php echo Jtext::_('COM_USERPROFILE_FILE_SIZE_ERROR') ?>');
                  $joomla(this).val('');
             }
             
        for(i=0;i<this.files.length;i++){
             
            var filename=this.files[i].name;
            var ext = filename.split('.').pop().toLowerCase(); 
            // var wrname = filename.split('..');
            
            // if(wrname.length > 1){
            //     alert('<?php echo Jtext::_('COM_USERPROFILE_INVALID_EXT');?>');
            //     $joomla(this).val('');
            // }
            if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf']) == -1) {
               
                 alert('<?php echo Jtext::_('COM_USERPROFILE_INVALID_EXT');?>');
                 $joomla(this).val('');
            }else{
              $joomla("input[name=addinvoiceTxt] #errorTxt-error").html('');    
            }
            
        }
             
    
  });
    
    $joomla('#itemstatusTxt,#updateStatus').attr("disabled", true); 

    
    $joomla("input[name=txtQty]").css("width","80px");
    
    if($joomla( "#orderdateTxt" )) 
    $joomla( "#orderdateTxt" ).datepicker({ maxDate: new Date });
   
    var tmp='';
    tmp=$joomla("#ord_edit .modal-body").html();
    
    $joomla('input[name="carriertrackingTxt"]').on('keypress',function(e) {
    if (e.keyCode == 32) {
        return false;
    }
});

 $joomla('input[name="carriertrackingTxt"]').on('keydown',function(e) {
   if($joomla(this).val().length <=1 ){
      $joomla("#track_error").html('');
   }
 });
    
    
    
        //exist tracking
    $joomla('input[name="carriertrackingTxt"]').on('blur',function(){
       
        var res=$joomla(this).val();
        if(res.indexOf(' ')<=0){
         $joomla('#track_error').html("");
         if(res!="")
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&trackexisttype="+res+"&trackexistflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "trackid": $joomla(this).val() },
			dataType:"html",
			type: "get",
			beforeSend: function() {
             //$joomla('input[name="carriertrackingTxt"]').after('<div id="loading-image4" ><img src="/components/com_userprofile/images/loader.gif"></div>');
               $joomla('.page_loader').show();
           },success: function(data){
             if(data.length==11){
                $joomla('#tabs1 .btn-primary').attr("disabled", false);
            //     $joomla('#loading-image4').each( function () {
            //     $joomla(this).remove();
            //   });
               $joomla('.page_loader').hide();
               $joomla('#track_error').html("");
             }else{
                 $joomla('.page_loader').hide();
                $joomla('#track_error').html("<label class='error'>"+data+"</label>");
                $joomla('#tabs1 .btn-primary').attr("disabled", true);
             }
                 
             }
		});
        }else{
            $joomla('#track_error').html("<label class='error'>Spaces are not allowed please enter again</label>");
            $joomla(this).val("");
        }
    });

    

    // Initialize form validation on the registration form.
    // It has the name attribute "registration"
    var validfirst=$joomla("form[name='userprofileFormOne']").validate({
    
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      mnameTxt: {
       
     },
      carrierTxt: {
        required: function(element) {
            
        if($joomla("#carrierVis").val() == 1){
            return true;
        }else{
            return false;
        }
                     
                    },
        alphanumeric:false
     },
      carriertrackingTxt: {
        
     },
      orderdateTxt: {
        
     },
      "addinvoiceTxtMul_1[]": {
        
     },
     country3Txt:{
        required:true
     },
      "anameTxt[]": {
       
     },
      "quantityTxt[]": {
       
     },
      "declaredvalueTxt[]": {
        maxlength:7
     },
      "totalpriceTxt[]": {
     },
      "itemstatusTxt[]": {
     }
    },
    // Specify validation error messages
    messages: {
      mnameTxt: {required:"<?php echo Jtext::_('COM_USERPROFILE_ALERTS_MERCHANT_ERROR');?>"},
      carrierTxt: {required:"<?php echo Jtext::_('COM_USERPROFILE_ALERTS_CARRIER_ERROR');?>",alphanumeric:"<?php echo Jtext::_('COM_USERPROFILE_ALERTS_ALPHABET_ERROR');?>"},
      carriertrackingTxt: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_CARRIER_TRACKING_ERROR');?>",
      orderdateTxt: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_ORDER_DATE_ERROR');?>",
      addinvoiceTxt: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_UPLOAD_INVOICE_ERROR');?>",
      country3Txt: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_COUNTRY_ERROR');?>",
      "anameTxt[]": {required:"<?php echo Jtext::_('COM_USERPROFILE_ALERTS_ARTICLE_NAME_ERROR');?>"},
      "quantityTxt[]": "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_QUANTITY_ERROR');?>",
      "declaredvalueTxt[]": {required:"<?php echo Jtext::_('COM_USERPROFILE_ALERTS_ITEM_PRICE_ERROR');?>",maxlength:"Please enter max 7 digit number"},
      "totalpriceTxt[]": "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_DECLARED_VALUE_ERROR');?>",
      "itemstatusTxt[]":{
    	required: "Please select status",
    	selectBox: "Please select status"
      }
    
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
        
            $joomla('#tabs1 .btn-primary').attr("disabled", true);
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
        var regex = new RegExp(/\./g)
        var count = $joomla('input[name^="declaredvalueTxt[]"]' ).val().match(regex).length;
        if (count > 1)
        {
            alert('Please enter valid Item Price');
            return false;
            
        }
	
        $joomla("input[name=addinvoiceTxt]").bind('change', function() {
            alert('This file size is: ' + this.files[0].size/1024/1024 + "MB");
            return false;
        });
        if($joomla("input[name=addinvoiceTxt]").val()!=""){
            $joomla("input[name=addinvoiceTxt] #errorTxt-error").html('');
            var ext = $joomla('input[name=addinvoiceTxt]').val().split('.').pop().toLowerCase();
            // wrname = $joomla('input[name=addinvoiceTxt]').val().slice(-2).reverse().pop();
            // alert(wrname);
            if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf']) == -1) {
                $joomla('input[name=addinvoiceTxt]').after('<label id="errorTxt-error" class="error" for="errorTxt"><?php echo Jtext::_('COM_USERPROFILE_INVALID_EXT');?>!</label>');
                return false;
            }else{
              $joomla("input[name=addinvoiceTxt] #errorTxt-error").html('');    
            }
        }
         if($joomla("#country3Txt").val()=="BBB" && $joomla("#state3Txt").val()==""){
            $joomla("#state3Txt").after('<label id="errorTxt-error" class="error" for="errorTxt">Select State</label>');
            return false;
              
         }{
              form.submit();
              
         }
         $joomla('#tabs1 .btn-primary').attr("disabled", true);
         }
    });
    
   
    
    host = window.location.host;
    hostArr = host.split(".");
    if(hostArr[0] == "lokesh"){
        $joomla("input[name=addinvoiceTxt]").rules("remove", "required");
    }
    
    
    $joomla.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z/ /]+$/.test(value);
    });
    
    $joomla.validator.addMethod("alphanumericNew", function(value, element) {
        return this.optional(element) || /^[a-zA-Z/ /!@#\$%\^\&*\)\(+=._-]+$/.test(value);
    });

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
		""
	);
    
    
  $joomla('#tabs1 #N_table').on('click','a:nth-child(1)',function(e){
        e.preventDefault();
        var resnew=$joomla(this).data('id');
        $joomla.ajax({
        	url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&orderupdatetype="+resnew +"&orderupdateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
        	data: { "orderupdatetype": $joomla(this).data('id') },
        	dataType:"html",
        	type: "get",
        	cache: false,
        	beforeSend: function() {
              $joomla("#ord_edit .modal-body").html('');
              $joomla(".page_loader").show();
             },success: function(data){
               $joomla(".page_loader").hide();
              $joomla("#ord_edit .modal-body").html(tmp); 
              var cospor=data;
              console.log(data);
              cospor=cospor.split(":");
             
              console.log("itemid::"+cospor[0])
              $joomla('input[name=txtItemId]').val(cospor[0]);
              $joomla('input[name=txtMerchantName]').val(cospor[1]);
              $joomla('input[name=txtCarrierName]').val(cospor[2]);
              $joomla('input[name=txtOrderDate]').val(cospor[3]);
              $joomla('input[name=txtTracking]').val(cospor[4]);
              $joomla('input[name=txtArticleName]').val(cospor[5]);
              $joomla('input[name=txtQuantity]').val(cospor[6]);
              $joomla('input[name=txtDvalue]').val(cospor[7]);
              $joomla('input[name=txtTotalPrice]').val(cospor[8]);
              $joomla('radia[name=txtStatus]').val(cospor[9]);
              $joomla('input[name=txtOrderId]').val(cospor[19]); 
              $joomla('input[name=txtRmaValue]').val(cospor[20]);
             
              
                if(cospor[3]){
                  
                  $joomla('input[name=txtMerchantName]').attr('readonly',true); 
                  $joomla('input[name=txtCarrierName]').attr('readonly',true);
                  $joomla('input[name="txtOrderDate"]').attr('readonly',true);
                  $joomla('input[name="txtOrderDate"]').datepicker("destroy");
                  
                  $joomla('input[name=txtTracking]').attr('readonly',true);
                  $joomla('input[name=txtTracking]').prop("disabled", true);
              }else{
                  $joomla('input[name=txtMerchantName]').attr('readonly',false);
                  $joomla('input[name=txtCarrierName]').attr('readonly',false);
                  $joomla('input[name="txtOrderDate"]').attr('readonly',false);
                  $joomla('input[name=txtTracking]').attr('readonly',false);
                  $joomla('input[name=txtTracking]').prop("disabled",false);

              if($joomla( 'input[name="txtOrderDate"]' ))
              $joomla('input[name="txtOrderDate"]').attr('readonly',true);
              $joomla('input[name="txtOrderDate"]').removeClass('hasDatepicker').datepicker({maxDate: new Date()}); 
              }
              
              if(!carrierReq){
                  $joomla('input[name=txtCarrierName]').attr('readonly',false);
              }
              
              
             
              if(cospor[12]){
              var fileName = cospor[12];
              var fileName1 = cospor[15];
              var fileName2 = cospor[16];
              var fileName3 = cospor[17];
              var fileName4 = cospor[18];
          
              
              var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
              if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                var hrefs=cospor[12];
                hrefs=hrefs.split(' ').join('%20');
                hrefs=hrefs.replace("##",":");
                $joomla('#mulorderimage').html('<a href='+hrefs+' target="_blank">(View Invoice)</a>');
                $joomla('input[name=multxtFileId1]').val(hrefs);
              }else{
                  $joomla('#userprofileFormTwo').validate().settings.rules.txtFile = {required: true};
              }
              
              var ext1 = fileName1.substring(fileName1.lastIndexOf('.') + 1);
              if(ext1 =="GIF" || ext1=="gif" || ext1 =="jpeg" || ext1=="JPEG"  || ext1=="pdf"  || ext1 =="PNG" || ext1=="png"  || ext1=="JPG"  || ext1=="jpg" ){
                var hrefs=cospor[15];
                hrefs=hrefs.split(' ').join('%20');
                hrefs=hrefs.replace("##",":");
                $joomla('#mulorderimage').append('<a href='+hrefs+' target="_blank">(View Invoice)</a>');
                $joomla('input[name=multxtFileId2]').val(hrefs);
              }
              
              var ext2 = fileName2.substring(fileName2.lastIndexOf('.') + 1);
              if(ext2 =="GIF" || ext2=="gif" || ext2 =="jpeg" || ext2=="JPEG"  || ext2=="pdf"  || ext2 =="PNG" || ext2=="png"  || ext2=="JPG"  || ext2=="jpg" ){
                var hrefs=cospor[16];
                hrefs=hrefs.split(' ').join('%20');
                 hrefs=hrefs.replace("##",":");
                $joomla('#mulorderimage').append('<a href='+hrefs+' target="_blank">(View Invoice)</a>');
                $joomla('input[name=multxtFileId3]').val(hrefs);
              }
              
              var ext3 = fileName3.substring(fileName3.lastIndexOf('.') + 1);
              if(ext3 =="GIF" || ext3=="gif" || ext3 =="jpeg" || ext3=="JPEG"  || ext3=="pdf"  || ext3 =="PNG" || ext3=="png"  || ext3=="JPG"  || ext3=="jpg" ){
                var hrefs=cospor[17];
                hrefs=hrefs.split(' ').join('%20');
                 hrefs=hrefs.replace("##",":");
                $joomla('#mulorderimage').append('<a href='+hrefs+' target="_blank">(View Invoice)</a>');
                $joomla('input[name=multxtFileId4]').val(hrefs);
              }
              
              var ext4 = fileName4.substring(fileName4.lastIndexOf('.') + 1);
              if(ext4 =="GIF" || ext4=="gif" || ext4 =="jpeg" || ext4=="JPEG"  || ext4=="pdf"  || ext4 =="PNG" || ext4=="png"  || ext4=="JPG"  || ext4=="jpg" ){
                var hrefs=cospor[18];
                hrefs=hrefs.split(' ').join('%20');
                 hrefs=hrefs.replace("##",":");
                $joomla('#mulorderimage').append('<a href='+hrefs+' target="_blank">(View Invoice)</a>');
                $joomla('input[name=multxtFileId5]').val(hrefs);
              }
              
              }
              
                 
            }

        });
    });    
 

    //delete inventry purchases order
    $joomla('#tabs1 #N_table').on('click','a:nth-child(2)',function(e){
        e.preventDefault();
        var res=$joomla(this).data('id');
        var reshtml=$joomla(this);
        var cf=confirm("<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_CONFIRM_DELETE');?>");
        if(cf==true){
            $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&orderdeletetype="+res +"&orderdeleteflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "orderdeletetype": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
                  $joomla(".page_loader").show();
               },success: function(data){
                    if(data==1){
                        //console.log(reshtml.closest('tr').hide());
                        $joomla(".page_loader").hide();
                        reshtml.closest('tr').hide();
                    }
                }
    		});
        }	
        return false;
    });
    
    $joomla('input[name^="quantityTxt[]"]' ).live('blur',function(e){
        $joomla(this).closest('.row').find('div:nth-child(4) input').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla(this).closest('.row').find('div:nth-child(3) input').val()));
        if(total>0){
            $joomla(this).closest('.row').find('div:nth-child(4) input').val(parseFloat(total).toFixed(2));
        }
    });
    $joomla('input[name^="declaredvalueTxt[]"]' ).live('blur',function(e){
        $joomla(this).closest('.row').find('div:nth-child(4) input').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla(this).closest('.row').find('div:nth-child(2) input').val()));
        if(total>0){
            $joomla(this).closest('.row').find('div:nth-child(4) input').val(parseFloat(total).toFixed(2));
        }    
    });
    
    

    $joomla('input[name="txtQuantity"]').live('blur',function(){
        $joomla('input[name="txtTotalPrice"]').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla('input[name="txtDvalue"]').val()));
        if(total>0){
            $joomla('input[name="txtTotalPrice"]').val(total).toFixed(2);
        }
    });
    $joomla('input[name="txtDvalue"]').live('blur',function(){
        $joomla('input[name="txtTotalPrice"]').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla('input[name="txtQuantity"]').val()));
     
        if(total>0){
            $joomla('input[name="txtTotalPrice"]').val(total).toFixed(2);
        }
    });

   
     $joomla(function() {
 
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormTwo']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          txtMerchantName: {
            required: true,
            alphanumeric:true
          },
          txtCarrierName: {
            required: function(element) {
            
            if($joomla("#carrierVis").val() == 1){
            return true;
            }else{
            return false;
            }
                     
                    },
            alphanumeric:true
          },
          txtOrderDate: {
            required: true
          },
          txtArticleName: {
            required: true
          },
          txtDvalue: {
            required: true
          },
          txtTracking: {
            required: true
          },
          txtQuantity: {
            required: true
          },
          txtFile: {
              required: {
                  depends: function(element) {
                      if($joomla("#orderimage").html() == ''){
                          return true;
                      }
                  }    
              }
          }
        },
        // Specify validation error messages
        messages: {
          txtMerchantName: {required:"<?php echo Jtext::_('COM_USERPROFILE_ALERTS_MERCHANT_ERROR');?>",alphanumeric:"<?php echo Jtext::_('COM_USERPROFILE_ALERTS_ALPHABET_ERROR');?>"},
          txtOrderDate: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_ORDER_DATE_ERROR');?>",
          txtArticleName: {required:"<?php echo Jtext::_('COM_USERPROFILE_ALERTS_ARTICLE_NAME_ERROR');?>"},
          txtDvalue: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_ITEM_PRICE_ERROR');?>",
          txtCarrierName: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_CARRIER_ERROR');?>",
          txtTracking: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_CARRIER_TRACKING_ERROR');?>",
          txtQuantity: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_QUANTITY_ERROR');?>",
          txtFile: "<?php echo Jtext::_('COM_USERPROFILE_ALERTS_FILE_ERROR');?>"
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
       		 $joomla('input[name=txtTracking]').prop("disabled", false);
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
            var regex = new RegExp(/\./g)
            var count = $joomla('input[name^="txtDvalue"]' ).val().match(regex).length;
            if (count > 1)
            {
                alert('Please enter valid Item Price');
                return false;
                
            }
	
          form.submit();
        }
        });    
    });
    $joomla("input[name='btnReset']").click(function(e){
       var alt=confirm("<?php echo Jtext::_('COM_USERPROFILE_ALERTS_RESET_ALERTS');?>");
       if(alt==true)    
       $joomla("#userprofileFormOne").trigger("reset");    
    }); 

   $joomla("input[name='txtQty']").keyup(function(e){
    if(parseFloat($joomla(this).val())>parseFloat($joomla(this).closest("tr").find("input[name='txtItemQty']").val())){
      $joomla('#myAlertModal').modal("show");
      $joomla('#error').html("Please enter less than quantity Number");
      this.value = this.value.replace($joomla(this).val(), $joomla(this).closest("tr").find("input[name='txtItemQty']").val());
    }else{
      //this.value = this.value.replace(/[^0-9/.]/g, '');
    }
        
    });
   $joomla("input[name='txtQuantity']").live('keyup',function(e){
    this.value = this.value.replace(/[^0-9]/g, '');
    //if (/\D/g.test(this.value))
    //this.value.replace(/[0-9]*\.?[0-9]+/g, '');  for name
    });
   $joomla(document).on("keyup","input[name='quantityTxt[]']",function(e){
    this.value = this.value.replace(/[^0-9]/g, '');
   });
   

   $joomla("input[name='txtDvalue']").live('keypress',function (e) {
    if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
    }
    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
    });
   $joomla(document).on("keypress","input[name='declaredvalueTxt[]']",function (e) {
    if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
    }
    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
    });
   


    $joomla('#tabs1').on('click','input[name="addrow"]',function(e){
        
        var i=0;
        $joomla('input[name="addrow"]').each(function(){
                i++;
        });
        
        if($joomla("form[name='userprofileFormOne']").valid() == true){
            cnt = i+1;
        }
        
        
       $joomla('#itemCount span').html(cnt);
        
      if(validfirst.form()==true){
       
      var rp=$joomla(this).closest('.rows').find('input[name="quantityTxt[]"]').attr('id');
      var er=rp+1;
      
      var rp2=$joomla(this).closest('.rows').find('input[name="declaredvalueTxt[]"]').attr('id');
      var er2=rp2+1;
      
      var rp3=$joomla(this).closest('.rows').find('input[name="totalpriceTxt[]"]').attr('id');
      var er3=rp3+1;
      
      var rp4=$joomla(this).closest('.rows').find('input[name="itemstatusTxt[]"]').attr('id');
      var er4=rp4+1;
      
      var rp5=$joomla(this).closest('.rows').find('input[type="file"]').attr('name');
      var idinv5=$joomla(this).closest('.rows').find('input[type="file"]').attr('id');
      var er5=rp5.replace('_'+i,'_'+(i+1));
      var addidinv5=idinv5.replace('_'+i,'_'+(i+1));
      
      var rp6=$joomla(this).closest('.rows').find('input[name="rmavalue[]"]').attr('id');
      var er6=rp6.replace('_'+i,'_'+(i+1));
      
      var rp7=$joomla(this).closest('.rows').find('input[name="orderidTxt[]"]').attr('id');
      var er7=rp7.replace('_'+i,'_'+(i+1));
      
      var rp8=$joomla(this).closest('.rows').find('input[name="anameTxt[]"]').attr('id');
      var er8=rp8+1;
      
      var sd=$joomla(this).closest('.rows').html().replace('id="'+rp+'"','id="'+er+'"').replace('id="'+rp2+'"','id="'+er2+'"').replace('id="'+rp3+'"','id="'+er3+'"').replace('id="'+rp4+'"','id="'+er4+'"').replace('name="'+rp5+'"','name="'+er5+'"').replace('id="'+idinv5+'"','id="'+addidinv5+'"').replace('id="'+rp6+'"','id="'+er6+'"').replace('id="'+rp7+'"','id="'+er7+'"').replace('id="'+rp8+'"','id="'+er8+'"');
      
      $joomla('<div class="row rows row-mob">'+sd+'</div>').insertAfter( $joomla(this).closest('.row') );
      $joomla('#tabs1 .rows:last').find('td:last').html('');
      $joomla('#tabs1 .rows:last').find('td:last').html('<input class="btn btn-danger btn-rem" type="button" name="deleterow" value="X">');
      
      //$joomla("input[name='"+er5+"']").attr('required',true);
      
      }          
    });
    $joomla('#tabs1').on('click','input[name="deleterow"]',function(e){
      var lastone=$joomla('#tabs1 .rows').html();
      if($joomla('#tabs1 .rows').length==1){
        alert('Minimum One Row Required');
        return false;
      }else
        $joomla(this).closest('.rows').remove();
        var i=0;
        $joomla('input[name="addrow"]').each(function(){
            i++;
        });
        
       $joomla('#itemCount span').html(i);

    });
    
	$joomla('#country2Txt').on('change',function(){
	    $joomla('input[name="city2Txt"]').val('');
		$joomla('#state2Txt').val(0);
		$joomla('#city2Txt').html('');
		var countryID = $joomla(this).val();
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&countryid="+$joomla("#country2Txt").val() +"&stateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
					$joomla('#state2Txt').html(data);
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
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&stateid="+$joomla("#state2Txt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
					$joomla('#city2Txt').append(data);
				}
			}); 
		}else{
			$joomla('#city2Txt').html('<option value="">Select City</option>'); 
		}
	});  
	$joomla("input[name='city2Txt']").blur(function(){
        
        var val = $joomla(this).val()
        var xyz = $joomla('#city2Txt option').filter(function() {
            return this.value == val;
        }).data('xyz');
        if(xyz){
            $joomla(this).val(xyz);
        }
        $joomla("input[name='city2Txtdiv']").val(val);
     });

	

    $joomla('input[name="txtTracking"]').live('blur',function(e){
               e.preventDefault();
 
        var res=$joomla(this).val();
        $joomla('#loading-image4').html('');
        if(res!=""){
            $joomla('#ord_edit .btn-primary').attr('disabled',true);
            $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&trackexisttype="+res+"&trackexistflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "trackid": $joomla(this).val() },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
    			    $joomla('.page_loader').show();
                 },success: function(data){
                   $joomla('.page_loader').hide();
                 if(data.length==11){
                    $joomla('#ord_edit .btn-primary').attr('disabled',false);
                    $joomla('#loading-image4').each( function () {
                    $joomla(this).remove();
                   });
                 }else{
                    $joomla('#ord_edit .btn-primary').attr('disabled',true);
                    $joomla('#loading-image4').html("<label class='error'>"+data+"</label>");
                 }
                     
                 }
    		});
        }	
    });

	$joomla('#country3Txt').on('change',function(){
		var countryID = $joomla(this).val();
		if(countryID=="BBB"){
		   $joomla('#state3TxtDiv').css('display','block'); 
		
    		if(countryID){
    			$joomla.ajax({
    				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&countryid="+$joomla("#country3Txt").val() +"&hubstateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    				data: { "country": $joomla("#countryTxt").val() },
    				dataType:"html",
    				type: "get",
    				success: function(data){
    					$joomla('#state3TxtDiv').css('display','block');
    					$joomla('#state3Txt').html('<option value="">Select State</option>'+data);
    				}
    			});
    		}
    		$joomla('#state3Txt').html('<option value="">Select State</option>');
		}else
		{
		   $joomla('#state3TxtDiv').css('display','none'); 
		}
	});
	
	$joomla('#orderdateTxt,#addinvoiceTxt').on('change', function(e) {
        //$joomla("#orderdateTxt-error").hide();
    });
	
  
});


</script>
<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_ALERTS_MAIN_HEADING');?></div>
    <div class="panel-body">
        
        <?php
        
            foreach($clients as $client){ 
                if(strtolower($client['Domain']) == strtolower($domainName) ){   
                    $prealert_text=$client['Myprealerts_text_dashboard'];
                    $pre_alert_subtitle1 = $client['Pre_alert_subtitle'];
                    $pre_alert_subtitle2 = $client['Pre_alert_subtitle2'];
                    $Default_qnt_prealert = $client['Default_qnt_prealert'];
                    $Default_qnt_prealert_readonly = $client['Default_qnt_prealert_readonly'];
                    
                }
            }

        ?>
        
        
      <div class="row">
        <div class="col-sm-12 tab_view">
          <ul class="nav nav-tabs">
              
            <li> <a class="active" ><?php echo $assArr['my_Pre_Alerts'];?></a> </li>
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocess"><?php echo $assArr['ready_to_ship'];?></a> </li>
           <!--  <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=cod">COD</a> </li>-->
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=shiphistory"><?php echo $assArr['shipment_History']; ?></a> </li>
          </ul>
        </div>
      </div>
      
     
      <div id="tabs1">
        <form name="userprofileFormOne" autocomplete="off" id="userprofileFormOne" method="post" action="" enctype="multipart/form-data">
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            
          <div class="row">
            <div class="col-sm-6">
              <h3 class="m-0"><strong><?php echo Jtext::_($pre_alert_subtitle1);?></strong></h3>
            </div>
          </div>
          <div class="row">
              
            <?php if($elem['MerchantName'][1] == "ACT"){  ?>  
            <div class="col-sm-12 col-md-4">
                
                <?php 
                    foreach($clients as $client){ 
                        if(strtolower($client['Domain']) == strtolower($domainName) ){
                            $Carrier = $client['Carrier'];
                        }
                    } 
                ?>
              
              <input type="hidden" class="form-control" name="carrierVis" id="carrierVis" value="<?php echo $Carrier; ?>" >
              
              <div class="form-group">
                <label><?php echo $assArr['merchant_name']; ?><?php if($elem['MerchantName'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input class="form-control" name="mnameTxt" value="<?php if($elem['MerchantName'][3]){  echo $elem['MerchantName'][4];  } ?>" id="mnameTxt" maxlength="32" <?php if($elem['MerchantName'][2]){ echo "required"; } ?> >
              </div>
            </div>
            <?php } if($elem['Carrier'][1] == "ACT"){ ?>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['carrier'];?><?php if($elem['Carrier'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input class="form-control" name="carrierTxt" value="<?php if($elem['Carrier'][3]){  echo $elem['Carrier'][4];  } ?>" id="carrierTxt" maxlength="32" <?php if($elem['Carrier'][2]){ echo "required"; } ?> >
              </div>
            </div>
             <?php } if($elem['CarrierTrackingID'][1] == "ACT"){ ?>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['tracking_ID_of_the_operator']; ?><?php if($elem['CarrierTrackingID'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input class="form-control" name="carriertrackingTxt" value="<?php if($elem['CarrierTrackingID'][3]){  echo $elem['CarrierTrackingID'][4];  } ?>" id="carriertrackingTxt" maxlength="40" <?php if($elem['CarrierTrackingID'][2]){ echo "required"; } ?> >
                <div id="track_error" ></div>
              </div>
            </div>
            <?php } ?>
          </div>
          <div class="row">
            <?php if($elem['OrderDate'][1] == "ACT"){ ?>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['order_date']; ?><?php if($elem['OrderDate'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input class="form-control" name="orderdateTxt" value="<?php if($elem['OrderDate'][3]){  echo $elem['OrderDate'][4];  } ?>"  readonly id="orderdateTxt" <?php if($elem['OrderDate'][2]){ echo "required"; } ?> >
              </div>
            </div>
             <?php } if($elem['AddInvoice'][1] == "INACT"){ ?>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['add_Invoice ']; ?><?php if($elem['AddInvoice'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="file" class="form-control" name="addinvoiceTxt"  id="addinvoiceTxt" <?php if($elem['AddInvoice'][2]){ echo "required"; } ?> >
                <label><?php echo Jtext::_('COM_USERPROFILE_ALERTS_INVOICE_VALID');?></label>
                <!-- -->
              </div>
            </div>
            <?php } ?>
            <!--multiple invoice -->
            
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['destination_country'];?> <span class="error">*</span></label>
                <?php
					       $countryView= UserprofileHelpersUserprofile::getCountriesList();
					       $arr = json_decode($countryView); 
                           $countries='';
					       foreach($arr->Data as $rg){
					          $countries.= '<option value="'.$rg->CountryCode.'">'.$rg->CountryDesc.'</option>';
                           }
             
    					?>
                <select class="form-control" name="country3Txt" id="country3Txt" >
                  <option value=""><?php echo Jtext::_('COM_USERPROFILE_ALERTS_SELECT_COUNTRY');?></option>
                  <?php echo $countries;?>
                </select>
              </div>
            </div>
            
            <div class="col-sm-12 col-md-4" id="state3TxtDiv" style="display:none">
              <div class="form-group">
                <label>Destination State <span class="error">*</span></label>
                <select class="form-control"  name="state3Txt" id="state3Txt">
                  <option value="0">Select State</option>
                </select>
              </div>
            </div>

          </div>
          
          
          <div class="row rows row-mob">
            <?php if($elem['ArticleName'][1] == "ACT"){ ?>  
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['article_name']; ?><?php if($elem['ArticleName'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input class="form-control" name="anameTxt[]" value="<?php if($elem['ArticleName'][3]){  echo $elem['ArticleName'][4];  } ?>"  id="1" maxlength="32" <?php if($elem['ArticleName'][2]){ echo "required"; } ?>>
              </div>
            </div>
            <?php } if($elem['Quantity'][1] == "ACT"){  ?>
            <div class="col-sm-12 col-md-2">
              <div class="form-group">
                <label><?php echo $assArr['quantity']; ?><?php if($elem['Quantity'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input class="form-control" name="quantityTxt[]" id="2" value="<?php if($elem['Quantity'][3]){  echo intval($elem['Quantity'][4]);  } ?>" maxlength=3 value="<?php echo $Default_qnt_prealert; ?>"  <?php echo $Default_qnt_prealert_readonly; ?> <?php if($elem['Quantity'][2]){ echo "required"; } ?> >
              </div>
            </div>
             <?php } if($elem['ItemPrice'][1] == "ACT"){  ?>
            <div class="col-sm-12 col-md-2">
              <div class="form-group">
                <label><?php echo $assArr['item_Price_(USD)']; ?><?php if($elem['ItemPrice'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input class="form-control" name="declaredvalueTxt[]" maxlength="7" id="3" <?php if($elem['ItemPrice'][2]){ echo "required"; } ?> >
              </div>
            </div>
            <?php } if($elem['DeclaredValue'][1] == "ACT"){  ?>
            <div class="col-sm-12 col-md-2">
              <div class="form-group">
                <label><?php echo $assArr['declared_Value_(USD)'];?><?php if($elem['DeclaredValue'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input class="form-control" name="totalpriceTxt[]"  id="4" readonly <?php if($elem['DeclaredValue'][2]){ echo "required"; } ?> >
              </div>
            </div>
             <?php  if($elem['ItemStatus'][1] == "ACT"){ ?> 
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['item_status']; ?><?php if($elem['ItemStatus'][2]){ ?><span class="error">*</span><?php } ?></label>
                <select class="form-control" name="itemstatusTxt[]" id="itemstatusTxt" <?php if($elem['ItemStatus'][2]){ echo "required"; } ?> >
                    
                    <?php 
                    
                        $statulist = Controlbox::getStatusList();
                        foreach($statulist as $list){
                            $def_status = '';
                            if($list->StatusId == "In Progress"){
                                $def_status = "selected";
                            }
                            echo '<option value="'.$list->StatusId.'" '.$def_status.' >'.$list->StatusDescription.'</option>';
                        }
            
                    ?>
                    
                  <!--<option value="In Progress"><?php echo Jtext::_('COM_USERPROFILE_ALERTS_INPROGRESS');?></option>-->
                  <!--<option value="Hold"><?php echo Jtext::_('COM_USERPROFILE_ALERTS_HOLD');?></option>-->
                  
                </select>
                <input type="hidden" name="itemstatusTxt[]" value="In Progress" >
              </div>
            </div>
            <?php } ?>
        <div class="clearfix"></div>
           
                <!--order id , rma value-->
                
            <?php } if($elem['RMAValue'][1] == "ACT"){    ?>
        
            
         <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['rMA_Value']; ?><?php if($elem['RMAValue'][2]){ ?><span class="error">*</span><?php } ?></label>
                    <input  class="form-control" name="rmavalue[]" value="<?php if($elem['RMAValue'][3]){  echo $elem['RMAValue'][4];  } ?>"  id="rmavalue_1" maxlength="100" <?php if($elem['RMAValue'][2]){ echo "required"; } ?> >
              </div>
        </div>
        
         <?php } if($elem['OrderID'][1] == "ACT"){    ?>
        
            
            <div class="col-sm-12 col-md-2">
              <div class="form-group">
                <label><?php echo $assArr['order_ID']; ?><?php if($elem['OrderID'][2]){ ?><span class="error">*</span><?php } ?></label>
                    <input  class="form-control" value="<?php if($elem['OrderID'][3]){  echo $elem['OrderID'][4];  } ?>"  name="orderidTxt[]" id="orderidTxt_1"  maxlength="100" <?php if($elem['OrderID'][2]){ echo "required"; } ?> >
              </div>
            </div>
            
           <?php } ?>
           
              <?php if($elem['AddInvoice'][1] == "ACT"){ ?>
             <div class="col-sm-12 col-md-5">
              <div class="form-group">
                <label><?php echo $assArr['add_Invoice '];?> <?php if($elem['AddInvoice'][2]){ ?><span class="error">*</span><?php } ?></label>
                
                <input type="file"  class="form-control" name="addinvoiceTxtMul_1[]" id="addinvoiceTxtMul_1" multiple <?php if($elem['AddInvoice'][2]){ echo "required"; } ?> >
                <!--<label><?#php echo $assArr['add_Invoice'];?></label>-->
               
              </div>
            </div>
            
            <?php } ?>
           
            <!-- End -->
            
            <div class="col-sm-12 col-md-2">
              <div class="form-group btn-grp1">
                <input type="button" name="addrow" value="+" class="btn btn-primary btn-add"> 
                <input type="button" name="deleterow" value="x" class="btn btn-danger btn-rem">
              </div>
            </div>
            
             <div class="clearfix"></div>
             
          </div>
          <div class="row">
          <div class="col-sm-12 col-md-2 item-cnt" id="itemCount" ><p class="badge"><?php echo Jtext::_('COM_USERPROFILE_TOTAL_ITEMS'); ?> : <span>1</span></p></div>
          </div>
          <div class="clearfix"></div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="button" name="btnReset" value="<?php echo $assArr['reset'];?>" class="btn btn-danger">
                <input type="submit" name="btnSubmit" value="<?php echo  $assArr['submit'];?>" class="btn btn-primary">
              </div>
            </div>
          </div>
          
         
          
          <input type="hidden" name="task" value="user.addshippment">
          <input type="hidden" name="id" value="0" />
          <input type="hidden" name="user" value="<?php echo $user;?>" />
        </form>
        <div class="row">
          <div class="col-sm-12 inventry-alert">
              <div class="col-sm-6">
            <h3 class=""><strong><?php echo Jtext::_($pre_alert_subtitle2);?></strong></h3>
            </div>
            <div class="col-sm-6 text-right">
               <a style="color:white;" href="<?php echo $assArr['eXPORT_CSV']; ?>/csvdata/pre_alerts_ind.csv" class="csvDownload export-csv btn btn-primary text-right">Export CSV</a>
          </div>
          </div>
        </div>
        
        <?php  
        
         Controlbox::getInvertoryPurchasesListCsv($user);
         
        ?>
        
       
        
        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered theme_table" id="N_table">
              <thead>
                <tr>
                  <th><?php echo $assArr['sNo'];?></th>
                  <th><?php echo $assArr['merchant_name']; ?></th>
                  <th><?php echo $assArr['article_name']; ?></th>
                  <th><?php echo $assArr['order_date']; ?></th>
                  <th><?php echo $assArr['quantity']; ?></th>
                  <th><?php echo $assArr['tracking_ID_of_the_operator']; ?> #</th>
                  <th><?php echo $assArr['declared_Value_(USD)']; ?></th>
                  <th><?php echo $assArr['order_ID']; ?></th>
                  <th><?php echo $assArr['rMA_Value']; ?></th>
                  <th><?php echo $assArr['status'];?></th>
                  <th><?php echo $assArr['action'];?></th>
                </tr>
              </thead>
              <tbody>
<?php
    $ordersView= UserprofileHelpersUserprofile::getInvertoryPurchasesList($user);
    
    // echo '<pre>';
    // var_dump($ordersView);
    
    $i=1;
    foreach($ordersView as $rg){
            if($rg->itemstatus=="In Progress"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_IN_PROGRESS');
         }else if($rg->itemstatus=="Hold"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_HOLD');
         }else{
             $status = $rg->itemstatus;
         }
      echo '<tr><td>'.$i.'</td><td>'.$rg->SupplierId.'</td><td>'.$rg->ItemName.'</td><td>'.$rg->OrderDate.'</td><td>'.$rg->ItemQuantity.'</td><td>'.$rg->TrackingId.'</td><td>'.$rg->cost.'</td><td>'.$rg->OrderIdNew.'</td><td>'.$rg->RMAValue.'</td><td>'.$status.'</td><td class="action_btns"><a href="#" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id='.$rg->Id.' data-target="#ord_edit"><i class="fa fa-pencil-square-o"></i></a><a href="#" class="btn btn-danger" data-id='.$rg->Id.'><i class="fa fa-trash"></i></a></td></tr>';
    $i++;
    }
?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<form name="userprofileFormTwo" id="userprofileFormTwo" method="post" action="" enctype="multipart/form-data">
  <div id="ord_edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">         
          <input type="button" data-dismiss="modal"  value="x" class="btn-close1" >       
          <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_ALERTS_UPDATE_MYPRE_ALERTS');?></strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['merchant_name'];?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="txtMerchantName" maxlength="32" >
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['carrier']; if($Carrier){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control"  name="txtCarrierName" maxlength="32" >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['order_date'];?> <span class="error">*</span></label>
                <input  readonly class="form-control"  name="txtOrderDate" id="txtOrderDate"  >
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['tracking_ID_of_the_operator'];?><span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtTracking" id="txtTracking" maxlength="40">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['article_name'];?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtArticleName" maxlength="32">
              </div>
            </div>
            <!--<div class="col-sm-12 col-md-6">-->
            <!--  <div class="form-group">-->
            <!--    <label><?php echo $assArr['add_Invoice'];?> <span class="error">*</span></label>-->
                
            <!--    <input type="hidden" class="form-control"  name="txtFileId">-->
            <!--    <input type="file" class="form-control"  name="txtFile">-->
            <!--    <?php echo Jtext::_('COM_USERPROFILE_ALERTS_UPLOAD_INVOICE_VALIDATION_TXT');?><div id="orderimage"></div>-->
            <!--  </div>-->
            <!--</div>-->
            
            <!--multiple invoice-->
            
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['add_Invoice ']; ?><span class="error">*</span></label>
                
                <input type="hidden" class="form-control"  name="multxtFileId1">
                <input type="hidden" class="form-control"  name="multxtFileId2">
                <input type="hidden" class="form-control"  name="multxtFileId3">
                <input type="hidden" class="form-control"  name="multxtFileId4">
                <input type="file" class="form-control"  name="multxtFile[]" id="multxtFile" multiple>
                <?php echo Jtext::_('COM_USERPROFILE_ALERTS_UPLOAD_INVOICE_VALIDATION_TXT');?><div id="mulorderimage"></div>
              </div>
            </div>
            
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['quantity'];?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtQuantity" maxlength="3" value="<?php echo $Default_qnt_prealert; ?>"  <?php echo $Default_qnt_prealert_readonly; ?> >
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['item_Price_(USD)'];?><span class="error">*</span></label>
                <input type="text" placeholder="0.00" class="form-control"  name="txtDvalue" maxlength="7">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                  <label><?php echo $assArr['declared_Value_(USD)']; ?> <span class="error">*</span></label>
                  <input type="text" placeholder="0.00" readonly="readonly" class="form-control"  name="txtTotalPrice">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['item_status'];?></label>
                <select class="form-control" name="txtStatus" id="updateStatus">
                  <?php 
                    
                        $statulist = Controlbox::getStatusList();
                        foreach($statulist as $list){
                            $def_status = '';
                            if($list->StatusId == "In Progress"){
                                $def_status = "selected";
                            }
                            echo '<option value="'.$list->StatusId.'" '.$def_status.' >'.$list->StatusDescription.'</option>';
                        }
            
                    ?>
                    
                </select>
                <input type="hidden" name="txtStatus" value="In Progress" >
              </div>
            </div>
          </div>
          
          <!-- order id, rma value -->
          
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                  <label><?php echo $assArr['order_ID']; ?></label>
                  <input type="text" class="form-control" name="txtOrderId" maxlength="100">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['rMA_Value']; ?></label>
                 <input type="text" class="form-control" name="txtRmaValue" maxlength="100">
              </div>
            </div>
          </div>
          
          <!-- End -->
          
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" value="<?php echo Jtext::_('COM_USERPROFILE_ALERTS_UPDATE');?>" class="btn btn-primary">
              <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_ALERTS_CLOSE');?>" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="task" value="user.userupdatepurchase">
  <input type="hidden" name="id"/>
  <input type="hidden" name="txtItemId"/>
  <input type="hidden" name="user" value="<?php echo $user;?>" />
</form>

<!-- Modal -->
<div id="view_image" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">
        <h4 class="modal-title"><strong>Image View</strong></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="viewImage"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
