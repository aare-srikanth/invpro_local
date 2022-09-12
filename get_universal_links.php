<?php

// GET REGISTATION, FORGOTPASSWORD AND aGENT REGISTRATION LINKS

error_reporting(E_ALL);

        mb_internal_encoding('UTF-8');
        header('Content-Type: application/json');
        $url='http://boxonsaasdev.inviewpro.com/api/RegistrationAPI/GetCompanyDetails?ActivationKey=123456789&CompanyId=';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $res=json_decode($result);
        
        $universalLinks['Universal Links'] = array();
        
        foreach($res->Data as $company){
                
                    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                    $uri = 'https://';
                    } else {
                    $uri = 'http://';
                    }
                    $uri .= $_SERVER['HTTP_HOST'];
                    
                    $hostnameArr = explode(".",$_SERVER['HTTP_HOST']);
                    $domain = $hostnameArr[0];
                    
                  
                    
                    $custRegLink = $uri."/index.php/en/registration";
                    $agentRegLink = $uri."/index.php/en/component/register/agentregister";
                    $forgotPassLink = $uri."/index.php/en/component/register/forgotpassword";
                    $prflink = $uri."/index.php/en/project-request-form";
                    
                    
                    $Links = array(
                        "Company ID" => $company->CompanyId,
                        "Company Name" => $company->CompanyName,
                        "Company Logo" => $company->CompanyLogo,
                        "Customer Registration Link" => $custRegLink,
                        "Agent Registration Link" => $agentRegLink,
                        "Forgot Password Link" => $forgotPassLink,
                        "PRF Link" => $prflink,
                        "Domain Name" => $company->Domain,
                        );
                        
                        
                        if($domain == strtolower($company->Domain)){
                            array_push($universalLinks['Universal Links'],$Links);  
                        }
                    
                         
        }  
        
       
       echo json_encode($universalLinks,JSON_UNESCAPED_SLASHES);
        

?>