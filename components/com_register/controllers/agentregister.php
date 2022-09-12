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
jimport('joomla.application.component.controller');

jimport('joomla.user.helper');

/**
 * Register controller class.
 *
 * @since  1.6
 */
class RegisterControllerAgentregister extends JControllerLegacy
{

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function login()
	{
	    $app = JFactory::getApplication();
        $uname = JRequest::getVar('unameTxt', '', 'post');
        $paswd = JRequest::getVar('passwordTxt', '', 'post');
        $itemId = JRequest::getVar('itemid', '', 'post');
       
        if($uname!="" &&  $paswd!=""){
           require_once JPATH_ROOT.'/components/com_register/helpers/register.php';
           $status=RegisterHelpersRegister::getLogin($uname,$paswd);
        }
        if($status){
            $session = JFactory::getSession();
			$user_casillero_id  =  strval($status);
			$user_casillero_password  =  strval($paswd);
			// for storing the user data in session variables
			$session->set('user_casillero_id', $user_casillero_id);
			$session->set('user_casillero_password', $user_casillero_password);
	
            $app->enqueueMessage(JText::_('YOUR_ARE_SUCCESSFULLY_LOGINED'), 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user', false));
	    	//$this->setRedirect(JRoute::_('index.php?option=com_myaccount&view=ptofile&layout=edit', false));
        }else{
            $app->enqueueMessage(JText::_('YOUR_USERNAME_OR_PASSWORD_IS_WRONGLY_INSERTED'), 'error');
	    	// Redirect to the edit screen.
		    $this->setRedirect(JRoute::_('index.php?option=com_register&view=login', false));
        }
    }
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function forgotpassword()
	{
		
		$app = JFactory::getApplication();
        $url = JRequest::getVar('url', '', 'post');
        $uname = JRequest::getVar('unameTxt', '', 'post');
        $itemId= JRequest::getVar('itemid', '', 'post');
        if($uname!=""){
           require_once JPATH_ROOT.'/components/com_register/helpers/register.php';
           $status=RegisterHelpersRegister::getForgetpassword($uname);
           
        }
        if($status==0){
            $app->enqueueMessage(JText::_('YOUR_USERNAME_IS_WRONGLY_INSERTED'), 'error');
		    $this->setRedirect(JRoute::_($url, false));
        }else{
            /*
            // Set the e-mail parameters
    		$sitename 		= $app->getCfg('sitename');
    		$from 		= $app->getCfg('mailfrom');
    		$fromname 		= $app->getCfg('fromname');
    		$siteURL		= JURI::base();
    		//--------------Blocked --------------------			
    		//$subject	= JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $sitename);
    		//$body		= JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $sitename, $token, $url);
    		//-------------- End Blocked --------------------
    		//---------------StartAdded------------------------------------------
    		$db = &JFactory::getDBO();
    		$query="Select * FROM #__angkor_emails Where code='PASSWORD_RESET_CONFIRMATION'";
    		$db->setQuery( $query );
    		$rows = $db->loadObjectList();
    		
    		$db_user= &JFactory::getDBO();		
    		$patterns=array(	
    						'/{array_alias_id}/',
    						'/{array_password}/',
    						'/{addressname}/',
    						'/{address}/',
    						'/{siteurl}/'
    					);
    		$replacements_subject=array(	
    							$array_alias_id,
    							$token,
    							$array_addressname,	
    							$array_address,
    							$url
    					);
    		$replacements_body=array(
    							$array_alias_id,
    							$token,
    							$array_addressname,
    							$array_address,
    							htmlLink($url)
    					);
    		foreach($rows as $row)
    		{
    			$subject	=preg_replace($patterns,$replacements_subject,$row->subject);
    			$body	=preg_replace($patterns,$replacements_body,$row->body);			
    		}
    
    	    $getsendMethodMail = sendHTMLMail($from, $fromname, $email, $subject, $body,1); 
    		//---------------End Added------------------------------------------
    		// Send the e-mail
    		if (!$getsendMethodMail)
    		{
    			$this->setError('ERROR_SENDING_CONFIRMATION_EMAIL');
    			return false;
    		}*/
            $app->enqueueMessage(JText::_('PLEASE_CHECK_FOR_YOUR_EMAIL_FOR_PASSWORD'), 'notice');
		    $this->setRedirect(JRoute::_($url, false));
        }
    }
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function save()
	{
    	$this->arr_userdata = array();
    	$postdata = array();
    	$session = JFactory::getSession();
		
    	$app = JFactory::getApplication();
		require_once JPATH_ROOT.'/components/com_register/classes/controlbox.php';
        $agentname=JRequest::getVar('agentidTxt','','post');
        $fname=JRequest::getVar('fnameTxt','','post');
        $lname=JRequest::getVar('lnameTxt','','post');
        $addressone=JRequest::getVar('addressTxt','','post');
        $addresstwo=JRequest::getVar('address2Txt','','post');
        $pin=JRequest::getVar('zipTxt','','post');
        $phone=JRequest::getVar('phoneTxt');
        $acctype=JRequest::getVar('accounttypeTxt','','post');
        $email=JRequest::getVar('emailTxt','','post');
        $dialcode=JRequest::getVar('dialcodeTxt','','post');
        $country=JRequest::getVar('countryTxt','','post');
        $country=explode(":",$country);
        $state=JRequest::getVar('stateTxt','','post');
        $city=JRequest::getVar('cityTxtdiv','','post');
        $password=JRequest::getVar('passwordTxt','','post');
        $regView= Controlbox::setAgentRegister($fname,$lname,$addressone,$addresstwo,$pin,$phone,$acctype,$email,$dialcode,$country[0],$state,$city,$password,$agentname);
        //echo $regView->Data->Id.'---'.$regView->Data->CustId;exit;
        if($regView){
    		// Get the model.
    		//$model = $this->getModel('Agentregister', 'AgentregisterModel');
             $model = &$this->getModel('Agentregister');
            //var_dump($model);exit;
            //$this->arr_userdata= $model->save($regView->Data->CustId,$regView->Data->Id);
            /*if(!empty($this->arr_userdata) && $this->arr_userdata == "datanotsaved")
            {
                $app->enqueueMessage(JText::_('PLEASE_CHECK_FOR_YOUR_EMAIL_FOR_PASSWORD'), 'error');
            }else{
                
                //$this->_sendMail($arr_userdata['email'],$regView->Data->UserName,$regView->Data->Password,$regView->Data->CustId,$regView->Data->Address1,$regView->Data->Address2,$regView->Data->City,$regView->Data->State,$regView->Data->Country,$regView->Data->PostalCode);
    			$user_casillero_id  =  strval($regView->Data->CustId);
    			
    			$user_casillero_password  =  strval($regView->Data->Password);
    
    			//$sms_message  =  strval($this->arr_userdata['smsfailureresult']);
    			
    			// for storing the user data in session variables
    			$session->set('user_casillero_id', $user_casillero_id);
    			
    			$session->set('user_casillero_password', $user_casillero_password);
    		*/	
    
    			//$session->set('sms_message', $sms_message);
                $app->enqueueMessage(JText::_($regView->Description), 'notice');

     		    //$this->setRedirect(JRoute::_('index.php?option=com_register&view=register&msg=success', false));
                
            //}
            
            
        }else{
            
            $app->enqueueMessage(JText::_('WEBSERVICES_ISSUE'), 'error');
            
        }
       
        $view = $this->getView('agentregister','html');
        $view->Response = $regView;
        $view->display();

    
        
		/*$this->setRedirect(JRoute::_('index.php?option=com_register&view=register', false));
		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_register.edit.register.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_register.edit.register.id', $editId);

		// Get the model.
		$model = $this->getModel('Register', 'RegisterModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId && $previousId !== $editId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user', false));*/
	}


	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function edit()
	{
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_register.edit.register.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_register.edit.register.id', $editId);

		// Get the model.
		$model = $this->getModel('Register', 'RegisterModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId && $previousId !== $editId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_register&view=registerform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 *
	 * @throws Exception
	 * @since    1.6
	 */
	public function publish()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Checking if the user can remove object
		$user = JFactory::getUser();

		if ($user->authorise('core.edit', 'com_register') || $user->authorise('core.edit.state', 'com_register'))
		{
			$model = $this->getModel('Register', 'RegisterModel');

			// Get the user data.
			$id    = $app->input->getInt('id');
			$state = $app->input->getInt('state');

			// Attempt to save the data.
			$return = $model->publish($id, $state);

			// Check for errors.
			if ($return === false)
			{
				$this->setMessage(JText::sprintf('Save failed: %s', $model->getError()), 'warning');
			}

			// Clear the profile id from the session.
			$app->setUserState('com_register.edit.register.id', null);

			// Flush the data from the session.
			$app->setUserState('com_register.edit.register.data', null);

			// Redirect to the list screen.
			$this->setMessage(JText::_('COM_REGISTER_ITEM_SAVED_SUCCESSFULLY'));
			$menu = JFactory::getApplication()->getMenu();
			$item = $menu->getActive();

			if (!$item)
			{
				// If there isn't any menu item active, redirect to list view
				$this->setRedirect(JRoute::_('index.php?option=com_register&view=registers', false));
			}
			else
			{
                $this->setRedirect(JRoute::_('index.php?Itemid='. $item->id, false));
			}
		}
		else
		{
			throw new Exception(500);
		}
	}

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function remove()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Checking if the user can remove object
		$user = JFactory::getUser();

		if ($user->authorise('core.delete', 'com_register'))
		{
			$model = $this->getModel('Register', 'RegisterModel');

			// Get the user data.
			$id = $app->input->getInt('id', 0);

			// Attempt to save the data.
			$return = $model->delete($id);

			// Check for errors.
			if ($return === false)
			{
				$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			}
			else
			{
				// Check in the profile.
				if ($return)
				{
					$model->checkin($return);
				}

                $app->setUserState('com_register.edit.inventory.id', null);
                $app->setUserState('com_register.edit.inventory.data', null);

                $app->enqueueMessage(JText::_('COM_REGISTER_ITEM_DELETED_SUCCESSFULLY'), 'success');
                $app->redirect(JRoute::_('index.php?option=com_register&view=registers', false));
			}

			// Redirect to the list screen.
			$menu = JFactory::getApplication()->getMenu();
			$item = $menu->getActive();
			$this->setRedirect(JRoute::_($item->link, false));
		}
		else
		{
			throw new Exception(500);
		}
	}

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function get_ajax_data()
	{
        require_once JPATH_ROOT.'/components/com_register/helpers/register.php';
        $countryid = JRequest::getVar('countryid', '', 'get');
        $stateflag = JRequest::getVar('stateflag', '', 'get');
        if($countryid!="" &&  $stateflag!=""){
            echo RegisterHelpersRegister::getStatesList($countryid);
            exit;
        }
        $stateid = JRequest::getVar('stateid', '', 'get');
        $cityflag = JRequest::getVar('cityflag', '', 'get');
        if($stateid!="" &&  $cityflag!=""){
            echo RegisterHelpersRegister::getCitiesList($stateid);
            exit;
        }
        $emailid = JRequest::getVar('emailTxt', '', 'get');
        $emailflag = JRequest::getVar('emailflag', '', 'get');
        if($emailid!="" &&  $emailflag!=""){
            $result=RegisterHelpersRegister::getEmailExist($emailid);
            if($result==1){
                echo "true";
            }
            exit;
        }
	}	



	/**
	* Email Sending Method 
	*
	* @access		  public
	*
	* @param string   $name
	* @param string   $email
	* @param string   $username
	* @param string   $activation
	* @param string   $password
	* @param string   $casilleroalias
	*
	*/
	function _sendMail($strEmail,$strusername,$strpassword,$strcustid,$straddressone,$straddresstwo,$strcity,$strstate,$strcountry,$strpostalcode)
	{
		$mainframe =& JFactory::getApplication();
		
		$db		=& JFactory::getDBO();
		//$strEmail='madan.chunchu@iblesoft.com';
		$sitename 		= $mainframe->getCfg('sitename');
		
		$mailfrom 		= $mainframe->getCfg('mailfrom');
		
		$fromname 		= $mainframe->getCfg('fromname');
		
		$siteURL		= JURI::base();
				
		$query = "Select * FROM #__angkor_emails Where code='SEND_MSG_ACTIVATE'";
		//$activationURL = $siteURL."index.php?option=com_registration&task=activate&activation=".$strActivation;
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();		
		
		$patterns = array('/{wp_username}/', '/{wp_custid}/', '/{wp_password}/', '/{wp_addressone}/',
		'/{sitename}/', '/{activationurl}/', '/{wp_addresstwo}/', '/{wp_city}/',
		'/{wp_state}/', '/{wp_country}/', '/{wp_zip}/'
		);
		
		$replacements_subject = array($strusername, $strcustid, $strpassword,$straddressone,		
		$sitename, htmlLink($siteURL), $straddresstwo, $strcity,
		$strstate, $strcountry,$strpostalcode);
		
		$replacements_body = array($strusername, $strcustid, $strpassword,$straddressone,		
		$sitename, htmlLink($siteURL),$straddresstwo, $strcity,
		$strstate, $strcountry,$strpostalcode); 
		
		foreach($rows as $row)
		{			
			$subject 	= preg_replace($patterns, $replacements_subject, $row->subject);
			
			$subject 	= html_entity_decode($subject, ENT_QUOTES);
			
			$message 	= preg_replace($patterns, $replacements_body, $row->body);
			
			$message	= html_entity_decode($message, ENT_QUOTES);
			
			$sender_name = $row->sender_name;
			
			$sender_name = html_entity_decode($strusername, ENT_QUOTES);
			
			$sender_email = $row->sender_email;
			
			$sender_email = html_entity_decode($strEmail, ENT_QUOTES);
		}
		//-----------------------------End Added-----------------------------
		
		$fromname = $sender_name;
		
		$mailfrom = $sender_email;

		sendHTMLMail($mailfrom, $fromname,$strEmail, $subject, $message, 1);
		

	}	
    
    
}
	/**
	* returns a html link of the url
	*
	* @access		  public
	*
	* @param string   $url
	*
	*/ 
	
