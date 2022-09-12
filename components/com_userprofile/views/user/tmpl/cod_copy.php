<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';

$document = JFactory::getDocument();
$document->setTitle("Order Process in Boxon Pobox Software");
defined('_JEXEC') or die;
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
if($_GET['r']==1){
    $app = JFactory::getApplication();
    $app->enqueueMessage("Suessfully payment done", 'success');
}

   $domainDetails = ModProjectrequestformHelper::getDomainDetails();
   $CompanyId = $domainDetails[0]->CompanyId;
   $companyName = $domainDetails[0]->CompanyName;
   $domainEmail = $domainDetails[0]->PrimaryEmail;
   $domainName =  $domainDetails[0]->Domain;
   
   foreach($domainDetails[0]->PaymentGateways as $PaymentGateways){
       if($PaymentGateways->PaymentGatewayName == "Paypal")
            $PaypalEmail = $PaymentGateways->Email;
            $AccountType = strtolower($PaymentGateways->AccountType);
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

//echo '<pre>';
//var_dump($assArr);
   
}  

curl_close($ch);

?>
<?php include 'dasboard_navigation.php' ?>

<script type="text/javascript" src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/css/styles.css">
<link rel="stylesheet" href="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/css/demo.css">
<script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform"></script>

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
    
    var tmp='';
    tmp=$joomla("#ord_edit .modal-body").html();
    
    // authoriz.net error msg display
    
    var url_string = window.location.href;
    var url = new URL(url_string);
    var error_msg = url.searchParams.get("error");
    if(error_msg != null ){
        alert(error_msg);
         $joomla(".page_loader").show();
        window.location = "index.php?option=com_userprofile&view=user&layout=cod";
    }
  

     $joomla.validator.addMethod(
		"selectBox",
		function(value, element) {
			if (element.value == "none" || element.value == "0")
			{
				return false;
			}
			else {
				return true;
			}
		},
		""
	);
    

    $joomla.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z/ /]+$/.test(value);
    });
    


    
      $joomla('.ship').on('click',function(){
        
          $joomla(".dvPaymentInformation").hide();
          $joomla('.paymentopt').css("display","none");
          $joomla('input[name="cc"]').prop("checked",false);
          $joomla('input[name="prepaidMethod"]').prop("checked",false);
          $joomla(".prepaid_method_sec").hide();
          

      var looping=$joomla(this).data('id');
      var loops=looping.split(":");
      $joomla('#ord_ship').show();
      $joomla('#bill_form_no').html(loops[0]);
      $joomla('#bill_form_nostr').val(loops[0]);
      $joomla('#Id_Serv').html(loops[1]);
      $joomla('#Id_Servstr').val(loops[1]);
      $joomla('#TotalAmountPaid').html(loops[3]);
      $joomla('#TotalAmountPaidstr').val(loops[3]);
      $joomla('#InHouseNo').html(loops[4]);
      $joomla('#InHouseNostr').val(loops[4]);
      $joomla('#TotalCost').html(loops[5]);
      $joomla('#TotalCoststr').val(loops[5]);
      $joomla('#AdditionalCost').html(loops[6]);
      $joomla('#AdditionalCoststr').val(loops[6]);
      $joomla('#TotalFinalCost').html(loops[7]);
      $joomla('.TotalFinalCost').val(loops[7]);
      $joomla('#TotalFinalCoststr').val(loops[7]);
      $joomla('#Discount').html(loops[8]);
      $joomla('#Discountstr').val(loops[8]);
      $joomla('#InhouseIdk').html(loops[9]);
      $joomla('#InhouseIdkstr').val(loops[9]);
      $joomla('#amtStr').val(loops[7]);
      $joomla('input[name="amount"]').val(loops[7]);
      $joomla('#InvoiceNo').val(loops[10]);
      
      $joomla("input[name='return']").val('<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=response&page=cod&invoice='+loops[10]+'&pay=<?php echo base64_encode(date("m/d/0Y"));?>');
         
    });    
    
    
    
    $joomla('input[name=cc]').click(function(){
        
       if($joomla(this).val()=="prepaid"){
           
           $joomla(".prepaid_method_sec").show();
           
       
       }else{
           $joomla('#userprofileFormFive').attr('action','');        
           $joomla('#dvPaymentMethod').hide(); 
           
       }    
    });
    
    
    $joomla('input[name=ccStr]').click(function(){
       $joomla('.paymentopt').toggle(); 
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
          
          cardnumberStr: {
            required: true,
            minlength:15
          },
          txtccnumberStr: {
            required: true
          },
          MonthDropDownListStr: {
            required: true,
            //currentdates:true
          },
          YearDropDownListStr: {
            required: true,
           // currentdates:true
          }
        },
        // Specify validation error messages
        messages: {
                            cardnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_NUMBER_ERROR');?>"},
                            MonthDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_MONTH_ERROR');?>"},
                            YearDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_YEAR_ERROR');?>"},
                            txtccnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_CVV_ERROR');?>"}
          
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
       	
    		    $joomla(".page_loader").show();
                var user='<?php echo $user;?>';
                if($joomla('input[name="cc"]:checked').val() == "Stripe"){
                    
                    setTimeout(function () {
                        ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="amount"]').val()+"&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&invidkStr="+''+"&qtyStr="+''+"&wherhourecStr="+$joomla('input[name="bill_form_nostr"]').val()+"&user="+user+"&txtspecialinsStr="+''+"&cc=PayForCOD&paymentgateway=Stripe&shipservtStr="+$joomla('input[name="Id_Servstr"]').val()+"&consignidStr="+''+"&invf="+''+"&filenameStr="+''+"&articleStr="+''+"&priceStr="+'';
                        $joomla.ajax({
                       			url: ajaxurl,
                       			data: { "paymentgatewayflag":1,"ratetypeStr": "","Conveniencefees":$joomla('input[name="Conveniencefees"]').val(),"addSerStr":"","addSerCostStr":"","companyId":$joomla('input[name="companyId"]').val(),"insuranceCost":"","extAddSer":"","paypalinvoice":"","page":"cod","inhouseNo":$joomla('input[name="InHouseNostr"]').val(),"invoice":$joomla('input[name="InvoiceNo"]').val()},
                       			dataType:"text",
                       			type: "get",
                                beforeSend: function() {
                                   
                                },
                                success: function(data){
                                    res = data.split(":");
                                    if(res[0] == 1){
                                    window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&page=cod&invoice="+$joomla('input[name="InvoiceNo"]').val()+"&res="+res[1];
                                    }else{
                                        $joomla(".page_loader").hide();
                                        $joomla(".paygaterrormsg").html(data);
                                    }
                                }
                           });
                           
                   }, 5000); 
                    
                }else if($joomla('input[name="cc"]:checked').val() == "authorize.net"){
                    
                        var paymentGateway = $joomla('input[name="cc"]:checked').val();
                        var user='<?php echo $user;?>';
                        $joomla('input[name="item_name"]').val($joomla('input[name="bill_form_nostr"]').val()+":"+$joomla('input[name="Id_Servstr"]').val()+":"+$joomla('[name="TotalAmountPaidstr"]').val()+":"+$joomla('input[name="InHouseNostr"]').val()+":"+$joomla('input[name="TotalCoststr"]').val()+":"+$joomla('input[name="AdditionalCoststr"]').val()+":"+$joomla('input[name="TotalFinalCoststr"]').val()+":"+$joomla('input[name="Discountstr"]').val()+":"+$joomla('input[name="InhouseIdkstr"]').val()+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+user);
                        $joomla('input[name="item_number"]').val(1);
                        $joomla('input[name="amount"]').val($joomla('input[name="amtStr"]').val());
                    
                    document.getElementById('userprofileFormFive').submit();
                    
                }
           
          
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


    // $joomla.validator.addMethod("currentdates", function(value, element) {
    //  if($joomla('select[name="YearDropDownListStr"]').val()=="<?php echo date('Y');?>" && $joomla('select[name="MonthDropDownListStr"]').val()<="<?php echo date('m');?>"){
    //   return false 
    //  }else{
    //   return true 
    //  }
    // }, '<?php //echo Jtext::_('Card expiry year and month not validated');?>');

    $joomla("input[name='cardnumberStr']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $joomla("input[name='txtccnumberStr']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
   });
   
   

    $joomla('input[name="cc"]').on('click',function(){
        
          $joomla("label.error").html("");
         // convenience fees 
         
           $joomla('.paymentopt').css("display","none");
            $joomla.ajax({
                url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="TotalFinalCoststr"]').val()+"&convflag=1&gateway="+$joomla(this).val()+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
    			data: { "shippmentid": 1 },
    			dataType:"html",
    			type: "get",
    			beforeSend: function() {
                  $joomla(".page_loader").show();
                  $joomla('#ord_ship #step2 .btn-primary').attr("disabled", true);
              },success: function(data){
                  slot=1
                  var sg=data;
                  sg=sg.split(":");
                  $joomla('input[name="Conveniencefees"]').val(sg[1]);
               
                  $joomla('#TotalFinalCost').text(sg[0]);
                  $joomla('#amtStr').val(sg[0]);
                  $joomla("input[name='amount']").val(sg[0]);
                  $joomla(".page_loader").hide();
                  $joomla('#ord_ship #step2 .btn-primary').attr("disabled", false);
                
              }
            });
            
            // end
            
            if($joomla(this).val() == "authorize.net"){
                $joomla(".dvPaymentInformation input").val("");
                $joomla(".dvPaymentInformation select").val("");
                $joomla(".paygaterrormsg").html("");
                $joomla('#userprofileFormFive').attr('action','<?php echo JURI::base(); ?>payment.php');
                $joomla(".final_ship").removeAttr("onclick");
                $(".dvPaymentInformation").show();
            }else if($joomla(this).val() == "Stripe"){
                $joomla(".dvPaymentInformation input").val("");
                $joomla(".dvPaymentInformation select").val("");
                $joomla(".paygaterrormsg").html("");
                $joomla(".final_ship").removeAttr("onclick");
                $(".dvPaymentInformation").show();
            }else if($joomla(this).val() == "Paypal"){
                $joomla(".final_ship").attr("onclick","onGetCardNonce(event)");
                $(".dvPaymentInformation").hide();
            }
            
            
   
       
    });
    
  
});



