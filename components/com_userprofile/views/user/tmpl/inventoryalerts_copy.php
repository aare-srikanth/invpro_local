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
$document->setTitle("Order Process in Boxon Pobox Software");
defined('_JEXEC') or die;
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$businessTypes = Controlbox::GetBusinessTypes($user);

if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
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

///echo '<pre>';
//var_dump($assArr);
   
}  

curl_close($ch);

?>
<?php include 'dasboard_navigation.php' ?>
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->
<script type="text/javascript" src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    
   // document.getElementById("myDIV").style.whiteSpace = "nowrap";
   
   $joomla("#country3Txt option").css("whiteSpace","break-spaces");
    
    $joomla('#p_table').DataTable({
        "pagingType": "simple" // "simple" option for 'Previous' and 'Next' buttons only
      });
    $joomla('#O_table').DataTable({
        "pagingType": "simple" // "simple" option for 'Previous' and 'Next' buttons only
      });
     
    $joomla('input[name="carriertrackingTxt"]').keydown(function(e) {
        if (e.keyCode == 32) {
            return false;
        }
    });
    
      $joomla(document).on('change','input[name="txtFile"],#multxtFile', function() {
          
       
             if(this.files[0].size > 2000000){
                 alert('<?php echo Jtext::_('COM_USERPROFILE_FILE_SIZE_ERROR') ?>');
                  $joomla(this).val('');
             }
             
            var filename=this.files[0].name;
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
             
    
  });
    
    $joomla(function() {
 
    // Initialize form validation on the registration form.
    // It has the name attribute "registration"
    var $valid=$joomla("form[name='userprofileFormOne']").validate({
    	
    	// Specify validation rules
    	rules: {
    	  // The key name on the left side is the name attribute
    	  // of an input field. Validation rules are defined
    	  // on the right side
    	  pnameTxt:{
    			required: true
    	  }/*,
    	  fnskuTxt: {
              required: true
          }*/,
    	  quantityTxt: {
              required: true
          },
    	  cnameTxt: {
              required: true
          },
    	  addinvoiceTxt: {
              required: true
          }
        },
    	// Specify validation error messages
    	messages: {
    	   pnameTxt:{
    		required: "Please enter Project Name"
    	  }/*,
    	  fnskuTxt: {
    		required: "Please enter FNSKU Number"
          }*/,
    	  quantityTxt: {
    		required: "Please enter Quantity"
          },
    	  cnameTxt: {
    		required: "Please enter Account Name"
          },
    	  addinvoiceTxt: {
    		required: "Please Upload Csv File"
          }
    
    	},
    	// Make sure the form is submitted to the destination defined
    	// in the "action" attribute of the form when valid
    	submitHandler: function(form) {
    	  $joomla('.page_loader').show();
    	  form.submit();
    	}
    });
    });	
    // Initialize form validation on the registration form.
    // It has the name attribute "registration"
    var $valid2=$joomla("form[name='userprofileFormTwo']").validate({
    	
    	// Specify validation rules
    	rules: {
    	  // The key name on the left side is the name attribute
    	  // of an input field. Validation rules are defined
    	  // on the right side
    	  pnameTxt:{
    			required: true
    	  }/*,
    	  fnskuTxt: {
              required: true
          }*/,
    	  quantityTxt: {
              required: true
          },
    	  cnameTxt: {
              required: true
          },
    	  addinvoiceTxt: {
              required: true
          }
        },
    	// Specify validation error messages
    	messages: {
    	   pnameTxt:{
    		required: "Please enter Project Name"
    	  }/*,
    	  fnskuTxt: {
    		required: "Please enter FNSKU Number"
          }*/,
    	  quantityTxt: {
    		required: "Please enter Quantity"
          },
    	  cnameTxt: {
    		required: "Please enter Account Name"
          },
    	  addinvoiceTxt: {
    		required: "Please Upload Inventory File"
          }
    
    	},
    	// Make sure the form is submitted to the destination defined
    	// in the "action" attribute of the form when valid
    	submitHandler: function(form) {
    	    $joomla('.page_loader').show();
    	     form.submit();
    	 }
       });
    
    
    $joomla('#pnameTxt').change(function(){
        $joomla('#productnameTxt').val('');
        $joomla('#cnameTxt').val('');
        //alert($joomla(this).val());
        if($joomla(this).val()){
            var exp=$joomla(this).val();
            exp=exp.split(":");
           $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&user=<?php echo $user;?>&project="+exp[0]+"&projflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "tracdid": $joomla(this).val() },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
               },success: function(data){
                   console.log(data);
                   //$msg[0]->ProjectId.':'.$msg[0]->AccountNumber.':'.$msg[0]->AccountName.':'.$msg[0]->InventoryNeworOverstock.':'.
                   //$msg[0]->ProjectName.':'.$msg[0]->ProductName.':'.$fns.':'.$fqt.':'.$fupc.':'.$fsku.':'.$idk;
                    
                    var exps=data;
                    exps=exps.split(":");
                    var fns=exps[6];
                    fns=fns.split(",");
                    var fqt=exps[7];
                    fqt=fqt.split(",");
                    var fupc=exps[8];
                    fupc=fupc.split(",");
                    var fsku=exps[9];
                    fsku=fsku.split(",");
                    var hsdl='';
                    for(i=0;i<fns.length;i++){
                        if(fns[i])
                        hsdl+='<tr><td>'+fns[i]+'</td><td>'+fqt[i]+'</td><td>'+fupc[i]+'</td><td>'+fsku[i]+'</td></tr>';
                    }
                    $joomla('#inventoryTxt').val(exps[3]);
                    $joomla('#productnameTxt').val(exps[5]);
                    $joomla('#cnameTxt').val(exps[1]);
                    $joomla('#Otable thead').show();
                    $joomla('#Otable tbody').show();
                    $joomla('#Otable').show();
                    $joomla('#Otable tbody').html('');
                    $joomla('#Otable tbody').append(hsdl);
                    //console.log(hsdl);
                 }
    		});
        }
    });
    $joomla('.reset').on('click',function () {
    	$joomla("#userprofileFormOne")[0].reset();
        $joomla('#Otable thead').hide();
        $joomla('#Otable tbody').hide();
    });    
    //$joomla('input[name="csvbtnSubmit"]').attr('disabled', true);
    $joomla('#stepone #addinvoiceTxt,#steptwo #addinvoiceTxt').live('change',function () {
        var ext = this.value.match(/\.(.+)$/)[1];
        switch (ext) {
            case 'csv':
            case 'CSV':
                $joomla('input[name="csvbtnSubmit"]').attr('disabled', false);
                break;
            default:
                alert('This is not an allowed file type.');
                this.value = '';
        }
    });	

    $joomla("input[name='quantityTxt']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    $joomla(document).on('change','input[name="txtFile"],#addinvoiceTxtMul,input[type="file"]', function() {
        
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
            
            //var wrname = filename.split('..');
            
           
            if($joomla(this).attr("id") != "addinvoiceTxt" && $joomla(this).attr("id") != "addinvoice3Txt"){
            
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
            
            }else{
                // if(wrname.length > 1){
                //     alert('<?php echo Jtext::_('COM_USERPROFILE_INVALID_EXT');?>');
                //     $joomla(this).val('');
                // }
                if($joomla.inArray(ext, ['csv']) == -1) {
                   
                     alert('<?php echo Jtext::_('COM_USERPROFILE_INVALID_EXT');?>');
                     $joomla(this).val('');
                }else{
                  $joomla("input[name=addinvoiceTxt] #errorTxt-error").html('');    
                }
            }
            
        }   
             
    
  });




    // Initialize form validation on the registration form.
    // It has the name attribute "registration"
    var validfirst=$joomla("form[name='userprofileFormThree']").validate({
    
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      mnameTxt: {
        required: true,
        alphanumeric:true
     },
      carrierTxt: {
        required: true,
        alphanumeric:true
     },
      carriertrackingTxt: {
        required: true
     },
      orderdateTxt: {
        required: true
     },
      "anameTxt[]": {
        required: true
     },
      "quantityTxt[]": {
        required: true
     },
      "declaredvalueTxt[]": {
        required: true
     },
      "totalpriceTxt[]": {
        required: false
     },
      "itemstatusTxt[]": {
        required: true
     },
      "country3Txt": {
        required: true
     }, 
     "addinvoiceTxtMul_1[]": {
     }
     
    },
    // Specify validation error messages
    messages: {
      mnameTxt: {required:"Please enter merchant name",alphanumeric:"Please enter alpha characters"},
      carrierTxt: {required:"Please enter Carrier Name",alphanumeric:"Please enter alpha characters"},
      carriertrackingTxt: "Please enter carrier tracking Id",
      orderdateTxt: "Please enter order date",
      "anameTxt[]": {required:"Please enter article name",alphanumeric:"Please enter alpha characters"},
      country3Txt: "<?php echo Jtext::_('Please enter destination country');?>",
      "quantityTxt[]": "Please enter quantity",
      "declaredvalueTxt[]": "Please enter declared value",
      "totalpriceTxt[]": "Please enter total price",
      "itemstatusTxt[]":{
    	required: "Please select status",
    	selectBox: "Please select status"
      },
      "addinvoiceTxtMul_1[]": "Please add invoice"
    
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
        $joomla('.page_loader').show();
        form.submit();
    }
    });    
	$joomla('#addinvoice2Txt').bind('change', function() {
        //this.files[0].size gets the size of your file.
        $joomla("input[name=addinvoice2Txt] #errorTxt-error").html('');
        var ext = $joomla('input[name=addinvoice2Txt]').val().split('.').pop().toLowerCase();
            if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf','GIF','PNG','JPG','JPEG','PDF']) == -1) {
            $joomla('input[name=addinvoice2Txt]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please check File invalid extension!</label>');
            $joomla('#addinvoice2Txt').val('');
            return false;
        }else{
          $joomla("input[name=addinvoice2Txt] #errorTxt-error").html('');    
        }
        if(this.files[0].size>2000000){
            $joomla('input[name=addinvoiceTxt]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please upload below 1MB file size</label>');
            $joomla('#addinvoice2Txt').val('');
            return false;
        }else{
          $joomla("input[name=addinvoice2Txt] #errorTxt-error").html('');    
        }   
            
    });
    
    
    
    $joomla.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z/ /]+$/.test(value);
    });
    
    $joomla('.return').click(function(){
        $joomla('#idk').val($joomla(this).data('id'));
        $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
    });
    $joomla('.keep').click(function(){
        $joomla('#idk2').val($joomla(this).data('id'));
        $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
    });
    $joomla('.discardc').click(function(){
        $joomla('#idk3').val($joomla(this).data('id'));
        $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
    });    
    $joomla('#inpr').show(); 
    $joomla('#inpre').hide(); 
    <?php if($_GET[r]==1){?>
            $joomla('#inpr').show(); 
            $joomla('#inpre').hide(); 
            $joomla('#stepone').show(); 
            $joomla('#steptwo').hide(); 
            $joomla('#stepthree').hide(); 
    <?php } ?>
    <?php if($_GET[r]==2){?>
           $joomla('#stepone').hide(); 
           $joomla('#steptwo').hide(); 
           $joomla('#stepthree').show(); 
            $joomla('#inpr').hide(); 
            $joomla('#inpre').show(); 
    <?php } ?>
    $joomla('input[name=alertTxt]').click(function(){
         if($joomla(this).val()==1){
            $joomla('#inpr').show(); 
            $joomla('#inpre').hide(); 
            $joomla('#stepone').show(); 
            $joomla('#steptwo').hide(); 
            $joomla('#stepthree').hide(); 
        }else if($joomla(this).val()==2){
            $joomla('#inpr').show(); 
            $joomla('#inpre').hide(); 
            
           $joomla('#stepone').hide(); 
           $joomla('#steptwo').show(); 
           $joomla('#stepthree').hide(); 
           $joomla('input[name="csvbtnSubmit"]').attr('disabled', false);

        }else{
             if($joomla( "#orderdateTxt" ).length){
        $joomla( "#orderdateTxt" ).datepicker({ maxDate: new Date });
    } 
           $joomla('#stepone').hide(); 
           $joomla('#steptwo').hide(); 
           $joomla('#stepthree').show(); 
            $joomla('#inpr').hide(); 
            $joomla('#inpre').show(); 
        }
       
        
    });     
    
    var g='<?php echo $_GET[r];?>';
    if(g==2){
          if($joomla( "#orderdateTxt" ).length){
        $joomla( "#orderdateTxt" ).datepicker({ maxDate: new Date });
    } 
    }
    
     //exist tracking
    $joomla('input[name="carriertrackingTxt"]').on('blur',function(){
        var res=$joomla(this).val();
        $joomla('#loading-image4').html('');
         if(res!="")
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&trackexisttype="+res+"&trackexistflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "trackid": $joomla(this).val() },
			dataType:"html",
			type: "get",
			beforeSend: function() {
             $joomla('input[name="carriertrackingTxt"]').after('<div id="loading-image4" ><img src="/components/com_userprofile/images/loader.gif"></div>');
             $joomla('#stepthree .btn-primary').attr("disabled", true);
           },success: function(data){
             if(data.length==11){
                $joomla('#stepthree .btn-primary').attr("disabled", false);
                $joomla('#loading-image4').each( function () {
                $joomla(this).remove();
               });
             }else{
                $joomla('#loading-image4').html("<label class='error'>"+data+"</label>");
                $joomla('#stepthree .btn-primary').attr("disabled", true);
             }
                 
             }
		});
    });

   $joomla('input[name^="quantityTxt[]"]' ).live('blur',function(e){
        $joomla(this).closest('.row').find('div:nth-child(4) input').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla(this).closest('.row').find('div:nth-child(3) input').val()));
        total=total.toFixed(2);
        if(total!="NaN")
        $joomla(this).closest('.row').find('div:nth-child(4) input').val(total);
    });
    $joomla('input[name^="declaredvalueTxt[]"]' ).live('blur',function(e){
        $joomla(this).closest('.row').find('div:nth-child(4) input').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla(this).closest('.row').find('div:nth-child(2) input').val()));
        total=total.toFixed(2);
        if(total!="NaN")
        $joomla(this).closest('.row').find('div:nth-child(4) input').val(total);
    });
    
    



   $joomla("input[name='quantityTxt[]']").live('keyup',function(e){
    this.value = this.value.replace(/[^0-9]/g, '');
   });


   $joomla("input[name='declaredvalueTxt[]']").live('keypress',function (e) {
    if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
    }
    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
    });


    $joomla('#stepthree').on('click','input[name="addrow"]',function(e){
        
        var i=0;
        $joomla('input[name="addrow"]').each(function(){
            if($joomla("form[name='userprofileFormThree']").valid() == true){
                i++;
            }
        });
        
       $joomla('#itemCount span').html(i+1);
        
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
     
      $joomla('<div class="row rows">'+sd+'</div>').insertAfter( $joomla(this).closest('.row') );
      $joomla('#stepthree .rows:last').find('td:last').html('');
      $joomla('#stepthree .rows:last').find('td:last').html('<input class="btn btn-danger btn-rem" type="button" name="deleterow" value="X">');
      }          
    });
    $joomla('#stepthree').on('click','input[name="deleterow"]',function(e){
      var lastone=$joomla('#tabs1 .rows').html();
      if($joomla('#stepthree .rows').length==1){
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

     var tmp='';
     tmp=$joomla("#ord_edit .modal-body").html();
     $joomla('#O_table').on('click','a:nth-child(1)',function(e){
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
              $joomla("#ord_edit .modal-body").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
           },success: function(data){
              //console.log(tmp);
              $joomla("#ord_edit .modal-body").html(tmp); 
              var cospor=data;
              cospor=cospor.split(":");
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
              $joomla('input[name=txtFNSKUName]').val(cospor[10]);
              $joomla('input[name=txtSKUName]').val(cospor[11]);
              $joomla('input[name=txtOrderId]').val(cospor[19]); 
              $joomla('input[name=txtRmaValue]').val(cospor[20]);
              
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
              
              
               $joomla.each($joomla("input[name=InventoryTxt]"), function(){
                    if($joomla(this).val()==$joomla.trim(cospor[21])){
                      $joomla(this).attr('checked','checked');
                    }
                  });
              
              if(cospor[3]){
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
              $joomla('input[name="txtOrderDate"]').removeClass('hasDatepicker').datepicker(); 
              }               
            }
        });
    }); 
    var tmp2='';
     tmp2=$joomla("#inv_edit .modal-body").html();
     $joomla('#p_table').on('click','a:nth-child(1)',function(e){
        e.preventDefault();
        var resnew=$joomla(this).data('id');
        $joomla.ajax({
        	url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&orderupdatetype="+resnew +"&orderupdateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
        	data: { "orderupdatetype": $joomla(this).data('id') },
        	dataType:"html",
        	type: "get",
        	cache: false,
        	beforeSend: function() {
              $joomla("#inv_edit .modal-body").html('');
              $joomla("#inv_edit .modal-body").html('<img src="/components/com_userprofile/images/loader.gif"></div>');
           },success: function(data){
              //console.log(tmp);
              $joomla("#inv_edit .modal-body").html(tmp2); 
              var cospor=data;
              cospor=cospor.split(":");
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
              $joomla('input[name=txtFNSKUName]').val(cospor[10]);
              $joomla('input[name=txtSKUName]').val(cospor[11]);
              $joomla('input[name=txtOrderId]').val(cospor[19]); 
              $joomla('input[name=txtRmaValue]').val(cospor[20]);
              
              if(cospor[12]){
              var fileName = cospor[12];
              var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                  if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                    var hrefs=cospor[12];
                    hrefs=hrefs.split(' ').join('%20');
                    hrefs=hrefs.replace("##",":");
                    $joomla('#orderimage').html('<a href='+hrefs+' target="_blank">(View Invoice)</a>');
                  }
              }
                  
                  $joomla.each($joomla("input[name=InventoryTxt]"), function(){
                    if($joomla(this).val()==$joomla.trim(cospor[21])){
                      $joomla(this).attr('checked','checked');
                    }
                  });
              
              
              if(cospor[3]){
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
              $joomla('input[name="txtOrderDate"]').removeClass('hasDatepicker').datepicker(); 
              }               
            }
        });
    });    
    
    $joomla('#txtFile').bind('change', function() {
        //this.files[0].size gets the size of your file.
        $joomla("input[name=txtFile] #errorTxt-error").html('');
        var ext = $joomla('input[name=txtFile]').val().split('.').pop().toLowerCase();
            if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf','GIF','PNG','JPG','JPEG','PDF']) == -1) {
            $joomla('input[name=txtFile]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please check File invalid extension!</label>');
            $joomla('#txtFile').val('');
            return false;
        }else{
          $joomla("input[name=txtFile] #errorTxt-error").html('');    
        }
        if(this.files[0].size>2000000){
            $joomla('input[name=txtFile]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please upload below 1MB file size</label>');
            $joomla('#txtFile').val('');
            return false;
        }else{
          $joomla("input[name=txtFile] #errorTxt-error").html('');    
        }   
            
    });

    
    
     $joomla(function() {
 
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormSix']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          /*txtFNSKUName: {
            required: true
          },*/
          txtSKUName: {
            required: true
          },
          txtMerchantName: {
            required: true,
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
          txtCarrierName: {
            required: true
          },
          txtTracking: {
            required: true
          },
          txtQuantity: {
            required: true
          }
        },
        // Specify validation error messages
        messages: {
          /*txtFNSKUName: "Please enter FNSKU Name",*/
          txtSKUName: "Please enter SKU Name",
          txtMerchantName: {required:"Please enter Merchant Name",alphanumeric:"Please enter Merchant Name must alphabet characters only"},
          txtOrderDate: "Please select Order date",
          txtArticleName: {required:"Please enter Artcile Name",alphanumeric:"Please enter Article Name must alphabet characters only"},
          txtDvalue: "Please enter Delcared Value",
          txtCarrierName: "Please enter Carrier Name",
          txtTracking: "Please enter Tracking Number",
          txtQuantity: "Please Enter Item Quantity"
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
       		 $joomla('input[name=txtTracking]').prop("disabled", false);
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
    
        $joomla(function() {
 
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormSeven']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          txtMerchantName: {
            required: true,
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
          txtCarrierName: {
            required: true
          },
          txtTracking: {
            required: true
          },
          txtQuantity: {
            required: true
          }
        },
        // Specify validation error messages
        messages: {
          txtMerchantName: {required:"Please enter Merchant Name",alphanumeric:"Please enter Merchant Name must alphabet characters only"},
          txtOrderDate: "Please select Order date",
          txtArticleName: {required:"Please enter Artcile Name",alphanumeric:"Please enter Article Name must alphabet characters only"},
          txtDvalue: "Please enter Delcared Value",
          txtCarrierName: "Please enter Carrier Name",
          txtTracking: "Please enter Tracking Number",
          txtQuantity: "Please Enter Item Quantity"
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
       	    $joomla('input[name=txtTracking]').prop("disabled", false);
            form.submit();
        }
        });    
    });
    
   $joomla("input[name='txtQuantity']").live('keyup',function(e){
    this.value = this.value.replace(/[^0-9]/g, '');
    //if (/\D/g.test(this.value))
    //this.value.replace(/[0-9]*\.?[0-9]+/g, '');  for name
    });
    
        

    $joomla('input[name="txtQuantity"]').live('blur',function(){
        $joomla('input[name="txtTotalPrice"]').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla('input[name="txtDvalue"]').val()));
        total=parseFloat(total).toFixed(2);
        if(total!="NaN")
        $joomla('input[name="txtTotalPrice"]').val(total);
       
    });
    $joomla('input[name="txtDvalue"]').live('blur',function(){
        $joomla('input[name="txtTotalPrice"]').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla('input[name="txtQuantity"]').val()));
        total=parseFloat(total).toFixed(2);
        if(total!="NaN")
        $joomla('input[name="txtTotalPrice"]').val(total);
       
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
   $joomla("input[name='declaredvalueTxt[]']").live('keypress',function (e) {
    if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
    }
    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
    });
   
  
    //delete inventry purchases order
    $joomla('#p_table,#O_table').on('click','a:nth-child(2)',function(e){
        e.preventDefault();
        var res=$joomla(this).data('id');
        var reshtml=$joomla(this);
        var cf=confirm("Please confirm to delete");
        
        if(cf==true){
            $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&orderdeletetype="+res +"&orderdeleteflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "orderdeletetype": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
                  $joomla("#loading-image").show();
               },success: function(data){
                    if(data==1){
                        //console.log(reshtml.closest('tr').hide());
                        reshtml.parents('tr').hide();
                    }
                }
    		});
        }	
        return false;
    });
    $joomla("#stepthree input[name='btnReset']").live('click',function(e){
        $joomla('.rows').show();
    });
    
    $joomla("input[name='addinvoice3Txt']").live('change',function(e){
        $joomla('.rows').hide();
    });
    
});
</script>
<style type="text/css">
    .tp-rdobtn input[type="radio"]{margin-top:2px;}
    .tp-rdobtn .radio-inline {margin-right: 30px;}