	function htmlLink($url)
	{
		return "<a href='$url'>$url</a>";
	}
	
	/**
	* Html Email Sending Method 
	*
	* @access		  public
	*
	* @param string   $from
	* @param string   $fromname
	* @param string   $recipient
	* @param string   $subject
	* @param string   $body
	* @param string   $mode
	* @param string   $cc
	* @param string   $bcc
	* @param string   $attachment
	* @param string   $replyto
	* @param string   $replytoname
	*
	*/
	
	function sendHTMLMail($from, $fromname, $recipient, $subject, $body, $mode = 0, $cc = null, $bcc = null, $attachment = null, $replyto = null, $replytoname = null)
	{
		// Get a JMail instance
		$mail =& JFactory::getMailer();
		
		$mail->CharSet='utf-8';
		
		$mail->setSender(array($from, $fromname));
		
		$mail->setSubject($subject);
		
		$mail->setBody($body);
	
		// Are we sending the email as HTML?
		if ($mode) {
			
			$mail->IsHTML(true);
			
			$mail->MsgHTML($body);
		}
	
		$mail->addRecipient($recipient);
		
		$mail->addCC($cc);
		
		$mail->addBCC($bcc);
		
		$mail->addAttachment($attachment);
	
		// Take care of reply email addresses
		if(is_array($replyto)) 
		{
			$numReplyTo = count($replyto);
			
			for ($i=0; $i < $numReplyTo; $i++)
			{
				$mail->addReplyTo(array($replyto[$i], $replytoname[$i]));
			}
			
		} 
		else if(isset($replyto)) 
		{
			$mail->addReplyTo(array($replyto, $replytoname));
		}
	
		return  $mail->Send();
	}
