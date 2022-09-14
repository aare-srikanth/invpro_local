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
$document->setTitle("Pickup Order in Boxon Pobox Software");
defined('_JEXEC') or die;
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$pass=$session->get('user_casillero_password');
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
$resWp=UserprofileHelpersUserprofile::getPickupFieldviewsList($user);
$UserView= UserprofileHelpersUserprofile::getUserprofileDetails($user);

// get cust type and menu access

$menuAccessStr=Controlbox::getMenuAccess($user,$pass);
$menuCustData = explode(":",$menuAccessStr);
$maccarr=array();
foreach($menuCustData as $menuaccess){
    
    $macess = explode(",",$menuaccess);
    $maccarr[$macess[0]]=$macess[1];
 
}
$menuCustType=end($menuCustData);

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
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function(){

    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    
    var userid='<?php echo $user;?>';
    console.log(userid);
    if($joomla( 'input[name="txtPickupDate"]' ))
    $joomla( 'input[name="txtPickupDate"]').datepicker({  minDate: new Date() });
    
    
   
    
    
    // dynamic service types on source, destination and shiptype change
    
    $joomla('select[name=txtSourceCntry],select[name=txtDestinationCntry],select[name=txtTypeOfShipperName]').on('change',function(){
         
            var shipmentType = $joomla('select[name=txtTypeOfShipperName]').val();
            var srcCountry = $joomla('#txtSourceCntry').val(); 
            var destCountry = $joomla('#txtDestinationCntry').val();
            
            var urls="<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&destination="+ destCountry +"&source="+ srcCountry +"&shiptype="+ shipmentType +"&user=<?php echo $user;?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime();
            
            $joomla.ajax({
            	url: urls,
            	data: { "shippmenttypecalcflag": '1' },
            	dataType:"html",
            	type: "get",
                beforeSend: function() {
                },
                success: function(data){
                  $joomla('select[name=txtServiceType]').html(data);
              }
            }); 
        
    });
    
    
    $joomla('select[name="txtShipperName"]').change(function(){
        var shipvb=$joomla(this).val();
        shipvb=shipvb.split(":");
        $joomla(".page_loader").slideToggle("slow",function callback() {
        $joomla('input[name="hiddenShipperNameId"]').val(shipvb[0]);
        $joomla('input[name="txtShipperAddress"]').val(shipvb[1]);
        $joomla('input[name="hiddenShipperName"]').val($joomla(this).find(":selected").text());
        $joomla(".page_loader").slideToggle("slow");
         });
         
    })
    $joomla('select[name="txtConsigneeName"]').change(function(){
        var convb=$joomla(this).val();
        convb=convb.split(":");
         $joomla(".page_loader").slideToggle("slow",function callback() {
        $joomla('input[name="hiddenConsigneeId"]').val(convb[0]);
        $joomla('input[name="txtConsigneeAddress"]').val(convb[1]);
        $joomla('input[name="hiddenConsignee"]').val($joomla(this).find(":selected").text());
        $joomla(".page_loader").slideToggle("slow");
         });
        
    })
    $joomla('select[name="txtThirdPartyName"]').change(function(){
        var thirdvb=$joomla(this).val();
        thirdvb=thirdvb.split(":");
         $joomla(".page_loader").slideToggle("slow",function callback() {
        $joomla('input[name="hiddenThirdPartyId"]').val(thirdvb[0]);
        $joomla('input[name="txtThirdPartyAddress"]').val(thirdvb[1]);
        $joomla('input[name="hiddenThirdParty"]').val($joomla(this).find(":selected").text());
        $joomla(".page_loader").slideToggle("slow");
         });
        
    })


    $joomla('select[name="txtTypeOfShipperName"]').change(function(){
        $joomla('select[name="txtServiceType"]').empty();
        var resServ='<?php foreach($resWp->ServiceType_List as $key=>$row){echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';}?>';
        if($joomla(this).val()=="")
        $joomla('select[name="txtServiceType"]').append('<option value="">Select</option>');
        else
        $joomla('select[name="txtServiceType"]').append('<option value="">Select</option>'+resServ);
    });
    $joomla('#gtable').on('change','select[name="txtPackageList[]"]',function(e){
        e.preventDefault();
        var ths=$joomla(this).closest('tr').parent().index();
        var resVal=$joomla(this).val();
        resVal=resVal.split(":");
        if(resVal)
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getpackagetype="+resVal[0] +"&getpackageflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "getpackagetype": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla(".page_loader").show();
              $joomla(':input[type="submit"]').prop('disabled', true);
           },success: function(data){
              $joomla(":button").attr("disabled", false); 
              //$joomla(':input[type="submit"]').prop('disabled', false);
              
              $joomla(".page_loader").hide();
              var cospor=data;
		      cospor=cospor.split(":");
		      console.log(ths);
		      $joomla('#gtable tr').eq(ths).find('#txtLength').val(cospor[0]);
		      $joomla('#gtable tr').eq(ths).find('#txtWidth').val(cospor[1]);
		      $joomla('#gtable tr').eq(ths).find('#txtHeight').val(cospor[2]);
            }
		});
    });    
    $joomla('#gtable').on('click','input[name="addrow"]',function(e){
        if($joomla("form[name='userprofileFormOne']").valid()==true){
          var iteid=$joomla(this).closest('tr').find('td:first input').attr('id');
          var comid=$joomla(this).closest('tr').find('td:nth-child(3) select').attr('id');
          var lenid=$joomla(this).closest('tr').find('td:nth-child(5) input').attr('id');
          var widid=$joomla(this).closest('tr').find('td:nth-child(6) input').attr('id');
          var heiid=$joomla(this).closest('tr').find('td:nth-child(7) input').attr('id');
          var ip=$joomla(this).closest('tr').find('td:first input').attr('id');
          var ir=ip+4;
          var rp=$joomla(this).closest('tr').find('td:nth-child(2) select').attr('id');
          var er=rp+1;
          var rrp=$joomla(this).closest('tr').find('td:nth-child(4) input').attr('id');
          var rer=rrp+2;
          var rrrp=$joomla(this).closest('tr').find('td:nth-child(8) input').attr('id');
          var rrer=rrrp+3;
          var text = '';
          text=$joomla(this).closest('tr').html().replace('id="'+ip+'"','id="'+ir+'"').replace('id="'+rp+'"','id="'+er+'"').replace('id="'+rrp+'"','id="'+rer+'"').replace('id="'+rrrp+'"','id="'+rrer+'"');
          $joomla(this).closest('tr').find('td:last').html('<input class="title btn btn-danger btn-rem" type="button" name="deleterow" value="X">');
          $joomla("#gtable").append('<tbody><tr>'+text+'</tr></tbody>');


          
          var pkg=$joomla('#'+rp).val();
          $joomla("#gtable").find('tr:last td:nth-child(2) select').val(pkg);
          
          var commid=$joomla('#'+comid).val();
          $joomla("#gtable").find('tr:last td:nth-child(3) select').val(commid);
          
          var qt=$joomla('#'+rrp).val();
          $joomla("#gtable").find('tr:last td:nth-child(4) input').val(qt);
          
          var legnid=$joomla('#'+lenid).val();
          $joomla("#gtable").find('tr:last td:nth-child(5) input').val(legnid);
          var widtid=$joomla('#'+widid).val();
          $joomla("#gtable").find('tr:last td:nth-child(6) input').val(widtid);
          
          var heitid=$joomla('#'+heiid).val();
          $joomla("#gtable").find('tr:last td:nth-child(7) input').val(heitid);
          
          var gsw=$joomla('#'+rrrp).val();
          $joomla("#gtable").find('tr:last td:nth-child(8) input').val(gsw);

        }
            
    });    
    $joomla('#gtable').on('click','input[name="deleterow"]',function(e){
      if($joomla('#gtable tr').length>2){    
       var lastone=$joomla('#gtable tr:last td:last').html();
        $joomla(this).closest('tbody').remove();
        $joomla('#gtable tr:last td:last').html('');
        $joomla('#gtable tr:last td:last').html(lastone);
       }    
       else
      alert('Minimum One Row Required');
    });  

    $joomla(".form-control").change(function() { 
        $joomla('input[name=txtquotationCost]').val('');
    });
    


    $joomla('input[name="btnCalculate"]').click(function(e){
      e.preventDefault();
      validation();
      if($joomla("form[name='userprofileFormOne']").valid()==true && $joomla('input[name=txtquotationCost]').val()==0){
    
        $joomla.ajax({
            url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatetype=1&userid=<?php echo $user;?>&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+$joomla('select[name="txtTypeOfShipperName"]').val() +"&stype="+$joomla('select[name="txtServiceType"]').val() +"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLengthIds"]').val() +"&width="+$joomla('input[name="txtWidthIds"]').val() +"&height="+$joomla('input[name="txtHeightIds"]').val() +"&qty="+$joomla('input[name="txtQuantityIds"]').val() +"&gwt="+$joomla('input[name="txtWeightIds"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val()+"&bustype="+$joomla('select[name="txtBusinessType"]').val()+"&getcalculateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
            data: { "getpackagetype": $joomla(this).data('id') },
            dataType:"html",
            type: "get",
            beforeSend: function() {
              $joomla(".page_loader").show();
              $joomla(":button").attr("disabled", true); 
              $joomla(':input[type="submit"]').prop('disabled', true);
            },success: function(data){

              $joomla(":button").attr("disabled", false); 
              console.log(data);
              $joomla(".page_loader").hide(); 
              var cospor=data;
              cospor=cospor.split(":");
              $joomla('#divApiResult').html('');
              $joomla('#divquotationCost').html('');
              if(cospor[3]>0){
                 $joomla(':input[type="submit"]').prop('disabled', false); 
                 $joomla('input[name=txtRatetypeIds]').val(cospor[0]);
                 //console.log("Valuem:"+cospor[1]);
                 var gw=cospor[1];
                 gw=gw.split(",");
                 $joomla.each( gw, function( key, value ) {
                    var ift=key+1;
                    var subStr = value.substring(0, 4);
                    $joomla('#gtable tr').eq(ift).find('#divVolumeMultiple').html(subStr);
                 });
                 $joomla('input[name=txtVolumeMultiple]').val(cospor[1]);
                 var vw=cospor[2];
                 vw=vw.split(",");
                 $joomla.each( vw, function( key, value ) {
                    var vift=key+1;
                    var subStrs = value.substring(0, 5);
                    $joomla('#gtable tr').eq(vift).find('#divVolWtMultiple').html(subStrs);
                 });
                 $joomla('input[name=txtVolWtMultiple]').val(cospor[2]);
                 
                 $joomla('input[name=txtquotationCost]').val(cospor[3]);
                 $joomla('#divquotationCost').html('');
                 $joomla('#divquotationCost').html('$'+cospor[3]);
                 $joomla('input[name=txtIdServ]').val(cospor[4]);
                 $joomla('input[name=txtIdRate]').val(cospor[5]);
                 $joomla('#divdiscountCost').html('');
                 $joomla('#divdiscountCost').html('$'+cospor[6]);
                 if(cospor[6]==""){
                     cospor[6]="0.00";
                 }
                  $joomla('#divdiscountCost').html('$'+cospor[6]);
                  $joomla('input[name=txtDiscount]').val(cospor[6]);

                 var quotcost=0;
                 quotcost=cospor[3];
                 $joomla('input[name=txtquotationCost]').val(quotcost);

                 var adds=0;
                 adds=cospor[7];
                 adds = adds.split(',');
                 var sum = 0;
                 for(j=0;j<adds.length;j++){
                   console.log(parseFloat(adds[j]));
                   if(isNaN(parseFloat(adds[j])))
                   sum += 0;
                   else
                   sum += parseFloat(adds[j]);
                 }
                 if(isNaN(sum)){
                   sum='0.00';
                 }
                 $joomla('input[name=txtAdditionalServices]').val(cospor[7]);
                 $joomla('#divadditionalCost').html('');
                 $joomla('#divadditionalCost').html('$'+sum.toFixed(2));                
                 
                 var dgw=cospor[8];
                 dgw=dgw.split(",");
                 $joomla.each( dgw, function( key, value ) {
                    var vdgw=key+1;
                    $joomla('#gtable tr').eq(vdgw).find('#divGrossWeight').html(value);
                 });
                 $joomla('input[name=txtGrossWeight]').val(cospor[8]);
                 $joomla('#divfinalCost').html('');
                 var fico=(parseFloat(quotcost)+parseFloat(sum))-parseFloat(cospor[6]);
                 $joomla('#divfinalCost').html('$'+fico.toFixed(2));
                 $joomla('input[name=txtfinalCost]').val(fico);
                 $joomla('input[name=txtAdditionalServicesId]').val(cospor[9]); 
                 $joomla('input[name=txtitemCost]').val(cospor[10]); 
                  
              }else{
                //$joomla('#divApiResult').html('<div class="col-sm-12"><label class="error">'+data+'</label></div>');
                $joomla('#divApiResult').html('<div class="col-sm-12"><label class="error">Service is not assigned to selected source and destination</label></div>');
                $joomla('#divadditionalCost').html('');
                $joomla('#divdiscountCost').html('');
                $joomla('#divfinalCost').html('');
              }
              console.log(data);
              return false;
            }
    	});
        }else{
           //$joomla('input[name="submit"]').click();
        }
    });  
    

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
          txtShipperName: {
            required: true
          },
          txtConsigneeName: {
            required: true
          },
          txtThirdPartyName: {
            required: true
          },
          txtTypeOfShipperName: {
            required: true
          },
          txtServiceType: {
            required: true
          },
          txtSourceCntry: {
            required: true
          },
          txtDestinationCntry: {
            required: true
          },
          txtMeasurementUnits: {
            required: true
          },
          txtWeightUnits: {
            required: true
         },
         txtChargableWeight: {
            required: true
         },     
         "txtItemName[]": "required",
         "txtPackageList[]": "required",
         "txtQuantity[]": "required",
         "txtWeight[]": "required"
        },
        errorPlacement: function (error, element) {
           
            if (element.attr("type") == "radio") {
                error.appendTo(".address_error");
            } 
        },
        messages: {  // Specify validation error messages
          txtShipperName:"<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_SHIPPER_NAME")  ?>",
          txtConsigneeName:"<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_CONSIGNEE_NAME")  ?>",
          txtThirdPartyName:"<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_THIRD_PARTY_NAME")  ?>",
          txtTypeOfShipperName: "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_SHPMENT_TYPE")  ?>",
          txtServiceType: "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_SERVICE_TYPE")  ?>",
          txtSourceCntry: "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_SOURCE_COUNTRY")  ?>",
          txtDestinationCntry: "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_DESTINATION_COUNTRY")  ?>",
          txtMeasurementUnits: "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_MEASUREMENT_UNITS")  ?>",
          txtWeightUnits: "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_WEIGHT_UNITS")  ?>",
          txtChargableWeight:"<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_PICKUP_INFO")  ?>",
          "txtItemName[]": "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_ITEM_NAME")  ?>",
          "txtPackageList[]": "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_PACKAGE")  ?>",
          "txtQuantity[]": "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_QUANTITY")  ?>",
          "txtWeight[]": "<?php echo Jtext::_("COM_USERPROFILE_ORDER_PICKUP_SELECT_WEIGHT")  ?>"
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
                if($joomla('input[name=txtquotationCost]').val()==0)
                {
                    validation();
                    var testing = false;
                    $joomla.ajax({
            			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatetype=1&userid=<?php echo $user;?>&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+$joomla('select[name="txtTypeOfShipperName"]').val() +"&stype="+$joomla('select[name="txtServiceType"]').val() +"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLengthIds"]').val() +"&width="+$joomla('input[name="txtWidthIds"]').val() +"&height="+$joomla('input[name="txtHeightIds"]').val() +"&qty="+$joomla('input[name="txtQuantityIds"]').val() +"&gwt="+$joomla('input[name="txtWeightIds"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val()+"&getcalculateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
            			data: { "getpackagetype": $joomla(this).data('id') },
            			dataType:"html",
            			type: "get",
            			async: false,
            			beforeSend: function() {
            			  $joomla(':input[type="submit"]').prop('disabled', true);  
                          $joomla(".page_loader").show();
                          $joomla(":button").attr("disabled", true); 
                       },success: function(data){
                          $joomla(':input[type="submit"]').prop('disabled', false);    
                          $joomla(":button").attr("disabled", false); 
                          console.log(data);
                          $joomla(".page_loader").hide(); 
                          var cospor=data;
                          cospor=cospor.split(":");
                          $joomla('#divApiResult').html('');
                          $joomla('#divquotationCost').html('');
                          if(cospor[3]>0){
                             $joomla('input[name=txtRatetypeIds]').val(cospor[0]);
                             //console.log("Valuem:"+cospor[1]);
                             var gw=cospor[1];
                             gw=gw.split(",");
                             $joomla.each( gw, function( key, value ) {
                                var ift=key+1;
                                var subStr = value.substring(0, 4);
                                $joomla('#gtable tr').eq(ift).find('#divVolumeMultiple').html(subStr);
                             });
                             $joomla('input[name=txtVolumeMultiple]').val(cospor[1]);
                             var vw=cospor[2];
                             vw=vw.split(",");
                             $joomla.each( vw, function( key, value ) {
                                var vift=key+1;
                                var subStrs = value.substring(0, 5);
                                $joomla('#gtable tr').eq(vift).find('#divVolWtMultiple').html(subStrs);
                             });
                             $joomla('input[name=txtVolWtMultiple]').val(cospor[2]);
                             
                             $joomla('input[name=txtquotationCost]').val(cospor[3]);
                             $joomla('#divquotationCost').html('');
                             $joomla('#divquotationCost').html('$'+cospor[3]);
                             $joomla('input[name=txtIdServ]').val(cospor[4]);
                             $joomla('input[name=txtIdRate]').val(cospor[5]);
                             $joomla('#divdiscountCost').html('');
                             
                            if(cospor[6]==""){
                            cospor[6]="0.00";
                            }
                            $joomla('#divdiscountCost').html('$'+cospor[6]);
                             $joomla('input[name=txtDiscount]').val(cospor[6]);
                             

                             var quotcost=0;
                             quotcost=cospor[3];
                             $joomla('input[name=txtquotationCost]').val(quotcost);
            
                             var adds=0;
                             adds=cospor[7];
                             adds = adds.split(',');
                             var sum = 0;
                             for(j=0;j<adds.length;j++){
                               console.log(parseFloat(adds[j]));
                               if(isNaN(parseFloat(adds[j])))
                               sum += 0;
                               else
                               sum += parseFloat(adds[j]);
                             }
                             if(isNaN(sum)){
                               sum='0.00';
                             }
                             $joomla('input[name=txtAdditionalServices]').val(cospor[7]);
                             $joomla('#divadditionalCost').html('');
                             $joomla('#divadditionalCost').html('$'+sum.toFixed(2));                
                             
                             var dgw=cospor[8];
                             dgw=dgw.split(",");
                             $joomla.each( dgw, function( key, value ) {
                                var vdgw=key+1;
                                $joomla('#gtable tr').eq(vdgw).find('#divGrossWeight').html(value);
                             });
                             $joomla('input[name=txtGrossWeight]').val(cospor[8]);
                             $joomla('#divfinalCost').html('');
                             var fico=(parseFloat(quotcost)+parseFloat(sum))-parseFloat(cospor[6]);
                             $joomla('#divfinalCost').html('$'+fico.toFixed(2));
                             $joomla('input[name=txtfinalCost]').val(fico);
                             $joomla('input[name=txtAdditionalServicesId]').val(cospor[9]);
                             $joomla('input[name=txtitemCost]').val(cospor[10]);
                             testing = true;
                          }else{
                            $joomla('#divApiResult').html('<div class="col-sm-12"><label class="error">'+data+'</label></div>');
                            return false;
                          }
                          console.log(data);
                          return true;
                       }
            		});
            		return testing;
            	
                }else{
                    return true;
                }
            }
        });    
    });
    
    //validation for all fields
	$joomla.validator.addMethod(
	"alphanumeric", 
	function(value, element) {
			return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
		},
		"<?php echo JText::_('ENTER_THE_NAME_ALPHANUMARIC_CHARACTORES');?>"
	);
    
	// Initialize form validation on the registration form.
	// It has the name attribute "registration"
	$joomla("form[name='userprofileFormTwo']").validate({
		
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
            countryTxt: {
             required: true
            },
            stateTxt: {
             required: true
            },
            cityTxt: {
             required: true
            },
            addressTxt: "required",
            zipTxt: {
              required: true,
              minlength: 5,
              number: true
            }
        },
		// Specify validation error messages
		messages: {
			fnameTxt:{
			    required: "Please enter first name",
			    alphanumeric: "please enter alphabet characters"
			},
			lnameTxt:{
			    required: "Please enter lasst name",
			    alphanumeric: "please enter alphabet characters"
			},
            countryTxt: {
             required: "Please select country"
            },
            stateTxt: {
             required: "Please select state"
            },
            cityTxt: {
             required: "Please select city"
            },
            addressTxt:{
              required: "Please enter address"
            },
            zipTxt:{
              required: "Please enter zip code"
            }
            
		},
		// Make sure the form is submitted to the destination defined
		// in the "action" attribute of the form when valid
		submitHandler: function(form) {
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
		  form.submit();
		}
	});
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
            country2Txt: {
             required: true
            
            },
            state2Txt: {
             required: true
            
            },
            city2Txt: {
             required: true
            
            },
            addressTxt: "required",
            zipTxt: {
              required: true,
              minlength: 5,
              number: true
            }
        },
		// Specify validation error messages
		messages: {
			fnameTxt:{
			    required: "Please enter first name",
			    alphanumeric: "please enter alphabet characters"
			},
			lnameTxt:{
			    required: "Please enter lasst name",
			    alphanumeric: "please enter alphabet characters"
			},
            country2Txt: {
             required: "Please select country"
             
            },
            state2Txt: {
             required: "Please select state"
            
            },
            city2Txt: {
             required: "Please select city"
             
            },
            addressTxt:{
              required: "Please enter address"
            },
            zipTxt:{
              required: "Please enter zip code"
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
	// Initialize form validation on the registration form.
	// It has the name attribute "registration"
	$joomla("form[name='userprofileFormFour']").validate({
		
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
             required: true
            },
            state3Txt: {
             required: true
            },
            city3Txt: {
             required: true
            },
            addressTxt: "required",
            zipTxt: {
              required: true,
              minlength: 5,
              number: true
            }
        },
		// Specify validation error messages
		messages: {
			fnameTxt:{
			    required: "Please enter first name",
			    alphanumeric: "please enter alphabet characters"
			},
			lnameTxt:{
			    required: "Please enter lasst name",
			    alphanumeric: "please enter alphabet characters"
			},
            country3Txt: {
             required: "Please select country"
            },
            state3Txt: {
             required: "Please select state"
            },
            city3Txt: {
             required: "Please select city"
            },
            addressTxt:{
              required: "Please enter address"
            },
            zipTxt:{
              required: "Please enter zip code"
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
 	$joomla('#countryTxt').on('change',function(){
 	    var countryID = $joomla(this).val();
		if(countryID!="0"){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#countryTxt").val() +"&stateflagpickup=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				    $joomla(".page_loader").hide();
					$joomla('#stateTxt').html(data);
					$joomla('#cityTxt').html('<option value="">Select City</option>'); 
				}
			});
		}else{
			$joomla('#stateTxt').html('<option value="">Select State</option>');
			$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		}
	});

	$joomla('#stateTxt').on('change',function(){
		var stateID = $joomla(this).val();
		if(stateID!="0"){
		    $joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#stateTxt").val() +"&cityflagpickup=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
					beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				    $joomla(".page_loader").hide();
					$joomla('#cityTxt').html(data);
				}
			}); 
		}else{
			$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		}
	});   
 	$joomla('#country2Txt').on('change',function(){
		var countryID = $joomla(this).val();
        if(countryID!="0"){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#country2Txt").val() +"&stateflagpickup=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#country2Txt").val() },
				dataType:"html",
				type: "get",
					beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				    $joomla(".page_loader").hide();
					$joomla('#state2Txt').html(data);
					$joomla('#city2Txt').html('<option value="">Select City</option>'); 
				}
			});
		}else{
			$joomla('#state2Txt').html('<option value="">Select State</option>');
			$joomla('#city2Txt').html('<option value="">Select City</option>'); 
		}
	});

	$joomla('#state2Txt').on('change',function(){
		var state2ID = $joomla(this).val();
		if(state2ID!="0"){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#state2Txt").val() +"&cityflagpickup=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#country2Txt").val() },
				dataType:"html",
				type: "get",
					beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				     $joomla(".page_loader").hide();
					$joomla('#city2Txt').html(data);
				}
			}); 
		}else{
			$joomla('#city2Txt').html('<option value="">Select City</option>'); 
		}
	});   
 	$joomla('#country3Txt').on('change',function(){
 	    var countryID = $joomla(this).val();
		if(countryID!="0"){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#country3Txt").val() +"&stateflagpickup=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#country3Txt").val() },
				dataType:"html",
				type: "get",
					beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				     $joomla(".page_loader").hide();
					$joomla('#state3Txt').html(data);
					$joomla('#city3Txt').html('<option value="">Select City</option>'); 
				}
			});
		}else{
			$joomla('#state3Txt').html('<option value="">Select State</option>');
			$joomla('#city3Txt').html('<option value="">Select City</option>'); 
		}
	});

	$joomla('#state3Txt').on('change',function(){
		var stateID = $joomla(this).val();
		if(stateID!="0"){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&stateid="+$joomla("#state3Txt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#country3Txt").val() },
				dataType:"html",
				type: "get",
					beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				    $joomla(".page_loader").hide();
					$joomla('#city3Txt').html(data);
				}
			}); 
		}else{
			$joomla('#city3Txt').html('<option value="">Select City</option>'); 
		}
	});   
    $joomla('input[name=txtChargableWeight]').click(function(){
        $joomla(".page_loader").slideToggle("slow",function callback(){
            $joomla('input[name="txtName"]').val('');
            $joomla('textarea[name="txtPickupAddress"]').val('');
            if($joomla('input[name=txtChargableWeight]').val()==1){
              $joomla('input[name="txtName"]').val('<?php echo $UserView->UserName;?>');
              $joomla('textarea[name="txtPickupAddress"]').val('<?php echo $UserView->Address.'  '.$UserView->Address2.','.$UserView->City.','.$UserView->State.','. $UserView->Country.','.$UserView->PostalCode;?>');
            }else if($joomla('input[name=txtChargableWeight]').val()==2){
              $joomla('input[name="txtName"]').val($joomla('select[name="txtShipperName"]').find(":selected").text());
              $joomla('textarea[name="txtPickupAddress"]').val($joomla('input[name="txtShipperAddress"]').val());
            }
            $joomla(".page_loader").slideToggle("slow");
        });
    });


   $joomla(document).on("keyup","input[name*='txtLength'],input[name*='txtWidth'],input[name*='txtHeight'],input[name*='txtWeight']",function(e){
    this.value = this.value.replace(/[^0-9.]/g, '');
    //if (/\D/g.test(this.value))
    });
    
    $joomla(document).on("keyup","input[name*='txtQuantity']",function(e){
    this.value = this.value.replace(/[^0-9]/g, '');
    //if (/\D/g.test(this.value))
    });
    
    
    $joomla("input[name='txtWeight']").keyup(function(e){
    this.value = this.value.replace(/[^0-9/.]/g, '');
    //if (/\D/g.test(this.value))
    });
    $joomla("input[name='txtLength']").keyup(function(e){
    this.value = this.value.replace(/[^0-9/.]/g, '');
    //if (/\D/g.test(this.value))
    });
    $joomla("input[name='txtWidth']").keyup(function(e){
    this.value = this.value.replace(/[^0-9/.]/g, '');
    //if (/\D/g.test(this.value))
    });
    $joomla("input[name='txtHeight']").keyup(function(e){
    this.value = this.value.replace(/[^0-9/.]/g, '');
    //if (/\D/g.test(this.value))
    });


   
       
    
});
function validation(){
         var tqty='';
        
        $joomla('input[name="txtQuantity[]"]').each(function(){
            if($joomla(this).val()>=1)
            tqty += $joomla(this).val()+',';
        });    
        console.log("tqty:"+tqty);
        var twt='';
        $joomla('input[name="txtWeight[]"]').each(function(){
            if($joomla(this).val()>=1)
            twt += $joomla(this).val()+',';
        });
        var twd='';
        $joomla("#gtable tr").each(function(){
          if($joomla(this).find('#txtWidth').val()==""){
          }else{
            if($joomla(this).find('#txtWidth').val()>=1)
            twd += $joomla(this).find('#txtWidth').val()+',';
          }
        });
        var tht='';
        $joomla("#gtable tr").each(function(){
          if($joomla(this).find('#txtHeight').val()==""){
          }else{
            if($joomla(this).find('#txtHeight').val()>=1)
            tht += $joomla(this).find('#txtHeight').val()+',';
          }
        });
        var tlt='';
        $joomla("#gtable tr").each(function(){
          if($joomla(this).find('#txtLength').val()==""){
          }else{
            if($joomla(this).find('#txtLength').val()>=1)
            tlt += $joomla(this).find('#txtLength').val()+',';
          }
        });
        $joomla('input[name="txtQuantityIds"]').val(tqty);    
        $joomla('input[name="txtWeightIds"]').val(twt);    
        $joomla('input[name="txtWidthIds"]').val(twd);    
        $joomla('input[name="txtHeightIds"]').val(tht);    
        $joomla('input[name="txtLengthIds"]').val(tlt); 
        
        return true;   
}
</script>