</style>
<div class="container">
  <div class="main_panel persnl_panel">
    <!-- <div class="main_heading">My Project Request</div>-->
    <div class="panel-body">
        
        <?php
        
                            $dispIpsRar = "false";
                            $dispWoFba = "false";
                            $dispAir = "flase";
                            
                            foreach($businessTypes as $type){
                                if($type->id_vals == "IPS" || $type->id_vals == "RAR"){
                                    $dispIpsRar = $type->is_visible;
                                    $dispWoFba = $type->is_visible;
                                }
                                
                                if($type->id_vals == "AIR"){
                                    $dispAir = $type->is_visible;
                                }
                            }
                            
        ?>
      
        <div class="tp-rdobtn">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <div class="row">
                      <!-- <div class="col-sm-4"> -->
                        <label class="radio-inline">
                        <input type="radio" <?php if($_GET[r]==""){?>checked<?php }?><?php if($_GET[r]==1){?>checked<?php }?> name="alertTxt" id="alertTxt" value=1>IPS/RAR
                        </label>
                      <!-- </div>
                      <div class="col-sm-4"> -->
                        <label class="radio-inline">
                        <input type="radio" name="alertTxt" id="alertTxt" value=2 >WITHOUT FBA FORM</label>
                      <!-- </div>
                      <div class="col-sm-4"> -->
                        <label class="radio-inline">
                        <input type="radio" <?php if($_GET[r]==2){?>checked<?php }?> name="alertTxt" id="alertTxt" value=3 >AIR
                        </label>
                      <!-- </div>-->
                    </div>
                  </div>
            </div>
        </div>
        <div id="stepone">
        <form name="userprofileFormOne" id="userprofileFormOne" method="post" action="" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Project Name <span class="error">*</span></label>
                <select class="form-control" name="pnameTxt" id="pnameTxt" >
                    <option value="">Please select option</option>
                    <?php echo Controlbox::getProjectdetails($user); ?>
                </select>    
              </div>
            </div>
            <input type="hidden" class="form-control" name="inventoryTxt" id="inventoryTxt">
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Product Name </label>
                <input class="form-control" name="productnameTxt" id="productnameTxt">
              </div>
            </div>
          </div>
           <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered theme_table" id="Otable" style="display:none" data-page-length='25'>
                  <thead>
                    <tr>
                      <th>FNSKU No</th>
                      <th>Quantity</th>
                      <th>UPC</th>
                      <th>SKU</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          
          <div class="row">
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Account Name<span class="error">*</span></label>
                <input class="form-control" name="cnameTxt" id="cnameTxt">
              </div>
            </div>
             <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Add Inventory<span class="error">*</span></label>
                <input type="file" class="form-control" name="addinvoiceTxt" id="addinvoiceTxt">
                <label>Upload CSV Below 2Mb file <a href="<?php echo JUri::base(); ?>/index.php?option=com_userprofile&task=user.downloadfile&val=fba" target="_blank">Download Sample</a></label>
               </div>
            </div>
           </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="button" name="btnReset" value="Reset" class="btn btn-danger reset">
                <input type="submit" name="csvbtnSubmit" value="Submit" class="btn btn-primary">
              </div>
            </div>
          </div>
          <input type="hidden" name="task" value="user.inventoryalertsform">
          <input type="hidden" name="id" value="0" />
          <input type="hidden" name="user" value="<?php echo $user;?>" />
        </form>
        </div>
        <div id="steptwo" style="display:none">
        <form name="userprofileFormTwo" id="userprofileFormTwo" method="post" action="" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Project Name <span class="error">*</span></label>
                <input type="text" class="form-control" name="pnameTxt" id="pnameTxt" maxlength="50">
              </div>
            </div>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>FNSKU Number </label>
                <input class="form-control" name="fnskuTxt" id="fnskuTxt" maxlength="20">
              </div>
            </div>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Quantity <span class="error">*</span></label>
                <input class="form-control" name="quantityTxt" id="quantityTxt" maxlength="3">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Account Name<span class="error">*</span></label>
                <input class="form-control" name="cnameTxt" id="cnameTxt" value="<?php echo $user;?>" readonly>
              </div>
            </div>
             <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Add Inventory<span class="error">*</span></label>
                <input type="file" class="form-control" name="addinvoiceTxt" id="addinvoiceTxt">
                <label>Upload CSV Below 2Mb file <a href="<?php echo JUri::base(); ?>/index.php?option=com_userprofile&task=user.downloadfile&val=ips" target="_blank">Download Sample</a></label>
              </div>
            </div>
            <div class="col-sm-12 col-md-4">                   
                  <div class="form-group ip-rdbtn">
            
            <?php  
            
            
            foreach($businessTypes as $type){
                if($type->is_shown == "true"){
           
            ?>
            <input id="InventoryTxt" type="radio" name="inventoryTxt" value="<?php echo $type->id_vals; ?>">
            <label class="custom-radio"><?php echo $type->desc_vals; ?></label>
            
            <?php } } ?>
            
             </div>   
            </div>
           </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="reset" name="btnReset" value="Reset" class="btn btn-danger">
                <input type="submit" name="csvbtnSubmit" value="Submit" class="btn btn-primary">
              </div>
            </div>
          </div>
          <input type="hidden" name="task" value="user.inventoryalertsform">
          <input type="hidden" name="id" value="0" />
          <input type="hidden" name="user" value="<?php echo $user;?>" />
        </form>
        </div>
        <div id="stepthree"  style="display:none">
        <form name="userprofileFormThree" id="userprofileFormThree" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" class="form-control" name="userType" id="" value="COMP">
            <input type="hidden" name="business_type" value="Air">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="m-0"><strong>Create an Pre Alert</strong></h3>
            </div>
            <!--<div class="col-sm-6 text-right">
              <button class="btn btn-primary">Add Purchase Order</button>
            </div>-->
          </div>
          <!--<div class="row">
            <div class="col-sm-12">
              <h4 class="sub_title"><strong>Add Prealerts</strong></h4>
            </div>
          </div>-->
          <div class="row">
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['merchant_name']; ?><span class="error">*</span></label>
                <input class="form-control" name="mnameTxt" id="mnameTxt" maxlength="40">
              </div>
            </div>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['carrier']; ?> <span class="error">*</span></label>
                <input class="form-control" name="carrierTxt" id="carrierTxt" maxlength="40">
              </div>
            </div>
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label><?php echo $assArr['tracking_ID_of_the_operator']; ?> <span class="error">*</span></label>
                <input class="form-control" name="carriertrackingTxt" id="carriertrackingTxt" maxlength="40">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['order_date']; ?> <span class="error">*</span></label>
                <input class="form-control" name="orderdateTxt" id="orderdateTxt">
              </div>
            </div>
           
         
            <div class="col-sm-12 col-md-3">
               <div class="form-group">
                <label><?php echo $assArr['destination_country']; ?> <span class="error">*</span></label>
                <?php
					       $countryView= UserprofileHelpersUserprofile::getCountriesList();
					       $arr = json_decode($countryView); 
                           $countries='';
					       foreach($arr->Data as $rg){
					          $countries.= '<option value="'.$rg->CountryCode.'">'.$rg->CountryDesc.'</option>';
                           }
             
    					?>
                <select class="form-control" name="country3Txt" id="country3Txt">
                  <option value=""><?php echo Jtext::_('COM_USERPROFILE_ALERTS_SELECT_COUNTRY');?></option>
                  <?php echo $countries;?>
                </select>
              </div>
            </div>
               <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['upload'];?></label>
                <input type="file" class="form-control" name="addinvoice3Txt"  id="addinvoice3Txt">
                <label>Upload extension type csv Below 2Mb file. <a href="<?php echo JUri::base(); ?>/index.php?option=com_userprofile&task=user.downloadfile&val=air" target="_blank">Download Sample</a></label>
              </div>
            </div>
          </div>
          <div class="row rows">
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['article_name'];?> <span class="error">*</span> </label>
                <input class="form-control"  name="anameTxt[]" id="1" maxlength="50">
              </div>
            </div>
            <div class="col-sm-12 col-md-2">
              <div class="form-group">
                <label><?php echo $assArr['quantity'];?> <span class="error">*</span></label>
                <input class="form-control" name="quantityTxt[]" id="2" maxlength="3" >
              </div>
            </div>
            <div class="col-sm-12 col-md-2">
              <div class="form-group">
                <label><?php echo $assArr['declared_Value_(USD)'];?>  <span class="error">*</span> </label>
                <input class="form-control" name="declaredvalueTxt[]" id="3" maxlength="10" >
              </div>
            </div>
            <div class="col-sm-12 col-md-2">
              <div class="form-group">
                <label><?php echo $assArr['total_cost'];?></label>
                <input class="form-control" name="totalpriceTxt[]" id="4" readonly >
              </div>
            </div>
            <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['item_status'];?></label>
                <select class="form-control" name="itemstatusTxt[]" id="itemstatusTxt" disabled="true">
                    
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
                <input type="hidden" name="itemstatusTxt[]" value="In Progress">
              </div>
            </div>
            <div class="clearfix"></div>
            
              <!--order id , rma value-->
            
         <div class="col-sm-12 col-md-3">
              <div class="form-group">
                <label><?php echo $assArr['rMA_Value'];?> </label>
                    <input class="form-control" name="rmavalue[]" id="rmavalue_1" maxlength="40"  >
              </div>
            </div>
            <div class="col-sm-12 col-md-2">
              <div class="form-group">
                <label><?php echo $assArr['order_ID'];?> </label>
                    <input class="form-control" name="orderidTxt[]" id="orderidTxt_1"  maxlength="40"  >
              </div>
            </div>
            <div class="col-sm-12 col-md-5">
              <div class="form-group">
                <label><?php echo $assArr['add_Invoice '];?> <span class="error">*</span></label>
                <!--  <div class="input-group finputfile">
                    <input type="text" class="form-control"  name="fileTxt" id="fileTxt" value=""> 
                    <span class="input-group-btn"> <span class="btn btn-file"> Choose File                   
                    <input type="file" class="form-control" name="addinvoiceTxt" id="addinvoiceTxt">
                    </span> </span> 
                </div>-->
                <input type="file" class="form-control"   name="addinvoiceTxtMul_1[]" id="addinvoiceTxtMul_1" class="addinvoiceTxtMul" multiple  required >
                <label>Upload extension type png,jpg,gif and pdf Below 2Mb file</label>
              </div>
            </div>
            <!-- End -->
            <div class="col-sm-12 col-md-2">
              <div class="form-group btn-grp1">
                <input type="button" name="addrow" value="+" class="btn btn-primary btn-add"> 
                <input type="button" name="deleterow" value="x" class="btn btn-danger btn-rem">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="reset" name="btnReset" value="Reset" class="btn btn-danger">
                <input type="submit" name="btnSubmit" value="Submit" class="btn btn-primary">
              </div>
            </div>
          </div>
          <input type="hidden" name="pages" value="1" />
          <input type="hidden" name="task" value="user.addshippment">
          <input type="hidden" name="id" value="0" />
          <input type="hidden" name="user" value="<?php echo $user;?>" />
        </form>
        
        </div>
        <div  style="display:none">
            <div class="row">
              <div class="col-sm-12">
                <h3 class="mx-1"><strong>Project Requests</strong></h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered theme_table" id="N_table" data-page-length='25'>
                  <thead>
                    <tr>
                      <th>Project Name</th>
                      <th>FNSKU No</th>
                      <th>Quantity</th>
                      <th>Account Name</th>
                      <th>Inventory</th>
                    </tr>
                  </thead>
                  <tbody>
    <?php
        //$ordersView= Controlbox::getpendingProjectdetails($user);
        foreach($ordersView as $rg){
          echo '<tr><td>'.$rg->Project_Name.'</td><td>'.$rg->FNSKU.'</td><td>'.$rg->Quantity_FNSKU.'</td><td>'.$rg->Cust_Name.'</td><td>'.$rg->TrackingId.'</td></tr>';
        }
    ?>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
       <div id="inpre"  style="dispay:none">
        
        <div class="row">
          <div class="col-sm-12 inventry-alert">
              <div class="col-sm-6">
            <h3 class=""><strong><?php echo 'Inventory Pre Alerts';?></strong></h3>
            </div>
            <div class="col-sm-6 text-right">
               <a style="color:white;" href="<?php echo JURI::base(); ?>/csvdata/pre_alerts_ind.csv" class="csvDownload export-csv btn btn-primary text-right">Export CSV</a>
          </div>
          </div>
        </div>
        
        <?php  
        
         Controlbox::getInvertoryPurchasesListCsv($user);
         
        ?>
        
        <div class="row">
          <div class="col-md-12">
              <div class="table-responsive">
            <table class="table table-bordered theme_table" id="O_table" data-page-length='25'>
              <thead>
                <tr>
                  <th><?php echo $assArr['merchant_name']; ?></th>
                  <th><?php echo $assArr['article_name']; ?></th>
                  <th><?php echo $assArr['order_date']; ?></th>
                  <th><?php echo $assArr['quantity']; ?></th>
                  <th><?php echo $assArr['tracking#']; ?></th>
                  <th><?php echo $assArr['total_cost']; ?></th>
                 <th><?php echo Jtext::_('COM_USERPROFILE_INV_INVENTORY_TYPE'); ?></th>
                  <th><?php echo $assArr['order_ID']; ?></th>
                  <th><?php echo $assArr['rMA_Value']; ?></th>
                  <th><?php echo $assArr['status']; ?></th>
                 <th><?php echo  $assArr['action']; ?></th>
                </tr>
              </thead>
              <tbody>
