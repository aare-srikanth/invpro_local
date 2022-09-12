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
       if(strtolower($PaymentGateways->PaymentGatewayName) == strtolower("Paypal")){
            $PaypalEmail = $PaymentGateways->Email;
            $AccountType = strtolower($PaymentGateways->AccountType);
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
if($AccountType == "sandbox") {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}
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
	$item_name = $_POST['item_name'];
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
        //input[name="bill_form_nostr"]').val()+":"+$joomla('[name="Id_Servstr"]').val()+":"+$joomla('[name="TotalAmountPaidstr"]').val()+":"+$joomla('input[name="InHouseNostr"]').val()+":"+$joomla('input[name="TotalCoststr"]').val()+":"
        //+$joomla('input[name="AdditionalCoststr"]').val()+":"+$joomla('input[name="TotalFinalCoststr"]').val()+":"+$joomla('input[name="Discountstr"]').val()+":"+user);

        $file_print = 'item.txt';
        $exp=explode(":",$item_name);
        $amtStr=$payment_amount;
        $cardnumberStr='';
        $txtccnumberStr=''; 
        $MonthDropDownListStr='';  
        $txtNameonCardStr='';
        $YearDropDownListStr='';
        $invidkStr='';
        $qtyStr='';
        $wherhourecStr=$exp[0]; 
        $CustId=$exp[11];
        $CompanyId=$exp[10];
        $Conveniencefees=$exp[9];
        $InhouseIdk=$exp[8];
        $Inhouse=$exp[3];
        $file='';
        $specialinstructionStr='';
        $cc='PayForCOD'; 
        $shipservtStr=$exp[1];
        $consignidStr='';
        $tid=$txn_id;
        //WR-1118-C:CUS-SRV1172:0.00:INH-10002604:345.60:1.00:346.60:0.00:58:BB0026
        $articleStr='';
        $priceStr='';
        $rateType='';
        $pg = "Paypal";
        $nameStr = "";
        $extStr = "";
        $addSerCost="";
        $addSerStr="";
        
        require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';

        $v=Controlbox::submitpayment($amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$invidkStr,$qtyStr,$wherhourecStr, $CustId,$specialinstructionStr, $cc, $pg, $shipservtStr,$consignidStr,$file,$nameStr,$articleStr,$priceStr,$tid,$Inhouse,$InhouseIdk,$rateType,$Conveniencefees,$addSerStr,$addSerCost,$CompanyId,'','');

         file_put_contents($file_print,$v);

 // Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


mail('madanchunchu@gmail.com',"cod--".$payment_amount.'--'.$payment_status,$item_name.'-----'.$txn_id.'---'.$v,$headers);
	    
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