<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Register
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
 use Joomla\CMS\Uri\Uri;

class Controlbox{
    
 
      /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
     public static function getCompanyId()
    {
        require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
        $domainDetails = ModProjectrequestformHelper::getDomainDetails();
        $CompanyId = $domainDetails[0]->CompanyId;
        
        return $CompanyId;
       
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    // public static function getCompanyLogo($CompanyId)
    // {
       
    //     mb_internal_encoding('UTF-8');
    //     $content_params =JComponentHelper::getParams( 'com_register' );
    //     $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetCompanyDetails?ActivationKey=123456789&CompanyId='.$CompanyId;
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //     $result=curl_exec($ch);
    //     $res=json_decode($result);
    //     foreach($res->Data as $company){
                
    //                 $CompanyLogo= $company->CompanyLogo;
               
    //     }   
        
    //     return $CompanyLogo;
        
    // }
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    // public static function getCompanyName($CompanyId)
    // {
       
    //     mb_internal_encoding('UTF-8');
    //     $content_params =JComponentHelper::getParams( 'com_register' );
    //     $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetCompanyDetails?ActivationKey=123456789&CompanyId='.$CompanyId;
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //     $result=curl_exec($ch);
    //     $res=json_decode($result);
    //     foreach($res->Data as $company){
                
    //                 $CompanyName= $company->CompanyName;
               
    //     }   
        
    //     return $CompanyName;
        
    // }
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    // public static function getCompanyDetails($CompanyId)
    // {
       
    //     mb_internal_encoding('UTF-8');
    //     $content_params =JComponentHelper::getParams( 'com_register' );
    //     $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetCompanyDetails?ActivationKey=123456789&CompanyId='.$CompanyId;
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //     $result=curl_exec($ch);
    //     $res=json_decode($result);
       
    //     return $res->Data[0];
        
    // }
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function setRegister($fname,$lname,$addressone,$addresstwo,$pin,$phone,$acctype,$email,$dialcode,$country,$state,$city,$password,$gender,$ur,$imageByteStream,$nameStr,$extStr,$idtype,$idvalue,$agentId,$domainurl)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        //$CompanyId = '';
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Register';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	    curl_setopt($ch, CURLOPT_POSTFIELDS,'{"ImageByteStream":"'.$imageByteStream.'","CompanyID":"'.$CompanyId.'","FirstName":"'.$fname.'","LastName":"'.$lname.'","Address1":"'.$addressone.'","Address2":"'.$addresstwo.'","PostalCode":"'.$pin.'","DailCode":"'.$dialcode.'","PhoneNumber":"'.$phone.'","AccountType":"'.$acctype.'","email":"'.$email.'","Country":"'.$country.'","State":"'.$state.'","City":"'.$city.'","Password":"'.$password.'","ConfirmPassword":"'.$password.'","UpdatedOn":"","Gender":"'.$gender.'","identityType":"'.$idtype.'","identityValue":"'.$idvalue.'","custImage":"'.$ur.'","fileName":"'.$nameStr.'","fileExtension":"'.$extStr.'","custImageURL":"Joomla","ActivationKey":"123456789","AgencyId":"'.$agentId.'","domainurl":"'.$domainurl.'"}');
		$result=curl_exec($ch);
		
		/** Debug **/
		
// 	 echo $url;
//      echo '{"ImageByteStream":"'.$imageByteStream.'","CompanyID":"'.$CompanyId.'","FirstName":"'.$fname.'","LastName":"'.$lname.'","Address1":"'.$addressone.'","Address2":"'.$addresstwo.'","PostalCode":"'.$pin.'","DailCode":"'.$dialcode.'","PhoneNumber":"'.$phone.'","AccountType":"'.$acctype.'","email":"'.$email.'","Country":"'.$country.'","State":"'.$state.'","City":"'.$city.'","Password":"'.$password.'","ConfirmPassword":"'.$password.'","UpdatedOn":"","Gender":"'.$gender.'","identityType":"'.$idtype.'","identityValue":"'.$idvalue.'","custImage":"'.$ur.'","fileName":"'.$nameStr.'","fileExtension":"'.$extStr.'","custImageURL":"Joomla","ActivationKey":"123456789","AgencyId":"'.$agentId.'","domainurl":"'.$domainurl.'"}';
//      var_dump($result);exit;

        $msg=json_decode($result);
        return $msg;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function setAgentRegister($fname,$lname,$addressone,$addresstwo,$pin,$phone,$acctype,$email,$dialcode,$country,$state,$city,$password,$agency)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/AgentRegister';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","FirstName":"'.$fname.'","LastName":"'.$lname.'","Address1":"'.$addressone.'","Address2":"'.$addresstwo.'","PostalCode":"'.$pin.'","PhoneNumber":"'.$phone.'","AccountType":"'.$acctype.'","Email":"'.$email.'","DailCode":"'.$dialcode.'","Country":"'.$country.'","State":"'.$state.'","City":"'.$city.'","Password":"'.$password.'","ConfirmPassword":"'.$password.'","UpdatedOn":"","ActivationKey":"123456789","AgencyId":"'.$agency.'","RegisterType":"AGENT"}');
		$result=curl_exec($ch);
		
