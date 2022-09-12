<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_unknownpkgs
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
if($user){
$session->clear('user_casillero_id');
$app =& JFactory::getApplication();
$app->redirect(JUri::base().'index.php/en/project-request-form');
}
//defined('_JEXEC') or die;
//$paramsAgain = ModProjectrequestform::getParams($params);
if($_REQUEST['submits']!=""){
   echo ModProjectrequestformHelper::submitrequestform($webservice);
}

$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$companyId = $domainDetails[0]->CompanyId;
$CompanyName = $domainDetails[0]->CompanyName;
$DomainEmail = $domainDetails[0]->PrimaryEmail;
$webservice = $params->get('webservice', '1');

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];

?>


  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    jQuery( function() {
    var Accountnumbers = <?php echo ModProjectrequestformHelper::getuserdetailsNameAjax($webservice,$companyId); ?>;
    jQuery( "#txtAccountnumber" ).autocomplete({
      source: Accountnumbers
    });
    });
    jQuery( function() {
    var txtAccountnames = <?php echo ModProjectrequestformHelper::getuserdetailsAjax($webservice,$companyId); ?>;
    jQuery( "#txtAccountname" ).autocomplete({
      source: txtAccountnames
    });
    });
  </script>

<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>-->
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo JUri::base(); ?>/components/com_userprofile/css/dataTables.bootstrap.min.css">
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    if($joomla( "#dateTxt" )){
    $joomla( "#dateTxt" ).datepicker({ maxDate: new Date });
 }
    // Wait for the DOM to be ready
	//$joomla(function() {
	
        //Initialize form validation on the registration form.
        // It has the name attribute "registration"
        var validfirst=$joomla("form[name='userprofileFormOne']").validate({
            
            // Specify validation rules
            rules: {
              // The key name on the left side is the name attribute
              // of an input field. Validation rules are defined
              // on the right side
              txtAccountnumber: {
                required: true
              },
              txtAccountname:{
				required: true/*,
				alphanumeric:true*/
			  },
			  txtInventory: {
                  required: true
              },
			  txtProjectname: {
                  required: true
              },
			  txtProductTitle: {
                  required: true
              },
			  "txtFnsku[]": {
                  required: true
              },
			  "txtFnskuquanity[]": {
                  required: true
              },
			  "txtUPC[]": {
                  required: true
              },
              "txtService[]":{
                  required: true 
              },
              dateTxt:{
                  required: true
              }
            },
            // Specify validation error messages
            messages: {
              txtAccountnumber: {required:"Please enter Account Id"},
              txtAccountname: {required:"Please enter name"/*,alphanumeric:"Please enter alpha characters"*/},
              txtInventory: {required:"Please check Inventory"},
              txtProjectname: {required:"Please enter Project Name"},
              txtProductTitle: {required:"Please enter Product Name"},
              "txtFnsku[]": {required:"Please enter FNSKU"},
              "txtFnskuquanity[]": {required:"Please enter quantity"},
              "txtUPC[]": {required:"Please enter UPC"},
              "txtService[]": {required:"Please check service"},
              dateTxt: {required:"Please enter Date"}
              
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function(form) {
              //form.submit();
            }
        });    
	//});    
	//$joomla('#submits').click(function (e) {	
		//alert ($joomla("#userprofileFormOne").form());
	//});	
    $joomla("#submits").click(function(){
        
      
        if (!$joomla("#userprofileFormOne").validate().form()) {
            return false; //doesn't validate
        }else
        {
            if($joomla("input[name='txtAccountnumber']")){
                var serv = [];
                $joomla('.modalday:checked').each(function() {
                  serv.push(this.value);
                });
                var fnsk='';
                $joomla("input[name='txtFnsku[]']").each(function () { 
                    fnsk +=$joomla(this).val()+",";
                });    
                var fqun='';
                $joomla("input[name='txtFnskuquanity[]']").each(function () { 
                    fqun +=$joomla(this).val()+",";
                });    
                
                var upc='';
                $joomla("input[name='txtUPC[]']").each(function () { 
                    upc +=$joomla(this).val()+",";
                });    
                var sku='';
                $joomla("input[name='txtSKU[]']").each(function () { 
                    sku +=$joomla(this).val()+",";
                });
                
                var formdata = new FormData();
                var totalfiles = $joomla(" input[type=file] ").length;
                
                $joomla(' input[type=file] ').each(function(){
                    
                        elemId = $joomla(this).attr('id');
                        var input = document.getElementById(elemId);
                        
                        
                        for (i=0 ; i < input.files.length ; i++){
                            var files = input.files[i];
                            formdata.append('inputFiles[]',files);
                        }
               
                });
                
                
                formdata.append('txtAccountnumber',$joomla("input[name='txtAccountnumber']").val());
                formdata.append('txtAccountname',$joomla('#txtAccountname').val());
                formdata.append('txtInventory',$joomla('#txtInventory').val());
                formdata.append('txtProjectname',$joomla('#txtProjectname').val());
                formdata.append('txtFnsku',fnsk);
                formdata.append('txtFnskuquanity',fqun);
                formdata.append('txtService',serv.join(","));
                formdata.append('dateTxt',$joomla('#dateTxt').val());
                formdata.append('projectid',$joomla('#projectid').val());
                formdata.append('txtProductTitle',$joomla('#txtProductTitle').val());
                formdata.append('txtUPC',upc);
                formdata.append('txtSKU',sku);
          
                $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>/modules/mod_projectrequestform/helper.php?task=get_ajax_data&regformsflag=1&CompanyID=<?php echo $companyId; ?>&webservice=<?php echo urlencode($webservice);?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			//data: { "txtAccountnumber":$joomla("input[name='txtAccountnumber']").val(),"txtAccountname": $joomla('#txtAccountname').val(),"txtInventory": $joomla('#txtInventory').val(),"txtProjectname": $joomla('#txtProjectname').val(),"txtFnsku": fnsk,"txtFnskuquanity": fqun,"txtService": serv.join(","),"dateTxt": $joomla('#dateTxt').val(),"projectid":$joomla('#projectid').val(),"txtProductTitle":$joomla('#txtProductTitle').val(),"txtUPC":upc,"txtSKU":sku, "inputdata":formdata},
    			
                data: formdata,
                contentType: false,
                cache: false,
                processData:false,
    			type: "post",
    			beforeSend: function() {
    			    $joomla(".page_loader").show();
    			   $joomla('#submits').prop('disabled', false); 
                },success: function(data){
                  $joomla('#submits').prop('disabled', false);     
                  if(data){
                    $joomla(".page_loader").hide();
                  alert("Successfully Inserted");
                  $joomla("#userprofileFormOne")[0].reset();
                  $joomla("#fileList ul").html("");
                  } 
                  
                }
              });
            }   
        }
        $joomla("#userprofileFormOne").submit();
    }); 
   	

    $joomla("input[name='txtAccountnumber']").blur(function(){
        if($joomla(this).val()){
            
            $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>/modules/mod_projectrequestform/helper.php?task=get_ajax_data&userids="+$joomla(this).val() +"&usingflag=1&webservice=<?php echo urlencode($webservice);?>&CompanyID=<?php echo $companyId;?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "userids": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
    			  $joomla(".loadimage1").html('');
                  $joomla(".loadimage1").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
                  $joomla('#submits').prop('disabled', true); 
               },success: function(data){
                   $joomla('#submits').prop('disabled', false); 
                  if(data) {
                  
                        $joomla.ajax({
                    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&user="+$joomla("input[name='txtAccountnumber']").val()+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                    			data: { "getbusinesstypeflag": 1 },
                    			dataType:"json",
                    			type: "get",
                    			beforeSend: function() {
                                },success: function(response){
                                   if(response == 0){
                                        $joomla(".loadimage1").html('');
                                        alert("No access for AIR type. Please contact administrator.");
                                   }else{
                                        $joomla(".loadimage1").html('');
                                       $joomla('input[name=txtAccountname]').val(data);
                                   }
                               }
                       
                        });
                  
                  
                  
                  }else{
                  $joomla('input[name=txtAccountname]').val('');
                  $joomla('input[name=txtAccountnumber]').val('');  
                  alert('Please register and add Project request form');
                  }
                }
    		});
    		
    		$joomla.ajax({
    			url: "<?php echo JURI::base(); ?>/modules/mod_projectrequestform/helper.php?userid="+$joomla(this).val() +"&businesstypeflag=1&webservice=<?php echo urlencode($webservice);?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: {"CompanyID":<?php echo $companyId;?>},
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
    			  $joomla(".loadimage1").html('');
                  $joomla(".loadimage1").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
                  $joomla('#submits').prop('disabled', true); 
               },success: function(data){
                   
                   $joomla('#submits').prop('disabled', false); 
                   $joomla(".loadimage1").html('');
                   $joomla("#businessTypesDiv").html(data);
                    
               }
    		});
    		
    		$joomla.ajax({
    			url: "<?php echo JURI::base(); ?>/modules/mod_projectrequestform/helper.php?userid="+$joomla(this).val() +"&getserviceflag=1&webservice=<?php echo urlencode($webservice);?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: {"CompanyID":<?php echo $companyId;?>},
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
    			  $joomla(".loadimage1").html('');
                  $joomla(".loadimage1").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
                  $joomla('#submits').prop('disabled', true); 
               },success: function(data){
                   
                   $joomla('#submits').prop('disabled', false); 
                   $joomla(".loadimage1").html('');
                   $joomla("#getservicesDiv").html(data);
                  
               }
    		}); 
    		
    		
        }	
	});  
	
	
	
	
    $joomla("input[name='txtAccountname']").blur(function(){
        if($joomla(this).val()){
            $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>/modules/mod_projectrequestform/helper.php?task=get_ajax_data&usernames="+$joomla(this).val() +"&usingflag=1&webservice=<?php echo urlencode($webservice);?>&CompanyID=<?php echo $companyId;?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "userids": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
    			  $joomla(".loadimage2").html('');
                  $joomla(".loadimage2").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
                  $joomla('#submits').prop('disabled', true); 
               },success: function(data){
                   $joomla('#submits').prop('disabled', false); 
                   $joomla(".loadimage2").html(''); 
                  if(data){
                  $joomla('input[name=txtAccountnumber]').val(data);
                  }else{
                  $joomla('input[name=txtAccountname]').val('');
                  $joomla('input[name=txtAccountnumber]').val('');
                  alert('Please register and add Project request form');
                  }
                }
    		});
        }	
	}); 
	$joomla('.accordion').click(function(){
	  $joomla(this).parent('.acrdin-blk').find('.acrdin-bdy').toggle();
	});
	
