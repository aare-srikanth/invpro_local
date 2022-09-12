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
if($_GET['r']==1){
    $app = JFactory::getApplication();
    $app->enqueueMessage("Suessfully payment done", 'success');
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
    //$joomla(".input-sm").html('<option value="25">25</option><option value="50">50</option><option value="100">100</option>');


    $joomla("input[name=txtQty]").css("width","80px");
    
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
        $joomla('#idk').val($joomla(this).data('id'));
        $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
       $joomla(":checkbox").prop("checked", false);
       $joomla('#step1').hide(); 
    });
    $joomla('.keep').click(function(){
        $joomla('#idk2').val($joomla(this).data('id'));
        $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
       $joomla(":checkbox").prop("checked", false);
       $joomla('#step1').hide(); 
    });
    $joomla('.discardc').click(function(){
        $joomla('#idk3').val($joomla(this).data('id'));
        $joomla('input[name="qty"]').val($joomla(this).closest('tr').find('input[name="txtQty"]').attr('value'));
       $joomla(":checkbox").prop("checked", false);
       $joomla('#step1').hide(); 
    });
    
    $joomla('#ord_ship .btn-primary:first').click(function(){
       $joomla("#loading-image").hide();
       $joomla('#step1').hide(); 
       $joomla('#ord_ship #step2').show();
       $joomla('#ord_ship #step3').hide();
    });
    $joomla('#ord_ship .btn-back').click(function(){
       $joomla('#step1').hide(); 
       $joomla('#ord_ship #step2').show();
       $joomla('#ord_ship #step3').hide();
    });
    
    
    $joomla('#ChangeShippingAddressStr').click(function(){
       loadadditionalusersData();
       $joomla('#ChangeShippingAddress').toggle(); 
    });
   $joomla('.btn-danger,.btn-close1').on("click",function(){
       $joomla(":checkbox").prop("checked", false);
       $joomla('#step1').hide(); 
       $joomla('#step1 #j_table tbody').remove();    
       $joomla('#ord_ship #k_table tbody').remove(); 
       $joomla('#specialinstructionStr').val('');
       $joomla('#fnameTxt').val('');
       $joomla('#lnameTxt').val('');
       $joomla('#zipTxt').val('');
       $joomla('#addressTxt').val('');
       $joomla('#emailTxt').val('');
       $joomla('[name="consignidStr"]').val('');
    });

   $joomla('#step1').on("click",'.shipsubmit',function(){
        $joomla('#divShipCOstOne').html('');
       $joomla("#loading-image").hide();
       $joomla('#ord_ship #step2').show();
       $joomla('#ord_ship #step3').hide();
       

    });

    
    $joomla('#ord_ship #step2').on('click','.btn-primary:last',function(){
       if($joomla('#ChangeShippingAddressNew').html()==""){
            alert("Please select shipping address");
            return false;
           
       }
       else if($joomla('input[name=shipmentStr]:checked').val()=="undifined" || $joomla('input[name=shipmentStr]:checked').val()==null){
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
         $joomla('#step1').hide(); 
         $joomla('#ord_ship #step2').hide(); 
         $joomla('#ord_ship #step3').show();
       }       
        
    });
    
    
    $joomla('select[name="adduserlistStr"]').live('change',function(){
        $joomla('input[name=shipmentStr]').filter(':radio').prop('checked',false);		 
        var addt=$joomla('#ChangeShippingAddressNew').html()
        if($joomla(this).val()){
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
                    $joomla("input[name='consignidStr']").val(vk[0]);
    		    }
    		});
        }else{
            $joomla('#ChangeShippingAddressNew').html(addt); 
                    
        }
    });
    $joomla('input[name=cc]').click(function(){
       if($joomla(this).val()=="prepaid"){
            var feitem=[];
            $joomla("input[name='invFile[]']").each( function () {
                var tem=$joomla(this).attr('id');
                console.log("oop:"+tem);
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
                          $joomla("#loading-image5").show();
                          $joomla('#ord_ship #step3 .btn-primary').attr("disabled", true);
                        },success: function(data){
                            $joomla("#loading-image5").hide();
                            feitem.push(tem+"-"+data);
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
            console.log(pricestrs);
            console.log(articlestrs);
            var user="<?php echo $user;?>";
            $joomla('input[name="item_name"]').val($joomla('input[name="invidkStr"]').val()+":"+$joomla('[name="wherhourecStr"]').val()+":"+$joomla('[name="consignidStr"]').val()+":"+$joomla('input[name="txtspecialinsStr"]').val()+":"+$joomla('input[name="shipservtStr"]').val()+":"+$joomla('input[name="paypalinvoice"]').val()+":"+articlestrs+":"+pricestrs+":"+user);
            $joomla('input[name="item_number"]').val($joomla('input[name="qtyStr"]').val());
            $joomla('input[name="amount"]').val($joomla('input[name="amtStr"]').val());
            $joomla('#userprofileFormFive').attr('action','https://www.sandbox.paypal.com/cgi-bin/webscr');        
            //$joomla('#userprofileFormFive').attr('action','https://www.paypal.com/cgi-bin/webscr');        
            $joomla('#ord_ship #step3 .btn-primary').attr("disabled", false);
                        
       }else{
           $joomla('#userprofileFormFive').attr('action','');        
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

        var shipetype=$joomla('input[name=txtId]').closest('tr').find('td:eq(5)').html();
      
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&paymenttype="+$joomla(this).val() +"&wherhourec="+ whsc+"&invidk="+$joomla('#invidkStr').val() +"&qty="+$joomla('#qtyStr').val() +"&destination="+$joomla('#dtStr').val() +"&volres="+$joomla('#volresStr').val() +"&tyserv="+$joomla('#tyserviceStr').val() +"&munits="+$joomla('#mrunitsStr').val()+"&source="+$joomla('#srStr').val()+"&shiptype="+shipetype+"&user=<?php echo $user;?>&shippmentflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
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
    //$joomla('input[name="txtId"]').change(function() {
    //});
   $joomla('#tabs2').on('click','input[name="txtId"]',function(){
      var ischecked= $joomla(this).is(':checked');
      if(!ischecked)

       $joomla('#step1 #j_table tbody').remove();    
       $joomla('#ord_ship #k_table tbody').remove();    

       $joomla('#j_table').focus();
       $joomla('#step1').css('display','block');
      
       $joomla('#ord_ship #step3').hide(); 
       $joomla('#ord_ship #step2').hide(); 
       if($joomla('#step1 #j_table tr').length>0){
         var tds='<tr>';
         var tdns='<tr>';
         var whs=[];
         var idksn=[];
         var qtyl=[];
         var volres=[];
         var tyservice=[];
         var sr=[];
         var dt=[];
         var mrunits=[];
         var srhub=[];
         var whtr='';
         var j=0;
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
                    tdns+='<td><input type="text" id="'+j+'" name="articleStr[]" value="'+loop[i]+'"></td>';
                 }    
                 if(i==1){
                    //$joomla('#wherhourecStr').val(loop[i]); 
                    whs.push(loop[i]);
                    tds+="<td>"+loop[i]+"</td>";
                    whtr=loop[i];
                 }    
                 if(i==2){
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
                    //tdns+="<td>"+loop[i]+"</td>";
                    tdns+='<td><input type="text" name="priceStr[]"  id="'+j+i+'"  value="'+loop[i]+'"></td>';
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
                  var fileName = loop[i];
                  console.log("fileName:"+fileName);
                  var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                  if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                    var hrefs=loop[i];
                    hrefs=hrefs.split(' ').join('%20');
                    hrefs=hrefs.replace("#",":");
                    tdns+='<td> <div><div><label>Invoice Upload</label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtr+'></div><div><p>Upload extension type png,jpg,gif and pdf Below 2Mb file</p></div></div><div class="clearfix"></div><a class="sfile" href="'+hrefs+'" target="_blank">(View Invoice)</a></td>';
                  }else{
                    tdns+='<td> <div><div><label>Invoice Upload</label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtr+'></div><div><p>Upload extension type png,jpg,gif and pdf Below 2Mb file</p></div></div></td>';
                  }
                 }
                j++;

              // }
          }
          whs.join(", ");
          idksn.join(", ");
          volres.join(", ");
          tyservice.join(", ");
          sr.join(", ");
          dt.join(", ");
          mrunits.join(", ");
          srhub.join(", ");
          
          qtyl.join(", ");
          $joomla('#qtyStr').val(qtyl);
          
          $joomla('#wherhourecStr').val(whs);
          $joomla('#invidkStr').val(idksn);
          $joomla('#volresStr').val(volres);
          $joomla('#tyserviceStr').val(tyservice);
          $joomla('#mrunitsStr').val(mrunits);
          
          
           
          $joomla('#srhub').val(srhub);
          $joomla('#srStr').val(sr);
          $joomla('#dtStr').val(dt);
          tds+="</tr>";
          tdns+="</tr>";
          
         }    
        });

        $joomla('#step1 #j_table:first').append(tds);<!-- <td><p data-dismiss="modal" >Remove</p></td>-->
        $joomla('#ord_ship #k_table:last').append(tdns);
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
      var ischecked= $joomla(this).is(':checked');
      if(!ischecked){
          if($joomla('#step1 #j_table td').length==0){
               $joomla('#step1').hide();    
          }
      }
      //var scrollPos =  $joomla("#srol").offset().top;
      //$joomla(window).scrollTop(scrollPos);
      $joomla("#srol").focus();

    });     


   $joomla('#tabs2').on('click','input[name="txtId"]:checked',function(){
      var x=0;
      var xx='';
      var types=$joomla(this).closest('tr').find('td:eq(5)').html();
      var stype=$joomla(this).closest('tr').find('td:eq(6)').html();
      var dtype=$joomla(this).closest('tr').find('td:eq(7)').html();
      $joomla("input[name='txtId']:checked").each( function () {
        if($joomla(this).closest('tr').find('td:eq(5)').html()==types)
        {
            
        }else{
          x=1;
          xx="Shipment Type";
        }
        if($joomla(this).closest('tr').find('td:eq(6)').html()==stype)
        {
            
        }else{
          x=2;
          xx="Source Type";
        }
        if($joomla(this).closest('tr').find('td:eq(7)').html()==dtype)
        {
            
        }else{
          x=3;
          xx="Destnation Type";
        }
      });
      if(x!=0){
        $joomla('#myAlertModal').modal("show");
        $joomla('#error').html("Please check Shipping "+xx+" are not same");
        $joomla(":checkbox").prop("checked", false);
        $joomla('#step1').hide(); 
        $joomla('#step1 #j_table tbody').remove();    
        $joomla('#ord_ship #k_table tbody').remove();    
        return false;
      }
        console.log("checkbox")

       $joomla('#step1 #j_table tbody').remove();    
       $joomla('#ord_ship #k_table tbody').remove();    
       $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',true);
      
       $joomla('#j_table').focus();
       $joomla('#step1').css('display','block');
      
       $joomla('#ord_ship #step3').hide(); 
       $joomla('#ord_ship #step2').hide(); 
       if($joomla('#step1 #j_table tr').length>0){
         var tds='<tr>';
         var tdns='<tr>';
         var whs=[];
         var idksn=[];
         var qtyl=[];
         var volres=[];
         var tyservice=[];
         var sr=[];
         var dt=[];
         var mrunits=[];
         var srhub=[];
         var whtr='';
         var j=0;
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
                    tdns+='<td><input type="text"  id="'+j+'"  name="articleStr[]" value="'+loop[i]+'"></td>';
                  }    
                 if(i==1){
                    //$joomla('#wherhourecStr').val(loop[i]); 
                    whs.push(loop[i]);
                    tds+="<td>"+loop[i]+"</td>";
                    whtr=loop[i];
                 }    
                 if(i==2){
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
                    //tdns+="<td>"+loop[i]+"</td>";
                    tdns+='<td><input type="text" name="priceStr[]"  id="'+j+i+'"  value="'+loop[i]+'"></td>';
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
                  var fileName = loop[i];
                  console.log("fileName:"+fileName);
                  var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                  if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                    var hrefs=loop[i];
                    hrefs=hrefs.split(' ').join('%20');
                    hrefs=hrefs.replace("#",":");
                    tdns+='<td> <div><div><label>Invoice Upload</label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtr+'></div><div><p>Upload extension type png,jpg,gif and pdf Below 2Mb file</p></div></div><div class="clearfix"></div><a class="sfile" href="'+hrefs+'" target="_blank">(View Invoice)</a></td>';
                  }else{
                    tdns+='<td> <div><div><label>Invoice Upload</label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtr+'></div><div><p>Upload extension type png,jpg,gif and pdf Below 2Mb file</p></div></div></td>';
                  }
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
          
          qtyl.join(", ");
          $joomla('#qtyStr').val(qtyl);
          
          $joomla('#wherhourecStr').val(whs);
          $joomla('#invidkStr').val(idksn);
          $joomla('#volresStr').val(volres);
          $joomla('#tyserviceStr').val(tyservice);
          $joomla('#mrunitsStr').val(mrunits);
          
          
           
          $joomla('#srhub').val(srhub);
          $joomla('#srStr').val(sr);
          $joomla('#dtStr').val(dt);
          tds+="</tr>";
          tdns+="</tr>";
          
         }    
        });

        $joomla('#step1 #j_table:first').append(tds);<!-- <td><p data-dismiss="modal" >Remove</p></td>-->
        $joomla('#ord_ship #k_table:last').append(tdns);
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
     // var scrollPos =  $joomla("#srol").offset().top;
      //$joomla(window).scrollTop(scrollPos);
      $joomla("#srol").focus();


    });     
    $joomla('#tabs2').on('click','.ship',function(){
      var x=0;
      var xx='';
      var types=$joomla(this).closest('tr').find('td:eq(5)').html();
      var stype=$joomla(this).closest('tr').find('td:eq(6)').html();
      var dtype=$joomla(this).closest('tr').find('td:eq(7)').html();
      
      $joomla("input[name='txtId']:checked").each( function () {
        if($joomla(this).closest('tr').find('td:eq(5)').html()==types)
        {
            
        }else{
          x=1;
          xx="Shipment Type";
        }
        if($joomla(this).closest('tr').find('td:eq(6)').html()==stype)
        {
            
        }else{
          x=2;
          xx="Source Type";
        }
        if($joomla(this).closest('tr').find('td:eq(7)').html()==dtype)
        {
            
        }else{
          x=3;
          xx="Destnation Type";
        }
      });
      if(x!=0){
        $joomla('#myAlertModal').modal("show");
        $joomla('#error').html("Please check Shipping "+xx+" are not same");
        $joomla(":checkbox").prop("checked", false);
        $joomla('#step1').hide(); 
        $joomla('#step1 #j_table tbody').remove();    
        $joomla('#ord_ship #k_table tbody').remove();
        return false;
      }

       $joomla('#step1 #j_table tbody').remove();    
       $joomla('#ord_ship #k_table tbody').remove();    
       $joomla(this).closest('tr').find('input[name="txtId"]').prop('checked',true);
    
       $joomla('#j_table').focus();
       $joomla('#step1').css('display','block');
      
       $joomla('#ord_ship #step3').hide(); 
       $joomla('#ord_ship #step2').hide(); 
       if($joomla('#step1 #j_table tr').length>0){
         var tds='<tr>';
         var tdns='<tr>';
         var whs=[];
         var idksn=[];
         var qtyl=[];
         var volres=[];
         var tyservice=[];
         var sr=[];
         var dt=[];
         var mrunits=[];
         var srhub=[];
         var whtrs='';
         var j=0;
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
                    tdns+='<td><input type="text" name="articleStr[]"  id="'+j+'"  value="'+loop[i]+'"></td>';
                 }    
                 if(i==1){
                    //$joomla('#wherhourecStr').val(loop[i]); 
                    whs.push(loop[i]);
                    tds+="<td>"+loop[i]+"</td>";
                    whtrs=loop[i];
                 }    
                 if(i==2){
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
                    //tdns+="<td>"+loop[i]+"</td>";
                    tdns+='<td><input type="text" name="priceStr[]"  id="'+j+i+'"  value="'+loop[i]+'"></td>';
                 }
                 if(i==6){
                    $joomla('#costStr').val(loop[i]);
                    //tdns+="<td>"+loop[i]+"</td>";
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
                 }
                 //if(i==13){
                    //srhub.push(loop[i]);
                 //}
                 if(i==13){
                  var fileName = loop[i];
                  console.log("1fileName:"+fileName);
                  var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                  if(ext =="GIF" || ext=="gif" || ext =="jpeg" || ext=="JPEG"  || ext=="pdf"  || ext =="PNG" || ext=="png"  || ext=="JPG"  || ext=="jpg" ){
                    var hrefs=loop[i];
                    hrefs=hrefs.split(' ').join('%20');
                    hrefs=hrefs.replace("#",":");
                    tdns+='<td> <div><div><label>Invoice Upload</label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtrs+'></div><div><p>Upload extension type png,jpg,gif and pdf Below 2Mb file</p></div></div><div class="clearfix"></div><a class="sfile" href="'+hrefs+'" target="_blank">(View Invoice)</a></td>';
                  }else{
                    tdns+='<td> <div><div><label>Invoice Upload</label></div><div><input type="file" class="upoadfe" name="invFile[]" multiple id='+whtrs+'></div><div><p>Upload extension type png,jpg,gif and pdf Below 2Mb file</p></div></div></td>';
                  }
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
          
          qtyl.join(", ");
          $joomla('#qtyStr').val(qtyl);
          
          $joomla('#wherhourecStr').val(whs);
          $joomla('#invidkStr').val(idksn);
          $joomla('#volresStr').val(volres);
          $joomla('#tyserviceStr').val(tyservice);
          $joomla('#mrunitsStr').val(mrunits);
          
          
           
          $joomla('#srhub').val(srhub);
          $joomla('#srStr').val(sr);
          $joomla('#dtStr').val(dt);
          tds+="</tr>";
          tdns+="</tr>";
          
         }    
        });

        $joomla('#step1 #j_table:first').append(tds);<!-- <td><p data-dismiss="modal" >Remove</p></td>-->
        $joomla('#ord_ship #k_table:last').append(tdns);
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
      //console.log($joomla('#tds').html());
      //var scrollPos =  $joomla("#srol").offset().top;
      //$joomla(window).scrollTop(scrollPos);
      $joomla("#srol").focus();

    });


    //ship_img
    $joomla('#tabs2').on('click','.ship_img',function(){
        var res=$joomla(this).data('id');
        $joomla('#viewImage').html('');
        if(res.match(/(?:pdf)$/)=="pdf") {
          $joomla(this).attr('href',res);
          $joomla(this).attr('target','-blank');
        }else if (res.match(/(?:gif|jpg|png|jpeg)$/)) {
          $joomla('#viewImage').html("<img src='"+res+"' width='100%'>");
        }
        else{
          $joomla('#viewImage').html("There is no image upload for this order");
        }
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
                    $joomla('input[name=fluReturnShippingLabel]').after('<label id="errorTxt-error" class="error" for="errorTxt">Please Upload extension type png,jpg,jpeg,gif,pdf !</label>');
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
            required: true,
            minlength:16
          },
          txtccnumberStr: {
            required: true,
            minlength:3
          },
          txtNameonCardStr: {
            required: true
          },
          MonthDropDownListStr: {
            required: true,
            currentdates:true
          },
          "articleStr[]": {
            required: true/*,
            alphanumeric:true*/
          },
          "priceStr[]": {
            required: true
          },
          YearDropDownListStr: {
            required: true,
            currentdates:true
          }/*,
          "invFile[]": {
                     extension: "jpg|jpeg|png|gif|GIF|PNG|JPEG|JPG|pdf",
                     filesize: 20971520,  
          }*/
        },
        // Specify validation error messages
        messages: {
          cc: "Select Payment Method",
          ccStr: "Select Card",
          cardnumberStr: {required:"Please enter Card Number",minlength:"please enter a valid card number"},
          txtccnumberStr: {required:"Please enter CC Number",minlength:"Please enter minimum CC numbers"},
          txtNameonCardStr: "Please enter Name on the Card",
          MonthDropDownListStr: {required:"Please select Card expiry month",currentdates:"Card expiry year and month not validated"},
          "articleStr[]": {required:"Please enter item description"/*,alphanumeric:"The item description must contain alphabet characters only."*/},
          "priceStr[]": "Please enter Declared Value",
          YearDropDownListStr: {required:"Please select Card expiry Year",currentdates:"Card expiry year and month not validated"}/*,
          "invFile[]": {extension:"Please upload file which is listed",filesize:"File size is greater than 2MB"}*/
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
    		});
        */
        
        
            var sdf=0;
            $joomla("input[name='priceStr[]']").each( function () {
                /*var regex = new RegExp(/\./g)
                var count = $joomla(this).val().match(regex).length;
                if (count > 1)
                {
                    alert('Please enter valid Price Vlaue');
                    return false;
                    
                }*/
                if($joomla(this).val()=="0.00"){             
                    alert("Please enter Declared Price Value($)");
                    sdf=1;
                    return false;
                }    
            });    
            $joomla("input[name='invFile[]']").each( function () {

              if( $joomla(this).closest("tr").find(".sfile").length=="0"){
                    if($joomla(this).val()==""){             
                        alert("please upload Invoice");
                        sdf=1;
                        return false;
                    }    
              }
            })
            if(sdf==0){
                var articlestrs=[];
                $joomla.each($joomla("input[name='articleStr[]']"), function(){
                    articlestrs.push($joomla(this).val());
                });    
                var pricestrs=[];
                $joomla.each($joomla("input[name='priceStr[]']"), function(){
                    pricestrs.push($joomla(this).val());
                });    
                
                var user="<?php echo $user;?>";
                $joomla('input[name="item_name"]').val($joomla('input[name="invidkStr"]').val()+":"+$joomla('[name="wherhourecStr"]').val()+":"+$joomla('[name="consignidStr"]').val()+":"+$joomla('input[name="txtspecialinsStr"]').val()+":"+$joomla('input[name="shipservtStr"]').val()+":"+$joomla('input[name="paypalinvoice"]').val()+":"+articlestrs+":"+pricestrs+":"+user);
                $joomla('input[name="item_number"]').val($joomla('input[name="qtyStr"]').val());
                $joomla('input[name="amount"]').val($joomla('input[name="amtStr"]').val());
                console.log("Submitted!");
                $joomla('#loading-image5').show();
    	        $joomla('#loading-image5').focus();
    	        form.submit();
            }else{
                return false;
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
 

	$joomla('#country2Txt').on('change',function(){
	    $joomla('input[name="city2Txt"]').val('');
		$joomla('#state2Txt').val(0);
		$joomla('#city2Txt').html('');
		var countryID = $joomla(this).val();
		if(countryID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&countryid="+$joomla("#country2Txt").val() +"&stateflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "country": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
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
		$joomla('input[name="city2Txt"]').val('');
		$joomla('#city2Txt').html('');
		var stateID = $joomla(this).val();
		if(stateID){
			$joomla.ajax({
				url: "<?php echo JURI::base(); ?>index.php?option=com_register&task=register.get_ajax_data&stateid="+$joomla("#state2Txt").val() +"&cityflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
				data: { "state": $joomla("#countryTxt").val() },
				dataType:"html",
				type: "get",
				success: function(data){
					$joomla('#city2Txt').append(data);
				}
			}); 
		}else{
			$joomla('#city2Txt').html('<option value="">Select City</option>'); 
		}
	});  
	$joomla("input[name='city2Txt']").blur(function(){
        
        var val = $joomla(this).val()
        var xyz = $joomla('#city2Txt option').filter(function() {
            return this.value == val;
        }).data('xyz');
        if(xyz){
            $joomla(this).val(val);
        }
        $joomla("input[name='city2Txtdiv']").val(xyz);
     });

	
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
            selectBox: true
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
          emailTxt: {
            required: true,
            email:true
          }
    },
        // Specify validation error messages
        messages: {
          fnameTxt: {required:"Please enter first name",alphanumeric:"The first name must contain alphabet characters only."},
          lnameTxt: {required:"Please enter last name",alphanumeric:"The last name must contain alphabet characters only."},
          country2Txt: {selectBox:"Please select country"},
          state2Txt: {selectBox:"Please select state"},
          /*city2Txt: {selectBox:"Please select city"},
          zipTxt: "Please enter zip code",*/
          addressTxt: "Please enter address",
          emailTxt: {required:"Please enter email",email:"Please enter email format"}
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
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
                      $joomla.ajax({
                	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&getadduserpayflag=1&user=<?php echo $user;?>&typeuser="+$joomla("input[name='typeuserTxt']").val()+"&id="+$joomla("input[name='idTxt']").val()+"&fname="+$joomla("input[name='fnameTxt']").val()+"&lname="+$joomla("input[name='lnameTxt']").val()+"&country="+$joomla('#country2Txt').val()+"&state="+$joomla('#state2Txt').val()+"&city="+$joomla('input[name="city2Txtdiv"]').val()+"&zip="+$joomla("input[name='zipTxt']").val()+"&address="+$joomla('#addressTxt').val()+"&address2="+$joomla('#address2Txt').val()+"&email="+$joomla("input[name='emailTxt']").val()+"&idtypetxt="+$joomla("#idtype2Txt").val()+"&identitytypetxt="+$joomla("input[name='identity2Txt']").val()+"&cpftxt="+$joomla("input[name='cpfTxt']").val()+"&cnpjtxt="+$joomla("input[name='cnpjTxt']").val()+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                			data: { "usertype":1},
                			dataType:"html",
                			type: "get",
                			beforeSend: function() {
                			    $joomla('#exampleModal .modal-body').html('<div id="loading-image4" ><img src="<?php echo JURI::base(); ?>/components/com_userprofile/images/loader.gif"></div>');
                         	},    
                			success: function(data){
                			    $joomla('#exampleModal .modal-body').html(body);
                			    if(data=="Additional address successfully inserted"){
                			        $joomla('#exampleModal').modal('toggle');
                			        loadadditionalusersData();
                			    }
                			}
                		});
				    }
				}
			});
        }	
     });     
    $joomla("input[name='cardnumberStr']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $joomla("input[name='txtccnumberStr']").keyup(function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
   });

    $joomla(".ishipment").click(function(){
        if($joomla(this).val()==2){
            $joomla(".ishpments").show();
            $joomla(".ishpments2").hide();
            $joomla('.btn-danger').click();
        }else
        {
            $joomla(".ishpments2").show();
            $joomla(".ishpments").hide();
        }
    })
    
  
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
    <div class="main_heading">My Packages</div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12 tab_view">
          <ul class="nav nav-tabs">
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocessalerts">My Pre-Alerts</a> </li>
            <li> <a class="active" href="index.php?option=com_userprofile&view=user&layout=orderprocess">Pending Shipments</a> </li>
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=cod">COD</a> </li>
          <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=shiphistory">Shipments History</a> </li>
            </ul>
        </div>
      </div>
      <div id="tabs2">
        <div class="row">
          <div class="col-sm-12">
            <h3 class="mx-1"><strong>Inventory items</strong></h3>
          </div>
        </div>
        <div class="rdo_cust">
                <div class="rdo_rd1">
                  <input type="radio" name="shipment"  class="ishipment" value="1" checked>
                  <label>Pending Shipments</label>
                </div>
                <div class="rdo_rd1">
                  <input type="radio" name="shipment"  class="ishipment" value="2">
                  <label>Hold Shipments</label>
                </div>
              </div>
        <div class="clearfix"></div>
        <div class="row ishpments" style="display:none">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered theme_table" id="u_table">
                <thead>
                  <tr>
                    <th class="action_btns">Actions#</th>
                    <th>Werehouse Receipt#</th>
                    <th>Item Description</th>
                    <th>Merchant Name</th>
                    <th>Quantity</th>
                    <th>Shipment Type</th>
                    <th>Source Hub</th>
                    <th>Destination</th>
                    <th>View Image</th>
                  </tr>
                </thead>
                <tbody>
    <?php
    $ordersPendingView= UserprofileHelpersUserprofile::getOrdersHoldList($user);
    $idf=1;
    foreach($ordersPendingView as $rg){

      if($rg->ItemQuantity>0){
      $volres=$rg->height*$rg->width*$rg->length*UserprofileHelpersUserprofile::getShippmentDetailsValues($rg->MeasureUnits,$rg->shipment_type,$rg->ServiceType,$rg->source,$rg->destination);
      if($rg->ItemImage==""){
          $sim=1; 
          $mgtd='<td>No Image</td>';
      }else{
          $sim=str_replace(":","#",$rg->ItemImage);
          if(end(explode('.', $rg->ItemImage))=="pdf"){
              $mgtd='<td class="action_btns"><a href="'.str_replace("http:","https:",$rg->ItemImage).'" target="-blank" ><i class="fa fa-eye"></i></a></td>';
          }else{
              $mgtd='<td class="action_btns"><a class="ship_img" data-toggle="modal" data-backdrop="static" data-keyboard="false" href="#" data-id="'.str_replace("http:","https:",$rg->ItemImage).'" data-target="#view_image" ><i class="fa fa-eye"></i></a></td>';
          }
      }
      echo '<tr>
       		<td class="action_btns"></td>
      		<td><input type="hidden" name="txtbiladdress" value="'.UserprofileHelpersUserprofile::getBindShipingAddress($user,$rg->BillFormNo).'">'.$rg->BillFormNo.'</td>
      		<td>'.$rg->ItemName.'</td>
      		<td>'.$rg->SupplierId.'</td>
      		<td><input type="hidden" class="form-control" readonly name="txtQty" value="'.$rg->Quantity.'"><input type="hidden" name="txtItemQty" value="'.$rg->ItemQuantity.'">'.$rg->ItemQuantity.'</td>
      	    <td>'.$rg->shipment_type.'</td>
      		<td><div id="sourcehub" style="display:none">'.$rg->source_hub.'</div>'.$rg->source_hub.'</td>
      		<td>'.$rg->destination_name.'</td>
    		<td>'.$rg->destination_name.'</td>
    		</tr>';
      }
        $idf++;
    }
    ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="row ishpments2">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered theme_table" id="j_table" data-page-length='100'>
                <thead>
                  <tr>
                    <th class="action_btns">Actions#</th>
                    <th>Werehouse Receipt#</th>
                    <th>Item Description</th>
                    <th>Merchant Name</th>
                    <th>Quantity</th>
                    <th>Shipment Type</th>
                    <th>Source Hub</th>
                    <th>Destination</th>
                    <th>View Image</th>
                  </tr>
                </thead>
                <tbody>
    <?php
    $ordersPendingView= UserprofileHelpersUserprofile::getOrdersPendingList($user);
    $idf=1;
    foreach($ordersPendingView as $rg){

      if($rg->ItemQuantity>0){
      $volres=$rg->height*$rg->width*$rg->length*UserprofileHelpersUserprofile::getShippmentDetailsValues($rg->MeasureUnits,$rg->shipment_type,$rg->ServiceType,$rg->source,$rg->destination);
      if($rg->ItemImage==""){
          $sim=1; 
          $mgtd='<td>No Image</td>';
      }else{
          $sim=str_replace(":","#",$rg->ItemImage);
          if(end(explode('.', $rg->ItemImage))=="pdf"){
              $mgtd='<td class="action_btns"><a href="'.str_replace("http:","https:",$rg->ItemImage).'" target="-blank" ><i class="fa fa-eye"></i></a></td>';
          }else{
              $mgtd='<td class="action_btns"><a class="ship_img" data-toggle="modal" data-backdrop="static" data-keyboard="false" href="#" data-id="'.str_replace("http:","https:",$rg->ItemImage).'" data-target="#view_image" ><i class="fa fa-eye"></i></a></td>';
          }
      }
      echo '<tr>
      		<td class="action_btns">
                <input type="checkbox" name="txtId" value="'.$rg->ItemName.':'.$rg->BillFormNo.':'.$rg->ItemQuantity.':'.$rg->TrackingId.':'.$rg->ItemId.':'.$rg->ItemPrice.':'.$rg->cost.':'.$idf.':'.$volres.':'.$rg->ServiceType.':'.$rg->source.':'.$rg->destination.':'.$rg->MeasureUnits.':'.$sim.'">
      			<input type="button" name="ship" class="ship" data-id="'.$rg->ItemName.':'.$rg->BillFormNo.':'.$rg->ItemQuantity.':'.$rg->TrackingId.':'.$rg->ItemId.':'.$rg->ItemPrice.':'.$rg->cost.':'.$idf.':'.$volres.':'.$rg->ServiceType.':'.$rg->source.':'.$rg->destination.':'.$rg->MeasureUnits.':'.$sim.'" data-target="#ord_ship" title="SHIP">
      			<input type="button" name="Return" class="return" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-id="'.$rg->BillFormNo.':'.$rg->ItemId.':'.$rg->ItemQuantity.'" data-target="#ord_return"" title="RETURN">
      			<input type="button" name="Keep" class="keep" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-id="'.$rg->BillFormNo.':'.$rg->ItemId.':'.$rg->ItemQuantity.'" data-target="#ord_keep" " title="HOLD">
      		</td>
      		<td><input type="hidden" name="txtbiladdress" value="'.UserprofileHelpersUserprofile::getBindShipingAddress($user,$rg->BillFormNo).'">'.$rg->BillFormNo.'</td>
      		<td>'.$rg->ItemName.'</td>
      		<td>'.$rg->SupplierId.'</td>
      		<td><input type="hidden" class="form-control" readonly name="txtQty" value="'.$rg->Quantity.'"><input type="hidden" name="txtItemQty" value="'.$rg->ItemQuantity.'">'.$rg->ItemQuantity.'</td>
      		<td>'.$rg->shipment_type.'</td>
      		<td><div id="sourcehub" style="display:none">'.$rg->source_hub.'</div>'.$rg->source_hub.'</td>
      		<td>'.$rg->destination_name.'</td>'.$mgtd.'</tr>';
      }
        $idf++;
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
                      <th>Item Description</th>
                      <th>Warehouse Receipt</th>
                      <th>Quantity</th>
                      <th>Tracking Id</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="button" value="Next" class="btn btn-primary shipsubmit" data-target="#ord_ship" data-toggle="modal" data-backdrop="static" data-keyboard="false">
              <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
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
          <h4 class="modal-title"><strong>Return</strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Back Company / Dealer <span class="error">*</span></label>
                <input type="text" name="txtBackCompany" class="form-control">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Return Address <span class="error">*</span></label>
                <input type="text" name="txtReturnAddress" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Return Shipping Carrier <span class="error">*</span></label>
                <input type="text" name="txtReturnCarrier" class="form-control">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Reason for Return<span class="error">*</span></label>
                <input type="text" name="txtReturnReason" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Original Order Number <span class="error">*</span></label>
                <input type="text" name="txtOriginalOrderNumber" class="form-control">
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Return Merchant Number <span class="error">*</span></label>
                <input type="text" name="txtMerchantNumber" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Return Shipping Label </label>
                <input type="file" name="fluReturnShippingLabel" class="form-control">
                <label>Upload extension type png,jpg,gif and pdf Below 2Mb file</label>
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Special Instructions <span class="error">*</span></label>
                <input type="text" name="txtSpecialInstructions" class="form-control">
              </div>
            </div>
          </div>
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
          <h4 class="modal-title"><strong>Hold Information</strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Reason for Hold/Keep<span class="error">*</span></label>
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
            <div id="viewImage"></div>
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
         <h4 class="modal-title"><strong>Shipping Information</strong></h4>       
      </div>
      <div class="modal-body">
        <div id="step2">
         <div class="row">
            <div class="col-md-12">
              <div class="rcvship-blk">
                <h4>Tell Us Where would you like to send your packages</h4>
                <label class="txt-hd">Receiver Shipping Adderss:</label>
                <div class="shp-addnew1">
                <div id="ChangeShippingAddressNew"></div>
                </div>
                <div id="ChangeShippingAddressStr" class="chg-address">
                  <label>Change Address</label>
                </div>
                <div class="row">
                 <div class="col-md-12">
                  <div class="form-group">
                    <input type="button" id="addusers" class="btn btn-primary" value="Additional Address" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#exampleModal">
                  </div>
                </div>
                </div>
                <div id="ChangeShippingAddress" style="display:none" class="addtion-user">
                  
                  
                    <div id="additionalusersData"><select class="form-control" name="adduserlistStr"></select></div>
                  
                  <div id="loading-image2" style="display:none"><img src='/components/com_userprofile/images/loader.gif'></div>
                </div>
              </div>
              <div class="rdo_cust">
                <div class="rdo_rd1">
                  <input type="radio" name="shipmentStr" value="standard">
                  <label>Standard</label>
                </div>
                <div class="rdo_rd1">
                  <input type="radio" name="shipmentStr" value="Express">
                  <label>Express</label>
                </div>
              </div>
              <div id="divShipCOstOne" class="rst_text"></div>
              <div id="divShipCOstTwo" class="rst_text"></div>
              
              
              <div class="spcl-ins">
                <label>Special instructions for shipping:</label>
                <textarea name="specialinstructionStr" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="button" value="Next" class="btn btn-primary">
              <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
            </div>
          </div>
        </div>
        <div id="step3" style="display:none">
          <form name="userprofileFormFive" id="userprofileFormFive" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" id="wherhourecStr" name="wherhourecStr">
            <input type="hidden" id="volresStr" name="volresStr">
            <input type="hidden" id="tyserviceStr" name="tyserviceStr">
            <input type="hidden" id="srStr" name="srStr">
            <input type="hidden" id="dtStr" name="dtStr">
            <input type="hidden" id="mrunitsStr" name="mrunitsStr">
            
            <input type="hidden" id="invidkStr" name="invidkStr">
            <input type="hidden" id="qtyStr" name="qtyStr">
            <input type="hidden" id="trackingidStr" name="trackingidStr">
            <input type="hidden" id="ItemPriceStr" name="ItemPriceStr">
            <input type="hidden" id="costStr" name="costStr">
            <input type="hidden" id="txtspecialinsStr" name="txtspecialinsStr">
            <input type="hidden" name="shipcostStr" />
            <input type="hidden" name="shipservtStr" />
            <input type="hidden" name="consignidStr" />

            <?php //$UserViews=UserprofileHelpersUserprofile::getUserpersonalDetails($user);?>

            <!--<input type="hidden" name="fnameStr" value="<?php echo $UserViews->AdditionalFirstName;?>" />
            <input type="hidden" name="lnameStr" value="<?php echo $UserViews->AdditionalLname;?>" />
            <input type="hidden" name="addressStr" value="<?php echo $UserViews->AddressAccounts.' '.$UserViews->addr_2_name;?>" />
            <input type="hidden" name="cityStr" value="<?php echo $UserViews->desc_city;?>"/>
            <input type="hidden" name="stateStr" value="<?php echo $UserViews->State;?>"/>
            <input type="hidden" name="zipStr" value="<?php echo $UserViews->PostalCode;?>"/>
            <input type="hidden" name="countryStr" value="<?php echo $UserViews->Country;?>"/>
            <input type="hidden" name="emailStr" value="<?php echo $UserViews->PrimaryEmail;?>"/>Jorge.farias@cifexpressusa.com-->
             
             <input type='hidden' name='business' value='sb-qa1ib1508141@personal.example.com'> 
             <input type='hidden'   name='item_name'>
                <input type='hidden' name='item_number'> 
                <input type='hidden' name='amount'>
                <input type='hidden' name='paypalinvoice' id="paypalinvoice" >
                
                
                <input type='hidden' name='no_shipping' value='1'> 
                <input type='hidden' name='currency_code' value='USD'>
                <input type='hidden' name='notify_url' value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=notify-order'>
            <input type='hidden' name='cancel_return'
                value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=orderprocess'>
            <input type='hidden' name='return'
                value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user'>
            <input type="hidden" name="cmd" value="_xclick">  
              

            <div class="row">
              <div class="col-md-12">
                <div class="finish-shipping">
                  <label>Finish shipping : </label><div id="shipmethodStrValuetwo" style="float:right"></div>
                  <p> My Summary Shipping</p>
                  <p> Included (s):</p>
                  <div class="table-responsive">
                  <table class="table table-bordered theme_table" id="k_table">
                    <thead>
                      <tr>
                        <th>Item Description</th>
                        <th>Quantity</th>
                        <th>Declared Value($)</th>
                        <th>Invoice</th>
                      </tr>
                    </thead>
                  </table>
                  </div>
                  <table width="100%" class="table table-bordered theme_table shipping-costtbl">
                    <tr>
                      <td colspan="2"><label>SHIPPING OPTIONS</label></td>
                    </tr>
                    <tr>
                      <td><label>
                        SHIPPING METHOD -
                        <div id="shipmethodStrtext" class="shp-mtd"></div>
                        </label></td>
                      <td class="txt-right"><div id="shipmethodStrValue"></div></td>
                    </tr>
                    <tr>
                      <td><label>ADDITIONAL SERVICES</label></td>
                      <td class="txt-right"><div id="addserStr"></div></td>
                    </tr>
                    <tr>
                      <td><label>DISCOUNT</label></td>
                      <td class="txt-right"><div id="discountStr"></div></td>
                    </tr>
                    <tr>
                      <td><label>SPECIAL INSTRUCTIONS</label>
                      </td>
                      <td class="txt-right"><div id="specialinstructionDiv"></div></td>
                    </tr>
                    <tr>
                      <td><label>CUSTOM CHARGES</label></td>
                      <td class="txt-right">0.00</td>
                    </tr>
                    <tr>
                      <td><label>CIF EXRESS COSTS</label></td>
                      <td class="txt-right">0.00</td>
                    </tr>
                    <tr class="total_cst">
                      <td><label>Total Buy For Today</label></td>
                      <td class="txt-right"><div id="shipmethodtotalStr"></div></td>
                    </tr>
        
                  </table>
                <div id="loading-image5" style="display:none"><img src="<?php echo JURI::base(); ?>/components/com_userprofile/images/loader.gif"></div>

                  <div class="clearfix"></div>
                   
                  <div class="rdo_cust">
                    <div class="rdo_rd1">
                      <input type="radio" name="cc" value="prepaid">
                      <label>Prepaid</label>
                      <input type="radio" name="cc" id="cc" value="cod">
                      <label>Cod </label>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                
                  <div class="payment-method" id="dvPaymentMethod" style="display: none;">
                    <h4>Payment Method</h4>
                    <div class="rdo_rd1">
                      <input type="radio" name="ccStr" id="ccStr" value="1">
                      <label>Paypal</label>
                    </div>
                    <div id="dvPaymentInformation" class="paymentopt form-horizontal" style="display: none;">
                      <div class="form-group">
                        <label class="col-sm-3 col-xs-12">Card Number</label>
                        <div class="col-md-4 col-xs-12">
                          <input type="text" maxlength="16" name="cardnumberStr" class="form-control">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-sm-3 col-xs-12">Card Type</label>
                        <div class="col-md-4 col-xs-12">
                          <select name="cardtypeStr" class="form-control">
						<option value="visa" selected="selected">Visa</option>
						<option value="MasterCard">Master Card</option>
						<option value="AmericanExpress">American Express</option>
						</select>
                        </div>
                      </div>
                
                      
						
                      <div class="form-group">
                        <label class="col-sm-3 col-xs-12">Security Code</label>
                        <div class="col-md-4 col-xs-12">
                          <input type="password" name="txtccnumberStr" maxlength="4" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 col-xs-12">Name on Card</label>
                        <div class="col-md-4 col-xs-12">
                          <input type="text" name="txtNameonCardStr"  class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-3 col-xs-12">Expiry</label>
                        <div class="col-md-4 col-xs-6">
                          <select name="MonthDropDownListStr" class="form-control">
                            <option value="">Please select</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
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
                        <div class="col-md-4 col-xs-6 pad-lft">
                          <select name="YearDropDownListStr" class="form-control">
                            <option value="">Year</option>
                            <?php
                                for($i=0;$i<10;$i++){
                                ?>
                            <option value="<?php echo date('Y')+$i;?>"><?php echo date('Y')+$i;?></option>
                            <?php
                                }?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-3 col-xs-12">Amount $</label>
                        <div class="col-md-4 col-xs-12">
                          <input type="text" name="amtStr"  class="form-control" readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <input type="hidden" name="task" value="user.payshippment">
                      <input type="hidden" name="id" />
                      <input type="hidden" name="user" value="<?php echo $user;?>" />
                      <input type="button" value="Back" class="btn btn-back">
                      <input type="submit" value="Ship" class="btn btn-primary">
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
                <label>Additional Address <span class="error">*</span></label>
                <textarea type="text" class="form-control" name="addressTxt" id="addressTxt"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Additional Address2 </label>
                <textarea type="text" class="form-control" name="address2Txt" id="address2Txt"></textarea>
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
                <label>City</label>
                <input type="text" class="form-control"  name="city2Txt" list="city2Txt" />
                    	<datalist id="city2Txt"></datalist>
                    	<input type="hidden" name="city2Txtdiv" id="city2Txtdiv">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Zip Code </label>
                <input type="text" class="form-control" name="zipTxt" id="zipTxt">
              </div>
            </div>
          </div>
          
           <!--<div class="row">
            <div class="col-md-12 col-sm-12 form-group">
              <div class="radiacp">
                <input type="radio" name="cityotherTxt" id="cityotherTxt" value="Capital" checked="">
                &nbsp; Capital &nbsp;&nbsp;  <input type="radio" name="cityotherTxt" id="cityotherTxt" value="Others"> &nbsp; Other Cities 
                </div>
            </div>
          </div>-->
         
          <div class="row">
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