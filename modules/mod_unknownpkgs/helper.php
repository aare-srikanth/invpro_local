<?php

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
* Unknownpkgs Module Helper
* Bug: it's not working with multiple entries. Probably I should use curl_multi_exec
*
* @static
*/
class ModUnknownpkgsHelper {

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
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
    
    public static function unknownpackagesdetails()
    {
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/UnknownShipmentsAPI/GetUnknown';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);
        $msg=json_decode($result);
        $tr='';
        foreach($msg->Data as $rg){
            $tr.= '<tr><td><a data-toggle="modal" data-target="#inv_view"  data-id="'.$rg->TrackingId.'" >'.$rg->TrackingId.'</a></td><td>'.$rg->ItemDesc.'</td></tr>';
        }
        return $tr;
    }       
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function unknownpackagesdetailsbytrack($track)
    {
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/UnknownShipmentsAPI/GetUnknownDetails?CommandType=GetUnknownShipmentsByTrackingId&TrackingID='.$track;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $msg=json_decode($result);
        $msg=$msg->Data;
        return '<tbody><tr><td>'.$msg[0]->DateOfRecieved.'</td><td>'.$msg[0]->TrackingId.'</td><td>'.$msg[0]->WarehouseId.'</td><td>'.$msg[0]->Weight.'</td><td>'.$msg[0]->Branch.'</td><td>'.$msg[0]->ItemDesc.'</td></tr></tbody>';
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
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Countries?ActivationKey=123456789';
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
    
    public static function getDialcodeList()
    {
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetDailCodes?ActivationKey=123456789';
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
    
    public static function postUnknownshippingDetails()
    {
        $txtWarehouse = JRequest::getVar('txtWarehouse', '', 'post');
        $txtTrackingId = JRequest::getVar('txtTrackingId', '', 'post');
        $txtFirstname = JRequest::getVar('txtFirstname', '', 'post');
        $txtLastname = JRequest::getVar('txtLastname', '', 'post');
        $txtDestinationCountry = JRequest::getVar('txtDestinationCountry', '', 'post');
        $txtDestinationState = JRequest::getVar('txtDestinationState', '', 'post');
        $txtDestinationCity = JRequest::getVar('txtDestinationCity', '', 'post');
        $txtAddress = JRequest::getVar('txtAddress', '', 'post');
        $txtAddresstwo = JRequest::getVar('txtAddresstwo', '', 'post');
        $txtZipcode = JRequest::getVar('txtZipcode', '', 'post');
        $txtEmail = JRequest::getVar('txtEmail', '', 'post');
        $txtDialcode = JRequest::getVar('txtDialcode', '', 'post');
        $txtContactNo = JRequest::getVar('txtContactNo', '', 'post');
        
        
        $fileTxt = JRequest::getVar('invoiceTxt', '', 'post');
        jimport('joomla.filesystem.file');
        $TARGET=$this->GUIDv4();
        $photodest1='';
        $profilepicFile = JRequest::getVar('profilepicTxt', null, 'files', 'array');
        if($profilepicFile["name"]){
            $profilepicname =JFile::makeSafe($profilepicFile['name']);
            $photodest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$profilepicname;
            $photodest1 = $TARGET.'/'.$profilepicname;
            JFile::upload($profilepicFile["tmp_name"], $photodest);
        }else{
            $photodest1=1;
        }
         mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/pi/UnknownShipmentsAPI/insertCustDetailsUnknownShipments';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"Name":"'.$txtName.'","TrackingId":"'.$txtTrackingId.'","WarehouseId":"'.$txtWarehouse.'","emailid":"'.$txtEmail.'","Address":"'.$txtAddress.'","DailCode":"'.$txtDialcode.'","MobileNo":"'.$txtContactNo.'","Destination":"'.$txtDestinationCountry.'","InvoiceFile":"'.$photodest1.'","InvoicePath":"Joomla"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Data;
    }   

}

if($_GET['task']){
    $objboxon=  new  ModUnknownpkgsHelper();
    $track=$_GET['trackid'];
    echo $objboxon->unknownpackagesdetailsbytrack($track);
    exit;
}