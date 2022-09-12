<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_calculator
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$paramsAgain = ModCalculatorHelper::getParams($params);

?>
<div class="calculator<?php echo $moduleclass_sfx; ?>" <?php if ($params->get('backgroundimage')) : ?> style="background-image:url(<?php echo $params->get('backgroundimage'); ?>)"<?php endif; ?> >
	<?php echo $module->content; ?>
</div>
<?php
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$idf=($user)?$user:$paramsAgain["wbuserid"];
$resWp=ModCalculatorHelper::getPickupFieldviewsList($idf);
?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function(){
    $joomla("select").change(function(e){
        if($joomla('select[name="txtDestinationCntry"]').val() && $joomla('input[name="txtLength"]').val() && $joomla('input[name="txtWidth"]').val() && $joomla('input[name="txtHeight"]').val() && $joomla('input[name="txtQuantity"]').val() && $joomla('input[name="txtWeight"]').val()) {
          $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatortype=1&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+$joomla('select[name="txtShipperType"]').val() +"&stype="+$joomla('select[name="txtShipperType"]').val() +"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLength"]').val() +"&width="+$joomla('input[name="txtWidth"]').val() +"&height="+$joomla('input[name="txtHeight"]').val() +"&qty="+$joomla('input[name="txtQuantity"]').val() +"&gwt="+$joomla('input[name="txtWeight"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val()+"&getcalculatorflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "getpackagetype": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#loading-image").show();
           },success: function(data){
              //alert(data);
              var cospor=data;
		      cospor=cospor.split(":");
		      $joomla('input[name=txtVolume]').val(cospor[0]);
		      $joomla('#divVolume').html(cospor[0]);
		      $joomla('input[name=VolumetricWeight]').val(cospor[1]);
		      $joomla('#divVolumetricWeight').html(cospor[1]);
	        }
		});
        }
    });    

    $joomla("input").on('keyup',function(e){
        e.preventDefault();
        if($joomla('select[name="txtDestinationCntry"]').val() && $joomla('input[name="txtLength"]').val() && $joomla('input[name="txtWidth"]').val() && $joomla('input[name="txtHeight"]').val() && $joomla('input[name="txtQuantity"]').val() && $joomla('input[name="txtWeight"]').val()) {
          $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatortype=1&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+$joomla('select[name="txtShipperType"]').val() +"&stype="+$joomla('select[name="txtShipperType"]').val() +"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLength"]').val() +"&width="+$joomla('input[name="txtWidth"]').val() +"&height="+$joomla('input[name="txtHeight"]').val() +"&qty="+$joomla('input[name="txtQuantity"]').val() +"&gwt="+$joomla('input[name="txtWeight"]').val() +"&wtunits="+$joomla('select[name="txtWeightUnits"]').val()+"&getcalculatorflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "getpackagetype": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#loading-image").show();
           },success: function(data){
              //alert(data);
              var cospor=data;
		      cospor=cospor.split(":");
		      $joomla('input[name=txtVolume]').val(cospor[0]);
		      $joomla('#divVolume').html(cospor[0]);
		      $joomla('input[name=VolumetricWeight]').val(cospor[1]);
		      $joomla('#divVolumetricWeight').html(cospor[1]);
	        }
		});
    }	
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
        $joomla("form[name='userprofileFormOne']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          txtSourceCntry: {
            required: true
          },
          txtDestinationCntry: {
            required: true
          },
          txtMeasurementUnits: {
            required: true
          },
          txtQuantity: {
            required: true
          },
          txtLength: {
            required: true
          },
          txtWeight: {
            required: true
          },
          txtShipperType: {
            required: true
          },
          txtHeight: {
            required: true
          },
          txtWidth:{
            required: true
          }
        },
        // Specify validation error messages
        messages: {
          txtSourceCntry: "Please select your Source Country",
          txtDestinationCntry: "Please enter your Destination Country",
          txtMeasurementUnits: "Please enter your Measurement Units",
          txtQuantity: "Please enter your Quantity",
          txtLength: "Please enter item Length",
          txtWeight: "Please enter your Item Weight",
          txtShipperType: "Please enter your Shipping type",
          txtHeight: "Please enter your Item Height",
          txtWidth: "Please enter your Item Width"
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
        		// Returns successful data submission message when the entered information is stored in database.
               $joomla.ajax({
    			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getcalculatingtype=1&munits="+$joomla('select[name="txtMeasurementUnits"]').val() +"&tos="+$joomla('select[name="txtShipperType"]').val() +"&stype="+$joomla('select[name="txtShipperType"]').val() +"&source="+$joomla('select[name="txtSourceCntry"]').val() +"&dt="+$joomla('select[name="txtDestinationCntry"]').val() +"&length="+$joomla('input[name="txtLength"]').val() +"&width="+$joomla('input[name="txtWidth"]').val() +"&height="+$joomla('input[name="txtHeight"]').val() +"&qty="+$joomla('input[name="txtQuantity"]').val() +"&gwt="+$joomla('input[name="txtWeight"]').val() +"&volume="+$joomla('input[name="txtVolume"]').val()+"&valuemetric="+$joomla('input[name="VolumetricWeight"]').val()+"&getcalculatingflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
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
});
</script>
<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading"><?php echo Jtext::_('CALCULATOR_LABEL');?></div>
		<div class="panel-body">
			<p>The dimensional weight is used when a package requires, for its size, exceptional space in a plane, making freight cost is slightly higher than the normal price. Enter your package measures to determine the actual weight to be quoted</p>
			<div class="row">
				<div class="col-sm-12">
					<h4 class="sub_title"><strong>Dimensional Weight Calculator</strong></h4>
				</div>
			</div>
			<form name="userprofileFormOne" id="userprofileFormOne" method="post" action="">
            <div class="row">
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label>Source <span class="error">*</span></label>
			            <select class="form-control" name="txtSourceCntry">
			      <option value="">Select</option>
			      <?php
                    foreach($resWp->SourceCntry_List as $key=>$row){
                        echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';
                    }
                ?>         
			            </select>
			        </div>
			    </div>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label>Destination <span class="error">*</span></label>
			            <select class="form-control"  name="txtDestinationCntry">
			                <option value="">Select</option>
			       <?php
                    foreach($resWp->DestinationCntry_List as $key=>$row){
                        echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';
                    }
                ?>     </select>
			        </div>
			    </div>
			</div>
			<div class="row">
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label>Measurement Units <span class="error">*</span></label>
			            <select class="form-control" name="txtMeasurementUnits">
			                <option value="">Select</option>
			               <?php
                    foreach($resWp->MeasurementUnits_List as $key=>$row){
                        echo '<option value="'.$row->id_values.'">'.$row->desc_vals.'</option>';
                    }
                ?>
			            </select>
			        </div>
			    </div>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label>Quantity <span class="error">*</span></label>
			            <input class="form-control" name="txtQuantity">
			        </div>
			    </div>
			</div>
			<div class="row">
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label>Length <span class="error">*</span></label>
			            <input class="form-control" name="txtLength">
			        </div>
			    </div>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label>Gross Weight</label>
			            <input class="form-control" name="txtWeight">
			        </div>
			    </div>
			</div>
			<div class="row">
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label>Width <span class="error">*</span></label>
			            <input class="form-control"  name="txtWidth">
			        </div>
			    </div>
			    <div class="col-sm-6 col-md-6">
			        <div class="form-group">
			            <label>Shipping Type <span class="error">*</span></label>
			            <select class="form-control" name="txtShipperType">
			            <option value="">Select</option>
                <?php
                    $arr=array(166=>"Air",1728=>"Ocean",122=>"Ground");
                    foreach($arr as $key=>$row){
                    //foreach($resWp->Shipment_List as $key=>$row){
                        echo '<option value="'.$key.'">'.$row.'</option>';
                    }
                ?>    
			            </select>
			        </div>
			    </div>
			</div>
			<div class="row">
			    <div class="col-sm-12 col-md-6">
			        <div class="form-group">
			            <label>Height <span class="error">*</span></label>
			            <input class="form-control"  name="txtHeight">
			        </div>
			    </div>
			    <div class="col-sm-12 col-md-6">
			        <div class="form-group">
			        	<label class="invisable d-block">&nbsp;</label>
			            <label>Volume:&nbsp;</label> <span id="divVolume"></span>
			            <br>
			            <label>Volumetric Weight:&nbsp;</label> <span id="divVolumetricWeight"></span>
			        </div>
			    </div>
			</div>
			<div class="row">
			    <div class="col-sm-12 col-md-12 text-center">
			        <input type="submit" name="btnCalculate" value="Calculate" class="btn btn-primary">
			    </div>
			</div>
			<input name="txtVolume" type="hidden">
			<input name="VolumetricWeight" type="hidden">
			</form>
			
			<div class="row">
			    <div class="col-sm-12 col-md-12">
			        <h4 class="sub_title"><strong>Result</strong></h4>
			    </div>
			</div>
			<div class="row">
			    <div class="col-sm-12 col-md-6">
			        <div class="form-group">
			            <label>Chargeable Weight</label>
			            <input class="form-control" name="txtChargeableWeight" >
			        </div>
			    </div>
			    <div class="col-sm-12 col-md-6">
			        <div class="form-group">
			            <label>Shipping Cost $</label>
			            <input class="form-control" name="txtShippingCost">
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>