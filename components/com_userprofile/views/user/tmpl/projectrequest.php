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
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';

if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}

$UserView= UserprofileHelpersUserprofile::getUserprofileDetails($user);
$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$CompanyId = $domainDetails[0]->CompanyId;
$CompanyName = $domainDetails[0]->CompanyName;
$DomainEmail = $domainDetails[0]->PrimaryEmail;


	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];


include 'dasboard_navigation.php';


?>

<script type="text/javascript" src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">




<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {

<!-- Accordion Script Start -->
	$joomla('.accordion').click(function(){
	  $joomla(this).parent('.acrdin-blk').find('.acrdin-bdy').toggle();
	});
<!-- Accordion Script End -->
    
 	var validfirst=$joomla("form[name='userprofileFormOne']").validate({
			
			// Specify validation rules
			rules: {
			  // The key name on the left side is the name attribute
			  // of an input field. Validation rules are defined
			  // on the right side
			  txtAccountnumber:{
					required: true
			  },
			  txtAccountname:{
					required: true
			  },
			  txtInventory:{
					required: true
			  },
			  txtProjectname:{
				required: true
			  },
			  txtProductTitle:{
					required: true
			  },
			  "txtFnsku[]":{
					required: true
			  },
			  "txtFnskuquanity[]":{
					required: true
			  },
			  "txtUPC[]":{
					required: true
			  },
			  "txtService[]": {
                  required: true
              },
              dateTxt: {
                  required: true
              }
            },
			// Specify validation error messages
			messages: {
			   txtAccountnumber:{
				required: "<?php echo $assArr['Account_Number_error'];?>"
			  },
			   txtAccountname:{
				required: "<?php echo $assArr['Account Name_error'];?>"
			  },
			   txtInventory:{
				required: "Please enter Inventory"
			  },
			   txtProjectname:{
				required: "<?php echo $assArr['Project Name_error'];?>"
			  },
			   txtProductTitle:{
				required: "<?php echo $assArr['Product Name FNSKU Title_error'];?>"
			  },
			   "txtFnsku[]":{
				required: "Please enter FNSKU Number"
			  },
			   "txtFnskuquanity[]":{
				required: "Please enter FNSKU quantity "
			  },
			   "txtUPC[]":{
				required: "Please enter UPC "
			  },
			   "txtService[]":{
				required: "Please check Service"
			  },
			   dateTxt:{
				required: "<?php echo $assArr['Date Requested_error'];?>"
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
    $joomla("input[name='txtProjectname']").live('blur',function(e){
         $joomla('#prfdiv .btn-primary').attr("disabled", true);
         e.preventDefault();
         $joomla.ajax({
            url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&txtProjectname="+$joomla(this).val()+"&prexistflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
        	data: { "ds": $joomla(this).data('id') },
        	dataType:"text",
        	type: "get",
        	cache: false,
        	beforeSend: function() {
              //$joomla("#ord_edit .modal-body").html('');
              //$joomla("#ord_edit .modal-body").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
           },
           success: function (response) {
               var datas=response;
               
               if(datas==1){
                   $joomla('#prfdiv .btn-primary').attr("disabled", false);
                   $joomla('.projectNameError').html("");
               }else{
                    $joomla('.projectNameError').html("<label class='error' for='ProjectnameTxt' >Project Name is already exist</label>");
                    $joomla('#prfdiv .btn-primary').attr("disabled", true);
               }
            },
            error: function () {
                alert("some problem in saving data");
            }
         })  
    })
    
    // services based on shipment type
    
    $joomla('input[name=txtInventory]').on('click',function(){
        $joomla(".getcustservices").html('');
        var businesstype = $joomla(this).val();
        
         $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&user=<?php echo $user; ?>&shiptype=" + businesstype + "&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "getaddserflag": 1 },
    			dataType:"text",
    			type: "get",
    			beforeSend: function() {
               },success: function(response){
                  $joomla(".getcustservices").html(response);
               }
       });
       
       
    });
    
    // edit
    
     $joomla(document).on('click','input[name=InventoryTxt]',function(){
        $joomla("#getcustservicesedit").html('');
        var businesstype = $joomla(this).val();
        
         $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&user=<?php echo $user; ?>&shiptype=" + businesstype + "&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "getaddserflag": 1 },
    			dataType:"text",
    			type: "get",
    			beforeSend: function() {
               },success: function(response){
                  $joomla("#getcustservicesedit").html(response);
               }
       });
       
       
    });

 	var validpop=$joomla("form[name='userprofileFormSix']").validate({
			
			// Specify validation rules
			rules: {
			  // The key name on the left side is the name attribute
			  // of an input field. Validation rules are defined
			  // on the right side
			  InventoryTxt:{
					required: true
			  },
			  ProjectnameTxt:{
				required: true
			  },
			  productnameTxt:{
					required: true
			  },
			  OrderDateTxt:{
					required: true
			  },     
			  "FnskuTxt[]":{
					required: true
			  },
			  "FnskuquanityTxt[]":{
					required: true
			  },
			  "upcTxt[]":{
					required: true
			  },
			  "ServiceTxt[]": {
                  required: true
              }
              
            },
			// Specify validation error messages
			messages: {
			   InventoryTxt:{
				required: "Please select Inventory"
			  },
			   ProjectnameTxt:{
				required: "<?php echo $assArr['Project Name_error'];?>"
			  },
			   productnameTxt:{
				required: "<?php echo $assArr['Product Name FNSKU Title_error'];?>"
			  },
			  OrderDateTxt:{
				required: "<?php echo $assArr['order_date_error'];?>"
			  },
			   "FnskuTxt[]":{
				required: "<?php echo $assArr['Provide FNSKU ex. X00BT4N3V_error'];?>"
			  },
			   "FnskuquanityTxt[]":{
				required: "<?php echo $assArr[' quantity of product per FNSKU_error'];?> "
			  },
			   "upcTxt[]":{
				required: "Please enter UPC "
			  },
			   "txtService[]":{
				required: "Please check Service"
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
    $joomla("input[name='ProjectnameTxt']").live('blur',function(e){
      if($joomla("input[name='Projectnamehidden']").val()!=$joomla(this).val()){
         $joomla('#ord_edit .btn-primary').attr("disabled", true);
         e.preventDefault();
         $joomla.ajax({
            url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&ProjectnameTxt="+$joomla(this).val()+"&prexistflag=2&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
        	data: { "ds": $joomla(this).data('id') },
        	dataType:"text",
        	type: "get",
        	cache: false,
        	beforeSend: function() {
              //$joomla("#ord_edit .modal-body").html('');
              //$joomla("#ord_edit .modal-body").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
           },
           success: function (response) {
               var datas=response;
               
               if(datas==1){
                   $joomla('#ord_edit .btn-primary').attr("disabled", false);
                    $joomla('.editProjectNameError').html("");
                   
               }else{
                   
                   $joomla('.editProjectNameError').html("<label class='error' for='ProjectnameTxt' >Project Name is already exist</label>");
                    $joomla('#ord_edit .btn-primary').attr("disabled", true);
               }

            },
            error: function () {
                alert("some problem in saving data");
            }
         })  
      }         
    });
    
    
   $joomla('#proreqform').show();
   $joomla("#showdiv").click(function(){
      
        if($joomla( "#dateTxt" ).length){
        $joomla( "#dateTxt" ).datepicker({ maxDate: new Date });
        }
        
         $joomla('#prfdiv').show();
         $joomla('#proreqform').hide();
         $joomla('#showdiv1').show();
       
    });	
    
    $joomla(document).on('click','#showdiv1',function(){
        $joomla(this).hide();
        $joomla('#prfdiv').hide();
        $joomla('#proreqform').show();
        $joomla('#showdiv').show();
        
    });

    $joomla("input[name='txtFnskuquanity[]']").live("keyup",function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $joomla("input[name='txtUPC[]']").live("keyup",function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $joomla("input[name='txtSKU[]']").live("keyup",function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    

    $joomla("input[name='FnskuquanityTxt[]']").live("keyup",function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $joomla("input[name='upcTxt[]']").live("keyup",function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $joomla("input[name='skuTxt[]']").live("keyup",function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $joomla('.clear-all').click(function(){
        $joomla("#userprofileFormOne")[0].reset();
    });
    
    $joomla('#tabs1').on('click','input[name="addrow"]',function(e){
         $joomla(this).parent().next().html("");
        var input1 = $joomla(this).parent().prev().find('div:nth-child(1) input').val();
        var input2 = $joomla(this).parent().prev().find('div:nth-child(2) input').val();
        var input3 = $joomla(this).parent().prev().find('div:nth-child(3) input').val();
       
        
        if (input1 != "" && input2 !="" && input3!=""){
            
             var rp=$joomla(this).closest('.rows').find('div:nth-child(1) input').attr('id');
          var er=rp+1;
          
          var rp2=$joomla(this).closest('.rows').find('div:nth-child(2) input').attr('id');
          var er2=rp2+1;
          
          var rp3=$joomla(this).closest('.rows').find('div:nth-child(3) input').attr('id');
          var er3=rp3+1;
          
          var rp4=$joomla(this).closest('.rows').find('div:nth-child(4) input').attr('id');
          var er4=rp4+1;
          
          var sd=$joomla(this).closest('.rows').html().replace('id="'+rp+'"','id="'+er+'"').replace('id="'+rp2+'"','id="'+er2+'"').replace('id="'+rp3+'"','id="'+er3+'"').replace('id="'+rp4+'"','id="'+er4+'"');
          $joomla('<div class="rows">'+sd+'</div>').insertAfter( $joomla(this).closest('.rows') );
          $joomla('#tabs1 .rows').find('.buttons').html('<input class="btn btn-danger" type="button" name="deleterow" value="X">');
          $joomla('#tabs1 .rows:last').find('.buttons').html('<input type="button" name="addrow" value="+" class="btn btn-primary">&nbsp;&nbsp;<input type="button" name="deleterow" value="x" class="btn btn-danger">');
         
        
        }else{
            $joomla(this).parent().next().html("<sub>Please fill all the required fields</sub>");
        }
        
         
    });
    $joomla('#tabs1').on('click','input[name="deleterow"]',function(e){
        e.preventDefault();
        var lastone=$joomla('#tabs1 .rows').html();
        if($joomla('#tabs1 .rows').length==1){
            alert('Minimum One Row Required');
            return false;
        }else{
            $joomla(this).closest('.rows').remove();
            $joomla('#tabs1 .rows:last').find('.buttons').html('<input type="button" name="addrow" value="+" class="btn btn-primary">&nbsp;&nbsp;<input type="button" name="deleterow" value="x" class="btn btn-danger">');
        }

    });
    $joomla('.addrowtwo').live('click',function(e){
     
        $joomla(this).parent().next().html("");
        var input1 = $joomla(this).parent().prev().prev().find('div:nth-child(1) input').val();
        var input2 = $joomla(this).parent().prev().prev().find('div:nth-child(2) input').val();
        var input3 = $joomla(this).parent().prev().find('div:nth-child(1) input').val();
      

        
         if (input1 != "" && input2 !="" && input3!=""){ 
     
          var rp=$joomla(this).closest('.addrows').find('div:nth-child(1) input').attr('id');
          var er=rp+1;
          var rp2=$joomla(this).closest('.addrows').find('div:nth-child(2) input').attr('id');
          var er2=rp2+1;
          var rp3=$joomla(this).closest('.addrows').find('div:nth-child(3) input').attr('id');
          var er3=rp3+1;
          var rp4=$joomla(this).closest('.addrows').find('div:nth-child(4) input').attr('id');
          var er4=rp4+1;
          
          var sds=$joomla(this).closest('.addrows').html().replace('id="'+rp+'"','id="'+er+'"').replace('id="'+rp2+'"','id="'+er2+'"').replace('id="'+rp3+'"','id="'+er3+'"').replace('id="'+rp4+'"','id="'+er4+'"');
          $joomla('<div class="addrows">'+sds+'</div>').insertAfter( $joomla(this).closest('.addrows') );
          $joomla('#tab2 .addrows:last').find('.form-control').val('');
          $joomla(this).closest('.hidebuttons').html('<input type="button" name="deleterowtwo" value="x" class="btn btn-danger deleterowtwo">');
          
         }else{
            $joomla(this).parent().next().html("<div><span class='error'>Please fill all the required fields</span></div>");
        }
              
    });    
    $joomla('.deleterowtwo').live('click',function(e){
      var lastone=$joomla('.addrows').html();
      if($joomla('.addrows').length==1){
        alert('Minimum One Row Required');
        return false;
      }else{
  
        $joomla(this).closest('.addrows').remove();
        if($joomla(this).closest('.addrows').find('.addrowtwo').length>0){
         $joomla('.hidebuttons:last').html('<input type="button" name="addrowtwo" value="+" class="btn btn-primary addrowtwo">&nbsp;&nbsp;<input type="button" name="deleterowtwo" value="x" class="btn btn-danger deleterowtwo">');
        }
      }    
    });   
    
    //  Tab 3 
    $joomla('.addrowthree').live('click',function(e){
        var valid=0;
        $joomla('.dangers').remove();
        $joomla( $joomla('input[name="uploadFiles[]"]') ).each(function( index, element ) {
        
            if($joomla(this).val()==""){
                valid=2;
                $joomla(this).parents('.addrows1').append('<div class="col-md-12 col-sm-12 col-xs-12"><label class="dangers error">Please Upload Labels</label></div>');
            }
        })
        
        if(valid==0){
            var rp=$joomla(this).closest('.addrows1').find('div:nth-child(1) input').attr('id');
            var er=rp+1;
            var sds=$joomla(this).closest('.addrows1').html().replace('id="'+rp+'"','id="'+er+'"');
            
            $joomla('<div class="row addrows1 cls-addrwclr">'+sds+'</div>').insertAfter( $joomla(this).closest('.addrows1') );
            $joomla('#tab3 .addrows1:last').find('.form-control').val('');
            $joomla('#tab3 .addrows1:last').find('#fileList').html('');
            $joomla(this).closest('.hidebuttons3').html('<input type="button" name="deleterowthree" value="x" class="btn btn-danger deleterowthree">');
        
        }else{
            
        }
        
    });
    
     $joomla('.deleterowthree').live('click',function(e){
      var lastone=$joomla('.addrows1').html();
      if($joomla('.addrows1').length==1){
        alert('Minimum One Row Required');
        return false;
      }else{
  
        $joomla(this).closest('.addrows1').remove();
        if($joomla(this).closest('.addrows1').find('.addrowthree').length>0){
         $joomla('.hidebuttons3:last').html('<input type="button" name="addrowthree" value="+" class="btn btn-primary addrowthree">&nbsp;&nbsp;<input type="button" name="deleterowthree" value="x" class="btn btn-danger deleterowthree">');
        }
      }    
    }); 
    
    
    // Tab4
    $joomla('.addrowfour').live('click',function(e){
        
        var valid=0;
        $joomla('.dangers').remove();
        $joomla( $joomla('input[name="editUploadFiles[]"]') ).each(function( index, element ) {
        
            if($joomla(this).val()==""){
                valid=2;
                $joomla(this).parents('.addrows2').append('<span class="dangers error">Please Upload Labels</span>');
            }
        })
        if(valid==0){
            var rp=$joomla(this).closest('.addrows2').find('div:nth-child(1) input').attr('id');
            var er=rp+1;
            var sds=$joomla(this).closest('.addrows2').html().replace('id="'+rp+'"','id="'+er+'"');
            $joomla('<div class="row addrows2 cls-addrwclr">'+sds+'</div>').insertAfter( $joomla(this).closest('.addrows2') );
            $joomla('#tab4 .addrows2:last').find('.form-control').val('');
             $joomla('#tab4 .addrows2:last').find('#fileList_edit').html('');
            $joomla(this).closest('.hidebuttons4').html('<input type="button" name="deleterowfour" value="x" class="btn btn-danger deleterowfour">');
        }
    });
    
     $joomla('.deleterowfour').live('click',function(e){
      var lastone=$joomla('.addrows2').html();
      if($joomla('.addrows2').length==1){
        alert('Minimum One Row Required');
        return false;
      }else{
  
        $joomla(this).closest('.addrows2').remove();
        if($joomla(this).closest('.addrows2').find('.addrowfour').length>0){
         $joomla('.hidebuttons4:last').html('<input type="button" name="addrowfour" value="+" class="btn btn-primary addrowfour">&nbsp;&nbsp;<input type="button" name="deleterowfour" value="x" class="btn btn-danger deleterowfour">');
        }
      }    
    }); 

    var tmp='';
    tmp=$joomla("#ord_edit .modal-body").html();
     $joomla('#N_table').on('click','.editpid',function(e){
        e.preventDefault();
        var resnew=$joomla(this).data('id');
        var htmldata='';
        $joomla.ajax({
        	url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&user=<?php echo $user;?>&prfupdatetype="+resnew +"&prfupdateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
        	data: { "prfupdatetype2": $joomla(this).data('id') },
        	dataType:"html",
        	type: "get",
        	cache: false,
        	beforeSend: function() {
              $joomla("#ord_edit .modal-body").html('');
              $joomla("#ord_edit .modal-body").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
           },success: function(data){
             //console.log(data);
              $joomla("#ord_edit .modal-body").html(tmp); 
              var cospor=data;
              cospor=cospor.split(":");
              
              console.log(cospor);
              
              $joomla('input[name=txtItemId]').val(cospor[0]);
              $joomla('input[name=AccountNumberTxt]').val(cospor[1]);
              $joomla('input[name=AccountNameTxt]').val(cospor[2]);
              //$joomla('input[name=InventoryTxt]').filter(':radio').prop('checked',false);	
              $joomla.each($joomla("input[name=InventoryTxt]"), function(){
                 
                    if($joomla(this).val()==$joomla.trim(cospor[3])){
                       $joomla(this).attr('checked','checked');
                    }
              });
              
              
              // dynamic cust add srvices
              
              $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&user=<?php echo $user; ?>&shiptype=" + cospor[3] + "&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "getaddsereditflag": 1 },
    			dataType:"text",
    			type: "get",
    			beforeSend: function() {
               },success: function(response){
                  console.log(response);
                  $joomla("#getcustservicesedit").html(response);
                            var strser = cospor[7];
                            var str_arrayserv = strser.split(',');
                          for(var i = 0; i < str_arrayserv.length; i++) {
                              var ser=str_arrayserv[i];
                              $joomla("#getcustservicesedit input[type='checkbox']").each(function(){
                                 var addservstr = $joomla(this).val();
                                    if(addservstr==ser){
                                       $joomla(this).prop('checked',true );
                                    }
                              });
                          }      
               }
            });
              
              $joomla('input[name=Projectnamehidden]').val(cospor[4]);
              $joomla('input[name=ProjectnameTxt]').val(cospor[4]);
              $joomla('input[name=productnameTxt]').val(cospor[11]);
              $joomla('input[name=OrderDateTxt]').val(cospor[8]);
              
              $joomla('input[name=AccountNumberTxt]').attr('readonly',true);
              $joomla('input[name=AccountNameTxt]').attr('readonly',true);
              //$joomla('input[name="OrderDateTxt"]').datepicker("destroy");
              if($joomla( 'input[name="OrderDateTxt"]' ))
                $joomla('input[name="OrderDateTxt"]').removeClass('hasDatepicker').datepicker(); 
              var str = cospor[5];
              var str_array = str.split(',');
              var strtwo = cospor[6];
              var str_arraytwo = strtwo.split(',');
              var strthree = cospor[9];
              var str_arraythree = strthree.split(',');
              var strfour = cospor[10];
              var str_arrayfour = strfour.split(',');
              var strfive = cospor[12];
              var str_arrayfive = strfive.split(',');
              for(var i = 0; i < str_array.length; i++) {
               // Trim the excess whitespace.
               str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
               str_arraytwo[i] = str_arraytwo[i].replace(/^\s*/, "").replace(/\s*$/, "");
               str_arraythree[i] = str_arraythree[i].replace(/^\s*/, "").replace(/\s*$/, "");
               str_arrayfour[i] = str_arrayfour[i].replace(/^\s*/, "").replace(/\s*$/, "");
               str_arrayfive[i] = str_arrayfive[i].replace(/^\s*/, "").replace(/\s*$/, "");
               if(str_array[i]){
                   htmldata+='<div class="addrows"><input type="hidden" name="idkTxt[]" value="'+str_arrayfive[i]+'" ><div class="col-md-12 col-sm-12 cls-addrowbg cls-addrwclr"><div class="row"><div class="col-sm-12 col-md-6"><div class="form-group"><label>Provide FNSKU ex. X00BT4N3V<span class="error">*</span></label><input type="text" class="form-control" name="FnskuTxt[]" maxlength="50" id="11'+i+'" value="'+str_array[i]+'" data-id="'+str_array[i]+'" ></div></div><div class="col-sm-12 col-md-6"><div class="form-group"><label>What is the quantity of product per FNSKU? <span class="error">*</span></label><input type="text" class="form-control FnskuquanityTxt" maxlength="5"  name="FnskuquanityTxt[]" id="12'+i+'" value="'+str_arraytwo[i]+'"></div></div></div><div class="row"><div class="col-sm-12 col-md-6"><div class="form-group"><label>UPC<span class="error">*</span></label><input type="text" class="form-control upcTxt" maxlength="20" name="upcTxt[]"  id="13'+i+'"  value="'+str_arraythree[i]+'"></div></div><div class="col-sm-12 col-md-6"><div class="form-group"><label>SKU </label><input type="text" maxlength="20" class="form-control skuTxt"  name="skuTxt[]" id="14'+i+'"  value="'+str_arrayfour[i]+'"></div></div></div><div class="col-md-12 text-right hidebuttons cls-add-ico">&nbsp;&nbsp;<input type="button" name="deleterowtwo" value="x" class="btn btn-danger deleterowtwo"></div><div class="addRowsError col-sm-12 col-xs-12"></div></div></div>';
               }              
                  
              }
              var strser = cospor[7];
              var str_arrayserv = strser.split(',');
              for(var i = 0; i < strser.length; i++) {
                  var ser=str_arrayserv[i];
                  $joomla.each($joomla("input[name='ServiceTxt[]']"), function(){
                        if($joomla(this).val()==ser){
                           $joomla(this).prop('checked',true );
                        }
                  });
              }                 
              console.log("labels:"+cospor[13]);
              var strlabe = cospor[13];
              
              
              var str_arraylab = strlabe.split(',');
              var labd='';
              for(var i = 0; i < str_arraylab.length; i++) {
                  var urc=str_arraylab[i];
                  
                    urc_str1=urc.replace("@",":");
                    urc_str2=urc_str1.replace("@",":");
                    
                    urc=urc_str2.split(":");
                    
                    //console.log(urc);
                    
                    //urc=urc_str2.split("@");
                    
                    arr_len = urc.length;
                    
                    if(arr_len == 3){
                      
                      fin_urc = urc[1] + ":" + urc[2];
                      
                    }else{
                      
                      fin_urc = urc[1];
                    }
                  
                  
                  if(urc[1]){
                      finalurl = urc[1].split("/");
                      if(finalurl[3] == "media"){
                          finalurl[2] = "<?php echo JURI::base();  ?>"; // window.location.host;
                          fin_urc = finalurl.join('/');
                          fin_urc = ltrim(fin_urc,"//");
                          
                      }else{
                          fin_urc = urc[0]+":"+urc[1];
                      }
                   
                   
                    labd+='<div id="deletelabel" class="cls-addrwclr cls-pos"><input type="hidden" name="existlabels[]" value="'+fin_urc+'"><a href="'+fin_urc+'" target="_blank"><img src="'+fin_urc+'" width="100px" height="100px"></a><div class="cls-add-ico"><input type="button" name="deletenewtwo" value="x" class="btn btn-danger deletenewtwo"></div></div>';
                  }    
              }
              
              $joomla('#labels').html(labd);

              $joomla('#tab2').html(htmldata);
              $joomla('.hidebuttons:last').html('<input type="button" name="addrowtwo" value="+" class="btn btn-primary addrowtwo">&nbsp;&nbsp;<input type="button" name="deleterowtwo" value="x" class="btn btn-danger deleterowtwo">');
            }
            
        });
     })
     
function rtrim(str, chr) {
  var rgxtrim = (!chr) ? new RegExp('\\s+$') : new RegExp(chr+'+$');
  return str.replace(rgxtrim, '');
}
function ltrim(str, chr) {
  var rgxtrim = (!chr) ? new RegExp('^\\s+') : new RegExp('^'+chr+'+');
  return str.replace(rgxtrim, '');
}


    $joomla('.deletenewtwo').live('click',function(){
        $joomla(this).closest('#deletelabel').remove();   
    })
    //delete inventry purchases order
    $joomla('#N_table').on('click','.deletepid',function(e){
        e.preventDefault();
        var projectName=$joomla(this).closest('tr').find('td:eq(3)').text();
        var res=$joomla(this).data('id');
        var reshtml=$joomla(this);
        var cf=confirm("Please confirm to delete");
        if(cf==true){
            $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&orderdeletetype="+res +"&projectdeleteflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "orderdeletetype": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
                  $joomla("#loading-image").show();
               },success: function(data){
                    if(data==1){
                        //console.log(reshtml.closest('tr').hide());
                        reshtml.closest('tr').hide();
                        $joomla('.'+projectName).hide();
                    }
                }
    		});
        }	
        return false;
    });    
     $joomla(".wrchild").click(function(){
       var htmse='';
       var rs=$joomla(this).closest('tr').find('td:eq(3)').text();
       rs = rs.replace(/\s/g,'');
       htmse+='<tr class="'+rs+'"><th></th><th>FNSKU</th><th>Quantity of FNSKU?*</th><th>SKU</th><th>UPC</th><th></th></tr>';
       $joomla('#N_table tbody tr').each( function () {
         //console.log($joomla(this).attr('id'));
         if($joomla(this).attr('id')==rs){
           htmse+='<tr id="dfg" class="'+rs+'">'+$joomla(this).html()+'</tr>';
         }
       })
       if($joomla(this).val()=='-'){
          // $joomla.each($joomla('#N_table tbody tr#dfg'), function(){
             //alert( $joomla(this).closest('tr').find("."+rs).html());
             // $joomla(this).closest('tr').find('.'+rs).remove();
            //})
            $joomla('.'+rs).remove();
            $joomla(this).val('+');
         }else{
             $joomla(this).closest('tr').after(htmse); 
             $joomla(this).val('-');
         }    
     });
        
    
   
    
    
    // display uploaded file names
    $joomla(document).on('change','.uploadFilenames',function(){
      
        $joomla(this).parents(".addrows1").find('.dangers ').remove();
    
    filesCount = $joomla(this)[0].files.length;
    var children = "";
    for (var i = 0; i < filesCount; ++i) {
        var filename = $joomla(this)[0].files.item(i).name;
        var filesize = $joomla(this)[0].files.item(i).size/1024/1024;
        ext = filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
        
        res = ["jpg","png","jpeg","gif","pdf"].includes(ext);
        
       
        if(res == false){
            alert("Invalid file type");
            return false;
        }
        
         if(filesize > 2){
            alert("File size exceeds 2 MB");
            return false;
            
        }
        
        
      
        children += '<li>' + $joomla(this)[0].files.item(i).name + '</li>';
    }
    $joomla(this).parents('.addrows1').find('#fileList').html('<ul>'+children+'</ul>');

    
    });
    
    // display uploaded file names in edit form
    
     $joomla(document).on('change','.uploadFilenames_edit',function(){
         
         $joomla(this).parents(".addrows2").find('.dangers ').remove();
    
    filesCount = $joomla(this)[0].files.length;
    var children = "";
    for (var i = 0; i < filesCount; ++i) {
        
        var filename = $joomla(this)[0].files.item(i).name;
        var filesize = $joomla(this)[0].files.item(i).size/1024/1024;
        ext = filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
        
        res = ["jpg","png","jpeg","gif","pdf"].includes(ext);
    
        if(res == false){
            alert("Invalid file type");
            return false;
        }
         if(filesize > 2){
            alert("File size exceeds 2 MB");
            return false;
        }
        
        
        children += '<li>' + $joomla(this)[0].files.item(i).name + '</li>';
    }
    $joomla(this).parents('.addrows2').find('#fileList_edit').html('<ul>'+children+'</ul>');
    
    });
    
    updateList = function() {
        var input = document.getElementById('uploadFiles_1');
        var output = document.getElementById('fileList');
        var children = "";
        for (var i = 0; i < input.files.length; ++i) {
            children += '<li>' + input.files.item(i).name + '</li>';
        }
        output.innerHTML = '<ul>'+children+'</ul>';
    }

    
});
</script>

<div class="container">
	<div class="main_panel persnl_panel panel-body">
		<div class="form-group text-right"><input type="button" id="showdiv1" style="display:none;" value="Go To Project List" class="btn btn-primary"></div>		
		
		<div class="panel-body" id="prfdiv" style="display:none">			
			<!-- Content Start -->
            <div class="cpl-blk">      
                    <!-- Panel Body Start -->
            <div class="col-md-12 rq-txt">
            <p class="pull-right"> <?php echo $assArr['Required'];?><span class="error">*</span></p>
            </div>
            <div class="clearfix"></div>
            
            <form name="userprofileFormOne" id="userprofileFormOne" method="post" enctype="multipart/form-data" action="">
                <div class="panel-body">
                    
                    <div class="acrdin-blk">
                        <div class="accordion"><?php echo $assArr['IPS/RAR'];?> <i class="fa fa-caret-down pull-right" aria-hidden="true"></i></div>
                         <div class="panel acrdin-bdy">
                             
                            <div class="col-md-12 form-group">
                            <p><strong><i><?php echo $assArr['Project Request Form_Scope of work'];?></i></strong></p>
                            <p><i>Be SMART and be Specific! Clearly define the scope of your project as concisely as possible. Your clear instructions will help us deliver a successful project!</i></p>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="row">
                                <div class="col-md-12 form-group">
                                <label class="col-md-4 col-form-label"><?php echo $assArr['Account_Number'];?><sub>*</sub></label>
                                <div class="col-md-8">
                                <input type="text" class="form-control" name="txtAccountnumber" name="txtAccountnumber" value="<?php echo $user;?>" readonly>
                                </div>
                                </div>
                                </div>
                                
                                 <!-- ********custom block start******** -->
                                
                                <div class="col-md-12 form-group">
                                    <div class="custom-radio-wrap">
                                                           
                                    <!--<div class="input-grp">-->
                                    <!--    <input id="txtInventory" type="radio"  name="txtInventory" value="IPS">-->
                                    <!--    <label class="custom-radio">Is your inventory NEW?</label> <sub>*</sub>-->
                                        <!--<span class="label-text">Yes</span>-->
                                    <!--</div>-->
                                    
                                    <!--<div class="input-grp">-->
                                    <!--    <input id="txtInventory" type="radio"  name="txtInventory" value="RAR">-->
                                    <!--    <label class="custom-radio">Used inventory?</label> <sub>*</sub>-->
                                    <!--</div> -->
                                    
                                    <?php  
                                    
                                    $businessTypes = Controlbox::GetBusinessTypes($user);
                                    
                                    foreach($businessTypes as $type){
                                        if($type->desc_vals == "IPS" && $type->is_shown == "true"){
                                            $type_desc = "Is your inventory NEW?";
                                        }else if($type->desc_vals == "RAR" && $type->is_shown == "true"){
                                            $type_desc = "Used inventory?";
                                        }else{
                                            $type_desc = '';
                                        }
                                        
                                        if($type_desc !=''){
                                   
                                    ?>
                                    
                                        <div class="input-grp">
                                            <input id="txtInventory" type="radio"  name="txtInventory" value="<?php echo $type->id_vals; ?>">
                                            <label class="custom-radio"><?php echo $type_desc; ?></label> <sub>*</sub>
                                            <!--<span class="label-text">Yes</span>-->
                                        </div>
                                    
                                    
                                    <?php } } ?>
                                    
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-4 col-form-label"><?php echo $assArr['Account_Name'];?><sub>*</sub></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="txtAccountname"  id="txtAccountname" value="<?php echo $UserView->UserName;?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                         </div>
                    </div>
                    
                    <div class="acrdin-blk">
                        <div class="accordion"><?php echo $assArr['IPS/RAR']?><i class="fa fa-caret-down pull-right" aria-hidden="true"></i></div>
                        <div class="panel acrdin-bdy">
                            
                            <div class="col-md-12 form-group">
                            <h4>Scope of project</h4>
                            <p><i>Clearly define the nature of your project. 
                            SMART Projects are: Specific, Measurable, Achievable, Realistic, Time-bound</i></p>
                            <p><i>Be SMART and clearly describe the project with direct and concise language. </i></p>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 col-form-label"><?php echo $assArr['Project_Name'];?><sub>*</sub></label>
                                    <div class="col-md-8">
                                         <input type="text" class="form-control"  name="txtProjectname" maxlength="50"  id="txtProjectname" autocomplete="off">
                                         <div class="projectNameError"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="acrdin-blk">
                        <div class="accordion"><?php echo $assArr['IPS/RAR'];?><i class="fa fa-caret-down pull-right" aria-hidden="true"></i></div>
                        <div class="panel acrdin-bdy">
                        <div class="form-group col-md-6 prdut-nme">
                        <label class="col-md-4 col-form-label"><?php echo $assArr['Product Name_(FNSKU Title)'];?><span class="error">*</span></label>
                        <div class="col-md-8">
                        <input type="text" class="form-control" maxlength="50"  name="txtProductTitle"  id="txtProductTitle" autocomplete="off">
                        </div>
                        </div>
                        <div id="tabs1">
                        <div class="rows"> 
                        
                            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 cls-addcol">
                                <label class="col-form-label"><?php echo $assArr['Provide_FNSKU'];?> ex. X00BT4N3V<span class="error">*</span></label>
                                <input type="text" class="form-control rowValidate" maxlength="20"  name="txtFnsku[]"  id="1" autocomplete="off">
                                </div>
                                
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 cls-addcol">
                                <label class="col-form-label"><?php echo $assArr['What is the quantity of product per_FNSKU'];?><span class="error">*</span></label>
                                <input type="text" class="form-control rowValidate" maxlength="5" name="txtFnskuquanity[]" id="2" >
                                </div>
                                
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 cls-addcol">
                                <label class="col-form-label"><?php echo $assArr['upc'];?><span class="error">*</span></label>
                                <input type="text" class="form-control rowValidate" maxlength="20"  name="txtUPC[]"  id="3">
                                </div>
                                
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 cls-addcol">
                                <label class="col-form-label"><?php echo $assArr['sKU'];?></label>
                                <input type="text" class="form-control" maxlength="20" name="txtSKU[]" id="4">
                                </div>
                                
                            </div>
                        
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 cls-add-ico buttons">
                            <input type="button" name="addrow" value="+" class="btn btn-primary"> 
                            <input type="button" name="deleterow" value="x" class="btn btn-danger">
                            </div>
                            
                            <div class="addRowsError col-sm-12 col-xs-12"></div>
                        </div>
                        </div>
                        </div>
                    </div>
                    
                    <div class="acrdin-blk">
                            <div class="accordion"><?php echo $assArr['IPS/RAR'];?> <i class="fa fa-caret-down pull-right" aria-hidden="true"></i></div>
                            <div class="panel acrdin-bdy">
                                <div class="col-md-12">
                                <h4>Service(s) Requested</h4>
                                </div>
                                    <div class="col-md-12">
                                    <div class="col-md-3 form-group"> <strong>Service requirement<sub>*</sub></strong>
                                    </div>
                                    <div class="col-md-9">
                                    <div class="getcustservices">
                                    <?php  
                                    
                                    $result =Controlbox::getnewservices($UserView->CustId);
                                    
                                    foreach($result->Data as $rg){
                                     echo '<div class="input-grp srvc-grp"><input type="checkbox"  name="txtService[]"  id="txtService[]" value="'.$rg->Cost.':'.$rg->id_AddnlServ.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label></div>';
                                    }
                                    ?>
                                    </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-7">
                                    <div class="row col-md-12 form-group dt-pkr-blk">
                                    <label class="col-md-5 col-form-label"><?php echo $assArr['Date_Requested'];?><sub>*</sub></label>
                                    <div class="col-md-7">
                                    <input type="text" class="form-control" id="dateTxt" name="dateTxt" >
                                    </div>
                                    </div>
                                    </div>
                                <div class="clearfix"></div>
                            </div>
                
              </div>
              
                    <div class="acrdin-blk">
                        <div class="accordion"> IPS / RAR <i class="fa fa-caret-down pull-right" aria-hidden="true"></i></div>
                        <div class="panel acrdin-bdy">
                        
                        <div class="col-md-12">
                            <div class="col-md-3 form-group"> <label>Upload Labels</label>
                            </div>
                            <div class="col-md-9">
                            <div id="tab3">
                                <div class="row addrows1 cls-addrwclr">
                                        <div class="input-grp srvc-grp col-md-3 col-sm-6 col-xs-6">
                                             <div class="input-group finputfile">
                                                  <span class="btn-block"> 
                                                    <span class="btn btn-file"> <?php echo $assArr['choose_file'];?> 
                                                        <input type="file" multiple class="uploadFilenames" name="uploadFiles[]"  id="uploadFiles_1" > <!--onchange="javascript:updateList()"-->
                                                    </span>
                                                   </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-3 col-xs-6 hidebuttons3 cls-add-ico">
                                            <input type="button" name="addrowthree" value="+" class="btn btn-primary addrowthree">&nbsp;&nbsp;<input type="button" name="deleterowthree" value="x" class="btn btn-danger deleterowthree">
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        <div id="fileList" class="col-md-12 col-sm-12 col-xs-12">(<?php echo $assArr['file_error'];?>)</div>
                                        <div class="clearfix"></div>
                                       
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        </div>
                        
                    <div class="acrdin-blk">
                        <div class="accordion"><?php echo $assArr['Project Request_Complete'];?><i class="fa fa-caret-down pull-right" aria-hidden="true"></i></div>
                        <div class="panel acrdin-bdy">
                        <div class="col-md-12">
                            <p>Thank you for completing the request form. Upon submission of this form, our warehouse will be notified of your project. If we have any questions or need additional clarification, someone for our Care Team will be in contact with you. </p>
                            <p>Before submitting the work order, please confirm that the scope of work you have described provides the clear and concise direct needed to complete the project accurately. </p>
                            <p>Our goal is 100% efficiency and to meet that goal we need your help by providing us SMART instructions. Be Specific, Be Realistic, Be Clear & Concise!</p>
                            <p>We are working on numerous projects concurrently. Good communication is the key to success for everyone.</p>
                            <p>Thank you - we will update you upon completion of your project. </p>
                            <p>All rights reserved by <?php echo $CompanyName; ?> | <a href="#"><?php echo $uri; ?></a></p>
                        </div>
                        </div>
                    </div>
                </div>
            <!-- Panel Body End -->
                    <div class="text-center">
                    <input type="submit" name="submit" value="SUBMIT" class="btn btn-primary" >
                    <input type="hidden" name="id" value="0" />
                    <input type="button" value="CLEAR" class="btn btn-danger clear-all" >
                    <input type="hidden" name="task" value="user.projectrequestform">
                    <input type="hidden" name="projectid" value="<?php echo Controlbox::getProjectautoid($user);?>">
                    <input type="hidden" name="user" value="<?php echo $user;?>" >
                    </div>
            
            </form>
            
          </div>  
        </div>
        
    <div id="proreqform">
        
        <?php  
            
            Controlbox::getProjectRequestsCsv($user);
            
        ?>
            
	<div class="row">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                        <h3 class=""><strong><?php echo Jtext::_('Project Requests');?></strong></h3>
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <a style="color:white;" class="form-group text-right btn btn-primary" id="showdiv">Add Project</a>		
                        <a style="color:white;" href="<?php echo JURI::base(); ?>/csvdata/prf_list.csv" class="btn btn-primary csvDownload export-csv"><?php echo $assArr['eXPORT_CSV'];?></a>
                    </div>
                </div>
        </div>
                <div class="row">
                <div class="col-md-12">
                <table class="table table-bordered theme_table" id="N_table"  data-page-length='50'>
                <thead>
                <tr>
                  <th><?php echo $assArr['action'];?></th>
                  <th><?php echo $assArr['Account_Number'];?></th>
                  <th><?php echo $assArr['Account_Name'];?></th>
                  <th><?php echo $assArr['Project_Name'];?></th>
                  <th><?php echo Jtext::_('COM_USERPROFILE_PRF_INVENTORY');?></th>
                  <th><?php echo $assArr['Date_Requested'];?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $ordersView= Controlbox::getpendingProjectdetails($user);
                
                // echo '<pre>';
                // var_dump($ordersView);exit;
                
                foreach($ordersView as $rg){
               for($i=0 ; $i < count($rg->FBAFormItems) ;$i++){
                echo '<tr id="'.str_replace(" ","",$rg->ProjectName).'" style="display:none"><td></td><td>'.$rg->FBAFormItems[$i]->FNSKUNo.'</td><td>'.$rg->FBAFormItems[$i]->QuantityperFNSKU.'</td><td>'.$rg->FBAFormItems[$i]->SKUNo.'</td><td>'.$rg->FBAFormItems[$i]->UPC.'</td><td></td></tr>';
                }  
                echo '<tr><td class="action_btns"><input type="button" class="btn btn-success wrchild prjrct-btn" value="+"><a href="#" class="btn btn-primary editpid" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id='.$rg->ProjectId.' data-target="#ord_edit"><i class="fa fa-pencil-square-o"></i></a><a href="#" class="btn btn-danger deletepid" data-id='.$rg->ProjectId.'><i class="fa fa-trash"></i></a></td><td>'.$rg->AccountNumber.'</td><td>'.$rg->AccountName.'</td><td>'.$rg->ProjectName.'</td><td>'.$rg->InventoryNeworOverstock.'</td><td>'.date("d-m-Y",strtotime($rg->RequestedDate)).'</td></tr>';
                }
                ?>
                </tbody>
                </table>
                </div>
        </div>
        </div>
        
        
        
    </div>
  </div> 
  
  
   <!--Update project request form-->
   
   <!-- Modal -->
<form name="userprofileFormSix" id="userprofileFormSix" method="post" action="" enctype="multipart/form-data">
  <div id="ord_edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content cpl-blk">
        <div class="modal-header">         
          <input type="button" data-dismiss="modal"  value="x" class="btn-close1" >       
          <h4 class="modal-title"><strong>Update My Project Request Form</strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['Account_Number'];?><span class="error">*</span></label>
                <input type="text" class="form-control" name="AccountNumberTxt" >
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label> <?php echo $assArr['Account_Name'];?><span class="error">*</span></label>
                <input type="text" class="form-control"  name="AccountNameTxt" >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="custom-radio-wrap">                   
                    <!-- <div class="input-grp">-->
                    <!--  <input id="InventoryTxt" type="radio" name="InventoryTxt" value="IPS">-->
                    <!--    <label class="custom-radio">Is your inventory NEW?</label> <sub>*</sub>-->
                        <!--<span class="label-text">Yes</span>-->
                    <!--    </div>-->
                    <!--<div class="input-grp">-->
                    <!--    <input id="InventoryTxt" type="radio" name="InventoryTxt" value="RAR">-->
                    <!--    <label class="custom-radio">Used Inventory?</label> <sub>*</sub>-->
                    <!-- </div>                   -->
                    
                    <?php  
                        
                        $businessTypes = Controlbox::GetBusinessTypes($user);
                        foreach($businessTypes as $type){
                            
                            if($type->desc_vals == "IPS" && $type->is_shown == "true"){
                                $type_desc = "Is your inventory NEW?";
                            }else if($type->desc_vals == "RAR" && $type->is_shown == "true"){
                                $type_desc = "Used inventory?";
                            }else{
                                $type_desc = '';
                            }
                            
                            if($type_desc !=''){
           
                            
                        ?>
                     <div class="input-grp">
                      <input id="InventoryTxt" type="radio" name="InventoryTxt" value="<?php echo $type->id_vals; ?>">
                        <label class="custom-radio"><?php echo $type_desc; ?></label>
                    </div>
                    
                     <?php } } ?>
                    
                    <div class="clearfix"></div>
                  </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['Define_Project'];?><span class="error">*</span></label>
                <input type="hidden" class="form-control" maxlength="250" name="Projectnamehidden" id="Projectnamehidden" >
                <input type="text" class="form-control" maxlength="250" name="ProjectnameTxt" id="ProjectnameTxt" >
                <div class="editProjectNameError"></div>
              </div>
              
            </div>
          </div>
           <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['order_date'];?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="OrderDateTxt" id="OrderDateTxt" >
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['Product_Name'];?>  <span class="error">*</span></label>
                <input type="text" class="form-control" maxlength="50"  name="productnameTxt" id="productnameTxt" >
              </div>
            </div>
          </div>
          <div id="tab2">
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="form-group">
                <div>
                <label>Service requirement <span class="error">*</span></label>
                </div>
                <div id="getcustservicesedit">
                 <?php  
                        $result =Controlbox::getnewservices($UserView->CustId);
                        foreach($result->Data as $rg){
                         echo '<div class="input-grp srvc-grp"><input type="checkbox"  name="ServiceTxt[]"  id="ServiceTxt[]" value="'.$rg->id_AddnlServ.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label></div>';
                        }
                      ?>
                </div>    
              </div>
              </div>
            </div>
           <div class="row form-group">
            <div class="col-sm-3 col-md-3">
            <div id="labels"></div>
            </div>
            <div class="clearfix"></div>
         </div> 
         <div class="clearfix"></div>
      <div class="row">    
         <div class="col-md-12 col-sm-12">
                <div class="col-md-2 col-sm-3 form-group"> <label>Upload Labels </label>
                </div>
                <div class="col-md-10 col-sm-8">
                <div id="tab4">
                    <div class="row addrows2 cls-addrwclr">
                            <div class="input-grp srvc-grp col-md-3 col-sm-6 col-xs-6">
                                <div class="input-group finputfile">
                                            <span class="btn-block"> 
                                                <span class="btn btn-file"> <?php echo $assArr['choose_file'];?>  
                                                    <input type="file" class="form-control uploadFilenames_edit" multiple  name="editUploadFiles[]" id="editUploadFiles_1">
                                                </span>
                                            </span>
                                </div>
                            </div>
                         
                            <div class="col-md-4 col-sm-3 hidebuttons4 cls-add-ico">
                                <input type="button" name="addrowfour" value="+" class="btn btn-primary addrowfour">&nbsp;&nbsp;<input type="button" name="deleterowfour" value="x" class="btn btn-danger deleterowfour">
                            </div>
                            
                            <div class="clearfix"></div>
                            <div id="fileList_edit" class="col-md-12 col-sm-12 col-xs-12"></div>
                            <div class="clearfix"></div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 text-center">
              (<?php echo $assArr['file_error'];?>)
            </div>
          </div>
      </div>


          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" value="Update" class="btn btn-primary">
              <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
      </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="task" value="user.userupdateprojectrequest">
  <input type="hidden" name="id"/>
  <input type="hidden" name="txtItemId"/>
  <input type="hidden" name="user" value="<?php echo $user;?>" />
</form>