</script>
<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_MY_PACAGES_LABEL');?></div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12 tab_view">
          <ul class="nav nav-tabs">
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocessalerts"> <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_PREALERTS');?></a> </li>
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocess"> <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_PENDING_SHIPMENT');?></a>  </li>
            <li> <a class="active"  href="index.php?option=com_userprofile&view=user&layout=cod"> <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_COD');?> </a> </li>
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=shiphistory"> <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_SHIPMENT_HISTORY');?></a> </li>
          </ul>
        </div>
      </div>
      <div id="tabs2">
        <div class="row">
          <div class="col-sm-12">
            <h3 class="mx-1"><strong><?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_COD_ITEM');?></strong></h3>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered theme_table" id="j_table">
                <thead>
                  <tr>
                    <th class="action_btns"  width=100><?php echo $assArr['actions#']; ?></th>
                    <th><?php echo $assArr['shipping#'];?></th>
                    <th><?php echo $assArr['warehouse_Receipt#']; ?></th>
                    <th><?php echo $assArr['total_cost'] ;?></th>
                  </tr>
                </thead>
                <tbody>
    <?php
    $ordersPendingView= UserprofileHelpersUserprofile::getCodorders($user);
    
   
    foreach($ordersPendingView as $rg){
      			
      echo '<tr>
      		<td class="action_btns"  width=100>
                <input type="button" name="ship" class="ship" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-id="'.$rg->bill_form_no.':'.$rg->Id_Serv.':'.$rg->paymentType.':'.$rg->TotalAmountPaid.':'.$rg->InHouseNo.':'.$rg->TotalCost.':'.$rg->AdditionalCost.':'.$rg->TotalFinalCost.':'.$rg->Discount.':'.$rg->InhouseIdk.':'.$rg->InvoiceNo.'" data-target="#ord_ship" title="ship">
      		</td>
      		<td>'.$rg->InHouseNo.'</td>
      		<td>'.$rg->bill_form_no.'</td>
      		<td>'.$rg->TotalFinalCost.'</td>
      		</tr>';
    }
    ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
