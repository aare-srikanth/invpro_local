<?php
$txn_id="dfgdfgdf";
$item_number="2";
$payment_amount='63.82';
//$item_name='WR-2126:CUS-SRV1173:0.00:INH-10002950:160.00:1.00:121.12::404:Bb006';
$item_name='266:WR-1369:::CUS-SRV-1171::hy:4.00:formula:BX1038';
        $exp=explode(":",$item_name);
        $amtStr=$payment_amount;
        $qtyStr=$item_number;
        $cardnumberStr='';
        $txtccnumberStr=''; 
        $MonthDropDownListStr='';  
        $txtNameonCardStr='';
        $YearDropDownListStr='';
        $invidkStr=$exp[0];
        $wherhourecStr=$exp[1]; 
        $CustId=$exp[9];
        $rateType=$exp[8];
        $InhouseIdk='';
        $Inhouse='';
        $file='';
        $specialinstructionStr='';
        $cc='PPD'; 
        $shipservtStr=$exp[4];
        $consignidStr='';
        $tid=$txn_id;
        //WR-1118-C:CUS-SRV1172:0.00:INH-10002604:345.60:1.00:346.60:0.00:58:BB0026
        $articleStr=$exp[6];
        $priceStr=$exp[7];
        require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';

        $v=Controlbox::submitpayment($amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$invidkStr,$qtyStr,$wherhourecStr, $CustId,$specialinstructionStr, $cc, $shipservtStr,$consignidStr,$file,$articleStr,$priceStr,$tid,$Inhouse,$InhouseIdk,$rateType);
