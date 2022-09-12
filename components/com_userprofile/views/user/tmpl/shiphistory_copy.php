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
$config = JFactory::getConfig();
$backend_url=$config->get('backend_url');

$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$pass=$session->get('user_casillero_password');
$CompanyId = Controlbox::getCompanyId();
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
$menuCustType=end($menuCustData);

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

    $joomla('select[name=txtHistoryStatus]').change(function(){
        var resk=$joomla(this).val();
        $joomla(".page_loader").slideToggle("slow");
        if(resk=='All'){
            $joomla('input.input-sm').val('');
            $joomla('input.input-sm').trigger('keyup');
        }else    
        $joomla('input.input-sm').val($joomla(this).val());
        $joomla('input.input-sm').trigger('keyup');
        $joomla(".page_loader").slideToggle("slow");
    });     
       
 
    $joomla('.getsstatus').on('click',function(e){
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
    
        //momentoLogs
    $joomla(document).on('click','.getmlog',function(){
         $joomla("#momentoLogs").html('');
         $joomla(".page_loader").show();
        //$joomla("#momentoLogs").html('<div id="loading-image3" ><img src="/components/com_userprofile/images/loader.gif"></div>');
         var res=$joomla(this).data('id');
     setTimeout(function(){ 
         //$joomla("#momentoLogs").html('');
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&momentotype="+res+"&momentoflag=1&momentoid="+res+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "momentoid": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              //$joomla("#momentoLogs").html('<div id="loading-image3" ><img src="/components/com_userprofile/images/loader.gif"></div>');
            
           },success: function(data){
                $joomla(".page_loader").hide();
              $joomla("#momentoLogs").html('<table class="table table-bordered theme_table" id="O_table"><thead><tr><th><?php echo Jtext::_('COM_USERPROFILE_HISTORY_MODAL_STATUS');?></th><th><?php echo Jtext::_('COM_USERPROFILE_HISTORY_MODAL_USER');?></th><th><?php echo Jtext::_('COM_USERPROFILE_HISTORY_MODAL_DATE');?></th></tr>'+data+'</thead></table>');
        	}
		});
         
     }, 1000);
       
    });
    
    $joomla(".wrchild").click(function(){
        
        //$joomla('#M_table tbody tr#dfg').remove(); 
        var rs=$joomla(this).closest('tr').find('td:eq(2)').text();
        var htms='';
        $joomla('#M_table tbody tr').each( function () {
            if($joomla(this).attr('id')==rs){ 
              htms+='<tr id="dfg">'+$joomla(this).html()+'</tr>';
          
            }
        })
        if($joomla(this).val()=='-'){
           $joomla.each($joomla('#M_table tbody tr#dfg'), function(){
               if($joomla(this).closest('tr').find('td:eq(2)').text()==rs){ 
                  $joomla(this).closest('tr').remove();
                }
            })

            $joomla(this).val('+');
        }else{
            $joomla(this).closest('tr').after(htms); 
            $joomla(this).val('-');
        }     
    });


});


