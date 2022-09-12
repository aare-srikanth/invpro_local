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
$document->setTitle("View Shipments in Boxon Pobox Software");
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$pass=$session->get('user_casillero_password');
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}

$menuAccessStr=Controlbox::getMenuAccess($user,$pass);
$menuCustData = explode(":",$menuAccessStr);
$maccarr=array();
foreach($menuCustData as $menuaccess){
    
    $macess = explode(",",$menuaccess);
    $maccarr[$macess[0]]=$macess[1];
 
}

$quotation = $maccarr['Quotation'];
$pickup = $maccarr['PickUpOrder'];

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

//echo '<pre>';
//var_dump($assArr);
   
}  

curl_close($ch);

?>
<?php include 'dasboard_navigation.php' ?>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
        history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

  /*$joomla('#j_table').on('click','a',function(e){
    e.preventDefault(); 
    var st=$joomla(this).closest("tr").find('td:eq(4)').html();
    if(st=="Pending"){
      alert('Pending Status can not be Converted into PickUp Order');  
      return false;        
    }
    var cf=confirm("Are you sure want to Convert Quotation to Pickup Order?");
    //$joomla('#myAlertModal').modal("show");
    //$joomla('#error').html("");
    if(cf==true){
        var htmls=$joomla('.panel-body').html();
        $joomla.ajax({
    		url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&quotid="+$joomla(this).attr('data-id')+"&quotationflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    		data: { "quotid": $joomla(this).attr('data-id') },
    		dataType:"html",
    		type: "get",
    		async:true,
    		beforeSend: function() {
              $joomla(".panel-body").html("<img src='/components/com_userprofile/images/loader.gif'>");
           },
           success: function(data){
    		  $joomla(".panel-body").html(htmls);
    		  $joomla('#myAlertModal').modal("show");
    		  $joomla('#error').html(data);
    		  location.reload();  
    		  console.log(data);
    		}
    	});
    }
    return false;
   }); */
   $joomla('#j_table').on('click','a',function(e){
    e.preventDefault();
    $joomla('#QuoteNumberTxt').val($joomla(this).attr('data-id'));
    /*$joomla.ajax({
		url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&userid=<?php echo $user;?>&quotid="+$joomla(this).attr('data-id')+"&quotationorderflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
		data: { "quotid": $joomla(this).attr('data-id') },
		dataType:"html",
		type: "get",
		async:true,
	    beforeSend: function() {
              $joomla("#inforesult").html("<img src='/components/com_userprofile/images/loader.gif'>");
        },
        success: function(data){
		  $joomla('#inforesult').html(data);
		}
	});*/
   });
});    
    
</script>
<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading">View Shipments</div>
		<div class="panel-body">
		    
		    <?php  
            
                Controlbox::getViewShipmentsListCsv($user);
            
            ?>
		    
        <div class="row">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                        <h3 class=""><strong><?php echo Jtext::_('View Shipments Details');?></strong></h3>
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <a style="color:white;" href="<?php echo JURI::base(); ?>/csvdata/viewshipments_list.csv" class="btn btn-primary csvDownload export-csv">Export CSV</a>
                    </div>
                </div>
        </div>
        
        
	        <div class="row">
	        	<div class="col-md-12">
	        		<table class="table table-bordered theme_table" id="j_table">
	        			<thead>
							<tr>
								<th><?php echo $assArr['sNo'];;?></th>
								
								<?php  if($pickup == "True" && $quotation == "True"){ ?>
								
								<th><?php echo Jtext::_('COM_USERPROFILE_VIEW_SHIPMENTS_QUOTATION');?>#</th>
								<th><?php echo Jtext::_('COM_USERPROFILE_VIEW_SHIPMENTS_PICKUP_ORDER');?>#</th>
								
								<?php }else if($pickup == "True"){ ?>
								
								<th><?php echo Jtext::_('COM_USERPROFILE_VIEW_SHIPMENTS_PICKUP_ORDER');?>#</th>
								
								<?php }elseif($quotation == "True"){ ?>
									<th><?php echo $assArr['quotation#'];?></th>
								<?php } ?>
								
								<th><?php echo $assArr['warehouse_Receipt#'];?></th>
								<th><?php echo $assArr['status'];?></th>
								<th><?php echo $assArr['quantity'];?> </th>
								<th><?php echo $assArr['generated_date'];?></th>
								
							</tr>
	        			</thead>
	        			<tbody><?php echo Controlbox::getQuotationShipmentsListFilter($user,$pickup,$quotation); ?>	
						</tbody>
	        		</table>
	        	</div>
	        </div>
</div>
</div>
</div>



<!-- Modal -->
<form name="userprofileFormOne" id="userprofileFormOne" method="post" action="" enctype="multipart/form-data">
  <div id="inv_view" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<?php
