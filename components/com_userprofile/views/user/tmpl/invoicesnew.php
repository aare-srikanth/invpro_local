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
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}

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

    
    $joomla("input[name=txtQty]").css("width","80px");
    
    if($joomla( "#orderdateTxt" ))
    $joomla( "#orderdateTxt" ).datepicker({ maxDate: new Date });
    var tmp='';
    tmp=$joomla("#ord_edit .modal-body").html();
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
              if(cospor[3]){
                  $joomla('input[name=txtMerchantName]').attr('readonly',true);
                  $joomla('input[name=txtCarrierName]').attr('readonly',true);
                  $joomla('input[name="txtOrderDate"]').attr('readonly',true);
                  $joomla('input[name="txtOrderDate"]').datepicker("destroy");
                  
                  $joomla('input[name=txtTracking]').attr('readonly',true);
                  console.log("sada:"+$joomla('input[name=txtTracking]').attr('readonly'));
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
 
    $joomla('#tabs3').on('click','a.getsstatus',function(e){
        e.preventDefault();
        var html =$joomla("#shipdetailsModal .modal-body").html();
        var awdid=$joomla(this).data('id');
      $joomla('input[name=txtShippingDetails]').val('');
      $joomla('input[name=txtComments]').val('');
      $joomla('input[name=txtDocumentCharges]').val('');
      $joomla('input[name=txtShippingCost]').val('');
      $joomla('input[name=txtFinalCost]').val('');
      $joomla('input[name=txtAmountPaid]').val('');
      $joomla('input[name=txtAmountPayable]').val('');
      $joomla('input[name=txtPaymentMethod]').val('');
      $joomla('input[name=txtTransactionNumber]').val('');
      $joomla('input[name=txtInvoiceNumber]').val('');
      $joomla('input[name=txtDate]').val('');
      $joomla('input[name=txtAmountPaidPaid]').val('');
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&ordershiptype="+awdid +"&ordershipflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "ordershiptype": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#shipdetailsModal .modal-body").html('<div id="loading-image3" ><img src="/components/com_userprofile/images/loader.gif"></div>');
           },success: function(data){
              $joomla("#shipdetailsModal .modal-body").html(html);
              var cosawd=data;
		      cosawd=cosawd.split(":");
		      $joomla('input[name=txtShippingDetails]').val(cosawd[3]);
		      $joomla('input[name=txtComments]').val(cosawd[2]);
		      $joomla('input[name=txtDocumentCharges]').val(cosawd[4]);
		      $joomla('input[name=txtShippingCost]').val(cosawd[5]);
		      $joomla('input[name=txtFinalCost]').val(cosawd[6]);
		      $joomla('input[name=txtAmountPaid]').val(cosawd[7]);
		      $joomla('input[name=txtAmountPayable]').val(cosawd[8]);
		      $joomla('input[name=txtPaymentMethod]').val(cosawd[9]);
		      $joomla('input[name=txtTransactionNumber]').val(cosawd[10]);
		      $joomla('input[name=txtInvoiceNumber]').val(cosawd[11]);
		      $joomla('input[name=txtDate]').val(cosawd[12]);
		      $joomla('input[name=txtAmountPaidPaid]').val(cosawd[13]);
            }
		});
    });    

    //delete inventry purchases order
    $joomla('#tabs1 #N_table').on('click','a:nth-child(2)',function(e){
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
        if(total)
        $joomla(this).closest('.row').find('div:nth-child(4) input').val(total+".00");
    });
    $joomla('input[name^="declaredvalueTxt[]"]' ).live('blur',function(e){
        $joomla(this).closest('.row').find('div:nth-child(4) input').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla(this).closest('.row').find('div:nth-child(2) input').val()));
        if(total)
        $joomla(this).closest('.row').find('div:nth-child(4) input').val(total+".00");
    });
    
    

    $joomla('input[name="txtQuantity"]').live('blur',function(){
        $joomla('input[name="txtTotalPrice"]').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla('input[name="txtDvalue"]').val()));
        if(total)
        $joomla('input[name="txtTotalPrice"]').val(total+".00");
       
    });
    $joomla('input[name="txtDvalue"]').live('blur',function(){
        $joomla('input[name="txtTotalPrice"]').val('');
        var total=0;
        total=(parseFloat($joomla(this).val())*parseFloat($joomla('input[name="txtQuantity"]').val()));
        if(total)
        $joomla('input[name="txtTotalPrice"]').val(total+".00");
       
    });
    var getC="<?php echo $_GET["c"];?>"; 
    if(getC==3){
       $joomla('.nav-tabs li a').removeClass('active');
       $joomla('.nav-tabs li:last a').addClass('active');
       $joomla('.panel-body #tabs3').show();
       $joomla('.panel-body #tabs1').hide();
       $joomla('.panel-body #tabs2').hide();
    }
    if(getC==2){
       $joomla('.nav-tabs li a').removeClass('active');
       $joomla('.nav-tabs li:nth-child(2) a').addClass('active');
       $joomla('.panel-body #tabs2').show();
       $joomla('.panel-body #tabs1').hide();
       $joomla('.panel-body #tabs3').hide();
    }

    $joomla('.nav-tabs li a').click(function(){
     $joomla('.panel-body #tabs1').hide();
     $joomla('.panel-body #tabs2').hide();
     $joomla('.panel-body #tabs3').hide();
     $joomla('.nav-tabs li a').removeClass('active');
      if( $joomla(this).html()=="Shipments History"){
       $joomla('.panel-body #tabs3').show();
       $joomla('.nav-tabs li:last a').addClass('active');
      }
      else if( $joomla(this).html()=="Pending Shipments"){
        $joomla('.panel-body #tabs2').show();
        $joomla('.nav-tabs li:nth-child(2) a').addClass('active');
      }
      else{
        $joomla('.panel-body #tabs1').show();
        $joomla('.nav-tabs li:first a').addClass('active');
      }
      return false;
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
        required: true,
        alphanumeric:true
     },
      "quantityTxt[]": {
        required: true
     },
      "declaredvalueTxt[]": {
        required: true
     },
      "totalpriceTxt[]": {
        required: true
     },
      "itemstatusTxt[]": {
        required: true
     }
    },
    // Specify validation error messages
    messages: {
      mnameTxt: {required:"Please enter merchant name",alphanumeric:"Please enter alphabet characters"},
      carrierTxt: {required:"Please enter Carrier Name",alphanumeric:"Please enter alphabet characters"},
      carriertrackingTxt: "Please enter carrier tracking Id",
      orderdateTxt: "Please enter order date",
      "anameTxt[]": {required:"Please enter article name",alphanumeric:"Please enter alphabet characters"},
      "quantityTxt[]": "Please enter quantity",
      "declaredvalueTxt[]": "Please enter declared value",
      "totalpriceTxt[]": "Please enter total price",
      "itemstatusTxt[]":{
    	required: "Please select status",
    	selectBox: "Please select status"
      }
    
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
        if($joomla("input[name=addinvoiceTxt]").val()!=""){
            $joomla("input[name=addinvoiceTxt] #errorTxt-error").html('');
            var ext = $joomla('input[name=addinvoiceTxt]').val().split('.').pop().toLowerCase();
            if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf']) == -1) {
                $joomla('input[name=addinvoiceTxt]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please check File invalid extension!</label>');
                return false;
            }else{
              $joomla("input[name=addinvoiceTxt] #errorTxt-error").html('');    
            }
        }
      form.submit();
    }
    });    
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
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
    
    $joomla('#ord_ship .btn-primary:first').click(function(){
       $joomla("#loading-image").hide();
       $joomla('#ord_ship #step1').hide(); 
       $joomla('#ord_ship #step2').show();
       $joomla('#ord_ship #step3').hide();
    });
    $joomla('#ord_ship .btn-back').click(function(){
       $joomla('#ord_ship #step1').hide(); 
       $joomla('#ord_ship #step2').show();
       $joomla('#ord_ship #step3').hide();
    });
    
    
    $joomla('#ChangeShippingAddressStr').click(function(){
       loadadditionalusersData();
       $joomla('#ChangeShippingAddress').toggle(); 
    });
    
    
    $joomla('#ord_ship #step2').on('click','.btn-primary:last',function(){
       if($joomla('input[name=shipmentStr]:checked').val()=="undifined" || $joomla('input[name=shipmentStr]:checked').val()==null){
        //$joomla('#myAlertModal').modal("show");
        //$joomla('#error').html("Please check shipping");
        alert("Please check shipping");
        return false;
       }else{
         $joomla('input[name="cc"]').prop("checked", false);
         $joomla('input[name="ccStr"]').prop("checked", false);
         $joomla('input[name="cardnumberStr"]').val('');
         $joomla('input[name="txtccnumberStr"]').val('');
         $joomla('input[name="txtNameonCardStr"]').val('');
         $joomla('#dvPaymentInformation').css('display','none');
         $joomla('#dvPaymentMethod').hide();          
         $joomla('select[name="MonthDropDownListStr"]').val('');
         $joomla('select[name="YearDropDownListStr"]').val('');
         $joomla('#specialinstructionDiv').html($joomla('textarea[name=specialinstructionStr]').val());
         $joomla('#txtspecialinsStr').val($joomla('textarea[name=specialinstructionStr]').val());
         $joomla('#ord_ship #step1').hide(); 
         $joomla('#ord_ship #step2').hide(); 
         $joomla('#ord_ship #step3').show();
       }       
        
    });
    
    
    $joomla('select[name="adduserlistStr"]').live('change',function(){
        $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);		 
        var vk=$joomla(this).val();
        vk=vk.split(":");
        $joomla.ajax({
            url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&adduserid="+vk[0] +"&adduserflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "shippmentid": vk[0] },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#loading-image2").show();
              $joomla('#ord_ship #step2 .btn-primary').attr("disabled", true);
           },success: function(data){
               $joomla('#ord_ship #step2 .btn-primary').attr("disabled", false);
                $joomla('#dtStr').val(vk[1]);
                var sdata=data;
                sdata=sdata.replace(",/gi","<br>");
                $joomla('#ChangeShippingAddressNew').html(sdata); 
                $joomla('#ChangeShippingAddress').hide();
                $joomla("#loading-image2").hide();
                $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);	
                $joomla("#divShipCOstOne").html('');
                
                
		    }
		});
    });
    
    $joomla('input[name=cc]').click(function(){
       if($joomla(this).val()=="prepaid"){
           $joomla('#dvPaymentMethod').show(); 
       }else{
           $joomla('#dvPaymentMethod').hide(); 
           
       }    
    });
    $joomla('input[name=ccStr]').click(function(){
       $joomla('.paymentopt').toggle(); 
    });
    
    $joomla('input[name=shipmentStr]').click(function(){
        
        var resv=$joomla(this).val();
        var whsc=$joomla('#wherhourecStr').val()
        whsc=whsc.split(",");

        var shipetype=$joomla('input[name=txtId]').closest('tr').find('td:eq(6)').html();
      
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&paymenttype="+$joomla(this).val() +"&wherhourec="+ whsc[0]+"&invidk="+$joomla('#invidkStr').val() +"&qty="+$joomla('#qtyStr').val() +"&destination="+$joomla('#dtStr').val() +"&volres="+$joomla('#volresStr').val() +"&tyserv="+$joomla('#tyserviceStr').val() +"&munits="+$joomla('#mrunitsStr').val()+"&source="+$joomla('#srStr').val()+"&shiptype="+shipetype+"&user=<?php echo $user;?>&shippmentflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "shippmentid": $joomla(this).val() },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#divShipCOstOne").html("<img src='/components/com_userprofile/images/loader.gif'>");
              $joomla('#ord_ship #step2 .btn-primary').attr("disabled", true);
           },success: function(data){
               var cosship=data;
		       cosship=cosship.split(":");
		       $joomla('#ord_ship #step2 .btn-primary').attr("disabled", false);
               $joomla("#divShipCOstOne").html('');
               $joomla('#divShipCOstTwo').html('');
		       $joomla('#shipmethodStrtext').html(resv);
		       $joomla('#shipmethodStrValue').html(cosship[4]);
		       $joomla('#shipmethodStrValuetwo').html(cosship[0]);
		       //var fixedNum = parseFloat(cosship[0]-cosship[3]).toFixed( 2 );
		       var fixedNum = parseFloat(cosship[0]);
		       $joomla('#shipmethodtotalStr').html(fixedNum);
		       $joomla('input[name=shipcostStr]').val(cosship[0]);
		       $joomla('input[name=shipservtStr]').val(cosship[1]);
		       $joomla('input[name=amtStr]').val(fixedNum);
		       $joomla("#addserStr").html(cosship[2]);
		       $joomla("#discountStr").html(cosship[3]);
		       if(cosship[0]>0){
		       if(resv=="standard")
    			   $joomla('#divShipCOstOne').html('<label>Shipping Method - Standard</label><br><label>SHIPPING COST :</label>'+cosship[4]+'<br><label>ADDITIONAL SERVICES :</label>'+cosship[2]+'<br><label>DISCOUNT :</label>'+cosship[3]+'</label><br><label>TOTAL COST : </label>'+ cosship[0]);
    			   else
    			   $joomla('#divShipCOstTwo').html('<label>Shipping Method -  Express </label><br><label>SHIPPING COST :</label>'+cosship[4]+'<br><label>ADDITIONAL SERVICES :</label>'+cosship[2]+'<br><label>DISCOUNT :</label>'+cosship[3]+'</label><br><label>TOTAL COST : </label>'+cosship[0]);
        	   }else{
                  $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);		           
                  alert("No service rates available for destination");
		       }
		       
	    	}
		});
    });
    $joomla('#tabs2').on('click','input[name="txtId"]:checked',function(){
      var x=0;
      var xx='';
      var types=$joomla(this).closest('tr').find('td:eq(6)').html();
      var stype=$joomla(this).closest('tr').find('td:eq(7)').html();
      var dtype=$joomla(this).closest('tr').find('td:eq(8)').html();
      var dunits=$joomla(this).closest('tr').find('td:eq(9)').html();
      var munits=$joomla(this).closest('tr').find('td:eq(10)').html();
      $joomla("input[name='txtId']:checked").each( function () {
        if($joomla(this).closest('tr').find('td:eq(6)').html()==types)
        {
            
        }else{
          x=1;
          xx="Shipment Type";
        }
        if($joomla(this).closest('tr').find('td:eq(7)').html()==stype)
        {
            
        }else{
          x=2;
          xx="Source Type";
        }
        if($joomla(this).closest('tr').find('td:eq(8)').html()==dtype)
        {
            
        }else{
          x=3;
          xx="Destnation Type";
        }
        if($joomla(this).closest('tr').find('td:eq(9)').html()==dunits)
        {
            
        }else{
          x=4;
          xx="Dim Units";
        }
        if($joomla(this).closest('tr').find('td:eq(10)').html()==munits)
        {
            
        }else{
          x=5;
          xx="Mass Units";
        }
      });
       if(x!=0){
        $joomla('#myAlertModal').modal("show");
        $joomla('#error').html("Please check Shipping "+xx+" are not same");
        //alert($joomla(this).val());
        $joomla(this).prop('checked',false);
       }
        
    });
    $joomla('#tabs2').on('click','.ship',function(){
       if (!$joomla("input[name='txtId']:checked").length) {
           $joomla(this).closest('tr').find('input[name="txtId"]').attr('checked','checked');
       }
       $joomla('#ord_ship #j_table:first tbody:last').remove();    
       $joomla('#ord_ship #k_table:first tbody:last').remove();    
       $joomla('#ord_ship #step3').hide(); 
       $joomla('#ord_ship #step2').hide(); 
       $joomla('#ord_ship #step1').show();
       if($joomla('#ord_ship #j_table tr').length==1){
         var tds='<tr>';
         var tdns='<tr>';
         var whs=[];
         var idksn=[];
         var qtyl=[];
         //var cdk = [];
         //var db=$joomla(this).data('id');
         var volres=[];
         var tyservice=[];
         var sr=[];
         var dt=[];
         var mrunits=[];
         $joomla.each($joomla("input[name='txtId']:checked"), function(){            
            //cdk.push($joomla(this).val());
            //alert(cdk.join(", "));      
            var loops=$joomla(this).val();
            //console.log(loops);
            var loop=loops.split(":");
            for(i=0;i<loop.length;i++){
                if(loop[i]=="" || loop[i]==null){
                 tds+="";   
                }
                else{
                 if(i==0){
                    tds+="<td>"+loop[i]+"</td>";
                    tdns+="<td>"+loop[i]+"</td>";
                 }    
                 if(i==1){
                    //$joomla('#wherhourecStr').val(loop[i]); 
                    whs.push(loop[i]);
                    tds+="<td>"+loop[i]+"</td>";
                 }    
                 if(i==2){
                   //console.log($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));     
                   qtyl.push($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
                   tds+="<td>"+$joomla(this).closest('tr').find('input[name="txtQty"]').attr('value')+"</td>";
                   tdns+="<td>"+$joomla(this).closest('tr').find('input[name="txtQty"]').attr('value')+"</td>";
                 }    
                 if(i==3){
                    $joomla('#trackingidStr').val(loop[i]);
                    tds+="<td>"+loop[i]+"</td>";
                 }
                 if(i==4){
                    idksn.push(loop[i]); 
                 }
                 if(i==5){
                    $joomla('#ItemPriceStr').val(loop[i]);
                    tdns+="<td>"+loop[i]+"</td>";
                 }
                 if(i==6){
                    $joomla('#costStr').val(loop[i]);
                    tdns+="<td>"+loop[i]+"</td>";
                }
                 if(i==8){
                    volres.push(loop[i]);
                    //console.log(loop[i])
                 }
                 if(i==9){
                    tyservice.push(loop[i]);
                    //console.log(loop[i])
                 }
                 if(i==10){
                    sr.push(loop[i]);
                    //console.log(loop[i])
                 }
                 if(i==11){
                    dt.push(loop[i]);
                    //console.log(loop[i])
                 }
                 if(i==12){
                    mrunits.push(loop[i]);
                    //console.log(loop[i])
                 }
               }
          }
          whs.join(", ");
          idksn.join(", ");
          volres.join(", ");
          tyservice.join(", ");
          sr.join(", ");
          dt.join(", ");
          mrunits.join(", ");
          
          
          qtyl.join(", ");
          //console.log(qtyl);
          $joomla('#qtyStr').val(qtyl);
          
          $joomla('#wherhourecStr').val(whs);
          $joomla('#invidkStr').val(idksn);
          $joomla('#volresStr').val(volres);
          $joomla('#tyserviceStr').val(tyservice);
          $joomla('#mrunitsStr').val(mrunits);
          
          $joomla('#srStr').val(sr);
          $joomla('#dtStr').val(dt);
          tds+="</tr>";
          tdns+="</tr>";
        
        });
        $joomla('#ord_ship #j_table:first').append(tds);<!-- <td><p data-dismiss="modal" >Remove</p></td>-->
        $joomla('#ord_ship #k_table:last').append(tdns);
        $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);
        $joomla('input[name=cc]').filter(':radio').prop('checked',false);
        $joomla('#divShipCOstOne').html('');
        $joomla('#ChangeShippingAddressNew').html($joomla('input[name=txtbiladdress]').val());
      }
      //console.log($joomla('#tds').html());
    });
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
           },success: function(data){
             if(data.length==11){
                $joomla('#tabs1 .btn-primary').attr("disabled", false);
                $joomla('#loading-image4').each( function () {
                $joomla(this).remove();
               });
             }else{
                $joomla('#loading-image4').html("<label class='error'>"+data+"</label>");
                $joomla('#tabs1 .btn-primary').attr("disabled", true);
             }
                 
             }
		});
    });
    //momentoLogs
    $joomla('#tabs3').on('click','.getmlog',function(){
        $joomla("#momentoLogs").html('');
        var res=$joomla(this).data('id');
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&momentotype="+res +"&momentoflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "momentoid": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#momentoLogs").html('<div id="loading-image3" ><img src="/components/com_userprofile/images/loader.gif"></div>');
           },success: function(data){
              $joomla("#momentoLogs").html('<table class="table table-bordered theme_table" id="O_table"><thead><tr><th>Status</th><th>User</th><th>Date</th></tr>'+data+'</thead></table>');
        	}
		});
    });
    
    //ship_img
    $joomla('#tabs2').on('click','.ship_img',function(){
        var res=$joomla(this).data('id');
        $joomla('#viewImage').html('');
        if (!res.match(/(?:gif|jpg|png|jpeg)$/)) 
        $joomla('#viewImage').html("There is no image upload for this order");
        else
        $joomla('#viewImage').html("<img src='"+res+"' width='100%'>");
    });
    $joomla('select[name=txtHistoryStatus]').change(function(){
        var resk=$joomla(this).val();
        if(resk=='ALL'){
            $joomla('.input-sm').val('');
            $joomla('.input-sm').trigger('keyup');
        }else    
        $joomla('.input-sm').val($joomla(this).val());
        $joomla('.input-sm').trigger('keyup');
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
          txtBackCompany: {
            required: true
         },
          txtReturnAddress: {
            required: true
         },
          txtReturnCarrier: {
            required: true
         },
          txtReturnReason: {
            required: true
         },
          txtOriginalOrderNumber: {
            required: true
         },
          txtMerchantNumber: {
            required: true
         },
          txtSpecialInstructions: {
            required: true
         }
        },
        // Specify validation error messages
        messages: {
          txtBackCompany: "Please enter Back Company / Dealer",
          txtReturnAddress: "Please enter Return Address",
          txtReturnCarrier: "Please enter Return Carrier",
          txtReturnReason: "Please enter Return Reason",
          txtOriginalOrderNumber: "Please enter Original Order Number",
          txtMerchantNumber: "Please enter Merchant Number",
          txtSpecialInstructions: "Please enter Special Instructions"

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
            if($joomla("input[name=fluReturnShippingLabel]").val()!=""){
                $joomla("input[name=fluReturnShippingLabel] #errorTxt-error").html('');
                var ext = $joomla('input[name=fluReturnShippingLabel]').val().split('.').pop().toLowerCase();
                if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf']) == -1) {
                    $joomla('input[name=fluReturnShippingLabel]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please check File invalid extension!</label>');
                    return false;
                }else{
                  $joomla("input[name=fluReturnShippingLabel] #errorTxt-error").html('');    
                }
            }
          form.submit();
        }
        });    
    });
    
     $joomla(function() {
 
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormThree']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          txtReturnReason: {
            required: true
         }
        },
        // Specify validation error messages
        messages: {
          txtReturnReason: "Please enter Return Reason"
          
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
    $joomla(function() {
 
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormFour']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          txtReturnReason: {
            required: true
         }
        },
        // Specify validation error messages
        messages: {
          txtReturnReason: "Please enter Return Reason"
          
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

    $joomla(function() {
 
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormFive']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          cc: {
            required: true
          },
          ccStr: {
            required: true
          },
          cardnumberStr: {
            required: true
          },
          txtccnumberStr: {
            required: true
          },
          txtNameonCardStr: {
            required: true
          },
          MonthDropDownListStr: {
            required: true
          },
          YearDropDownListStr: {
            required: true
          },
          amtStr: {
            required: true
          }
        },
        // Specify validation error messages
        messages: {
          cc: "Select Payment Method",
          ccStr: "Select Card",
          cardnumberStr: "Enter Card Number",
          txtccnumberStr: "Enter CC Number",
          txtNameonCardStr: "Enter Name on the Card",
          MonthDropDownListStr: "Select Card expiry month",
          YearDropDownListStr: "Select Card expiry Year",
          amtStr: "Enter Amount"
          
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
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "cc") {
              //error.insertAfter(#cc);
              error.appendTo(element.parent('div').after());            
                
            }
            else if (element.attr("name") == "ccStr") {
              //error.insertAfter(#cc);
              error.appendTo(element.parent('div').after());            
                
            }
            else {
              error.insertAfter(element);
            }
        }

        
        });    
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
          txtMerchantName: {
            required: true,
            alphanumeric:true
          },
          txtOrderDate: {
            required: true
          },
          txtArticleName: {
            required: true,
            alphanumeric:true
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
          txtMerchantName: {required:"Please enter Merchant Name",alphanumeric:"Please enter alphabet characters"},
          txtOrderDate: "Please select Order date",
          txtArticleName: {required:"Please enter Artcile Name",alphanumeric:"Please enter alphabet characters"},
          txtDvalue: "Please enter Delcared Value",
          txtCarrierName: "Please enter Carrier Name",
          txtTracking: "Please enter Tracking Number",
          txtQuantity: "Please Enter Item Quantity"
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
    $joomla("input[name='btnReset']").click(function(e){
       var alt=confirm("Please confirm to Reset the form");
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
   $joomla("input[name='txtQuantity']").keyup(function(e){
    this.value = this.value.replace(/[^0-9]/g, '');
    //if (/\D/g.test(this.value))
    //this.value.replace(/[0-9]*\.?[0-9]+/g, '');  for name
    });
   $joomla("input[name='quantityTxt[]']").keyup(function(e){
    this.value = this.value.replace(/[^0-9]/g, '');
   });
   

   $joomla("input[name='txtDvalue']").keypress(function (e) {
    if(e.which == 46){
        if($joomla(this).val().indexOf('.') != -1) {
            return false;
        }
    }
    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
    });
   $joomla("input[name='declaredvalueTxt[]']").keypress(function (e) {
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
      if(validfirst.form()==true){
      var rp=$joomla(this).closest('.rows').find('div:nth-child(1) input').attr('id');
      var er=rp+1;
      
      var rp2=$joomla(this).closest('.rows').find('div:nth-child(2) input').attr('id');
      var er2=rp2+1;
      
      var rp3=$joomla(this).closest('.rows').find('div:nth-child(3) input').attr('id');
      var er3=rp3+1;
      
      var rp4=$joomla(this).closest('.rows').find('div:nth-child(4) input').attr('id');
      var er4=rp4+1;
      
      var sd=$joomla(this).closest('.rows').html().replace('id="'+rp+'"','id="'+er+'"').replace('id="'+rp2+'"','id="'+er2+'"').replace('id="'+rp3+'"','id="'+er3+'"').replace('id="'+rp4+'"','id="'+er4+'"');
      
      $joomla('<div class="row rows">'+sd+'</div>').insertAfter( $joomla(this).closest('.row') );
      $joomla('#tabs1 .rows:last').find('td:last').html('');
      $joomla('#tabs1 .rows:last').find('td:last').html('<input class="btn btn-danger btn-rem" type="button" name="deleterow" value="X">');
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
    
	$joomla('#country2Txt').on('change',function(){
		var countryID = $joomla(this).val();
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&countryid="+$joomla("#country2Txt").val() +"&stateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
					$joomla('#state2Txt').html(data);
					$joomla('#city2Txt').html('<option value="">Select City</option>'); 
				}
			});
		}
		$joomla('#state2Txt').html('<option value="">Select State</option>');
		$joomla('#city2Txt').html('<option value="">Select City</option>'); 
		$joomla('#zip2Txt').val(''); 
	});

	$joomla('#state2Txt').on('change',function(){
		var stateID = $joomla(this).val();
		if(stateID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&stateid="+$joomla("#state2Txt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
					$joomla('#city2Txt').html(data);
				}
			}); 
		}else{
			$joomla('#city2Txt').html('<option value="">Select City</option>'); 
		}
	});  
	$joomla('#addusers').on('click',function(){
	    $joomla('#divShipCOstOne').html('');
	    $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);	
	    $joomla.ajax({
	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getadduserconsigflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "useridtypes":1},
			dataType:"html",
			type: "get",
			success: function(data){
			  var dsed=data;
			  dsed=dsed.split(":");  
			  console.log("consig data:"+data);
			  $joomla("input[name='typeuserTxt']").val(dsed[0]);
			  $joomla("input[name='idTxt']").val(dsed[1]);
			}
		}); 
	});
 
 
     $joomla('#exampleModal .btn-primary:first').click(function(){
        $joomla.ajax({
	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getadduserpayflag=1&user=<?php echo $user;?>&typeuser="+$joomla("input[name='typeuserTxt']").val()+"&id="+$joomla("input[name='idTxt']").val()+"&fname="+$joomla("input[name='fnameTxt']").val()+"&lname="+$joomla("input[name='lnameTxt']").val()+"&country="+$joomla('#country2Txt').val()+"&state="+$joomla('#state2Txt').val()+"&city="+$joomla('#city2Txt').val()+"&zip="+$joomla("input[name='zipTxt']").val()+"&address="+$joomla('#addressTxt').val()+"&email="+$joomla("input[name='emailTxt']").val()+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "usertype":1},
			dataType:"html",
			type: "get",
			success: function(data){
			    if(data=="Additional Address Added Successfully"){
			        $joomla('#exampleModal').modal('toggle');
			        loadadditionalusersData();
			    }
			}
		}); 
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
                 $joomla('input[name="txtTracking"]').after('<div id="loading-image4" ><img src="/components/com_userprofile/images/loader.gif"></div>');
               },success: function(data){
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
  
});

