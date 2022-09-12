<?php

error_reporting(1);
    function sendRequest($url, $timeout=30){
    	//Make sure cURL is available
    	if (function_exists('curl_init') && function_exists('curl_setopt')){
	        //The headers are required for authentication
	        $headers = array(
	            'Cache-Control: no-cache',
	            'Fk-Affiliate-Id: '.$this->affiliateId,
	            'Fk-Affiliate-Token: '.$this->token
	            );

	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-ClusterDev-Flipkart/0.1');
	        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        $result = curl_exec($ch);
	        curl_close($ch);

	        return $result ? $result : false;
	    }else{
            //Cannot work without cURL
			return false;
	    }        
    }
   
    
    $url='http://cmcclaims.justfordemo.biz/api/CommonApi/States?CountryId=1&Activationkey=123456789';
            $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
	        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
             $result = curl_exec($ch);
	        curl_close($ch);
	        
	        
	        var_dump($result);
	        
	        
	        
	        

	        
?>