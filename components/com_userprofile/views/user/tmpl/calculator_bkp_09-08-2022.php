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
$document->setTitle("Calculator in Boxon Pobox Software");

defined('_JEXEC') or die;
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
$resWp=UserprofileHelpersUserprofile::getPickupFieldviewsList($user);

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
   
   $res = Controlbox::dynamicElements('Calculator');
   $elem=array();
   foreach($res as $element){
      $elem[$element->ElementId]=array($element->ElementDescription,$element->ElementStatus,$element->is_mandatory,$element->is_default,$element->ElementValue);
   }
   
// echo '<pre>';   
// var_dump($elem);exit;
// end

?>
<?php if(!$user){ ?><style>ul.nav.menu.nav.navbar-nav.mod-list { display: block !important; }</style><?php }  ?>
<?php include 'dasboard_navigation.php' ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function(){
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    
    var domainName = '<?php echo strtolower($domainName);  ?>';
    
    
     $joomla('#txtDestinationCntry').on('change',function(){
		var countryID = $joomla(this).val();
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#txtDestinationCntry").val() +"&stateflagCalc=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#txtDestinationCntry").val() },
				dataType:"html",
				type: "get",
				beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				    $joomla(".page_loader").hide();
					$joomla('#dstateTxt').html(data);
				}
			});
			
			 // dynamic service types
    
    // var shiptypesArr={"5000":"Air", "1728":"Ocean", "122":"Ground"};
    // var shipetypeVal=$joomla('select[name=txtShipperType]').val();
    // var shipmentType = shiptypesArr[shipetypeVal];
    
    var shipmentType=$joomla('select[name=txtShipperType]').val();
    
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
              $joomla('#txtRateType').html(data);
          }
      }); 
    
		}
		$joomla('#dstateTxt').html('<option value="">Select State</option>');
		//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		$joomla('#dzipTxt').val(''); 


	});
    
    jQuery.getJSON('<?php echo JURI::base(); ?>/client_config.json', function(jd) {
              //console.log(jd.ClientList);
              var iterator = jd.ClientList.values();
              
              console.log(iterator);
              
                for (let elements of iterator) {
                    if(elements.Domain.toLowerCase() == domainName){
                        $joomla('select[name="txtDestinationCntry"]').val(elements.Default_destcountry_calc).trigger("change");
                        $joomla('select[name="txtMeasurementUnits"]').val(elements.Default_measurement_calc);
                        $joomla('select[name="txtWeightUnits"]').val(elements.Default_weight_calc);
                    }
                }
    });
   
    
    $joomla('select[name="txtSourceCntry"]').val('US');
     
    $joomla("select").change(function(e){
        var txtShipperVal=$joomla('select[name="txtShipperType"]').find(':selected').data('id');
        if($joomla('select[name="txtDestinationCntry"]').val() && $joomla('input[name="txtLength"]').val() && $joomla('input[name="txtWidth"]').val() && $joomla('input[name="txtHeight"]').val() && $joomla('input[name="txtQuantity"]').val() && $joomla('input[name="txtWeight"]').val()) {
          $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatortype="+$joomla('select[name="txtRateType"]').val() +"&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+txtShipperVal+"&stype="+txtShipperVal+"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLength"]').val() +"&width="+$joomla('input[name="txtWidth"]').val() +"&height="+$joomla('input[name="txtHeight"]').val() +"&qty="+$joomla('input[name="txtQuantity"]').val() +"&gwt="+$joomla('input[name="txtWeight"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val()+"&getcalculatorflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "getpackagetype": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
                  $joomla("#loading-image3").show();
               },success: function(data){
                  $joomla("#loading-image3").hide();
               //alert(data);
              var cospor=data;
		      cospor=cospor.split(":");
		      if(parseFloat(cospor[0])  || cospor[0]==0){
    		      $joomla('input[name=txtVolume]').val(cospor[0]);
    		      $joomla('#divVolume').html(cospor[0]);
              }
              if(parseFloat(cospor[1])  || cospor[1]==0){
                  $joomla('input[name=VolumetricWeight]').val(cospor[1]);
		          $joomla('#divVolumetricWeight').html(cospor[1]);
                }   
                  $joomla("#loading2").hide();
                  $joomla("#loading3").hide();
	         }
		    });
        }	
    });    
    $joomla("#shipmentStr").live('click',function(e){
        $joomla('.resr').html('');
        $joomla(this).after('<p class="resr price-align1">'+$joomla(this).val()+'</p>');
    });

    $joomla("input").not(".txtReadonly").blur(function(e){
        var txtShipperVal=$joomla('select[name="txtShipperType"]').find(':selected').data('id');
        e.preventDefault();
        if($joomla('select[name="txtDestinationCntry"]').val() && $joomla('input[name="txtLength"]').val() && $joomla('input[name="txtWidth"]').val() && $joomla('input[name="txtHeight"]').val() && $joomla('input[name="txtQuantity"]').val() && $joomla('input[name="txtWeight"]').val()) {
          if($joomla(this).val()!="Calculate"){
              $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatortype="+$joomla('select[name="txtRateType"]').val() +"&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+txtShipperVal+"&stype="+txtShipperVal+"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLength"]').val() +"&width="+$joomla('input[name="txtWidth"]').val() +"&height="+$joomla('input[name="txtHeight"]').val() +"&qty="+$joomla('input[name="txtQuantity"]').val() +"&gwt="+$joomla('input[name="txtWeight"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val()+"&getcalculatorflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "getpackagetype": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
                      $joomla("#loading-image3").show();
                   },success: function(data){
                      $joomla("#loading-image3").hide();
                  //alert(data);
                  var cospor=data;
    		      cospor=cospor.split(":");
    		      if(parseFloat(cospor[0]) || cospor[0]==0){
        		      $joomla('input[name=txtVolume]').val(cospor[0]);
        		      $joomla('#divVolume').html(cospor[0]);
    		      }
    		      if(parseFloat(cospor[1])  || cospor[1]==0){
        		      $joomla('input[name=VolumetricWeight]').val(cospor[1]);
        		      $joomla('#divVolumetricWeight').html(cospor[1]);
        		  }
                      $joomla("#loading2").hide();
                      $joomla("#loading3").hide();
                      //$joomla("input[name='btnCalculate']").trigger( "click" );
    	        }
    		  });
          }  
        }	
    });
    
    $joomla("input[name='btnCalculate']").click(function(e){
    // Do not delete this. This is not an empty function
    // this function will trigger on blur inputs
});
 
    /*$joomla("input[name='btnCalculate']").click(function(e){
        e.preventDefault();
          $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatingtype=1&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+$joomla('select[name="txtShipperType"]').val() +"&stype="+$joomla('select[name="txtShipperType"]').val() +"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLength"]').val() +"&width="+$joomla('input[name="txtWidth"]').val() +"&height="+$joomla('input[name="txtHeight"]').val() +"&qty="+$joomla('input[name="txtQuantity"]').val() +"&gwt="+$joomla('input[name="txtWeight"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val()+"&volume="+$joomla('input[name="txtVolume"]').val()+"&valuemetric="+$joomla('input[name="VolumetricWeight"]').val()+"&getcalculatingflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "getpackagetype": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#loading-image").show();
           },success: function(data){
              //alert(data);
              var cospor=data;
		      cospor=cospor.split(":");
		      $joomla('input[name=txtChargeableWeight]').val(cospor[0]);
		      $joomla('input[name=txtShippingCost]').val(cospor[1]);
		    }
		});
    });*/  
   
    // Wait for the DOM to be ready
    $joomla(function() {
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        validateForm = $joomla("form[name='userprofileFormOne']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          txtSourceCntry: {
           
          },
          stateTxt: {
          
          },
          cityTxt: {
           
          },
          zipTxt: {
           
          },
          txtAddress: {
           
          },
          txtDestinationCntry: {
          
          },
          dstateTxt: {
           
          },
          dcityTxt: {
          
          },
          dzipTxt: {
           
          },
          txtdAddress: {
           
          },
          txtMeasurementUnits: {
           
          },
          txtQuantity: {
           
          },
          txtLength: {
           
          },
          txtWeight: {
           
          },
          txtShipperType: {
           
          },
          txtHeight: {
           
          },
          txtWidth:{
            
          },
          txtRateType:{
            
          },
          txtWeightUnits:{
           
          }
        },
        // Specify validation error messages
        messages: {
          txtSourceCntry: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_SOURCE_COUNTRY');?>",
          stateTxt: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_SOURCE_STATE');?>",
          cityTxt: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_SOURCE_CITY');?>",
          zipTxt: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_SOURCE_ZIP');?>",
          txtAddress: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_SOURCE_COUNTRY');?>",
          txtDestinationCntry: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_DESTINATION_COUNTRY');?>",
          dstateTxt: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_DESTINATION_STATE');?>",
          dcityTxt: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_DESTINATION_CITY');?>",
          dzipTxt: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_DESTINATION_ZIP');?>",
          txtdAddress: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_SOURCE_COUNTRY');?>",
          txtMeasurementUnits: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_MEASUREMENT_UNITS');?>",
          txtQuantity: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_QUANTITY');?>",
          txtLength: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_ITEM_LENGTH');?>",
          txtWeight: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_ITEM_WEIGHT');?>",
          txtShipperType: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_SHIPPMENT_TYPE');?>",
          txtHeight: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_ITEM_HEIGHT');?>",
          txtWidth: "<?php echo Jtext::_('COM_USERPROFILE_ENTER_ITEM_WIDTH');?>",
          txtRateType: "<?php echo Jtext::_('COM_USERPROFILE_SELECT_SERVICE_TYPE');?>",
          txtWeightUnits:"<?php echo Jtext::_('COM_USERPROFILE_SELECT_WEIGHT_UNITS');?>",
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
            $joomla(".page_loader").show();
           setTimeout(function () {
        		// Returns successful data submission message when the entered information is stored in database.
        	  
               $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatingtype="+$joomla('select[name="txtRateType"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val() +"&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&stype="+$joomla('select[name="txtShipperType"]').val() +"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLength"]').val() +"&width="+$joomla('input[name="txtWidth"]').val() +"&height="+$joomla('input[name="txtHeight"]').val() +"&qty="+$joomla('input[name="txtQuantity"]').val() +"&gwt="+$joomla('input[name="txtWeight"]').val() +"&volume="+$joomla('input[name="txtVolume"]').val()+"&valuemetric="+$joomla('input[name="VolumetricWeight"]').val()+"&state="+$joomla('select[name="stateTxt"]').val()+"&city="+$joomla('input[name="cityTxt"]').val()+"&zip="+$joomla('input[name="zipTxt"]').val()+"&address="+encodeURIComponent($joomla('input[name="txtAddress"]').val())+"&dstate="+$joomla('select[name="dstateTxt"]').val()+"&dcity="+$joomla('input[name="dcityTxt"]').val()+"&dzip="+$joomla('input[name="dzipTxt"]').val()+"&daddress="+encodeURIComponent($joomla('input[name="txtdAddress"]').val())+"&getcalculatingflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "getpackagetype": $joomla(this).data('id') },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
                  //$joomla(".page_loader").show();
                  $joomla("#loading2").hide();
                  $joomla("#loading3").hide();
    		   },success: function(data){
                  $joomla(".page_loader").hide();
                  $joomla("#loading2").hide();
                  $joomla('.rty').remove();
                  $joomla("#loading3").show().after('<div class="rty">'+data+'</div>');
    		    }
    		});
           }, 3000);
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
          //form.submit();
        }
        });    
    }); 
    
    // allow only numbers in quantity except 0
    
    $joomla("input[name='txtQuantity']").live("keyup",function(e){
        
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if($joomla(this).val().length == 1 && $joomla(this).val() == 0){
            
             $joomla(this).val(''); 
        }
        
    });
    
     // allow only umbers and decimal values 
    
    $joomla("input[name='txtLength']").live("keyup", function (event) {


            if (event.shiftKey == true) {
                event.preventDefault();
            }

            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

            } else {
                event.preventDefault();
            }
            
            if($joomla(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();

        });
        
        $joomla(".numDecimal").on("input", function(evt) {
           
            var self = $joomla(this);
            self.val(self.val().replace(/[^0-9\.]/g, ''));
            if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
            {
            evt.preventDefault();
            }
        });
   
    if($joomla('#txtSourceCntry').val()=="US"){
    
        $joomla.ajax({
            url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&cid="+$joomla('#txtSourceCntry').val()+"&getuscalcflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
            data: { "getpackagetype": $joomla(this).data('id') },
            dataType:"html",
            type: "get",
            beforeSend: function() {
                  $joomla("#loading-image3").show();
               },success: function(data){
                  $joomla("#loading-image3").hide();
                  // console.log(data)
        		  var cospors=data;
    		      cospors=cospors.split(":");
    		      $joomla("#stateTxt").val('');
    		      $joomla("#stateTxt").val(cospors[0]);
                  $joomla("input[name=cityTxt]").val('');
                  $joomla("input[name=cityTxt]").val(cospors[1]);
                  $joomla("#zipTxt").val('');
                  $joomla("#zipTxt").val(cospors[2]);
                  $joomla("#txtAddress").val('');
                  $joomla("#txtAddress").val(cospors[3]);
                  //$joomla("#txtPhoneNumber").val(cospors[4]);
               }
        });
    } 
    
    // if($joomla('#txtDestinationCntry').val()==""){
    //     $joomla("#dstateTxt").html('<option selected value="">Select</option>');
    // }
                      
    $joomla('#txtSourceCntry').on('change',function(){
		var countryID = $joomla(this).val();
		if(countryID){
		 $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#txtSourceCntry").val() +"&stateflagCalc=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "country": $joomla("#txtSourceCntry").val() },
			dataType:"html",
			type: "get",
			success: function(data){
				$joomla('#stateTxt').html(data);
			}
		 });
    	 $joomla.ajax({
                url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&cid="+countryID+"&getuscalcflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                data: { "getpackagetype": $joomla(this).data('id') },
                dataType:"html",
                type: "get",
                beforeSend: function() {
                      $joomla(".page_loader").show();
                   },success: function(data){
                      $joomla(".page_loader").hide();
                      $joomla("#stateTxt").val('KH');
                      console.log(data);
        		      var cospors=data;
        		      cospors=cospors.split(":");
        		      $joomla("#stateTxt").val('');
        		      $joomla("#stateTxt").val(cospors[0]);
                      console.log("state:"+cospors[0]);
                      $joomla("input[name=cityTxt]").val('');
                      $joomla("input[name=cityTxt]").val(cospors[1]);
                      console.log("city:"+cospors[1]);
                      $joomla("#zipTxt").val('');
                      $joomla("#zipTxt").val(cospors[2]);
                      console.log("zip:"+cospors[2]);
                      $joomla("#txtAddress").val('');
                      $joomla("#txtAddress").val(cospors[3]);
                      console.log("address:"+cospors[3]);
                      //$joomla("#txtPhoneNumber").val(cospors[4]);
                   }
            });
            
             // dynamic service types
    
    // var shiptypesArr={"5000":"Air", "1728":"Ocean", "122":"Ground"};
    // var shipetypeVal=$joomla('select[name=txtShipperType]').val();
    // var shipmentType = shiptypesArr[shipetypeVal];
    
    var shipmentType=$joomla('select[name=txtShipperType]').val();
    
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
              $joomla('#txtRateType').html(data);
          }
      }); 
    
         
		}
    	//$joomla('#stateTxt').html('<option value="">Select State</option>');
    	//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
    	$joomla('#zipTxt').val(''); 


	});
	
	 $joomla('select[name=txtShipperType]').on('change',function(){
	
	 // dynamic service types
    
    // var shiptypesArr={"5000":"Air", "1728":"Ocean", "122":"Ground"};
    // var shipetypeVal=$joomla('select[name=txtShipperType]').val();
    // var shipmentType = shiptypesArr[shipetypeVal];
    
    var shipmentType=$joomla('select[name=txtShipperType]').val();
    
   
    var srcCountry = $joomla('#txtSourceCntry').val(); 
    var destCountry = $joomla('#txtDestinationCntry').val();
   
        var urls="<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&destination="+ destCountry +"&source="+ srcCountry +"&shiptype="+ shipmentType +"&user=<?php echo $user;?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime();
       
        $joomla.ajax({
			url: urls,
			data: { "shippmenttypecalcflag": '1' },
			dataType:"html",
			type: "get",
            beforeSend: function() {
                $joomla(".page_loader").show();
            },
            success: function(data){
              $joomla('#txtRateType').html(data);
              $joomla(".page_loader").hide();
          }
      }); 
      
	 });


   

