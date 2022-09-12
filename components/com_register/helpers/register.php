<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Register
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('RegisterHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_register' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'register.php');
include_once JPATH_ROOT.'/components/com_register/classes/controlbox.php';


/**
 * Class RegisterFrontendHelper
 *
 * @since  1.6
 */
class RegisterHelpersRegister
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
		if (file_exists(JPATH_SITE . '/components/com_register/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_register/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'RegisterModel');
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

        if ($user->authorise('core.edit', 'com_register'))
        {
            $permission = true;
        }
        else
        {
            if (isset($item->created_by))
            {
                if ($user->authorise('core.edit.own', 'com_register') && $item->created_by == $user->id)
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
    public static function getdialcodeList()
    {
        return Controlbox::getdialcodeList();
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
    public static function getStatesList($cid)
    {
        return Controlbox::getStatesList($cid);
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
        return Controlbox::getCitiesList($cid);
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
        return Controlbox::getAgentId();
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
        return Controlbox::getLogin($user,$pass);
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
        return Controlbox::getEmailExist($email);
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
        return Controlbox::getForgetpassword($user,$domainurl);
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
        return Controlbox::getidentityList();
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
        return Controlbox::getIdExist($idfil,$idvalue);
    }

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getHubStatesList($c,$s)
    {
        return Controlbox::getHubStatesList($c,$s);
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
        return Controlbox::getmainpagedetails();
    }       
   
   
     /**
     * Gets Account Types
     */
    public static function getaccounttype()
    {
        return Controlbox::getaccounttype();
    }     


    
}