<div>
</div> 

      </div>
    </div>
  </div>

</div>

<!-- Modal -->
<div id="ord_ship" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">      
        <input type="button" data-dismiss="modal" value="x" aria-label="Close" class="btn-close1">
         <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_SHIPPING_INFORMATION_LABEL');?>#</strong></h4>       
      </div>
      <div class="modal-body">
          <form name="userprofileFormFive" id="userprofileFormFive" method="post" action="" enctype="multipart/form-data">
             <?php $CompanyId = Controlbox::getCompanyId(); ?>
            <input type="hidden" id="companyId" name="companyId" value="<?php echo $CompanyId; ?>">
            <input type="hidden" id="bill_form_nostr" name="bill_form_nostr">
            <input type="hidden" id="Id_Servstr" name="Id_Servstr">
            <input type="hidden" id="TotalAmountPaidstr" name="TotalAmountPaidstr">
            <input type="hidden" id="InHouseNostr" name="InHouseNostr">
            <input type="hidden" id="TotalCoststr" name="TotalCoststr">
            <input type="hidden" id="AdditionalCoststr" name="AdditionalCoststr">
            <input type="hidden" id="TotalFinalCoststr" name="TotalFinalCoststr">
            <input type="hidden" id="Discountstr" name="Discountstr">
            <input type="hidden" id="InhouseIdkstr" name="InhouseIdkstr">
            <input type="hidden" id="InvoiceNo" name="InvoiceNo">
             <input type="hidden"  name="page" value="cod">
            
            <input type="hidden" id="amtStr" name="amtStr">
            <?php //$UserViews=UserprofileHelpersUserprofile::getUserpersonalDetails($user);?>

            <!--<input type="hidden" name="fnameStr" value="<?php echo $UserViews->AdditionalFirstName;?>" />
            <input type="hidden" name="lnameStr" value="<?php echo $UserViews->AdditionalLname;?>" />
            <input type="hidden" name="addressStr" value="<?php echo $UserViews->AddressAccounts.' '.$UserViews->addr_2_name;?>" />
            <input type="hidden" name="cityStr" value="<?php echo $UserViews->desc_city;?>"/>
            <input type="hidden" name="stateStr" value="<?php echo $UserViews->State;?>"/>
            <input type="hidden" name="zipStr" value="<?php echo $UserViews->PostalCode;?>"/>
            <input type="hidden" name="countryStr" value="<?php echo $UserViews->Country;?>"/>
            <input type="hidden" name="emailStr" value="<?php echo $UserViews->PrimaryEmail;?>"/>-->
             <!-- Jorge.farias@cifexpressusa.com-->
             <input type='hidden' name='business' value='sb-qa1ib1508141@personal.example.com'> 
             <input type='hidden'   name='item_name'>
                <input type='hidden' name='item_number' value=1> 
                <input type='hidden' name='amount'>
                <input type='hidden' name='paypalinvoice' id="paypalinvoice">
                <input type='hidden' name='Conveniencefees' id="Conveniencefees" value="0">
                
                
                <input type='hidden' name='no_shipping' value='1'> 
                <input type='hidden' name='currency_code' value='USD'>
                <input type='hidden' name='notify_url' value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=notify-cod'>
            <input type='hidden' name='cancel_return'
                value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=cod'>
            <input type='hidden' name='return'
                value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=response&page=cod&invoice=&pay=<?php echo base64_encode(date("m/d/0Y"));?>'>
            <input type="hidden" name="cmd" value="_xclick">  
              

            <div class="row">
              <div class="col-md-12">
                <div class="finish-shipping">
                  <label><?php echo Jtext::_('COM_USERPROFILE_FINAL_SHIPPING_LABEL');?># : </label><div id="shipmethodStrValuetwo" style="float:right"></div>
                  <p><?php echo Jtext::_('COM_USERPROFILE_MY_SUMMERY_SHIPPING');?># </p>
                  <p> Included (s):</p>
                  <table width="100%" class="table table-bordered theme_table shipping-costtbl">
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_WEREHOUSE_RECEIPT');?>#</label></td>
                      <td class="txt-right"><div id="bill_form_no"></div></td>
                    </tr>
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIPPING_LABEL');?>#</label></td>
                      <td class="txt-right"><div id="InHouseNo"></div></td>
                    </tr>
                    <tr>
                      <td colspan="2"><label><?php echo Jtext::_('COM_USERPROFILE_SHIPPING_OPTIONS');?></label></td>
                    </tr>

                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_TOTAL_COST');?></label></td>
                      <td class="txt-right"><div id="TotalCost"></div></td>
                    </tr>
 
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_ADDITIONAL_SERVICES');?></label></td>
                      <td class="txt-right"><div id="AdditionalCost">0.00</div></td>
                    </tr>
                    
                   
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_DISCOUNT');?></label></td>
                      <td class="txt-right"><div id="Discount">0.00</div></td>
                    </tr>
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_CUSTOM_CHARGES');?></label></td>
                      <td class="txt-right">0.00</td>
                    </tr>
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_COSTS');?></label></td>
                      <td class="txt-right">0.00</td>
                    </tr>
                    <tr class="total_cst">
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_TOTAL_BUY_FOR_TODAY');?></label></td>
                      <td class="txt-right"><div id="TotalFinalCost"></div></td>
                    </tr>

                  </table>
                  <div class="clearfix"></div>
                  <div class="rdo_cust">
                    <!--<div class="rdo_rd1">-->
                    <!--  <input type="radio" name="cc" value="paypal">-->
                    <!--  <label><?php //echo Jtext::_('COM_USERPROFILE_SHIP_PREPAID');?></label>-->
                    <!--</div>-->
                    
                    <div class="rdo_rd1">
                      <div class="paymentmethodsDiv">
                                
                                <?php 
                                    $paymentmethodsStr = ''; 
                                    $paymentmethodsStr.=Controlbox::getpaymentgateways('PPD');
                                    echo $paymentmethodsStr;
                                ?>
                               
                    </div>
                    </div>
                    
                  </div>
                  
                  <div class="clearfix"></div>
                  
                  
                 
                <div class="modal-body pagshipup" style="display:none"><img src='/components/com_userprofile/images/loader.gif' height="400"></div>
                  
                <div class="dvPaymentInformation col-md-6 col-sm-12 col-xs-12" style="display:none">
            <div class="heading">
                <h3 class="text-center">Confirm Purchase</h3>
            </div>
            <div class="payment">
                 <div class="form-group col-md-12 col-sm-12 col-xs-12" id="card-number-field">
                         <label for="cardNumber"><?php echo Jtext::_('COM_USERPROFILE_CARD_NUMBER');?></label>
                        <input type="text" class="form-control" id="cardNumber" name="cardnumberStr" minlength="15" maxlength="16" placeholder="4111111111111111">
                    </div>
                 <div class="clearfix"></div>
                   <div class="form-group" id="credit_cards">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/visa.jpg" id="visa">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/mastercard.jpg" id="mastercard">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/amex.jpg" id="amex">
                    </div>
                  
                    <div class="form-group col-md-12 col-sm-12" id="expiration-date">
                        <div class="row">
                        <label class="col-md-12 col-sm-12 col-xs-12">Expiration Date</label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        <select class="form-control" name="MonthDropDownListStr">
                            <option value="">Select Month</option>
                            <option value="01">January</option>
                            <option value="02">February </option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        <select class="form-control" name="YearDropDownListStr">
                            <option value="">Select Year</option>
                           <option value="2022" selected> 2022</option>
                            <option value="2023"> 2023</option>
                            <option value="2024"> 2024</option>
                            <option value="2025"> 2025</option>
                            <option value="2026"> 2026</option>
                            <option value="2027"> 2027</option>
                            <option value="2028"> 2028</option>
                            <option value="2029"> 2029</option>
                            <option value="2030"> 2030</option>
                            
                        </select>
                        </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                       <div class="form-group CVV col-md-12 col-sm-12 col-xs-12">
                        <label for="cvv">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="txtccnumberStr" style="width:20%">
                    </div>
                    <div class="clearfix"></div>
                   
                    <div class="col-md-12 col-sm-12 col-xs-12 error paygaterrormsg"></div>
            </div>
        </div>

                  
                  <div class="clearfix"></div>
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <input type="hidden" name="task" value="user.dirpayshippment">
                      <input type="hidden" name="id" />
                      <input type="hidden" name="user" value="<?php echo $user;?>" />
                      <input type="hidden" id="amount" name="amount">
                      <input type="hidden" id="card-nonce" name="nonce">
                      
                      <input type="submit" onclick="onGetCardNonce(event)" value="Ship"  class="btn btn-primary final_ship">
                      <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
                    </div>
                  </div>
                  <div class="form-group"></div>
                </div>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/js/jquery.payform.min.js" charset="utf-8"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/js/script.js"></script>
