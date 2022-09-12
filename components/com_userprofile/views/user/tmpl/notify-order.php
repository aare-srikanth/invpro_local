<?php

// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
// Set this to 0 once you go live or don't require logging.
define("DEBUG", 1);
// Set to 0 once you're ready to go live
define("USE_SANDBOX", 1);
define("LOG_FILE", "ipn.log");

require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
$domainDetails = ModProjectrequestformHelper::getDomainDetails();

foreach($domainDetails[0]->PaymentGateways as $PaymentGateways){
       if($PaymentGateways->PaymentGatewayName == "Paypal"){
            $PaypalEmail = $PaymentGateways->Email;
            $AccountType = strtolower($PaymentGateways->AccountType);
            $ApiUrl = strtolower($PaymentGateways->ApiUrl);
       }
   }
   
   


// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}
// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data

 $paypal_url = $ApiUrl;

$ch = curl_init($paypal_url);
if ($ch == FALSE) {
	return FALSE;
}
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
if(DEBUG == true) {
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}
// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.
//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);
$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
	{
	if(DEBUG == true) {	
		error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
	}
	curl_close($ch);
	exit;
} else {
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
		}
		curl_close($ch);
}
// Inspect IPN validation result and act accordingly
// Split response headers and payload, a better way for strcmp
$tokens = explode("\r\n\r\n", trim($res));
$res = trim(end($tokens));


if (strcmp ($res, "VERIFIED") == 0) {
	// assign posted variables to local variables
	$companyId = $_POST['companyId'];
	$item_name_str = $_POST['item_name'];
	$item_name_strArr = explode(":",$item_name_str);
	$user = end($item_name_strArr);
    $item_name = Controlbox::getdatapaypal($user);
    //$item_name = "1851:WR-2017::dfsfsf:CUS-SRV1001::ddsfds:6.00:0.00:::FORMULA:130:0.00::ICID-1055";
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	
	
	// check whether the payment_status is Completed
	$isPaymentCompleted = false;
	if($payment_status == "Completed") {
		$isPaymentCompleted = true;
	}
	// check that txn_id has not been previously processed
	$isUniqueTxnId = false; 
	if(empty($txn_id)) {
        $isUniqueTxnId = true;
	}	
	// check that receiver_email is your PayPal email
	// check that payment_amount/payment_currency are correct
	if($isPaymentCompleted) {
	    
	    $param_value_array = array($item_number, $item_name, $payment_status, $payment_amount, $payment_currency, $txn_id);
        //$joomla('input[name="txtShippingMethod"]').val()+":"+ids+":"+spi+":"+$joomla('textarea[name="txtSpecialIn"]').val()+":"+user
        
        $itemfile = 'item.txt';
        $content = '';
        $content .= $item_name."\n";
        $content .= $item_number."\n";
        $content .= $payment_status."\n";
        $content .= $payment_amount."\n";
        $content .= $payment_currency."\n";
        $content .= $txn_id."\n";
        
       
        
        $exp=explode(":",$item_name);
        $amtStr=$payment_amount;
        $cardnumberStr='';
        $txtccnumberStr=''; 
        $MonthDropDownListStr='';  
        $txtNameonCardStr='';
        $YearDropDownListStr='';
        $invidkStr=$exp[0];
        $qtyStr=$item_number;
        $wherhourecStr=$exp[1]; 
        $extAddSer=$exp[14];
        $CustId=$exp[25];
        $insuranceCost=$exp[13];
        $CompanyId=$exp[12];
        $rateType=$exp[11];
        $addSerCost=$exp[10];
        $addSerStr=$exp[9];
        $Conveniencefees=$exp[8];
        
         
        
        //279,278:WR-1284,WR-1284:::CUS-SRV-1171::Art,Art:1.00,1.00:BB008
        $elfe=explode(",",$exp[0]);
        $mgsr='';
        $invf='';
        $filenameStr='';
        $nameStr = "";
        $extStr = "";
        $i=0;
        
        $invidkStrArr = explode(",",$exp[0]);
        $paypalinvoiceArr = explode(",",$exp[5]);
        
        $invoiceStr='';
        
        foreach($invidkStrArr as $idk){
            $invoiceStrLoop = '';
           foreach($paypalinvoiceArr as $invc){
                $idkval = substr($invc, 0, strlen($idk));
                if($idk == $idkval){
                    $invoiceStrLoop = $invc;
                 
                }
                
            }
            
                if($invoiceStrLoop != ""){
                    $invoiceStr .= $invoiceStrLoop.",";
                }else{
                    $invoiceStr .= "0,";
                }
        
        }
        
        //$eew=explode(",",$exp[5]);
        $eew=explode(",",rtrim($invoiceStr,","));
        
        foreach($eew as $res){
            
            $mgsr = substr($res,strlen($elfe[$i])+1);
            $imageNameArr=explode("/",$mgsr);
           
            if($res){
                $mgsr = substr($res,strlen($elfe[$i])+1);
                    $photodest = JPATH_SITE. "/media/com_userprofile/".$mgsr;
                    $image1 = file_get_contents($photodest);
                    $imageByteStream = base64_encode($image1);
                    $invf .= $imageByteStream.',';
                    $filenameStr.= $imageNameArr[1].',';
            }else{
                $invf .= '0,';
                $filenameStr.= '0,';
            } 
            $i++;
        }
        
        //  $filenamesList=explode(",",$filenameStr);
        
        
        // for($i=0;$i < count($filenamesList);$i++){
        //   if($filenamesList[$i]!=''){
        //     $nameExtAry=explode(".",$filenamesList[$i]);
        //     $nameStr.=$nameExtAry[0].",";
        //     $extStr.=".".$nameExtAry[1].",";
        //   }
        // }
        
        
        $file=$invf;
        $specialinstructionStr=$exp[3];
        $cc='PPD'; 
        $shipservtStr=$exp[4];
        $consignidStr=$exp[2];
        $tid=$txn_id;
        $articleStr=$exp[6];
        $priceStr=$exp[7];
        $pg = "Paypal";
        
        $lengthStr = $exp[15];
        $widthStr = $exp[16];
        $heightStr = $exp[17];
        $grosswtStr = $exp[18];
        $volumeStr = $exp[19];
        $volumetwtStr = $exp[20];
        $totalDecVal = $exp[21];
        $shipmentCost = $exp[22];
        $couponCodeStr  = $exp[23];
        $couponDiscAmt  = $exp[24];
        
        $v=Controlbox::submitpayment($amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$invidkStr,$qtyStr,$wherhourecStr, $CustId,$specialinstructionStr, $cc, $pg, $shipservtStr,$consignidStr,$file,$filenameStr,$articleStr,$priceStr,$tid,$Inhouse,$InhouseIdk,$rateType,$Conveniencefees,$addSerStr,$addSerCost,$CompanyId,$insuranceCost,$extAddSer,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$shipmentCost,$totalDecVal,$couponCodeStr,$couponDiscAmt);
        file_put_contents($itemfile, $v);
        $res = explode(":",$v);
        if($res[2] == 1){
            Controlbox::deletecustdata($user);
        }
        unset($_POST);
 
 // Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


mail('madanchunchu@gmail.com',$payment_amount.'--'.$payment_status,$item_name.''.$txn_id.'---'.$item_number.'--'.$v,$headers);
	    
	    error_log(date('[Y-m-d H:i e] '). " IPN: $req ". PHP_EOL, 3, LOG_FILE);
	} 
	// process payment and mark item as paid.
	
	
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
	}
	
} else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	// Add business logic here which deals with invalid IPN messages
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
	}
}
?>