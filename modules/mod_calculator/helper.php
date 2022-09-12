<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_calculator
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die('Restricted Access!');

/**
* Calculator Module Helper
* Bug: it's not working with multiple entries. Probably I should use curl_multi_exec
*
* @static
*/
class ModCalculatorHelper {
    public static function getParams ($params) {
        return $params;
    }
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getPickupFieldviewsList($cid)
    {
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/getPickupOrder?custId='.$cid.'&Activationkey=123456789';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Data;
    }       

}