<script>
$joomla(document).keyup('#cvv,#cardNumber',function() {
        $joomla("#cvv-error").html("");
        cardNum = $joomla('#cardNumber').val();
        cardNumLen = cardNum.length;
        if(cardNumLen == 16){
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }else if(cardNumLen == 15){
            $joomla("#cvv").attr("maxlength",4);
            $joomla("#cvv").attr("minlength",4);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_FOUR_DIGITS_ERROR'); ?>");
        }else{
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }
    });
    $joomla("#cvv,#cardNumber").on('blur',function() {
       
        $joomla("#cvv-error").html("");
        cardNum = $joomla('#cardNumber').val();
        cardNumLen = cardNum.length;
        if(cardNumLen == 16){
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }else if(cardNumLen == 15){
            $joomla("#cvv").attr("maxlength",4);
            $joomla("#cvv").attr("minlength",4);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_FOUR_DIGITS_ERROR'); ?>");
        }else{
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }
    });
    
</script>   
   
   
   <script type="text/javascript">
     // Create and initialize a payment form object
     const paymentForm = new SqPaymentForm({
       // Initialize the payment form elements
       
       //TODO: Replace with your sandbox application ID sandbox-sq0idb-n0mRDDVFmPIFGl8A7410zg
       //applicationId: "sandbox-sq0idb-Y-iff9zZeN6J1DXEqNYA9Q",
       applicationId: "sandbox-sq0idb-n0mRDDVFmPIFGl8A7410zg",
       inputClass: 'sq-input',
       autoBuild: false,
       // Customize the CSS for SqPaymentForm iframe elements
       inputStyles: [{
           fontSize: '16px',
           lineHeight: '24px',
           padding: '8px 10px',
           placeholderColor: '#a0a0a0',
           backgroundColor: 'transparent',
       }],
       // Initialize the credit card placeholders
       cardNumber: {
           elementId: 'sq-card-number',
           placeholder: '4111111111111111'
       },
       cvv: {
           elementId: 'sq-cvv',
           placeholder: '111'
       },
       expirationDate: {
           elementId: 'sq-expiration-date',
           placeholder: '12/21'
       },
       postalCode: {
           elementId: 'sq-postal-code',
           placeholder: '11111'
       },
       // SqPaymentForm callback functions
       callbacks: {
           /*
           * callback function: cardNonceResponseReceived
           * Triggered when: SqPaymentForm completes a card nonce request
           */
           cardNonceResponseReceived: function (errors, nonce, cardData) {
           if (errors) {
               alert("Please fill all required fields");
               // Log errors from nonce generation to the browser developer console.
               console.error('Encountered errors:');
               errors.forEach(function (error) {
                   console.error('  ' + error.message);
               });
               //alert('Encountered errors, check browser developer console for more details');
               return;
           }
           
           
            document.getElementById('card-nonce').value = nonce;
           
            document.getElementById('userprofileFormFive').submit();
           
           }
       }
     });
     
      paymentForm.build();
      
     // onGetCardNonce is triggered when the "Pay $1.00" button is clicked
    function onGetCardNonce(event) {
            event.preventDefault();
            var AccountType = "<?php echo $AccountType; ?>";
            var paymentGateway = $joomla('input[name="cc"]:checked').val();
       
            var user='<?php echo $user;?>';
            $joomla('input[name="item_name"]').val($joomla('input[name="bill_form_nostr"]').val()+":"+$joomla('input[name="Id_Servstr"]').val()+":"+$joomla('[name="TotalAmountPaidstr"]').val()+":"+$joomla('input[name="InHouseNostr"]').val()+":"+$joomla('input[name="TotalCoststr"]').val()+":"+$joomla('input[name="AdditionalCoststr"]').val()+":"+$joomla('input[name="TotalFinalCoststr"]').val()+":"+$joomla('input[name="Discountstr"]').val()+":"+$joomla('input[name="InhouseIdkstr"]').val()+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+user);
            $joomla('input[name="item_number"]').val(1);
            $joomla('input[name="amount"]').val($joomla('input[name="amtStr"]').val());
           
       if(paymentGateway == 'Paypal'){
          
            $joomla(".dvPaymentInformation").hide();
           
            if(AccountType == "sandbox"){
                $joomla('#userprofileFormFive').attr('action','https://www.sandbox.paypal.com/cgi-bin/webscr'); 
            }else{
                $joomla('#userprofileFormFive').attr('action','https://www.paypal.com/cgi-bin/webscr'); 
            }
            
            document.getElementById('userprofileFormFive').submit();
            
       }
       
    }
     
   </script>
   
   <style>
   #divShipCOstOne input{margin-right:5px;}
   #divShipCOstOne{clear:both;}

