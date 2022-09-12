<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_unknownpkgs
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$paramsAgain = ModUnknownpkgsHelper::getParams($params);

if($_POST['submit']){
    echo ModUnknownpkgsHelper::postUnknownshippingDetails();
}
?>
<div class="unknownpkgs<?php echo $moduleclass_sfx; ?>" <?php if ($params->get('backgroundimage')) : ?> style="background-image:url(<?php echo $params->get('backgroundimage'); ?>)"<?php endif; ?> >
	<?php echo $module->content; ?>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo JUri::base(); ?>/components/com_userprofile/css/dataTables.bootstrap.min.css">
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/dataTables.bootstrap.min.js"></script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    $joomla('#a_table').DataTable();  
    $joomla('#a_table').on('click','a:nth-child(1)',function(e){
        $joomla('.btn-primary:first').show();
        $joomla('.btn-danger:first').show();
        $joomla('#cliam').hide();
        e.preventDefault();
        var track=$joomla(this).data('id');
        var tmp='';
        $joomla.ajax({
        	url: "<?php echo JURI::base(); ?>modules/mod_unknownpkgs/helper.php?task=get_ajax_data&trackid="+track +"&trackflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
        	data: { "trackid": $joomla(this).data('id') },
        	dataType:"html",
        	type: "get",
        	beforeSend: function() {
             $joomla("#b_table").find("tr:gt(0)").remove(); 
             $joomla("#b_table").append('<tr><td align="center" valign="middle" colspan="6"><img src="<?php echo JUri::base(); ?>/modules/mod_unknownpkgs/images/loader.gif"></td></tr>');
           },success: function(data){
              $joomla("#b_table").find("tr:gt(0)").remove(); 
              $joomla("#b_table:last").append(data);
              var wh=$joomla("#b_table").find("td").eq(2).html(); 
              var trc=$joomla("#b_table").find("td").eq(1).html(); 
              $joomla("input[name=txtWarehouse]").val(wh); 
              $joomla("input[name=txtTrackingId]").val(trc);
            }
        });
    });    
    $joomla('.btn-primary:first').show();
    $joomla('.btn-danger:first').show();
    $joomla('#cliam').hide();
    $joomla('.btn-primary:first').on('click',function(e){
        $joomla('#cliam').show();
        $joomla(this).hide();
        $joomla('.btn-danger:first').hide();
        
    });
    // Wait for the DOM to be ready
	$joomla(function() {
	
        //Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormOne']").validate({
            
            // Specify validation rules
            rules: {
              // The key name on the left side is the name attribute
              // of an input field. Validation rules are defined
              // on the right side
              txtName: {
                required: true,
                alphanumeric:true
              },
              txtWarehouse: {
                required: true
              },
              txtDestinationCountry: {
                required: true
              },
              txtTrackingId: {
                required: true
              },
              txtEmail: {
                required: true,
                email:true
              },
              txtContactNo: {
                required: true
              },
              txtAddress: {
                required: true
              }
        
            },
            // Specify validation error messages
            messages: {
              txtName: {required:"Please enter name",alphanumeric:"Please enter alpha characters"},
              txtWarehouse: {required:"Please enter Warehouse Number"},
              txtDestinationCountry: {required:"Please Select Destination Country"},
              txtTrackingId: "Please enter carrier tracking Id",
              txtEmail: {required:"Please enter email",alphanumeric:"Please enter valid email"},
              txtContactNo: "Please enter Contact Number",
              txtAddress: "Please enter Address"
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
    
});    
</script>
<style>
.modal-dialog{width:56%;}
</style>
<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading">View Unknown Packages</div>
		<div class="panel-body">

<div class="row">
	          <div class="col-sm-12">
	            <h3 class="mx-1"><strong>Unknown Packages</strong></h3>
	          </div>
	        </div>
	        <div class="row">
	        	<div class="col-md-12">
	        		<table class="table table-bordered theme_table" id="a_table">
	        			<thead>
							<tr>
								<th>Tracking Id#</th>
								<th>Item Details#</th>
							</tr>
	        			</thead>
	        			<tbody><?php echo ModUnknownpkgsHelper::unknownpackagesdetails();?></tbody>
	        		</table>
	        	</div>
	        </div>
</div>
</div>
</div>


<!-- Modal -->
  <div id="inv_view" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="text-right">
          <input type="button" data-dismiss="modal" value="X" >
        </div>
        <h4 class="modal-title"><strong>Package Details</strong></h4>
      </div>
      <div class="modal-body">
      <div class="row">
    	<div class="col-md-12">
    		<table class="table table-bordered theme_table" id="b_table">
    			<thead>
					<tr>
						<th>DateOfRecieved</th>
						<th>Tracking Id</th>
						<th>Warehouse No</th>
						<th>Weight</th>
						<th>Branch</th>
						<th>Item</th>
					</tr>
    			</thead>
    		</table>
    	</div>
    </div>
    <div class="row">
        <div class="col-md-12">
             <input type="button" value="Claim" class="btn btn-primary">
              <input type="button" value="Cancel" data-dismiss="modal" class="btn btn-danger">
    	</div>
    </div>

      <div id="cliam">
      <form name="userprofileFormOne" id="userprofileFormOne" method="post" action="">
        

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Warehouse NO<span class="error">*</span></label>
                <input type="text" class="form-control" name="txtWarehouse" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Tracking Id <span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtTrackingId" readonly>
              </div>
              
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>First Name<span class="error">*</span></label>
                <input type="text" class="form-control" name="txtFirstname">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Last Name<span class="error">*</span></label>
                <input type="text" class="form-control" name="txtLastname">
              </div>
              
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Destination Country  <span class="error">*</span></label>
                
               <select class="form-control" name="txtDestinationCountry">
                <option value="">Select</option>
                <?php
                    $country=ModUnknownpkgsHelper::getCountriesList();
                    $arr = json_decode($country); 
                    foreach($arr->Data as $rg){
                      echo '<option value="'.$rg->CountryCode.'" '.$sel. '>'.$rg->CountryDesc.'</option>';
                    }
                ?>
              </select>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>State<span class="error">*</span></label>
                    <select class="form-control" name="txtDestinationState">
                <option value="">Select</option>
                <?php
                    $country=ModUnknownpkgsHelper::getCountriesList();
                    $arr = json_decode($country); 
                    foreach($arr->Data as $rg){
                      echo '<option value="'.$rg->CountryCode.'" '.$sel. '>'.$rg->CountryDesc.'</option>';
                    }
                ?>
              </select>
                </div>
            </div>
            
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Destination City  <span class="error">*</span></label>
                
               <select class="form-control" name="txtDestinationCity">
                <option value="">Select</option>
                <?php
                    $country=ModUnknownpkgsHelper::getCountriesList();
                    $arr = json_decode($country); 
                    foreach($arr->Data as $rg){
                      echo '<option value="'.$rg->CountryCode.'" '.$sel. '>'.$rg->CountryDesc.'</option>';
                    }
                ?>
              </select>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Address<span class="error">*</span></label>
                    <input type="text" class="form-control"  name="txtAddress">
                </div>
            </div>
          </div>

         <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Address 2<span class="error">*</span></label>
                    <input type="text" class="form-control"  name="txtAddresstwo">
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Zip<span class="error">*</span></label>
                    <input type="text" class="form-control"  name="txtZipcode">
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email <span class="error">*</span></label>
                
                <input type="text" class="form-control"  name="txtEmail">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Contact No<span class="error">*</span></label>
                <select class="form-control" name="txtDialcode">
                <option value="">Select Dial Code</option>
                <?php
                    $dial=ModUnknownpkgsHelper::getDialcodeList();
                    $arr_dial = json_decode($dial); 
                    foreach($arr_dial->Data as $rg){
                      echo '<option value="'.$rg->DailCode.'" '.$sel. '>'.$rg->DailCode.'</option>';
                    }
                ?>
              </select><input type="text" class="form-control" name="txtContactNo">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Invoice<span class="error">*</span></label>
                <input type="file" class="form-control" name="txtInvoice">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
              </div>
            </div>
          </div>    
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" name="submit" value="Save" class="btn btn-primary">
              <input type="button" value="Cancel" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
      </form>
      </div>
      
    </div>
  </div>
  </div>
  </div>