<?php
    $ordersView= UserprofileHelpersUserprofile::getInvertoryPurchasesList($user);
    
    //var_dump($ordersView);
    
    foreach($ordersView as $rg){
        if($rg->Fnsku=="0" || $rg->Fnsku=="")
      echo '<tr><td>'.$rg->SupplierId.'</td><td>'.$rg->ItemName.'</td><td>'.date("d-m-Y",strtotime($rg->OrderDate)).'</td><td>'.$rg->ItemQuantity.'</td><td>'.$rg->TrackingId.'</td><td>'.$rg->cost.'</td><td>'.strtoupper($rg->type_busines).'</td><td>'.$rg->OrderIdNew.'</td><td>'.$rg->RMAValue.'</td><td>In Progress</td><td class="action_btns"><a href="#" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id='.$rg->Id.' data-target="#ord_edit"><i class="fa fa-pencil-square-o"></i></a><a href="#" class="btn btn-danger" data-id='.$rg->Id.'><i class="fa fa-trash"></i></a></td></tr>';
    }
?>
              </tbody>
            </table>
            </div>
          </div>
        </div>
        </div>
        
        <div id="inpr" style="dispay:none">
      <div class="row">
          <div class="col-sm-12 inventry-alert">
              <div class="col-sm-6">
            <h3 class=""><strong><?php echo 'Inventory Pre Alerts';?></strong></h3>
            </div>
            <div class="col-sm-6 text-right">
               <a style="color:white;" href="<?php echo JURI::base(); ?>/csvdata/pending_projects.csv" class="csvDownload export-csv btn btn-primary text-right">Export CSV</a>
          </div>
          </div>
        </div>
        
        <?php  
        
         Controlbox::getpendingProjectdetailsCsv($user);
         
        ?>
        <div class="row">
          <div class="col-md-12">
              <div class="table-responsive">
            <table class="table table-bordered theme_table" id="p_table" data-page-length='25'>
              <thead>
                <tr>
                  <th><?php echo $assArr['order_date']; ?></th>
                  <th><?php echo $assArr['quantity']; ?></th>
                  <th><?php echo $assArr['tracking#']; ?></th>
                  <th><?php echo Jtext::_('COM_USERPROFILE_INVWFORM_FNSKU_NUMBER'); ?></th>
                  <th><?php echo Jtext::_('COM_USERPROFILE_INVWFORM_SKU_NUMBER'); ?>#</th>
                  <th><?php echo Jtext::_('COM_USERPROFILE_INVWFORM_INV_TYPE'); ?></th>
                  <th><?php echo $assArr['order_ID']; ?></th>
                  <th><?php echo $assArr['rMA_Value']; ?></th>
                  <th><?php echo $assArr['status']; ?></th>
                  <th><?php echo $assArr['action']; ?></th>
                </tr>
              </thead>
              <tbody>
