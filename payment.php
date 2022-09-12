<?php

define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__) );//this is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';

$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
$domainDetails = ModProjectrequestformHelper::getDomainDetails();


foreach($domainDetails[0]->PaymentGateways as $PaymentGateways){
    
       if($PaymentGateways->PaymentGatewayName == "authorize.net"){
            $userid = $PaymentGateways->UserId;
            $trankey = $PaymentGateways->TransactionKey;
            $apiurl = $PaymentGateways->ApiUrl; 
       }
       
}

// $userid = '83WCzGzsh65';
// $trankey = '6UMa3Sq289u7nUSX';
// $apiurl = 'https://apitest.authorize.net'; 

// echo '<pre>';
// var_dump($userid.$trankey.$apiurl);exit;

// Include Authorize.Net PHP sdk 
require 'sdk/autoload.php';  
use net\authorize\api\contract\v1 as AnetAPI; 
use net\authorize\api\controller as AnetController; 
 
// // Include configuration file  
// //require_once 'config.php'; 
 
$paymentID = $statusMsg = ''; 
$ordStatus = 'error'; 
$responseArr = array(1 => 'Approved', 2 => 'Declined', 3 => 'Error', 4 => 'Held for Review'); 
$itemName = $_POST['wherhourecStr'];
$itemPrice = $_POST['amount'];
$itemNameStr = $_POST['item_name'];
$item_number = $_POST['item_number'];
$invoiceNo = $_POST['InvoiceNo'];

$lengthStr = $_POST['lengthStr']; 
$widthStr = $_POST['widthStr']; 
$heightStr = $_POST['heightStr']; 
$grosswtStr = $_POST['weightStr']; 
$volumeStr = $_POST['volStr']; 
$volumetwtStr = $_POST['volmetStr']; 
$shipmentCost = $_POST['shipmentCost']; 
$totalDecVal = $_POST['totalDecVal']; 
$couponCodeStr = $_POST['couponCodeStr']; 
$couponDiscAmt = $_POST['couponDiscAmt']; 

// var_dump($lengthStr);
// exit;