// 	if($joomla("#stateTxt")){
//     	$joomla.ajax({
//     		url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#stateTxt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
//     		data: { "state": $joomla("#countryTxt").val() },
//     		dataType:"html",
//     		type: "get",
//     		beforeSend: function() {
//                       $joomla(".page_loader").show();
//                   },
//     		success: function(data){
//     		    $joomla(".page_loader").hide();
//     		   $joomla("#cityTxt").append(data);
//     		}
//     	}); 
// 	}
// 	if($joomla("#dstateTxt")){
//     	$joomla.ajax({
//     		url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#dstateTxt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
//     		data: { "state": $joomla("#countryTxt").val() },
//     		dataType:"html",
//     		type: "get",
//     			beforeSend: function() {
//                       $joomla(".page_loader").show();
//                   },
//     		success: function(data){
//     		    $joomla(".page_loader").hide();
//     		   $joomla("#dcityTxt").append(data);
//     		}
//     	}); 
// 	}
	
	
   	$joomla("input[name='cityTxt']").blur(function(){
        
        var val = $joomla(this).val()
        var xyz = $joomla('#cityTxt option').filter(function() {
            return this.value == val;
        }).data('xyz');
        if(xyz){
            $joomla(this).val(val);
        }
        $joomla("input[name='cityTxtdiv']").val(xyz);
    });
    
   	$joomla("input[name='dcityTxt']").blur(function(){
        
        var val = $joomla(this).val()
        var xyzd = $joomla('#dcityTxt option').filter(function() {
            return this.value == val;
        }).data('xyzd');
        if(xyzd){
            $joomla(this).val(val);
        }
        $joomla("input[name='cityTxtdiv']").val(xyzd);
    });
    
    
    $joomla('#stateTxt').on('change',function(){
	    $joomla('input[name="cityTxt"]').val('');
		$joomla('#cityTxt').html('');
		var stateID = $joomla(this).val();
		if(stateID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#stateTxt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#txtSourceCntry").val() },
				dataType:"html",
				type: "get",
					beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				    $joomla(".page_loader").hide();
					$joomla("#cityTxt").append(data);
				}
			}); 
		}
		//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		$joomla('#zipTxt').val(''); 

	});   
    $joomla('#dstateTxt').on('change',function(){
	    $joomla('input[name="dcityTxt"]').val('');
		$joomla('#dcityTxt').html('');
		var stateID = $joomla(this).val();
		if(stateID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#dstateTxt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#txtDestinationCntry").val() },
				dataType:"html",
				type: "get",
				beforeSend: function() {
                      $joomla(".page_loader").show();
                   },
				success: function(data){
				    $joomla(".page_loader").hide();
					$joomla("#dcityTxt").append(data);
				}
			}); 
		}
		//$joomla('#cityTxt').html('<option value="">Select City</option>'); 
		$joomla('#dzipTxt').val(''); 

	});   
    
});
</script>
<style>
.rty div{display: block; clear: both; width: 100%;}
.rty input[type="radio"]{float:left; margin-right:10px;}
.rty label {float: left; display: inline-block;  min-width: 150px; margin-right: 5px;}
.price-align1{display:inline-block;}
</style>
<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_CAL_TITLE');?></div>
		<div class="panel-body">
			<p><?php echo Jtext::_('COM_USERPROFILE_CAL_SUB_TITLE');?></p>
			<div class="row">
				<div class="col-sm-12">
					<h4 class="sub_title"><strong><?php echo Jtext::_('COM_USERPROFILE_LABEL_TITLE');?></strong></h4>
				</div>
			</div>
			<form name="userprofileFormOne" id="userprofileFormOne" method="post" action="">
            <div class="row">
                <?php if($elem['SourceCountry'][1] == "ACT"){  ?> 
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_SOURCE_COUNTRY_LABEL');?><?php if($elem['SourceCountry'][2]){ ?><span class="error">*</span><?php } ?></label>
			             <?php
					       $countryView= Controlbox::getCountriesList();
					       $arr = json_decode($countryView); 
                           $countries='';
					       foreach($arr->Data as $rg){
                               $countries.= '<option value="'.$rg->CountryCode.'">'.$rg->CountryDesc.'</option>';
                           }
    					?>
                        <select class="form-control" name="txtSourceCntry" id="txtSourceCntry" <?php if($elem['SourceCountry'][2]){ echo "required";  } ?> >
			              <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT_COUNTRY');?></option>
                          <?php echo $countries;?>
                        </select>
			        </div>
			    </div>
			    <?php } if($elem['DestinationCountry'][1] == "ACT"){ ?>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">

			            <label><?php echo Jtext::_('COM_USERPROFILE_DESTINATION_COUNTRY_LABEL');?><?php if($elem['DestinationCountry'][2]){ ?><span class="error">*</span><?php } ?></label>
			             <select class="form-control"  name="txtDestinationCntry"   id="txtDestinationCntry" <?php if($elem['DestinationCountry'][2]){ echo "required";  } ?> >
			                <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT');?></option>
			        <?php echo $countries;?>
			          </select>
			        </div>
			    </div>
			    <?php } ?>
			</div>
			
			<div class="row">
			    <?php if($elem['SourceState'][1] == "ACT"){  ?> 
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_SOURCE_STATE_LABEL');?><?php if($elem['SourceState'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <select class="form-control"  id="stateTxt" name="stateTxt" <?php if($elem['SourceState'][2]){ echo "required";  } ?> >
                          <?php
        		           echo UserprofileHelpersUserprofile::getStatesList('US','');
        		          ?>
                        </select>
			        </div>
			    </div>
			    <?php } if($elem['DestinationState'][1] == "ACT"){ ?>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
	             <label><?php echo Jtext::_('COM_USERPROFILE_DESTINATION_STATE_LABEL');?><?php if($elem['DestinationState'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <select class="form-control"  id="dstateTxt" name="dstateTxt" <?php if($elem['DestinationState'][2]){ echo "required";  } ?> >
                  <option value="0"><?php echo Jtext::_('COM_USERPROFILE_SELECT');?></option>
                  <?php
		           //echo UserprofileHelpersUserprofile::getStatesList('','');
		          ?>
                </select>
			        </div>
			    </div>
			    <?php } ?>
			</div>
			
			
			
			<div class="row">
			    <?php if($elem['SourceCity'][1] == "ACT"){  ?> 
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_SOURCE_CITY_LABEL');?><?php if($elem['SourceCity'][2]){ ?><span class="error">*</span><?php } ?></label>
                           
                            <select type="text" class="form-control"  name="cityTxt" id="cityTxt" autocomplete="off"  <?php if($elem['SourceCity'][2]){ echo "required";  } ?> >
                                    <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT_CITY_LABEL');?></option>
                            </select>
			            
			        </div>
			    </div>
			    <?php } if($elem['DestinationCity'][1] == "ACT"){  ?>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_DESTINATION_CITY_LABEL'); ?><?php if($elem['DestinationCity'][2]){ ?><span class="error">*</span><?php } ?></label>
                       
                    	<select type="text" class="form-control"  name="dcityTxt" id="dcityTxt" autocomplete="off" <?php if($elem['DestinationCity'][2]){ echo "required";  } ?> >
                                    <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT_CITY_LABEL');?></option>
                            </select>
			        </div>
			    </div>
			    <?php } ?>
			</div>
			
			        <?php
			
		        	foreach($clients as $client){ 
                        if(strtolower($client['Domain']) == strtolower($domainName) ){   
                            $sourcezip_text=$client['Source_zipcode'];
                            $destzip_text=$client['Dest_zipcode'];
                            $length_def=$client['length_default'];
                            $width_def=$client['width_default'];
                            $height_def=$client['height_default'];
                            $Default_len_calc_readonly=$client['Default_len_calc_readonly'];
                            $Default_width_calc_readonly=$client['Default_width_calc_readonly'];
                            $Default_height_calc_readonly=$client['Default_height_calc_readonly'];
                            
                            
                        }
		        	}
                    
                    ?>


			<div class="row">
			     <?php if($elem['SourcePincode'][1] == "ACT"){  ?> 
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_($sourcezip_text);?><?php if($elem['SourcePincode'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <input class="form-control" name="zipTxt"  id="zipTxt" <?php if($elem['SourcePincode'][2]){ echo "required";  } ?> >
			        </div>
			    </div>
			    <?php } if($elem['DestinationPincode'][1] == "ACT"){ ?>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			           <label><?php echo Jtext::_($destzip_text);?><?php if($elem['DestinationPincode'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <input class="form-control" name="dzipTxt" id="dzipTxt" <?php if($elem['DestinationPincode'][2]){ echo "required";  } ?> >
			        </div>
			    </div>
			    <?php } ?>
			</div>

			<div class="row">
			     <?php if($elem['Sourceaddress'][1] == "ACT"){  ?>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_SOURCE_ADDRESS_LABEL');?><?php if($elem['Sourceaddress'][2]){ ?><span class="error">*</span><?php } ?></label> 
			            <input class="form-control" name="txtAddress" id="txtAddress" <?php if($elem['Sourceaddress'][2]){ echo "required";  } ?> >
			        </div>
			    </div>
			    <?php } if($elem['Destinationaddress'][1] == "ACT"){  ?>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_DESTINATION_ADDRESS_LABEL');?><?php if($elem['Destinationaddress'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <input class="form-control" name="txtdAddress" id="txtdAddress" <?php if($elem['Destinationaddress'][2]){ echo "required";  } ?> >
			        </div>
			    </div>
			    <?php } ?>
			</div>

			<div class="row">
			    <?php if($elem['MeasurementsUnits'][1] == "ACT"){  ?>
			    <div class="col-sm-3 col-md-3">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_MEASUREMENT_UNITS_LABEL');?><?php if($elem['MeasurementsUnits'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <select class="form-control" name="txtMeasurementUnits" <?php if($elem['MeasurementsUnits'][2]){ echo "required";  } ?> >
			                <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT');?></option>
                               <option value="cm">CENTIMETERS</option>;
                                <option value="ft">FEET</option>;
                                <option value="in" selected="">INCHES</option>;
                                <option value="m">METER</option>;
			            </select>
			        </div>
			    </div>
			    <?php } if($elem['WeightUnits'][1] == "ACT"){ ?>
			    <div class="col-sm-3 col-md-3">
			        <div class="form-group">
			        	 <label><?php echo Jtext::_('COM_USERPROFILE_WEIGHT_UNITS_LABEL');?><?php if($elem['WeightUnits'][2]){ ?><span class="error">*</span><?php } ?></label>
			               <select class="form-control" name="txtWeightUnits" <?php if($elem['WeightUnits'][2]){ echo "required";  } ?> >
			                <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT');?></option>
                            <option value="Kg">KILOGRAMS</option>
                            <option selected="selected" value="Lb">POUNDS</option>
			            </select>
			        </div>
			    </div>
			    <?php } if($elem['ShippingType'][1] == "ACT"){   ?>
				<div class="col-sm-3 col-md-3">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_SHIPPING_TYPE_LABEL');?><?php if($elem['ShippingType'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <select class="form-control" name="txtShipperType" <?php if($elem['ShippingType'][2]){ echo "required";  } ?> >
			            <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT_LABEL');?></option>
			            
			             
                <!--dynamic-->
                
                <?php
                
                foreach($resWp->Shipment_List as $row){ ?>
                
                    <option data-id="<?php echo $row->value; ?>" value="<?php echo $row->id_values; ?>" <?php if ($row->id_values == "AIR" ) { echo "selected"; } ?>  ><?php echo $row->desc_vals;  ?></option>
                
                <?php } ?>
                
                
			            </select>
			        </div>
			    </div>
			    <?php } if($elem['ServiceType'][1] == "ACT"){ ?>
				<div class="col-sm-3 col-md-3">
			        <div class="form-group">
			        	 <label><?php echo Jtext::_('COM_USERPROFILE_SERVICE_TYPE_LABEL');?><?php if($elem['ServiceType'][2]){ ?><span class="error">*</span><?php } ?></label>
			        	<select class="form-control" name="txtRateType" id="txtRateType" <?php if($elem['ServiceType'][2]){ echo "required";  } ?> >
                           <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT');?></option>
			        	</select>    
			    </div>
				</div>
				<?php }  ?>
			</div>
			<div class="row">
			     <?php if($elem['Quantity'][1] == "ACT"){  ?>
				 <div class="col-sm-3 col-md-3">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_PACKAGE_QUANTITY_LABEL');?><?php if($elem['Quantity'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <input class="form-control" name="txtQuantity" <?php if($elem['Quantity'][2]){ echo "required";  } ?>  >
			        </div>
			    </div>
			    <?php } if($elem['Length'][1] == "ACT"){ ?>
				<div class="col-sm-3 col-md-3">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_LENGTH_LABEL');?><?php if($elem['Length'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <input class="form-control numDecimal"  name="txtLength" <?php if($elem['Length'][2]){ echo "required";  } ?> value="<?php if($elem['Length'][3]){  echo intval($elem['Length'][4]);  } ?>"  <?php echo $Default_len_calc_readonly; ?> >
			        </div>
			    </div>
			    <?php } if($elem['Width'][1] == "ACT"){ ?>
			    <div class="col-sm-3 col-md-3">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_WIDTH_LABEL');?><?php if($elem['Width'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <input class="form-control numDecimal"  name="txtWidth" <?php if($elem['Width'][2]){ echo "required";  } ?> value="<?php if($elem['Width'][3]){  echo intval($elem['Width'][4]);  } ?>" <?php echo $Default_width_calc_readonly; ?> >
			        </div>
			    </div>
			    <?php } if($elem['Height'][1] == "ACT"){ ?>
				<div class="col-sm-3 col-md-3">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_HEIGHT_LABEL');?><?php if($elem['Height'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <input class="form-control numDecimal"  name="txtHeight" <?php if($elem['Height'][2]){ echo "required";  } ?> value="<?php if($elem['Height'][3]){  echo intval($elem['Height'][4]);  } ?>" <?php echo $Default_height_calc_readonly; ?> >
			        </div>
			    </div>
			    <?php } ?>
			</div>			
			<div class="row">
			    <?php if($elem['GrossWeight'][1] == "ACT"){ ?>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_GROSS_WEIGHT_LABEL');?><?php if($elem['GrossWeight'][2]){ ?><span class="error">*</span><?php } ?></label>
			            <input class="form-control numDecimal" name="txtWeight" <?php if($elem['GrossWeight'][2]){ echo "required";  } ?> >
			        </div>
			    </div>	
			    <?php } ?>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			        	<label class="invisable d-block">&nbsp;</label>
			            <label><?php echo Jtext::_('COM_USERPROFILE_VALUME_LABEL');?>:&nbsp;</label> <span id="divVolume"></span>
			            <br>
			            <label><?php echo Jtext::_('COM_USERPROFILE_VALUMETRIC_WEIGHT_LABEL');?>:&nbsp;</label> <span id="divVolumetricWeight"></span>
			        </div>
			    </div>
			</div>
			<div class="row">
			    <div class="col-sm-12 col-md-12 text-center">
			        <input type="submit" name="btnCalculate" value="<?php echo Jtext::_('COM_USERPROFILE_CALCULATE');?>" class="btn btn-primary">
			    </div>
			</div>
			<input name="txtVolume" type="hidden">
			<input name="VolumetricWeight" type="hidden">
			</form>
			<!-- Additional Services start -->
			<!--
			<div class="row">
			    <div class="col-sm-12 col-md-12">
			        <h4 class="sub_title"><strong>Additional Services</strong></h4>
			    </div>
			</div>
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    <div class="addserv-blk">
			    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			        <ul>
			        <?php
			            foreach($resService as $row){
			        ?>
			            <li><?php echo $row->Addnl_Serv_Name.' - '.$row->Cost;?></li>
			        
			        <?php
			            }
			        ?>
			        </ul>
		        </div>
		        </div>
	        </div>
	        </div>
	        
			-->
			
			<div class="clearfix"></div>
	        <div id="loading-image2" style="display:none"><img src='/components/com_userprofile/images/loader.gif' width="300" height="300"></div>
			<div class="row">
			    <div class="col-sm-12 col-md-12">
			        <h4 class="sub_title"><strong><?php echo Jtext::_('COM_USERPROFILE_RESULT_LABEL');?></strong></h4>
			    </div>
			</div>
			<div id="loading3"></div>
			<div id="loading2">
			<!-- Additinal Services end -->
			
		    <!--<div class="row">
			    <div class="col-sm-12 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_LABEL');?>Chargeable Weight</label>
			            <input class="form-control txtReadonly" name="txtChargeableWeight" >
			        </div>
			    </div>
			    <div class="col-sm-12 col-md-6">
			        <div class="form-group">
			            <label><?php echo Jtext::_('COM_USERPROFILE_LABEL');?>Shipping Cost (USD)</label>
			            <input class="form-control txtReadonly" name="txtShippingCost">
			        </div>
			    </div>
			</div><div class="row">
            <div class="col-sm-12 col-md-12">
             <label><h4 class="sub_title"><strong>Note</strong></h4> :   Based on Item Price Insurance Charges will apply.</label>
            </div>
            </div>-->

			</div>
			
        
		</div>
	</div>
</div>x