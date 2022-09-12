<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Register
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;


// Import Joomla! libraries
jimport('joomla.application.component.model');

jimport('joomla.user.helper');

include_once JPATH_ROOT.'/components/com_register/tables/usersnew.php';


jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
 * Register model.
 *
 * @since  1.6
 */
class RegisterModelRegister extends JModelItem
{
    public $_item;

        
    
        
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since    1.6
	 *
	 */
	protected function populateState()
	{
		$app  = Factory::getApplication('com_register');
		$user = Factory::getUser();

		// Check published state
		if ((!$user->authorise('core.edit.state', 'com_register')) && (!$user->authorise('core.edit', 'com_register')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		// Load state from the request userState on edit or from the passed variable on default
		if (Factory::getApplication()->input->get('layout') == 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_register.edit.register.id');
		}
		else
		{
			$id = Factory::getApplication()->input->get('id');
			Factory::getApplication()->setUserState('com_register.edit.register.id', $id);
		}

		$this->setState('register.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('register.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer $id The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
     *
     * @throws Exception
	 */
	public function getItem($id = null)
	{
            if ($this->_item === null)
            {
                $this->_item = false;

                if (empty($id))
                {
                    $id = $this->getState('register.id');
                }

                // Get a level row instance.
                $table = $this->getTable();

                // Attempt to load the row.
                if ($table->load($id))
                {
                    

                    // Check published state.
                    if ($published = $this->getState('filter.published'))
                    {
                        if (isset($table->state) && $table->state != $published)
                        {
                            throw new Exception(JText::_('COM_REGISTER_ITEM_NOT_LOADED'), 403);
                        }
                    }

                    // Convert the JTable to a clean JObject.
                    $properties  = $table->getProperties(1);
                    $this->_item = ArrayHelper::toObject($properties, 'JObject');

                    
                } 
            }
        
            

            return $this->_item;
        }

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string $type   Name of the JTable class to get an instance of.
	 * @param   string $prefix Prefix for the table class name. Optional.
	 * @param   array  $config Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Register', $prefix = 'RegisterTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_register/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string $alias Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
            $table      = $this->getTable();
            $properties = $table->getProperties();
            $result     = null;

            if (key_exists('alias', $properties))
            {
                $table->load(array('alias' => $alias));
                $result = $table->id;
            }
            
                return $result;
            
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('register.id');
                
		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
                
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('register.id');

                
		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = Factory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
                
	}

	/**
	 * Publish the element
	 *
	 * @param   int $id    Item id
	 * @param   int $state Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
                
		$table->load($id);
		$table->state = $state;

		return $table->store();
                
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int $id Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

                
                    return $table->delete($id);
	}                

	/**
	 * Method to delete an item
	 *
	 * @param   int $id Element id
	 *
	 * @return  bool
	 */
	public function save($username,$webserviceid)
	{
		$date =& JFactory::getDate();
		
		$arr_userdata = array();
		
		$params = &JComponentHelper::getParams('com_register');
	    
	    $db =& JFactory::getDBO();
	    
	    $app =& JFactory::getApplication();


		$arr_gid = $this->getUsersGroupId('Registered');
		
		
		$gid = $arr_gid[0]['id'];

		$strPassword = JRequest::getString('passwordTxt', '', 'post', JREQUEST_ALLOWRAW);
	

		setcookie('userpassword', $strPassword);
		
		$_COOKIE['userpassword'] =  $strPassword;
		
		$strOriginalPassword = $strPassword;
		
		$strPassword = preg_replace('/[\x00-\x1F\x7F]/', '', $strPassword); //Disallow control chars in the email
		

		// crypting the password		
		$salt  = JUserHelper::genRandomPassword(32);
		
		$crypt = JUserHelper::getCryptedPassword($strPassword, $salt);
		
		$strPassword = $crypt.':'.$salt;		

		//$activation = JUtility::getHash(JUserHelper::genRandomPassword(32));
		$activation = JUserHelper::genRandomPassword(32);

		$this->post_users_data['id'] = JRequest::getVar('id');
		
		$this->post_users_data['name'] = JRequest::getVar('fnameTxt')." ".JRequest::getVar('lnameTxt');
		
		$this->post_users_data['username'] = $username;
		
		$this->post_users_data['email'] = JRequest::getVar('emailTxt');

		$this->post_users_data['password'] = $strPassword;


		$this->post_users_data['password'] = $strPassword;

		$this->post_users_data['usertype'] = '';
		
		$this->post_users_data['block'] = $block;
		
		$this->post_users_data['sendEmail'] = '';
		
		//$this->post_users_data['gid'] = $gid;
		
		$this->post_users_data['registerDate'] = $date;
		
		$this->post_users_data['lastvisitDate'] = '';
		
		$this->post_users_data['activation'] = $activation;
		
		$this->post_users_data['params'] = '';
		
        $this->post_users_data['webserviceid'] = $webserviceid;

		//var_dump($params);exit;

		// Initialize new usertype setting
		$newUsertype = 'Registered';
		
		

		$arr_userdata['name'] = $this->post_users_data['name'];
		
		$arr_userdata['email'] = $this->post_users_data['email'];
		
		$arr_userdata['username'] = $this->post_users_data['username'];
		
		$arr_userdata['activation'] =$activation;
		
		$arr_userdata['password'] = $strOriginalPassword;
		
		$arr_userdata['mobile'] = $smsActivationMobile;
		
		
		
		$usersnewrow = new TableUsersnew($db);
		
		if (!$usersnewrow->bind($this->post_users_data)) 
		{
			$intUserDataBindFlag = 0;
		}
		else
		{
			$intUserDataBindFlag = 1;
		} 
		
				
		if(!$usersnewrow->store())
		{
			$intUserDataSavedFlag = 0;
		}
		else
		{
			$intUserDataSavedFlag = 1;
		}
		
		
		
		
		// if there is problem while saving the data 
		if($intUserDataBindFlag<=0 || $intUserDataSavedFlag<=0)
		{
			return "datanotsaved";
		}		
				
		
		// in insert mode
		if(!empty($this->post_users_data['id']))
		{
			$lastinsertuserid = $this->post_users_data['id'];		
		}
		// in edit mode
		else
		{
			$lastinsertuserid = $db->insertid();
		} 
				
		$intUserId = $lastinsertuserid;
		
		$strSection = 'users';
		
		$intGroupId = $gid;
		// for storing the data in #__core_acl_groups_aro_map table		
		$this->addcoreAclGroupsAroMapNew($intGroupId, $strSection, $intUserId);
				


		// if there is problem while saving the data 
		if($intUserDataBindFlag<=0 || $intUserDataSavedFlag<=0)
		{
			// delete the other data 
			$this->deleteUsersData($intUserId);
			
			//$this->deleteCoreAclGroupsAroMap($intGroupId, $strSection, $intAroId);
			$this->deleteCoreAclGroupsAroMapNew($intGroupId, $strSection, $intUserId);
			
			return "datanotsaved";
		} 
				

		return $arr_userdata;		


	}



	/**
	* deletes the data from  #__core_acl_groups_aro_map table
	*
	* @access		 public
	*
	* @param int  	 $groupid
	* @param string  $section
	* @param int	 $aroid	
	*
	*/
	
	function deleteCoreAclGroupsAroMapNew($intGroupId, $strSection, $intUserId) {
	
		$num_rows = 0;		
		
		$db =& JFactory::getDBO();
		
		if(!empty($intAroId) && $intAroId>0)
		{
		
			$query	= "SELECT * FROM #__user_usergroup_map "
					. "WHERE  group_id='".$intGroupId."'  and user_id='" .$intUserId."'";  
						
			$db->setQuery($query);
			
			$db->query();
			
			$num_rows = $db->getNumRows();
			
			// if the row already exists
			if($num_rows>0)
			{
				// There are records so delete
				$query = 'DELETE FROM #__user_usergroup_map WHERE user_id=' .$intUserId;
				
				$db->setQuery($query);
				
				if (!$db->query()) {
					JError::raiseError(500, $db->stderr());
				}
			
			}
			else
			{
				return false;
			}
		
		}
		
	}

	/**
	* deletes the data from  #__users table
	*
	* @access		 public
	*
	* @param int  	 $userid
	*
	*/
	
	function deleteUsersData($intUserId) {
	
		$num_rows = 0;		
		
		$db =& JFactory::getDBO();
		
		if(!empty($intUserId) && $intUserId>0)
		{
		
			$query	= "SELECT * FROM #__users "
					. "WHERE  id='" .$intUserId."'";  
						
			$db->setQuery($query);
			
			$db->query();
			
			$num_rows = $db->getNumRows();
			
			// if the row exists
			if($num_rows>0)
			{
				// There are records so delete
				$query = 'DELETE FROM #__users WHERE id=' .$intUserId;
				
				$db->setQuery($query);
				
				if (!$db->query()) 
				{
					JError::raiseError(500, $db->stderr());
				}
			
			}
			else
			{
				return false;
			}
		
		}
	
	}
	/**
	* inserts the data in  #__core_acl_groups_aro_map table
	*
	* @access		 public
	*
	* @param int  	 $groupid
	* @param string  $section
	* @param int	 $aroid	
	*
	*/
	
	function addcoreAclGroupsAroMapNew($intGroupId, $strSection, $intUserId) {
	
		$num_rows = 0;		
		
		$db =& JFactory::getDBO();
				
		$query	= "SELECT * FROM #__user_usergroup_map "
				. "WHERE  group_id='".$intGroupId."'  and user_id='" .$intUserId."'";  
			
		$db->setQuery($query);
		
		$db->query();
		
		$num_rows = $db->getNumRows();
		
		// if the row already exists
		if($num_rows>0)
		{
			return false;
		}
		else
		{
			// There are no records so insert
			$query = 'INSERT INTO #__user_usergroup_map (group_id, user_id)' .
					 ' VALUES ('.$intGroupId.', '.$intUserId.')';
			
			$db->setQuery($query);
			
			if (!$db->query()) 
			{
				JError::raiseError(500, $db->stderr());
			}
			
			$intCoreAclGroupsAroMapId =	$db->insertid();
			
			return $intCoreAclGroupsAroMapId;
		}	
	
	}	
	
	
	/**
	* returns the data from  #__users table
	*
	* @access		 public
	*
	* @param int  	 $userid
	*
	*/
	
	function getUsersGroupId($groupName) {
	
		$num_rows = 0;		
		
		$db =& JFactory::getDBO();
		
		$query	= "SELECT * FROM #__usergroups "
				. "WHERE  title='".$groupName."'";  
		
		$db->setQuery($query);
		
		$db->query();
		
		$num_rows = $db->getNumRows();
		
		$row = $db->loadAssocList();
		
		return $row; 
	}
	
	
}