		/** Debug **/
        // echo '{"CompanyID":"'.$CompanyId.'","FirstName":"'.$fname.'","LastName":"'.$lname.'","Address1":"'.$addressone.'","Address2":"'.$addresstwo.'","PostalCode":"'.$pin.'","PhoneNumber":"'.$phone.'","AccountType":"'.$acctype.'","Email":"'.$email.'","DailCode":"'.$dialcode.'","Country":"'.$country.'","State":"'.$state.'","City":"'.$city.'","Password":"'.$password.'","ConfirmPassword":"'.$password.'","UpdatedOn":"","ActivationKey":"123456789","AgencyId":"'.$agency.'","RegisterType":"AGENT"}';
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg;
    }
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getLogin($user,$pass)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/AccountApi/Login';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","UserName":"'.$user.'","Password":"'.$pass.'","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","UserName":"'.$user.'","Password":"'.$pass.'","ActivationKey":"123456789"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        if($msg->ResCode==1)
        return $msg->Data->UserName;
        else
        return 0;
    }
    
    
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getidentityList()
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoAPI/getIdentityTypes?CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        return $result;
    }       
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getdialcodeList()
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetDailCodes?ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        return $result;
    }       

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCountriesList()
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Countries?ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        return $result;
    }       


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getStatesList($cid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/States?countryId='.$cid.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $states='<option value=0>Select State</option>';
        $result = json_decode($result); 
        
        // echo $url;
        // var_dump($result);exit;
        
        foreach($result->Data as $rg){
          //echo '<br>'.$rg->StateDesc;
          //if($arr->StatesId!="")
          $states.= '<option value="'.$rg->StatesId.'">'.$rg->StateDesc.'</option>';
        }        
        return $states;
        
        
    }       


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCitiesList($cid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Cities?stateId='.urlencode($cid).'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $rescities = json_decode($result); 
        //$cities='';
        $cities='<option value="">'.Jtext::_('COM_REGISTER_SELECT_CITY_LABEL').'</option>';
        foreach($rescities->Data as $rg){
          //echo '<br>'.$rescities[0]->CityCode;
          //$cities.= '<option value="'.$rg->CityDesc.'" data-xyz="'.$rg->CityCode.'" >'.$rg->CityDesc.'</option>';
          $cities.= '<option value="'.$rg->CityCode.'" >'.$rg->CityDesc.'</option>';
        }        
        return $cities;
    }  

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getIdExist($idfil,$idvalue)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/CheckIdentityValue?idType='.urlencode($idfil).'&idValue='.$idvalue.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $result;
    }




    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getEmailExist($email)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetEmail?Email='.$email.'&ActivationKey=123456789&AdditionalUser=FALSE&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $msg=json_decode($result);
        
        /** Debug **/
        
        // echo $url;
        // var_dump($msg->Response.":".$msg->Description);exit;
        
        return $msg->Response.":".$msg->Description;
    }



    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getAgentId()
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/AutoGenerateAgencyId?ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Data;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getForgetpassword($user,$domainurl)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/AccountApi/ForgotPassword';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$user))
        { 
            $req='{"CompanyID":"'.$CompanyId.'","UserName":"'.$user.'","Email":"","domainurl":"'.$domainurl.'",ActivationKey":"123456789","Portal":"Joomla"}';
           curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","UserName":"'.$user.'","Email":"","domainurl":"'.$domainurl.'","ActivationKey":"123456789","Portal":"Joomla"}');
        }else{
            $req='{"CompanyID":"'.$CompanyId.'","UserName":"","Email":"'.$user.'","domainurl":"'.$domainurl.'","ActivationKey":"123456789","Portal":"Joomla"}';
           curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","UserName":"","Email":"'.$user.'","domainurl":"'.$domainurl.'","ActivationKey":"123456789","Portal":"Joomla"}');
        }
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
// 		echo $req;
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->ResCode.":".$msg->Msg;
    }
 




     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getHubStatesList($cid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/getHubsByCountryId?CountryId='.$cid.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        $opt='';
        foreach($msg->Data as $rows){
            $opt .='<option value="'.$rows->code_hub.'">'.$rows->name_hub.'</option>';
        }
        return $opt;
        
        
    }  
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getmainpagedetails()
    {
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/DashBoardAPI/getmainpagedetails?CompanyID='.$CompanyId.'&Activationkey=123456789';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $msg=json_decode($result);
        
        /** Debug **/
        // echo $url;
        // var_dump($msg->Data);
        // exit;
        
        return $msg->Data;
    }    
    
     /**
     * Gets Account Type
     */
    public static function getaccounttype()
    {
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetAccountTypeDetails?CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $result=json_decode($result);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);
        // exit;
        
        return $result;
    }      

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function dynamicElements($page)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/AccountAPI/GetPageElements?PageName='.$page.'&CompanyID='.$CompanyId;
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
    
     public static function getlabels($langSel)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams('com_register');
        
        $ch = curl_init();
        $url=$content_params->get( 'webservice' )."/api/ImgUpldFTP/ConvertResxXmlToJson?companyId=".$CompanyId."&language=".$langSel;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        
        // var_dump($url."##".$resp);
        // exit;
        

        if($e= curl_error($ch)){
            echo $e;
        }
        else{
                $decoded = json_decode($resp,true);
                $res = json_decode($decoded['Data']);
        }  

        return $res;
    }
    
    
}