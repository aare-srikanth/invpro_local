<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->setTitle("Shopper Assist in Boxon Pobox Software");
$session = JFactory::getSession();
session_start();
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';
$user=$session->get('user_casillero_id');
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
if($_GET['r']==1){
    $app = JFactory::getApplication();
    $app->enqueueMessage("Payment Done Suessfully", 'success');
}

// get domain details start

   $clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
   $clientConf = json_decode($clientConfigObj, true);
   $clients = $clientConf['ClientList'];
   
   $domainDetails = ModProjectrequestformHelper::getDomainDetails();
   $domainName =  $domainDetails[0]->Domain;
   
   foreach($domainDetails[0]->PaymentGateways as $PaymentGateways){
       if($PaymentGateways->PaymentGatewayName == "Paypal"){
            $PaypalEmail = $PaymentGateways->Email;
            $AccountType = strtolower($PaymentGateways->AccountType);
            $ApiUrl = strtolower($PaymentGateways->ApiUrl);
       }
            
   }
   
   $invoiceCountRes = UserprofileHelpersUserprofile::GetInvoicesCount($user,'shopperassist');
   
   
   // dynamic elements
   
   $res = Controlbox::dynamicElements('ShopperAssist');
   $elem=array();
   foreach($res as $element){
      $elem[$element->ElementId]=array($element->ElementDescription,$element->ElementStatus,$element->is_mandatory,$element->is_default,$element->ElementValue);
   }
   
   //var_dump($elem['MerchantName'][0]);exit;
   
   // end
   
   // get labels
    $lang=$session->get('lang_sel');
    $res=Controlbox::getlabels($lang);
    $assArr = [];
    
    foreach($res->data as $response){
    $assArr[$response->id]  = $response->text;
    }

?>

<?php include 'dasboard_navigation.php' ?>
<script type="text/javascript" src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/css/styles.css">
<link rel="stylesheet" href="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/css/demo.css">
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
--> 
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    
    
    // authoriz.net error msg display
    
    var url_string = window.location.href;
    var url = new URL(url_string);
    var error_msg = url.searchParams.get("error");
    if(error_msg != null ){
        alert(error_msg);
         $joomla(".page_loader").show();
        window.location.href = "<?php echo JURI::base(); ?>/index.php/component/userprofile/user?layout=shopperassist";
    }
    
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    if($joomla( "#orderdateTxt" ))
    $joomla( "#orderdateTxt" ).datepicker();
    
    // Function to create the cookie