// Check whether card information is not empty 
if(!empty($_POST['cardnumberStr']) && !empty($_POST['MonthDropDownListStr']) && !empty($_POST['YearDropDownListStr']) && !empty($_POST['txtccnumberStr'])){ 
    
    
     
    // Retrieve card and user info from the submitted form data 
    
    $email = $_POST['emailStr']; 
    $card_number = preg_replace('/\s+/', '', $_POST['cardnumberStr']); 
    $card_exp_month = $_POST['MonthDropDownListStr']; 
    $card_exp_year = $_POST['YearDropDownListStr']; 
    $card_exp_year_month = $card_exp_year.'-'.$card_exp_month; 
    $card_cvc = $_POST['txtccnumberStr']; 
     
    // Set the transaction's reference ID 
    $refID = 'REF'.time(); 
     
    // Create a merchantAuthenticationType object with authentication details 
    // retrieved from the config file 
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();    
    //$merchantAuthentication->setName(ANET_API_LOGIN_ID);    
    //$merchantAuthentication->setTransactionKey(ANET_TRANSACTION_KEY);
    
    $merchantAuthentication->setName($userid);    
    $merchantAuthentication->setTransactionKey($trankey);  //6265h73Z58VzxmKw // 3xFLu3979M5dcHfV 

    // Create the payment data for a credit card 
    $creditCard = new AnetAPI\CreditCardType(); 
    $creditCard->setCardNumber($card_number); 
    $creditCard->setExpirationDate($card_exp_year_month); 
    $creditCard->setCardCode($card_cvc); 
     
    // Add the payment data to a paymentType object 
    $paymentOne = new AnetAPI\PaymentType(); 
    $paymentOne->setCreditCard($creditCard); 
     
    // Create order information 
    $order = new AnetAPI\OrderType(); 
    $order->setDescription($itemName); 
     
    // Set the customer's identifying information 
    $customerData = new AnetAPI\CustomerDataType(); 
    $customerData->setType("individual"); 
    $customerData->setEmail($email); 
     
    // Create a transaction 
    $transactionRequestType = new AnetAPI\TransactionRequestType(); 
    $transactionRequestType->setTransactionType("authCaptureTransaction");    
    $transactionRequestType->setAmount($itemPrice); 
    $transactionRequestType->setOrder($order); 
    $transactionRequestType->setPayment($paymentOne); 
    $transactionRequestType->setCustomer($customerData); 
    $request = new AnetAPI\CreateTransactionRequest(); 
    $request->setMerchantAuthentication($merchantAuthentication); 
    $request->setRefId($refID); 
    $request->setTransactionRequest($transactionRequestType); 
    $controller = new AnetController\CreateTransactionController($request); 
    $response = $controller->executeWithApiResponse($apiurl); 
    
    
   
    if ($response != null) { 
        // Check to see if the API request was successfully received and acted upon 
        if ($response->getMessages()->getResultCode() == "Ok") { 
            // Since the API request was successful, look for a transaction response 
            // and parse it to display the results of authorizing the card 
            $tresponse = $response->getTransactionResponse(); 
 
            if ($tresponse != null && $tresponse->getMessages() != null) { 
                // Transaction info 
                $transaction_id = $tresponse->getTransId(); 
                $payment_status = $response->getMessages()->getResultCode(); 
                $payment_response = $tresponse->getResponseCode(); 
                $auth_code = $tresponse->getAuthCode(); 
                $message_code = $tresponse->getMessages()[0]->getCode(); 
                $message_desc = $tresponse->getMessages()[0]->getDescription(); 
                 
                // for payment transaction service (insert the data)
                
                if($_POST['page'] == 'orderprocess'){ 
                            $itemfile = "item.txt";
                            $exp=explode(":",$itemNameStr);
                            $amtStr=$payment_amount;
                            $cardnumberStr='';
                            $txtccnumberStr=$card_cvc; 
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
                            
                            $elfe=explode(",",$exp[0]);
                            $mgsr='';
                            $invf='';
                            $filenameStr='';
                            $nameStr = "";
                            $extStr = "";
                            $i=0;
                            
                            $eew=explode(",",$exp[5]);
                          
                            
        $TARGET=GUIDv4();
                        
        foreach($_FILES['invFile']['name'] as $key=>$mage){
            $profilepicname =JFile::makeSafe($_FILES['invFile']['name'][$key]);
            $photodest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$profilepicname;
           
            if($_FILES['invFile']["tmp_name"][$key]){
                JFile::upload($_FILES['invFile']["tmp_name"][$key], $photodest);
            }
            
             if($profilepicname){
            
            $image1 = file_get_contents($photodest);
            $imageByteStream = base64_encode($image1);
            $invf .= $imageByteStream.',';
            $filenameStr .= $profilepicname.',';
            
            }else{
                $filenameStr .='0,';
                $invf .= '0,';
            }    
        }             
                            $file=$invf;
                            $specialinstructionStr=$exp[3];
                            $cc='PPD'; 
                            $shipservtStr=$exp[4];
                            $consignidStr=$exp[2];
                            $tid=$txn_id;
                            $articleStr=$exp[6];
                            $priceStr=$exp[7];
                            $pg = "authorize.net";
                            
                            $status=Controlbox::submitpayment($itemPrice,$card_number,$txtccnumberStr,$card_exp_month,$txtNameonCardStr,$card_exp_year,$invidkStr,$qtyStr,$wherhourecStr,$CustId,$specialinstructionStr,$cc,$pg,$shipservtStr,$consignidStr,$file,$filenameStr,$articleStr,$priceStr,$transaction_id,$Inhouse,$InhouseIdk,$rateType,$Conveniencefees,$addSerStr,$addSerCost,$CompanyId,$insuranceCost,$extAddSer,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$shipmentCost,$totalDecVal,$couponCodeStr,$couponDiscAmt);
                   
                            
                }else if($_POST['page'] == "shopperassist"){
                    $exp=explode(":",$itemNameStr);
                    $txtccnumberStr=$card_cvc;
                    $txtNameonCardStr='';
                    $invidkStr=$exp[1];
                    $qtyStr=$item_number;
                    $pg = "authorize.net";
                    $articleStr=$exp[2];
                    $specialinstructionStr=$exp[3];
                    $CustId = $exp[4];
                    $tid=$transaction_id;
                    $txtPaymentMethod = 'PPD';
                    $txtTaxesStr = 0;
                    $txtShippChargesStr = 0;
                    
                    $invfArr=array();
                    $filenameArr=array();
                    jimport('joomla.filesystem.file');
                    
                    foreach($_FILES['invFile']['name'] as $key=>$image){
                    
                    array_push($filenameArr,JFile::makeSafe($_FILES['invFile']['name'][$key]));
                    $images_mul = file_get_contents($_FILES['invFile']['tmp_name'][$key]);
                    array_push($invfArr,base64_encode($images_mul));
                    
                    }
                    
                    $status=Controlbox::submitpayshopperassist($CustId,$itemPrice,$card_number,$txtccnumberStr, $card_exp_month,  $txtNameonCardStr, $card_exp_year,$specialinstructionStr,$txtTaxesStr,$txtShippChargesStr,$invidkStr,$qtyStr,$articleStr,$txtPaymentMethod,$pg,'','',$tid,$filenameArr,$invfArr);
                    
                }else if($_POST['page'] == "cod"){
                    $exp=explode(":",$itemNameStr);
                    $txtccnumberStr=$card_cvc;
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
                    $pg = "authorize.net";
                    $nameStr = "";
                    $extStr = "";
                    $addSerCost="";
                    $addSerStr="";
                    $filenameStr="";
                    $insuranceCost="";
                    $extAddSer="";
                    
                    $status=Controlbox::submitpayment($itemPrice,$card_number,$txtccnumberStr,$card_exp_month,$txtNameonCardStr,$card_exp_year,$invidkStr,$qtyStr,$wherhourecStr,$CustId,$specialinstructionStr,$cc,$pg,$shipservtStr,$consignidStr,$file,$filenameStr,$articleStr,$priceStr,$transaction_id,$Inhouse,$InhouseIdk,$rateType,$Conveniencefees,$addSerStr,$addSerCost,$CompanyId,$insuranceCost,$extAddSer,'','','','','','','','');
                   
                }
                
                
                // var_dump($status);
                // exit;
                            
                           
                            $input = JFactory::getApplication()->input;
                            $input->set('invoice', $status);
                            
                            if(strpos($status, ':')===false){
                                if($status){
                                    //$app->enqueueMessage($status, 'error');
                                }else{
                                    //$app->enqueueMessage("Payment Failure", 'error');
                                }
                                if($_POST['page'] == 'orderprocess'){
                                header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess&error='.$status, false));
                                }else if($_POST['page'] == 'shopperassist'){
                                    header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist&error='.$status, false));
                                }else if($_POST['page'] == 'cod'){
                                    header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=cod&error='.$status, false));
                                }
                                
                            }else{
                                $statausArr = explode(":",$status);
                                if($statausArr[2] == '1'){
                                     if($_POST['page'] == 'orderprocess'){
                                        header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=response&res='.base64_encode($statausArr[0]), false));
                                     }else if($_POST['page'] == 'shopperassist'){
                                         header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=response&res='.base64_encode($statausArr[0]), false));
                                     }else if($_POST['page'] == 'cod'){
                                         header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=response&page=cod&invoice='.$invoiceNO.'&res='.base64_encode($statausArr[0]), false));
                                     }
                                    
                                }else{
                                    if($_POST['page'] == 'orderprocess'){
                                        header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess', false));
                                    }else if($_POST['page'] == 'shopperassist'){
                                        header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist', false));
                                    }else if($_POST['page'] == 'cod'){
                                        header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=cod', false));
                                    }
                                }
                                
                            }    
                
                // end
                 
                $ordStatus = 'success'; 
                $statusMsg = 'Your Payment has been Successful!'; 
            } else { 
                $error = "Transaction Failed! \n"; 
                if ($tresponse->getErrors() != null) { 
                    //$error .= " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "<br/>"; 
                    $error .= " Error Message : " . $tresponse->getErrors()[0]->getErrorText(); 
                } 
                $statusMsg = $error; 
                if($_POST['page'] == 'orderprocess'){
                    header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess&error='.$statusMsg, false));
                }else if($_POST['page'] == 'shopperassist'){
                    header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist&error='.$statusMsg, false));
                }else if($_POST['page'] == 'cod'){
                    header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=cod&error='.$statusMsg, false));
                }
            } 
            // Or, print errors if the API request wasn't successful  
        } else { 
            $error = "Transaction Failed! \n"; 
            $tresponse = $response->getTransactionResponse(); 
         
            if ($tresponse != null && $tresponse->getErrors() != null) { 
                //$error .= " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "<br/>"; 
                $error .= " Error Message : " . $tresponse->getErrors()[0]->getErrorText(); 
            } else { 
                //$error .= " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "<br/>"; 
                $error .= " Error Message : " . $response->getMessages()->getMessage()[0]->getText(); 
            } 
            $statusMsg = $error;
            if($_POST['page'] == 'orderprocess'){
                header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess&error='.$statusMsg, false));
            }else if($_POST['page'] == 'shopperassist'){
                header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist&error='.$statusMsg, false));
            }else if($_POST['page'] == 'cod'){
                header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=cod&error='.$statusMsg, false));
            }
        } 
    } else { 
        $statusMsg =  "Transaction Failed! No response returned"; 
        if($_POST['page'] == 'orderprocess'){
            header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess&error='.$statusMsg, false));
        }else if($_POST['page'] == 'shopperassist'){
            header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist&error='.$statusMsg, false));
        }else if($_POST['page'] == 'cod'){
            header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=cod&error='.$statusMsg, false));
        }
    } 
}else{ 
    $statusMsg = "Error on form submission."; 
    if($_POST['page'] == 'orderprocess'){
        header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess&error='.$statusMsg, false));
    }else if($_POST['page'] == 'shopperassist'){
        header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist&error='.$statusMsg, false));
    }else if($_POST['page'] == 'cod'){
        header('Location: '.JRoute::_('index.php?option=com_userprofile&view=user&layout=cod&error='.$statusMsg, false));
    }
} 

function GUIDv4 ($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }
    
        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
    
        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace.
                  substr($charid,  0,  8).$hyphen.
                  substr($charid,  8,  4).$hyphen.
                  substr($charid, 12,  4).$hyphen.
                  substr($charid, 16,  4).$hyphen.
                  substr($charid, 20, 12).
                  $rbrace;
        return $guidv4;
    }
?>