function loadadditionalusersData(){
    $joomla(document).ready(function() {    
 	    $joomla.ajax({
	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&user=<?php echo $user;?>&getadduserdataflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "useridtypes":1},
			dataType:"html",
			type: "get",
			success: function(data){
			  $joomla('#additionalusersData').html('<select class="form-control" name="adduserlistStr">'+data+'</select>');
			  
			}
		}); 
    });
  
}
//onKeyPress="return isNumber(event)" 
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}


</script>
<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading">Invoices</div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12 tab_view"></div>
      </div>
      <div id="tabs3">
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-9 col-sm-9">&nbsp;</div>
            <div class="col-sm-3 p-0">
              <div class="form-group text-right">
                <select class="form-control" name="txtHistoryStatus">
                  <option value="">Select Option</option>
                  <option selected="selected" value="ALL">ALL</option>
                  <option value="Ship">Ship</option>
                  <option value="Return">Return</option>
                  <option value="Discard">Discard</option>
                  <option value="In Progress">In Progress</option>
                  <option value="Finished">Finished</option>
                  <option value="Received">Received</option>
                  <option value="Hold">Hold</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered theme_table" id="M_table">
              <thead>
                <tr>
                  <th>Shipping#</th>
                  <th>Warehouse Receipt</th>
                  <th>Creation Date</th>
                  <th>Article Name</th>
                  <th>Quantity</th>
                  <th>Carrier</th>
                  <th>Tracking Number</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
    $ordersHistoryView= UserprofileHelpersUserprofile::getOrdersHistoryList($user);
    foreach($ordersHistoryView as $rg){
        if($rg->Status=="Ship"){
            $status='<a data-toggle="modal" class="getsstatus" data-backdrop="static" data-keyboard="false" data-id="'.$rg->ItemId.'" data-target="#shipdetailsModal" title="momento">'.$rg->Status.'</a>';
        }else{
           $status=$rg->Status;
        }
      echo '<tr><td>'.$rg->Id.'</td><td><a data-toggle="modal" data-backdrop="static" data-keyboard="false" class="getmlog" data-id="'.$rg->BillFormNo.'" data-target="#logModal" title="momento">'.$rg->BillFormNo.'</a></td><td>'.$rg->dti_created.'</td><td>'.$rg->ItemName.'</td><td>'.$rg->Quantity.'</td><td>'.$rg->carrier_name.'</td><td>'.$rg->TrackingId.'</td><td>'.$status.'</td></tr>';
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
<div id="shipdetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">       
         <input type="button" data-dismiss="modal" value="x" class="btn-close1">       
        <h4 class="modal-title"><strong>Shipping Details</strong></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Shipping details for Inhouse</label>
                    <input type="text" name="txtShippingDetails" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Comments<span class="error">*</span></label>
                    <input type="text" name="txtComments" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Documentation Charges</label>
                    <input type="text" name="txtDocumentCharges" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Shipping cost</label>
                    <input type="text" name="txtShippingCost" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Final cost</label>
                    <input type="text" name="txtFinalCost" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Amount paid</label>
                    <input type="text" name="txtAmountPaid" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Amount payable</label>
                    <input type="text" name="txtAmountPayable" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Payment method</label>
                    <input type="text" name="txtPaymentMethod" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Transaction number</label>
                    <input type="text" name="txtTransactionNumber" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Invoice number</label>
                    <input type="text" name="txtInvoiceNumber" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Date</label>
                    <input type="text" name="txtDate" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Amount Paid</label>
                    <input type="text" name="txtAmountPaidPaid" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-center">
                  <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="logModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">       
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">       
        <h4 class="modal-title"><strong>Tracking</strong></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="momentoLogs"></div>
          </div>
        </div>
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
          <h4 class="modal-title"><strong>Additional Address</strong></h4>
      </div>
      <form name="userprofileFormSeven" id="userprofileFormSeven" method="post" action=""  enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>User Type <span class="error">*</span></label>
                <input type="text" class="form-control" name="typeuserTxt" id="typeuserTxt" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Id <span class="error">*</span></label>
                <input type="text" class="form-control"  name="idTxt" id="idTxt" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>First Name <span class="error">*</span></label>
                <input type="text" class="form-control" name="fnameTxt" id="fnameTxt">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Last name <span class="error">*</span></label>
                <input type="text" class="form-control"  name="lnameTxt" id="lnameTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Country <span class="error">*</span></label>
                <?php
					       $countryView= UserprofileHelpersUserprofile::getCountriesList();
					       $arr = json_decode($countryView); 
                           $countries='';
					       foreach($arr->Data as $rg){
					          $countries.= '<option value="'.$rg->CountryCode.'">'.$rg->CountryDesc.'</option>';
                           }
             
    					?>
                <select class="form-control" name="country2Txt" id="country2Txt">
                  <option value="0">Select Country</option>
                  <?php echo $countries;?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>State <span class="error">*</span></label>
                <select class="form-control"  name="state2Txt" id="state2Txt">
                  <option value="0">Select State</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>City <span class="error">*</span></label>
                <select class="form-control" name="city2Txt" id="city2Txt">
                  <option value="0">Select City</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Postal Code <span class="error">*</span></label>
                <input type="text" class="form-control" name="zipTxt" id="zipTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Additional Address <span class="error">*</span></label>
                <textarea type="text" class="form-control" name="addressTxt" id="addressTxt"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Email <span class="error">*</span></label>
                <input type="text" class="form-control" name="emailTxt" id="emailTxt">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="button" value="Save" class="btn btn-primary">
              <input type="button" value="Cancel" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>