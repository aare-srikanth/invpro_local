<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('UserprofileHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_userprofile' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'userprofile.php');
include_once JPATH_ROOT.'/components/com_userprofile/classes/controlbox.php';

/**
 * Class UserprofileFrontendHelper
 *
 * @since  1.6
 */
class UserprofileHelpersUserprofile
{
    
    
    
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_userprofile/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_userprofile/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'UserprofileModel');
		}

		return $model;
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function canUserEdit($item)
    {
        $permission = false;
        $user       = JFactory::getUser();

        if ($user->authorise('core.edit', 'com_userprofile'))
        {
            $permission = true;
        }
        else
        {
            if (isset($item->created_by))
            {
                if ($user->authorise('core.edit.own', 'com_userprofile') && $item->created_by == $user->id)
                {
                    $permission = true;
                }
            }
            else
            {
                $permission = true;
            }
        }

        return $permission;
    }
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUserprofileDetails($user)
    {
        return Controlbox::getUserprofileDetails($user);
    }
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUserpersonalDetails($user)
    {
        return Controlbox::getUserpersonalDetails($user);
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
        return Controlbox::getCountriesList();
    }
    
  
   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getStatesList($country,$sid)
    {
        return Controlbox::getStatesList($country,$sid);
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getStatesListPickup($country)
    {
        return Controlbox::getStatesListPickup($country);
    }
    
   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCitiesList($country,$sid,$cid)
    {
        return Controlbox::getCitiesList($country,$sid,$cid);
    }
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCitiesListPickup($state)
    {
        return Controlbox::getCitiesListPickup($state);
    }
    
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUsersorderscount($user)
    {
        return Controlbox::getUsersorderscount($user);
    }
    
     
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDialCodeList($user)
    {
        return Controlbox::getDialCodeList($user);
    }
  
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getOrdersList($user)
    {
        return Controlbox::getOrdersList($user);
    }
    
    
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getOrdersPendingList($user)
    {
        return Controlbox::getOrdersPendingList($user);
        
    }
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getAdditionalUsersDetails($user)
    {
        return Controlbox::getAdditionalUsersDetails($user);
    }
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getAdditionalUsersSelect($user)
    {
        return Controlbox::getAdditionalUsersSelect($user);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getTicketnumber($user)
    {
        return Controlbox::getTicketnumber($user);
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUserTickets($user)
    {
        return Controlbox::getUserTickets($user);
    }
    


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getOrdersHistoryList($user,$status,$purchasetype)
    {
        return Controlbox::getOrdersHistoryList($user,$status,$purchasetype);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getInvertoryPurchasesList($user)
    {
        return Controlbox::getInvertoryPurchasesList($user);
    }



    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getInvoicedetailsList($user)
    {
        return Controlbox::getInvoicedetailsList($user);
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getBindShipingAddress($user,$billform)
    {
        return Controlbox::getBindShipingAddress($user,$billform);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShippmentDhlDetails($user,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue,$weight,$dtunit,$length,$width,$height,$consignee)
    {
        return Controlbox::getShippmentDhlDetails($user,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue,$weight,$dtunit,$length,$width,$height,$consignee);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShippmentDetails($user,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue,$bustype,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$dvalueStr,$shipmentCost,$destCnt,$rateType)
    {
        return Controlbox::getShippmentDetails($user,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue,$bustype,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$dvalueStr,$shipmentCost,$destCnt,$rateType);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShippment2Details($user,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue)
    {
        return Controlbox::getShippment2Details($user,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShippmentDetailsValues($munits,$shiptype,$service,$source,$destination)
    {
        return Controlbox::getShippmentDetailsValues($munits,$shiptype,$service,$source,$destination);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getMomentoLog($billform)
    {
        return Controlbox::getMomentoLog($billform);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDeleteOrder($id)
    {
        return Controlbox::getDeleteOrder($id);
    }



    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUpdatePurchaseDetails($id)
    {
        return Controlbox::getUpdatePurchaseDetails($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShipDetails($id)
    {
        return Controlbox::getShipDetails($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShopperassistList($id)
    {
        return Controlbox::getShopperassistList($id);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDeleteShopper($id)
    {
        return Controlbox::getDeleteShopper($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getQuotationShipmentsList($id)
    {
        return Controlbox::getQuotationShipmentsList($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPickupFieldviewsList($id)
    {
        return Controlbox::getPickupFieldviewsList($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getQuotationFieldviewsList($id)
    {
        return Controlbox::getQuotationFieldviewsList($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPackageDetails($id)
    {
        return Controlbox::getPackageDetails($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculationShipping($userid,$units,$shiptype,$sertype,$source,$dt,$lg,$wd,$hi,$qty,$gwt,$wtunits,$bustype)
    {
        return Controlbox::getCalculationShipping($userid,$units,$shiptype,$sertype,$source,$dt,$lg,$wd,$hi,$qty,$gwt,$wtunits,$bustype);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculatorMesurements($units,$shiptype,$sertype,$source,$dt,$lg,$wd,$hi,$qty,$gwt,$wtunits)
    {
        return Controlbox::getCalculatorMesurements($units,$shiptype,$sertype,$source,$dt,$lg,$wd,$hi,$qty,$gwt,$wtunits);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPickupOderShippernameId($id)
    {
        return Controlbox::getPickupOderShippernameId($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPickupOderConsigneeId($id)
    {
        return Controlbox::getPickupOderConsigneeId($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPickupOderThirdpartyId($id)
    {
        return Controlbox::getPickupOderThirdpartyId($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCaluclatorFieldviewsList($id)
    {
        return Controlbox::getCaluclatorFieldviewsList($id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculatingMesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric)
    {
        return Controlbox::getCalculatingMesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric);
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculating2Mesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric)
    {
        return Controlbox::getCalculating2Mesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDocumentListAjax($id)
    {
        return Controlbox::getDocumentListAjax($id);
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDocumentList($id)
    {
        return Controlbox::getDocumentList($id);
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPreAlertsList($id)
    {
        return Controlbox::getPreAlertsList($id);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function deleteDownloadfile($cid,$id)
    {
        return Controlbox::deleteDownloadfile($cid,$id);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getExistTracking($cid)
    {
        return Controlbox::getExistTracking($cid);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPickupOderInfo($userid,$cid)
    {
        return Controlbox::getPickupOderInfo($userid,$cid);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getQuotationProcess($userid)
    {
        return Controlbox::getQuotationProcess($userid);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduserconsigInfo()
    {
        return Controlbox::getadduserconsigInfo();
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getaddusertypeInfo($user,$usertype)
    {
        return Controlbox::getaddusertypeInfo($user,$usertype);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduserfieldsInfo($userid,$sid)
    {
        return Controlbox::getadduserfieldsInfo($userid,$sid);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduserdeleteInfo($deleteadduserid)
    {
        return Controlbox::getadduserdeleteInfo($deleteadduserid);
    }    



    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduserpay($CustId,$gettypeuser,$getid,$getfname,$getlname,$getcountry,$getstate,$getcity,$getzip,$getaddress,$getaddress2,$getemail,$idtypeTxt,$idvalueTxt)
    {
        return Controlbox::getadduserpay($CustId,$gettypeuser,$getid,$getfname,$getlname,$getcountry,$getstate,$getcity,$getzip,$getaddress,$getaddress2,$getemail,$idtypeTxt,$idvalueTxt);
    }    

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduserselectpay($CustId)
    {
        return Controlbox::getAdditionalUsersSelect($CustId);
    }    

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function submitpayshopperassist($CustId,$amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$txtSpecialInStr,$txtTaxesStr,$txtShippChargesStr,$ItemIdsStr,$ItemQuantityStr,$ItemSupplierIdStr)
    {
        return Controlbox::submitpayshopperassist($CustId,$amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$txtSpecialInStr,$txtTaxesStr,$txtShippChargesStr,$ItemIdsStr,$ItemQuantityStr,$ItemSupplierIdStr);
    }    

   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getcitiesInfo($cid,$sid,$citid)
    {
        return Controlbox::getcitiesInfo($cid,$sid,$citid);
    }

    
   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCitiesListOne($country,$sid,$cid)
    {
        return Controlbox::getCitiesListOne($country,$sid,$cid);
    }
   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getFnameDetails($userids,$fnameid,$lnameid)
    {
        return Controlbox::getFnameDetails($userids,$fnameid,$lnameid);
    }

   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCodorders($userids)
    {
        return Controlbox::getCodorders($userids);
    }


   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getOrdersHoldList($userids)
    {
        return Controlbox::getOrdersHoldList($userids);
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getpaypalcharge($userids)
    {
        return Controlbox::getpaypalcharge($userids);
    }
    
      /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getsquarecharge($userids)
    {
        return Controlbox::getsquarecharge($userids);
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getconvcharge($userids,$gateway)
    {
        return Controlbox::getconvcharge($userids,$gateway);
    }


   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getServiceList()
    {
        return Controlbox::getServiceList();
    }

/**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculatingnewMesurements($getcalculatingtype,$munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric,$state,$city,$zip,$address,$dstate,$dcity,$dzip,$daddress)
    {
        return Controlbox::getCalculatingnewMesurements($getcalculatingtype,$munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric,$state,$city,$zip,$address,$dstate,$dcity,$dzip,$daddress);
    }

   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalcudetailsforus($r)
    {
        return Controlbox::getCalcudetailsforus($r);
    }
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getInvoicedetailsId($ci)
    {
        return Controlbox::getInvoicedetailsId($ci);
    }    


 /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getAdditionalServiceList()
    {
        return Controlbox::getAdditionalServiceList();
    }   
    
   
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function updateBranchAddress($branch,$custId)
    {
        return Controlbox::updateBranchAddress($branch,$custId);
    }   
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getexistProjectname($ci)
    {
        return Controlbox::getexistProjectname($ci);
    }  
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getexistFnsku($ci)
    {
        return Controlbox::getExistFnsku($ci);
    }    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getprojetdetails($CustId,$ci)
    {
        return Controlbox::getprojetdetails($CustId,$ci);
    }  

 /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDeleteproject($ci)
    {
        return Controlbox::getDeleteproject($ci);
    }    
    
     
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getallProjectdetails($CustId,$ci)
    {
        return Controlbox::getallProjectdetails($CustId,$ci);
    }    

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getServiceType($src,$dest,$shiptype)
    {
        return Controlbox::getServiceType($src,$dest,$shiptype);
    }    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getServiceTypeCalc($src,$dest,$shiptype)
    {
        return Controlbox::getServiceTypeCalc($src,$dest,$shiptype);
    }    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function dynamicpages()
    {
        return Controlbox::dynamicpages();
    }    

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getExistTrackingTicket($trackingid,$user)
    {
        return Controlbox::getExistTrackingTicket($trackingid,$user);
    }    

 /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function GetInvoicesCount($user,$invoiceType)
    {
        return Controlbox::GetInvoicesCount($user,$invoiceType);
    }    





         
}