function createCookie(name, value, days) {
    var expires;
      
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
      
    document.cookie = escape(name) + "=" + 
        escape(value) + expires + ";path=/";
}
    
     // get payment gateways
       
        $joomla('input[name="paymentmethod"]').on('click',function(e){
            var paymentmethod = $joomla(this).val();
            var ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&paymentmethod="+paymentmethod;
          
            $joomla.ajax({
       			url: ajaxurl,
       			data: { "paymentmethodflag": 1 },
       			dataType:"text",
       			type: "get",
                beforeSend: function() {
                    $joomla(".page_loader").show();
                },
                success: function(data){
                    $joomla(".page_loader").hide();
                    $joomla("#paymentgatewaysDiv").html(data);
                }
           });
           
        });
       
       // End
       
        $joomla('.goback').on('click',function(e){
            $joomla("input[name='txtitemId']").attr("checked",false);
            $joomla("textarea[name='txtSpecialIn']").val("");
             $joomla("input[name='txtShippingMethod']").attr("checked",false);
            
        });
       
       
       
       $joomla(document).on('click','#shippingMethod',function(){
           $joomla(".dvPaymentInformation").hide();
       });
       
       $joomla(document).on('click','input[name=cc]',function(){
           $joomla("label.error").html("");
           
        //   if($joomla(this).val() != 'COD'){
        //     // convenience fees 
        //       $joomla.ajax({
        //           url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="amount"]').val() +"&convflag=1&gateway="+$joomla(this).val()+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
       	// 		data: { "shippmentid": 1 },
       	// 		dataType:"html",
       	// 		type: "get",
       	// 		beforeSend: function() {
        //              //$joomla(".pagshipup_prepaid").show();
        //              $joomla(".page_loader").show();
        //              $joomla('#ord_ship #step2 .btn-primary').attr("disabled", true);
        //           },success: function(data){
        //               slot=1
                     
        //               var sg=data;
        //               sg=sg.split(":");
        //               $joomla('input[name="Conveniencefees"]').val(sg[1]);
        //               $joomla('input[name=amtStr]').val(sg[0]);
        //               $joomla('#divTotalShippingCharges').html(sg[0]);
        //               $joomla('input[name=amount]').val(sg[0]);
                       
        //               $joomla(".page_loader").hide();
        //               //$joomla(".pagshipup_prepaid").hide();
                      
        //              $joomla('#ord_ship #step2 .btn-primary').attr("disabled", false);
        //           }
        //       });
               
        //   }  
           
               if($joomla(this).val() == 'Paypal'){
                   $joomla('#userprofileFormTwo').attr('action','https://www.sandbox.paypal.com/cgi-bin/webscr');
               }
                if($joomla(this).val() == 'Stripe'){
                    var feitem=[];
                    $joomla("input[name='invFile[]']").each( function () {
                   var tem=$joomla(this).attr('id');
                   for (var i = 0; i < $joomla(this).get(0).files.length; i++) {
                       //var file_data = $joomla(this).prop('files')[0];
                       var file_data = $joomla(this).get(0).files[i];
                       var form_data = new FormData();                  
                       form_data.append('file', file_data);
                       $joomla.ajax({
                       	url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&uploadflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                   	    dataType: 'text',  // what to expect back from the PHP script, if anything
                           cache: false,
                           contentType: false,
                           processData: false,
                           data: form_data,                         
                           type: 'post',
                       	beforeSend: function() {
                             $joomla(".pagshipup").show();
                             $joomla('.pagshipdown').hide();
                             $joomla('#ord_ship #step3 .btn-primary').attr("type", "button");
                             $joomla('#ord_ship #step3 .btn-primary').attr("disabled", true);
                           },success: function(data){
                             $joomla('#ord_ship #step3 .btn-primary').attr("type", "submit");
                             $joomla('#ord_ship #step3 .btn-primary').attr("disabled", false);
                             $joomla(".pagshipup").hide();
                             $joomla('.pagshipdown').show();
                             feitem.push(tem+"-"+data);
                             
                             /** Debug **/
                             console.log(feitem);
                             
                             $joomla('input[name=paypalinvoice]').val(feitem); 
                           }
                       });
                   }
               }); 
                    
                    $joomla(".dvPaymentInformation input").val("");
                    $joomla(".dvPaymentInformation select").val("");
                  $joomla('#userprofileFormTwo').attr('action','');
                  $joomla(".dvPaymentInformation").show();
                }else if($joomla(this).val() == 'authorize.net'){
                    $joomla(".dvPaymentInformation input").val("");
                    $joomla(".dvPaymentInformation select").val("");
                    $joomla(".dvPaymentInformation").show();
                    $joomla('#userprofileFormTwo').attr('action','<?php echo JURI::base(); ?>payment.php');
                
                }else{
                    $joomla(".dvPaymentInformation").hide();
                }
       });
       
       
       

    $joomla(function() {
        
        // payment submit start
       
      
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormOne']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          txtMerchantName: {
            alphanumeric:true
         },
          txtMerchantWebsite: {
           
         },
          "txtItemName[]": {
            alphanumeric:true
         },
          "txtItemRefference[]": {
            alphanumeric3:true
         },
          "txtQuantity[]": {
         },
          "txtDvalue[]": {
         },
          "txtTprice[]": {
         },
          "txtItemurl[]": {
         },
          "txtItemModel[]": {
         },
          "txtColor[]": {
         },
          "txtSize[]": {
         }
         
        },
        // Specify validation error messages
        messages: {
          txtMerchantName:{ required:"<?php echo $assArr['merchant_name_error'];  ?>",alphanumeric:"Enter alphabet characters"},
          txtMerchantWebsite:{ required:"<?php echo $assArr["merchant's_website_error"];  ?>",alphanumeric2:"Please enter alphanumeric characters"},
          "txtItemName[]":{ required:"<?php echo $assArr['item_name_error'];  ?>",alphanumeric:"Please enter alphabet characters"},
          "txtItemRefference[]":{ required:"<?php echo $assArr['merchant_name_error'];  ?>",alphanumeric3:"Please enter alphanumeric characters"},
          "txtQuantity[]": "<?php echo $assArr['quAntity_error'];  ?>",
          "txtDvalue[]": "<?php echo $assArr['declared_Value_(USD)_error'];  ?>",
          "txtTprice[]": "<?php echo $assArr['item_Price_(USD)_error'];  ?>",
          "txtItemurl[]":{ required:"<?php echo $assArr['article_URL_error'];  ?>",alphanumeric2:"Please enter alphanumeric characters"},
          "txtItemdescription[]": "<?php echo $assArr['item_Description_error'];  ?>",
          "txtItemModel[]": "<?php echo $assArr['item_Model_error'];  ?>",
          "txtColor[]": "<?php echo $assArr['color_error']; ?>",
          "txtSize[]": "<?php echo $assArr['size_error'];  ?>"
    
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
    		var regex = new RegExp(/\./g)
            var count = $joomla('input[name^="txtDvalue"]' ).val().match(regex).length;
            if (count > 1){
                alert('Please enter valid Declared Vlaue');
                return false;
            }
          form.submit();
        }
        }); 
        
    });
    
    $joomla.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z/ /]+$/.test(value);
    });
    $joomla.validator.addMethod("alphanumeric2", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9/./:/-/]+$/.test(value);
    });
    $joomla.validator.addMethod("alphanumeric3", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    });
    $joomla.validator.addMethod("alphanumeric4", function(value, element) {
        return this.optional(element) || /^[0-9]+$/.test(value);
    });
    
   
    
    $joomla.validator.addMethod("currentdates", function(value, element) {
     if($joomla('select[name="YearDropDownListStr"]').val()=="<?php echo date('Y');?>" && $joomla('select[name="MonthDropDownListStr"]').val()<="<?php echo date('m');?>"){
      return false 
     }else{
      return true 
     }
    }, '<?php echo Jtext::_('Card expiry year and month not validated');?>');
    
    
  
    
    $joomla("input[name='txtQuantity']").keyup(function () {     
        this.value = this.value.replace(/[^0-9]/g,'');
        
        if($joomla(this).val().length == 1 && $joomla(this).val() == 0){
            
             $joomla(this).val(''); 
        }
    });
    $joomla("input[name='txtDvalue']").keyup(function () {     
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    $joomla("input[name='txtSize']").keyup(function () {     
        this.value = this.value.replace(/[^a-zA-Z0-9\.]/g,'');
    });
    $joomla('input[name="txtTaxes"]').on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
            $joomla(this).val($joomla(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $joomla(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
    });
    $joomla('input[name="txtShippCharges"]').on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
            $joomla(this).val($joomla(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $joomla(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
    });
    
    $joomla('input[name=txtitemId]').on('click',function(){
        $joomla(".dvPaymentInformation").hide();
        $joomla("label.error").html("");
        $joomla("span.error").html("");
    });
    $joomla('#tabs1').on('click','.btn-primary:last',function(){
         $joomla('label.error').remove();
         if (($joomla("input[name*='txtitemId']:checked").length)<=0) {
           $joomla(this).after('<span class="error"><?php echo Jtext::_('PLEASE_CHECK_ORDERS');?></span>');  
           return false;
         }else
         
    
         var checks = [];
         $joomla.each($joomla("input[name='txtitemId']:checked"), function(){            
           checks.push($joomla(this).val());
         });
         var checkresult=checks.join(", ");
         $joomla('#hiddenTableValues').html(checkresult);

         $joomla('#tabs2').show();
         $joomla('#tabs3').hide();
    });
    
    $joomla('#tabs2').on('click','.btn-primary',function(){
     if($joomla("textarea[name='txtSpecialIn']").val() !=''){
         var tablevalues=$joomla('#hiddenTableValues').html();
         tablevalues=tablevalues.split(",");
         var cal=0;
         var ids='';
         var qyt='';
         var spi='';
         $joomla('#k_table').find("tr:gt(0)").remove();

         for(i=0;i<tablevalues.length;i++){
             var dtvalues=tablevalues[i].split(":");
             
             
             var img1=""; 
             var img2=""; 
             var img3=""; 
             var img4=""; 
             
             if(dtvalues[5]){
                 image1=dtvalues[5];
                 img1 = '<a href="'+image1.replace("#",":")+'" target="_blank">View Invoice</a>';
             }
             if(dtvalues[6]){
                 image2=dtvalues[6];
                 img2 = '<a href="'+image2.replace("#",":")+'" target="_blank">View Invoice</a>';
             }
            
             if(dtvalues[7]){
                 image3=dtvalues[7];
                 img3 = '<a href="'+image3.replace("#",":")+'" target="_blank">View Invoice</a>';
             }
             if(dtvalues[8]){
                 image4=dtvalues[8];
                 img4 = '<a href="'+image4.replace("#",":")+'" target="_blank">View Invoice</a>';
             }
             
             $joomla('#k_table').append('<tr><td>'+dtvalues[1]+'</td><td>'+dtvalues[2]+'</td><td>'+dtvalues[3]+'</td><td>'+dtvalues[4]+'</td><td><input type="file" multiple id="'+dtvalues[0]+'" name="invFile[]" >'+img1+img2+img3+img4+'</td></tr>');
             cal+=parseFloat(dtvalues[4]);
             ids+=dtvalues[0]+",";
             qyt+=dtvalues[2]+",";
             spi+=dtvalues[1]+",";
         }
         $joomla('input[name="hiddenItemIds"]').val(ids);
         $joomla('input[name="hiddenItemQuantity"]').val(qyt);
         $joomla('input[name="hiddenItemSupplierId"]').val(spi);
         var tax=0;
         tax=parseFloat($joomla('input[name="txtTaxes"]').val()) || 0;
         $joomla('input[name="hiddentxtTaxes"]').val(tax);
         $joomla('#divtxtTaxes').html(tax);
         
         var tsc=0;
         tsc=parseFloat($joomla('input[name="txtShippCharges"]').val()) || 0;
         $joomla('input[name="hiddentxtShippCharges"]').val(tsc);
         $joomla('#divtxtShippCharges').html(tsc);
         var totals=cal+tax+tsc;
         $joomla('input[name="amount"]').val(totals);
         var dsc=0;
         dsc=parseFloat($joomla('input[name="amount"]').val());
         $joomla('#divTotalShippingCharges').html(dsc.toFixed(2));
         
         $joomla('input[name="hiddentxtShippingMethod"]').val($joomla('input[name="txtShippingMethod"]').val());
         $joomla('#divtxtShippingMethod').html($joomla('input[name="txtShippingMethod"]:checked').attr("data-id"));
         $joomla('input[name="hiddentxtSpecialIn"]').val($joomla('textarea[name="txtSpecialIn"]').val());
         $joomla('#divtxtSpecialIn').html($joomla('textarea[name="txtSpecialIn"]').val());
         $joomla('#tabs2').hide();
         $joomla('#tabs3').show();
         $joomla(".paymentopt").hide();
         $joomla('input[name="cc"]').prop("checked", false);
         $joomla('.dvPaymentInformation').hide();
         $joomla('input[name="cardnumberStr"]').val('');
         $joomla('input[name="txtccnumberStr"]').val('');
         $joomla('input[name="txtNameonCardStr"]').val('');
         $joomla('select[name="MonthDropDownListStr"]').val('');
         $joomla('select[name="YearDropDownListStr"]').val('');  
         var user="<?php echo $user;?>";
         $joomla('input[name="item_name"]').val($joomla('input[name="txtShippingMethod"]').val()+":"+ids+":"+spi+":"+$joomla('textarea[name="txtSpecialIn"]').val()+":"+user);
         createCookie("item_name", $joomla('input[name="item_name"]').val());
         $joomla('input[name="item_number"]').val(qyt);
         $joomla('input[name="amount"]').val(totals);
         
     }else{
         alert("Please enter special instructions");
     }
     //console.log(validation2.form());
    });
    
    $joomla('#tabs3').on('click','.btn-back',function(){
     $joomla('#tabs2').show();
     $joomla('#tabs3').hide();
    });
    
    $joomla(document).on('click','#tabs1 a.btn-danger',function(e){
       e.preventDefault();
        var res=$joomla(this).data('id');
        var reshtml=$joomla(this);
        var del=confirm('<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_CONFIRM_DELETE');  ?>');
        if(del==true){
            $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&shopperdeletetype="+res +"&shopperdeleteflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "shopperdeletetype": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
                  $joomla(".page_loader").show();
               },success: function(data){
                   $joomla(".page_loader").hide();
                    if(data==1){
                        console.log(reshtml.closest('tr').html());
                        reshtml.closest('tr').hide();
                    }
                }
    		});
        }	
        return false;

    });
    $joomla('input[name="txtQuantity[]"]').live('blur',function(){
        $joomla(this).closest('.rows').find('input[name="txtTprice[]"]').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla(this).closest('.rows').find('input[name="txtDvalue[]"]').val()));
        var final = total.toFixed(2);
        if(final>0){
            $joomla(this).closest('.rows').find('input[name="txtTprice[]"]').val(final);
            
    //         $joomla.ajax({
    // 			url: 'http://api.exchangeratesapi.io/v1/latest?access_key=a946255bf6564aa07b1de9e97e6fe519&base=USD&symbols=EUR',
    //             crossDomain: true,
    //             dataType: "json",
    // 			type: "get",
    // 			beforeSend: function() {
    //               $joomla(".page_loader").show();
    //           },success: function(data){
    //               $joomla(".page_loader").hide();
    //                 var currentRate = data.rates.EUR;
    //                 alert(data);
    //           }
    //         });
        }
    });    
    $joomla('input[name="txtDvalue[]"]').live('blur',function(){
        $joomla(this).closest('.rows').find('input[name="txtTprice[]"]').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla(this).closest('.rows').find('input[name="txtQuantity[]"]').val()));
          var final = total.toFixed(2);
          
        if(final>0){
            var finalPrice = $joomla(this).closest('.rows').find('input[name="txtTprice[]"]').val(final);
        }
    });

    
     $joomla("input[name='btnReset']").click(function(e){
       var alt=confirm("Please confirm to Reset the form");
       if(alt==true)    
       $joomla("#userprofileFormOne").trigger("reset");    
    }); 
    
    
   
    $joomla("input[name='cardnumberStr']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $joomla("input[name='txtccnumberStr']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    
    // multiple rows
    
    $joomla("#tabs1").on('click','input[name="addrow"]',function(){
        
        var i=0;
        $joomla('input[name="addrow"]').each(function(){
                i++;
        });
        
        if($joomla("form[name='userprofileFormOne']").valid() == true){
            cnt = i+1;
        }
       
    if($joomla("#userprofileFormOne").valid()){
        
      var itemNameId=$joomla(this).closest('.rows').find('input[name="txtItemName[]"]').attr('id');
      var newItemNameId=itemNameId+1;
      
      var itemModalId=$joomla(this).closest('.rows').find('input[name="txtItemModel[]"]').attr('id');
      var newItemModalId=itemModalId+1; 
      
      var itemRefId=$joomla(this).closest('.rows').find('input[name="txtItemRefference[]"]').attr('id');
      var newItemRefId=itemRefId+1;
      
      var itemColorId=$joomla(this).closest('.rows').find('input[name="txtColor[]"]').attr('id');
      var newItemColorId=itemColorId+1;
      
      var itemSizeId=$joomla(this).closest('.rows').find('input[name="txtSize[]"]').attr('id');
      var newItemSizeId=itemSizeId+1;
      
      var itemUrlId=$joomla(this).closest('.rows').find('input[name="txtItemurl[]"]').attr('id');
      var newItemUrlId=itemUrlId+1;
      
      var itemDesId=$joomla(this).closest('.rows').find('input[name="txtItemdescription[]"]').attr('id');
      var newItemDesId=itemDesId+1;
      
      var itemQntId=$joomla(this).closest('.rows').find('input[name="txtQuantity[]"]').attr('id');
      var newItemQntId=itemQntId+1;
      
      var itemPriceId=$joomla(this).closest('.rows').find('input[name="txtDvalue[]"]').attr('id');
      var newItemPriceId=itemPriceId+1;
      
      var itemInvoiceName=$joomla(this).closest('.rows').find('input[type="file"]').attr('name');
      var newItemInvoiceName=itemInvoiceName.replace('_'+i,'_'+(i+1));
      
      var itemInvoiceId=$joomla(this).closest('.rows').find('input[type="file"]').attr('id');
      var newItemInvoiceId=itemInvoiceId.replace('_'+i,'_'+(i+1));
      
      
       var rowData = $joomla(this).closest('.rows').html().replace('id="'+itemNameId+'"','id="'+newItemNameId+'"').replace('id="'+itemModalId+'"','id="'+newItemModalId+'"').replace('id="'+itemRefId+'"','id="'+newItemRefId+'"').replace('id="'+itemColorId+'"','id="'+newItemColorId+'"').replace('id="'+itemSizeId+'"','id="'+newItemSizeId+'"').replace('id="'+itemUrlId+'"','id="'+newItemUrlId+'"').replace('id="'+itemDesId+'"','id="'+newItemDesId+'"').replace('id="'+itemQntId+'"','id="'+newItemQntId+'"').replace('id="'+itemPriceId+'"','id="'+newItemPriceId+'"').replace('name="'+itemInvoiceName+'"','name="'+newItemInvoiceName+'"').replace('id="'+itemInvoiceId+'"','id="'+newItemInvoiceId+'"');
       $joomla('<div class="row rows row-mob">'+rowData+'</div>').insertAfter( $joomla(this).closest('.row') );
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
    });
    
});
</script>