//	project name check



  $joomla("input[name='txtProjectname']").on('blur',function(e){
      
         $joomla('#prfdiv .btn-primary').attr("disabled", true);
         e.preventDefault();
         $joomla.ajax({
            url: "<?php echo JURI::base(); ?>/modules/mod_projectrequestform/helper.php?task=helper.get_ajax_data&txtProjectname="+$joomla(this).val()+"&prexistflag=1&CompanyID=<?php echo $companyId;  ?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
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
                   
                    $joomla('.projectNameError').html("<label class='error' for='txtProjectname' >Project Name is already exist</label>");
                    
                    $joomla('#prfdiv .btn-primary').attr("disabled", true);
               }

            },
            error: function () {
                alert("some problem in saving data");
            }
         })  
    })
    
    //end
	

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
         
        //   $joomla('#tabs1 .rows:last').find('td:last').html('<input class="btn btn-danger" type="button" name="deleterow" value="X">');
        
          }else{
            $joomla(this).parent().next().html("<sub>Please fill all the required fields</sub>");
        }
              
    });
    $joomla('#tabs1').on('click','input[name="deleterow"]',function(e){
      var lastone=$joomla('#tabs1 .rows').html();
      if($joomla('#tabs1 .rows').length==1){
        alert('Minimum One Row Required');
        return false;
      }else
        $joomla(this).closest('.rows').remove();
    });
    $joomla("input[name='txtFnskuquanity[]']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $joomla("input[name='txtUPC[]']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    $joomla("input[name='txtSKU[]']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Tab3
    
    
    $joomla(document).on('click','.addrowthree',function(e){
        
        // $inputFilescount = $joomla(this).parent().prev().find('input')[0].files.length;
    
        //  if($inputFilescount > 0){
        
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
      
          }
        //else{
        //      alert('Select minimum one file');
        //  }
         
    });
    
     $joomla(document).on('click','.deleterowthree',function(e){
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
    
    // display uploaded file names
    
    $joomla(document).on('change','.uploadFilenames',function(){
    
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
    
    
});    

</script>

<div class="container">
	<div class="main_panel persnl_panel panel-body">	
		<div class="" id="prfdiv">			
			<!-- Content Start -->
            <div class="cpl-blk">      
                    <!-- Panel Body Start -->
        <div class="col-md-12 rq-txt">
        	<p>* Required</p><!-- /index.php?option=com_content&view=article&id=8&Itemid=121-->
        </div>
        
        <div class="clearfix"></div>
        <form name="userprofileFormOne" id="userprofileFormOne" method="post" action="" enctype="multipart/form-data">
        <div class="">
          <div class="acrdin-blk">
            <div class="accordion"> IPS / RAR <i class="fa fa-caret-down" aria-hidden="true"></i></div>
            <div class="panel acrdin-bdy">
              <div class="col-md-12 form-group">
                <p><strong><i>Project Request Form: Scope of work</i></strong></p>
                <p><i>Be SMART and be Specific! Clearly define the scope of your project as concisely as possible. Your clear instructions will help us deliver a successful project!</i></p>
              </div>
              <div class="col-md-6">
                <div class="row col-md-12 form-group">
                  <label class="col-md-4 col-form-label">Account Number<sub>*</sub></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" maxlength="50" name="txtAccountnumber" id="txtAccountnumber">
                  </div>
                  <div class="loadimage1"></div>
                </div>
                <div class="col-md-12 form-group">
                  
                  
                  <!-- ********custom block start******** -->
                  <!--<div class="custom-radio-wrap">                   -->
                  <!--   <div class="input-grp">-->
                  <!--    <input id="txtInventory" type="radio"  name="txtInventory" id="txtInventory" value="IPS">-->
                  <!--      <label class="custom-radio">Is your inventory NEW?</label> <sub>*</sub>-->
                        <!--<span class="label-text">Yes</span>-->
                  <!--      </div>-->
                  <!--  <div class="input-grp">-->
                  <!--      <input id="txtInventory" type="radio"  name="txtInventory" id="txtInventory" value="RAR">-->
                  <!--      <label class="custom-radio">Used inventory?</label> <sub>*</sub>-->
                  <!--   </div>                   -->
                  <!--  <div class="clearfix"></div>-->
                  <!--</div>-->
                  
                  <div class="custom-radio-wrap">
                    <div id="businessTypesDiv"></div>
                    <div class="clearfix"></div>
                  </div>
                  
                  <!-- ********custom block end******** -->
                </div>
              </div>
              <div class="col-md-6">
                  <div class="row">
                <div class="col-md-12 form-group">
                  <label class="col-md-4 col-form-label">Account Name<sub>*</sub></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" maxlength="50" name="txtAccountname"  id="txtAccountname">
                  </div>
                  <div class="loadimage2"></div>
                </div>
                </div>
              </div>
            </div>
          </div>
          <div class="acrdin-blk">
            <div class="accordion"> IPS / RAR <i class="fa fa-caret-down" aria-hidden="true"></i></div>
            <div class="panel acrdin-bdy">
              <div class="col-md-12 form-group">
                <h4>Scope of project</h4>
                <p><i>Clearly define the nature of your project. 
                  SMART Projects are: Specific, Measurable, Achievable, Realistic, Time-bound</i></p>
                <p><i>Be SMART and clearly describe the project with direct and concise language. </i></p>
              </div>
              <div class="form-group col-md-6">
                <div class="form-group">
                  <label class="col-md-4 col-form-label">Define Project<sub>*</sub></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" maxlength="50"  name="txtProjectname"  id="txtProjectname">
                     <div class="projectNameError"></div>
                  </div>
                 
                </div>
              </div>
              
            </div>
          </div>
          <div class="acrdin-blk">
            <div class="accordion"> IPS / RAR <i class="fa fa-caret-down" aria-hidden="true"></i></div>
            <div class="panel acrdin-bdy">
                  <div class="form-group col-md-6">
                      <label class="col-md-4 col-form-label">Product Name (FNSKU Title)<sub>*</sub></label>
                      <div class="col-md-8">
                       <input type="text" class="form-control" maxlength="50"  name="txtProductTitle"  id="txtProductTitle">
                    </div>
                  </div>
                  <div id="tabs1">
                    <div class="rows">  
                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                      <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 cls-addcol">
                          <label class="col-form-label">Provide FNSKU ex. X00BT4N3V<sub>*</sub></label>
                            <input type="text" class="form-control" maxlength="20"  name="txtFnsku[]"  id="1">
                      </div>
                      <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 cls-addcol">
                          <label class="col-form-label">What is the quantity of product per FNSKU?<sub>*</sub></label>
                            <input type="text" class="form-control" maxlength="5" name="txtFnskuquanity[]" id="2" >
                      </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 cls-addcol">
                      <label class="col-form-label">UPC<sub>*</sub></label>
                        <input type="text" class="form-control" maxlength="20"  name="txtUPC[]"  id="3">
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 cls-addcol">
                          <label class="col-form-label">SKU</label>
                            <input type="text" class="form-control" maxlength="20" name="txtSKU[]" id="4" >
                        </div>
                  </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 cls-add-ico">
                      <input type="button" name="addrow" value="+" class="btn btn-primary"> 
                      <input type="button" name="deleterow" value="x" class="btn btn-danger">
                    </div>
                    <div class="addRowsError col-sm-12 col-xs-12"></div>
                </div>
                   </div>
            </div>
          </div>
          <div class="acrdin-blk">
            <div class="accordion"> IPS / RAR <i class="fa fa-caret-down" aria-hidden="true"></i></div>
        <div class="panel acrdin-bdy">
            
            <div id="getservicesDiv"></div>
            
              <div class="col-md-6">
                <div class="row col-md-12 form-group dt-pkr-blk">
                  <label class="col-md-4 col-form-label">Date Requested<sub>*</sub></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="dateTxt" name="dateTxt" >
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
            
          </div>
          
          
          <!--   Upload File Section  -->
          
               <div class="acrdin-blk">
                <div class="accordion"> IPS / RAR <i class="fa fa-caret-down" aria-hidden="true"></i></div>
                <div class="panel acrdin-bdy">
                  
                   <div class="col-md-12">
                        <div class="col-md-3 form-group"> <strong>Upload</strong>
                        </div>
                        <div class="col-md-9">
                        <div id="tab3">
                            
                            <div class="row addrows1 cls-addrwclr">
                                    <div class="input-grp srvc-grp col-md-3 col-sm-6 col-xs-6">
                                        <div class="input-group finputfile">
                                            <span class="btn-block"> 
                                            <span class="btn btn-file"> Choose File                 
                                            <input type="file" class="uploadFilenames"  multiple name="uploadFiles[]" id="uploadFiles_1">
                                            </span></span>
                                        </div>
                                    </div>                                 
                                    <div class="col-md-4 col-sm-3 col-xs-6 hidebuttons3 cls-add-ico">
                                        <input type="button" name="addrowthree" value="+" class="btn btn-primary addrowthree">&nbsp;&nbsp;<input type="button" name="deleterowthree" value="x" class="btn btn-danger deleterowthree">
                                    </div>                                    
                                    <div class="clearfix"></div>
                                    <div id="fileList" class="col-md-12 col-sm-12 col-xs-12"></div>
                                    <div class="clearfix"></div>
                            </div>
                        </div>
                          
                          </div>
                        
                      
                    </div>
                  </div>
                </div>
                
              </div>
          
          <!--  End  -->
          
          
          <div class="acrdin-blk">
              <div class="accordion">Project Request Complete<i class="fa fa-caret-down" aria-hidden="true"></i></div>
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
            <input type="submit" name="submits" id="submits" value="SUBMIT" class="btn btn-primary" >
            <input type="hidden" name="id" value="0" />
            <input type="hidden" name="projectid" id="projectid" value="<?php echo ModProjectrequestformHelper::getproectid($companyId); ?>" />
            
            <input type="button" value="CLEAR" class="btn btn-danger" onclick="this.form.reset();" >
        </div>
        </form>                    
      </div>
            <!-- Content End -->
		</div>
		</div>
	</div>
</div>