</script>
<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_ALERTS_MAIN_HEADING');?></div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12 tab_view">
          <ul class="nav nav-tabs">
              
            <?php 
            
                    if(!isset($maccarr['FulFillment'])){
                      $maccarr['FulFillment'] = "False";
                    }
            
                    foreach($clients as $client){ 
                        if(strtolower($client['Domain']) == strtolower($domainName) ){   
                            $prealert_text=$client['Myprealerts_text_dashboard'];
                        }
                    }
            
            
            if($menuCustType == "CUST" || ($menuCustType == "COMP" && $maccarr['FulFillment'] == "False") ){  ?>
                <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocessalerts"><?php echo Jtext::_($prealert_text);?></a> </li>
            <?php }else if($menuCustType == "COMP" && $maccarr['FulFillment'] == "True"){  ?>
                <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=inventoryalerts"><?php echo Jtext::_('COM_USERPROFILE_SHIP_COMP_TITLE1');?></a> </li>
            <?php } ?>
            
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocess"><?php echo Jtext::_('COM_USERPROFILE_HISTORY_TITLE2');?></a> </li>
            <!--<li> <a class="" href="index.php?option=com_userprofile&view=user&layout=cod">COD</a> </li>-->
            <li> <a class="active"><?php echo Jtext::_('COM_USERPROFILE_HISTORY_TITLE3');?></a> </li>
          </ul>
        </div>
      </div>
      
      <!--get status list-->
      
      <?php
            $statusList = Controlbox :: getStatusList('');
      ?>
      
      <div id"tabs3">
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-9 col-sm-9">&nbsp;</div>
            <div class="col-sm-3 p-0">
              <div class="form-group text-right">
                <select class="form-control" name="txtHistoryStatus">
                  <option value=""><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SELECT');  ?></option>
                  <option selected="selected" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_ALL');  ?>"><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_ALL');  ?></option>
                  
                  <?php
                    foreach($statusList->Data as $status){
                  ?>
                            <option value="<?php echo $status->StatusId; ?>"><?php echo $status->StatusDescription;  ?></option>
                  
                  <?php } ?>
                  <!--<option value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP');  ?>"><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP');  ?></option>-->
                  <!--<option value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP');  ?>"><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP');  ?></option>-->
                  <!--<option value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RETURN');  ?>"><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RETURN');  ?></option>-->
                  <!--<option value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_DISCARD');  ?>"><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_DISCARD');  ?></option>-->
                  <!--<option value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_IN_PROGRESS');  ?>"><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_IN_PROGRESS');  ?></option>-->
                  <!--<option value="<?php //echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_FINISHED');  ?>"><?php //echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_FINISHED');  ?></option>-->
                  <!--<option value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RECEIVED');  ?>"><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RECEIVED');  ?></option>-->
                  <!--<option value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_HOLD');  ?>"><?php echo Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_HOLD');  ?></option>-->
                </select>
              </div>
            </div>
          </div>
        </div>
        <?php  
            
            Controlbox::getOrdersHistoryListCsv($user);
            
        ?>
        
        <div class="row">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                       
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <a style="color:white;" href="<?php echo JURI::base(); ?>/csvdata/history_list.csv" class="btn btn-primary csvDownload export-csv">Export CSV</a>
                    </div>
                </div>
        </div>
            
        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered theme_table" id="M_table"  data-page-length='10'>
              <thead>
                <tr>
                  <th><?php echo $assArr['shipping#'];?></th>
                  <th><?php echo $assArr['warehouse_Receipt'];?></th>
                  <th><?php echo $assArr['creation_date'];?></th>
                  <th><?php echo $assArr['item_name'];?></th>
                  <th><?php echo $assArr['quantity'];?></th>
                  <th><?php echo $assArr['carrier'];?></th>
                  <th><?php echo $assArr['the_tracking_number'];?></th>
                  <th><?php echo $assArr['status'];?></th>
                  <th><?php echo $assArr['reports'];?></th>
                </tr>
              </thead>
              <tbody>
                <?php
    $ordersHistoryView= UserprofileHelpersUserprofile::getOrdersHistoryList($user);
    //var_dump($ordersHistoryView);exit;
    
       $env = explode(".",$_SERVER['SERVER_NAME']);
        
       $service_url = $backend_url;
       
    
    foreach($ordersHistoryView as $rg){
       if($rg->Status=="Ship"){
            $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP');
         }else if($rg->Status=="Received"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RECEIVED');
         }else if($rg->Status=="In Progress"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_IN_PROGRESS');
         }else if($rg->Status=="Discard"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_DISCARD');
         }else if($rg->Status=="Hold"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_HOLD');
         }else if($rg->Status=="Return"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RETURN');
         }else if($rg->Status=="Finished"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_FINISHED');
         }
        
        $rep='<a href="'.$service_url.'/ASPX/Tx_Wh_Receipt.aspx?bid='.$rg->BillFormNo.'&companyid='.$CompanyId.'" target="_blank">'.Jtext::_('COM_USERPROFILE_HISTORY_TABLE_WAREHOUSE_RECEIPT').'<a>';
        
       //  <!-- old feature start -->
        
        // if($p==$rg->BillFormNo){
        //   echo '<tr id="'.$rg->BillFormNo.'" style="display:none"><td></td><td>'.$rg->Id.'</td><td><a data-toggle="modal" data-backdrop="static" data-keyboard="false" class="getmlog" data-id="'.$rg->BillFormNo.'" data-target="#logModal" title="momento">'.$rg->BillFormNo.'</a></td><td>'.$rg->dti_created.'</td><td>'.$rg->ItemName.'</td><td>'.$rg->Quantity.'</td><td>'.$rg->carrier_name.'</td><td>'.$rg->TrackingId.'</td><td>'.$status.'</td><td>'.$rep.'</td></tr>';
        // }else{
         // echo '<tr ><td><input type="button" class="wrchild" value="+"></td><td>'.$rg->Id.'</td><td><a data-toggle="modal" data-backdrop="static" data-keyboard="false" class="getmlog" data-id="'.$rg->BillFormNo.'" data-target="#logModal" title="momento">'.$rg->BillFormNo.'</a></td><td>'.$rg->dti_created.'</td><td>'.$rg->ItemName.'</td><td>'.$rg->Quantity.'</td><td>'.$rg->carrier_name.'</td><td>'.$rg->TrackingId.'</td><td>'.$status.'</td><td>'.$rep.'</td></tr>';
        //     $p=$rg->BillFormNo;
        // }
        
        //  <!-- old feature end -->
        
        echo '<tr ><td>'.$rg->Id.'</td><td><a data-toggle="modal" data-backdrop="static" data-keyboard="false" class="getmlog" data-id="'.$rg->BillFormNo.'" data-target="#logModal" title="momento">'.$rg->BillFormNo.'</a></td><td>'.$rg->dti_created.'</td><td>'.$rg->ItemName.'</td><td>'.$rg->Quantity.'</td><td>'.$rg->carrier_name.'</td><td>'.$rg->TrackingId.'</td><td>'.$status.'</td><td>'.$rep.'</td></tr>';
        
        
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
        <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_HISTORY_MODAL_TRACKING');?></strong></h4>
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