<?php
    $ordersView= UserprofileHelpersUserprofile::getInvertoryPurchasesList($user);
    
    // echo '<pre>';
    // var_dump($ordersView);exit;
    
    foreach($ordersView as $rg){
      if(strlen($rg->Fnsku)>1){
       echo '<tr><td>'.date("d-m-Y",strtotime($rg->OrderDate)).'</td><td>'.$rg->ItemQuantity.'</td><td>'.$rg->TrackingId.'</td><td>'.$rg->Fnsku.'</td><td>'.$rg->Sku.'</td><td>'.$rg->type_busines.'</td><td>'.$rg->OrderIdNew.'</td><td>'.$rg->RMAValue.'</td><td>In Progress</td><td class="action_btns"><a href="#" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id='.$rg->Id.' data-target="#inv_edit"><i class="fa fa-pencil-square-o"></i></a><a href="#" class="btn btn-danger" data-id='.$rg->Id.'><i class="fa fa-trash"></i></a></td></tr>';
      }
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
</div>
   
<!-- Modal -->
<form name="userprofileFormSix" id="userprofileFormSix" method="post" action="" enctype="multipart/form-data" >
  <input type="hidden" class="form-control" name="custType" value="COMP">  
  <div id="inv_edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">         
          <input type="button" data-dismiss="modal"  value="x" class="btn-close1" >       
          <h4 class="modal-title"><strong>Update My Pre Alerts</strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>FNSKU Number </label>
                <input type="text" class="form-control" name="txtFNSKUName" >
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>SKU <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtSKUName" >
              </div>
            </div>
          </div>
          <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group ip-rdbtn">
                        
                        <?php  
                        
                        $businessTypes = Controlbox::GetBusinessTypes($user);
                        foreach($businessTypes as $type){
                         
                        ?>
                        <input id="InventoryTxt" type="radio" name="InventoryTxt" value="<?php echo $type->id_vals; ?>">
                        <label class="custom-radio"><?php echo $type->desc_vals; ?></label>
                        
                        <?php  } ?>
                        
                    </div> 
                </div>
            </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Merchant Name <span class="error">*</span></label>
                <input type="text" class="form-control" name="txtMerchantName"  readonly>
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Carrier <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtCarrierName" >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Order Date <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtOrderDate" id="txtOrderDate" >
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Carrier Tracking ID <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtTracking" id="txtTracking">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Article Name <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtArticleName">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Invoice </label>
                <input type="file" class="form-control"  name="txtFile">
                Upload extension type png,jpg,gif and pdf Below 2Mb file<div id="orderimage"></div>
              </div>
            </div>
            
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Quantity <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtQuantity">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Declared Value($) <span class="error">*</span></label>
                <input type="text" placeholder="0.00" class="form-control"  name="txtDvalue">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                  <label>Total Price <span class="error">*</span></label>
                  <input type="text" placeholder="0.00" readonly="readonly" class="form-control"  name="txtTotalPrice">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Item Status</label>
                <select class="form-control" name="txtStatus" disabled="true">
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
                <input type="hidden" name="txtStatus" value="In Progress">
              </div>
            </div>
          </div>
          
           <!-- order id, rma value -->
          
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                  <label>Order ID</label>
                  <input type="text" class="form-control" name="txtOrderId" maxlength="100">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>RMA Value </label>
                 <input type="text" class="form-control" name="txtRmaValue" maxlength="100">
              </div>
            </div>
          </div>
          
          <!-- End -->
          
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
  <input type="hidden" name="task" value="user.userupdatepurchase">
  <input type="hidden" name="id"/>
  <input type="hidden" name="urlid" value="1"/>
  <input type="hidden" name="txtItemId"/>
  <input type="hidden" name="user" value="<?php echo $user;?>" />
</form>

   
<!-- Modal -->
<form name="userprofileFormSeven" id="userprofileFormSeven" method="post" action="" enctype="multipart/form-data">
    <input type="hidden" class="form-control" name="custType" value="COMP">
  <div id="ord_edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">         
          <input type="button" data-dismiss="modal"  value="x" class="btn-close1" >       
          <h4 class="modal-title"><strong>Update My Pre Alerts</strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Merchant Name <span class="error">*</span></label>
                <input type="text" class="form-control" name="txtMerchantName" readonly>
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Carrier <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtCarrierName" >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Order Date <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtOrderDate" id="txtOrderDate" >
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Carrier Tracking ID <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtTracking" id="txtTracking">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Article Name <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtArticleName">
              </div>
            </div>
            <!--multiple invoice-->
            
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ALERTS_ADD_INVOICE');?> <span class="error">*</span></label>
                
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
                <label>Quantity <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtQuantity">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Declared Value($) <span class="error">*</span></label>
                <input type="text" placeholder="0.00" class="form-control"  name="txtDvalue">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                  <label>Total Price <span class="error">*</span></label>
                  <input type="text" placeholder="0.00" readonly="readonly" class="form-control"  name="txtTotalPrice">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Item Status</label>
                <select class="form-control" name="txtStatus" disabled="true">
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
                <input type="hidden" name="txtStatus" value="In Progress">
              </div>
            </div>
          </div>
          
           <!-- order id, rma value -->
          
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                  <label>Order ID</label>
                  <input type="text" class="form-control" name="txtOrderId" maxlength="100">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>RMA Value </label>
                 <input type="text" class="form-control" name="txtRmaValue" maxlength="100">
              </div>
            </div>
          </div>
          
          <!-- End -->
          
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
  <input type="hidden" name="task" value="user.userupdatepurchase">
  <input type="hidden" name="id"/>
  <input type="hidden" name="urlid" value="2"/>
  <input type="hidden" name="txtItemId"/>
  <input type="hidden" name="user" value="<?php echo $user;?>" />
</form>