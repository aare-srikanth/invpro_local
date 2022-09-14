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
   $document->setTitle("Order Process in Boxon Pobox Software");
   $session = JFactory::getSession();
   require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';
   
   $user=$session->get('user_casillero_id');
   $pass=$session->get('user_casillero_password');
   $CompanyId = Controlbox::getCompanyId();
   $PaypalEmail = '';
   
    $config = JFactory::getConfig();
    $backend_url=$config->get('backend_url');
    
    $resId=UserprofileHelpersUserprofile::getInvoicedetailsId($user);
    $invoiceCountRes = UserprofileHelpersUserprofile::GetInvoicesCount($user,'Inhouse');
    
   // get domain details start

   $clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
   $clientConf = json_decode($clientConfigObj, true);
   $clients = $clientConf['ClientList'];
   
   $domainDetails = ModProjectrequestformHelper::getDomainDetails();
   $CompanyId = $domainDetails[0]->CompanyId;
   $companyName = $domainDetails[0]->CompanyName;
   $domainEmail = $domainDetails[0]->PrimaryEmail;
   $domainName =  $domainDetails[0]->Domain;
   
   
   
   foreach($domainDetails[0]->PaymentGateways as $PaymentGateways){
       if($PaymentGateways->PaymentGatewayName == "Paypal"){
            $PaypalEmail = $PaymentGateways->Email;
            $AccountType = strtolower($PaymentGateways->AccountType);
            $ApiUrl = strtolower($PaymentGateways->ApiUrl);
            $ClientId = $PaymentGateways->UserId;
       }
            
   }
   
   // get domain details end
   
   if(!$user){
       $app =& JFactory::getApplication();
       $app->redirect('index.php?option=com_register&view=login');
   }
   if($_GET['r']==1){
       $app = JFactory::getApplication();
       $app->enqueueMessage("Suessfully payment done", 'success');
   }
   
   $menuAccessStr=Controlbox::getMenuAccess($user,$pass);
   $menuCustData = explode(":",$menuAccessStr);
   
    $maccarr=array();
    foreach($menuCustData as $menuaccess){
        
        $macess = explode(",",$menuaccess);
        $maccarr[$macess[0]]=$macess[1];
     
    }
   
   $menuCustType=end($menuCustData);
   
   // echo '<pre>';
   // var_dump($menuCustData);
   // dynamic elements
   
   $res = Controlbox::dynamicElements('PendingShipments');
   $elem=array();
   foreach($res as $element){
      $elem[$element->ElementId]=array($element->ElementDescription,$element->ElementStatus,$element->is_mandatory,$element->is_default,$element->ElementValue);
   }
   
   // end
   
// echo '<pre>';   
// var_dump($elem);exit;
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
<link rel="stylesheet" href="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/css/styles.css">
<link rel="stylesheet" href="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/css/demo.css">
<script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform"></script>
<!--<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $ClientId; ?>&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>-->
<script src="https://www.paypal.com/sdk/js?client-id=AWtfHhqYZGWNVk9j2-hMYDuV_zW7fVNtc9UM51e7ZnmaAu4SdjTgt9R2hFGxMUhfCb6Rlga4Li5MHBI5&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>
  <!--AWtfHhqYZGWNVk9j2-hMYDuV_zW7fVNtc9UM51e7ZnmaAu4SdjTgt9R2hFGxMUhfCb6Rlga4Li5MHBI5 -- Iblesoft  -->
  <!--Afn_T4BjyFQOXFwJxZ4U3WlGajRi3_3c6yeUOtehsSL0S31S1wstR147TtNDYTOSRUZObfJsoaSf79MZ -- kupiglobal -->
<style>
.chg-address {
    display: inline-block;
    clear: both;
}

.expand_items.btn.btn-success:focus, .expand_items.btn.btn-success:active{outline: 0; outline-offset: 0;}
.selinpt-chk{    margin: -1px 5px 0 8px !important;
    vertical-align: middle;
    width: 24px;
    height: 24px;
    border-radius: 8px;
}
.chk-boxfld .selinpt-chksub {
    width: 18px;
    height:24px;
}
    .col-img-all {
  float: left;
  width: 25%;
  padding: 10px;
  border: 1px solid;
}
.pdf-view {
  position: relative;
}

/* Style the images inside the grid */
.col-img-all img {
  opacity: 0.8; 
  cursor: pointer; 
  height: 70px;
}

.col-img-all img:hover {
  opacity: 1;
}
/* Clear floats after the columns */
#viewImage .row:after {
  content: "";
  display: table;
  clear: both;
}

/* The expanding image container */
.mult-img-container {
  position: relative;
  display: none;
  margin-top:30px;
}

/* Expanding image text */
#imgalttext {
  position: absolute;
  bottom: 15px;
  left: 15px;
  color: white;
  font-size: 20px;
}
.pdf-img{
    text-decoration:none;
}
.pdf-img img{
width: 40%;
display: block;
text-align: center;
margin: 0 auto;
}
.pdf-img span{
    display:none;
}
.pdf-img span.btn {
    padding: 0px !important;
    border-radius: 8px;
    padding: 10px;
    height: 28px;
    line-height: 28px;
}
.pdf-img:hover span {
    display: block;
    text-align: center;
    font-size: 12px;
    text-transform: uppercase;
    font-weight: bold;
    position: absolute;
    top: 30px;
    left: 24px;
    /* font-size: 10px; */
}
.col-img-all.images-view.img-active {
  border: 3px solid #8dc6d3;
}
@media (max-width: 767px){
    .pdf-img:hover span {
    padding: 0;
    display: block;
    text-align: center;
    font-size: 10px;
    text-transform: uppercase;
    font-weight: bold;
    position: absolute;
    top: 34px;
    left: 3px;
    /* padding: 4px !important; */
    /* font-size: 10px; */
}
.pdf-img span.btn {
    padding: 1px 0px 0px 0 !important;
    border-radius: 8px;
    padding: 10px;
    height: 23px;
    line-height: 22px;
    /* width: 8px; */
    min-width: 81px;
    /* margin: -1px; */
}
}

</style>
<!-- 
   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
   -->
