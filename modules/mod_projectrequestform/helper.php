<?php

error_reporting(E_ALL);

/**
 * @package     Joomla.Site
 * @subpackage  mod_unknownpkgs
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__).'/../../' );

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );

require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');


//defined('_JEXEC') or die('Restricted Access!');

/**
* Projectrequestform Module Helper
* Bug: it's not working with multiple entries. Probably I should use curl_multi_exec
*
* @static
*/
class ModProjectrequestformHelper {

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getParams($params) {
        return $params;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
     public static function getDomainDetails()
    {
        $hostnameStr = $_SERVER['HTTP_HOST'];
        $hostnameStr = str_replace("www.","",$hostnameStr);
        $hostnameArr = explode(".",$hostnameStr);
        $domain = $hostnameArr[0];
        
        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetDomainDetails?ActivationKey=123456789&DomainName='.$domain;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $res = json_decode($result);
        return $res->Data;
    }
    
    
    // get language list
    
     public static function getLanguageList($companyId)
    {
        $hostnameStr = $_SERVER['HTTP_HOST'];
        $hostnameStr = str_replace("www.","",$hostnameStr);
        $hostnameArr = explode(".",$hostnameStr);
        $domain = $hostnameArr[0];
        
        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'//api/RegistrationAPI/GetLanguageDetails?CompanyID='.$companyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $res = json_decode($result);
        return $res->Data;
    }
    
    
    public static function getproectid($companyId){
        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/AutoGenerateProjectId?CompanyID='.$companyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url; 
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
     }

    public static function getservices($companyId){
        mb_internal_encoding('UTF-8'); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getCustAdditionalServices?CompanyID='.$companyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $states='';
        $result = json_decode($result); 
        
        
        
        foreach($result->Data as $rg){
          $states.='<div class="input-grp srvc-grp"><input type="checkbox" class="modalday" name="txtService[]"  id="txtService[]" value="'.$rg->id_AddnlServ.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label></div>';
          //$states.='<input type="checkbox" class="modalday" name="txtService[]"  id="txtService[]" value="'.$rg->id_AddnlServ.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label>';
        }        
        return $states;
    }
    public static function getuserdetails($webservice,$track,$companyId){
       
        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoAPI/GetCustomerIds?Activationkey=123456789&CompanyID='.$companyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg = json_decode($result); 
        foreach($msg->Data as $rg){
            //echo $rg->id_cust.'<br>';
            if(strtolower($rg->id_cust)==strtolower($track)){
              return  $rg->name_f;
            }
        }
    }
    public static function getuserdetailsAjax($webservice,$companyId){
       
        mb_internal_encoding('UTF-8');  
        $url=$webservice.'/api/CustomerInfoAPI/GetCustomerIds?Activationkey=123456789&CompanyID='.$companyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $msg = json_decode($result); 
        $skillData = array(); 
        foreach($msg->Data as $rg){
            //$data['id'] = $rg->name_f; 
            //$data['value'] = $rg->name_f;
            array_push($skillData, $rg->name_f); 
        }
        // Return results as json encoded array 
        echo json_encode($skillData);             
    }
    
    public static function getuserdetailsName($webservice,$track,$companyId){
       
        mb_internal_encoding('UTF-8');  
        $url=$webservice.'/api/CustomerInfoAPI/GetCustomerIds?Activationkey=123456789&CompanyID='.$companyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg = json_decode($result); 
        foreach($msg->Data as $rg){
            $name=$rg->name_f;
            $track=str_replace(' ', '', $track);
            if(strtolower($name)==strtolower($track)){
              return  $rg->id_cust;
            }
        }
    }
    public static function getuserdetailsNameAjax($webservice,$companyId){
       
        mb_internal_encoding('UTF-8');  
        $url=$webservice.'/api/CustomerInfoAPI/GetCustomerIds?Activationkey=123456789&CompanyID='.$companyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        $msg = json_decode($result); 
        $skillData = array(); 
        foreach($msg->Data as $rg){
            //$data['id'] = $rg->id_cust; 
            //$data['value'] = $rg->id_cust;
            //array_push($skillData, $data); 
            array_push($skillData, $rg->id_cust); 
        }
        // Return results as json encoded array 
        echo json_encode($skillData);             
    }    
    public static function submitrequestform($companyId){
    
         
	    $app = JFactory::getApplication();
        $txtAccountnumber = JRequest::getVar('txtAccountnumber', '', 'post');
        $txtInventory = JRequest::getVar('txtInventory', '', 'post');
        $txtAccountname = JRequest::getVar('txtAccountname', '', 'post');
        $txtProjectname = JRequest::getVar('txtProjectname', '', 'post');
        $txtServices = JRequest::getVar('txtService', '', 'post');
        $dateTxt = JRequest::getVar('dateTxt', '', 'post');
        $projectid = JRequest::getVar('projectid', '', 'post');
       
        $services_arr = explode(",", $txtServices);
        
        $txtProductTitle = JRequest::getVar('txtProductTitle', '', 'post');
        
        
         $labelsTxt="";
         $file = JRequest::getVar('inputFiles', null, 'files', 'array');
         
         
        $TARGET=ModProjectrequestformHelper::GUIDv4();
      
        for($i=0;$i<count($file['name']);$i++){
            
            jimport('joomla.filesystem.file');
           
            $filename = JFile::makeSafe($file['name'][$i]);
            $src = $file['tmp_name'][$i];
            $dest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$filename;
            
            $dest1 = $TARGET.'/'.$filename;
            //Redirect to a page of your choice
            if(JFile::upload($src, $dest)){
                $labelsTxt.=$dest1.',';
            } 
        }

        $txtFnsku = JRequest::getVar('txtFnsku', '', 'post');
        $txtFnskuquanity = JRequest::getVar('txtFnskuquanity', '', 'post');
        $txtUPC = JRequest::getVar('txtUPC', '', 'post');
        $txtSKU = JRequest::getVar('txtSKU', '', 'post');
        
       

        //Redirect to a page of your choice
        if($txtAccountnumber!=""){
             mb_internal_encoding('UTF-8'); 
            $content_params =JComponentHelper::getParams( 'com_userprofile' );
            $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/FBARequestForm';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$companyId.'","ProjectId":"'.$projectid.'","AccountNumber":"'.$txtAccountnumber.'","AccountName":"'.$txtAccountname.'","InventoryNeworOverstock":"'.$txtInventory.'","ProjectName":"'.$txtProjectname.'","FNSKUNo":"'.$txtFnsku.'","QuantityperFNSKU":"'.$txtFnskuquanity.'","RequestedDate":"'.$dateTxt.'","CreatedBy":"CUST","ProductName":"'.$txtProductTitle.'","SKUNo":"'.$txtUPC.'","UPC":"'.$txtSKU.'","ActivationKey":"123456789"}');
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    		$result=curl_exec($ch);
    		
    		echo $url;
    		echo '{"CompanyID":"'.$companyId.'","ProjectId":"'.$projectid.'","AccountNumber":"'.$txtAccountnumber.'","AccountName":"'.$txtAccountname.'","InventoryNeworOverstock":"'.$txtInventory.'","ProjectName":"'.$txtProjectname.'","FNSKUNo":"'.$txtFnsku.'","QuantityperFNSKU":"'.$txtFnskuquanity.'","RequestedDate":"'.$dateTxt.'","CreatedBy":"CUST","ProductName":"'.$txtProductTitle.'","SKUNo":"'.$txtUPC.'","UPC":"'.$txtSKU.'","ActivationKey":"123456789"}';
            var_dump($result);exit;
            
            $msg=json_decode($result);
            
            //return $msg->Response.':'.$msg->Description;
           //response code 200 for submit additional services
           //$statusr=explode(":",$statuse);
           if($msg->Response == 200){
            $status=ModProjectrequestformHelper::postprojectservicesrequest($webservice,$txtAccountnumber,$projectid,implode(",",$services_arr),$companyId);
            $status1 = ModProjectrequestformHelper::postprojectlabelsrequest($projectid,$labelsTxt,$companyId);
               
           }
           else{
            $status=$statusr[1];  
           }
        }
        //$app->enqueueMessage($status, 'success');
        return $status;
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




   
       /**
     * Posts the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function postprojectservicesrequest($webservice,$customerid,$projectid,$txtService,$companyId)
    {
        mb_internal_encoding('UTF-8'); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/FBAAdditionalServices';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$companyId.'","ProjectId":"'.$projectid.'","AccountNumber":"'.$customerid.'","AdditionalServices":"'.$txtService.'","CreatedBy":"CUST"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$companyId.'","ProjectId":"'.$projectid.'","AccountNumber":"'.$customerid.'","AdditionalServices":"'.$txtService.'","CreatedBy":"CUST"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Description;
   }
   
    /**
     * Posts the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function postprojectlabelsrequest($projectid,$txtFiles,$companyId)
    {
         mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/AddFBAFormLabels';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$companyId.'","ProjectId":"'.$projectid.'","FNSKULabels":"'.$txtFiles.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$companyId.'","ProjectId":"'.$projectid.'","FNSKULabels":"'.$txtFiles.'"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Description;
   }
   
   
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getexistProjectname($pid,$companyId)
    {
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetProjectName?ProjectName='.curl_escape($ch, $pid).'&ActivationKey=123456789&CompanyID='.$companyId;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Response;
    } 
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function GetBusinessTypes($webservice, $user,$companyId)
    {
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$webservice.'/api/shipmentsapi/GetBusinessTypes?CustomerId='.$user.'&ActivationKey=123456789&CompanyID='.$companyId;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        $msg=json_decode($result);
        $result='';
        
         foreach($msg->Data as $type){
                if($type->desc_vals == "RAR"){
                    $type_desc = "Used Inventory?";
                }else if($type->desc_vals == "IPS"){
                    $type_desc = "Is your inventory NEW?";
                }else{
                    $type_desc = "";
                }
                if($type_desc !=''){
                                  
                    $result .= '<div class="input-grp">
                      <input id="txtInventory" type="radio"  name="txtInventory" id="txtInventory" value="'.$type->id_vals.'" >
                        <label class="custom-radio">'.$type_desc.'</label> <sub>*</sub>
                    </div>';
                    
                }
            } 
            
        // echo $url;
        // var_dump($result);
        // exit;
       
       return $result;
       
    }    
    
     public static function getservicesproj($webservice,$user,$companyId){
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getCustAdditionalServices?CustId='.$user.'&type_business=&CompanyID='.$companyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // echo $result;
        // exit;
        
        $serviceList='';
        $serviceList.='<div class="col-md-12"><h4>Service(s) Requested</h4></div><div class="col-md-12"><div class="col-md-2 form-group"> <strong>Service requirement<sub>*</sub></strong></div><div class="col-md-10" id="getcustserdiv">';
        $result = json_decode($result); 
        foreach($result->Data as $rg){
          $serviceList.='<div class="input-grp srvc-grp"><input type="checkbox" class="modalday" name="txtService[]"  id="txtService[]" value="'.$rg->id_AddnlServ.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label></div>';
          //$states.='<input type="checkbox" class="modalday" name="txtService[]"  id="txtService[]" value="'.$rg->id_AddnlServ.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label>';
        }   
        
        $serviceList .='</div></div>';
        return $serviceList;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function dynamicpages($CompanyId)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/AccountAPI/GetDynamicPages?ModuleType=frontend&CompanyID='.$CompanyId;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }     
    
   


}

if($_REQUEST['userids']){
    $webservice=$_REQUEST['webservice'];
    $companyId=$_REQUEST['CompanyID'];
    $users=new ModProjectrequestformHelper();
    $track=$_REQUEST['userids'];
    echo $users->getuserdetails($webservice,$track,$companyId);
    exit;
}
if($_REQUEST['usernames']){
    $users=new ModProjectrequestformHelper();
    $track=$_REQUEST['usernames'];
    $webservice=$_REQUEST['webservice'];
    $companyId=$_REQUEST['CompanyID'];
    echo $users->getuserdetailsName($webservice,$track,$companyId);
    exit;
}
if($_REQUEST['regformsflag']){
    $users=new ModProjectrequestformHelper();
    $companyId=$_REQUEST['CompanyID'];
    echo ModProjectrequestformHelper::submitrequestform($companyId);
    exit;
}

if($_REQUEST['prexistflag']){
    $pid=$_REQUEST['txtProjectname'];
    $companyId=$_REQUEST['CompanyID'];
    echo ModProjectrequestformHelper::getexistProjectname($pid,$companyId);
   
}
if($_REQUEST['businesstypeflag']){
     $webservice=$_REQUEST['webservice'];
     $user = $_REQUEST['userid'];
     $companyId=$_REQUEST['CompanyID'];
     echo ModProjectrequestformHelper::GetBusinessTypes($webservice,$user,$companyId);
     exit;
}
if($_REQUEST['getserviceflag']){
     $webservice=$_REQUEST['webservice'];
     $user = $_REQUEST['userid'];
     $companyId=$_REQUEST['CompanyID'];
     echo ModProjectrequestformHelper::getservicesproj($webservice,$user,$companyId);
     exit;
}

 