<div class="container">
  <div class="main_panel persnl_panel">
    <div id="tabs1">
      <div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_TITLE');?></div>
      <div class="panel-body">
        <form name="userprofileFormOne" id="userprofileFormOne" method="post" action="" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-6">
              <h4 class="sub_title"><strong><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_SUB_TITLE');?></strong></h4>
            </div>
          </div>
          <div class="row">
            <?php if($elem['MerchantName'][1] == "ACT"){  ?>  
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <!--<label><?php //echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MERCHANT_NAME');?> <span class="error">*</span> </label>-->
                <label><?php echo $assArr['merchants_Name'];?><?php if($elem['MerchantName'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control"  name="txtMerchantName" value="<?php if($elem['MerchantName'][3]){  echo $elem['MerchantName'][4];  } ?>"    maxlength="32" <?php if($elem['MerchantName'][2]){ echo "required"; }  ?> >
              </div>
            </div>
             <?php }if($elem['MerchantWebsite'][1] == "ACT"){  ?>  
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr["merchants_website"];?><?php if($elem['MerchantWebsite'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" name="txtMerchantWebsite" value="<?php if($elem['MerchantWebsite'][3]){  echo $elem['MerchantWebsite'][4];  } ?>" maxlength="250" <?php if($elem['MerchantWebsite'][2]){ echo "required"; }  ?> >
              </div>
            </div>
            <?php } ?>
          </div>
          <div class="row rows">
            <?php  if($elem['ArticleName'][1] == "ACT"){  ?>  
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['article_name'];?><?php if($elem['ArticleName'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control"  name="txtItemName[]" id="1" value="<?php if($elem['ArticleName'][3]){  echo $elem['ArticleName'][4];  } ?>" maxlength="32"  <?php if($elem['ArticleName'][2]){ echo "required"; }  ?> >
              </div>
            </div>
            <?php   } if($elem['ItemModel'][1] == "ACT"){  ?> 
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['item_Model'];?><?php if($elem['ItemModel'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control"  name="txtItemModel[]" id="2" maxlength="32" value="<?php if($elem['ItemModel'][3]){  echo $elem['ItemModel'][4];  } ?>" <?php if($elem['ItemModel'][2]){ echo "required"; }  ?> >
              </div>
            </div>
            <?php }if($elem['ItemReference'][1] == "ACT"){  ?>  
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['item_Reference (SKU)'];?><?php if($elem['ItemReference'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" id="3" name="txtItemRefference[]" value="<?php if($elem['ItemReference'][3]){  echo $elem['ItemReference'][4];  } ?>" maxlength="32" <?php if($elem['ItemReference'][2]){ echo "required"; }  ?> >
              </div>
            </div>
            <?php } ?>
          <div class="clearfix"></div>
         
            <?php if($elem['Color'][1] == "ACT"){  ?>   
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['color'];?><?php if($elem['Color'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" id="4" name="txtColor[]" value="<?php if($elem['Color'][3]){  echo $elem['Color'][4];  } ?>"  <?php if($elem['Color'][2]){ echo "required"; }  ?>  maxlength="20">
              </div>
            </div>
            <?php }if($elem['Size'][1] == "ACT"){  ?>  
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['size'];?><?php if($elem['Size'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" id="5" name="txtSize[]" maxlength="20" value="<?php if($elem['Size'][3]){  echo $elem['Size'][4];  } ?>"  <?php if($elem['Size'][2]){ echo "required"; }  ?> >
              </div>
            </div>
            <?php }if($elem['ArticleURL'][1] == "ACT"){  ?>  
             <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['article_URL'];?><?php if($elem['ArticleURL'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" id="6" name="txtItemurl[]" value="<?php if($elem['ArticleURL'][3]){  echo $elem['ArticleURL'][4];  } ?>" maxlength="250" <?php if($elem['ArticleURL'][2]){ echo "required"; }  ?>>
              </div>
            </div>
            <?php }if($elem['ItemDescription'][1] == "ACT"){  ?>  
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['item_Description'];?><?php if($elem['ItemDescription'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" id="7" name="txtItemdescription[]" value="<?php if($elem['ItemDescription'][3]){  echo $elem['ItemDescription'][4];  } ?>" maxlength="250" <?php if($elem['ItemDescription'][2]){ echo "required"; }  ?> >
              </div>
            </div>
           <?php } ?> 
          <div class="clearfix"></div>
          
            <?php if($elem['Quantity'][1] == "ACT"){  ?>  
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['quantity'];?><?php if($elem['Quantity'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" id="8" name="txtQuantity[]" value="<?php if($elem['Quantity'][3]){  echo $elem['Quantity'][4];  } ?>" maxlength="3" <?php if($elem['Quantity'][2]){ echo "required"; }  ?> >
              </div>
            </div>
            <?php }if($elem['ItemPrice'][1] == "ACT"){  ?>
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label> <?php echo $assArr['item_Price_(USD)'];?><?php if($elem['item_Price_(USD)'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" id="9" name="txtDvalue[]" value="<?php if($elem['ItemPrice'][3]){  echo $elem['ItemPrice'][4];  } ?>" maxlength="7" <?php if($elem['ItemPrice'][2]){ echo "required"; }  ?> >
              </div>
            </div>
            <?php }if($elem['DeclaredValue'][1] == "ACT"){  ?>
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label> <?php echo $assArr['Declared Value (USD)'];?><?php if($elem['DeclaredValue'][2]){ ?><span class="error">*</span><?php } ?></label>
                <input type="text" class="form-control" id="10"  name="txtTprice[]" value="<?php if($elem['DeclaredValue'][3]){  echo $elem['DeclaredValue'][4];  } ?>" <?php if($elem['DeclaredValue'][2]){ echo "required"; }  ?> readonly>
              </div>
            </div>
            <?php } ?>
            
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['add_Invoice '];?></label>
                <input type="file" class="form-control" id="addinvoiceTxtMul_1" multiple  name="addinvoiceTxtMul_1[]" value="" >
              </div>
            </div>
            
            <!-- currency conversion -->
            
            <!--<div class="col-sm-12 col-md-3">-->
            <!--  <div class="form-group">-->
            <!--    <label> <?php //echo Jtext::_('Declared Value (EUROS)');?> :</label>-->
            <!--    <input type="text" class="form-control"  name="txtTpriceConversion" readonly>-->
            <!--  </div>-->
            <!--</div>-->
            
            <div class="clearfix"></div>
            
            <div class="col-sm-12 col-md-2">
              <div class="form-group btn-grp1">
                <input type="button" name="addrow" value="+" class="btn btn-primary btn-add"> 
                <input type="button" name="deleterow" value="x" class="btn btn-danger btn-rem">
              </div>
          </div>
         
            
          </div>
          
         
          
          <div class="row">
            <div class="col-sm-12 text-left">
              <div class="form-group">
                <input type="button" value="<?php echo $assArr['reset'];?>" name="btnReset" class="btn btn-danger">    
                <input type="submit" value="<?php echo $assArr['submit'];?>" class="btn btn-primary">
              </div>
            </div>
          </div>
          <input type="hidden" name="task" value="user.addshopperassist">
          <input type="hidden" name="id" value="0" />
          <input type="hidden" name="user" value="<?php echo $user;?>" />
        </form>
        
        
        <?php  
            
            Controlbox::getShopperassistListCsv($user);
            
        ?>
        
         <div class="row">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                        <h3 class=""><strong><?php echo Jtext::_('Inventory purchases');?></strong></h3>
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <a style="color:white;" href="<?php echo JURI::base(); ?>/csvdata/shopperassist_list.csv" class="btn btn-primary csvDownload export-csv"><?php echo $assArr['eXPORT_CSV'];?></a>
                    </div>
                </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered theme_table" id="N_table">
              <thead>
                <tr>
                  <th><?php echo $assArr['select'];?></th>
                  <th><?php echo $assArr['merchants_Name'];?></th>
                  <th><?php echo $assArr['item_name'];?></th>
                  <th><?php echo $assArr['quantity'];?></th>
                  <th><?php echo $assArr['item_Price_(USD)'];?></th>
                  <th><?php echo $assArr['Declared Value (USD)'];?></th>
                  <th><?php echo $assArr['action'];?></th>
                </tr>
              </thead>
              <tbody>
                <?php
    $ordersView= UserprofileHelpersUserprofile::getShopperassistList($user);
    foreach($ordersView as $rg){
        
        $imagePathCnt = count(explode("/",$rg->ItemImage));
        if($imagePathCnt >4){
            $image = str_replace(":","#",$rg->ItemImage);
            
        }
        $image1PathCnt = count(explode("/",$rg->ItemImage1));
        if($image1PathCnt >4){
            $image1 = str_replace(":","#",$rg->ItemImage1);
        }
        $image2PathCnt = count(explode("/",$rg->ItemImage2));
        if($image2PathCnt >4){
            $image2 = str_replace(":","#",$rg->ItemImage2);
        }
        $image3PathCnt = count(explode("/",$rg->ItemImage3));
        if($image3PathCnt >4){
            $image3 = str_replace(":","#",$rg->ItemImage3);
        }
        
        
      echo '<tr><td><input type="radio" name="txtitemId" value="'.$rg->Id.':'.$rg->ItemName.':'.$rg->ItemQuantity.':'.$rg->ItemPrice.':'.$rg->TotalPrice.':'.$image.':'.$image1.':'.$image2.':'.$image3.'"></td><td>'.$rg->SupplierId.'</td><td>'.$rg->ItemName.'</td><td>'.$rg->ItemQuantity.'</td><td>'.$rg->ItemPrice.'</td><td>'.$rg->TotalPrice.'</td><td class="action_btns"><a class="btn btn-danger" data-id='.$rg->Id.'><i class="fa fa-trash"></i></a></td></tr>';
    }
?>
              </tbody>
            </table>
            <div class="row">
              <div class="col-sm-12 text-center">
                <div class="form-group">
                  <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_PROCEED_BTN');?>" class="btn btn-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#order_edit">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="order_edit" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
          <div class="text-right">
          <input type="button" data-dismiss="modal" class="goback" value="X" >
          </div>
            <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_TITLE');?></strong></h4>
          </div>
          <div class="modal-body">
            <form name="userprofileFormTwo" id="userprofileFormTwo" method="post" action="" enctype="multipart/form-data">
              <div id="tabs2" style="display:none">
                <div class="row">
                  <div class="col-md-12">
                    <p><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_DESC');?> </p>
                    <div id="hiddenTableValues" style="display:none"></div>
                  </div>
                </div>
                
                <?php $serviceList = Controlbox::getservicetypeshop(); ?>
                
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_SHIPPING_METHOD');?> : <span class="error">*</span> </label>
                      <div class="rdo_rd1">
                        <?php
                        
                        foreach($serviceList as $service){
                            echo '<input type="radio" id="shippingMethod" name="txtShippingMethod" data-id="'.$service->desc_vals.'" value="'.$service->id_values.'">';
                            echo '<label>'.$service->desc_vals.'</label>';
                        }
                        
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_SPECIAL_INSTRUCTIONS');?> : <span class="error">*</span> </label>
                      <textarea class="form-control" name="txtSpecialIn"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 text-center">
                    <div class="form-group">
                      <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_CLOSE');?>" data-dismiss="modal" class="btn btn-danger goback">
                      <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_CONTINUE');?>" class="btn btn-primary">
                    </div>
                  </div>
                </div>
              </div>
              <div id="tabs3" style="display:none">
              <input type="hidden" name="hiddentxtTaxes">
              <input type="hidden" name="hiddentxtShippCharges">
              <input type="hidden" name="hiddentxtShippingMethod">
              <input type="hidden" name="hiddentxtSpecialIn">
              <input type="hidden" name="hiddenItemIds">
              <input type="hidden" name="hiddenItemQuantity">
              <input type="hidden" name="hiddenItemSupplierId">
              <input type="hidden" name="paypalinvoice">
              
             
             
             <input type='hidden' name='business' value='sb-jftsj5136420@business.example.com'> 
             <input type='hidden'   name='item_name'>
                <input type='hidden' name='item_number'> 
                <input type='hidden' name='amount'>
                
                <input type='hidden' name='no_shipping' value='1'> 
                <input type='hidden' name='currency_code' value='USD'>
                <input type='hidden' name='notify_url' value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=notify'>
            <input type='hidden' name='cancel_return'
                value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=shopperassist'>
            <input type='hidden' name='return'
                value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=response&invoiceType=shopperassist&invc=<?php echo $invoiceCountRes->Data; ?>&pay=<?php echo base64_encode(date("m/d/0Y"));?>'>
            <input type="hidden" name="cmd" value="_xclick">  
              
              

              <div class="row">
                <div class="col-md-12">
                  <div class="finish-shipping">
                    <p><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_SHIPPING_SUMMARY');?> </p>
                    <table class="table table-bordered theme_table" id="k_table">
                      <thead>
                        <tr>
                          <th><?php echo $assArr['item_name'];?></th>
                          <th><?php echo $assArr['quantity'];?></th>
                          <th><?php echo $assArr['item_price'];?></th>
                          <th><?php echo $assArr['Declared Value (USD)'];?></th>
                          <th><?php echo "Invoice"; ?></th>
                        </tr>
                      </thead>
                    </table>
                    <table width="100%" class="table table-bordered theme_table shipping-costtbl">
                      <!--
                      <tr>
                        <td colspan="2"><label>Taxes and Shipping Charges</label></td>
                      </tr>
                      
                      <tr>
                        <td><label>Local tax (USD)</label></td>
                        <td class="txt-right"><div id="divtxtTaxes"></div></td>
                      </tr>
                      <tr>
                        <td><label>Local shipping charges (USD)</label></td>
                        <td class="txt-right"><div id="divtxtShippCharges"></div></td>
                      </tr>
                      
                      -->
                      <tr>
                        <td><label><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_TOTAL_SHIPMENT_CHARGES');?></label></td>
                        <td class="txt-right"><div id="divTotalShippingCharges"></div></td>
                      </tr>
                      <tr>
                        <td><label><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_SHIPPING_METHOD');?></label></td>
                        <td class="txt-right"><div id="divtxtShippingMethod"></div></td>
                      </tr>
                      <tr>
                        <td><label><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_SPECIAL_INSTRUCTIONS');?></label></td>
                        <td class="txt-right"><div id="divtxtSpecialIn"></div></td>
                      </tr>
                    </table>
                    
                    <div class="payment-method">
                      <h4><?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_PAYMENT_METHOD');?></h4>
                    
                      
                      <?php 
                  
                      foreach($clients as $client){ 
                            if(strtolower($client['Domain']) == strtolower($domainName) ){   
                                $Payment_gateway_dynamic_enable=$client['Payment_gateway_dynamic_enable']; 
                            }
                        }
                        
                    ?>
                    <div class="rdo_cust">
                    <div class="rdo_rd1"> 
                    <div class="paymentmethodsDiv">
                                
                                <?php 
                                    $paymentmethodsStr = ''; 
                                    $paymentmethods = Controlbox::getpaymentmethods();
                                    foreach($paymentmethods as $method){
                                        if($method->desc_vals){
                                            if($method->id_values == "COD"){
                                                $paymentmethodsStr .= '<input type="radio" name="cc" value="'.$method->id_values.'">';
                                                $paymentmethodsStr .='<label>'.$method->desc_vals.'</label>';
                                            }
                                        }
                                    }
                                    
                                    $paymentmethodsStr.=Controlbox::getpaymentgateways('PPD');
                                    echo $paymentmethodsStr;
                                ?>
                                
                               
                               
                    </div>
                    </div> 
                    </div> 
                    
      
      <div class="dvPaymentInformation col-md-6 col-sm-12 col-xs-12" style="display:none">
            <div class="heading">
                <h3 class="text-center">Confirm Purchase</h3>
            </div>
            <div class="payment">
                 <div class="form-group col-md-12 col-sm-12 col-xs-12" id="card-number-field">
                         <label for="cardNumber"><?php echo Jtext::_('COM_USERPROFILE_CARD_NUMBER');?></label>
                        <input type="text" class="form-control" id="cardNumber" name="cardnumberStr" minlength="15" maxlength="16" placeholder="4111111111111111">
                    </div>
                 <div class="clearfix"></div>
                   <div class="form-group" id="credit_cards">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/visa.jpg" id="visa">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/mastercard.jpg" id="mastercard">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/amex.jpg" id="amex">
                    </div>
                  
                    <div class="form-group col-md-12 col-sm-12" id="expiration-date">
                        <div class="row">
                        <label class="col-md-12 col-sm-12 col-xs-12">Expiration Date</label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        <select class="form-control" name="MonthDropDownListStr">
                            <option value="">Select Month</option>
                            <option value="01">January</option>
                            <option value="02">February </option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        <select class="form-control" name="YearDropDownListStr">
                            <option value="">Select Year</option>
                           <option value="2022" selected> 2022</option>
                            <option value="2023"> 2023</option>
                            <option value="2024"> 2024</option>
                            <option value="2025"> 2025</option>
                            <option value="2026"> 2026</option>
                            <option value="2027"> 2027</option>
                            <option value="2028"> 2028</option>
                            <option value="2029"> 2029</option>
                            <option value="2030"> 2030</option>
                            
                        </select>
                        </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                       <div class="form-group CVV col-md-12 col-sm-12 col-xs-12">
                        <label for="cvv">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="txtccnumberStr" style="width:20%">
                    </div>
                    <div class="clearfix"></div>
                   
                    <div class="col-md-12 col-sm-12 col-xs-12 error paygaterrormsg"></div>
            </div>
        </div>
                       <div class="row">
                          <div class="col-md-12 text-center final_payment" style="height:58px">
                            <input type="hidden" name="task" value="user.payshopperassist">
                            <input type="hidden" name="id" id="id" />
                            <input type="hidden" name="user" value="<?php echo $user;?>" />
                            <input type="hidden" name="page" value="shopperassist" />
                            <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_BACK');?>" class="btn btn-back">
                            <input type="submit" onclick="return submitpayment(event);" value="<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_SHIP');?>" class="btn btn-primary">
                           
                            
                            <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_MODAL_CLOSE');?>" data-dismiss="modal" class="btn btn-danger goback">
                          </div>
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
  </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/js/jquery.payform.min.js" charset="utf-8"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/js/script.js"></script>
<script>
$joomla(document).keyup('#cvv,#cardNumber',function() {
        $joomla("#cvv-error").html("");
        cardNum = $joomla('#cardNumber').val();
        cardNumLen = cardNum.length;
        if(cardNumLen == 16){
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }else if(cardNumLen == 15){
            $joomla("#cvv").attr("maxlength",4);
            $joomla("#cvv").attr("minlength",4);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_FOUR_DIGITS_ERROR'); ?>");
        }else{
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }
    });
    $joomla("#cvv,#cardNumber").on('blur',function() {
       
        $joomla("#cvv-error").html("");
        cardNum = $joomla('#cardNumber').val();
        cardNumLen = cardNum.length;
        if(cardNumLen == 16){
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }else if(cardNumLen == 15){
            $joomla("#cvv").attr("maxlength",4);
            $joomla("#cvv").attr("minlength",4);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_FOUR_DIGITS_ERROR'); ?>");
        }else{
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }
    });
    
    
    function submitpayment(event) {
       
        var AccountType = "<?php echo $AccountType;  ?>";
        var ApiUrl = "<?php echo $ApiUrl; ?>";
    
       if($joomla('input[name="cc"]').is(":visible") == true){
           if($joomla('input[name="cc"]').is(":checked") == false){
               alert("Please check any one payment option");
               return false;
           }
       }
            
          if($joomla('input[name="cc"]:checked').val() == "Paypal"){
             
             $joomla(".page_loader").show();
             $joomla('#userprofileFormTwo').attr("action",ApiUrl);
             $joomla('#userprofileFormTwo').submit();
             return true;
             
          }else if($joomla('input[name="cc"]:checked').val() == "Stripe" || $joomla('input[name="cc"]:checked').val() == "authorize.net"){
          
             $joomla("form[name='userprofileFormTwo']").validate({
                rules:  {
                        cardnumberStr: {
                        required: true,
                        minlength:15
                        },
                        MonthDropDownListStr: {
                        required: true
                        },
                        YearDropDownListStr: {
                        required: true
                        },
                        txtccnumberStr: {
                        required: true
                        },
                    },
                messages: {
                            cardnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_NUMBER_ERROR');?>"},
                            MonthDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_MONTH_ERROR');?>"},
                            YearDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_YEAR_ERROR');?>"},
                            txtccnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_CVV_ERROR');?>"}
                         },
                submitHandler: function(form) {
                  
                    $joomla(".page_loader").show();
                    if($joomla('input[name="cc"]:checked').val() == "Stripe"){
                    
                   ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&user="+$joomla('input[name="user"]').val()+"&txtSpecialIn="+$joomla('textarea[name="txtSpecialIn"]').val();
                  
                    $joomla.ajax({
                   			url: ajaxurl,
                   			data: { "paymentgatewayshopflag":1,"amount":$joomla('input[name="amount"]').val(),"hiddentxtTaxes":$joomla('input[name="hiddentxtTaxes"]').val(),"hiddentxtShippCharges":$joomla('input[name="hiddentxtShippCharges"]').val(),"hiddenItemIds":$joomla('input[name="hiddenItemIds"]').val(),"hiddenItemQuantity":$joomla('input[name="hiddenItemQuantity"]').val(),"hiddenItemSupplierId":$joomla('input[name="hiddenItemSupplierId"]').val(),"paymentmethod":"PPD","paymentgateway":"Stripe","invoiceStr":$joomla('input[name="paypalinvoice"]').val()},
                   			dataType:"text",
                   			type: "get",
                            beforeSend: function() {
                               
                            },
                            success: function(res){
                                response = res.split(":");
                                if(response[0] == 1){
                                        
                                        window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&res="+response[1];
                                }else{
                                    $joomla(".page_loader").hide();
                                    $joomla(".paygaterrormsg").html(res);
                                }
                            }
                       });
                    }else if($joomla('input[name="cc"]:checked').val() == "authorize.net"){
                     
                        $joomla('#userprofileFormTwo').submit();
                        return true;
                        
                    }
                   
                }
            });
            
              if(!$joomla("form[name='userprofileFormTwo']").valid()){
                   $joomla(".page_loader").hide();
              }
            
          }else{
              $joomla(".page_loader").show();
              $joomla('#userprofileFormTwo').submit();
             
          }
       }
       
</script>