.third {
  
  /*float: left;,width: calc((100% - 32px) / 3);*/
  padding: 0;
  margin: 0 16px 16px 0;
}

.third:last-of-type {
  margin-right: 0;
}

/* Define how SqPaymentForm iframes should look */
.sq-input {
    height:40px;
  box-sizing: border-box;
  border: 1px solid #404040;
  border-radius: 4px;
  outline-offset: -2px;
  display: inline-block;
  -webkit-transition: border-color .2s ease-in-out, background .2s ease-in-out;
     -moz-transition: border-color .2s ease-in-out, background .2s ease-in-out;
      -ms-transition: border-color .2s ease-in-out, background .2s ease-in-out;
          transition: border-color .2s ease-in-out, background .2s ease-in-out;
}

/* Define how SqPaymentForm iframes should look when they have focus */
.sq-input--focus {
  border: 1px solid #4A90E2;
  background-color: rgba(74,144,226,0.02);
}


/* Define how SqPaymentForm iframes should look when they contain invalid values */
.sq-input--error {
  border: 1px solid #E02F2F;
  background-color: rgba(244,47,47,0.02);
}

#sq-card-number {
  margin-bottom: 16px;
}


#error {
  width: 100%;
  margin-top: 16px;
  font-size: 14px;
  color: red;
  font-weight: 500;
  text-align: center;
  opacity: 0.8;
}

.source_id{
    color : green;
}
   </style>