<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_TITLE')  ?></div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12">
          <h4 class="sub_title"><strong><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_HEADING1')  ?> : <?php echo $resWp->PickUpOrder_Id;?></strong></h4>
        </div>
        <div class="col-sm-12 text-right">
          <p><?php echo Jtext::_('COM_USERPROFILE_ORDER_REQUIRED_FIELDS')  ?></p>
        </div>
       
      </div>
      <form name="userprofileFormOne" id="userprofileFormOne" method="post" action="">
        <div class="row">
          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label><?php echo $assArr['shipper_Name'];  ?> <span class="error">*</span></label>
              <select class="form-control" name="txtShipperName">
                <option value="">Select</option>
                <?php
                    foreach($resWp->Shipper_List as $key=>$row){
                        if($row->Name)
                        echo '<option value="'.$row->UserId.':'.$row->Address.'">'.$row->Name.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label><?php echo $assArr['shipper_Address']  ?> <span class="error">*</span></label>
              <input type="text" class="form-control" name="txtShipperAddress">
            </div>
          </div>
          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label>&nbsp;</label>
              <input type="button" class="btn btn-primary btn-block" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ADD_NEW')  ?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#exampleModal">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label><?php echo $assArr['consignee_Name']  ?> <span class="error">*</span></label>
              <select class="form-control" name="txtConsigneeName">
                <option value="">Select</option>
                <?php
                    foreach($resWp->Consignee_List as $key=>$row){
                        if($row->Name)
                        echo '<option value="'.$row->UserId.':'.$row->Address.'">'.$row->Name.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label><?php echo $assArr['consignee_Address'];  ?> <span class="error">*</span></label>
              <input type="text" class="form-control"  name="txtConsigneeAddress">
            </div>
          </div>
          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label>&nbsp;</label>
              <input type="button" class="btn btn-primary btn-block" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ADD_NEW')  ?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#exampleModal1">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label><?php echo $assArr['third_Party_Name']  ?> </label>
              <select class="form-control" name="txtThirdPartyName">
                <option value="">Select</option>
                <?php
                    foreach($resWp->ThirdParty_List as $key=>$row){
                        if($row->Name)
                        echo '<option value="'.$row->UserId.':'.$row->Address.'">'.$row->Name.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label><?php echo $assArr['third_Party_Address'];  ?></label>
              <input type="text" class="form-control" name="txtThirdPartyAddress">
            </div>
          </div>

          <div class="col-sm-4 col-md-4">
            <div class="form-group">
              <label>&nbsp;</label>
              <input type="button" class="btn btn-primary btn-block" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ADD_NEW')  ?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#exampleModal2">
            </div>
          </div>
        </div>
        <div class="row">
              <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <label><?php echo $assArr['source_Country']; ?> <span class="error">*</span></label>
              <select class="form-control" id="txtSourceCntry"  name="txtSourceCntry">  
                <option value="">Select</option>
                <?php
                    foreach($resWp->SourceCntry_List as $key=>$row){
                        echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <!--	<label class="invisable d-block">&nbsp;</label>-->
              <label><?php echo $assArr['destination_country']; ?>:&nbsp;<span class="error">*</span></label>
              <select class="form-control"  name="txtDestinationCntry" id="txtDestinationCntry">
                <option value="">Select</option>
                <?php
                    foreach($resWp->DestinationCntry_List as $key=>$row){
                        echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>
        
        </div>
        <div class="row">            
		  <div class="col-sm-3 col-md-3">
            <div class="form-group">
              <label><?php echo $assArr['destination_country'];  ?> <span class="error">*</span></label>
              <select class="form-control" name="txtTypeOfShipperName">
                <option value="">Select</option>
                <?php
                    foreach($resWp->Shipment_List as $key=>$row){
                        echo '<option value="'.strtoupper($row->id_values).'">'.$row->desc_vals.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="col-sm-3 col-md-3">
            <div class="form-group">
              <label><?php echo $assArr['type_of_shipment'];  ?> <span class="error">*</span></label>
              <select class="form-control"  name="txtServiceType">
                <option value="">Select</option>
                <?php
                    foreach($resWp->ServiceType_List as $key=>$row){
                        echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>        
          <div class="col-sm-3 col-md-3">
            <div class="form-group">
              <!--<label class="invisable d-block">&nbsp;</label>-->
              <label><?php echo $assArr['weight_Units'];  ?>:&nbsp;<span class="error">*</span></label>
              <select class="form-control"  name="txtWeightUnits">
                <option value="">Select</option>
                <?php
                    foreach($resWp->WeightUnits_List as $key=>$row){
                        echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="col-sm-3 col-md-3">
            <div class="form-group">
              <label><?php echo $assArr['measurement_Units'];  ?> <span class="error">*</span></label>
              <select class="form-control"  name="txtMeasurementUnits">
                <option value="">Select</option>
                <?php
                    foreach($resWp->MeasurementUnits_List as $key=>$row){
                        echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';
                    }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <h4 class="sub_title"><strong><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_INFO')  ?></strong></h4>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <div class="rdo_cust">
                <div class="rdo_rd1">
                  <input type="radio" name="txtChargableWeight" value=1>
                  <label><?php echo $assArr['same_as_Customer'];  ?></label>
                  <input type="radio" name="txtChargableWeight" value=2 required>
                  <label><?php echo $assArr['same_as_Shipper'];  ?></label>
                </div>
                <div class="address_error"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <label><?php echo $assArr['shipper_Name'];  ?></label>
              <input class="form-control" type="text" name="txtName">
            </div>  
          </div>  
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <label><?php echo $assArr['pickup_Date']; ?></label>
              <input class="form-control" type="text" name="txtPickupDate">
            </div>  
          </div>
        </div>  
        
        <div class="row">
          <div class="col-sm-6 col-md-6">
            <div class="form-group">
              <label><?php echo $assArr['pickup_Address'];  ?></label>
              <textarea class="form-control" name="txtPickupAddress"></textarea>
            </div>
          </div>
          <div class="col-sm-6 col-md-6">
            <div class="form-group">
              <label><?php echo $assArr['notes'];  ?></label>
              <textarea class="form-control" name="txtNotes"></textarea>
            </div>
          </div>
        </div>
        
        <div class="row">
        
        <?php if($menuCustType == "COMP"){ 
                $businessTypes = Controlbox::GetBusinessTypes($user);
        ?>
            <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label><?php echo Jtext::_('Business Type')  ?> </label>
               <select  class="form-control" name="txtBusinessType" id="txtBusinessType"> 
               <option value="">Select Business Type</option>
               
            <?php 
            foreach($businessTypes as $type){
                if($type->is_shown == "true"){
                        echo '<option value="'.$type->desc_vals.'">'.$type->desc_vals.'</option>';
               
                } 
            } 
            ?>
               
               </select>
            </div>
            </div>
            <?php } ?>
            
        </div>
            
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered theme_table" id="gtable">
                <thead>
                  <tr>
					<th><?php echo $assArr['item_name'];  ?></th>
					<th><?php echo $assArr['package'];  ?></th>
					<th><?php echo $assArr['quantity'];  ?> </th>
					<th><?php echo $assArr['length'];  ?> </th>
					<th><?php echo $assArr['width'];  ?> </th>
					<th><?php echo $assArr['height'];  ?></th>
					<th><?php echo $assArr['gROSS_WT/ITEM'];  ?></th>
					<th><?php echo $assArr['gROSS_WT'];  ?></th>
					<th><?php echo $assArr['vOLUME'];  ?></th>
					<th><?php echo $assArr['vOLUMETRIC_WT'];  ?></th>
				  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="text" class="form-control" name="txtItemName[]"  maxlength="25" id="4">
                    </td>
                    <td><select class="form-control" name="txtPackageList[]"  id="1">
                        <option value="">Select</option>
                        <?php
			                    foreach($resWp->Package_List as $key=>$row){
			                        echo '<option value="'.$row->id_values.':'.$row->desc_vals.'">'.$row->desc_vals.'</option>';
			                    }
			                ?>
                      </select></td>
                    <td><input type="text" class="form-control" name="txtQuantity[]" maxlength="3" id="2"><div id="errsQuantity"></div> </td>
			            <td><input type="text" class="form-control" name="txtLength[]" maxlength="6" id="txtLength" >  </td>
			            <td><input type="text" class="form-control" name="txtWidth[]" maxlength="6" id="txtWidth" >  </td>
			            <td><input type="text" class="form-control" name="txtHeight[]" maxlength="6" id="txtHeight" >  </td>
			            <td><input type="text" class="form-control" name="txtWeight[]" id="3" maxlength="10"><div id="errsWeight"></div>  </td>
			            <td><div id="divGrossWeight"></div></td>
			            <td><div id="divVolumeMultiple"></div></td>
			            <td><div id="divVolWtMultiple"></div>  </td>
			            <!--<td>
                        	<input type="button" name="addrow" value="+" class="btn btn-primary btn-add"> 
                        	<input type="button" name="deleterow" value="x" class="btn btn-danger btn-rem">
                        </td>-->
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        
        <div id="divApiResult"></div>
        
        <div class="shp-blk1 quot-shiprates">
			<table width="100%" class="table">
				<tr>
					 <td><p class="shpprice"><label><?php echo Jtext::_('COM_USERPROFILE_ORDER_TABLE_SHIPPING_COST')  ?> </label><span id="divquotationCost" >$0.00</span> </p></td>
					 <td><p class="shpprice"><label><?php echo Jtext::_('COM_USERPROFILE_ORDER_TABLE_ADDITIONAL_COST')  ?> </label><span id="divadditionalCost" >$0.00</span> </p></td>
					 <td><p class="shpprice"><label><?php echo Jtext::_('COM_USERPROFILE_ORDER_TABLE_DISCOUNT')  ?> </label><span id="divdiscountCost" >$0.00</span></p></td>
					 <td><p class="shpprice"><label><?php echo Jtext::_('COM_USERPROFILE_ORDER_TABLE_FINAL_COST')  ?> </label><span id="divfinalCost" >$0.00</span> </p></td>
				</tr>
			</table>							
			<div id="loading-image" style="display:none" ><img src="/components/com_userprofile/images/loader.gif"></div>               
			<div class="clearfix"></div>
		</div>
        
        
        <div class="row">
          <div class="col-sm-12 col-md-12 text-center">
             <input type="hidden" name="hiddenShipperNameId">
             <input type="hidden" name="hiddenConsigneeId">
             <input type="hidden" name="hiddenThirdPartyId">
             <input type="hidden" name="hiddenShipperName">
             <input type="hidden" name="hiddenConsignee">
             <input type="hidden" name="hiddenThirdParty">
             
            <input type="hidden" class="form-control" name="txtAdditionalServices">
		    <input type="hidden" class="form-control" name="txtGrossWeight">
			
            <input type="hidden" class="form-control" name="txtQuantityIds">
		    <input type="hidden" class="form-control" name="txtLengthIds">
		    <input type="hidden" class="form-control" name="txtWidthIds">
		    <input type="hidden" class="form-control" name="txtHeightIds">
		    <input type="hidden" class="form-control" name="txtWeightIds">
		    
     	    <input type="hidden" class="form-control" name="txtRatetypeIds">
            <input type="hidden" class="form-control" name="txtVolumeMultiple">
            <input type="hidden" class="form-control" name="txtVolWtMultiple">
            <input type="hidden" class="form-control" name="txtDiscount">
            
            <input type="hidden" class="form-control" name="txtfinalCost">
            <input type="hidden" class="form-control" name="txtquotationCost">
		    <input type="hidden" class="form-control" name="txtAdditionalServicesId">
		    <input type="hidden" class="form-control" name="txtitemCost">

            
            <input type="hidden" class="form-control" name="txtIdServ">
            <input type="hidden" class="form-control" name="txtRateId">
            <input type="button" name="btnCalculate" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_CALCULATE')  ?>" class="btn btn-primary" disabled>
            <input type="submit" name="submit" disabled="true" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_SUBMIT')  ?>" class="btn btn-primary">
          </div>
        </div>
        
        <input type="hidden" name="task" value="user.insertPickup">
        <input type="hidden" name="id" value="0" />
        <input type="hidden" name="quotid" value="<?php echo $resWp->PickUpOrder_Id;?>" />
        <input type="hidden" name="user" value="<?php echo $user;?>" />
      </form>
    </div>
  </div>
</div>




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <input type="button" data-dismiss="modal" value="x" class="btn-close1">        
        <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_SHIPPER_NAME')  ?></strong></h4>
      </div>
      <form name="userprofileFormTwo" id="userprofileFormTwo" method="post" action="">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_USER_TYPE')  ?> </label>
                <input type="text" class="form-control" name="usertypeTxt"  value="Shipper" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ID')  ?> </label>
                <input type="text" class="form-control"  name="useridTxt" value="<?php echo UserprofileHelpersUserprofile::getPickupOderShippernameId(1);?>" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_FIRST_NAME')  ?><span class="error">*</span></label>
                <input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="25">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_LAST_NAME')  ?><span class="error">*</span></label>
                <input type="text" class="form-control"  name="lnameTxt" id="lnameTxt" maxlength="25">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_COUNTRY')  ?> <span class="error">*</span></label>
                <select class="form-control" name="countryTxt" id="countryTxt">
                  <option value="">Select Country</option>
                  <?php echo UserprofileHelpersUserprofile::getPickupOderShippernameId(2);?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_STATE')  ?> <span class="error">*</span></label>
                <select class="form-control"  name="stateTxt" id="stateTxt">
                  <option value="">Select State</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_CITY')  ?> <span class="error">*</span></label>
                <select class="form-control" name="cityTxt" id="cityTxt">
                  <option value="">Select City</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ZIPCODE')  ?><span class="error">*</span></label>
                <input type="text" class="form-control" name="zipTxt" id="zipTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ADDITIONAL_ADDRESS')  ?>  <span class="error">*</span></label>
                <textarea type="text" class="form-control" name="addressTxt" id="addressTxt" maxlength="35"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_EMAIL')  ?></label>
                <input type="text" class="form-control"  name="emailTxt" id="emailTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_SAVE_BTN')  ?>"  class="btn btn-primary">
              <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_CANCEL_BTN')  ?>" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>
        <input type="hidden" name="task" value="user.pickupAdditionalusers">
        <input type="hidden" name="id" value="0" />
        <input type="hidden" name="user" value="<?php echo $user;?>" />
      </form>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">      
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">      
        <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_CONSIGNEE_NAME')  ?></strong></h4>
      </div>
      <form name="userprofileFormThree" id="userprofileFormThree" method="post" action="">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_USER_TYPE')  ?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="usertypeTxt"  value="Consignee" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ID')  ?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="useridTxt" value="<?php echo UserprofileHelpersUserprofile::getPickupOderConsigneeId(1);?>" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_FIRST_NAME')  ?><span class="error">*</span></label>
                <input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="25">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_LAST_NAME')  ?><span class="error">*</span></label>
                <input type="text" class="form-control"  name="lnameTxt" id="lnameTxt" maxlength="25">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_COUNTRY')  ?> <span class="error">*</span></label>
                <select class="form-control" name="country2Txt" id="country2Txt">
                  <option value="">Select Country</option>
                  <?php echo UserprofileHelpersUserprofile::getPickupOderConsigneeId(2);?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_STATE')  ?> <span class="error">*</span></label>
                <select class="form-control"  name="state2Txt" id="state2Txt">
                  <option value="">Select State</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_CITY')  ?> <span class="error">*</span></label>
                <select class="form-control" name="city2Txt" id="city2Txt">
                  <option value="">Select City</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ZIPCODE')  ?><span class="error">*</span></label>
                <input type="text" class="form-control" name="zipTxt" id="zipTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ADDITIONAL_ADDRESS')  ?> <span class="error">*</span></label>
                <textarea type="text" class="form-control" name="addressTxt" id="addressTxt" maxlength="35"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_EMAIL')  ?></label>
                <input type="text" class="form-control"  name="emailTxt" id="emailTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_SAVE_BTN')  ?>" class="btn btn-primary">
              <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_CANCEL_BTN')  ?>" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>
        <input type="hidden" name="task" value="user.pickupAdditionalusers">
        <input type="hidden" name="id" value="0" />
        <input type="hidden" name="user" value="<?php echo $user;?>" />
      </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">        
        <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_THIRD_PARTY_NAME')  ?></strong></h4>
      </div>
      <form name="userprofileFormFour" id="userprofileFormFour" method="post" action="">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_USER_TYPE')  ?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="usertypeTxt"  value="Third Party" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ID')  ?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="useridTxt" value="<?php echo UserprofileHelpersUserprofile::getPickupOderThirdpartyId(1);?>" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_FIRST_NAME')  ?><span class="error">*</span></label>
                <input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="25">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_LAST_NAME')  ?><span class="error">*</span></label>
                <input type="text" class="form-control"  name="lnameTxt" id="lnameTxt" maxlength="25">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_COUNTRY')  ?> <span class="error">*</span></label>
                <select class="form-control" name="country3Txt" id="country3Txt">
                  <option value="">Select Country</option>
                  <?php echo UserprofileHelpersUserprofile::getPickupOderThirdpartyId(2);?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_STATE')  ?> <span class="error">*</span></label>
                <select class="form-control"  name="state3Txt" id="state3Txt">
                  <option value="">Select State</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_CITY')  ?> <span class="error">*</span></label>
                <select class="form-control" name="city3Txt" id="city3Txt">
                  <option value="">Select City</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ZIPCODE')  ?><span class="error">*</span></label>
                <input type="text" class="form-control" name="zipTxt" id="zipTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_ADDITIONAL_ADDRESS')  ?> <span class="error">*</span></label>
                <textarea type="text" class="form-control" name="addressTxt" id="addressTxt" maxlength="35"></textarea>
              </div>
            </div>
            <!--<div class="col-md-6">
              <div class="form-group">
                <label><?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_POPUP_EMAIL')  ?> <span class="error">*</span></label>
                <input type="text" class="form-control"  name="emailTxt" id="emailTxt">
              </div>
            </div>-->
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_SAVE_BTN')  ?>" class="btn btn-primary">
              <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_ORDER_PICKUP_CANCEL_BTN')  ?>" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>
        <input type="hidden" name="task" value="user.pickupAdditionalusers">
        <input type="hidden" name="id" value="0" />
        <input type="hidden" name="user" value="<?php echo $user;?>" />
      </form>
    </div>
  </div>
</div>