$UserView= UserprofileHelpersUserprofile::getUserprofileDetails($user);
$resWp=UserprofileHelpersUserprofile::getPickupFieldviewsList($user);
?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script>
    var $joomla = jQuery.noConflict(); 
    $joomla(document).ready(function(){
    if($joomla( 'input[name="txtPickupDate"]' ))
    $joomla( 'input[name="txtPickupDate"]').datepicker({ minDate: new Date });
    var userid='<?php echo $user;?>';
      $joomla('select[name="txtShipperName"]').change(function(){
        var shipvb=$joomla(this).val();
        shipvb=shipvb.split(":");
        $joomla('input[name="hiddenShipperNameId"]').val(shipvb[0]);
        $joomla('input[name="txtShipperAddress"]').val(shipvb[1]);
        $joomla('input[name="hiddenShipperName"]').val($joomla(this).find(":selected").text());
    })
    $joomla('select[name="txtConsigneeName"]').change(function(){
        var convb=$joomla(this).val();
        convb=convb.split(":");
        $joomla('input[name="hiddenConsigneeId"]').val(convb[0]);
        $joomla('input[name="txtConsigneeAddress"]').val(convb[2]);
        $joomla('input[name="hiddenConsignee"]').val($joomla(this).find(":selected").text());
    })
    $joomla('select[name="txtThirdPartyName"]').change(function(){
        var thirdvb=$joomla(this).val();
        thirdvb=thirdvb.split(":");
        $joomla('input[name="hiddenThirdPartyId"]').val(thirdvb[0]);
        $joomla('input[name="txtThirdPartyAddress"]').val(thirdvb[1]);
        $joomla('input[name="hiddenThirdParty"]').val($joomla(this).find(":selected").text());
    })
    $joomla('input[name=txtChargableWeight]').click(function(){
        $joomla('input[name="txtName"]').val('');
        $joomla('textarea[name="txtPickupAddress"]').val('');
        if($joomla(this).val()==1){
          $joomla('input[name="txtName"]').val('<?php echo $UserView->UserName;?>');
          $joomla('textarea[name="txtPickupAddress"]').val('<?php echo $UserView->Address.'  '.$UserView->Address2.','.$UserView->City.','.$UserView->State.','. $UserView->Country.','.$UserView->PostalCode;?>');
        }else if($joomla(this).val()==2){
          $joomla('input[name="txtName"]').val($joomla('select[name="txtShipperName"]').find(":selected").text());
          $joomla('textarea[name="txtPickupAddress"]').val($joomla('input[name="txtShipperAddress"]').val());
        }
    });
    });
    </script>
        <div>
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">
          <h4 class="modal-title"><strong>Additional Pickup Information</strong></h4>
        </div>
        
      </div>
      <form name="userprofileFormThree" id="userprofileFormThree" method="post" action="">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['shipper_Name'];?><span class="error">*</span></label>
                
                <select class="form-control" name="txtShipperName">
			        <option value="">Select</option>
			        <?php
                    foreach($resWp->Shipper_List as $key=>$row){
                        if($row->Name)
                        echo '<option value="'.$row->Name.':'.$row->Address.'">'.$row->Name.'</option>';
                    }
                    ?>
			        </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['shipper_Address'];?><span class="error">*</span></label>
                <input type="text" class="form-control" name="txtShipperAddress">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['consignee_Name'];?> <span class="error">*</span></label>
                
               <select class="form-control" name="txtConsigneeName">
                <option value="">Select</option>
                <?php
                    foreach($resWp->Consignee_List as $key=>$row){
                        if($row->Name)
                        echo '<option value="'.$row->UserId.':'.$row->Name.':'.$row->Address.'">'.$row->Name.'</option>';
                    }
                ?>
              </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['consignee_Address'];?><span class="error">*</span></label>
                <input type="text" class="form-control"  name="txtConsigneeAddress">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['third_Party_Name'];?> <span class="error">*</span></label>
                
                <select class="form-control" name="txtThirdPartyName">
                <option value="">Select</option>
                <?php
                    foreach($resWp->ThirdParty_List as $key=>$row){
                        if($row->Name)
                        echo '<option value="'.$row->Name.':'.$row->Address.'">'.$row->Name.'</option>';
                    }
                ?>
              </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['third_Party_Address'];?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="txtThirdPartyAddress">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Pick-Up Information</label>
                <div class="rdo_rd1">
                  <input type="radio" name="txtChargableWeight" value=1>
                  <label><?php echo $assArr['same_as_Customer'];?></label>
                  <input type="radio" name="txtChargableWeight" value=2>
                  <label><?php echo $assArr['same_as_Shipper'];?></label>
                </div>
              </div>
            </div>
          </div>    
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['name'];?> <span class="error">*</span></label>
                <input class="form-control" type="text" name="txtName">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label> <?php echo $assArr['pickup_Date'];?>  <span class="error">*</span></label>
               <input class="form-control" type="text" name="txtPickupDate">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo $assArr['pickup_Address'];?><span class="error">*</span></label>
               <textarea class="form-control" name="txtPickupAddress"></textarea>
                <input type="hidden" class="form-control" name="QuoteNumberTxt" id="QuoteNumberTxt">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="submit" value="Save" class="btn btn-primary">
              <input type="button" value="Cancel" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>
        
        <input type="hidden" name="task" value="user.convertpickup">
        <input type="hidden" name="id" value="0" />
        <input type="hidden" name="user" value="<?php echo $user;?>" />
      </form>
    </div>
  </div>
  </div>
</form>