<script type="text/javascript">
   var $joomla = jQuery.noConflict(); 
   $joomla(document).ready(function() {
       
       $joomla('#dvPaymentInformation input[type=text]').on('keyup',function(){
        cardFormValidate();
    });
    
    var AccountType = "<?php echo $AccountType; ?>";
    var ApiUrl = "<?php echo $ApiUrl; ?>";
    
    
     $joomla("#userprofileFormFive").validate({
                rules:  {
                        cardnumberStr: {
                        required: true,
                        minlength:15
                        },
                        MonthDropDownListStr: {
                        required: true
                        },
                        YearDropDownListStr: {
                        required: true
                        },
                        txtccnumberStr: {
                        required: true
                        },
                    },
                messages: {
                            cardnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_NUMBER_ERROR');?>"},
                            MonthDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_MONTH_ERROR');?>"},
                            YearDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_YEAR_ERROR');?>"},
                            txtccnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_CVV_ERROR');?>"}
                         },
                submitHandler: function(form) {
                     $joomla(".page_loader").show();
                     var feitem=[];
               $joomla("input[name='invFile[]']").each( function () {
                   var tem=$joomla(this).attr('id');
                   if($joomla(this).val()){
                       var file_data = $joomla(this).prop('files')[0];   
                       var form_data = new FormData();                  
                       form_data.append('file', file_data);
                       $joomla.ajax({
                       	url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&uploadflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                   	    dataType: 'text',  // what to expect back from the PHP script, if anything
                           cache: false,
                           contentType: false,
                           processData: false,
                           data: form_data,                         
                           type: 'post',
                       	beforeSend: function() {
                             $joomla(".pagshipup").show();
                             $joomla('.pagshipdown').hide();
                             $joomla('#ord_ship #step3 .btn-primary').attr("type", "button");
                             $joomla('#ord_ship #step3 .btn-primary').attr("disabled", true);
                           },success: function(data){
                             $joomla('#ord_ship #step3 .btn-primary').attr("type", "submit");
                             $joomla('#ord_ship #step3 .btn-primary').attr("disabled", false);
                             $joomla(".pagshipup").hide();
                             $joomla('.pagshipdown').show();
                             feitem.push(tem+"-"+data);
                             
                             /** Debug **/
                             console.log(feitem);
                             
                             $joomla('input[name=paypalinvoice]').val(feitem); 
                           }
                       });
                   }
               }); 
                   var articlestrs=[];
               $joomla.each($joomla("input[name='articleStr[]']"), function(){
                   articlestrs.push($joomla(this).val());
               });    
               var pricestrs=[];
               $joomla.each($joomla("input[name='priceStr[]']"), function(){
                   pricestrs.push($joomla(this).val());
               }); 
               
               if($joomla('input[name="cc"]:checked').val() == "Stripe"){
               
                   setTimeout(function () {
                        ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="amount"]').val()+"&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&invidkStr="+$joomla('input[name="invidkStr"]').val()+"&qtyStr="+$joomla('input[name="qtyStr"]').val()+"&wherhourecStr="+$joomla('input[name="wherhourecStr"]').val()+"&user="+$joomla('input[name="user"]').val()+"&txtspecialinsStr="+$joomla('input[name="txtspecialinsStr"]').val()+"&cc=PPD&paymentgateway=Stripe&shipservtStr="+$joomla('input[name="shipservtStr"]').val()+"&consignidStr="+$joomla('input[name="consignidStr"]').val()+"&invf=&filenameStr=&articleStr="+articlestrs+"&priceStr="+pricestrs;
                        $joomla.ajax({
                       			url: ajaxurl,
                       			data: { "paymentgatewayflag":1,"ratetypeStr": $joomla('input[name="ratetypeStr"]').val(),"Conveniencefees":$joomla('input[name="Conveniencefees"]').val(),"addSerStr":$joomla('input[name="addSerStr"]').val(),"addSerCostStr":$joomla('input[name="addSerCostStr"]').val(),"companyId":$joomla('input[name="companyId"]').val(),"insuranceCost":$joomla('input[name="insuranceCost"]').val(),"extAddSer":$joomla('input[name="extAddSer"]').val(),"paypalinvoice":$joomla('input[name="paypalinvoice"]').val(),"length":$joomla("#lengthStr").val(),"width":$joomla("#widthStr").val(),"height":$joomla("#heightStr").val(),"grosswt":$joomla("#weightStr").val(),"volume":$joomla("#volStr").val(),"volumetwt":$joomla("#volmetStr").val(),"totalDecVal":$joomla('#totalDecVal').val(),"shipmentCost":$joomla('input[name=shipmentCost]').val(),"couponCodeStr":$joomla('#couponCodeStr').val(),"couponDiscAmt":$joomla('input[name=couponDiscAmt]').val()},
                       			dataType:"text",
                       			type: "get",
                                beforeSend: function() {
                                   
                                },
                                success: function(data){
                                    res = data.split(":");
                                    if(res[0] == 1){
                                    window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&res="+res[1];
                                    }else{
                                        $joomla(".page_loader").hide();
                                        $joomla(".paygaterrormsg").html(data);
                                    }
                                }
                           });
                           
                   }, 5000); 
               
               }else{
                   form.submit();
               }
   				      
                }
          
          });
          
          
    
    // authoriz.net error msg display
    
    var url_string = window.location.href;
    var url = new URL(url_string);
    var error_msg = url.searchParams.get("error");
    if(error_msg != null ){
        alert(error_msg);
         $joomla(".page_loader").show();
        window.location = "index.php?option=com_userprofile&view=user&layout=orderprocess";
    }
   
    
    function cardFormValidate(){
    var cardValid = 0;

    //card number validation
    $joomla('#card_number').validateCreditCard(function(result){
        if(result.valid){
            $joomla("#card_number").removeClass('required');
            cardValid = 1;
        }else{
            $joomla("#card_number").addClass('required');
            cardValid = 0;
        }
    });
      
    //card details validation
    var cardName = $joomla("#name_on_card").val();
    var expMonth = $joomla("#expiry_month").val();
    var expYear = $joomla("#expiry_year").val();
    var cvv = $joomla("#cvv").val();
    var regName = /^[a-z ,.'-]+$/i;
    var regMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
    var regYear = /^2017|2018|2019|2020|2021|2022|2023|2024|2025|2026|2027|2028|2029|2030|2031$/;
    var regCVV = /^[0-9]{3,3}$/;
    if (cardValid == 0) {
        $joomla("#card_number").addClass('required');
        $joomla("#card_number").focus();
        return false;
    }else if (!regMonth.test(expMonth)) {
        $joomla("#card_number").removeClass('required');
        $joomla("#expiry_month").addClass('required');
        $joomla("#expiry_month").focus();
        return false;
    }else if (!regYear.test(expYear)) {
        $joomla("#card_number").removeClass('required');
        $joomla("#expiry_month").removeClass('required');
        $joomla("#expiry_year").addClass('required');
        $joomla("#expiry_year").focus();
        return false;
    }else if (!regCVV.test(cvv)) {
        $joomla("#card_number").removeClass('required');
        $joomla("#expiry_month").removeClass('required');
        $joomla("#expiry_year").removeClass('required');
        $joomla("#cvv").addClass('required');
        $joomla("#cvv").focus();
        return false;
    }else if (!regName.test(cardName)) {
        $joomla("#card_number").removeClass('required');
        $joomla("#expiry_month").removeClass('required');
        $joomla("#expiry_year").removeClass('required');
        $joomla("#cvv").removeClass('required');
        $joomla("#name_on_card").addClass('required');
        $joomla("#name_on_card").focus();
        return false;
    }else{
        $joomla("#card_number").removeClass('required');
        $joomla("#expiry_month").removeClass('required');
        $joomla("#expiry_year").removeClass('required');
        $joomla("#cvv").removeClass('required');
        $joomla("#name_on_card").removeClass('required');
        return true;
    }
}
      
       // get payment gateways
       
        // $joomla('input[name="paymentmethod"]').on('click',function(e){
        //     $joomla("#paymentgatewaysDiv").show();
        //     var paymentmethod = $joomla(this).val();
        //     var ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&paymentmethod="+paymentmethod;
          
        //     $joomla.ajax({
       	// 		url: ajaxurl,
       	// 		data: { "paymentmethodflag": 1 },
       	// 		dataType:"text",
       	// 		type: "get",
        //         beforeSend: function() {
        //             $joomla(".page_loader").show();
        //         },
        //         success: function(data){
        //             $joomla(".page_loader").hide();
        //             $joomla("#paymentgatewaysDiv").html(data);
        //         }
        //   });
           
        // });
        
        $joomla('.dvPaymentInformation select').on('change',function(e){
             $joomla(".paygaterrormsg").html("");
         });
         $joomla('.dvPaymentInformation input').keyup(function(e){
             $joomla(".paygaterrormsg").html("");
         });
       
       // End
       
       var previousPageText="<?php echo Jtext::_('COM_USERPROFILE_PREVIOUS_PAGE');?>";
       var nextPageText="<?php echo Jtext::_('COM_USERPROFILE_NEXT_PAGE');?>";
       var showEntText="<?php echo Jtext::_('COM_USERPROFILE_SHOW_ENTRIES');?>";
       var searchText="<?php echo Jtext::_('COM_USERPROFILE_SEARCH');?>";
       var showingEntriesText="<?php echo Jtext::_('COM_USERPROFILE_SHOWING_ENTRIES');?>";
       var showingEmptyEntriesText="<?php echo Jtext::_('COM_USERPROFILE_SHOWING_EMPTY_ENTRIES');?>";
       var nodataText = "<?php echo Jtext::_('COM_USERPROFILE_NO_DATA');?>";
       
       
       $joomla('#u_table').DataTable({
           "pagingType": "simple", // "simple" option for 'Previous' and 'Next' buttons only
           "ordering": false,
          "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
         },
            "order": [],
            "columnDefs": [
            { 
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }
            ]
            
    });  
    
     $joomla(document).on('change','input[name="invFile[]"]', function() {
        
            if(this.files.length > 1){
                 alert('<?php echo Jtext::_('Should not exceed more than 1 file.') ?>');
                 $joomla(this).val('');
            }
            
     });
    
       //$joomle('.dataTables_length').addClass('bs-select');
   
       history.pushState(null, null, location.href);
       window.onpopstate = function () {
           history.go(1);
       };
       //$joomla(".input-sm").html('<option value="25">25</option><option value="50">50</option><option value="100">100</option>');
   
       $joomla("input[name='txtQty']").live("blur",function(e){
           
           this.value = this.value.replace(/[^0-9]/g, '');
           if($joomla(this).val().length == 1 && $joomla(this).val() == 0 ||  $joomla(this).val() == ""){
                $joomla(this).val($joomla(this).closest('tr').find("input[name=ItemQtyTxt]").val()); 
           }
       });
       
       
        // select all
        
        
       
      $joomla(document).on('click','#selectAll',function(e){
           // model changes
           $joomla('.shipAll').next().removeClass("not_same_error");
           $joomla("#error").html("");
           $joomla("#error").hide();
           $joomla("#confirmq").show();
           //end
           
           var ischecked= $joomla(this).is(':checked');
           //alert(ischecked);
           if(ischecked){
               $joomla('#ship_all').modal('show');
               $joomla('.shipAll').show();
               //$joomla('#step1 #j_table').show();
               $joomla('tr .ship:first-child').hide();
               
           }else{
               
                $joomla('input[name="txtId"]').each(function(){
                    var shipVal = $joomla(this).val();
                    var shipValArr = shipVal.split(":");
                    var qntVal = shipValArr[2];
                    //var qntVal = $joomla(this).closest('tr').find('td:eq(5)').text();
                    $joomla(this).closest('tr').find('input[name="txtQty"]').val(qntVal);
                    $joomla(this).prop( "checked", false );
                });
              $joomla(".check_all_items").prop("checked",false);
              $joomla('#step1').hide(); 
              $joomla('.shipAll').hide();
              $joomla('tr .ship:first-child').show();
           }
       });
       
       $joomla('.ship_all_close').on('click',function(e){
           $joomla('tr .ship:first-child').show();
       });
       
       $joomla(document).on('click','.pagination li a',function(e){
          $joomla("input[name=selectAll]").prop( "checked", false );
                $joomla("#step1 #j_table").hide();
                $joomla(".btn-danger").trigger("click");
                $joomla("input[name=txtQty]").css("width","80px");
                
       });
       
       
       $joomla(document).on('change','select[name=j_table_length]',function(e){
           $joomla("input[name=txtQty]").css("width","80px");
           
       });
       $joomla("select[name=j_table_length]").val(50);
       $joomla("select[name=j_table_length]").trigger("change");
       
       
       $joomla(document).on('change','select[name=u_table_length]',function(e){
           $joomla("input[name=txtQty]").css("width","80px");
           
       });
       $joomla("select[name=u_table_length]").val(50);
       $joomla("select[name=u_table_length]").trigger("change");
       
       
       
        // ship all
       
        $joomla('.shipAll').on('click',function(){
            
            $joomla("#selectAll").prop( "checked", true );
            $joomla("#error").html("");
            $joomla("#error").hide();
            
            //$joomla(".ship:visible").trigger("click");
            $joomla("input[name=txtId]").prop("checked",false);
            $joomla("input[name=txtId]:visible").trigger("click");
            
        $joomla(".check_all_items").prop( "checked", false );
         
         $joomla(".check_all_items:visible").each(function(){
             $joomla(this).trigger("click");
             $joomla(this).prop("checked",true);
         });
            
            $joomla('#step1').show();
            $joomla('#step1 #j_table').show();
            if($joomla("#error").html().length > 0){
                 $joomla('#confirmq').hide();
                 $joomla('#shipAll').hide();
            }else{
               $joomla('#ship_all').modal('hide');
            }
        });
        
        
         // discard from shiplist
        
         $joomla(document).on('click','.discardShip',function(){
             
            
             
             var wrhsStr = $joomla(this).closest("tr").find(".warehouseStr").html();
             var disSno = $joomla(this).attr("data-sno");
             var discardRes = false;
             
                 $joomla("input[name='txtId']:checked").each( function () {
                     var txtidSno = $joomla(this).attr("data-sno");
                     var valueStr = $joomla(this).val();
                     var valueArr = valueStr.split(":");
                     
                     console.log(valueArr[1]+"#"+ wrhsStr + "#" + disSno + "#" + txtidSno );
                   
                     if(valueArr[1] == wrhsStr && disSno == txtidSno ){
                         $joomla(this).trigger('click');
                         discardRes = true;
                       //   var qntVal = $joomla(this).closest('tr').find('td:eq(5)').text();
                       //   $joomla(this).closest('tr').find('input[name="txtQty"]').val(qntVal);
                       //   $joomla(this).prop("checked",false);
                         
                     }
                     
                 });
                 
                 if(discardRes){
                     $joomla(this).closest("tr").hide();
                      var checkboxLen = $joomla("input[name='txtId']").filter(":checked").length;
                      
                      if(checkboxLen == 0){
                            $joomla('#step1').hide(); 
                            $joomla('#selectAll').prop("checked",false);
                            $joomla('.shipAll').hide();
                            
                            
                      }
                 }
        });
        
        // end
       
   $joomla("textarea[name='specialinstructionStr']").on('keyup',function(){
     $joomla("#count").text((250 - $joomla(this).val().length) + "/250" );
   });
   
       $joomla("input[name=txtQty]").css("width","80px");
       
       $joomla("input[name=txtQty]").live('blur',function(e){
           
           
           
           $joomla(this).closest('tr').find("input[name=ItemQtyEdit]").val($joomla(this).val());
          // $joomla(this).closest('tr').find("input[name=ItemQtyShip]").val($joomla(this).val());
          
          if(parseInt($joomla(this).val()) > parseInt($joomla(this).closest('tr').find("input[name=ItemQtyTxt]").val())){
             alert("Quantity will never update more than previous Quantity");
             $joomla(this).val($joomla(this).closest('tr').find("input[name=ItemQtyTxt]").val()); 
             $joomla($joomla(this).closest('tr').find("input[name=ItemQtyEdit]")).val($joomla(this).closest('tr').find("input[name=ItemQtyTxt]").val());
          }
          
          $joomla('#step1 #j_table tbody').remove(); 
          $joomla('#ord_ship #kk_table tbody').remove(); 
          
          
           if($joomla('#step1 #j_table tr').length>0){
                var tds='<tr>';
                var tdns='<tr>';
                var tdns2='<tr>';
                var whs=[];
                var idksn=[];
                var qtyl=[];
                var volres=[];
                var tyservice=[];
                var sr=[];
                var dt=[];
                var mrunits=[];
                var srhub=[];
                var ht=[];
                var wd=[];
                var lg=[];
                var wt=[];
                var dtunit=[];
                var whtr='';
                var bustype=[];
                var j=0;
                var invCount=0;
                var vol=[];
                var volmetwt=[];
               
                $joomla.each($joomla("input[name='txtId']:checked"), function(){
                   if($joomla(this).val()){
                   var loops=$joomla(this).val();
                   //console.log(loops);
                   var loop=loops.split(":");
                   for(i=0;i<loop.length;i++){
                       //if(loop[i]=="" || loop[i]==null){
                        //tds+="";   
                       //}
                       //else{
                        if(i==0){
                           tds+="<td>"+loop[i]+"</td>";
                           tdns+='<td><input type="text"  id="'+j+'" readonly  name="articleStr[]" class="form-control" value="'+loop[i]+'"></td>';
                           tdns2+='<td><input type="text"  id="'+j+'" readonly  name="article2Str[]" value="'+loop[i]+'"></td>';
                         }    
                        if(i==1){
                           //$joomla('#wherhourecStr').val(loop[i]); 
                           whs.push(loop[i]);
                           tds+="<td class='warehouseStr'>"+loop[i]+"</td>";
                        }    
                        if(i==2){
                           
                          qtyl.push($joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value'));
                          tds+="<td>"+$joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value')+"</td>";
                          tdns+="<td>"+$joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value')+"</td>";
                          tdns2+="<td>"+$joomla(this).closest('tr').find('input[name="txtQty"]').attr('value')+"</td>";
                        }    
                        if(i==3){
                           $joomla('#trackingidStr').val(loop[i]);
                           tds+="<td>"+loop[i]+"</td>";
                           tdns+="<td>"+loop[i]+"</td>";
                           tdns2+="<td>"+loop[i]+"</td>";
                        }
                        if(i==4){
                           idksn.push(loop[i]); 
                           whtr=loop[i];
                        }
                        if(i==5){
                           $joomla('#ItemPriceStr').val(loop[i]);
                           //tdns+="<td>"+loop[i]+"</td>";
                           var quantity = $joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value');
                           var totalCost = quantity*loop[24];
                           tdns+='<td><input type="text" class="form-control pricechange '+ loop[19] +'" data-insurance="'+loop[23]+'"   name="priceStr[]"  id="'+j+i+'"  value="'+totalCost.toFixed(2)+'"></td>';
                           tdns2+='<td><input type="text" name="price2Str[]"  id="'+j+i+'" readonly  value="'+loop[i]+'"></td>';
                        }
                        if(i==6){
                           $joomla('#costStr').val(loop[i]);
                           //tdns+="<td>"+loop[i]+"</td>";
                       }
                        if(i==8){
                           volres.push(loop[i]);
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
                        //if(i==13){
                           //srhub.push(loop[i]);
                           //console.log(loop[i])
                        //}
                        if(i==13){
                           lg.push(loop[i]);
                        }
                        if(i==14){
                           wd.push(loop[i]);
                        }
                        if(i==15){
                           ht.push(loop[i]);
                        }
                        if(i==16){
                           wt.push(loop[i]);
                        }
                        if(i==17){
                           dtunit.push(loop[i]);
                        }
                        if(i==28){
                           bustype.push(loop[i]);
                        }
                        if(i==18){
                         var fileName = loop[i];
                         console.log("fileName:"+fileName);
                         var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                         if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                               invCount++;
                           var hrefs=loop[i];
                           hrefs=hrefs.split(' ').join('%20');
                           hrefs=hrefs.replace("#",":");
                           tdns+='<td> <div><div><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INVOICE_UPLOAD');?></label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtr+'></div><div><p><?php echo Jtext::_('COM_USERPROFILE_SHIP_KTABLE_UPLOAD_EXT_TYPE_TXT');?></p></div></div><div class="clearfix"></div><a class="sfile" href="'+hrefs+'" target="_blank">(<?php echo Jtext::_('COM_USERPROFILE_INVOICE');?>)</a></td>';
                         }else{
                           tdns+='<td> <div><div><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INVOICE_UPLOAD');?></label></div><div><input type="file" class="upoadfe" name="invFile[]"  multiple id='+whtr+'></div><div><p><?php echo Jtext::_('COM_USERPROFILE_SHIP_KTABLE_UPLOAD_EXT_TYPE_TXT');?></p></div></div></td>';
                         }
   
                         var file2Name = loop[i];
                         console.log("file2Name:"+file2Name);
                         var ext = file2Name.substring(file2Name.lastIndexOf('.') + 1);
                         if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                           var hrefs2=loop[i];
                           hrefs2=hrefs2.split(' ').join('%20');
                           hrefs2=hrefs2.replace("#",":");
                           tdns2+='<td> <div><div><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INVOICE_UPLOAD');?></label></div><div><input type="file" class="upoadfe2" name="inv2File[]" multiple id='+whtr+'></div><div><p><?php echo Jtext::_('COM_USERPROFILE_SHIP_KTABLE_UPLOAD_EXT_TYPE_TXT');?></p></div></div><div class="clearfix"></div><a class="sfile" href="'+hrefs2+'" target="_blank">(<?php echo Jtext::_('COM_USERPROFILE_INVOICE');?>)</a></td>';
                         }else{
                           tdns2+='<td> <div><div><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INVOICE_UPLOAD');?></label></div><div><input type="file" class="upoadfe2" name="inv2File[]" multiple id='+whtr+'></div><div><p><?php echo Jtext::_('COM_USERPROFILE_SHIP_KTABLE_UPLOAD_EXT_TYPE_TXT');?></p></div></div></td>';
                         }
   
                        }
                        if(i==29){
                            vol.push(loop[i]);
                        }
                        if(i==30){
                            volmetwt.push(loop[i]);
                        }
                        
       
                       j++;
                      //}
                 }
                 whs.join(", ");
                 idksn.join(", ");
                 volres.join(", ");
                 tyservice.join(", ");
                 sr.join(", ");
                 dt.join(", ");
                 mrunits.join(", ");
                 srhub.join(", ");
                 bustype.join(", ");
                 vol.join(", ");
                 volmetwt.join(", ");
   
                 lg.join(", ");
                 wd.join(", ");
                 ht.join(", ");
                 wt.join(", ");
                 dtunit.join(", ");
                 
                 qtyl.join(", ");
                 $joomla('#qtyStr').val(qtyl);
                 $joomla('#qty2Str').val(qtyl);
                 
                 $joomla('#wherhourecStr').val(whs);
                 $joomla('#wherhourec2Str').val(whs);
                 $joomla('#invidkStr').val(idksn);
                 $joomla('#invidk2Str').val(idksn);
                 $joomla('#volresStr').val(volres);
                 $joomla('#tyserviceStr').val(tyservice);
                 $joomla('#mrunitsStr').val(mrunits);
                 $joomla('#volStr').val(vol);
                 $joomla('#volmetStr').val(volmetwt);
                 
                 $joomla('#lengthStr').val(lg);
                 $joomla('#widthStr').val(wd);
                 $joomla('#heightStr').val(ht);
                 $joomla('#weightStr').val(wt);
                 $joomla('#dtunitStr').val(dtunit);
                 $joomla('#bustypeStr').val(bustype);
                 
                  
                 $joomla('#srhub').val(srhub);
                 $joomla('#srStr').val(sr);
                 $joomla('#dtStr').val(dt);
                 tds+='<td><span data-sno="'+loop[7]+'" class="discardShip"></span></td>';
                
                 tds+="</tr>";
                 tdns+="</tr>";
                 tdns2+="</tr>";
                 
               
                 
                }    
               });
       
               $joomla('#step1 #j_table:first').append(tds);
               //$joomla('#ord_ship #k_table:last').append(tdns);
               $joomla('#ord_ship #kk_table:last').append(tdns);
               $joomla('#ord_ship #ki_table:last').append(tdns2);
               $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);
               $joomla('input[name=cc]').filter(':radio').prop('checked',false);
               $joomla('#divShipCOstOne').html('');
               $joomla('#ChangeShippingAddressNew').html();
               
               var sdf=$joomla('input[name=txtbiladdress]').val();
               var sdatas=sdf.replace(/,/g,"<br>");
               $joomla('#ChangeShippingAddressNew').html(sdatas); 
               $joomla('#toaddressTxt').val(sdf);         
               $joomla('#destingaddreesStr').val(sdatas); 
               
             }
          
          
       });
       
       
        $joomla('input[name=txtService]').live( "click", function() {
           if($joomla('input[name=shipmentStr]:checked').val()=="undifined" || $joomla('input[name=shipmentStr]:checked').val()==null){
           alert("Please check shipping");
           return false;
          }
          if($joomla('input[name=shipmentNewStr]:checked').val()=="undifined" || $joomla('input[name=shipmentNewStr]:checked').val()==null){
           alert("Please check rate type");
           return false;
          }
           
           var ischecked= $joomla(this).is(':checked');
           
           var cosserv=$joomla(this).val();
           cosserv=cosserv.split(":");
           
           var totalview=0;
           if(ischecked){
               
               //$joomla(this).next().after('<span class="serCost_'+cosserv[1]+'" >&nbsp;&nbsp; $' + cosserv[0] + '</span>');
               
               totalview=$joomla('#totalamountDiv').html();
               $joomla('input[name=shipcostStr]').val(parseFloat(parseFloat(totalview)+parseFloat(cosserv[0])).toFixed(2));
               $joomla('#totalamountDiv').html(parseFloat(parseFloat(totalview)+parseFloat(cosserv[0])).toFixed(2));
               $joomla('input[name=amtStr]').val(parseFloat(parseFloat(totalview)+parseFloat(cosserv[0])).toFixed(2));
               $joomla('#shipmethodtotalStr').html(parseFloat(parseFloat(totalview)+parseFloat(cosserv[0])).toFixed(2));
               $joomla('#shipmethodStrValuetwo').html(parseFloat(parseFloat(totalview)+parseFloat(cosserv[0])).toFixed(2));
   
               var additionalcost=0;
               additionalcost=$joomla("#addserStr").html();
               $joomla('#addserStr').html(parseFloat(parseFloat(additionalcost)+parseFloat(cosserv[0])).toFixed(2));
               
               additionalcost1=0;
               additionalcost1=$joomla("#addserStr1").html();
               $joomla('#addserStr1').html(parseFloat(parseFloat(additionalcost1)+parseFloat(cosserv[0])).toFixed(2));
               
           }else{
               
             //$joomla('.serCost_'+cosserv[1]).remove();
               
               totalview=$joomla('#totalamountDiv').html();
               $joomla('input[name=shipcostStr]').val(parseFloat(parseFloat(totalview)-parseFloat(cosserv[0])).toFixed(2));
               $joomla('#totalamountDiv').html(parseFloat(parseFloat(totalview)-parseFloat(cosserv[0])).toFixed(2));
               $joomla('input[name=amtStr]').val(parseFloat(parseFloat(totalview)-parseFloat(cosserv[0])).toFixed(2));
               $joomla('#shipmethodtotalStr').html(parseFloat(parseFloat(totalview)-parseFloat(cosserv[0])).toFixed(2));
               $joomla('#shipmethodStrValuetwo').html(parseFloat(parseFloat(totalview)-parseFloat(cosserv[0])).toFixed(2));
               
               var additionalcost=0;
               additionalcost=$joomla("#addserStr").html();
               $joomla('#addserStr').html(parseFloat(parseFloat(additionalcost)-parseFloat(cosserv[0])).toFixed(2));
               
               additionalcost1=0;
               additionalcost1=$joomla("#addserStr1").html();
               $joomla('#addserStr1').html(parseFloat(parseFloat(additionalcost1)-parseFloat(cosserv[0])).toFixed(2));
           }  
           
           var serStrIds='';
                var serStrCost='';
               
           $joomla.each($joomla("input[name='txtService']:checked"), function(){ 
                   
                   var cosserv=$joomla(this).val();
                   cosserv=cosserv.split(":");
                   
                   serStrIds = serStrIds + cosserv[1] + ',';
                   serStrCost = serStrCost + cosserv[0] + ',';
                   
           });
           
           $joomla("#addSerStr").val(serStrIds);
           $joomla("#addSerCostStr").val(serStrCost);
           
       });  
       
       
        
   
       
       
       //if($joomla( "#orderdateTxt" ))
       //$joomla( "#orderdateTxt" ).datepicker({ maxDate: new Date });
       var tmp='';
       tmp=$joomla("#ord_edit .modal-body").html();
       
   
     
   
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
       
       $joomla('.return').click(function(){
          $joomla("#dfg").remove();  
           $joomla('#idk').val($joomla(this).data('id'));
           $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
          $joomla(":checkbox").prop("checked", false);
          $joomla('#step1').hide(); 
          $joomla(".ship").click(true);  
           $joomla(".txtId").prop( "checked", false );
           $joomla.each($joomla("input[name='txtId']"), function(){ 
             $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
           })
           $joomla("#ord_ship .ship").click(true); 
           $joomla.each($joomla(".wrchild"), function(){ 
               $joomla('.wrchild').val('+');
           }) 
           $joomla('#ChangeShippingAddress').hide(); 
          
       });
       $joomla('.keep').click(function(){
          $joomla("#dfg").remove();  
           $joomla('#idk2').val($joomla(this).data('id'));
           $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
          $joomla(":checkbox").prop("checked", false);
          $joomla('#step1').hide(); 
          $joomla(".ship").click(true);  
           $joomla(".txtId").prop( "checked", false );
           $joomla.each($joomla("input[name='txtId']"), function(){ 
             $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
           })
           $joomla("#ord_ship .ship").click(true); 
           $joomla.each($joomla(".wrchild"), function(){ 
               $joomla('.wrchild').val('+');
           })    
           $joomla('#ChangeShippingAddress').hide(); 
   
       });
       $joomla('.discardc').click(function(){
          $joomla("#dfg").remove();  
           $joomla('#idk3').val($joomla(this).data('id'));
           $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
          $joomla(":checkbox").prop("checked", false);
          $joomla('#step1').hide(); 
          $joomla(".ship").click(true);  
           $joomla(".txtId").prop( "checked", false );
   
           $joomla.each($joomla("input[name='txtId']"), function(){ 
             $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
           })
           $joomla("#ord_ship .ship").click(true); 
           $joomla.each($joomla(".wrchild"), function(){ 
               $joomla('.wrchild').val('+');
           })    
           $joomla('#ChangeShippingAddress').hide(); 
       });
       
       $joomla('#ord_ship .btn-primary:first').click(function(){
          $joomla("#loading-image").hide();
          $joomla('#step1').hide(); 
          $joomla('#ord_ship #step2').show();
          $joomla('#ord_ship #step3').hide();
       });
       $joomla('#ord_ship .btn-back').click(function(){
          $joomla('#shipmethodtotalStr').html($joomla('input[name="shipcostStr"]').val());
          $joomla('#step1').hide(); 
          $joomla('#ord_ship #step2').show();
          $joomla('#ord_ship #step3').hide();
       });
       
       
       $joomla('#ChangeShippingAddressStr').click(function(){
          loadadditionalusersData();
          $joomla('#ChangeShippingAddressHidden').html($joomla('#ChangeShippingAddressNew').html());
          $joomla('#dtStr2').val($joomla('#dtStr').val());
          $joomla('#ChangeShippingAddress').toggle(); 
       });
       
      $joomla('.btn-close1,#step1 .btn-danger,#step2 .btn-danger,#step3 .btn-danger,.not_same_error').on("click",function(){
          $joomla(".paypalCreditDebit").hide();
          if($joomla("#exampleModal").css('display')=="block"){
              console.log('exampleModal');
          }else{
              $joomla("#dfg").remove();  
              $joomla(":checkbox").prop("checked", false);
              $joomla("input[name=paymentmethod]").prop("checked", false);
              $joomla('#paymentgatewaysDiv').hide();
              $joomla('#step1').hide(); 
              $joomla('#step1 #j_table tbody').remove();    
              $joomla('#ord_ship #k_table tbody').remove(); 
              $joomla('#ord_ship #ki_table tbody').remove(); 
              $joomla('#specialinstructionStr').val('');
              $joomla('#fnameTxt').val('');
              $joomla('#lnameTxt').val('');
              $joomla('#zipTxt').val('');
              $joomla('#addressTxt').val('');
              $joomla('#emailTxt').val('');
              $joomla('[name="consignidStr"]').val('');
              $joomla(".txtId").prop( "checked", false );
              if($joomla('input[name="shipment"]:checked').val()=="1"){
                  $joomla('#tabs2 #j_table tbody tr#dfg').remove();
              }else{
                  $joomla('#tabs2 #u_table tbody tr#dfg').remove();
              }   
          
               $joomla.each($joomla("input[name='txtId']"), function(){ 
                   
                   $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
                   originalVal = $joomla(this).closest('tr').find('input[name="ItemQtyTxt"]').val();
                   $joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value',originalVal);
                   $joomla(this).closest('tr').find('input[name="txtQty"]').val(originalVal);
                   $joomla(this).closest('tr').find('td:eq(3)').html(originalVal);
               
               })
               
               $joomla("#ord_ship .ship").click(true); 
               $joomla.each($joomla(".wrchild"), function(){ 
                   $joomla('.wrchild').val('+');
               })    
               $joomla('#ChangeShippingAddress').hide(); 
          }    
       });
       
       
        $joomla('.btn-danger').on("click",function(){
            
        var checkboxLen = $joomla("input[name='txtId']").filter(":checked").length;  
        
        if(checkboxLen == 0){
          
          if($joomla("#exampleModal").css('display')=="block"){
              console.log('exampleModal');
          }else{
              $joomla("#dfg").remove();  
              $joomla(":checkbox").prop("checked", false);
              $joomla("input[name=paymentmethod]").prop("checked", false);
              $joomla('#paymentgatewaysDiv').hide();
              $joomla('#step1').hide(); 
              $joomla('#step1 #j_table tbody').remove();    
              $joomla('#ord_ship #k_table tbody').remove(); 
              $joomla('#ord_ship #ki_table tbody').remove(); 
              $joomla('#specialinstructionStr').val('');
              $joomla('#fnameTxt').val('');
              $joomla('#lnameTxt').val('');
              $joomla('#zipTxt').val('');
              $joomla('#addressTxt').val('');
              $joomla('#emailTxt').val('');
              $joomla('[name="consignidStr"]').val('');
              $joomla(".txtId").prop( "checked", false );
              if($joomla('input[name="shipment"]:checked').val()=="1"){
                  $joomla('#tabs2 #j_table tbody tr#dfg').remove();
              }else{
                  $joomla('#tabs2 #u_table tbody tr#dfg').remove();
              }   
          
               $joomla.each($joomla("input[name='txtId']"), function(){ 
                   
                   $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
                   originalVal = $joomla(this).closest('tr').find('input[name="ItemQtyTxt"]').val();
                   $joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value',originalVal);
                   $joomla(this).closest('tr').find('input[name="txtQty"]').val(originalVal);
                   $joomla(this).closest('tr').find('td:eq(3)').html(originalVal);
               
               })
               
               $joomla("#ord_ship .ship").click(true); 
               $joomla.each($joomla(".wrchild"), function(){ 
                   $joomla('.wrchild').val('+');
               })    
               $joomla('#ChangeShippingAddress').hide(); 
          } 
          
        }else{
                $joomla("input[name='selectAll']").prop("checked",false);
        } 
          
       });
       
       
         $joomla('#kk_table input[name="priceStr[]"]').on("keyup",function(e){
          
           if($joomla(this).val().length == 1 && $joomla(this).val() == 0 ||  $joomla(this).val() == ""){
           	$joomla(this).val($joomla(this).prev().text());
           }
       });
       
       $joomla(document).on('blur', '#kk_table input[name="priceStr[]"]',function(){
           
               if($joomla(this).val() == 0 || $joomla(this).val() == 0.0 || $joomla(this).val() == 0.00 ||  $joomla(this).val() == ""){
               	$joomla(this).val($joomla(this).prev().text());
               	alert("Please enter a valid number");
               	return false;
               }
               $joomla('input[name="shipmentStr"]').prop("checked",false);
               $joomla('input[name="shipmentNewStr"]').prop("checked",false);
               $joomla('#divShipCOstOne').hide();
               $joomla('#divShipCOstTwo').hide();
               
       });
       
   
      $joomla('#step1').on("click",'.shipsubmit',function(){
          
         
           $joomla('#divShipCOstOne').removeClass('rdo_cust shp-addnew1');
           $joomla('#divShipCOstTwo').removeClass('rst_text shp-addnew1');
           
             var dvalue=[];
               // $joomla.each($joomla("#kk_table input[name='priceStr[]'].True"), function(){
                $joomla.each($joomla("#kk_table input[name='priceStr[]']"), function(){
                    if($joomla(this).attr("data-insurance") == "True")
                            dvalue.push($joomla(this).val());
               }); 
               
               var total = 0;
               for (var i = 0; i < dvalue.length; i++) {
                   total = parseFloat(total) + parseFloat(dvalue[i]);
               }
               
               dtotal = total.toFixed(2);
          
          $joomla('input[value=DHL]').hide();
          $joomla('input[value=DHL]').next().hide();
          $joomla('#divShipCOstOne').html('');
          $joomla('#divShipCOstTwo').html('');
          
          $joomla("#loading-image").hide();
          $joomla('#ord_ship #step2').show();
          $joomla('#ord_ship #step3').hide();
          $joomla('#ord_ship #step4').hide();
          //alert($joomla('input[name=shipment]:checked').closest(".rdo_rd1").find("label").text())
          if($joomla('input[name=shipment]:checked').closest(".rdo_rd1").find("label").text()=="Ready To Ship J")
           {
              $joomla('#ord_ship #step2').hide();
              $joomla('#ord_ship #step3').hide();
              $joomla('#ord_ship #step4').show();
           }
           else{
              $joomla('#ord_ship #step2').show();
              $joomla('#ord_ship #step3').hide();
               
           }
           var whsc=$joomla('#wherhourecStr').val();
           whsc=whsc.split(",");
           var shipetype=$joomla('#shipmenttype').val();
           var ulks="<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&paymenttype&dvalue="+ dtotal +"&wherhourec="+ whsc+"&invidk="+$joomla('#invidkStr').val() +"&qty="+$joomla('#qtyStr').val() +"&destination="+$joomla('#dtStr').val() +"&volres="+$joomla('#volresStr').val() +"&tyserv="+$joomla('#tyserviceStr').val() +"&munits="+$joomla('#mrunitsStr').val()+"&source="+$joomla('#srStr').val()+"&shiptype="+shipetype+"&user=<?php echo $user;?>&shippment2flag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime();
          
           $joomla.ajax({
   			url: ulks,
   			data: { "sd": $joomla(this).val() },
   			dataType:"html",
   			type: "get",
               beforeSend: function() {
                   //$joomla(".pagshipup").show();
                   $joomla(".page_loader").show();
                   $joomla('.pagshipdown').hide();
               },
               success: function(data){
                   //$joomla(".pagshipup").hide();
                   $joomla(".page_loader").hide();
                   $joomla('.pagshipdown').show();
                  $joomla('#divShiprates').html(data);
              }
           });
           
           var shipetype=$joomla('#shipmenttype').val();
           var urls="<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&destination="+$joomla('#dtStr').val() +"&source="+$joomla('#srStr').val()+"&shiptype="+shipetype+"&user=<?php echo $user;?>&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime();
          
           $joomla.ajax({
   			url: urls,
   			data: { "shippmenttypeflag": '1' },
   			dataType:"html",
   			type: "get",
               beforeSend: function() {
               },
               success: function(data){
                 $joomla('#getServiceTypeDiv').html(data);
             }
           });
           
           
           
   
       });
       
       
   
   
   $joomla(document).on('click','#goto_payment_scr',function(){
       
       var rateTypesText = $joomla('#divShipCOstTwo').text();
       var decValsErr=0;
       
       $joomla(".pricechange").each(function(){
           
           if($joomla(this).val() =="" || $joomla(this).val() =="0.00"){
               decValsErr=1;
               return false;
           }
           
       });
       
       
       if($joomla('#ChangeShippingAddressNew').html()==""){
               alert("Please select shipping address");
               return false;
              
          }else if($joomla('input[name=shipmentStr]:checked').val()=="undifined" || $joomla('input[name=shipmentStr]:checked').val()==null || $joomla('input[name=shipmentNewStr]:checked').val()=="undifined" || $joomla('input[name=shipmentNewStr]:checked').val()==null){
              
              if($joomla('input[name=shipmentStr]:checked').val()=="undifined" || $joomla('input[name=shipmentStr]:checked').val()==null){
                   alert("<?php echo Jtext::_('COM_USERPROFILE_SHIP_CHECK_SHIPPING') ?>");
              }
              else if($joomla('input[name=shipmentNewStr]:checked').val()=="undifined" || $joomla('input[name=shipmentNewStr]:checked').val()==null){
                   alert("<?php echo Jtext::_('COM_USERPROFILE_SHIP_CHECK_RATETYPE') ?>");
              }
           
           return false;
          }else if(rateTypesText.includes("Rates issue")){
              alert("<?php echo Jtext::_('COM_USERPROFILE_SHIP_RATE_ISSUE') ?>");
           return false;
          }else if(decValsErr == 1){
              alert("<?php echo Jtext::_('Please enter declared value') ?>");
              return false
          }else{
            
            $joomla('input[name="cc"]').prop("checked", false);
            $joomla('input[name="ccStr"]').prop("checked", false);
            $joomla('input[name="cardnumberStr"]').val('');
            $joomla('input[name="txtccnumberStr"]').val('');
            $joomla('input[name="txtNameonCardStr"]').val('');
            $joomla('.dvPaymentInformation').css('display','none');
            $joomla('#dvPaymentMethod').hide();          
            $joomla('select[name="MonthDropDownListStr"]').val('');
            $joomla('select[name="YearDropDownListStr"]').val('');
            $joomla('#specialinstructionDiv').html($joomla('textarea[name=specialinstructionStr]').val());
            $joomla('#txtspecialinsStr').val($joomla('textarea[name=specialinstructionStr]').val());
            $joomla('#step1').hide(); 
            $joomla('#ord_ship #step2').hide(); 
            $joomla('#ord_ship #step3').show();
          } 
          var serStrIds='';
          var serStrCost="";
          $joomla("input[name='txtService']:checked").each(function(){ 
                   var cosserv=$joomla(this).val();
                   cosserv=cosserv.split(":");
                   serStrIds = serStrIds + cosserv[1] + ',';
                   serStrCost = serStrCost + cosserv[0] + ',';
           });
            
           $joomla("#addSerStr").val(serStrIds);
           $joomla("#addSerCostStr").val(serStrCost);
           
   
            var unselList='';
            $joomla(".unselecteServices:checked").each(function(){
               
               var uncosserv=$joomla(this).val();
               var uncosservArr=uncosserv.split(":");
               unselList = unselList + uncosservArr[1] + ",";
                  
            });
            
            unselList = unselList.replace(/,+$/, '');
            $joomla("#extAddSer").val(unselList);
           
          
   });
   
       
      
       
           
       $joomla('select[name="adduserlistStr"]').live('change',function(){
           $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);
           $joomla('#divShipCOstTwo').html('');
           $joomla('#divShipCOstOne').removeClass('rdo_cust shp-addnew1');
           
           
           if($joomla(this).val()==0){
               $joomla('#dtStr').val($joomla('#dtStr2').val());
               $joomla('#ChangeShippingAddressNew').html(''); 
               $joomla('#ChangeShippingAddressNew').html($joomla('#ChangeShippingAddressHidden').html());
               $joomla("input[name='consignidStr']").val('');
   
               var whsc=$joomla('#wherhourecStr').val();
               whsc=whsc.split(",");
               var shipetype=$joomla('#shipmenttype').val();
               var ulks="<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&paymenttype&wherhourec="+ whsc+"&invidk="+$joomla('#invidkStr').val() +"&qty="+$joomla('#qtyStr').val() +"&destination="+$joomla('#dtStr2').val() +"&volres="+$joomla('#volresStr').val() +"&tyserv="+$joomla('#tyserviceStr').val() +"&munits="+$joomla('#mrunitsStr').val()+"&source="+$joomla('#srStr').val()+"&shiptype="+shipetype+"&user=<?php echo $user;?>&shippment2flag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime();
              
               $joomla.ajax({
       			url: ulks,
       			data: { "sd": $joomla(this).val() },
       			dataType:"html",
       			type: "get",
                   beforeSend: function() {
                       $joomla(".pagshipup").show();
                       $joomla('.pagshipdown').hide();
                   },
                   success: function(data){
                       $joomla(".pagshipup").hide();
                       $joomla('.pagshipdown').show();
                      $joomla('#divShiprates').html(data);
                  }
               });   
   
   
          }else{    
   
               var vk=$joomla(this).val();
               vk=vk.split(":");
               var whsc=$joomla('#wherhourecStr').val();
               whsc=whsc.split(",");
               var shipetype=$joomla('#shipmenttype').val();
               var ulks="<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&paymenttype&wherhourec="+ whsc+"&invidk="+$joomla('#invidkStr').val() +"&qty="+$joomla('#qtyStr').val() +"&destination="+vk[1] +"&volres="+$joomla('#volresStr').val() +"&tyserv="+$joomla('#tyserviceStr').val() +"&munits="+$joomla('#mrunitsStr').val()+"&source="+$joomla('#srStr').val()+"&shiptype="+shipetype+"&user=<?php echo $user;?>&shippment2flag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime();
              
               $joomla.ajax({
       			url: ulks,
       			data: { "sd": $joomla(this).val() },
       			dataType:"html",
       			type: "get",
                   beforeSend: function() {
                       $joomla(".pagshipup").show();
                       $joomla('.pagshipdown').hide();
                   },
                   success: function(data){
                       $joomla(".pagshipup").hide();
                       $joomla('.pagshipdown').show();
                      $joomla('#divShiprates').html(data);
                  }
               });   
   
    
               $joomla.ajax({
                   url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&adduserid="+vk[0] +"&adduserflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
       			data: { "shippmentid": vk[0] },
       			dataType:"html",
       			type: "get",
       			beforeSend: function() {
                       $joomla(".pagshipup").show();
                       $joomla('.pagshipdown').hide();
                       //$joomla("#loading-image2").show();
                       $joomla('#ord_ship #step2 .btn-primary').attr("disabled", true);
                  },success: function(data){
                       $joomla(".pagshipup").hide();
                       $joomla('.pagshipdown').show();
                       $joomla('#ord_ship #step2 .btn-primary').attr("disabled", false);
                       $joomla('#dtStr').val(vk[1]);
                       var sdata=data;
                       sdata=sdata.replace(",/gi","<br>");
                       $joomla('#ChangeShippingAddressNew').html(sdata); 
                       //$joomla('#ChangeShippingAddress').hide();
                       $joomla("#loading-image2").hide();
                       $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);	
                       $joomla("#divShipCOstOne").html('');
                       $joomla("input[name='consignidStr']").val(vk[0]);
                       
       		    }
       		});
           }
       });
       
       
        $joomla(document).on('click','input[name=paymentmethod]',function(){
            
          if($joomla(this).val() == 'COD'){
                  $joomla('#userprofileFormFive').attr('action','');        
                  $joomla('#dvPaymentMethod').hide();
                  $joomla(".dvPaymentInformation").hide();
                  $joomla(".prepaid_method_sec").hide();
                  $joomla("input[name='prepaidMethod']").prop("checked",false);
                  $joomla('#shipmethodtotalStr').text($joomla('input[name="shipcostStr"]').val());
                  $joomla('input[name=amtStr]').val($joomla('input[name="shipcostStr"]').val());
                  $joomla('input[name=amount]').val($joomla('input[name="shipcostStr"]').val());
          }
            
        });
        
        // for authorization start
        
        $joomla(document).on('click','input[name=prepaidMethod]',function(){
            
        });
        
         // for authorization end
       
       $joomla(document).on('click','input[name=cc]',function(){
           
           
          if($joomla(this).val() == 'Paypal'){
              
              // credit / debit card integration changes
                 $joomla(".paypalCreditDebit").show();
                 $joomla(".final_btns").hide();
              //  end
              
              $joomla(".final_ship").attr("onclick","return onGetCardNonce(event)");
               
               $joomla(".dvPaymentInformation").hide();
                 var feitem=[];
               $joomla("input[name='invFile[]']").each( function () {
                   
                   var tem=$joomla(this).attr('id');
                   if($joomla(this).val()){
                       var file_data = $joomla(this).prop('files')[0];   
                       var form_data = new FormData();                  
                       form_data.append('file', file_data);
                       $joomla.ajax({
                       	url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&uploadflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                   	    dataType: 'text',  // what to expect back from the PHP script, if anything
                           cache: false,
                           contentType: false,
                           processData: false,
                           data: form_data,                         
                           type: 'post',
                       	beforeSend: function() {
                             $joomla(".pagshipup").show();
                             $joomla('.pagshipdown').hide();
                             $joomla('#ord_ship #step3 .btn-primary').attr("type", "button");
                             $joomla('#ord_ship #step3 .btn-primary').attr("disabled", true);
                           },success: function(data){
                             $joomla('#ord_ship #step3 .btn-primary').attr("type", "submit");
                             $joomla('#ord_ship #step3 .btn-primary').attr("disabled", false);
                             $joomla(".pagshipup").hide();
                             $joomla('.pagshipdown').show();
                             feitem.push(tem+"-"+data);
                             
                             
                             /** Debug **/
                          
                             $joomla('input[name=paypalinvoice]').val(feitem); 
                           }
                       });
                   }
               }); 
               var articlestrs=[];
               $joomla.each($joomla("input[name='articleStr[]']"), function(){
                   articlestrs.push($joomla(this).val());
               });    
               var pricestrs=[];
               $joomla.each($joomla("input[name='priceStr[]']"), function(){
                   pricestrs.push($joomla(this).val());
               });    
               var user="<?php echo $user;?>";
               $joomla('input[name="data_test"]').val($joomla('input[name="invidkStr"]').val()+":"+$joomla('[name="wherhourecStr"]').val()+":"+$joomla('[name="consignidStr"]').val()+":"+$joomla('input[name="txtspecialinsStr"]').val()+":"+$joomla('input[name="shipservtStr"]').val()+":"+$joomla('input[name="paypalinvoice"]').val()+":"+articlestrs+":"+pricestrs+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="addSerStr"]').val()+":"+$joomla('input[name="addSerCostStr"]').val()+":"+$joomla('input[name="ratetypeStr"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+$joomla('input[name="insuranceCost"]').val()+":"+$joomla('input[name="extAddSer"]').val()+":"+$joomla("#lengthStr").val()+":"+$joomla("#widthStr").val()+":"+$joomla("#heightStr").val()+":"+$joomla("#weightStr").val()+":"+$joomla("#volStr").val()+":"+$joomla("#volmetStr").val()+":"+$joomla('#totalDecVal').val()+":"+$joomla('input[name=shipmentCost]').val()+":"+$joomla('#couponCodeStr').val()+":"+$joomla('input[name=couponDiscAmt]').val()+":"+user);
               $joomla('input[name="item_name"]').val(''+":"+user);
               $joomla('input[name="item_number"]').val($joomla('input[name="qtyStr"]').val());
               $joomla('input[name="amount"]').val($joomla('input[name="amtStr"]').val());
                
               $joomla('#userprofileFormFive').attr('action',ApiUrl); 
               
               $joomla('#ord_ship #step3 .btn-primary').attr("disabled", false);
               
               
           }else if($joomla(this).val() == 'authorize.net'){
                $joomla(".paypalCreditDebit").hide();
                $joomla(".final_btns").show();
                $joomla(".dvPaymentInformation input").val("");
                $joomla(".dvPaymentInformation select").val("");
                $joomla(".final_ship").removeAttr("onclick");
              
               var articlestrs=[];
               $joomla.each($joomla("input[name='articleStr[]']"), function(){
                   articlestrs.push($joomla(this).val());
               });    
               var pricestrs=[];
               $joomla.each($joomla("input[name='priceStr[]']"), function(){
                   pricestrs.push($joomla(this).val());
               });    
               var user="<?php echo $user;?>";
               $joomla('input[name="item_name"]').val($joomla('input[name="invidkStr"]').val()+":"+$joomla('[name="wherhourecStr"]').val()+":"+$joomla('[name="consignidStr"]').val()+":"+$joomla('input[name="txtspecialinsStr"]').val()+":"+$joomla('input[name="shipservtStr"]').val()+":"+$joomla('input[name="paypalinvoice"]').val()+":"+articlestrs+":"+pricestrs+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="addSerStr"]').val()+":"+$joomla('input[name="addSerCostStr"]').val()+":"+$joomla('input[name="ratetypeStr"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+$joomla('input[name="insuranceCost"]').val()+":"+$joomla('input[name="extAddSer"]').val()+":"+$joomla("#lengthStr").val()+":"+$joomla("#widthStr").val()+":"+$joomla("#heightStr").val()+":"+$joomla("#weightStr").val()+":"+$joomla("#volStr").val()+":"+$joomla("#volmetStr").val()+":"+$joomla('#totalDecVal').val()+":"+$joomla('input[name=shipmentCost]').val()+":"+$joomla('#couponCodeStr').val()+":"+$joomla('input[name=couponDiscAmt]').val()+":"+user);
               $joomla('input[name="item_number"]').val($joomla('input[name="qtyStr"]').val());
               $joomla('#shipmethodtotalStr').text($joomla('input[name="shipcostStr"]').val());
               $joomla('input[name="amount"]').val(parseFloat($joomla('#shipmethodtotalStr').html()));
               
                $joomla(".dvPaymentInformation").show();
                $joomla('#userprofileFormFive').attr('action','<?php echo JURI::base(); ?>payment.php');
            }else if($joomla(this).val() == 'Stripe'){
                $joomla(".paypalCreditDebit").hide();
                $joomla(".final_btns").show();
                $joomla(".dvPaymentInformation input").val("");
                $joomla(".dvPaymentInformation select").val("");
                $joomla(".final_ship").removeAttr("onclick");
                  $joomla('input[name=amount]').val($joomla('input[name="shipcostStr"]').val());
                  $joomla('#shipmethodtotalStr').text($joomla('input[name="shipcostStr"]').val());
                  $joomla('#userprofileFormFive').attr('action','');
                  $joomla(".dvPaymentInformation").show();
                }else{
                  $joomla(".paypalCreditDebit").hide();
                  $joomla(".final_btns").show();
                  $joomla(".final_ship").attr("onclick","return onGetCardNonce(event)");
                  $joomla('#userprofileFormFive').attr('action','');        
                  $joomla('#dvPaymentMethod').hide();
                  $joomla(".dvPaymentInformation").hide();
                  $joomla(".prepaid_method_sec").hide();
                  $joomla("input[name='prepaidMethod']").prop("checked",false);
                  $joomla('#shipmethodtotalStr').text($joomla('input[name="shipcostStr"]').val());
                  $joomla('input[name=amtStr]').val($joomla('input[name="shipcostStr"]').val());
                  $joomla('input[name=amount]').val($joomla('input[name="shipcostStr"]').val());
                  
           }
           
           
           if($joomla(this).val() != 'COD'){
            // convenience fees 
               $joomla.ajax({
                   url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="shipcostStr"]').val() +"&convflag=1&gateway="+$joomla(this).val()+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
       			data: { "shippmentid": 1 },
       			dataType:"html",
       			type: "get",
       			beforeSend: function() {
                     //$joomla(".pagshipup_prepaid").show();
                     $joomla(".page_loader").show();
                     $joomla('#ord_ship #step2 .btn-primary').attr("disabled", true);
                  },success: function(data){
                      slot=1
                     
                      var sg=data;
                      sg=sg.split(":");
                      $joomla('input[name="Conveniencefees"]').val(sg[1]);
                      $joomla('input[name=amtStr]').val(sg[0]);
                      $joomla('#shipmethodtotalStr').text(sg[0]);
                       $joomla('input[name=amount]').val(sg[0]);
                       $joomla('input[name="item_name"]').val($joomla('input[name="invidkStr"]').val()+":"+$joomla('[name="wherhourecStr"]').val()+":"+$joomla('[name="consignidStr"]').val()+":"+$joomla('input[name="txtspecialinsStr"]').val()+":"+$joomla('input[name="shipservtStr"]').val()+":"+$joomla('input[name="paypalinvoice"]').val()+":"+articlestrs+":"+pricestrs+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="addSerStr"]').val()+":"+$joomla('input[name="addSerCostStr"]').val()+":"+$joomla('input[name="ratetypeStr"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+$joomla('input[name="insuranceCost"]').val()+":"+$joomla('input[name="extAddSer"]').val()+":"+$joomla("#lengthStr").val()+":"+$joomla("#widthStr").val()+":"+$joomla("#heightStr").val()+":"+$joomla("#weightStr").val()+":"+$joomla("#volStr").val()+":"+$joomla("#volmetStr").val()+":"+$joomla('#totalDecVal').val()+":"+$joomla('input[name=shipmentCost]').val()+":"+$joomla('#couponCodeStr').val()+":"+$joomla('input[name=couponDiscAmt]').val()+":"+user);
                       $joomla('input[name="data_test"]').val($joomla('input[name="invidkStr"]').val()+":"+$joomla('[name="wherhourecStr"]').val()+":"+$joomla('[name="consignidStr"]').val()+":"+$joomla('input[name="txtspecialinsStr"]').val()+":"+$joomla('input[name="shipservtStr"]').val()+":"+$joomla('input[name="paypalinvoice"]').val()+":"+articlestrs+":"+pricestrs+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="addSerStr"]').val()+":"+$joomla('input[name="addSerCostStr"]').val()+":"+$joomla('input[name="ratetypeStr"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+$joomla('input[name="insuranceCost"]').val()+":"+$joomla('input[name="extAddSer"]').val()+":"+$joomla("#lengthStr").val()+":"+$joomla("#widthStr").val()+":"+$joomla("#heightStr").val()+":"+$joomla("#weightStr").val()+":"+$joomla("#volStr").val()+":"+$joomla("#volmetStr").val()+":"+$joomla('#totalDecVal').val()+":"+$joomla('input[name=shipmentCost]').val()+":"+$joomla('#couponCodeStr').val()+":"+$joomla('input[name=couponDiscAmt]').val()+":"+user);
               
                       $joomla(".page_loader").hide();
                      //$joomla(".pagshipup_prepaid").hide();
                      
                     $joomla('#ord_ship #step2 .btn-primary').attr("disabled", false);
                  }
               });
               
           }  
           
           
           
          
       });
       
   
       $joomla('input[name=ccStr]').click(function(){
          $joomla('.paymentopt').toggle(); 
       });
       
        $joomla(document).on('click','input[name=shipmentStr],#shipmentNewStr',function(){
            
           shippingCost = 0;
            var rateType = $joomla("input[name=shipmentNewStr]:checked").val();
            if(rateType != undefined){
                var rateTypeArr = rateType.split(":");
                rateType = rateTypeArr[6];
                shippingCost = rateTypeArr[4];
                $joomla('input[name=shipmentCost]').val(shippingCost);
                
            }
            
            
            
            $joomla('#divShipCOstOne').addClass("rdo_cust shp-addnew1");
            $joomla('#divShipCOstOne').show();
           
           var resv=$joomla('input[name=shipmentStr]').val();
           var whsc=$joomla('#wherhourecStr').val()
           whsc=whsc.split(",");
   
           var shipetype=$joomla('#shipmenttype').val();
           var bustype=$joomla('#bustypeStr').val();
           var bustypeArr = bustype.split(",");
           var bustypeInd = bustypeArr[0];
           
             var dvalue=[];
             var dvalueStr = "";
               // $joomla.each($joomla("#kk_table input[name='priceStr[]'].True"), function(){
               $joomla.each($joomla("#kk_table input[name='priceStr[]']"), function(){
                  if($joomla(this).attr("data-insurance") == "True"){
                   dvalue.push($joomla(this).val());
                   dvalueStr +=$joomla(this).val()+",";
                  }
               }); 
               
               var total = 0;
               for (var i = 0; i < dvalue.length; i++) {
                   total = parseFloat(total) + parseFloat(dvalue[i]);
               }
               
               dtotal = total.toFixed(2);
               
               $joomla('#totalDecVal').val(dtotal);
               
         
           $joomla.ajax({
   			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&paymenttype="+$joomla('input[name=shipmentStr]').val() +"&wherhourec="+ whsc+"&invidk="+$joomla('#invidkStr').val() +"&qty="+$joomla('#qtyStr').val() +"&destination="+$joomla('#dtStr').val() +"&volres="+$joomla('#volresStr').val() +"&tyserv="+$joomla('#tyserviceStr').val() +"&munits="+$joomla('#mrunitsStr').val()+"&source="+$joomla('#srStr').val()+"&shiptype="+shipetype+"&bustype="+bustypeInd+"&dvalue="+dtotal+"&user=<?php echo $user;?>&shippmentflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
   			data: { "shippmentid": $joomla('input[name=shipmentStr]').val(),"length":$joomla("#lengthStr").val(),"width":$joomla("#widthStr").val(),"height":$joomla("#heightStr").val(),"grosswt":$joomla("#weightStr").val(),"volume":$joomla("#volStr").val(),"volumetwt":$joomla("#volmetStr").val(),"dvalueStr":dvalueStr,"shipmentCost":shippingCost,"destCnt":$joomla("#dtStr").val(),"rateType":rateType},
   			dataType:"html",
   			type: "get",
   			beforeSend: function() {
                 //$joomla("#divShipCOstOne").html("<img src='/components/com_userprofile/images/loader.gif'>");
                 $joomla('.page_loader').show();
                 $joomla('#ord_ship #step2 .btn-primary').attr("disabled", true);
              },success: function(data){
                  
                  $joomla('#ord_ship #step2 .btn-primary').attr("disabled", false);
                  //$joomla("#divShipCOstOne").html('');
                  $joomla('.page_loader').hide();
                  //$joomla('#divShipCOstTwo').html('');
                  $joomla('#divShipCOstOne').html(data+'<div class="clearfix"></div>');
                                 
                  if(data==""){
                     $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);
                     $joomla('#divShipCOstOne').removeClass("rdo_cust shp-addnew1");
                     $joomla('#divShipCOstTwo').removeClass("rst_text shp-addnew1");
                     alert("No service rates available for destination");                   
                  }
                
   	    	}
   		});
   		
   		
   		 $joomla('#divShipCOstTwo').show();
         $joomla('#divShipCOstTwo').addClass("rst_text shp-addnew1");
           
           var scosship=$joomla('input[name=shipmentNewStr]:checked').val();
          scosship=scosship.split(":");
      
          $joomla('#shipmethodStrValue').html(scosship[4]);
          $joomla('#shipmethodStrValuetwo').html(scosship[0]);
          var sfixedNum = parseFloat(scosship[0]).toFixed(2);
          $joomla('#shipmethodtotalStr').html(sfixedNum);
          $joomla('input[name=shipcostStr]').val(scosship[0]);
          $joomla('input[name=shipservtStr]').val(scosship[1]);
          $joomla('input[name=amtStr]').val(sfixedNum);
           $joomla('input[name=amount]').val(sfixedNum);
          $joomla("#addserStr").html(scosship[2]);
          
          if(scosship[3]){
              $joomla("#discountStr").html(scosship[3]);
              discountVal = scosship[3];
          }
          else{
              $joomla("#discountStr").html("0.00");
              discountVal = "0.00";
          }
          
          if(scosship[5]){
          $joomla("#insuranceDiv").html(scosship[5]);
          $joomla("input[name='insuranceCost']").val(scosship[5]);
          insCost = scosship[5];
          }
          else{
          $joomla("#insuranceDiv").html("0.00");
          $joomla("input[name='insuranceCost']").val(0.00);
          insCost = "0.00";
          }
          
          if(scosship[8]){
          $joomla("#fuelCharges").html(scosship[8]);
          fuelCharges = scosship[8];
          }
          else{
          $joomla("#fuelCharges").html("0.00");
          fuelCharges = "0.00";
          }
          
          $joomla("#storageCharges").html(scosship[9]);
      	  $joomla('#divShipCOstTwo').html('<table class="table-responsive shipTable"><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_SHIPPING_COST');?></td><td>$'+scosship[4]+'</td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_FUEL_CHARGES');?> :</td><td>$'+fuelCharges+'</td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_INSURANCE_COST');?> : </td><td>$'+insCost+'</td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_ADDITIONAL_SERVICES');?> : </td><td>$<span id="addserStr1">'+scosship[2]+'</span></td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_STORAGE_CHARGES');?> :</td><td>$<span id="storageCharges">'+scosship[9]+'</span></td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_DISCOUNT');?> : </td><td>$'+discountVal+'</td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_TOTAL_COST');?> : </td><td>$<span id="totalamountDiv">'+ scosship[0]+'</span></td></tr>');
      	  
      	  //alert($joomla('#divShipCOstTwo').html());
      	  $joomla('input[name=ratetypeStr]').val(scosship[6]);
   		
   		
       });
       
    //     $joomla('#shipmentNewStr').live('click',function(){
            
    //       $joomla('#divShipCOstTwo').show();
    //       $joomla('#divShipCOstTwo').addClass("rst_text shp-addnew1");
           
    //       var scosship=$joomla('input[name=shipmentNewStr]:checked').val();
    //       scosship=scosship.split(":");
    //       $joomla('#shipmethodStrValue').html(scosship[4]);
    //       $joomla('#shipmethodStrValuetwo').html(scosship[0]);
    //       var sfixedNum = parseFloat(scosship[0]).toFixed(2);
    //       $joomla('#shipmethodtotalStr').html(sfixedNum);
    //       $joomla('input[name=shipcostStr]').val(scosship[0]);
    //       $joomla('input[name=shipservtStr]').val(scosship[1]);
    //       $joomla('input[name=amtStr]').val(sfixedNum);
    //       $joomla('input[name=amount]').val(sfixedNum);
    //       $joomla("#addserStr").html(scosship[2]);
          
    //       if(scosship[3]){
    //           $joomla("#discountStr").html(scosship[3]);
    //           discountVal = scosship[3];
    //       }
    //       else{
    //           $joomla("#discountStr").html("0.00");
    //           discountVal = "0.00";
    //       }
          
    //       if(scosship[5]){
    //       $joomla("#insuranceDiv").html(scosship[5]);
    //       $joomla("input[name='insuranceCost']").val(scosship[5]);
    //       insCost = scosship[5];
    //       }
    //       else{
    //       $joomla("#insuranceDiv").html("0.00");
    //       $joomla("input[name='insuranceCost']").val(0.00);
    //       insCost = "0.00";
    //       }
          
    //       if(scosship[8]){
    //       $joomla("#fuelCharges").html(scosship[8]);
    //       fuelCharges = scosship[8];
    //       }
    //       else{
    //       $joomla("#fuelCharges").html("0.00");
    //       fuelCharges = "0.00";
    //       }
          
    //       $joomla("#storageCharges").html(scosship[9]);
    //   	  $joomla('#divShipCOstTwo').html('<table class="table-responsive shipTable"><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_SHIPPING_COST');?></td><td>$'+scosship[4]+'</td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_FUEL_CHARGES');?> :</td><td>$'+fuelCharges+'</td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_INSURANCE_COST');?> : </td><td>$'+insCost+'</td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_ADDITIONAL_SERVICES');?> : </td><td>$<span id="addserStr1">'+scosship[2]+'</span></td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_STORAGE_CHARGES');?> :</td><td>$<span id="storageCharges">'+scosship[9]+'</span></td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_DISCOUNT');?> : </td><td>$'+discountVal+'</td></tr><tr><td><?php echo Jtext::_('COM_USERPROFILE_SHIP_TOTAL_COST');?> : </td><td>$<span id="totalamountDiv">'+ scosship[0]+'</span></td></tr>');
    //   	  $joomla('input[name=ratetypeStr]').val(scosship[6]);
    //   });
     
       $joomla("#tabs2").on("click","input[name='txtId']",function(){
           
           $joomla('#step1 #j_table').show();
           $joomla('.paygaterrormsg').html("");
           
            var checkboxLen = $joomla("input[name='txtId']").filter(":checked").length;
          if(checkboxLen == 0){
                $joomla("#selectAll").prop("checked",false);
                $joomla('.shipAll').hide();
                $joomla('tr .ship:first-child').show();
          }
          $joomla("#checkboxCount").val(checkboxLen);
           
          var txtIdVal = $joomla(this).val();
           var indVal=txtIdVal.split(":");
           var wtUnits = indVal[17];
           var dimUnits = indVal[12];
           var types = indVal[19];
           var stype = indVal[20];
           var dtype = indVal[21];
           var dhub = indVal[22];
          
           
           $joomla.each($joomla("input[name='txtId']:checked"), function(){
               //$joomla(this).closest('tr').find('input[name="txtQty"]').val($joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').val());
           });
         
            var x=0;
            var xx='';
             
             $joomla('#shipmenttype').val(types);
             $joomla("input[name='txtId']:checked").each( function () {
                 
           var txtIdValLoop = $joomla(this).val();
           var indValLoop=txtIdValLoop.split(":");
           var wtUnitsLoop = indValLoop[17];
           var dimUnitsLoop = indValLoop[12];
           var shipTypeLoop = indValLoop[19];
           var sTypeLoop = indValLoop[20];
           var dTypeLoop = indValLoop[21];
           var dhubLoop = indValLoop[22];
           
                 
               if(shipTypeLoop==types)
               {
                   
               }else{
                 x=1;
                 xx="Shipment Type";
               }
               if(sTypeLoop==stype)
               {
                   
               }else{
                 x=2;
                 xx="Source Type";
               }
               if(dTypeLoop==dtype)
               {
                   
               }else{
                 x=3;
                 xx="Destnation Type";
               }
               if(wtUnits == wtUnitsLoop)
               {
                   
               }else{
                 x=4;
                 xx="Weight Units";
               }
               
               if(dimUnits == dimUnitsLoop)
               {
                   
               }else{
                 x=5;
                 xx="Measurement Units";
               }
                if(dhubLoop==dhub)
               {
                   
               }else{
                 x=6;
                 xx="Destnation Hubs";
               }
               
               
             });
             if(x!=0){
               $joomla('#ship_all').modal("show");
               $joomla('.shipAll').hide();
               $joomla('.shipAll').next().addClass("not_same_error");
               $joomla('#confirmq').hide();
               $joomla('#error').html("Please check Shipping "+xx+" are not same");
               $joomla('#error').show();
               $joomla(":checkbox").prop("checked", false);
               $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
               if($joomla('input[name="shipment"]:checked').val()=="1"){
                   $joomla('#tabs2 #j_table tbody tr#dfg').remove(); 
               }else{
                   $joomla('#tabs2 #u_table tbody tr#dfg').remove(); 
               }    
               $joomla('#step1').hide(); 
               $joomla('#step1 #j_table tbody').remove();    
               //$joomla('#ord_ship #k_table tbody').remove();
               $joomla('#ord_ship #kk_table tbody').remove(); 
               $joomla('#ord_ship #ki_table tbody').remove();    
               $joomla.each($joomla("input[name='txtId']"), function(){ 
                 $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
                 originalVal = $joomla(this).closest('tr').find('input[name="ItemQtyTxt"]').val();
                   $joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value',originalVal);
                   $joomla(this).closest('tr').find('input[name="txtQty"]').val(originalVal);
                   $joomla(this).closest('tr').find('td:eq(3)').html(originalVal);
               })
               $joomla("#ord_ship .ship").click(true); 
               $joomla.each($joomla(".wrchild"), function(){ 
                   $joomla('.wrchild').val('+');
               })    
               return false;
             }     
   
              $joomla('#step1 #j_table tbody').remove();    
              //$joomla('#ord_ship #k_table tbody').remove(); 
              $joomla('#ord_ship #kk_table tbody').remove(); 
              $joomla('#ord_ship #ki_table tbody').remove();    
             
              $joomla('#j_table').focus();
              $joomla('#step1').css('display','block');
             
              $joomla('#ord_ship #step3').hide(); 
              $joomla('#ord_ship #step2').hide(); 
   
           var ischecked= $joomla(this).is(':checked');
         if(ischecked){
             
             
               if($joomla('input[name="shipment"]:checked').val()=="1"){
                   
              
                   var rs=$joomla(this).closest('tr').find('td:eq(1)').text();
                   var htmsw='';
                   $joomla('#tabs2 #j_table tbody tr').each( function () {
                       if($joomla(this).attr('id')==rs){ 
                         htmsw+='<tr id="dfg">'+$joomla(this).html()+'</tr>';
                       }
                   })
                   if($joomla(this).closest('tr').find('.wrchild').val()=='-'){
                       $joomla(this).closest('tr').find('.wrchild').val('-');
                   }
                   else
                   {
                       $joomla.each($joomla('#tabs2 #j_table tbody tr#dfg'), function(){
                           console.log($joomla(this).find('td:eq(1)').text()+"-----"+rs)
                           if($joomla(this).find('td:eq(1)').text()==rs){ 
                              $joomla(this).remove();
                           }
                       });    
                       $joomla(this).closest('tr').after(htmsw);
                       if($joomla(this).closest('tr').find('.wrchild').val()=='+'){
                           $joomla(this).closest('tr').find('.wrchild').val('-');
                       }    
                   }     
                   $joomla.each($joomla('#tabs2 #j_table tbody tr#dfg'), function(){
                      // console.log($joomla(this).find('td:eq(1)').text()+"-----"+rs)
                       if($joomla(this).find('td:eq(1)').text()==rs){ 
                           $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',true);
                           $joomla(this).closest('tr').find('input[name="txtId"]').prop('readonly',true);
                           $joomla(this).closest('tr').find('input[name="txtId"]').css('pointer-events','none');
                       }else{
                       }    
                   })
           
               }else{
                 
                   
                   var rs=$joomla(this).closest('tr').find('td:eq(1)').text();
                   
                   var htms='';
                   $joomla('#tabs2 #u_table tbody tr').each( function () {
                       if($joomla(this).attr('id')==rs){ 
                         htms+='<tr id="dfg">'+$joomla(this).html()+'</tr>';
                       }
                   })
                   if($joomla(this).closest('tr').find('.wrchild').val()=='-'){
                       $joomla(this).closest('tr').find('.wrchild').val('-');
                   }else
                   {
                       $joomla.each($joomla('#tabs2 #u_table tbody tr#dfg'), function(){
                           console.log($joomla(this).find('td:eq(1)').text()+"-----"+rs)
                           if($joomla(this).find('td:eq(1)').text()==rs){ 
                              $joomla(this).remove();
                           }
                       });    
                       $joomla(this).closest('tr').after(htms);
                       if($joomla(this).closest('tr').find('.wrchild').val()=='+'){
                           $joomla(this).closest('tr').find('.wrchild').val('-');
                       }    
                   }     
              
                 $joomla.each($joomla('#tabs2 #u_table tbody tr#dfg'), function(){
                       if($joomla(this).find('td:eq(1)').text()==rs){ 
                           $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',true);
                           $joomla(this).closest('tr').find('input[name="txtId"]').prop('readonly',true);
                           $joomla(this).closest('tr').find('input[name="txtId"]').css('pointer-events','none');
                       }else{
                       }    
                 })
             
               }
        
    
         }else{
             
             
           
               if($joomla('input[name="shipment"]:checked').val()=="1"){
         
                   var rs=$joomla(this).closest('tr').find('td:eq(1)').text();
                  
                  $joomla.each($joomla('#tabs2 #j_table tbody tr#dfg'), function(){
                      if($joomla(this).closest('tr').find('td:eq(1)').text()==rs){ 
                         $joomla(this).closest('tr').remove();
                       }
                   })
                   if($joomla(this).closest('tr').find('.wrchild').val()=='-'){
                       $joomla(this).closest('tr').find('.wrchild').val('+');
                   } 
       
                   }else{
         
                   var rs=$joomla(this).closest('tr').find('td:eq(1)').text();
                  
                  $joomla.each($joomla('#tabs2 #u_table tbody tr#dfg'), function(){
                      if($joomla(this).closest('tr').find('td:eq(1)').text()==rs){ 
                         $joomla(this).closest('tr').remove();
                       }
                   })
                   if($joomla(this).closest('tr').find('.wrchild').val()=='-'){
                       $joomla(this).closest('tr').find('.wrchild').val('+');
                   } 
                   
               }
   
         }       
             
   
             if($joomla('#step1 #j_table tr').length>0){
                var tds='<tr>';
                var tdns='<tr>';
                var tdns2='<tr>';
                var whs=[];
                var idksn=[];
                var qtyl=[];
                var volres=[];
                var tyservice=[];
                var sr=[];
                var dt=[];
                var mrunits=[];
                var srhub=[];
                var ht=[];
                var wd=[];
                var lg=[];
                var wt=[];
                var dtunit=[];
                var bustype=[];
                var whtr='';
                var j=0;
                var sno=1;
                var vol=[];
                var volmetwt=[];
                $joomla.each($joomla("input[name='txtId']:checked"), function(){
                   if($joomla(this).val()){
                   var loops=$joomla(this).val();
                   //console.log(loops);
                   var loop=loops.split(":");
                   for(i=0;i<loop.length;i++){
                       //if(loop[i]=="" || loop[i]==null){
                        //tds+="";   
                       //}
                       //else{
                        if(i==0){
                           tds+="<td>"+loop[i]+"</td>";
                           tdns+='<td><input type="text"  id="'+j+'" readonly  name="articleStr[]" class="form-control" value="'+loop[i]+'"></td>';
                           tdns2+='<td><input type="text"  id="'+j+'" readonly name="article2Str[]" value="'+loop[i]+'"></td>';
                         }    
                        if(i==1){
                           //$joomla('#wherhourecStr').val(loop[i]); 
                           whs.push(loop[i]);
                           tds+="<td class='warehouseStr'>"+loop[i]+"</td>";
                        }    
                        if(i==2){
                          qtyl.push($joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value'));
                          tds+="<td>"+$joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value')+"</td>";
                          tdns+="<td>"+$joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value')+"</td>";
                          tdns2+="<td>"+$joomla(this).closest('tr').find('input[name="txtQty"]').attr('value')+"</td>";
                        }    
                        if(i==3){
                           $joomla('#trackingidStr').val(loop[i]);
                           tds+="<td>"+loop[i]+"</td>";
                           tdns+="<td>"+loop[i]+"</td>";
                           tdns2+="<td>"+loop[i]+"</td>";
                        }
                        if(i==4){
                           idksn.push(loop[i]); 
                           whtr=loop[i];
                        }
                        if(i==5){
                           $joomla('#ItemPriceStr').val(loop[i]);
                           //tdns+="<td>"+loop[i]+"</td>";
                            var quantity = $joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value');
                           var totalCost = quantity*loop[24];
                           tdns+='<td><input type="text" class="form-control pricechange '+ loop[19] +'"  data-insurance="'+loop[23]+'"  name="priceStr[]"  id="'+j+i+'"  value="'+totalCost.toFixed(2)+'"></td>';
                           tdns2+='<td><input type="text" name="price2Str[]"  id="'+j+i+'" readonly  value="'+loop[i]+'"></td>';
                        }
                        if(i==6){
                           $joomla('#costStr').val(loop[i]);
                           //tdns+="<td>"+loop[i]+"</td>";
                       }
                        if(i==8){
                           volres.push(loop[i]);
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
                        //if(i==13){
                           //srhub.push(loop[i]);
                           //console.log(loop[i])
                        //}
                        if(i==13){
                           lg.push(loop[i]);
                        }
                        if(i==14){
                           wd.push(loop[i]);
                        }
                        if(i==15){
                           ht.push(loop[i]);
                        }
                        if(i==16){
                           wt.push(loop[i]);
                        }
                        if(i==17){
                           dtunit.push(loop[i]);
                        }
                        if(i==28){
                           bustype.push(loop[i]);
                        }
                        if(i==18){
                         var fileName = loop[i];
                         console.log("fileName:"+fileName);
                         var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                         if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                           var image1=image2=image3 ="";
                           var hrefs=loop[i];
                           hrefs=hrefs.split(' ').join('%20');
                           hrefs=hrefs.replace("#",":");
                           
                           var hrefs1=loop[25];
                           hrefs1=hrefs1.split(' ').join('%20');
                           hrefs1=hrefs1.replace("#",":");
                           if(hrefs1 !='')
                           image1='<a class="sfile" href="'+hrefs1+'" target="_blank">(<?php echo Jtext::_('COM_USERPROFILE_INVOICE');?>)</a>';
                           
                           var hrefs2=loop[26];
                           hrefs2=hrefs2.split(' ').join('%20');
                           hrefs2=hrefs2.replace("#",":");
                           if(hrefs2 !='')
                           image2='<a class="sfile" href="'+hrefs2+'" target="_blank">(<?php echo Jtext::_('COM_USERPROFILE_INVOICE');?>)</a>';
                           
                           var hrefs3=loop[27];
                           hrefs3=hrefs3.split(' ').join('%20');
                           hrefs3=hrefs3.replace("#",":");
                           if(hrefs3 !='')
                           image3='<a class="sfile" href="'+hrefs3+'" target="_blank">(<?php echo Jtext::_('COM_USERPROFILE_INVOICE');?>)</a>';
                           
                           
                           
                           tdns+='<td> <div><div><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INVOICE_UPLOAD');?></label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtr+'></div><div><p><?php echo Jtext::_('COM_USERPROFILE_SHIP_KTABLE_UPLOAD_EXT_TYPE_TXT');?></p></div></div><div class="clearfix"></div><a class="sfile" href="'+hrefs+'" target="_blank">(<?php echo Jtext::_('COM_USERPROFILE_INVOICE');?>)</a>'+image1+image2+image3+'</td>';
                         }else{
                           tdns+='<td> <div><div><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INVOICE_UPLOAD');?></label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtr+'></div><div><p><?php echo Jtext::_('COM_USERPROFILE_SHIP_KTABLE_UPLOAD_EXT_TYPE_TXT');?></p></div></div></td>';
                         }
   
                         var file2Name = loop[i];
                         console.log("file2Name:"+file2Name);
                         var ext = file2Name.substring(file2Name.lastIndexOf('.') + 1);
                         if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                           var hrefs2=loop[i];
                           hrefs2=hrefs2.split(' ').join('%20');
                           hrefs2=hrefs2.replace("#",":");
                           tdns2+='<td> <div><div><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INVOICE_UPLOAD');?></label></div><div><input type="file" class="upoadfe2" name="inv2File[]" multiple id='+whtr+'></div><div><p><?php echo Jtext::_('COM_USERPROFILE_SHIP_KTABLE_UPLOAD_EXT_TYPE_TXT');?></p></div></div><div class="clearfix"></div><a class="sfile" href="'+hrefs2+'" target="_blank">(<?php echo Jtext::_('COM_USERPROFILE_INVOICE');?>)</a></td>';
                         }else{
                           tdns2+='<td> <div><div><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INVOICE_UPLOAD');?></label></div><div><input type="file" class="upoadfe2" name="inv2File[]" multiple id='+whtr+'></div><div><p><?php echo Jtext::_('COM_USERPROFILE_SHIP_KTABLE_UPLOAD_EXT_TYPE_TXT');?></p></div></div></td>';
                         }
   
                        }
                        if(i==29){
                           vol.push(loop[i]);
                        }
                        if(i==30){
                           volmetwt.push(loop[i]);
                        }
                        
       
                       j++;
                      //}
                 }
                 whs.join(", ");
                 idksn.join(", ");
                 volres.join(", ");
                 tyservice.join(", ");
                 sr.join(", ");
                 dt.join(", ");
                 mrunits.join(", ");
                 srhub.join(", ");
                 bustype.join(", ");
                 vol.join(", ");
                 volmetwt.join(", ");
   
                 lg.join(", ");
                 wd.join(", ");
                 ht.join(", ");
                 wt.join(", ");
                 dtunit.join(", ");
                 
                 qtyl.join(", ");
                 $joomla('#qtyStr').val(qtyl);
                 $joomla('#qty2Str').val(qtyl);
                 
                 $joomla('#wherhourecStr').val(whs);
                 $joomla('#wherhourec2Str').val(whs);
                 $joomla('#invidkStr').val(idksn);
                 $joomla('#invidk2Str').val(idksn);
                 $joomla('#volresStr').val(volres);
                 $joomla('#tyserviceStr').val(tyservice);
                 $joomla('#mrunitsStr').val(mrunits);
                 $joomla('#volStr').val(vol);
                 $joomla('#volmetStr').val(volmetwt);
                 
                 
                 $joomla('#lengthStr').val(lg);
                 $joomla('#widthStr').val(wd);
                 $joomla('#heightStr').val(ht);
                 $joomla('#weightStr').val(wt);
                 $joomla('#dtunitStr').val(dtunit);
                 $joomla('#bustypeStr').val(bustype);
                 
                  
                 $joomla('#srhub').val(srhub);
                 $joomla('#srStr').val(sr);
                 $joomla('#dtStr').val(dt);
                 tds+='<td><span data-sno="'+loop[7]+'" class="discardShip"></span></td>';
                // tds+='<td style="display:none;">'+sno+'</span></td>';
                 tds+="</tr>";
                 tdns+="</tr>";
                 tdns2+="</tr>";
                 
                }
                sno++;
               });
       
               $joomla('#step1 #j_table:first').append(tds);
               //$joomla('#ord_ship #k_table:last').append(tdns);
               $joomla('#ord_ship #kk_table:last').append(tdns);
               $joomla('#ord_ship #ki_table:last').append(tdns2);
               $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);
               $joomla('input[name=cc]').filter(':radio').prop('checked',false);
               $joomla('#divShipCOstOne').html('');
               $joomla('#ChangeShippingAddressNew').html();
               
               var sdf=$joomla('input[name=txtbiladdress]').val();
               var sdatas=sdf.replace(/,/g,"<br>");
               $joomla('#ChangeShippingAddressNew').html(sdatas); 
               $joomla('#toaddressTxt').val(sdf);         
               $joomla('#destingaddreesStr').val(sdatas); 
               
               totalQnt = $joomla(this).closest('tr').find('input[name="txtQty"]').attr('value')
               shipQnt = $joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value');
               finalQnt =  totalQnt-shipQnt;
                //$joomla(this).closest('tr').find('td:eq(5)').html(finalQnt);
                $joomla(this).closest('tr').find('td:eq(6) input[name="txtQty"]').val(finalQnt);
               
             }
             
             if(!ischecked){
                 
               originalVal = $joomla(this).closest('tr').find('input[name="ItemQtyTxt"]').val();
               $joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value',originalVal);
               $joomla(this).closest('tr').find('input[name="txtQty"]').val(originalVal);
               $joomla(this).closest('tr').find('td:eq(3)').html(originalVal);
               
               
             }
             
             if($joomla('#step1 #j_table tbody tr td').length==0){
                 $joomla('.btn-danger').click();
             }
           $joomla("#srol").focus();  
       });     
   
       
   
       $joomla('#tabs2').on('click','.ship',function(){
           
            // $joomla('#step1').show();
            // $joomla('#step1 #j_table').show();
           
           var txtIdVal = $joomla(this).prev().val();
           var indVal=txtIdVal.split(":");
           var wtUnits = indVal[17];
           var dimUnits = indVal[12];
           var types = indVal[19];
           var stype = indVal[20];
           var dtype = indVal[21];
           var dhub = indVal[22];
         
         var x=0;
         var xx='';
         
      
         $joomla('#shipmenttype').val(types);
         
         // check for the same shipment type, source type, destination type, weight and dimentions
         
         $joomla("input[name='txtId']:checked").each( function () {
             
             
             
                           var txtIdValLoop = $joomla(this).val();
                           var indValLoop=txtIdValLoop.split(":");
                           var wtUnitsLoop = indValLoop[17];
                           var dimUnitsLoop = indValLoop[12];
                           var shipTypeLoop = indValLoop[19];
                           var sTypeLoop = indValLoop[20];
                           var dTypeLoop = indValLoop[21];
                           var dhubLoop = indValLoop[22];
                             
                           if(shipTypeLoop==types)
                           {
                               
                           }else{
                             x=1;
                             xx="Shipment Type";
                           }
                           if(sTypeLoop==stype)
                           {
                               
                           }else{
                             x=2;
                             xx="Source Type";
                           }
                           if(dTypeLoop==dtype)
                           {
                               
                           }else{
                             x=3;
                             xx="Destnation Type";
                           }
                          if(wtUnits == wtUnitsLoop)
                           {
                               
                           }else{
                             x=4;
                             xx="Weight Units";
                           }
                           
                           if(dimUnits == dimUnitsLoop)
                           {
                               
                           }else{
                             x=5;
                             xx="Measurement Units";
                           }
                           if(dhubLoop==dhub)
                           {
                               
                           }else{
                             x=6;
                             xx="Destnation Hubs";
                           }
               
         });
         
         //end
         
         if(x!=0){
           $joomla('#ship_all').modal("show");
            $joomla('.shipAll').hide();
            $joomla('.shipAll').next().addClass("not_same_error");
           $joomla('#confirmq').hide();
           $joomla('#error').html("Please check Shipping "+xx+" are not same");
           $joomla('#error').show();
           $joomla(":checkbox").prop("checked", false);
           if($joomla('input[name="shipment"]:checked').val()=="1"){
               $joomla('#tabs2 #j_table tbody tr#dfg').remove(); 
           }else{
               $joomla('#tabs2 #u_table tbody tr#dfg').remove(); 
           }
           $joomla('#step1').hide(); 
           $joomla('#step1 #j_table tbody').remove();    
           $joomla('#ord_ship #k_table tbody').remove();    
           $joomla('#ord_ship #ki_table tbody').remove();    
           $joomla.each($joomla("input[name='txtId']"), function(){ 
             $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
             originalVal = $joomla(this).closest('tr').find('input[name="ItemQtyTxt"]').val();
                   $joomla(this).closest('tr').find('input[name="ItemQtyEdit"]').attr('value',originalVal);
                   $joomla(this).closest('tr').find('input[name="txtQty"]').val(originalVal);
                   $joomla(this).closest('tr').find('td:eq(3)').html(originalVal);
           })
           $joomla("#ord_ship .ship").click(true);  
           $joomla.each($joomla(".wrchild"), function(){ 
               $joomla('.wrchild').val('+');
           })    
           return false;
         }
         
         $joomla(this).closest('tr').find('input[name="txtId"]').click();
         
        
   
       });
   
   
       //ship_img
    //   $joomla('#tabs2').on('click','.ship_img',function(){
    //       var res=$joomla(this).data('id');
    //       $joomla('#viewImage').html('');
    //       if(res.match(/(?:pdf)$/)=="pdf"|| res.match(/(?:pdf)$/)=="PDF") {
    //          $joomla(this).attr('href',res);
    //          $joomla(this).attr('target','-blank');
    //       }else if (res.match(/(?:gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG)$/)) {
    //          $joomla('#viewImage').html("<img src='"+res+"' width='100%'>");
    //       }
    //       else{
    //          $joomla('#viewImage').html("There is no image upload for this order");
    //       }
    //   });
       
       
        $joomla('#tabs2').on('click','.ship_img',function(){
        $joomla('#viewImage #multimages').html('');
        var expandImg = document.getElementById("expandedImg");
        expandImg.parentElement.style.display = "none";
        var res=$joomla(this).data('id');
        
        var imgarr = res.split("##");
        var imgstr = '';
        
        for(var i=0;i<imgarr.length;i++){
            if (imgarr[i] !=''){ 
                imgext = imgarr[i].split(".");
                if (imgext[imgext.length - 1] != 'pdf') {
                    imgstr += "<div class='col-img-all images-view'><img src='"+imgarr[i]+"' width='100%'></div>";
                }else{
                    imgstr += "<div class='col-img-all pdf-view'><a href='"+imgarr[i]+"' target='_blank' class='pdf-img'><img src='<?php echo JURI::base(); ?>components/com_userprofile/images/pdf-icon.png' width='100%'><span class='btn btn-primary'>View PDF</span></a></div>";
                 }
            }
        }
        if(imgstr !=''){
            $joomla('#viewImage #multimages').html(imgstr);
            var firstImgSrc = $joomla('#multimages .images-view:first img').attr("src");
            if(typeof firstImgSrc !== 'undefined'){
             expandImg.src = $joomla('#multimages .images-view:first img').attr("src");
             expandImg.parentElement.style.display = "block";
            }else{
                expandImg.parentElement.style.display = "none";
            }
            $joomla('#multimages .images-view:first').addClass("img-active");
        }else{
            $joomla('#viewImage #multimages').html("There is no image for this order");
        }
    });
       
       
        $joomla(document).on('click','.col-img-all img',function(){
            $joomla('.col-img-all').removeClass("img-active");
            $joomla(this).parent().addClass("img-active");
            var imgsrc = $joomla(this).attr("src");
            var alttext = $joomla(this).attr("alt");
            var expandImg = document.getElementById("expandedImg");
            var imgText = document.getElementById("imgalttext");
            expandImg.src = imgsrc;
            //imgText.innerHTML = alttext;
            expandImg.parentElement.style.display = "block";
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
             txtBackCompany: "<?php echo Jtext::_('COM_USERPROFILE_RETURN_BACK_COMPANY_VALID')  ?>",
             txtReturnAddress: "<?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_ADDRESS_VALID')  ?>",
             txtReturnCarrier: "<?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_SHIPPING_CARRIER_VALID')  ?>",
             txtReturnReason: "<?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_REASON_VALID')  ?>",
             txtOriginalOrderNumber: "<?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_ORDER_NUMBER_VALID')  ?>",
             txtMerchantNumber: "<?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_MERCHANT_NUMBER_VALID')  ?>",
             txtSpecialInstructions: "<?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_SPECIAL_INSTRUCTION_VALID')  ?>"
   
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
                   if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf','GIF','PNG','JPG','JPEG','PDF']) == -1) {
                       $joomla('input[name=fluReturnShippingLabel]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please Upload extension type png,jpg,jpeg,gif,pdf !</label>');
                       return false;
                   }else{
                     $joomla("input[name=fluReturnShippingLabel] #errorTxt-error").html('');    
                   }
               }
               $joomla('#ord_return .modal-body').hide();
               $joomla('#ord_return .modal-body2').html('<img src="/components/com_userprofile/images/loader.gif" width="400" height="400">');
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
             txtReturnReason: "Please enter Reason for Hold"
             
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
               $joomla('#ord_keep .modal-body').hide();
               $joomla('#ord_keep .modal-body2').html('<img src="/components/com_userprofile/images/loader.gif">');
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
       
       
       
       
   
      
           
      
       
       
       
   
      $joomla("input[name='priceStr[]']").live('keypress',function (e) {
       if(e.which == 46){
           if($joomla(this).val().indexOf('.') != -1) {
               return false;
           }
       }
       if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
           return false;
       }
       });
   
       $joomla.validator.addMethod("currentdates", function(value, element) {
        if($joomla('select[name="YearDropDownListStr"]').val()=="<?php echo date('Y');?>" && $joomla('select[name="MonthDropDownListStr"]').val()<="<?php echo date('m');?>"){
         return false 
        }else{
         return true 
        }
       }, '<?php echo Jtext::_('Card expiry year and month not validated');?>');
   
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
       $joomla('.upoadfe').live('change',function(){
           $joomla('input[name=cc]').filter(':radio').prop('checked',false);	
           var ext = $joomla(this).val().split('.').pop().toLowerCase();
           var file_size = $joomla(this)[0].files[0].size;
       	if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf']) == -1) {
               alert('Invad Extension!');
               $joomla(this).val('');
           }
           else if(file_size>2097152) {
       		alert("File size is greater than 2MB");
               $joomla(this).val('');
       	}
   	});
       $joomla('.upoadfe2').live('change',function(){
           var ext = $joomla(this).val().split('.').pop().toLowerCase();
           var file_size = $joomla(this)[0].files[0].size;
       	if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf']) == -1) {
               alert('Invad Extension!');
               $joomla(this).val('');
           }
           else if(file_size>2097152) {
       		alert("File size is greater than 2MB");
               $joomla(this).val('');
       	}
   	});
    
   
   	$joomla('#country2Txt').on('change',function(){
   	    $joomla('#country2Txt-error').html('');
   	    $joomla('input[name="city2Txt"]').val('');
   		$joomla('#state2Txt').val('');
   		$joomla('#city2Txt').html('');
   		var countryID = $joomla(this).val();
   		if(countryID){
   			$joomla.ajax({
   				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&countryid="+$joomla("#country2Txt").val() +"&stateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
   				data: { "country": $joomla("#countryTxt").val() },
   				dataType:"html",
   				type: "get",
       			beforeSend: function() {
                       //$joomla(".pagshipup").show();
                       $joomla(".page_loader").show();
                       $joomla('.pagshipdown').hide();
                   },    
   				success: function(data){
                       //$joomla(".pagshipup").hide();
                       $joomla(".page_loader").hide();
                       $joomla('.pagshipdown').show();
   					$joomla('#state2Txt').html(data);
   					//$joomla('#city2Txt').html('<option value="">Select City</option>'); 
   				}
   			});
   		}
   		$joomla('#state2Txt').html('<option value="">Select State</option>');
   		//$joomla('#city2Txt').html('<option value="">Select City</option>'); 
   		$joomla('#zip2Txt').val(''); 
   	});
   
   	$joomla('#state2Txt').on('change',function(){
   	    $joomla('#state2Txt-error').html('');
   		$joomla('input[name="city2Txt"]').val('');
   		$joomla('#city2Txt').html('');
   		var stateID = $joomla(this).val();
   		if(stateID){
   			$joomla.ajax({
   				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&stateid="+$joomla("#state2Txt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
   				data: { "state": $joomla("#countryTxt").val() },
   				dataType:"html",
   				type: "get",
       			beforeSend: function() {
                       //$joomla(".pagshipup").show();
                       $joomla(".page_loader").show();
                       $joomla('.pagshipdown').hide();
                   },    
   				success: function(data){
                       //$joomla(".pagshipup").hide();
                       $joomla(".page_loader").hide();
                       $joomla('.pagshipdown').show();
   					$joomla('#city2Txt').append(data);
   				}
   			}); 
   		}else{
   			$joomla('#city2Txt').html('<option value="">Select City</option>'); 
   		}
   	});  
   // 	$joomla("input[name='city2Txt']").blur(function(){
           
   //         var val = $joomla(this).val()
   //         var xyz = $joomla('#city2Txt option').filter(function() {
   //             return this.value == val;
   //         }).data('xyz');
   //         if(xyz){
   //             $joomla(this).val(val);
   //         }
   //         $joomla("input[name='city2Txtdiv']").val(xyz);
   //      });
   
   	
   	$joomla('#addusers').on('click',function(){
   	    $joomla('#userprofileFormSeven')[0].reset();
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
   	
   	// getcouponcodes data and promocodes
   	
   $joomla('.cupn-cde a').on('click',function(e){
   	    e.preventDefault();
   	    $joomla('.cupn-cde p').show();
   	   $joomla.ajax({
   	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&jpath=<?php echo urlencode(JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
   			data: { "getcouponcodeflag":1,"user":"<?php echo $user; ?>","amount":$joomla("input[name='amount']").val(),"volmetStr":$joomla("input[name='volmetStr']").val(),"volStr":$joomla("input[name='volStr']").val(),"qtyStr":$joomla("input[name='qtyStr']").val(),"wtStr":$joomla("input[name='weightStr']").val(),"shippingCost":$joomla("input[name='shipmentCost']").val()},
   			dataType:"html",
   			type: "get",
   			beforeSend: function() {
                       $joomla(".page_loader").show();
                   },    
   			success: function(data){
   			    $joomla(".page_loader").hide();
   			    $joomla(".cupn-cdes").html(data);
   			}
   		}); 
   		$joomla(this).hide();
   	});
   	
   	
           // Initialize form validation on the registration form.
           // It has the name attribute "registration"
           // Initialize form validation on the registration form.
           // It has the name attribute "registration"
           var validseven=$joomla("form[name='userprofileFormSeven']").validate({
           
           // Specify validation rules
           rules: {
             // The key name on the left side is the name attribute
             // of an input field. Validation rules are defined
             // on the right side
             fnameTxt: {
               required: true,
               alphanumeric:true
             },
             lnameTxt: {
               required: true,
               alphanumeric:true
             },
             country2Txt: {
               selectBox: true
             },
             state2Txt: {
               required: true
             }/*,
             city2Txt: {
               selectBox: true
             },
             zipTxt: {
               required: true
             }*/,
             addressTxt: {
               required: true
             },
             idtypeTxt: {
               required: true
             },
             idvalueTxt: {
               required: true
             },
             emailTxt: {
               required: true,
               validateEmail:true
             }
       },
           // Specify validation error messages
           messages: {
             fnameTxt: {required:"<?php echo $assArr['first_name_error'];?>",alphanumeric:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_FIRSTNAME_ALPHABET_ERROR');?>"},
             lnameTxt: {required:"<?php echo $assArr['last_Name_error'];?>",alphanumeric:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_LASTNAME_ALPHABET_ERROR');?>"},
             country2Txt: {selectBox:"<?php echo $assArr['country_error'];?>"},
             state2Txt: {required:"<?php echo $assArr['state_error'];?>"},
             /*city2Txt: {selectBox:"Please select city"},
             zipTxt: "Please enter zip code",*/
             idtypeTxt: {required: "<?php echo $assArr['identification_type_error'];?>"},
             idvalueTxt: {required: "<?php echo $assArr['identification_value_error'];?>"},
             addressTxt: "<?php echo $assArr['additional_address_error'];?>",
             emailTxt: {required:"<?echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_FIRSTNAME_ALPHABET_ERROR');?>",email:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_EMAIL_FORMAT_ERROR');?>"}
           },
           // Make sure the form is submitted to the destination defined
           // in the "action" attribute of the form when valid
           submitHandler: function(form) {
               $joomla(".page_loader").show();
          		$joomla("input[name=fnameTxt] #errorTxt-error").html('');
    		    $joomla.ajax({
   				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&userid=<?php echo $user;?>&fnameid="+$joomla("#fnameTxt").val() +"&lnameid="+$joomla("#lnameTxt").val() +"&fnameflag=1&jpath=%2Fhome%2Fdemodelivery%2Fpublic_html&pseudoParam="+new Date().getTime(),
   				data: { "lnameid": $joomla("#lnameTxt").val(),"fnameid": $joomla("#fnameTxt").val() },
   				dataType:"html",
   				type: "get",
   				cache: false,             
                   processData: false,
                   beforeSend:function(){
                       $joomla(".page_loader").show();
                   },
   				success: function(data){
   				    $joomla(".page_loader").hide();
   				    if(data==1){
   				      if($joomla("#fnameTxt #errorTxt-error")){
   				       $joomla('#fnameTxt').after('<label id="errorTxt-error" class="error" for="errorTxt">Your Fullname already existed! </label>');    
   				      }    				      
   				    }else{
   				      if($joomla("#fnameTxt #errorTxt-error")){
   				       $joomla("#fnameTxt #errorTxt-error").html('');
   				      }
   				      form.submit(); 
   				    }
   				}
   			});
             
           }
      
           }); 
           
          
              //validation for email fields
	$joomla.validator.addMethod(
	"validateEmail", 
	function(value, element) {
	    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(value);
		},
		"<?php echo JText::_('COM_USERPROFILE_PLEASE_ENTER_VALID_EMAIL_ADDRESS');?>"
	);
          
           
   
   
       $joomla("input[name='zipTxt']").live('keypress',function (e) {
         if(e.which == 46){
           if($joomla(this).val().indexOf('.') != -1) {
               return false;
           }
         }
         if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
           return false;
         }
       });
        $joomla('#exampleModal .btn-primary:first').live('click',function(){
            $joomla(".page_loader").show();
           if(validseven.form()==true){
               $joomla("input[name=fnameTxt] #errorTxt-error").html('');
    		    $joomla.ajax({
   				url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&userid=<?php echo $user;?>&fnameid="+$joomla("#fnameTxt").val() +"&lnameid="+$joomla("#lnameTxt").val() +"&fnameflag=1&jpath=%2Fhome%2Fdemodelivery%2Fpublic_html&pseudoParam="+new Date().getTime(),
   				data: { "lnameid": $joomla("#lnameTxt").val(),"fnameid": $joomla("#fnameTxt").val() },
   				dataType:"html",
   				type: "get",
   				cache: false,             
                   processData: false, 
   				success: function(data){
   				    console.log(data);
   				    if(data==1){
   				      //if($joomla("#fnameTxt #errorTxt-error")){
   				       alert('Your Fullname already existed');
   				       //$joomla('#fnameTxt').after('<label id="errorTxt-error" class="error" for="errorTxt"><?php echo Jtext::_('COM_USERPROFILE_EXIST_FULLNAME');?> </label>');    
   				       return false;
   				      //}    				      
   				    }else{
   				      var body=$joomla('#exampleModal .modal-body').html();
   				    //   var city2Txt=$joomla('input[name="city2Txtdiv"]').val();
   				      
   				    //   if(city2Txt == ''){
   				    //       city2Txt = $joomla('input[name="city2Txt"]').val();
   				    //   }
   				      var city2Txt = $joomla('select[name="city2Txt"]').val();
                         $joomla.ajax({
                   	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getadduserpayflag=1&user=<?php echo $user;?>&typeuser="+$joomla("input[name='typeuserTxt']").val()+"&id="+$joomla("input[name='idTxt']").val()+"&fname="+$joomla("input[name='fnameTxt']").val()+"&lname="+$joomla("input[name='lnameTxt']").val()+"&country="+$joomla('#country2Txt').val()+"&state="+$joomla('#state2Txt').val()+"&city="+city2Txt+"&zip="+$joomla("input[name='zipTxt']").val()+"&address="+$joomla('#addressTxt').val()+"&address2="+$joomla('#address2Txt').val()+"&email="+$joomla("input[name='emailTxt']").val()+"&idtypetxt="+$joomla("#idtype2Txt").val()+"&identitytypetxt="+$joomla("input[name='identity2Txt']").val()+"&cpftxt="+$joomla("input[name='cpfTxt']").val()+"&cnpjtxt="+$joomla("input[name='cnpjTxt']").val()+"&idtypeTxt="+$joomla("select[name='idtypeTxt']").val()+"&idvalueTxt="+$joomla("input[name='idvalueTxt']").val()+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                   			data: { "usertype":1},
                   			dataType:"html",
                   			type: "get",
                   			beforeSend: function() {
                   			   //$joomla('#exampleModal .modal-body').html('<div id="loading-image4" ><img src="<?php echo JURI::base(); ?>/components/com_userprofile/images/loader.gif"></div>');
                            	},    
                   			success: function(data){
                   			    //$joomla('#exampleModal .modal-body').html(body);
                   			    if(data=="Additional address successfully inserted"){
                   			        $joomla('#exampleModal').modal('toggle');
                   			        loadadditionalusersData();
                   			    }
                   			}
                   		});
   				    }
   				}
   			});
           }else{
               $joomla(".page_loader").hide();
           }	
        });    
       
       
        
       $joomla("input[name='cardnumberStr']").keyup(function(e){
           this.value = this.value.replace(/[^0-9]/g, '');
       });
   
       $joomla("input[name='txtccnumberStr']").keyup(function(e){
           this.value = this.value.replace(/[^0-9]/g, '');
      });
   
       $joomla(".ishipment").click(function(){
          $joomla(".page_loader").show();
          $joomla(".pageup").hide();
          $joomla("#dfg").remove();  
   
          $joomla('#step1').hide(); 
          $joomla('#step1 #j_table tbody').remove();    
          $joomla('#ord_ship #k_table tbody').remove(); 
          $joomla('#ord_ship #ki_table tbody').remove(); 
          $joomla('#specialinstructionStr').val('');
          $joomla('#fnameTxt').val('');
          $joomla('#lnameTxt').val('');
          $joomla('#zipTxt').val('');
          $joomla('#addressTxt').val('');
          $joomla('#emailTxt').val('');
          $joomla('[name="consignidStr"]').val('');
          $joomla(".txtId").prop( "checked", false );
          if($joomla('input[name="shipment"]:checked').val()=="1"){
              $joomla('#tabs2 #j_table tbody tr#dfg').remove();
          }else{
              $joomla('#tabs2 #u_table tbody tr#dfg').remove();
          }   
      
           $joomla.each($joomla("input[name='txtId']"), function(){ 
             $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',false);
           })
           $joomla("#ord_ship .ship").click(true); 
           $joomla.each($joomla(".wrchild"), function(){ 
               $joomla('.wrchild').val('+');
           })    
   
           
           if($joomla(this).val()==2){
               setTimeout(function(){
                  $joomla(".page_loader").hide();
                  $joomla(".pageup").show();
                   $joomla(".ishpments").show();
                   $joomla(".ishpments2").hide();
                   $joomla('.btn-danger').click();
               },3000);
           }else
           {
               setTimeout(function(){
                  $joomla(".page_loader").hide();
                  $joomla(".pageup").show();
                   $joomla(".ishpments2").show();
                   $joomla(".ishpments").hide();
               },3000);
           }
       })
   
      $joomla(".wrchild").click(function(){
           $joomla(this).closest('tr').find('td:eq(3)').text($joomla(this).closest('tr').find('input[name="txtQty"]').val());
           if($joomla('input[name="shipment"]:checked').val()=="1"){
               var rs=$joomla(this).closest('tr').find('td:eq(1)').text();
               var hj=$joomla(this).closest('tr').find('input[name="txtId"]').is(':checked');
               var htmse='';
               $joomla('#tabs2 #j_table tbody tr').each( function () {
                   if($joomla(this).attr('id')==rs){ 
                     htmse+='<tr id="dfg">'+$joomla(this).html()+'</tr>';
                     //$joomla('#dfg').find('input[name="txtId"]').prop('checked',true);
                     $joomla('#dfg').find('input[name="txtId"]').css('pointer-events','none');
                     $joomla('#dfg').find('input[name="txtId"]').prop('readonly',true);
                   }
               })
               if($joomla(this).val()=='-'){
                  var rmv=1;
                  $joomla.each($joomla('#tabs2 #j_table tbody tr#dfg'), function(){
                     if($joomla(this).closest('tr').find('td:eq(1)').text()==rs){ 
                         console.log(hj);
                         if(hj){ 
                             rmv=0;
                         }else{
                             $joomla(this).closest('tr').remove();
                         }
                       }
                   })
                   if(rmv==1){
                       $joomla(this).val('+');
                   }
                   $joomla(this).closest('tr').find('td:eq(3)').text($joomla(this).closest('tr').find('input[name="txtItemQty"]').val());
               }else{
                    //$joomla(this).closest('tr').after(htmse); 
                   $joomla(this).val('-');
               }     
           }else{
   
               var rs=$joomla(this).closest('tr').find('td:eq(1)').text();
               var hj=$joomla(this).closest('tr').find('input[name="txtId"]').is(':checked');
              var htmse='';
               $joomla('#tabs2 #u_table tbody tr').each( function () {
                   if($joomla(this).attr('id')==rs){ 
                     htmse+='<tr id="dfg">'+$joomla(this).html()+'</tr>';
                     $joomla('#dfg').find('input[name="txtId"]').prop('checked',true);
                     $joomla('#dfg').find('input[name="txtId"]').css('pointer-events','none');
                     $joomla('#dfg').find('input[name="txtId"]').prop('readonly',true);
                   }
               })
               if($joomla(this).val()=='-'){
                  var rmv=1;
                  $joomla.each($joomla('#tabs2 #u_table tbody tr#dfg'), function(){
                     if($joomla(this).closest('tr').find('td:eq(1)').text()==rs){ 
                         console.log(hj);
                         if(hj){ 
                             rmv=0;
                         }else{
                             $joomla(this).closest('tr').remove();
                         }
                       }
                   })
                   if(rmv==1){
                       $joomla(this).val('+');
                   }
                   $joomla(this).closest('tr').find('td:eq(3)').text($joomla(this).closest('tr').find('input[name="txtItemQty"]').val());
               }else{
                    //$joomla(this).closest('tr').after(htmse); 
                   $joomla(this).val('-');
               }     
   
           }    
       });
     
       var r=0;
       $joomla('#tabs2 #j_table .wrchild').each( function () {
               var rs=$joomla(this).closest('tr').find('td:eq(1)').text();
               $joomla('#tabs2 #j_table tbody tr').each( function () {
                   if($joomla(this).attr('id')==rs){ 
                       r+=parseInt($joomla(this).closest('tr').find('input[name="txtQty"]').val());
                   }
               })
               r=parseInt($joomla(this).closest('tr').find('input[name="txtQty"]').val())+r;
           console.log(r);
           $joomla(this).closest('tr').find('td:eq(3)').text(r);
           $joomla(this).closest('tr').find('input[name="txtItemQty"]').val(r);
           r=0;
           //r++;
       });
       var rr=0;
       $joomla('#tabs2 #u_table .wrchild').each( function () {
             var rs=$joomla(this).closest('tr').find('td:eq(1)').text();
             $joomla('#tabs2 #u_table tbody tr').each( function () {
                   if($joomla(this).attr('id')==rs){ 
                       rr+=parseInt($joomla(this).closest('tr').find('input[name="txtQty"]').val());
                   }
               })
               rr=parseInt($joomla(this).closest('tr').find('input[name="txtQty"]').val())+rr;
           console.log(rr);
           $joomla(this).closest('tr').find('td:eq(3)').text(rr);
           $joomla(this).closest('tr').find('input[name="txtItemQty"]').val(rr);
           rr=0;
       });
   
   
       $joomla(function() {
    
           
           // Initialize form validation on the registration form.
           // It has the name attribute "registration"
           $joomla("form[name='userprofileFormSix']").validate({
           rules: {
             // The key name on the left side is the name attribute
             // of an input field. Validation rules are defined
             // on the right side
             "article2Str[]": {
               required: true/*,
               alphanumeric:true*/
             },
             "price2Str[]": {
               required: true
             }
           },
           // Specify validation error messages
           messages: {
             "article2Str[]": {required:"Please enter item description"/*,alphanumeric:"The item description must contain alphabet characters only."*/},
             "price2Str[]": "Please enter Declared Value"
           },
           
           // Specify validation rules
           // Make sure the form is submitted to the destination defined
           // in the "action" attribute of the form when valid
           submitHandler: function(form) {
   
               var sdf=0;
               $joomla("input[name='price2Str[]']").each( function () {
                   if($joomla(this).val()=="0.00"){             
                       alert("Please enter Declared Price Value($)");
                       sdf=1;
                       return false;
                   }    
               });    
               // $joomla("input[name='inv2File[]']").each( function () {
               //   if( $joomla(this).closest("tr").find(".sfile").length=="0"){
               //         if($joomla(this).val()==""){             
               //             alert("please upload Invoice");
               //             sdf=1;
               //             return false;
               //         }    
               //   }
               // })
               if(sdf==0){
                   var articlestrs=[];
                   $joomla.each($joomla("input[name='article2Str[]']"), function(){
                       articlestrs.push($joomla(this).val());
                   });    
                   var pricestrs=[];
                   $joomla.each($joomla("input[name='price2Str[]']"), function(){
                       pricestrs.push($joomla(this).val());
                   });    
                   
                   var user="<?php echo $user;?>";
                   $joomla('#loading-image5').show();
       	        $joomla('#loading-image5').focus();
                   $joomla('#ord_ship .modal-body').hide();
                   $joomla('#ord_ship .modal-body2').html('<img src="/components/com_userprofile/images/loader.gif" width="400" height="400">');
   
       	        form.submit();
               }else{
                   return false;
               }
           }
           });    
       });
       
       
       // select all
    
     $joomla('.check_all_items').on('click',function(e){
         wrhsno = $joomla(this).attr("data-id");
         
         if($joomla(this).is(':checked') == true ){
         
             $joomla('.txtId').each(function(){
                        var valueStr = $joomla(this).val();
                        var valueArr = valueStr.split(":");
                        if(wrhsno == valueArr[1]){
                            $joomla(this).closest("tr").find(".txtId").prop("checked",false);
                            $joomla(this).closest("tr").find(".txtId").trigger("click");
                        }
             });
         
     }else{
         $joomla('.txtId').each(function(){
                        var valueStr = $joomla(this).val();
                        var valueArr = valueStr.split(":");
                        if(wrhsno == valueArr[1]){
                            $joomla(this).closest("tr").find(".txtId").prop("checked",true);
                            $joomla(this).closest("tr").find(".txtId").trigger("click");
                        }
             });
     }
         
         
     });
       
       
       // expand
   
   $joomla(document).on('click','.expand_items',function(e){
        wrhsno = $joomla(this).attr("data-id");
        
         $joomla('.txtId').each(function(){
                    var valueStr = $joomla(this).val();
                    var valueArr = valueStr.split(":");
                    if(wrhsno == valueArr[1]){
                        $joomla(this).closest("tr").toggle();
                    }
         });
         
         if($joomla(this).html() == '+'){
             $joomla(this).html('-');
             $joomla(this).parent().parent().next().show();
         }else{
             $joomla(this).html('+');
             $joomla(this).parent().parent().next().hide();
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
   			beforeSend: function() {
                   //$joomla(".pagshipup").show();
                   $joomla(".page_loader").show();
                   $joomla('.pagshipdown').hide();
               },    
               success: function(data){
                   //$joomla(".pagshipup").hide();
                   $joomla(".page_loader").hide();
                   $joomla('.pagshipdown').show();
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


<div class="container pagein" style="display:none">
   <div class="main_panel persnl_panel">
      <div class="panel-body">
         <div class="row">
            <div class="col-sm-12">
               <img src='/components/com_userprofile/images/loader.gif' height="400" width="400">
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container pageup">
   <div class="main_panel persnl_panel">
      <div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_ALERTS_MAIN_HEADING');?></div>
      <div class="panel-body">
         <div class="row">
            <div class="col-sm-12 tab_view">
               <ul class="nav nav-tabs">
                  <?php 
                  
                  foreach($clients as $client){ 
                        if(strtolower($client['Domain']) == strtolower($domainName) ){   
                            $prealert_text=$client['Myprealerts_text_dashboard'];
                            $return_enable=$client['Return_enable'];
                            $hold_enable=$client['Hold_enable'];
                            $change_address_enable=$client['change_address_enable'];
                            $prepaid_text=$client['Prepaid_text'];
                            $Payment_gateway_dynamic_enable=$client['Payment_gateway_dynamic_enable']; 
                            $Gross_weight_display=$client['Gross_weight_display'];
                        }
                    }
                  
                  if(!isset($maccarr['FulFillment'])){
                      $maccarr['FulFillment'] = "False";
                  }
                  
                    
                  if($menuCustType == "CUST" || ($menuCustType == "COMP" && $maccarr['FulFillment'] == "False") ){  ?>
                  <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocessalerts"><?php echo Jtext::_($prealert_text);?></a> </li>
                  <?php }else if($menuCustType == "COMP" && $maccarr['FulFillment'] == "True"){  ?>
                  <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=inventoryalerts"><?php echo $assArr['my_Pre_Alerts'];?></a> </li>
                  <?php } ?>
                  <li> <a class="active" href="index.php?option=com_userprofile&view=user&layout=orderprocess"><?php echo $assArr['ready_to_ship'];?></a> </li>
                  <!--<li> <a class="" href="index.php?option=com_userprofile&view=user&layout=cod">COD</a> </li>-->
                  <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=shiphistory"><?php echo $assArr['shipment_History'];;?></a> </li>
               </ul>
            </div>
         </div>
         <div id="tabs2">
             
            <div class="row">
               <div class="col-sm-12">
                  <div class="rdo_cust">
                     <div class="rdo_rd1">
                        <?php if($elem['Readytosend'][1] == "ACT"){  ?>
                        <input type="radio" name="shipment"  class="ishipment" value="1" checked>
                        <label><?php echo $assArr['ready_to_send'];?></label>
                        <?php } if($elem['Holdshipments'][1] == "ACT"){  ?>
                        <input type="radio" name="shipment"  class="ishipment" value="2">
                        <label><?php echo $assArr['hold_shipments'];?></label>
                        <?php } ?>
                     </div>
                     
                  </div>
               </div>
            </div>
            
            <div class="clearfix"></div>
            <?php  
            
            Controlbox::getOrdersHoldListCsv($user);
            
            ?>
            
            <div class="row ishpments" style="display:none">
                
            <div class="">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                        <h3 class=""><strong><?php echo Jtext::_('COM_USERPROFILE_SHIP_SUB_TITLE');?></strong></h3>
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <?php if($elem['EXPORTCSV'][1] == "ACT"){ ?>
                        <a style="color:white;" href="<?php echo JURI::base(); ?>/csvdata/hold_list.csv" class="btn btn-primary csvDownload export-csv"><?php echo $assArr['eXPORT_CSV'];?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
                
               <div class="col-md-12">
                <div class="row">
                   
                </div>
                  <div class="table-responsive">
                     <table class="table table-bordered theme_table" id="u_table" data-page-length='10'>
                         <thead>
                           <tr> 
                             <th class="action_btns text-center"><input type="checkbox" name="selectAll" id="selectAll"> <?php echo $assArr['select_All'];?>Select All</th>
                                              
                              <th><?php echo 'warehouse_Receipt# '; $assArr['warehouse_Receipt#'];?></th>
                              <th><?php echo 'Qty Received'; ?></th>
                              <th><?php echo 'Qty Shipped'; ?></th>
                              <th><?php echo 'Qty OnHand'; ?></th>
                              <th><?php echo 'Qty Delivered'; ?></th>
                              <th><?php echo 'Qty Rejected'; ?></th>
                              <th><?php echo $assArr['tracking_ID'];?></th>
                              <th><?php echo $assArr['merchants_Name'];?></th>
                              <th><?php echo $assArr['type_of_shipment'];?></th>
                               <th><?php echo $assArr['source_Hub'];?></th>
                              <th><?php echo $assArr['destination'];?></th>
                              <th><?php echo $assArr['destination_Hub'];?></th>
                              <th><?php echo $assArr['wt_Units'];?></th>
                              <th><?php echo $assArr['measurement_Units'];?></th>
                              <?php if(0){ ?>
                              <th><?php echo $assArr['gROSS_WT'];;?></th>
                               <?php } ?>
                              <th><?php echo 'Business Type'; ?></th>     
                           </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                           
                              $ordersHoldView= UserprofileHelpersUserprofile::getOrdersHoldList($user);
                              
                            //   echo '<pre>';
                            //   var_dump($ordersHoldView);exit;
                            
                            $i=0;
                            foreach($ordersHoldView as $res){
                                
                                
                                echo '<tr>
                                        		<td><button  class="expand_items btn btn-success" data-sno="item_wr_'.$i.'"  data-id="'.$res->BillFormNo.'">+</button><input type="checkbox" class="selinpt-chk check_all_items" data-id="'.$res->BillFormNo.'">
                                                </td>
                                        		<td><span class="whrse-link label-success"><input type="hidden" name="txtbiladdress" value="'.UserprofileHelpersUserprofile::getBindShipingAddress($user,$res->BillFormNo).'">'.$res->BillFormNo.'</span></td>
                                        		<td>'.$res->QtyReceived.'</td>
                                        		<td>'.$res->QtyShipped.'</td>
                                        		<td>'.$res->QtyOnHand.'</td>
                                        		<td>'.$res->QtyDelivered.'</td>
                                        		<td>'.$res->QtyRejected.'</td>
                                        		<td>'.$res->TrackingId.'</td>
                                        		<td>'.$res->MerchantName.'</td>
                                        		<td>'.$res->ShipmentType.'</td>
                                        	    <td>'.$res->SourceHub.'</td>
                                        	    <td>'.$res->DestinationCountryName.'</td>
                                        	    <td>'.$res->DestinationHubName.'</td>
                                        		<td>'.$res->WeightUnit.'</td>
                                        		<td>'.$res->MeasureUnits.'</td>
                                        		<td>'.$res->BusinessType.'</td>
                                        		</tr>';
                                        		
                                        echo '<tr style="display:none;" class="wrhuse-grid">
                                        		<td class="action_btns">
                                                </td>
                                        		<td>Action</td>
                                        		<td>Item Description</td>
                                        		<td>Quantity</td>
                                        		<td>Ship Quantity</td>
                                        		<td>Tracking ID</td>
                                        		<td>Merchant name</td>
                                        		<td>Order ID</td>
                                        		<td>RMA Value</td>
                                        		<td>View image</td>
                                        		<td></td>
                                        	    <td></td>
                                        	    <td></td>
                                        	    <td></td>
                                        		<td></td>
                                        		<td></td>
                                        	 </tr>';
                              
                              $idf=1;
                              
                              foreach($ordersHoldView[$i]->ItemDetails as $rg){
                                  
                                //   var_dump($rg->ItemImage1);
                                //   exit;
                               
                                if(1){ // $rg->ItemQuantity>0
                                    $volres=$res->Height*$res->Width*$res->Length*UserprofileHelpersUserprofile::getShippmentDetailsValues($res->MeasureUnits,$res->shipment_type,$res->ServiceType,$res->Source,$res->Dest_Cntry);
                                    if($res->shipment_type=="AIR"){
                                      $volres=$rg->ItemQuantity*$volres;
                                    }
                                    if($rg->ItemImage1==""){
                                        $sim=1; 
                                        $mgtd='<td>No Image</td>';
                                    }else{
                                            $sim=str_replace(":","#",$rg->ItemImage1);
                                            $sim1=str_replace(":","#",$rg->ItemImage2);
                                            $sim2=str_replace(":","#",$rg->ItemImage3);
                                            $sim3=str_replace(":","#",$rg->ItemImage4);
                                            $mgtd='<td class="action_btns"><a class="ship_img" data-toggle="modal" data-backdrop="static" data-keyboard="false" href="#" data-id="'.$rg->ItemImage1.'##'.$rg->ItemImage2.'##'.$rg->ItemImage3.'##'.$rg->ItemImage4.'" data-target="#view_image" ><i class="fa fa-eye"></i></a></td>';
                                      }
                                    $grosswtTd = NULL;
                                 if(0){
                                     $grosswtTd = "<td>".$res->weight."</td>";
                                 }  
                                 
                                 // class="check-invisable"
                                       echo '<tr style="display:none;">
                                                <td></td>
                                        		<td class="action_btns">
                                        		<input type="checkbox"  name="txtId" class="txtId selinpt-chksub" data-sno="item_wr_'.$i.'"  value="'.$rg->ItemName.':'.$res->BillFormNo.':'.$rg->ItemQuantity.':'.$res->TrackingId.':'.$rg->ItemIdk.':'.$rg->cost.':'.$rg->cost.':'.$idf.':'.$volres.':'.$res->ServiceType.':'.$res->Source.':'.$res->Dest_Cntry.':'.$res->MeasureUnits.':'.$res->Length.':'.$res->Width.':'.$res->Height.':'.$res->Weight.':'.$res->WeightUnit.':'.$sim.':'.$res->ShipmentType.':'.$res->SourceHub.':'.$res->DestinationCountryName.':'.$res->DestinationHubName.':'.$rg->Insurance.':'.$rg->ItemPrice.':'.$sim1.':'.$sim2.':'.$sim3.':'.$res->BusinessType.':'.$rg->Volume.':'.$rg->VolumetricWeight.'">
                                        		 <input type="button" name="ship" class="ship" data-sno="'.$idf.'" data-id="'.$rg->ItemName.':'.$res->BillFormNo.':'.$rg->ItemQuantity.':'.$res->TrackingId.':'.$rg->ItemIdk.':'.$rg->cost.':'.$rg->cost.':'.$idf.':'.$volres.':'.$res->ServiceType.':'.$res->Source.':'.$res->Dest_Cntry.':'.$res->MeasureUnits.':'.$res->Length.':'.$res->Width.':'.$res->Height.':'.$res->Weight.':'.$res->WeightUnit.':'.$sim.':'.$res->ShipmentType.':'.$res->SourceHub.':'.$res->DestinationCountryName.':'.$res->DestinationHubName.':'.$rg->Insurance.':'.$rg->ItemPrice.':'.$sim1.':'.$sim2.':'.$sim3.':'.$res->BusinessType.':'.$rg->Volume.':'.$rg->VolumetricWeight.'" data-target="#ord_ship" title="'.Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP').'">';
                                        			if(0)
                                        			echo '<input type="button" name="Return" class="return" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-id="'.$res->BillFormNo.':'.$rg->ItemId.':'.$rg->ItemQuantity.'" data-target="#ord_return"" title="'.Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RETURN').'">';
                                        			if(0)
                                        			echo '<input type="button" name="Keep" class="keep" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-id="'.$res->BillFormNo.':'.$rg->ItemId.':'.$rg->ItemQuantity.'" data-target="#ord_keep" " title="'.Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_HOLD').'">';
                                        		    echo '</td>
                                        		<td>'.$rg->ItemName.'</td>
                                        		<td>'.$rg->ItemQuantity.'</td>
                                        		<td><input type="hidden" name="ItemIdkTxt" value="'.$rg->ItemIdk.'"><input type="hidden" name="ItemQtyTxt" value="'.$rg->ItemQuantity.'"><input type="hidden" name="ItemQtyEdit" value="'.$rg->ItemQuantity.'">
                                                <input type="text" class="form-control" name="txtQty" value="'.$rg->ItemQuantity.'"></td>
                                                <td>'.$res->TrackingId.'</td>
                                        		<td>'.$res->MerchantName.'</td>
                                        		<td>'.$rg->OrderID.'</td>
                                        		<td>'.$rg->RmaValue.'</td>'.$mgtd.'<td></td><td></td><td></td><td></td><td></td><td></td>'.$grosswtTd.'</tr>';
                                              $p=$res->BillFormNo;
                                        //<td><input type="hidden" name="txtQty" value="'.$rg->Quantity.'"><input type="hidden" name="txtItemQty"><input type="hidden" name="txtbiladdress" value="'.UserprofileHelpersUserprofile::getBindShipingAddress($user,$res->BillFormNo).'">'.$res->BillFormNo.'</td>

                                        //         <td>'.$res->ShipmentType.'</td>
                                        // 		<td><div id="sourcehub" style="display:none">'.$res->SourceHub.'</div>'.$res->SourceHub.'</td>
                                        // 	    <td>'.$res->DestinationCountryName.'</td>
                                        // 	    <td>'.$res->DestinationHubName.'</td>
                                        // 		<td>'.$res->WeightUnit.'</td>
                                        // 		<td>'.$res->MeasureUnits.'</td>
                                           
                                     
                                    }
                                  $idf++;
                                  
                              }
                              
                              $i++;
                              
                            }
                              
                              ?>
                              
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="clearfix"></div>
            <?php  
            
            Controlbox::getOrdersPendingListCsv($user);
            
            ?>
            
            <div class="row ishpments2">
            <div class="">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                        <h3 class=""><strong><?php echo Jtext::_('COM_USERPROFILE_SHIP_SUB_TITLE');?></strong></h3>
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <a style="color:white;" target="_blank" href="<?php echo $backend_url; ?>/ASPX/Tx_inventoryReceipt.aspx?bid=<?php echo $user; ?>&companyid=<?php echo $CompanyId; ?>"  class="btn btn-primary csvDownload">Inventory Reports</a>
                        <?php if($elem['EXPORTCSV'][1] == "ACT"){ ?>
                        <a style="color:white;" href="<?php echo $assArr['eXPORT_CSV']; ?>/csvdata/pending_list.csv" class="btn btn-primary csvDownload export-csv"><?php echo $assArr['eXPORT_CSV'];?></a>
                        <?php }  ?>
                    </div>
                </div>
            </div>
                
                
               <div class="col-md-12">
                  <div class="table-responsive">
                     <table class="table table-bordered theme_table" id="j_table" data-page-length='10'>
                        <thead>
                           <tr> 
                             <th class="action_btns text-center"><input type="checkbox" name="selectAll" id="selectAll"> <?php echo $assArr['select_All'];?>Select All</th>
                                              
                              <th><?php echo 'warehouse_Receipt# '; $assArr['warehouse_Receipt#'];?></th>
                              <th><?php echo 'Qty Received'; ?></th>
                              <th><?php echo 'Qty Shipped'; ?></th>
                              <th><?php echo 'Qty OnHand'; ?></th>
                              <th><?php echo 'Qty Delivered'; ?></th>
                              <th><?php echo 'Qty Rejected'; ?></th>
                              <th><?php echo $assArr['tracking_ID'];?></th>
                              <th><?php echo $assArr['merchants_Name'];?></th>
                              <th><?php echo $assArr['type_of_shipment'];?></th>
                               <th><?php echo $assArr['source_Hub'];?></th>
                              <th><?php echo $assArr['destination'];?></th>
                              <th><?php echo $assArr['destination_Hub'];?></th>
                              <th><?php echo $assArr['wt_Units'];?></th>
                              <th><?php echo $assArr['measurement_Units'];?></th>
                              <?php if(0){ ?>
                              <th><?php echo $assArr['gROSS_WT'];;?></th>
                               <?php } ?>
                              <th><?php echo 'Business Type'; ?></th>     
                           </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                           
                              $ordersPendingView= UserprofileHelpersUserprofile::getOrdersPendingList($user);
                              
                            //   echo '<pre>';
                            //   var_dump($ordersPendingView);exit;
                            
                            $i=0;
                            foreach($ordersPendingView as $res){
                                
                                
                                echo '<tr>
                                        		<td><button  class="expand_items btn btn-success" data-sno="item_wr_'.$i.'"  data-id="'.$res->BillFormNo.'">+</button><input type="checkbox" class="selinpt-chk check_all_items" data-id="'.$res->BillFormNo.'">
                                                </td>
                                        		<td><span class="whrse-link label-success"><input type="hidden" name="txtbiladdress" value="'.UserprofileHelpersUserprofile::getBindShipingAddress($user,$res->BillFormNo).'">'.$res->BillFormNo.'</span></td>
                                        		<td>'.$res->QtyReceived.'</td>
                                        		<td>'.$res->QtyShipped.'</td>
                                        		<td>'.$res->QtyOnHand.'</td>
                                        		<td>'.$res->QtyDelivered.'</td>
                                        		<td>'.$res->QtyRejected.'</td>
                                        		<td>'.$res->TrackingId.'</td>
                                        		<td>'.$res->MerchantName.'</td>
                                        		<td>'.$res->ShipmentType.'</td>
                                        	    <td>'.$res->SourceHub.'</td>
                                        	    <td>'.$res->DestinationCountryName.'</td>
                                        	    <td>'.$res->DestinationHubName.'</td>
                                        		<td>'.$res->WeightUnit.'</td>
                                        		<td>'.$res->MeasureUnits.'</td>
                                        		<td>'.$res->BusinessType.'</td>
                                        		</tr>';
                                        		
                                        echo '<tr style="display:none;" class="wrhuse-grid">
                                        		<td class="action_btns">
                                                </td>
                                        		<td>Action</td>
                                        		<td>Item Description</td>
                                        		<td>Quantity</td>
                                        		<td>Ship Quantity</td>
                                        		<td>Tracking ID</td>
                                        		<td>Merchant name</td>
                                        		<td>Order ID</td>
                                        		<td>RMA Value</td>
                                        		<td>View image</td>
                                        		<td></td>
                                        	    <td></td>
                                        	    <td></td>
                                        	    <td></td>
                                        		<td></td>
                                        		<td></td>
                                        	 </tr>';
                              
                              $idf=1;
                              
                              foreach($ordersPendingView[$i]->ItemDetails as $rg){
                                  
                                //   var_dump($rg->ItemImage1);
                                //   exit;
                               
                                if(1){ // $rg->ItemQuantity>0
                                    $volres=$res->Height*$res->Width*$res->Length*UserprofileHelpersUserprofile::getShippmentDetailsValues($res->MeasureUnits,$res->shipment_type,$res->ServiceType,$res->Source,$res->Dest_Cntry);
                                    if($res->shipment_type=="AIR"){
                                      $volres=$rg->ItemQuantity*$volres;
                                    }
                                    if($rg->ItemImage1==""){
                                        $sim=1; 
                                        $mgtd='<td>No Image</td>';
                                    }else{
                                            $sim=str_replace(":","#",$rg->ItemImage1);
                                            $sim1=str_replace(":","#",$rg->ItemImage2);
                                            $sim2=str_replace(":","#",$rg->ItemImage3);
                                            $sim3=str_replace(":","#",$rg->ItemImage4);
                                            $mgtd='<td class="action_btns"><a class="ship_img" data-toggle="modal" data-backdrop="static" data-keyboard="false" href="#" data-id="'.$rg->ItemImage1.'##'.$rg->ItemImage2.'##'.$rg->ItemImage3.'##'.$rg->ItemImage4.'" data-target="#view_image" ><i class="fa fa-eye"></i></a></td>';
                                      }
                                    $grosswtTd = NULL;
                                 if(0){
                                     $grosswtTd = "<td>".$res->weight."</td>";
                                 }  
                                 
                                 // class="check-invisable"
                                       echo '<tr style="display:none;">
                                                <td></td>
                                        		<td class="action_btns">
                                        		<input type="checkbox"  name="txtId" class="txtId selinpt-chksub" data-sno="item_wr_'.$i.'"  value="'.$rg->ItemName.':'.$res->BillFormNo.':'.$rg->ItemQuantity.':'.$res->TrackingId.':'.$rg->ItemIdk.':'.$rg->cost.':'.$rg->cost.':'.$idf.':'.$volres.':'.$res->ServiceType.':'.$res->Source.':'.$res->Dest_Cntry.':'.$res->MeasureUnits.':'.$res->Length.':'.$res->Width.':'.$res->Height.':'.$res->Weight.':'.$res->WeightUnit.':'.$sim.':'.$res->ShipmentType.':'.$res->SourceHub.':'.$res->DestinationCountryName.':'.$res->DestinationHubName.':'.$rg->Insurance.':'.$rg->ItemPrice.':'.$sim1.':'.$sim2.':'.$sim3.':'.$res->BusinessType.':'.$rg->Volume.':'.$rg->VolumetricWeight.'">
                                        		 <input type="button" name="ship" class="ship" data-sno="'.$idf.'" data-id="'.$rg->ItemName.':'.$res->BillFormNo.':'.$rg->ItemQuantity.':'.$res->TrackingId.':'.$rg->ItemIdk.':'.$rg->cost.':'.$rg->cost.':'.$idf.':'.$volres.':'.$res->ServiceType.':'.$res->Source.':'.$res->Dest_Cntry.':'.$res->MeasureUnits.':'.$res->Length.':'.$res->Width.':'.$res->Height.':'.$res->Weight.':'.$res->WeightUnit.':'.$sim.':'.$res->ShipmentType.':'.$res->SourceHub.':'.$res->DestinationCountryName.':'.$res->DestinationHubName.':'.$rg->Insurance.':'.$rg->ItemPrice.':'.$sim1.':'.$sim2.':'.$sim3.':'.$res->BusinessType.':'.$rg->Volume.':'.$rg->VolumetricWeight.'" data-target="#ord_ship" title="'.Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP').'">';
                                        			if($elem['Hold'][1] == "ACT")
                                        			echo '<input type="button" name="Return" class="return" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-id="'.$res->BillFormNo.':'.$rg->ItemId.':'.$rg->ItemQuantity.'" data-target="#ord_return"" title="'.Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RETURN').'">';
                                        			if($elem['Return'][1] == "ACT")
                                        			echo '<input type="button" name="Keep" class="keep" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-id="'.$res->BillFormNo.':'.$rg->ItemId.':'.$rg->ItemQuantity.'" data-target="#ord_keep" " title="'.Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_HOLD').'">';
                                        		    echo '</td>
                                        		<td>'.$rg->ItemName.'</td>
                                        		<td>'.$rg->ItemQuantity.'</td>
                                        		<td><input type="hidden" name="ItemIdkTxt" value="'.$rg->ItemIdk.'"><input type="hidden" name="ItemQtyTxt" value="'.$rg->ItemQuantity.'"><input type="hidden" name="ItemQtyEdit" value="'.$rg->ItemQuantity.'">
                                                <input type="text" class="form-control" name="txtQty" value="'.$rg->ItemQuantity.'"></td>
                                                <td>'.$res->TrackingId.'</td>
                                        		<td>'.$res->MerchantName.'</td>
                                        		<td>'.$rg->OrderID.'</td>
                                        		<td>'.$rg->RmaValue.'</td>'.$mgtd.'<td></td><td></td><td></td><td></td><td></td><td></td>'.$grosswtTd.'</tr>';
                                              $p=$res->BillFormNo;
                                        //<td><input type="hidden" name="txtQty" value="'.$rg->Quantity.'"><input type="hidden" name="txtItemQty"><input type="hidden" name="txtbiladdress" value="'.UserprofileHelpersUserprofile::getBindShipingAddress($user,$res->BillFormNo).'">'.$res->BillFormNo.'</td>

                                        //         <td>'.$res->ShipmentType.'</td>
                                        // 		<td><div id="sourcehub" style="display:none">'.$res->SourceHub.'</div>'.$res->SourceHub.'</td>
                                        // 	    <td>'.$res->DestinationCountryName.'</td>
                                        // 	    <td>'.$res->DestinationHubName.'</td>
                                        // 		<td>'.$res->WeightUnit.'</td>
                                        // 		<td>'.$res->MeasureUnits.'</td>
                                           
                                     
                                    }
                                  $idf++;
                                  
                              }
                              
                              $i++;
                              
                            }
                              
                              ?>
                              
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="clearfix"></div>
            <div>
               <div id="step1" style="display:none">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                           <table class="table table-bordered theme_table" id="j_table">
                              <thead>
                                 <tr>
                                    <th><?php echo $assArr['item_Description']?></th>
                                    <th><?php echo $assArr['warehouse_Receipt#'];?></th>
                                    <th><?php echo $assArr['quantity'];?></th>
                                    <th><?php echo $assArr['tracking_ID'];?></th>
                                    <th><?php echo $assArr['delete'];?></th>
                                 </tr>
                              </thead>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12 text-center btn-grp1">
                        <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_JTABLE_NEXT_BTN');?>" class="btn btn-primary shipsubmit" data-target="#ord_ship" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                        <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_JTABLE_CLOSE_BTN');?>" data-dismiss="modal" class="btn btn-danger">
                     </div>
                  </div>
               </div>
            </div>
            <div id="srol" tabindex="1"></div>
         </div>
      </div>
   </div>
</div>
<!-- Modal -->
<form name="userprofileFormTwo" id="userprofileFormTwo" method="post" action="" enctype="multipart/form-data">
   <div id="ord_return" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <input type="button" data-dismiss="modal"  value="x" class="btn-close1">        
               <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_RETURN_TITLE'); ?></strong></h4>
            </div>
            <div class="modal-body2"></div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-12 col-md-6">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_BACK_COMPANY'); ?><span class="error">*</span></label>
                        <input type="text" name="txtBackCompany" class="form-control">
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_ADDRESS'); ?> <span class="error">*</span></label>
                        <input type="text" name="txtReturnAddress" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 col-md-6">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_SHIPPING_CARRIER'); ?>  <span class="error">*</span></label>
                        <input type="text" name="txtReturnCarrier" class="form-control">
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_REASON'); ?><span class="error">*</span></label>
                        <input type="text" name="txtReturnReason" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 col-md-6">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_ORDER_NUMBER'); ?><span class="error">*</span></label>
                        <input type="text" name="txtOriginalOrderNumber" class="form-control">
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_MERCHANT_NUMBER'); ?><span class="error">*</span></label>
                        <input type="text" name="txtMerchantNumber" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 col-md-6">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_SHIPPING_LABEL'); ?> </label>
                        <input type="file" name="fluReturnShippingLabel" class="form-control">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_UPLOAD_FILE_VALIDATION'); ?></label>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_SPECIAL_INSTRUCTION'); ?> <span class="error">*</span></label>
                        <input type="text" name="txtSpecialInstructions" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 text-center">
                     <input type="submit" value="<?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_UPDATE'); ?>" class="btn btn-primary">
                     <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_RETURN_RETURN_CLOSE'); ?>" data-dismiss="modal" class="btn btn-danger">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" name="task" value="user.returnshippment">
   <input type="hidden" name="id" />
   <input type="hidden" name="idk" id="idk" />
   <input type="hidden" name="qty"/>
   <input type="hidden" name="user" value="<?php echo $user;?>" />
</form>
<!-- Modal -->
<form name="userprofileFormThree" id="userprofileFormThree" method="post" action="" enctype="multipart/form-data">
   <div id="ord_keep" class="modal fade" role="dialog">
      <div class="modal-dialog modal-sm">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_HOLD_POPUP_INFO');  ?></strong></h4>
            </div>
            <div class="modal-body2"></div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="form-group">
                        <label><?php echo Jtext::_('COM_USERPROFILE_HOLD_POPUP_REASON');  ?><span class="error">*</span></label>
                        <input type="text" name="txtReturnReason" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 text-center">
                     <input type="submit" value="<?php echo Jtext::_('COM_USERPROFILE_HOLD_POPUP_UPDATE');  ?>" class="btn btn-primary">
                     <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_HOLD_POPUP_CLOSE');  ?>" data-dismiss="modal" class="btn btn-danger">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" name="task" value="user.holdshippment">
   <input type="hidden" name="id" />
   <input type="hidden" name="qty" />
   <input type="hidden" name="idk" id="idk2" />
   <input type="hidden" name="user" value="<?php echo $user;?>" />
</form>
<!-- Modal -->
<form name="userprofileFormFour" id="userprofileFormFour" method="post" action="" enctype="multipart/form-data">
   <div id="ord_delete" class="modal fade" role="dialog">
      <div class="modal-dialog modal-sm">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title"><strong>Delete Information</strong></h4>
            </div>
            <div class="modal-body2"></div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="form-group">
                        <label>Reason for Delete<span class="error">*</span></label>
                        <input type="text" name="txtReturnReason" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 text-center">
                     <input type="submit" value="Update" class="btn btn-primary">
                     <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" name="task" value="user.discardshippment">
   <input type="hidden" name="id" />
   <input type="hidden" name="qty" />
   <input type="hidden" name="idk" id="idk3" />
   <input type="hidden" name="user" value="<?php echo $user;?>" />
</form>
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
<!--<div id="view_image" class="modal fade" role="dialog">-->
<!--   <div class="modal-dialog modal-md">-->
<!--      <div class="modal-content">-->
<!--         <div class="modal-header">-->
<!--            <input type="button" data-dismiss="modal" value="x" class="btn-close1">-->
<!--            <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_SHIP_TABLE_IMAGE_VIEW');?></strong></h4>-->
<!--         </div>-->
<!--         <div class="modal-body2"></div>-->
<!--         <div class="modal-body">-->
<!--            <div class="row">-->
<!--               <div class="col-md-12">-->
<!--                  <div id="viewImage"></div>-->
<!--               </div>-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
<!--</div>-->
<!-- Modal -->

<!-- Modal -->
<div id="view_image" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">
        <h4 class="modal-title"><strong>Image View</strong></h4>
      </div>
      <div class="modal-body">
        <div class="row">
            
          <div class="col-md-12">
            <div id="viewImage">
                <div class="row" id="multimages">
                    
                </div>
                <div class="mult-img-container">
                <img id="expandedImg" src="" style="width:100%">
                <div id="imgalttext"></div>
                </div>
            </div>
          </div>
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
            <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_SHIP_MODAL_TITLE');?></strong></h4>
         </div>
         <div class="modal-body2"></div>
         <div class="modal-body pagshipup" style="display:none"><img src='/components/com_userprofile/images/loader.gif' height="400"></div>
         <div class="modal-body pagshipdown">
            <form name="userprofileFormFive" id="userprofileFormFive"  method="post" action="" enctype="multipart/form-data">
               <div id="step2">
                  <div class="">
                     <div class="col-md-12">
                        <div class="rcvship-blk">
                           <h4><?php echo Jtext::_('COM_USERPROFILE_SHIP_MODAL_HEADING1');?></h4>
                           <label class="txt-hd"><?php echo Jtext::_('COM_USERPROFILE_SHIP_MODAL_SHIPPING_ADDRESS');?></label>
                           <div id="ChangeShippingAddressHidden" style="display:none"></div>
                           <input type="hidden" id="dtStr2">
                           <div class="shp-addnew1">
                              <div id="ChangeShippingAddressNew"></div>
                           </div>
                           
                           <?php if($elem['Changeaddress'][1] == "ACT"){  ?>
                           
                           <div id="ChangeShippingAddressStr" class="chg-address">
                              <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_MODAL_CHANGE_ADDRESS');?></label>
                           </div>
                           <div class="clearfix"></div>
                           <div id="ChangeShippingAddress" style="display:none" class="addtion-user">
                              <div id="additionalusersData" class="form-group"><select class="form-control" name="adduserlistStr"></select></div>
                              <div id="loading-image2" style="display:none"><img src='/components/com_userprofile/images/loader.gif'></div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <input type="button" id="addusers" class="btn btn-primary" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_MODAL_ADDITIONAL_ADDRESS');?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#exampleModal">
                                 </div>
                              </div>
                           </div>
                           
                           <?php } ?>
                           
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="table-responsive">
                                 <table class="table table-bordered theme_table" id="kk_table">
                                    <thead>
                                       <tr>
                                          <th><?= Jtext::_('COM_USERPROFILE_SHIP_KTABLE_ITEM_DESC') ?></th>
                                          <th><?= Jtext::_('COM_USERPROFILE_SHIP_KTABLE_QUANTITY') ?></th>
                                          <th><?= Jtext::_('COM_USERPROFILE_SHIP_KTABLE_TRACKING_ID') ?></th>
                                          <th><?= Jtext::_('COM_USERPROFILE_SHIP_KTABLE_DECLARED_VALUE') ?></th>
                                          <!-- <a href="" target="_blank"> <? // = Jtext::_('COM_USERPROFILE_SHIP_KTABLE_TERMS_N_CONDITIONS') ?> </a> -->
                                          <th><?= Jtext::_('COM_USERPROFILE_SHIP_KTABLE_INVOICE') ?></th>
                                         
                                       </tr>
                                    </thead>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <br>
                        <div class="rdo_cust">
                           <div id="divShiprates"></div>
                          
                           <?php
                              //$res = Controlbox::getServiceType();
                              //   echo '<pre>';
                              //   var_dump($res);exit;
                              
                              ?>
                           <div id="getServiceTypeDiv"></div>
                           <!--<div class="rdo_cust">-->
                           <!--    <div class="rdo_rd1">-->
                           <!--      <?php  //foreach($res as $data){ ?>-->
                           <!--          <input type="radio" name="shipmentStr" value="<?php echo $data->id_values; ?>">-->
                           <!--          <label><?php echo $data->desc_vals; ?></label>-->
                           <!--      <?php //} ?>-->
                           <!--    </div>-->
                           <!--</div>-->
                           <!--<div class="rdo_cust">-->
                           <!--   <div class="rdo_rd1">-->
                           <!--        <input type="radio" name="shipmentStr" value="Standard">-->
                           <!--        <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_STANDARD');?></label>-->
                           <!--        <input type="radio" name="shipmentStr" value="Express">-->
                           <!--        <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_EXPRESS');?></label>-->
                           <!--  </div>-->
                           <!--</div>-->
                           <div id="divShipCOstOne" class="col-md-12"></div>
                           <div class="clearfix"></div>
                           <div id="divShipCOstTwo" class="rst_text"></div>
                           <div class="clearfix"></div>
                           <!-- <div class="row">-->
                           <!--   <div class="col-md-12">-->
                           <!--    <div class="form-group servc-chk">-->
                           <!--     <div id="getservices">-->
                           <!--      <div style="color:#EC1B24;">-->
                           <!--          <label><?php echo Jtext::_('Additional Services'); ?></label>-->
                           <!--      </div>-->
                           <!--     </div>-->
                           <?php 
                              // $result= UserprofileHelpersUserprofile::getAdditionalServiceList();
                              
                              // foreach($result as $rg){
                              //   echo '<input type="checkbox"  name="txtService"  id="txtService" value="'.$rg->Cost.':'.$rg->id_AddnlServ.'"><label for="option">&nbsp;'.$rg->Addnl_Serv_Name.'</label><br>';
                              // }
                              ?>
                           <!--    </div>-->
                           <!--   </div>-->
                           <!--</div> -->
                           <div class="clearfix"></div>
                           <div class="spcl-ins">
                              <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_SPECIAL_INSTRUCTIONS');?></label>
                              <textarea name="specialinstructionStr" placeholder="Enter up to 250 characters.... " maxlength="250" class="form-control"></textarea>
                              <span id="count"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12 text-center">
                           <input type="button" id="goto_payment_scr" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_JTABLE_NEXT_BTN');?>" class="btn btn-primary">
                           <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_JTABLE_CLOSE_BTN');?>" data-dismiss="modal" class="btn btn-danger">
                        </div>
                     </div>
                  </div>
               </div>
               <div id="step3" style="display:none">
                  <?php $CompanyId = Controlbox::getCompanyId(); ?>
                  <input type="hidden" id="companyId" name="companyId" value="<?php echo $CompanyId; ?>">
                  
                  <input type="hidden" id="extAddSer" name="extAddSer">
                  <input type="hidden" id="shipmenttype" name="shipmenttype">
                  <input type="hidden" id="wherhourecStr" name="wherhourecStr">
                  <input type="hidden" id="volresStr" name="volresStr">
                  <input type="hidden" id="tyserviceStr" name="tyserviceStr">
                  <input type="hidden" id="srStr" name="srStr">
                  <input type="hidden" id="dtStr" name="dtStr">
                  <input type="hidden" id="mrunitsStr" name="mrunitsStr">
                  <input type="hidden" id="volStr" name="volStr">
                  <input type="hidden" id="volmetStr" name="volmetStr">
                  <input type="hidden" id="shipmentCost" name="shipmentCost">
                  <input type="hidden" id="totalDecVal" name="totalDecVal">
                  <input type="hidden" id="invidkStr" name="invidkStr">
                  <input type="hidden" id="qtyStr" name="qtyStr">
                  <input type="hidden" id="trackingidStr" name="trackingidStr">
                  <input type="hidden" id="ItemPriceStr" name="ItemPriceStr">
                  <input type="hidden" id="costStr" name="costStr">
                  <input type="hidden" id="txtspecialinsStr" name="txtspecialinsStr">
                  <input type="hidden" name="shipcostStr" />
                  <input type="hidden" name="shipservtStr" />
                  <input type="hidden" name="consignidStr" id="consignidStr" />
                  <input type="hidden" name="heightStr" id="heightStr" />
                  <input type="hidden" name="lengthStr" id="lengthStr" />
                  <input type="hidden" name="widthStr" id="widthStr" />
                  <input type="hidden" name="weightStr" id="weightStr" />
                  <input type="hidden" name="dtunitStr" id="dtunitStr" />
                  <input type="hidden" name="bustypeStr" id="bustypeStr" />
                  <input type="hidden" id="ratetypeStr" name="ratetypeStr">
                  <input type="hidden" id="addSerStr" name="addSerStr">
                  <input type="hidden" id="addSerCostStr" name="addSerCostStr">
                  <input type="hidden" id="insuranceCost" name="insuranceCost">
                  <input type="hidden" id="couponCodeStr" name="couponCodeStr">
                  <input type="hidden" id="couponDiscAmt" name="couponDiscAmt">
                  <?php $UserViews=UserprofileHelpersUserprofile::getUserpersonalDetails($user);?>
                  <input type="hidden" name="fnameStr" value="<?php echo $UserViews->AdditionalFirstName;?>" />
                  <input type="hidden" name="lnameStr" value="<?php echo $UserViews->AdditionalLname;?>" />
                  <input type="hidden" name="addressStr" value="<?php echo $UserViews->AddressAccounts.' '.$UserViews->addr_2_name;?>" />
                  <input type="hidden" name="cityStr" value="<?php echo $UserViews->desc_city;?>"/>
                  <input type="hidden" name="stateStr" value="<?php echo $UserViews->State;?>"/>
                  <input type="hidden" name="zipStr" value="<?php echo $UserViews->PostalCode;?>"/>
                  <input type="hidden" name="countryStr" value="<?php echo $UserViews->Country;?>"/>
                  <input type="hidden" name="emailStr" value="<?php echo $UserViews->PrimaryEmail;?>"/> <!-- Jorge.farias@cifexpressusa.com -->
                  <input type='hidden' name='business' value='<?php echo $PaypalEmail; ?>'> 
                  <input type='hidden'   name='item_name'>
                  <input type='hidden'   name='item_data'>
                  <input type='hidden'   name='data_test'>
                  <input type='hidden' name='item_number'> 
                  <input type='hidden' name='amount'>
                  <input type='hidden' name='page' value="orderprocess">
                  <input type='hidden' name='paypalinvoice' id="paypalinvoice" >
                  <input type='hidden' name='Conveniencefees' id="Conveniencefees" value="0">
                  <input type='hidden' name='no_shipping' value='1'> 
                  <input type='hidden' name='currency_code' value='USD'>
                  <input type='hidden' name='notify_url' value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=notify-order'>
                  <input type='hidden' name='cancel_return'
                     value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=orderprocess'>
                  <input type='hidden' name='return'
                     value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=response&invoiceType=Inhouse&invc=<?php echo $invoiceCountRes->Data; ?>&pay=<?php echo base64_encode(date("m/d/0Y"));?>'>
                  <input type="hidden" name="cmd" value="_xclick">  
                  <div class="row">
                     <div class="col-md-12">
                        <div class="finish-shipping">
                           <label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_FINISH_SHIPPING');?> : </label>
                           <div id="shipmethodStrValuetwo" style="float:right"></div>
                           <p> <?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_MY_SUMMARY');?></p>
                           <p> <?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_INCLUDES');?>:</p>
                           <br/>
                          <div class="cupn-cde">
                               <a href="">Apply Coupons</a>
                               <p style="display:none;">Apply Coupons</p>
                           </div>
                           <div class="cupn-detals">
                           <div class="cupn-cdes">
                                       <!--DYNAMIC CONTENT-->
                           </div>
                           </div>
                           <table width="100%" class="table table-bordered theme_table shipping-costtbl">
                              <tr>
                                 <td colspan="2"><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_SHIPPING_OPTIONS');?></label></td>
                              </tr>
                              <tr>
                                 <td>
                                    <label>
                                       <?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_SHIPPING_METHOD');?> -
                                       <div id="shipmethodStrtext" class="shp-mtd"></div>
                                    </label>
                                 </td>
                                 <td class="txt-right">
                                    <div id="shipmethodStrValue"></div>
                                 </td>
                              </tr>
                              <tr>
                                 <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_ADDITIONAL_SERVICES');?></label></td>
                                 <td class="txt-right">
                                    <div id="addserStr"></div>
                                 </td>
                              </tr>
                              <tr>
                                 <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_STORAGE_CHARGES');?></label></td>
                                 <td class="txt-right" id="storageCharges">0.00</td>
                              </tr>
                              <tr>
                                 <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_INSURANCE_COST');?></label></td>
                                 <td class="txt-right">
                                    <div id="insuranceDiv"></div>
                                 </td>
                              </tr>
                              <tr>
                                 <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_FUEL_CHARGES');?></label></td>
                                 <td class="txt-right">
                                    <div id="fuelCharges"></div>
                                 </td>
                              </tr>
                              <tr>
                                 <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_DISCOUNT');?></label></td>
                                 <td class="txt-right">
                                    <div id="discountStr"></div>
                                 </td>
                              </tr>
                              
                              <tr>
                                 <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_SPECIAL_INSTRUCTIONS');?></label>
                                 </td>
                                 <td class="txt-right">
                                    <div id="specialinstructionDiv"></div>
                                 </td>
                              </tr>
                              <tr class="total_cst">
                                 <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_TOTAL_BUY');?></label></td>
                                 <td class="txt-right">
                                    <div id="shipmethodtotalStr"></div>
                                 </td>
                              </tr>
                           </table>
                           <div id="loading-image5"  tabindex="1" style="display:none"><img src="<?php echo JURI::base(); ?>/components/com_userprofile/images/loader.gif" height="500"></div>
                           <div class="clearfix"></div>
                           
                            <div class="rdo_cust">
                            <div class="rdo_rd1">
                            <div class="paymentmethodsDiv">
                                
                                <?php 
                                    $paymentmethodsStr = ''; 
                                    $paymentmethods = Controlbox::getpaymentmethods();
                                    foreach($paymentmethods as $method){
                                        if($method->desc_vals){
                                            if($method->id_values == "COD"){
                                                $paymentmethodsStr .= '<input type="radio" name="cc" value="'.$method->id_values.'">';
                                                $paymentmethodsStr .='<label>'.$method->desc_vals.'</label>';
                                            }
                                        }
                                    }
                                    
                                    $paymentmethodsStr.=Controlbox::getpaymentgateways('PPD');
                                    echo $paymentmethodsStr;
                                ?>
                               
                            </div>    
                            </div>    
                            </div>
                            
                           
                           <!--End-->
                           
                           <!--paypal credit card payment-->
  <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 paypalCreditDebit" style="display:none;" >                        
    <div id="smart-button-container">
      <div style="text-align: center;">
        <div id="paypal-button-container"></div>
      </div>
    </div>
  </div> 
                           
                           <!--end -->
                           
                           
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
                           <div class="modal-body pagshipup_prepaid" style="display:none"><img src='/components/com_userprofile/images/loader.gif' height="400"></div>
                           <div class="clearfix"></div>
                           <div class="row">
                              <div class="col-md-12 text-center final_btns">
                                 <input type="hidden" name="task" value="user.payshippment">
                                 <input type="hidden" name="id" />
                                 <input type="hidden" name="user" value="<?php echo $user;?>" />
                                 <input type="hidden" id="amount" name="amount">
                                 <input type="hidden" id="card-nonce" name="nonce">
                                 <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_BACK_BTN');?>" class="btn btn-back">
                                 <input type="submit" onclick="return onGetCardNonce(event)"  value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_SHIP_BTN');?>" class="btn btn-primary final_ship">  <!--  -->
                                 <input type="button" value="<?php echo Jtext::_('COM_USERPROFILE_SHIP_POPUP_CLOSE_BTN');?>" data-dismiss="modal" class="btn btn-danger"> 
                           
                          
                             </div>
                           </div>
                           <div class="form-group"></div>
                        </div>
                     </div>
                  </div>
            </div>
        </form>
        
            
            <!--<div id="step4" style="display:none">-->
            <!--   <form name="userprofileFormSix" id="userprofileFormSix" method="post" action="" enctype="multipart/form-data">-->
            <!--      <input type="hidden" id="wherhourecStr" name="wherhourecStr">-->
            <!--      <input type="hidden" id="wherhourec2Str" name="wherhourec2Str">-->
            <!--      <input type="hidden" id="invidk2Str" name="invidk2Str">-->
            <!--      <input type="hidden" id="qty2Str" name="qty2Str">-->
            <!--      <input type="hidden" id="trackingidStr" name="trackingidStr">-->
            <!--      <input type="hidden" id="ItemPriceStr" name="ItemPriceStr">-->
            <!--      <input type="hidden" name="shipservtStr" />-->
            <!--      <input type="hidden" name="consignidStr" id="consignidStr" />-->
            <!--      <div class="row">-->
            <!--         <div class="col-md-12">-->
            <!--            <div class="finish-shipping">-->
            <!--               <p> My Summary Shipping</p>-->
            <!--               <div class="table-responsive">-->
            <!--                  <table class="table table-bordered theme_table" id="ki_table">-->
            <!--                     <thead>-->
            <!--                        <tr>-->
            <!--                           <th>Item Description</th>-->
            <!--                           <th>Quantity</th>-->
            <!--                           <th>Tracking Id</th>-->
            <!--                           <th>Declared Value($)</th>-->
            <!--                           <th>Invoice</th>-->
            <!--                        </tr>-->
            <!--                     </thead>-->
            <!--                  </table>-->
            <!--               </div>-->
            <!--               <div id="loading-image5"  tabindex="1" style="display:none"><img src="<?php echo JURI::base(); ?>/components/com_userprofile/images/loader.gif" height="500"></div>-->
            <!--               <div class="clearfix"></div>-->
            <!--               <div class="clearfix"></div>-->
                           
            <!--               <div class="form-group"></div>-->
            <!--            </div>-->
            <!--         </div>-->
            <!--      </div>-->
            <!--     </form>-->
            <!--     <div class="row">-->
            <!--                  <div class="col-md-12 text-center">-->
            <!--                     <input type="hidden" name="task" value="user.payshippment">-->
            <!--                     <input type="hidden" name="sid" value="2" />-->
            <!--                     <input type="hidden" name="id" />-->
            <!--                     <input type="hidden" name="user" value="<?php echo $user;?>" />-->
            <!--                     <input type="submit" value="Ship" class="btn btn-primary">-->
            <!--                     <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">-->
            <!--                  </div>-->
            <!--               </div>-->
            <!--</div>-->
            <!--</form>-->
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
            <h4 class="modal-title"><strong><?php echo $assArr['additional_address'];?></strong></h4>
         </div>
         <form name="userprofileFormSeven" id="userprofileFormSeven" method="post" action=""  enctype="multipart/form-data">
            <div class="modal-body pagshipup" style="display:none"><img src='/components/com_userprofile/images/loader.gif' height="400"></div>
            <div class="modal-body pagshipdown">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo  $assArr['type_of_user'];?> <span class="error">*</span></label>
                        <input type="text" class="form-control" name="typeuserTxt" id="typeuserTxt" readonly>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['id'];?>  <span class="error">*</span></label>
                        <input type="text" class="form-control"  name="idTxt" id="idTxt" readonly>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['first_name'];?>  <span class="error">*</span></label>
                        <input type="text" class="form-control" name="fnameTxt" id="fnameTxt" maxlength="25">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['last_Name'];?> <span class="error">*</span></label>
                        <input type="text" class="form-control"  name="lnameTxt" id="lnameTxt" maxlength="25">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['address_1'];?>  <span class="error">*</span></label>
                        <textarea type="text" class="form-control" name="addressTxt" id="addressTxt" maxlength="35"></textarea>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['address_2'];?> 2 </label>
                        <textarea type="text" class="form-control" name="address2Txt" id="address2Txt" maxlength="35"></textarea>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['country'];?> <span class="error">*</span></label>
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
                        <label><?php echo $assArr['state'];?> <span class="error">*</span></label>
                        <select class="form-control"  name="state2Txt" id="state2Txt">
                           <option value="">Select State</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['city'];?></label>
                        <!--<input type="text" class="form-control"  name="city2Txt" list="city2Txt" />-->
                        <!--<datalist id="city2Txt"></datalist>-->
                        <!--<input type="hidden" name="city2Txtdiv" id="city2Txtdiv">-->
                        <select  class="form-control"  name="city2Txt" id="city2Txt" autocomplete="off">
                           <option value=""><?php echo Jtext::_('COM_USERPROFILE_SELECT_CITY_LABEL');?></option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['zip_code'];?> </label>
                        <input type="text" class="form-control" name="zipTxt" id="zipTxt">
                     </div>
                  </div>
               </div>
             
              <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo $assArr['identification_type'];?><span class="error">*</span></label>
                        <?php
                                    $idtypeView= Controlbox::getidentityList();
                                    $arr = json_decode($idtypeView); 
                                    $idtypeformfive='';
                                    foreach($arr as $rg){
                                     $idtypeformfive.= '<option value="'.$rg->id_values.'">'.$rg->id_values.'</option>';
                                    } 
                                    ?>
                                 <select class="form-control" id="idtypeTxt" name="idtypeTxt">
                                    <option value=""><?php echo Jtext::_('COM_REGISTER_SELECT_IDENTITY_LABEL');?></option>
                                    <?php echo $idtypeformfive;?>
                                 </select>
                    </div>
                </div>    
                <div class="col-md-6">
                    <div class="form-group">
                                 <label><?php echo $assArr['identification_value'];?><span class="error">*</span></label>
                                 <input type="text" class="form-control" name="idvalueTxt" id="idvalueTxt">
                              </div>
                </div>        
          </div>
             
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?php echo $assArr['email'];?> <span class="error">*</span></label>
                        <input type="text" class="form-control" name="emailTxt" id="emailTxt">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 text-center">
                     <input type="button" value="<?php echo $assArr['save']?>" class="btn btn-primary">
                     <input type="button" value="<?php echo $assArr['cancel'];?>" data-dismiss="modal" class="btn btn-danger">
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Modal -->
<div id="ship_all" class="modal fade" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><strong>Confirmation</strong></h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-sm-12">
                  <div class="form-group">
                     <p id="error"></p>
                     <label id="confirmq">Do you want to ship all ?</label>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-sm-12 text-center">
                  <input type="button" value="OK" class="btn btn-primary shipAll">
                  <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

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
       if($joomla('input[name="cc"]').is(":visible") == true){
           if($joomla('input[name="cc"]').is(":checked") == false){
               alert("Please check any one payment option");
               return false;
           }
       }
       
   if($joomla('input[name="cc"]:checked').val() == "Paypal"){
       
       $joomla(".page_loader").show();
       var articlestrs=[];
          $joomla.each($joomla("input[name='articleStr[]']"), function(){
              articlestrs.push($joomla(this).val());
          });    
          var pricestrs=[];
          $joomla.each($joomla("input[name='priceStr[]']"), function(){
              pricestrs.push($joomla(this).val());
          });    
         $joomla('#shipcostStr').val($joomla('#amount').val());
         var user="<?php echo $user;?>";
         $joomla('input[name="data_test"]').val($joomla('input[name="invidkStr"]').val()+":"+$joomla('[name="wherhourecStr"]').val()+":"+$joomla('[name="consignidStr"]').val()+":"+$joomla('input[name="txtspecialinsStr"]').val()+":"+$joomla('input[name="shipservtStr"]').val()+":"+$joomla('input[name="paypalinvoice"]').val()+":"+articlestrs+":"+pricestrs+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="addSerStr"]').val()+":"+$joomla('input[name="addSerCostStr"]').val()+":"+$joomla('input[name="ratetypeStr"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+$joomla('input[name="insuranceCost"]').val()+":"+$joomla('input[name="extAddSer"]').val()+":"+$joomla("#lengthStr").val()+":"+$joomla("#widthStr").val()+":"+$joomla("#heightStr").val()+":"+$joomla("#weightStr").val()+":"+$joomla("#volStr").val()+":"+$joomla("#volmetStr").val()+":"+$joomla('#totalDecVal').val()+":"+$joomla('input[name=shipmentCost]').val()+":"+$joomla('#couponCodeStr').val()+":"+$joomla('input[name=couponDiscAmt]').val()+":"+user);
         $joomla('input[name="item_name"]').val(''+":"+user);
          var sdf=0;
          $joomla("input[name='priceStr[]']").each( function () {
              if($joomla(this).val()=="0.00"){  
                  sdf=1;
                  $joomla(".page_loader").hide();
                  return false;
              }    
          });    
          if(sdf == 0){
              
                   $joomla.ajax({
                       	url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                   	    dataType: 'text',  
                           data: {"inscustdataflag":1,"item_name":$joomla('input[name="data_test"]').val(),"item_number":$joomla('input[name="item_number"]').val(),"user":"<?php echo $user;?>"},                         
                           type: 'post',
                       	beforeSend: function() {
                             
                           },success: function(data){
                             $joomla('input[name="data_test"]').val("");
                             document.getElementById('userprofileFormFive').submit();
                           }
                     });
                     
              
           
          }
           
   }else{
       $joomla(".page_loader").show();
       document.getElementById('userprofileFormFive').submit();
   }
   
   }
     
     
</script>
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

 <!--paypal credit / debit card payment integration Start-->
<script src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/custom.js"></script>  
 
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
   .source_id{
   color : green;
   }
</style>