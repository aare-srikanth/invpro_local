<?php

/** 
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/components/com_userprofile/classes/controlbox.php';

/**
 * User controller class.
 *
 * @since  1.6
 */
class UserprofileControllerUser extends JControllerLegacy
{
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
		$previousId = (int) $app->getUserState('com_userprofile.edit.user.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_userprofile.edit.user.id', $editId);

		// Get the model.
		$model = $this->getModel('User', 'UserprofileModel');

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
		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=userform&layout=edit', false));
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

		if ($user->authorise('core.edit', 'com_userprofile') || $user->authorise('core.edit.state', 'com_userprofile'))
		{
			$model = $this->getModel('User', 'UserprofileModel');

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
			$app->setUserState('com_userprofile.edit.user.id', null);

			// Flush the data from the session.
			$app->setUserState('com_userprofile.edit.user.data', null);

			// Redirect to the list screen.
			$this->setMessage(JText::_('COM_USERPROFILE_ITEM_SAVED_SUCCESSFULLY'));
			$menu = JFactory::getApplication()->getMenu();
			$item = $menu->getActive();

			if (!$item)
			{
				// If there isn't any menu item active, redirect to list view
				$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=users', false));
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

		if ($user->authorise('core.delete', 'com_userprofile'))
		{
			$model = $this->getModel('User', 'UserprofileModel');

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

                $app->setUserState('com_userprofile.edit.inventory.id', null);
                $app->setUserState('com_userprofile.edit.inventory.data', null);

                $app->enqueueMessage(JText::_('COM_USERPROFILE_ITEM_DELETED_SUCCESSFULLY'), 'success');
                $app->redirect(JRoute::_('index.php?option=com_userprofile&view=users', false));
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
	public function logout()
	{
	    jimport( 'joomla.session.session' );
	    $session = JFactory::getSession();
	    //unset($_SESSION['user_casillero_id']);
	    $session->clear('user_casillero_id');
	    $session->clear('userData');
	    
		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_register&view=login', false));
	}    
    
	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function changepassword()
	{
    	$app = JFactory::getApplication();
        $user = JRequest::getVar('user', '', 'post');
        $oldpassword = JRequest::getVar('passwordTxt', '', 'post');
        $password = JRequest::getVar('newpasswordTxt', '', 'post');
        if($password!=""){
          $status=Controlbox::getchangepassword($user,$oldpassword,$password);
        }
	    //$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=login', false));
	    //$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=changepassword', false));
        if($status->Response==1){
            
            if($status->Description== "Your passsword has been changed successfully"){
                    jimport( 'joomla.session.session' );
                    $session = JFactory::getSession();
                    //unset($_SESSION['user_casillero_id']);
                    $session->clear('user_casillero_id');
                    // Redirect to the edit screen.
                    $app->enqueueMessage(Jtext::_('COM_USERPROFILE_CHANGEPASS_SUCCESS'), 'notice');
                    $this->setRedirect(JRoute::_('index.php?option=com_register&view=login', false));
            }else{
                $app->enqueueMessage($status->Description, 'notice');
            }
                
        }else{
            $app->enqueueMessage($status->Description, 'error');
             $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=changepassword', false));
        }    
	   

	}    

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function newpassword()
	{

    	$app = JFactory::getApplication();
        $user = JRequest::getVar('user', '', 'post');
        $token = JRequest::getVar('resetToken', '', 'post');
        $password = JRequest::getVar('newpasswordTxt', '', 'post');
        $companyId = JRequest::getVar('companyId', '', 'post');
        if($password!=""){
          $status=Controlbox::getnewpassword($user,$token,$password,$companyId);
        }
        if($status==""){
            $app->enqueueMessage(Jtext::_('WEBSERVICE_ISSUE'), 'error');
		    //$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=login', false));
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=newpassword', false));
        }else{
            if($status->ResCode==1){
              $app->enqueueMessage('Your passsword has been created successfully', 'notice');
	          $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user', false));
            }else{
              $app->enqueueMessage($status->Msg, 'error');
    	      $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=newpassword', false));
            }
	    }    

	}    

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function userupdateprofile()
	{
	    $app = JFactory::getApplication();
	    $firstName = JRequest::getVar('firstName', '', 'post');
	    $lastName = JRequest::getVar('lastName', '', 'post');
        $CustId = JRequest::getVar('user', '', 'post');
        $DialCode = JRequest::getVar('dialcodeTxt', '', 'post');
        $PrimaryNumber = JRequest::getVar('phoneTxt', '', 'post');
        $AlternativeNumber = JRequest::getVar('anumberTxt', '', 'post');
        $Fax = JRequest::getVar('faxTxt', '', 'post');
        $PrimaryEmail = JRequest::getVar('emailTxt', '', 'post');
        $DialCodeOther = JRequest::getVar('dialcodealtTxt', '', 'post');
        $AlternativeEmail = JRequest::getVar('aemailTxt', '', 'post');
        $AddressAccounts = JRequest::getVar('addressTxt', '', 'post');
        $address2Txt = JRequest::getVar('address2Txt', '', 'post');
        
        $Country = JRequest::getVar('countryTxt', '', 'post');
        $State = JRequest::getVar('stateTxt', '', 'post');
        $cityTxtdiv = JRequest::getVar('cityTxtdiv', '', 'post');
        if($cityTxtdiv)
        {
            $City =$cityTxtdiv;
        }else{
            $City = JRequest::getVar('cityTxt', '', 'post');
        
        }
        
        $PostalCode = JRequest::getVar('zipTxt', '', 'post');
        $fileTxt = JRequest::getVar('fileTxt', '', 'post');
        jimport('joomla.filesystem.file');
        $TARGET=$this->GUIDv4();
        $photodest1='';
        $nameStr = "";
        $extStr = "";
        
        $profilepicFile = JRequest::getVar('profilepicTxt', null, 'files', 'array');
       
        if($profilepicFile["name"]){
            $profilepicname =JFile::makeSafe($profilepicFile['name']);
            $photodest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$profilepicname;
            $photodest1 = $TARGET.'/'.$profilepicname;
            JFile::upload($profilepicFile["tmp_name"], $photodest);
        }else{
            
            $photodest= JRequest::getVar('fileTxt', '', 'post');
            $photodest=str_replace(" ","%20",$photodest);
            $destArr=explode("/",$photodest);
            $profilepicname=JFile::makeSafe(end($destArr));
        }
        
       
        
        $image1 = file_get_contents($photodest);
        $imageByteStream = base64_encode($image1);
        
          //var_dump($image1);
        //var_dump($photodest);exit;
        
        
        $nameExtAry=explode(".",$profilepicname);
        $fileName = $nameExtAry[0];
        $fileExt = ".".$nameExtAry[1];
        
        
        
        if($CustId!=""){
           $status=Controlbox::changepersonalinformation($CustId,$firstName,$lastName,$DialCode,$PrimaryNumber, $AlternativeNumber,$Fax, $PrimaryEmail, $AlternativeEmail, $AddressAccounts, $Country, $State, $City,$PostalCode,$profilepicname,$imageByteStream,$fileName,$fileExt,$fileTxt,$DialCodeOther,$address2Txt);
        }
        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation', false));
        }else{
            
            if($status == "Successfully updated"){
                $app->enqueueMessage(Jtext::_('COM_USERPROFILE_PI_UPDATED_SUCCESSFULLY'), 'notice');
            }else{
                $app->enqueueMessage($status, 'notice');
            }
            
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation', false));
        }    
   
	}    



	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function mydocuments()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $taskmethod=JRequest::getVar('taskmethod', '', 'post');
        jimport('joomla.filesystem.file');
        //$TARGET=$this->GUIDv4();
        

        $photoFile = JRequest::getVar('photoFile', null, 'files', 'array');
        if($photoFile["name"]){
            $photofilename =JFile::makeSafe($photoFile['name']);
            $phototype = end(explode(".", $photoFile['name']));
            $phototype=".".$phototype;
            $photoname=explode($phototype,$photofilename);
            $photoname=$photoname[0];
            $photosrc = addslashes(file_get_contents($photoFile['tmp_name']));
            //$photodest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$photofilename;
            //$photodest1 = $TARGET.'/'.$photofilename;
            //JFile::upload($photosrc, $photodest);
        }
        $formFile = JRequest::getVar('formFile', null, 'files', 'array');
        if($formFile["name"]){
            $formfilename = JFile::makeSafe($formFile['name']);
            $formtype = end(explode(".", $formFile['name']));
            $formtype=".".$formtype;
            $formname=explode($formtype,$formfilename);
            $formname=$formname[0];
            $formsrc = addslashes(file_get_contents($formFile['tmp_name']));
            //$formdest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$formfilename;
            //$formdest1 = $TARGET.'/'.$formfilename;
            //JFile::upload($formsrc, $formdest);
        }
        $utilityFile = JRequest::getVar('utilityFile', null, 'files', 'array');
        if($utilityFile["name"]){ 
            $utilityfilename =JFile::makeSafe($utilityFile['name']);
            $utilitytype = end(explode(".", $utilityFile['name']));
            $utilitytype=".".$utilitytype;
            $utilityname=explode($utilitytype,$utilityfilename);
            $utilityname=$utilityname[0];
            $utilitysrc = addslashes(file_get_contents($utilityFile['tmp_name']));;
            //$utilitydest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$utilityfilename;
            //$utilitydest1 = $TARGET.'/'.$utilityfilename;
            //JFile::upload($utilitysrc, $utilitydest);
        }
        
        $otherFile = JRequest::getVar('otherFile', null, 'files', 'array');
        if($otherFile["name"]){
            $otherfilename = JFile::makeSafe($otherFile['name']);
            $othertype = end(explode(".", $otherFile['name']));
            $othertype=".".$othertype;
            $othername=explode($othertype,$otherfilename);
            $othername=$othername[0];
            $othersrc = addslashes(file_get_contents($otherFile['tmp_name']));;
            //$otherdest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$otherfilename;
            //$otherdest1 = $TARGET.'/'.$otherfilename;
            //JFile::upload($othersrc, $otherdest);
        }
        //Redirect to a page of your choice
        if($taskmethod=="Insert"){
            $status=Controlbox::insertDocument($CustId,$photoname, base64_encode($photosrc),$phototype, $formname,base64_encode($formsrc),$formtype,$utilityname,base64_encode($utilitysrc),$utilitytype,$othername,base64_encode( $othersrc),$othertype);
        }else{
            $status=Controlbox::updateDocument($CustId,$photoname, base64_encode($photosrc),$phototype, $formname,base64_encode($formsrc),$formtype,$utilityname,base64_encode($utilitysrc),$utilitytype,$othername,base64_encode( $othersrc),$othertype);
        }

        if($status==""){
            $app->enqueueMessage(JText::_('WEBSERVICE_ISSUE'), 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation&c=2', false));
        }else{
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation&c=2', false));
        }    
   
	}    



	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function userupdatepurchase()
	{
	    $app = JFactory::getApplication();
        $itemid = JRequest::getVar('txtItemId', '', 'post');
        $customerid = JRequest::getVar('user', '', 'post');
        $CustType = JRequest::getVar('custType', '', 'post');
       
        $supplierid = JRequest::getVar('txtMerchantName', '', 'post');
        $carrierid = JRequest::getVar('txtCarrierName', '', 'post');
        $trackingid = JRequest::getVar('txtTracking', '', 'post');
        $orderdate = JRequest::getVar('txtOrderDate', '', 'post');
        $itemname = JRequest::getVar('txtArticleName', '', 'post');
        $itemquantity = JRequest::getVar('txtQuantity', '', 'post');
        $price = JRequest::getVar('txtDvalue', '', 'post');
        $cost = JRequest::getVar('txtTotalPrice', '', 'post');
        $status = JRequest::getVar('txtStatus', '', 'post');
        $countryTxt = JRequest::getVar('country3Txt', '', 'post');
        $stateTxt = JRequest::getVar('state3Txt', '', 'post');
        $inventoryTxt = JRequest::getVar('InventoryTxt', '', 'post');
        $txtOrderId = JRequest::getVar('txtOrderId', '', 'post');
        $txtRmaValue = JRequest::getVar('txtRmaValue', '', 'post');
        
        // mltiple upload start
        
        $mulfiles = JRequest::getVar('multxtFile', null, 'files', 'array');
        if($mulfiles['name'][0] !=''){
        $filename1 = "";
        $filename2 = "";
        $filename3 = "";
        $filename4 = "";
       
       
        $mulimageByteStream = array('','','','');
        for($i=0; $i < count($mulfiles['name']) ; $i++){
            $mulfilename[$i] = $mulfiles['name'][$i];
            
            
            jimport('joomla.filesystem.file');
        $filename = JFile::makeSafe($mulfiles['name'][$i]);
        $src = $mulfiles['tmp_name'][$i];
        $TARGET=$this->GUIDv4();
        $dest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$filename;
        $dest1 = $TARGET.'/'.$filename;
        $url='';
        $status=0;
          //Redirect to a page of your choice
        if($mulfiles['name'][$i]!=""){
            if(JFile::upload($src, $dest)){
               //$url=JURI::base().'media/com_userprofile/'.$dest1;
              $url=$dest1;
            } else 
              //Redirect and throw an error message
            $app->enqueueMessage(JText::_('IMAGE_NOT_SUCCESSFULLY_UPLOADED'), 'error');
		    if($pages==1){
    		    if($CustType != "COMP")
    		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts&r=2', false));
    		     else
            		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts&r=2', false));
		    }else{
		       if($CustType != "COMP")
    		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
    		     else
            		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
		    }
		    $image1 = file_get_contents($dest);
            $mulimageByteStream[$i] = base64_encode($image1);
        }
          
        }
        }else{
            $uri = JUri::getInstance();
            $photodest1= JRequest::getVar('multxtFileId1', '', 'post');
            $destArr=explode("/",$photodest1);
            $mulfilename[0]=JFile::makeSafe(end($destArr));
            $image1 = file_get_contents($photodest1);
            $mulimageByteStream[0] = base64_encode($image1);
            
            $photodest2= JRequest::getVar('multxtFileId2', '', 'post');
            $destArr=explode("/",$photodest2);
            $mulfilename[1]=JFile::makeSafe(end($destArr));
            $image2 = file_get_contents($photodest2);
            $mulimageByteStream[1] = base64_encode($image2);
            
            $photodest3= JRequest::getVar('multxtFileId3', '', 'post');
            $destArr=explode("/",$photodest3);
            $mulfilename[2]=JFile::makeSafe(end($destArr));
            $image3 = file_get_contents($photodest3);
            $mulimageByteStream[2] = base64_encode($image3);
            
            $photodest4= JRequest::getVar('multxtFileId4', '', 'post');
            $destArr=explode("/",$photodest4);
            $mulfilename[3]=JFile::makeSafe(end($destArr));
            $image4 = file_get_contents($photodest4);
            $mulimageByteStream[3] = base64_encode($image4);
            
        }
        
        // end


        // $fileTxt = JRequest::getVar('txtFile', '', 'post');
        // jimport('joomla.filesystem.file');
        // $TARGET=$this->GUIDv4();
        // $photodest1='';
        // $profilepicFile = JRequest::getVar('txtFile', null, 'files', 'array');
        // if($profilepicFile["name"]!=""){
        //     $profilepicname =JFile::makeSafe($profilepicFile['name']);
        //     $photodest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$profilepicname;
        //     $photodest1 = $TARGET.'/'.$profilepicname;
        //     JFile::upload($profilepicFile["tmp_name"], $photodest);
        // }else{
           
        //     $uri = JUri::getInstance();
        //     $photodest= $uri->getScheme().':'.JRequest::getVar('txtFileId', '', 'post');
        //     $destArr=explode("/",$photodest);
        //     $profilepicname=JFile::makeSafe(end($destArr));
        // }
      
        // $image1 = file_get_contents($photodest);
        // $imageByteStream = base64_encode($image1);
        
        //var_dump($photodest1);exit;
          
        //multxtFile
        
        //Redirect to a page of your choice
        if($customerid!=""){
           $status=Controlbox::updatePurchaseOrder($itemid,$customerid,$supplierid,$carrierid,$trackingid,$orderdate,$profilepicname,$imageByteStream,$itemname,$itemquantity,$price,$cost,'In Progress',$countryTxt,$stateTxt,$mulfilename[0],$mulfilename[1],$mulfilename[2],$mulfilename[3],$mulimageByteStream[0],$mulimageByteStream[1],$mulimageByteStream[2],$mulimageByteStream[3],$txtOrderId,$txtRmaValue,$inventoryTxt);
        }
        
       
       
        if($status==""){
            $app->enqueueMessage($status, 'error');
		    if($CustType != "COMP")
    		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
    		     else
            		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        }else{
            
            if($status == "Succesfully updated"){
                $app->enqueueMessage(Jtext::_('COM_USERPROFILE_PRE_ALERTS_UPDATE_SUCCESS_MSG'), 'notice');
            }else{
                $app->enqueueMessage($status, 'notice');
            }
            
            
            if($CustType != "COMP")
    		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
    		     else
            		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        }    
   
	}    
	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function pickupAdditionalusers()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $useridTxt = JRequest::getVar('useridTxt', '', 'post');
        $usertypeTxt = JRequest::getVar('usertypeTxt', '', 'post');
        $fnameTxt = JRequest::getVar('fnameTxt', '', 'post');
        $lnameTxt = JRequest::getVar('lnameTxt', '', 'post');
        $PostalCode = JRequest::getVar('zipTxt', '', 'post');
        $addressTxt = JRequest::getVar('addressTxt', '', 'post');
        $emailTxt = JRequest::getVar('emailTxt', '', 'post');

        if($usertypeTxt=="Shipper"){ 
            $countryTxt = JRequest::getVar('countryTxt', '', 'post');
            $stateTxt = JRequest::getVar('stateTxt', '', 'post');
            $cityTxt = JRequest::getVar('cityTxt', '', 'post');
        }elseif($usertypeTxt=="Consignee"){
            $countryTxt = JRequest::getVar('country2Txt', '', 'post');
            $stateTxt = JRequest::getVar('state2Txt', '', 'post');
            $cityTxt = JRequest::getVar('city2Txt', '', 'post');
        }else{
            $countryTxt = JRequest::getVar('country3Txt', '', 'post');
            $stateTxt = JRequest::getVar('state3Txt', '', 'post');
            $cityTxt = JRequest::getVar('city3Txt', '', 'post');
        }


        
    	if($CustId!=""){
           $status=Controlbox::pickupAdditionalusers($CustId,$useridTxt, $usertypeTxt,$fnameTxt, $lnameTxt,$countryTxt, $stateTxt, $cityTxt, $PostalCode, $addressTxt,$emailTxt);
        }
        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=pickuporders', false));
        }else{
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=pickuporders', false));
        }    
   
	}    

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function insertQotation()
	{

	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $txtTypeOfShipperName = JRequest::getVar('txtTypeOfShipperName', '', 'post');
        $txtServiceType = JRequest::getVar('txtServiceType', '', 'post');
        $txtSourceCntry = JRequest::getVar('txtSourceCntry', '', 'post');
        $txtDestinationCntry = JRequest::getVar('txtDestinationCntry', '', 'post');
        $txtMeasurementUnits = JRequest::getVar('txtMeasurementUnits', '', 'post');
        $txtWeightUnits = JRequest::getVar('txtWeightUnits', '', 'post');
    	$txtIns=JRequest::getVar('txtIns', '', 'post');
        $txtNotes = JRequest::getVar('txtNotes', '', 'post');
        
        
        //for loop        
        $txtItemName = JRequest::getVar('txtItemName', '', 'post');
        $txtPackageList=JRequest::getVar('txtPackageList', '', 'post');
        $txtCommodity = JRequest::getVar('txtCommodity', '', 'post');
        $txtQuantity = JRequest::getVar('txtQuantity', '', 'post');
        $txtLength = JRequest::getVar('txtLength', '', 'post');
        $txtWidth = JRequest::getVar('txtWidth', '', 'post');
        $txtHeight = JRequest::getVar('txtHeight', '', 'post');
        $txtWeight = JRequest::getVar('txtWeight', '', 'post');


        //hidden fields
        $txtIdServ = JRequest::getVar('txtIdServ', '', 'post');
        $quotid = JRequest::getVar('quotid', '', 'post');
        $txtRatetypeIds = JRequest::getVar('txtRatetypeIds', '', 'post');
        $txtquotationCost = JRequest::getVar('txtquotationCost', '', 'post');
        $txtVolumeMultiple=JRequest::getVar('txtVolumeMultiple', '', 'post');
        $txtVolWtMultiple=JRequest::getVar('txtVolWtMultiple', '', 'post');
    	$txtIdrate=JRequest::getVar('txtIdRate', '', 'post');
    	
    	$txtGrossWeight=JRequest::getVar('txtGrossWeight', '', 'post');
        $txtqty=JRequest::getVar('txtQuantityIds', '', 'post');
    	
    	$addservcost=JRequest::getVar('txtAdditionalServices', '', 'post');
    	$discount=JRequest::getVar('txtDiscount', '', 'post');
    	$txtfinalCost = JRequest::getVar('txtfinalCost', '', 'post');
    	
    	$busnesstype = JRequest::getVar('txtBusinessType', '', 'post');
        if($busnesstype == 'undefined'){
            $bustype = '';
        }else{
            $bustype = $busnesstype;
        }
    	
    	
    	$addservid=JRequest::getVar('postQotationRequest', '', 'post');
    	
    	if($CustId!=""){
           $status=Controlbox::postQotationRequest($CustId, $txtMeasurementUnits,$txtTypeOfShipperName,$txtServiceType,$txtIdServ,$txtSourceCntry,$txtDestinationCntry,$txtLength,$txtWidth,$txtHeight,$txtQuantity,$txtWeight,$txtWeightUnits,$txtquotationCost,$discount,$txtfinalCost,$addservcost,$addservid,$txtqty,$quotid,$txtNotes,$txtCommodity,$txtRatetypeIds,$txtItemName,$txtPackageList,$txtVolumeMultiple,$txtVolWtMultiple,$txtIdrate,$txtIns,$txtGrossWeight,$bustype);
        }
        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=quotations', false));
        }else{
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=viewshipments', false));
        }    
   
	}    



	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function insertPickup()
	{

	    
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');

    	$txtConsigneeId = JRequest::getVar('hiddenConsigneeId', '', 'post');
    	$txtThirdPartyId = JRequest::getVar('hiddenThirdPartyId', '', 'post');
    	$txtShipperNameId = JRequest::getVar('hiddenShipperNameId', '', 'post');
    	
    	$txtShipperName = JRequest::getVar('hiddenShipperName', '', 'post');
    	$txtConsigneeName = JRequest::getVar('hiddenConsignee', '', 'post');
    	$txtThirdPartyName = JRequest::getVar('hiddenThirdParty', '', 'post');
    	
    	$txtShipperAddress = JRequest::getVar('txtShipperAddress', '', 'post');
    	$txtConsigneeAddress = JRequest::getVar('txtConsigneeAddress', '', 'post');
    	$txtThirdPartyAddress = JRequest::getVar('txtThirdPartyAddress', '', 'post');


        $txtMeasurementUnits = JRequest::getVar('txtMeasurementUnits', '', 'post');
        $txtTypeOfShipperName = JRequest::getVar('txtTypeOfShipperName', '', 'post');
        $txtServiceType = JRequest::getVar('txtServiceType', '', 'post');
        $txtIdServ = JRequest::getVar('txtIdServ', '', 'post');
        $txtSourceCntry = JRequest::getVar('txtSourceCntry', '', 'post');
        $txtDestinationCntry = JRequest::getVar('txtDestinationCntry', '', 'post');
        $txtLength = JRequest::getVar('txtLength', '', 'post');
        $txtWidth = JRequest::getVar('txtWidth', '', 'post');
        $txtHeight = JRequest::getVar('txtHeight', '', 'post');
        $txtQuantity = JRequest::getVar('txtQuantity', '', 'post');
        $txtWeight = JRequest::getVar('txtWeight', '', 'post');
        $txtWeightUnits = JRequest::getVar('txtWeightUnits', '', 'post');
        $txtquotationCost = JRequest::getVar('txtquotationCost', '', 'post');

        $txtDiscount = JRequest::getVar('txtDiscount', '', 'post');
        $txtfinalCost = JRequest::getVar('txtfinalCost', '', 'post');
        $txtGrossWeight=JRequest::getVar('txtGrossWeight', '', 'post');
        $quotid = JRequest::getVar('quotid', '', 'post');
        $txtNotes = JRequest::getVar('txtNotes', '', 'post');
        $txtCommodity = JRequest::getVar('txtCommodityList', '', 'post');
        $txtRatetypeIds = JRequest::getVar('txtRatetypeIds', '', 'post');
        $txtItemName = JRequest::getVar('txtItemName', '', 'post');
    	$txtRateId = JRequest::getVar('txtRateId', '', 'post');
        $txtPackageList=JRequest::getVar('txtPackageList', '', 'post');
        $txtVolumeMultiple=JRequest::getVar('txtVolumeMultiple', '', 'post');
        $txtVolWtMultiple=JRequest::getVar('txtVolWtMultiple', '', 'post');

        $txtName = JRequest::getVar('txtName', '', 'post');
    	$txtPickupAddress = JRequest::getVar('txtPickupAddress', '', 'post');
    	$txtChargableWeight = JRequest::getVar('txtChargableWeight', '', 'post');
    	$txtPickupDate = JRequest::getVar('txtPickupDate', '', 'post');
    	
    	$txtIdrate=JRequest::getVar('txtIdRate', '', 'post');
    	$addservid=JRequest::getVar('txtAdditionalServicesId', '', 'post');
    	$addservcost=JRequest::getVar('txtAdditionalServices', '', 'post');
    	$txtitemsCost=JRequest::getVar('txtitemCost', '', 'post');
    	
    	$busnesstype = JRequest::getVar('txtBusinessType', '', 'post');
        if($busnesstype == 'undefined'){
            $bustype = '';
        }else{
            $bustype = $busnesstype;
        }
    	
    	$txtqty=JRequest::getVar('txtQuantityIds', '', 'post');
    	if($CustId!=""){
           $status=Controlbox::postPickupRequest($CustId, $txtMeasurementUnits,$txtTypeOfShipperName,$txtServiceType,$txtIdServ,$txtSourceCntry,$txtDestinationCntry,$txtLength,$txtWidth,$txtHeight,$txtQuantity,$txtWeight,$txtWeightUnits,$txtquotationCost,$txtRateId,$quotid,$txtNotes,$txtCommodity,$txtRatetypeIds,$txtItemName,$txtPackageList,$txtVolumeMultiple,$txtVolWtMultiple,$txtShipperName,$txtConsigneeName,$txtThirdPartyName,$txtName,$txtChargableWeight,$txtPickupDate,$txtPickupAddress,$txtConsigneeId,$txtThirdPartyId,$txtShipperNameId,$txtShipperAddress,$txtConsigneeAddress,$txtThirdPartyAddress,$txtIdrate,$txtfinalCost,$txtDiscount,$txtGrossWeight,$addservid,$addservcost,$txtqty,$txtitemsCost,$bustype);
        }
        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=viewshipments', false));
        }else{
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=viewshipments', false));
        }    
   
	}    


	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function convertpickup()
	{
	    $app = JFactory::getApplication();

        $CustId = JRequest::getVar('user', '', 'post');
        $txtShipperName = JRequest::getVar('txtShipperName', '', 'post');
        $txtShipperAddress = JRequest::getVar('txtShipperAddress', '', 'post');
        $txtConsigneeName = JRequest::getVar('txtConsigneeName', '', 'post');
        $txtConsigneeAddress= JRequest::getVar('txtConsigneeAddress', '', 'post');
        $txtThirdPartyName = JRequest::getVar('txtThirdPartyName', '', 'post');
        $txtThirdPartyAddress= JRequest::getVar('txtThirdPartyAddress', '', 'post');
        $txtChargableWeight = JRequest::getVar('txtChargableWeight', '', 'post');
        $txtName = JRequest::getVar('txtName', '', 'post');
        $txtPickupDate = JRequest::getVar('txtPickupDate', '', 'post');
        $txtPickupAddress = JRequest::getVar('txtPickupAddress', '', 'post');
        $QuoteNumberTxt = JRequest::getVar('QuoteNumberTxt', '', 'post');

    	if($CustId!=""){
           $status=Controlbox::addconvertpickup($CustId,$txtShipperName,$txtShipperAddress,$txtConsigneeName, $txtConsigneeAddress,$txtThirdPartyName,$txtThirdPartyAddress, $txtChargableWeight,$txtName, $txtPickupDate, $txtPickupAddress, $QuoteNumberTxt);
        }
        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=viewshipments', false));
        }else{
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=viewshipments', false));
        }    
   
	}
	
	
	
	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function addusers()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $typeuserTxt = JRequest::getVar('typeuserTxt', '', 'post');
        $idTxt = JRequest::getVar('idTxt', '', 'post');
        $fnameTxt = JRequest::getVar('fnameTxt', '', 'post');
        $lnameTxt = JRequest::getVar('lnameTxt', '', 'post');
        $country2Txt = JRequest::getVar('country2Txt', '', 'post');
        $state2Txt = JRequest::getVar('state2Txt', '', 'post');
        $city2Txtdiv = JRequest::getVar('city2Txtdiv', '', 'post');
        $idtypeTxt = JRequest::getVar('idtypeTxt', '', 'post');
        $idvalueTxt = JRequest::getVar('idvalueTxt', '', 'post');
        
        if($city2Txtdiv){
            $city2Txt = $city2Txtdiv;
        }else{
            $city2Txt = JRequest::getVar('city2Txt', '', 'post');
        }
        $PostalCode = JRequest::getVar('zipTxt', '', 'post');
        $addressTxt = JRequest::getVar('addressTxt', '', 'post');
        $address2Txt = JRequest::getVar('address2Txt', '', 'post');
        $emailtxt = JRequest::getVar('emailTxt', '', 'post');
    	if($CustId!=""){
           $status=Controlbox::getadduser($CustId,$typeuserTxt,$idTxt, $fnameTxt, $lnameTxt,$country2Txt, $state2Txt, $city2Txt, $PostalCode, $addressTxt,$address2Txt,$emailtxt,$idtypeTxt,$idvalueTxt);
        }
        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation', false));
        }else{
           
            if($status == "Additional address successfully inserted"){
                $app->enqueueMessage(Jtext::_('COM_USERPROFILE_PI_ADDITIONAL_ADDRESS_INSERTED_SUCCESSFULLY'), 'notice');
            }else{
                $app->enqueueMessage($status, 'notice');
            }
            
            
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation', false));
        }    
   
	}

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function editaddusers()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $typeuserTxt = JRequest::getVar('typeuserTxt', '', 'post');
        $idTxt = JRequest::getVar('idTxt', '', 'post');
        
        $fnameTxt = JRequest::getVar('fnameTxt', '', 'post');
        $lnameTxt = JRequest::getVar('lnameTxt', '', 'post');
        $country3Txt = JRequest::getVar('country3Txt', '', 'post');
        $state3Txt = JRequest::getVar('state3Txt', '', 'post');
        $idtypeTxt = JRequest::getVar('idtypeTxt', '', 'post');
        $idvalueTxt = JRequest::getVar('idvalueTxt', '', 'post');
        
        // $city3Txtdiv = JRequest::getVar('city3Txtdiv', '', 'post');
        // if($city3Txtdiv){
        //     $city3Txt = $city3Txtdiv;
        // }else{
        //     $city3Txt = JRequest::getVar('city3Txt', '', 'post');
        
        // }
        
        $city3Txt = JRequest::getVar('city3Txt', '', 'post');
        
        
        $PostalCode = JRequest::getVar('zipTxt', '', 'post');
        $addressTxt = JRequest::getVar('addressTxt', '', 'post');
        $address2Txt = JRequest::getVar('address2Txt', '', 'post');
        $emailTxt = JRequest::getVar('emailTxt', '', 'post');
        $fid = JRequest::getVar('fidTxt', '', 'post');
    	if($CustId!=""){
           $status=Controlbox::geteditadduser($CustId, $fid,$typeuserTxt,$idTxt,$fnameTxt, $lnameTxt,$country3Txt, $state3Txt, $city3Txt, $PostalCode, $addressTxt, $address2Txt, $emailTxt,$idtypeTxt,$idvalueTxt);
        }
        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation', false));
        }else{
            
            if($status == "Additional address successfully updated"){
                 $app->enqueueMessage(Jtext::_('COM_USERPROFILE_PI_ADDITIONAL_ADDRESS_UPDATED_SUCCESSFULLY'), 'notice');
            }else{
                 $app->enqueueMessage($status, 'notice');
            }
            
           
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation', false));
        }    
   
	}    

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	 
	public function dirpayshippment()
	{
	     
	    $app = JFactory::getApplication();
        $amtStr = JRequest::getVar('amtStr', '', 'post');
        $cardnumberStr = JRequest::getVar('cardnumberStr', '', 'post');
        $txtccnumberStr = JRequest::getVar('txtccnumberStr', '', 'post');
        $MonthDropDownListStr = JRequest::getVar('MonthDropDownListStr', '', 'post');
        $txtNameonCardStr = JRequest::getVar('txtNameonCardStr', '', 'post');
        $YearDropDownListStr = JRequest::getVar('YearDropDownListStr', '', 'post');
        $invidkStr = JRequest::getVar('invidkStr', '', 'post');
        $qtyStr = JRequest::getVar('qtyStr', '', 'post');
        $wherhourecStr = JRequest::getVar('bill_form_nostr', '', 'post');
        $CustId = JRequest::getVar('user', '', 'post');
        $consignidStr = JRequest::getVar('consignidStr', '', 'post');
        $articleStr =JRequest::getVar('articleStr', '', 'post');
        $articleStr=implode(",",$articleStr);
        $priceStr =JRequest::getVar('priceStr', '', 'post');
        $priceStr=implode(",",$priceStr);
        $inhouseNo=JRequest::getVar('InHouseNostr', '', 'post');
        $inhouseId=JRequest::getVar('InhouseIdkstr', '', 'post');
        $ratetypeStr =JRequest::getVar('ratetypeStr', '', 'post');
        $addSerStr =JRequest::getVar('addSerStr', '', 'post');
        $addSerCostStr =JRequest::getVar('addSerCostStr', '', 'post');
        
        jimport('joomla.filesystem.file');
        $TARGET=$this->GUIDv4();
        $invf='';
        $profilepicFile = JRequest::getVar('invFile[]', null, 'files', 'array');
        foreach($_FILES['invFile']['name'] as $key=>$mage){
            $profilepicname =JFile::makeSafe($_FILES['invFile']['name'][$key]);
            $photodest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$profilepicname;
            if($profilepicname){
            $invf .= $TARGET.'/'.$profilepicname.',';
            }else{
                $invf .= '0,';
            }    
            if($_FILES['invFile']["tmp_name"][$key]){
                JFile::upload($_FILES['invFile']["tmp_name"][$key], $photodest);
            }
        }

        
        
        $specialinstructionStr = JRequest::getVar('txtspecialinsStr', '', 'post');
        
        if(JRequest::getVar('cc', '', 'post')=="Paypal"){
            $cc = 'PPD';
        }
        elseif(JRequest::getVar('cc', '', 'post')=="COD"){
            $cc = 'COD';
        }
        $shipservtStr = JRequest::getVar('Id_Servstr', '', 'post');
        
       
        
       
        if($cc=="PPD"){
            
            define('PAYMENT_URL','https://connect.squareupsandbox.com/v2/payments');
            //define('ACCESS_TOKEN','EAAAECxnHpyXK64CWNzZVy5DjZQK1b9MaMb3F1r9kS5C5OdZ3jzS0gj84Jy3jKEl');
            define('ACCESS_TOKEN','EAAAEDhZ0KCJ8B_qJsuGkDPKYvUX4AQLVHy-Ab8b5fbXgqJF5jFqdH0I3pSNZSX_');
            
            //define('ACCESS_TOKEN','EAAAEI_EAQytVNDUlW0zcFiTLp3SJhon_ZJwNx1reR3hezKJ781rP5138ag-6r8d');
            define('SQUARE_VERSION','2020-08-26');
            $timeout = 40;
            $url=PAYMENT_URL;
            $headers = [
                'Square-Version: '.SQUARE_VERSION,
                'Authorization: Bearer '.ACCESS_TOKEN,
                'Content-Type: application/json'
            ];
            
            $idempotency_key = uniqid('pobox',true);
            $amtStrs=$amtStr*100;
            $postFields = array(
                "idempotency_key" => uniqid('pobox',true),
                "autocomplete" => false,
                "amount_money" => array("amount" =>$amtStrs, "currency" => "USD"),
                "source_id" => $_POST['nonce']
            );
            $data=json_encode($postFields);
            
            //var_dump($data);
            
            $ch = curl_init();  
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         
            $output=curl_exec($ch);
            $msg=json_decode($output);
            //var_dump($msg);exit;
            curl_close($ch);
            $tid=$msg->payment;
            
            if($tid->status=="APPROVED"){
            
                $tid=$tid->id;
                $status=Controlbox::submitpayment($amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$invidkStr,$qtyStr,$wherhourecStr, $CustId,$specialinstructionStr, $cc, $shipservtStr,$consignidStr,$invf,$articleStr,$priceStr,$tid,$inhouseNo,$inhouseId,$ratetypeStr);
            }else{
                if($msg->status!="APPROVED"){
                
                    $app->enqueueMessage('Payment Failed ', 'error');
        		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess', false));
                }else{    
                    $app->enqueueMessage('Square Payment will not accept this amount ('.$amtStr.') USD to make transaction ', 'error');
        		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess', false));
                }
            }

        }
        else{
           $status=Controlbox::submitpayment($amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$invidkStr,$qtyStr,$wherhourecStr, $CustId,$specialinstructionStr, $cc, $shipservtStr,$consignidStr,$invf,$articleStr,$priceStr,$a,$b,$c,$ratetypeStr,$addSerStr,$addSerCostStr);
        }


        $input = JFactory::getApplication()->input;
        $input->set('invoice', $status);
        
        
        
        
        if(strpos($status, ':')===false){
            
            if($status->Msg){
                $app->enqueueMessage($status->Msg, 'error');
            }else{
                $app->enqueueMessage("Payment Failure", 'error');
            }
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=cod', false));
        }else{
            $app->enqueueMessage(Jtext::_('ORDER_PAYMENT_DONE_SUCCESSFULLY'), 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=response&res='.base64_encode($status), false));
        }    
	}
	
	
		/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	
	
	public function payshippment()
	{
	    
	    
	    $app = JFactory::getApplication();
	    $companyId = JRequest::getVar('companyId', '', 'post');
        $amtStr = JRequest::getVar('amount', '', 'post');
        $cardnumberStr = JRequest::getVar('cardnumberStr', '', 'post');
        $txtccnumberStr = JRequest::getVar('txtccnumberStr', '', 'post');
        $MonthDropDownListStr = JRequest::getVar('MonthDropDownListStr', '', 'post');
        $txtNameonCardStr = JRequest::getVar('txtNameonCardStr', '', 'post');
        $YearDropDownListStr = JRequest::getVar('YearDropDownListStr', '', 'post');
        $invidkStr = JRequest::getVar('invidkStr', '', 'post');
        $qtyStr = JRequest::getVar('qtyStr', '', 'post');
        $wherhourecStr = JRequest::getVar('wherhourecStr', '', 'post');
        $CustId = JRequest::getVar('user', '', 'post');
        $consignidStr = JRequest::getVar('consignidStr', '', 'post');
        $articleStr =JRequest::getVar('articleStr', '', 'post');
        $articleStr=implode(",",$articleStr);
        $priceStr =JRequest::getVar('priceStr', '', 'post');
        $priceStr=implode(",",$priceStr);
        $addSerStr =JRequest::getVar('addSerStr', '', 'post');
        $addSerCostStr =JRequest::getVar('addSerCostStr', '', 'post');
        $insuranceCost =JRequest::getVar('insuranceCost', '', 'post');
        $extAddSer =JRequest::getVar('extAddSer', '', 'post');
        
        $ratetypeStr =JRequest::getVar('ratetypeStr', '', 'post');
        $Conveniencefees =JRequest::getVar('Conveniencefees', '', 'post');
       
        jimport('joomla.filesystem.file');
        $TARGET=$this->GUIDv4();
        $invf='';
        $filenameStr='';
        $profilepicFile = JRequest::getVar('invFile[]', null, 'files', 'array');
        foreach($_FILES['invFile']['name'] as $key=>$mage){
            $profilepicname =JFile::makeSafe($_FILES['invFile']['name'][$key]);
            $photodest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$profilepicname;
           
            if($_FILES['invFile']["tmp_name"][$key]){
                JFile::upload($_FILES['invFile']["tmp_name"][$key], $photodest);
            }
            
             if($profilepicname){
            
            $image1 = file_get_contents($photodest);
            $imageByteStream = base64_encode($image1);
            $invf .= $imageByteStream.',';
            $filenameStr .= $profilepicname.',';
            
            }else{
                $invf .= '0,';
            }    
            
        }

        $specialinstructionStr = JRequest::getVar('txtspecialinsStr', '', 'post');
        if(JRequest::getVar('cc', '', 'post')=="Paypal" || JRequest::getVar('cc', '', 'post')=="Stripe"){
            if(JRequest::getVar('cc', '', 'post')=="Stripe"){
                $paymentgateway = 'Stripe';
            }
            $cc = 'PPD';
        }
        elseif(JRequest::getVar('cc', '', 'post')=="COD" ){
            $cc = 'COD';
            $paymentgateway = '';
            $Conveniencefees = 0;
        }
        $shipservtStr = JRequest::getVar('shipservtStr', '', 'post');
        $status=Controlbox::submitpayment($amtStr,$cardnumberStr,$txtccnumberStr,$MonthDropDownListStr,$txtNameonCardStr,$YearDropDownListStr,$invidkStr,$qtyStr,$wherhourecStr,$CustId,$specialinstructionStr,$cc,$paymentgateway,$shipservtStr,$consignidStr,$invf,$filenameStr,$articleStr,$priceStr,$a,$b,$c,$ratetypeStr,$Conveniencefees,$addSerStr,$addSerCostStr,$companyId,$insuranceCost,$extAddSer);

        $input = JFactory::getApplication()->input;
        $input->set('invoice', $status);
        
        if(strpos($status, ':')===false){
            if($status){
                $app->enqueueMessage($status, 'error');
            }else{
                $app->enqueueMessage("Payment Failure", 'error');
            }
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess', false));
        }else{
            $app->enqueueMessage(Jtext::_('ORDER_PAYMENT_DONE_SUCCESSFULLY'), 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=response&res='.base64_encode($status), false));
        }    
   
	}    
	
	
	
	

/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
function PPHttpPost($methodName, $nvpStr) {
	global $environment;
    $environment='sandbox';
	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode('sb-jftsj5136420_api1.business.example.com');//sdk-three_api1.sdk.com
	$API_Password = urlencode('6QBMLHULL9XXQA8U');//QFZCWN5HZM8VBG7Q
	$API_Signature = urlencode('AGXSavYHDzXzq-xLRYo784Nm--X4ASUsM13nfOGlCKiXQA7129h07nrO');//A-IzJhZZjhg29XQ2qnhapuwxIDzyAZQ92FRP5dqBzVesOkzbdUONzmOU
	$API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('100');

	// Set the API operation, version, and API signature in the request.
	//$nvpreq = "METHOD=$methodName&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature&$nvpStr";
	
	
    $nvpreq="USER=$API_UserName&PWD=$API_Password&SIGNATURE=$API_Signature&METHOD=DoDirectPayment&VERSION=100&CREDITCARDTYPE=VISA&ACCT=4032038454199955&EXPDATE=032026&CVV2=808&AMT=107.55&FIRSTNAME=Designer&LASTNAME=Fotos&IPADDRESS=255.55.167.002&STREET=1234%20AVS_A%20Street&CITY=San%20Jose&STATE=CA&COUNTRY=United%20States&ZIP=95110&COUNTRYCODE=US&SHIPTONAME=Lenny%20P.%20Rico&SHIPTOSTREET=1234%20Easy%20Street&SHIPTOSTREET2=Apt%2022%20bis&SHIPTOCITY=New%20Orleans&SHIPTOSTATE=LA&SHIPTOCOUNTRY=US&SHIPTOZIP=70114&PAYMENTACTION=Authorization&FIZBIN=foo";

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
	// Get response from the server.
	$httpResponse = curl_exec($ch);
	
// 	var_dump($nvpreq);
// 	var_dump($httpResponse);exit;

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}


	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function payshopperassist()
	{
	    $app = JFactory::getApplication();
        $amtStr = JRequest::getVar('amount', '', 'post');
        $cardnumberStr = JRequest::getVar('cardnumberStr', '', 'post');
        $txtccnumberStr = JRequest::getVar('txtccnumberStr', '', 'post');
        $MonthDropDownListStr = JRequest::getVar('MonthDropDownListStr', '', 'post');
        $txtNameonCardStr = JRequest::getVar('txtNameonCardStr', '', 'post');
        $YearDropDownListStr = JRequest::getVar('YearDropDownListStr', '', 'post');
        $txtSpecialInStr = JRequest::getVar('hiddentxtSpecialIn', '', 'post');
        $CustId = JRequest::getVar('user', '', 'post');
        
        $txtTaxesStr = JRequest::getVar('hiddentxtTaxes', '', 'post');
        $txtShippChargesStr = JRequest::getVar('hiddentxtShippCharges', '', 'post');
        
        $ItemIdsStr = JRequest::getVar('hiddenItemIds', '', 'post');
        $ItemQuantityStr = JRequest::getVar('hiddenItemQuantity', '', 'post');
        $ItemSupplierIdStr = JRequest::getVar('hiddenItemSupplierId', '', 'post');
        $txtPaymentMethod = JRequest::getVar('cc', '', 'post');
        
     
        if($txtPaymentMethod!="COD"){
            $environment = 'live';	// or 'beta-sandbox' or 'live'
    
            $paymentType =	'Sale';
            $fnameStr = JRequest::getVar('fnameStr', '', 'post');
            $lnameStr = JRequest::getVar('lnameStr', '', 'post');
            $firstName =urlencode($fnameStr);
            $lastName = urlencode($lnameStr);
            $cardtypeStr = JRequest::getVar('cardtypeStr', '', 'post');
            $creditCardType = urlencode($cardtypeStr);
            $creditCardNumber = urlencode($cardnumberStr);
            $expDateMonth = $MonthDropDownListStr;
            // Month must be padded with leading zero
            $padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
            
            $expDateYear = urlencode($YearDropDownListStr);
            $cvv2Number = urlencode($txtccnumberStr);
            $addressStr = JRequest::getVar('addressStr', '', 'post');
            $address1 = urlencode($addressStr);
            $address2 = '';
            $cityStr = JRequest::getVar('cityStr', '', 'post');
            $city = urlencode($cityStr);
            $stateStr = JRequest::getVar('stateStr', '', 'post');
            $state = urlencode($stateStr);
            $zipStr = JRequest::getVar('zipStr', '', 'post');
            $zip = urlencode($zipStr);
            $country = JRequest::getVar('countryStr', '', 'post');
            $amount = JRequest::getVar('amtStr', '', 'post');	//actual amount should be substituted here
            $currencyID = 'USD';// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
            
            // Add request-specific fields to the request string.
            $nvpStr =	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
            			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
            			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";
            // Execute the API operation; see the PPHttpPost function above.
            $httpParsedResponseAr = $this->PPHttpPost('DoDirectPayment', $nvpStr);
            // echo '<pre>';
            // var_dump($_POST);
            // var_dump($httpParsedResponseAr);exit;
            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
               $status=Controlbox::submitpayshopperassist($CustId,$amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$txtSpecialInStr,$txtTaxesStr,$txtShippChargesStr,$ItemIdsStr,$ItemQuantityStr,$ItemSupplierIdStr,$txtPaymentMethod,$httpParsedResponseAr["ACK"],$httpParsedResponseAr["CORRELATIONID"],$httpParsedResponseAr["TRANSACTIONID"]);
    
            	//exit('Direct Payment Completed Successfully: '.print_r($httpParsedResponseAr, true));
            }else{
                $app->enqueueMessage("Payment Failure", 'notice');
        	    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist', false));
            }
        }
        else  {
            $paymentgateway = "";
            $status=Controlbox::submitpayshopperassist($CustId,$amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$txtSpecialInStr,$txtTaxesStr,$txtShippChargesStr,$ItemIdsStr,$ItemQuantityStr,$ItemSupplierIdStr,$txtPaymentMethod,$paymentgateway,'','','');
    
        }


       
        $input = JFactory::getApplication()->input;
        $input->set('invoice', $status);
        if(strpos($status, ':')===false){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist', false));
        }else{
            $app->enqueueMessage(Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_PAYMENT_SUCCESS'), 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=response&res='.base64_encode($status), false));
        }    

	}    



	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function addshippment()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $CustType = JRequest::getVar('userType', '', 'post');
        $mnameTxt = JRequest::getVar('mnameTxt', '', 'post');
        $carrierTxt= JRequest::getVar('carrierTxt', '', 'post');
        $carriertrackingTxt = JRequest::getVar('carriertrackingTxt', '', 'post');
        $orderdateTxt = JRequest::getVar('orderdateTxt', '', 'post');
        //$addinvoiceTxt = JRequest::getVar('addinvoiceTxt', '', 'post');
        $anameTxt= JRequest::getVar('anameTxt', '', 'post');
        $quantityTxt = JRequest::getVar('quantityTxt', '', 'post');
        $declaredvalueTxt = JRequest::getVar('declaredvalueTxt', '', 'post');
        $totalpriceTxt = JRequest::getVar('totalpriceTxt', '', 'post');
        $itemstatusTxt = JRequest::getVar('itemstatusTxt', '', 'post');
        $pages = JRequest::getVar('pages', '', 'post'); 
        $countryTxt = JRequest::getVar('country3Txt', '', 'post');
        $business_type = JRequest::getVar('business_type', '', 'post');
        
        $rmavalue = JRequest::getVar('rmavalue', '', 'post');
        $orderidTxt = JRequest::getVar('orderidTxt', '', 'post');
     
        $file = JRequest::getVar('addinvoice2Txt', null, 'files', 'array');
        if($file==""){
            $file = JRequest::getVar('addinvoiceTxt', null, 'files', 'array');
        }
        
         // for multiple image selection
         
        $mulfiles = array();
        for($j=0;$j<count($anameTxt);$j++){
            
        $fileNameStr = 'addinvoiceTxtMul_'.($j+1);
        $mulfiles[$j] = JRequest::getVar($fileNameStr, null, 'files', 'array');
        
        // echo '<pre>';
        // var_dump($_FILES[$fileNameStr]);
        // // var_dump(base64_encode(file_get_contents($_FILES[$fileNameStr]["tmp_name"][0])));
        // exit;
        
        $mulimageByteStream[$j] = array();
        $mulfilename[$j] = array();
        
        for($i=0; $i < count($mulfiles[$j]['name']) ; $i++){
            array_push($mulfilename[$j],$mulfiles[$j]['name'][$i]);
            //$mulfilename[$i] = $mulfiles[$j]['name'][$i];
            
        jimport('joomla.filesystem.file');
        //$filename = str_replace(" ","_",JFile::makeSafe($mulfiles[$j]['name'][$i]));
        $filename = str_replace(" ","_",JFile::makeSafe($mulfiles[$j]['name'][$i]));
        
        $src = $mulfiles[$j]['tmp_name'][$i];
        $TARGET=$this->GUIDv4();
        $dest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$filename;
        $dest1 = $TARGET.'/'.$filename;
        $url='';
        $status=0;
          //Redirect to a page of your choice
        if($mulfiles[$j]['name'][$i]!=""){
            if(JFile::upload($src, $dest)){
              //$url=JURI::base().'media/com_userprofile/'.$dest1;
              $url=$dest1;
            } 
            //else 
            //   //Redirect and throw an error message
            // $app->enqueueMessage(JText::_('IMAGE_NOT_SUCCESSFULLY_UPLOADED'), 'error');
		  //  if($pages==1){
		  //       if($CustType != "COMP")
    // 		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts&r=2', false));
    // 		     else
    //         		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts&r=2', false));
		  //  }else{
		  //      if($CustType != "COMP")
		  //          $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
		  //      else
    //         		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
		  //  }
		    
		     $images_mul = file_get_contents($dest);
		  
		    //$images_mul = file_get_contents($_FILES[$fileNameStr]["tmp_name"][$i]);
            //$mulimageByteStream[$j] = base64_encode($images_mul);
            array_push($mulimageByteStream[$j],base64_encode($images_mul));
            
        }
             
      }
       
    }
    
    //   echo '<pre>';
    //   //var_dump($mulfilename);
   
      
        
        $filename1 = "";
        $filename2 = "";
        $filename3 = "";
        $filename4 = "";
       
       
        
        //var_dump($mulimageByteStream);exit;
        
        // jimport('joomla.filesystem.file');
        // $filename = JFile::makeSafe($file['name']);
        
        // $allowed =  array('jpeg','jpg', "png", "gif", "pdf", "JPEG","JPG", "PNG", "GIF", "PDF");
        // $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        
        // if($filename !=''){
            
        //     if(!in_array($ext,$allowed) ) {
           
        //                 $app->enqueueMessage($ext."&nbsp".JText::_('COM_USERPROFILE_INVALID_EXT_ERROR_MSG'), 'error');
        //                 if($CustType != "COMP")
        //     		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
        //     		     else
        //             		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
                    		
            
        //     }
            
        // }
        
        //     $src = $file['tmp_name'];
        //     $TARGET=$this->GUIDv4();
        //     $dest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$filename;
        //     $dest1 = $TARGET.'/'.$filename;
        //     $url='';
        //     $status=0;
        //       //Redirect to a page of your choice
        //     if($file['name']!=""){
        //         if(JFile::upload($src, $dest)){
        //           //$url=JURI::base().'media/com_userprofile/'.$dest1;
        //           $url=$dest1;
        //         } else 
        //           //Redirect and throw an error message
        //         $app->enqueueMessage(JText::_('IMAGE_NOT_SUCCESSFULLY_UPLOADED'), 'error');
    		  //  if($pages==1){
    		  //       if($CustType != "COMP")
        // 		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts&r=2', false));
        // 		     else
        //         		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts&r=2', false));
        		        
    		  //  }else{
    		  //      if($CustType != "COMP")
    		  //          $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
    		  //      else
        //         		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
    		  //  }
    		  //  $image1 = file_get_contents($dest);
        //         $imageByteStream = base64_encode($image1);
        //     }
        
            $status=0;
        	if($CustId!=""){
        	    $csvFile=JRequest::getVar('addinvoice3Txt', null, 'files', 'array');
        	    if(JFile::makeSafe($csvFile['name'])){
            	    if (($handle = fopen($csvFile['tmp_name'], 'r')) !== FALSE) { 
            	        // Check the resource is valid
                        $i=0;
                        $status=0;
                        while (($column = fgetcsv($handle, 10000, ",")) !== FALSE ) {
                        if($i==0){
                            if(strcasecmp("Article Name",$column[0])==0 && strcasecmp("Quantity",$column[1])==0 && strcasecmp("Declared Value",$column[2])==0 && strcasecmp("Order Id",$column[3])==0 && strcasecmp("RMA Value",$column[4])==0 ){
                                unset($anameTxt);
                                unset($quantityTxt);
                                unset($declaredvalueTxt);
                                unset($totalpriceTxt);
                                unset($orderidTxt);
                                unset($rmavalue);
                                
                                $anameTxt = array();
                                $quantityTxt = array();
                                $declaredvalueTxt = array();
                                $totalpriceTxt = array();
                                $orderidTxt = array();
                                $rmavalue = array();
                            }else
                            $status=1;
                        }else{
                            if($status==0){
                                $anameTxt[]=$column[0];
                                if($column[1]>0){
                                    $quantityTxt[]=$column[1];
                                }else{
                                    $status=2;
                                }
                                $declaredvalueTxt[]=$column[2];
                                $totalpriceTxt[]=$column[1]*$column[2];
                                $orderidTxt[]=$column[3];
                                $rmavalue[]=$column[4];
                            }    
                        }   
                        
                        $i++;    
                        } 
                        fclose($handle);
                    }
        	    }
        	    
            
        	   if($status==1){
                    $app->enqueueMessage('Please check uploaded csv is not valid', 'error');
                    if($CustType != "COMP")
        		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
        		    else
                		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        		    
        	   }elseif($status==2){
                    $app->enqueueMessage('Please check uploaded csv quantity is empty', 'error');
                    if($CustType != "COMP")
        		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
        		    else
                		$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
               }else 
               {
                   
            // var_dump($mulimageByteStream);
            // exit;

                   
                    $status=Controlbox::getaddshippment($CustId,$mnameTxt,$carrierTxt,$carriertrackingTxt,$orderdateTxt,$anameTxt,$quantityTxt,$declaredvalueTxt,$totalpriceTxt,$itemstatusTxt,$countryTxt,'',$rmavalue,$orderidTxt,$business_type,$mulfilename,$mulimageByteStream);
                   
                    if($status==""){
                        $app->enqueueMessage(JText::_('WEBSERVICE_ISSUE'), 'error');
            		    if($pages==1){
            		        if($CustType != "COMP")
                		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts&r=2', false));
                		    else
                		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts&r=2', false));
            		    }else{
            		        if($CustType != "COMP")
            		            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
            		        else
                		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
            		    }
                    }
                    else{
                        
                        $prealert_success = $this->getClientConfig();
                        
                        if($status == "Pre alerts added successfully"){
                            $status_txt = Jtext::_($prealert_success);
                        }
                        
                        $app->enqueueMessage($status_txt, 'notice');
            		    if($pages==1){
            		         if($CustType != "COMP")
                		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts&r=2', false));
                		     else
                		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts&r=2', false));
                		        
            		    }else{
            		         if($CustType != "COMP")
            		            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocessalerts', false));
            		         else
                		        $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
                        }    
                    }
    
               }
        	}
        
       
	}  



	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function addshopperassist()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $txtMerchantName= JRequest::getVar('txtMerchantName', '', 'post');
        $txtMerchantWebsite = JRequest::getVar('txtMerchantWebsite', '', 'post');
        $txtItemName= JRequest::getVar('txtItemName', '', 'post');
        $txtItemModel = JRequest::getVar('txtItemModel', '', 'post');
        $txtItemRefference= JRequest::getVar('txtItemRefference', '', 'post');
        $txtColor = JRequest::getVar('txtColor', '', 'post');
        $txtSize = JRequest::getVar('txtSize', '', 'post');
        $txtQuantity = JRequest::getVar('txtQuantity', '', 'post');
        $txtDvalue = JRequest::getVar('txtDvalue', '', 'post');
        $txtTprice = JRequest::getVar('txtTprice', '', 'post');
        $txtItemurl = JRequest::getVar('txtItemurl', '', 'post');
        $txtItemdescription = JRequest::getVar('txtItemdescription', '', 'post');
        
    	if($CustId!=""){
           $status=Controlbox::addShopperassist($CustId, $txtMerchantName, $txtMerchantWebsite,$txtItemName, $txtItemModel, $txtItemRefference, $txtColor, $txtSize,$txtQuantity,$txtDvalue,$txtTprice,$txtItemurl,$txtItemdescription);
        }
        if($status==""){
            $app->enqueueMessage(JText::_('WEBSERVICE_ISSUE'), 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist', false));
        }else{
            
            if($status == "Item added successfully"){
                $app->enqueueMessage(Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_ITEM_ADDED_SUCCESS'), 'notice');
            }else{
                $app->enqueueMessage($status, 'notice');
            }
            
           
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist', false));
        }    
   
	}    





	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function returnshippment()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $idk = explode(":",JRequest::getVar('idk', '', 'post'));
        $txtWArehousid=$idk[0];
        $txtIdkid=$idk[1];
        $txtqty=JRequest::getVar('qty', '', 'post');
        $txtBackCompany = JRequest::getVar('txtBackCompany', '', 'post');
        $txtReturnAddress= JRequest::getVar('txtReturnAddress', '', 'post');
        $txtReturnCarrier = JRequest::getVar('txtReturnCarrier', '', 'post');
        $txtReturnReason = JRequest::getVar('txtReturnReason', '', 'post');
        $txtOriginalOrderNumber= JRequest::getVar('txtOriginalOrderNumber', '', 'post');
        $txtMerchantNumber = JRequest::getVar('txtMerchantNumber', '', 'post');
        $txtSpecialInstructions = JRequest::getVar('txtSpecialInstructions', '', 'post');


        $file = JRequest::getVar('fluReturnShippingLabel', null, 'files', 'array');
        if($file){
            jimport('joomla.filesystem.file');
            $filename = JFile::makeSafe($file['name']);
            $src = $file['tmp_name'];
            $TARGET=$this->GUIDv4();
            $dest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$filename;
            $dest1 = $TARGET.'/'.$filename;
            if($filename)
            JFile::upload($src, $dest);
        }else{    
        $dest1=1;
        }
        
    	if($CustId!=""){
           $status=Controlbox::getareturnshippment($CustId, $txtqty,$txtBackCompany, $txtReturnAddress,$txtReturnCarrier, $txtReturnReason, $txtOriginalOrderNumber,$txtMerchantNumber,$txtSpecialInstructions, $dest1,$txtWArehousid,$txtIdkid);
        }

       
        if($status==""){
            
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess', false));
        }else{
            if($status == "Your Request For Return Has Been Placed"){
                $app->enqueueMessage(Jtext::_('COM_USERPROFILE_RETURN_SUCCESS'), 'notice');
            }else{
                $app->enqueueMessage($status, 'notice');
            }
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess', false));
        }    
   
	}    


	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function holdshippment()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $idk = explode(":",JRequest::getVar('idk', '', 'post'));
        $txtWArehousid=$idk[0];
        $txtIdkid=$idk[1];
        $txtqty=JRequest::getVar('qty', '', 'post');
        $txtReturnReason = JRequest::getVar('txtReturnReason', '', 'post');

        //Redirect to a page of your choice
    	if($CustId!=""){
           $status=Controlbox::getaholdshippment($CustId, $txtqty,$txtReturnReason,$txtWArehousid,$txtIdkid);
        }


        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess', false));
        }else{
           
            if($status == "Your Order Is On Hold"){
                 $app->enqueueMessage(JText::_('COM_USERPROFILE_HOLD_POPUP_SUCCESS'), 'notice');
            }else{
            $app->enqueueMessage($status, 'notice');
            }
            
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess', false));
        }    
   
	}    

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function discardshippment()
	{
	    $app = JFactory::getApplication();
        $CustId = JRequest::getVar('user', '', 'post');
        $idk = explode(":",JRequest::getVar('idk', '', 'post'));
        $txtWArehousid=$idk[0];
        $txtQty=JRequest::getVar('qty', '', 'post');
        $txtIdkid=$idk[2];
        $txtReturnReason = JRequest::getVar('txtReturnReason', '', 'post');

        //Redirect to a page of your choice
    	if($CustId!=""){
           $status=Controlbox::getdiscardshippment($CustId,$txtQty, $txtReturnReason,$txtWArehousid,$txtIdkid);
        }


        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess&c=2', false));
        }else{
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=orderprocess&c=2', false));
        }    
   
	}    



	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function createtickets()
	{
	    $app = JFactory::getApplication();
        $UserId = JRequest::getVar('user', '', 'post');
        $txtticketDescStr = JRequest::getVar('ticketDescStr', '', 'post');
        $txtTicketNumberStr = JRequest::getVar('ticketNumberStr', '', 'post');

        //Redirect to a page of your choice
    	if($UserId!=""){
           $status=Controlbox::submitTicket($UserId, $txtticketDescStr,$txtTicketNumberStr);
        }


        if($status==""){
            $app->enqueueMessage($status, 'notice');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=tickets', false));
        }else{
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=tickets', false));
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
        require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';
        $user = JRequest::getVar('user', '', 'get');
        $paymenttype = JRequest::getVar('paymenttype', '', 'get');
        $wherhourec = JRequest::getVar('wherhourec', '', 'get');
        $invidk = JRequest::getVar('invidk', '', 'get');
        $qty = JRequest::getVar('qty', '', 'get');
        
        $destination =JRequest::getVar('destination', '', 'get');
        $volres =JRequest::getVar('volres', '', 'get');
        $munits =JRequest::getVar('munits', '', 'get');
        $source =JRequest::getVar('source', '', 'get');
        $shiptype =JRequest::getVar('shiptype', '', 'get');
        $service=JRequest::getVar('tyserv', '', 'get');
        $dvalue=JRequest::getVar('dvalue', '', 'get');
        
        $weight=JRequest::getVar('weight', '', 'get');
        $dtunit=JRequest::getVar('dtunit', '', 'get');
        $length=JRequest::getVar('length', '', 'get');
        $width= JRequest::getVar('width', '', 'get');
        $height=JRequest::getVar('height', '', 'get');
        $consignee=JRequest::getVar('consignee', '', 'get');
        $countryid = JRequest::getVar('countryid', '', 'get');
        
         $stateflag = JRequest::getVar('stateflag', '', 'get');
         $stateflagCalc = JRequest::getVar('stateflagCalc', '', 'get');
        if($countryid!="" &&  $stateflag!=""){
            echo UserprofileHelpersUserprofile::getStatesList($countryid,'');
            exit;
        }
        
        $addserlistselflag = JRequest::getVar('addserlistselflag', '', 'get');
        $qntstr = JRequest::getVar('qntstr', '', 'get');
        $wrhsstr = JRequest::getVar('wrhsstr', '', 'get');
        
        if($addserlistselflag == 1){
             $data = Controlbox::getBillFormAdditionalServices($wrhsstr,$qntstr);
             $listArr=array();
                foreach($data as $list){
                    $listArr[] = $list->id_AddnlServ;
                }
             echo implode(",",$listArr);   
            exit;
        }
        
        
        if($countryid!="" &&  $stateflagCalc!=""){
            echo UserprofileHelpersUserprofile::getStatesList($countryid,'');
            exit;
        }
        
         $stateflagpickup = JRequest::getVar('stateflagpickup', '', 'get');
        if($countryid!="" &&  $stateflagpickup!=""){
            echo UserprofileHelpersUserprofile::getStatesListPickup($countryid);
            exit;
        }
        
        
        $stateid = JRequest::getVar('stateid', '', 'get');
        $countryid = JRequest::getVar('state', '', 'get');
        $cityid = JRequest::getVar('cityid', '', 'get');
        $cityflag = JRequest::getVar('cityflag', '', 'get');
        
        
        if($stateid!="" &&  $cityflag!=""){
            echo UserprofileHelpersUserprofile::getCitiesList($countryid,$stateid,$cityid);
            exit;
        }
        
        $cityflagpickup = JRequest::getVar('cityflagpickup', '', 'get');
        if($stateid!="" &&  $cityflagpickup!=""){
            echo UserprofileHelpersUserprofile::getCitiesListPickup($stateid);
            exit;
        }

        $shippmentflag = JRequest::getVar('shippmentflag', '', 'get');
        if($paymenttype!="" &&  $shippmentflag!=""){
           $service=explode(",",$service);
           $sr=explode(",",$source);
           $dt=explode(",",$destination);
           $mr=explode(",",$munits);
           $bustype = JRequest::getVar('bustype', '', 'get');
           $services=0;
           if($paymenttype=="DHL"){
               echo UserprofileHelpersUserprofile::getShippmentDhlDetails($user,$paymenttype,$wherhourec,$invidk,$qty,$dt[0],$volres,$mr[0],$sr[0],$shiptype,$services,$dvalue,$weight,$dtunit,$length,$width,$height,$consignee);
               exit;
           }
           else
           //$services= UserprofileHelpersUserprofile::getShippmentDetailsValues($mr[0],$shiptype,$service[0],$sr[0],$dt[0]);
           echo UserprofileHelpersUserprofile::getShippmentDetails($user,$paymenttype,$wherhourec,$invidk,$qty,$dt[0],$volres,$mr[0],$sr[0],$shiptype,$services,$dvalue,$bustype);
           exit;
        }
        $shippment2flag = JRequest::getVar('shippment2flag', '', 'get');
        if($shippment2flag!=""){
           $service=explode(",",$service);
           $sr=explode(",",$source);
           $dt=explode(",",$destination);
           $mr=explode(",",$munits);
           $services=0;
           //$services= UserprofileHelpersUserprofile::getShippmentDetailsValues($mr[0],$shiptype,$service[0],$sr[0],$dt[0]);
           echo UserprofileHelpersUserprofile::getShippment2Details($user,$paymenttype,$wherhourec,$invidk,$qty,$dt[0],$volres,$mr[0],$sr[0],$shiptype,$services,$dvalue);
           exit;
        }


        $adduserid = JRequest::getVar('adduserid', '', 'get');
        $adduserflag = JRequest::getVar('adduserflag', '', 'get');
        if($adduserid!="" &&  $adduserflag!=""){
            echo UserprofileHelpersUserprofile::getBindShipingAddress($adduserid,'');
            exit;
        }
        $momentoid = JRequest::getVar('momentoid', '', 'get');
        $momentoflag = JRequest::getVar('momentoflag', '', 'get');
        if($momentoid!="" &&  $momentoflag!=""){
            echo UserprofileHelpersUserprofile::getMomentoLog($momentoid);
            exit;
        }
        $orderdeletetype = JRequest::getVar('orderdeletetype', '', 'get');
        $orderdeleteflag = JRequest::getVar('orderdeleteflag', '', 'get');
        if($orderdeletetype!="" &&  $orderdeleteflag!=""){
            echo UserprofileHelpersUserprofile::getDeleteOrder($orderdeletetype);
            exit;
        }
        $fnameid =JRequest::getVar('fnameid', '', 'get');
        $lnameid =JRequest::getVar('lnameid', '', 'get');
        $fnameflag=JRequest::getVar('fnameflag', '', 'get');
        if($fnameflag!=""){
           $userids=JRequest::getVar('userid', '', 'get');
           echo UserprofileHelpersUserprofile::getFnameDetails($userids,$fnameid,$lnameid);
           exit;
        }
        

        $orderupdatetype = JRequest::getVar('orderupdatetype', '', 'get');
        $orderupdateflag = JRequest::getVar('orderupdateflag', '', 'get');
        if($orderupdatetype!="" &&  $orderupdateflag!=""){
            echo UserprofileHelpersUserprofile::getUpdatePurchaseDetails($orderupdatetype);
            exit;
        }

        $ordershiptype = JRequest::getVar('ordershiptype', '', 'get');
        $ordershipflag = JRequest::getVar('ordershipflag', '', 'get');
        if($ordershiptype!="" &&  $ordershipflag!=""){
            echo UserprofileHelpersUserprofile::getShipDetails($ordershiptype);
            exit;
        }

        $shopperdeletetype = JRequest::getVar('shopperdeletetype', '', 'get');
        $shopperdeleteflag = JRequest::getVar('shopperdeleteflag', '', 'get');
        if($shopperdeletetype!="" &&  $shopperdeleteflag!=""){
            echo UserprofileHelpersUserprofile::getDeleteShopper($shopperdeletetype);
            exit;
        }
        
            
        $getpackagetype = JRequest::getVar('getpackagetype', '', 'get');
        $getpackageflag = JRequest::getVar('getpackageflag', '', 'get');
        if($getpackagetype!="" &&  $getpackageflag!=""){
            echo UserprofileHelpersUserprofile::getPackageDetails($getpackagetype);
            exit;
        }

        $munits = JRequest::getVar('munits', '', 'get');
        $tos = JRequest::getVar('tos', '', 'get');
        $stype = JRequest::getVar('stype', '', 'get');
        $source = JRequest::getVar('source', '', 'get');
        $dt = JRequest::getVar('dt', '', 'get');
        $length = JRequest::getVar('length', '', 'get');
        $width = JRequest::getVar('width', '', 'get');
        $height = JRequest::getVar('height', '', 'get');
        $qty = JRequest::getVar('qty', '', 'get');
        $gwt = JRequest::getVar('gwt', '', 'get');
        $wtunits = JRequest::getVar('wtunits', '', 'get');
        
        $busnesstype = JRequest::getVar('bustype', '', 'get');
        if($busnesstype == 'undefined'){
            $bustype = '';
        }else{
            $bustype = $busnesstype;
        }

        $getcalculatetype = JRequest::getVar('getcalculatetype', '', 'get');
        $getcalculateflag = JRequest::getVar('getcalculateflag', '', 'get');
        $user=JRequest::getVar('userid', '', 'get');
        if($getcalculatetype!="" &&  $getcalculateflag!=""){
            echo UserprofileHelpersUserprofile::getCalculationShipping($user,$munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$bustype);
            exit;
        }
        $getcalculatortype = JRequest::getVar('getcalculatortype', '', 'get');
        $getcalculatorflag = JRequest::getVar('getcalculatorflag', '', 'get');
        if($getcalculatortype!="" &&  $getcalculatorflag!=""){
            echo UserprofileHelpersUserprofile::getCalculatorMesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits);
            exit;
        }
        
        
        
        $value = JRequest::getVar('volume', '', 'get');
        $vmetric = JRequest::getVar('valuemetric', '', 'get');
        
        $state = JRequest::getVar('state', '', 'get');
        $city = JRequest::getVar('city', '', 'get');
        $zip = JRequest::getVar('zip', '', 'get');
        $address = JRequest::getVar('address', '', 'get');
        
        $dstate = JRequest::getVar('dstate', '', 'get');
        $dcity = JRequest::getVar('dcity', '', 'get');
        $dzip = JRequest::getVar('dzip', '', 'get');
        $daddress = JRequest::getVar('daddress', '', 'get');
        $getcalculatingtype = JRequest::getVar('getcalculatingtype', '', 'get');
        $getcalculatingflag = JRequest::getVar('getcalculatingflag', '', 'get');
        if($getcalculatingtype!="" &&  $getcalculatingflag!=""){
            
            //echo UserprofileHelpersUserprofile::getCalculatingMesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric);
            echo UserprofileHelpersUserprofile::getCalculatingnewMesurements($getcalculatingtype,$munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric,$state,$city,$zip,$address,$dstate,$dcity,$dzip,$daddress);
            exit;
        }
        

        /*
        if($getcalculatingtype!="" &&  $getcalculatingflag!=""){
            echo UserprofileHelpersUserprofile::getCalculatingMesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric);
            exit;
        }*/
        $getcalculatingtype = JRequest::getVar('getcalculatingtype', '', 'get');
        $getcalculating2flag = JRequest::getVar('getcalculating2flag', '', 'get');
        $value = JRequest::getVar('volume', '', 'get');
        $vmetric = JRequest::getVar('valuemetric', '', 'get');
        if($getcalculatingtype!="" &&  $getcalculating2flag!=""){
            echo UserprofileHelpersUserprofile::getCalculating2Mesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric);
            exit;
        }
        
        $getuscalcflag = JRequest::getVar('getuscalcflag', '', 'get');
        if($getuscalcflag==1){
            $c = JRequest::getVar('cid', '', 'get');
            echo UserprofileHelpersUserprofile::getCalcudetailsforus($c);
            exit;
        }
        
        
        $userid = JRequest::getVar('userid', '', 'get');
        $deletedowntype = JRequest::getVar('deletedowntype', '', 'get');
        $deletedownflag = JRequest::getVar('deletedownflag', '', 'get');
        if($deletedowntype!="" &&  $deletedownflag!=""){
            echo UserprofileHelpersUserprofile::deleteDownloadfile($userid,$deletedowntype);
            exit;
        }

        $gettrackexisttype = JRequest::getVar('trackexisttype', '', 'get');
        $gettrackexistflag = JRequest::getVar('trackexistflag', '', 'get');
        if($gettrackexisttype!="" &&  $gettrackexistflag!=""){
            echo UserprofileHelpersUserprofile::getExistTracking($gettrackexisttype);
            exit;
        }
        
         $user = JRequest::getVar('user', '', 'get');
        $trackingid = JRequest::getVar('trackingid', '', 'get');
        $gettrackexistticketflag = JRequest::getVar('trackexistticketflag', '', 'get');
        if($gettrackexistticketflag!=""){
            echo UserprofileHelpersUserprofile::getExistTrackingTicket($trackingid,$user);
            exit;
        }

        $getuserid = JRequest::getVar('userid', '', 'get');
        $getdocumentsflag = JRequest::getVar('documentsflag', '', 'get');
        if($getuserid!="" &&  $getdocumentsflag!=""){
            echo UserprofileHelpersUserprofile::getDocumentListAjax($getuserid);
            exit;
        }

        $getquotid = JRequest::getVar('quotid', '', 'get');
        $getquotationflag = JRequest::getVar('quotationflag', '', 'get');
        if($getquotid!="" &&  $getquotationflag!=""){
            echo UserprofileHelpersUserprofile::getQuotationProcess($getquotid);
            exit;
        }
        $getquotid = JRequest::getVar('quotid', '', 'get');
        $quotationorderflag = JRequest::getVar('quotationorderflag', '', 'get');
        if($getquotid!="" &&  $quotationorderflag!=""){
            echo UserprofileHelpersUserprofile::getPickupOderInfo($userid,$getquotid);
            exit;
        }

        $useridtype = JRequest::getVar('useridtype', '', 'get');
        $getadduserflag = JRequest::getVar('getadduserflag', '', 'get');
        if($useridtype!="" &&  $getadduserflag!=""){
            echo UserprofileHelpersUserprofile::getadduserfieldsInfo($userid,$useridtype);
            exit;
        }
        $getadduserconsigflag = JRequest::getVar('getadduserconsigflag', '', 'get');
        if($getadduserconsigflag!=""){
            echo UserprofileHelpersUserprofile::getadduserconsigInfo();
            exit;
        }
        
        $getaddusertypeflag = JRequest::getVar('getaddusertypeflag', '', 'get');
        $user = JRequest::getVar('user', '', 'get');
        $usertype = JRequest::getVar('usertype', '', 'get');
        
        if($getaddusertypeflag!=""){
            echo UserprofileHelpersUserprofile::getaddusertypeInfo($user,$usertype);
            exit;
        }
        
        $deleteadduserid = JRequest::getVar('deleteadduserid', '', 'get');
        $deleteadduserflag = JRequest::getVar('deleteadduserflag', '', 'get');
        if($deleteadduserid!="" && $deleteadduserflag!=""){
            echo UserprofileHelpersUserprofile::getadduserdeleteInfo($deleteadduserid);
            exit;
        }

        $user=JRequest::getVar('user', '', 'get');
        $gettypeuser = JRequest::getVar('typeuser', '', 'get');
        $getid = JRequest::getVar('id', '', 'get');
        $getfname = JRequest::getVar('fname', '', 'get');
        $getlname = JRequest::getVar('lname', '', 'get');
        $getcountry = JRequest::getVar('country', '', 'get');
        $getstate = JRequest::getVar('state', '', 'get');
        $getcity = JRequest::getVar('city', '', 'get');
        $getzip = JRequest::getVar('zip', '', 'get');
        $getaddress = JRequest::getVar('address', '', 'get');
        $getaddress2 = JRequest::getVar('address2', '', 'get');
        $getemail = JRequest::getVar('email', '', 'get');
        $idtypeTxt = JRequest::getVar('idtypeTxt', '', 'get');
        $idvalueTxt = JRequest::getVar('idvalueTxt', '', 'get');
        $getadduserpayflag = JRequest::getVar('getadduserpayflag', '', 'get');
        if($gettypeuser!="" && $getadduserpayflag!=""){
            echo UserprofileHelpersUserprofile::getadduserpay($user,$gettypeuser,$getid,$getfname,$getlname,$getcountry,$getstate,$getcity,$getzip,$getaddress,$getaddress2,$getemail,$idtypeTxt,$idvalueTxt);
            exit;
        }
        $getadduserdataflag = JRequest::getVar('getadduserdataflag', '', 'get');
        if($getadduserdataflag!=""){
            echo UserprofileHelpersUserprofile::getadduserselectpay($user);
            exit;
        }
        $user=JRequest::getVar('user', '', 'get');
        $getshoppersubmitflag = JRequest::getVar('shoppersubmitflag', '', 'get');
        if($user!="" && $getshoppersubmitflag!=""){

            $amtStr = JRequest::getVar('amtStr', '', 'get');
            $cardnumberStr = JRequest::getVar('cardnumberStr', '', 'get');
            $txtccnumberStr = JRequest::getVar('txtccnumberStr', '', 'get');
            $MonthDropDownListStr = JRequest::getVar('MonthDropDownListStr', '', 'get');
            $txtNameonCardStr = JRequest::getVar('txtNameonCardStr', '', 'get');
            $YearDropDownListStr = JRequest::getVar('YearDropDownListStr', '', 'get');
            $txtSpecialInStr = JRequest::getVar('hiddentxtSpecialIn', '', 'get');
            $CustId = JRequest::getVar('user', '', 'get');
            
            $txtTaxesStr = JRequest::getVar('hiddentxtTaxes', '', 'get');
            $txtShippChargesStr = JRequest::getVar('hiddentxtShippCharges', '', 'get');
            
            $ItemIdsStr = JRequest::getVar('hiddenItemIds', '', 'get');
            $ItemQuantityStr = JRequest::getVar('hiddenItemQuantity', '', 'get');
            $ItemSupplierIdStr = JRequest::getVar('hiddenItemSupplierId', '', 'get');
    
           
        	if($CustId!=""){
               $status=UserprofileHelpersUserprofile::submitpayshopperassist($CustId,$amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$txtSpecialInStr,$txtTaxesStr,$txtShippChargesStr,$ItemIdsStr,$ItemQuantityStr,$ItemSupplierIdStr);
            }
            $input = JFactory::getApplication()->input;
            $input->set('invoice', $status);
            if(strpos($status, ':')===false){
                $app->enqueueMessage($status, 'notice');
    		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=shopperassist', false));
            }else{
                $app->enqueueMessage(Jtext::_('COM_USERPROFILE_SHOPPER_ASSIST_PAYMENT_SUCCESS'), 'notice');
                $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=response&res='.base64_encode($status), false));
            } 
        }
        
        $citygetflag = JRequest::getVar('citygetflag', '', 'get');
        if($citygetflag!=""){
            
            $cid = JRequest::getVar('cid', '', 'get');
            $sid = JRequest::getVar('sid', '', 'get');
            $citid = JRequest::getVar('citid', '', 'get');
            echo UserprofileHelpersUserprofile::getcitiesInfo($cid,$sid,$citid);
            exit;
        }
        $uploadflag = JRequest::getVar('uploadflag', '', 'get');
        $imginv = $_FILES['file']['name'];
        if($uploadflag!="" && $imginv!=""){
            jimport('joomla.filesystem.file');
            $TARGET=$this->GUIDv4();
            $invf='';
            $profilepicname =JFile::makeSafe($imginv);
            $photodest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$profilepicname;
            $invf = $TARGET.'/'.$profilepicname;
            JFile::upload($_FILES['file']['tmp_name'], $photodest);
            echo $invf;
            exit;
        }
        
        // convenience fee
        
         $paypaltflag = JRequest::getVar('paypalflag', '', 'get');
        if($paypaltflag!=""){
            $amount = JRequest::getVar('amount', '', 'get');
            echo UserprofileHelpersUserprofile::getpaypalcharge($amount);
            exit;
        }
        
         // convenience fee square
        
         $squareflag = JRequest::getVar('squareflag', '', 'get');
        if($squareflag!=""){
            $amount = JRequest::getVar('amount', '', 'get');
            echo UserprofileHelpersUserprofile::getsquarecharge($amount);
            exit;
        }
        
        // convenience fee square
        
         $convflag = JRequest::getVar('convflag', '', 'get');
         $gateway = JRequest::getVar('gateway', '', 'get');
        if($convflag!=""){
            $amount = JRequest::getVar('amount', '', 'get');
            echo UserprofileHelpersUserprofile::getconvcharge($amount,$gateway);
            exit;
        }
        
         // update branch address
        
        $updatebranchflag = JRequest::getVar('updatebranchflag', '', 'get');
        if($updatebranchflag!=""){
            $custId = JRequest::getVar('custid', '', 'get');
            $branch = JRequest::getVar('branch', '', 'get');
            echo UserprofileHelpersUserprofile::updateBranchAddress($branch,$custId);
            exit;
        }
        
        $prexistflag = JRequest::getVar('prexistflag', '', 'get');
        if($prexistflag==1){
            $prname= JRequest::getVar('txtProjectname', '', 'get');
            echo UserprofileHelpersUserprofile::getexistProjectname($prname);
            exit;
        }
        
          if($prexistflag==2){
            $prname= JRequest::getVar('ProjectnameTxt', '', 'get');
            echo UserprofileHelpersUserprofile::getexistProjectname($prname);
            exit;
        }
        
         $fnskuexistflag= JRequest::getVar('fnskuexistflag', '', 'get');
        if($fnskuexistflag==1){
            $txtfnsku= JRequest::getVar('txtfnsku', '', 'get');
            echo UserprofileHelpersUserprofile::getexistFnsku($txtfnsku);
            exit;
        }
        
         $user = JRequest::getVar('user', '', 'get');
        $prfupdatetype = JRequest::getVar('prfupdatetype', '', 'get');
        $prfupdateflag = JRequest::getVar('prfupdateflag', '', 'get');
        if($prfupdateflag!=""){
            echo UserprofileHelpersUserprofile::getprojetdetails($user,$prfupdatetype);
            exit;
        }
        
        $orderdeletetype = JRequest::getVar('orderdeletetype', '', 'get');
        $projectdeleteflag = JRequest::getVar('projectdeleteflag', '', 'get');
        if($orderdeletetype!="" &&  $projectdeleteflag!=""){
            echo UserprofileHelpersUserprofile::getDeleteproject($orderdeletetype);
            exit;
        }
        
         $projflag = JRequest::getVar('projflag', '', 'get');
         $user = JRequest::getVar('user', '', 'get');
         $project= JRequest::getVar('project', '', 'get');
        if($projflag!=""){
            echo UserprofileHelpersUserprofile::getallProjectdetails($user,$project);
            exit;
        }
        
        
        $shiptypeflag = JRequest::getVar('shippmenttypeflag', '', 'get');
        $shiptypecalcflag = JRequest::getVar('shippmenttypecalcflag', '', 'get');
        $dest = JRequest::getVar('destination', '', 'get');
        $src = JRequest::getVar('source', '', 'get');
        $shiptype = JRequest::getVar('shiptype', '', 'get');
        if($shiptypeflag == 1){
            echo UserprofileHelpersUserprofile::getServiceType($src,$dest,$shiptype);
            exit;
        }
        if($shiptypecalcflag == 1){
            echo UserprofileHelpersUserprofile::getServiceTypeCalc($src,$dest,$shiptype);
            exit;
        }
        
        /** get payment gateways **/
        $paymentmethodflag = JRequest::getVar('paymentmethodflag', '', 'get');
        $paymentmethod = JRequest::getVar('paymentmethod', '', 'get');
         if($paymentmethodflag == 1){
            echo Controlbox::getpaymentgateways($paymentmethod);
            exit;
        }
        
        /**end **/
        
        // for stripe integration through ajax call
        $paymentgatewayflag = JRequest::getVar('paymentgatewayflag', '', 'get');
         if($paymentgatewayflag == 1){
             
        $companyId = JRequest::getVar('companyId', '', 'get');
        $amtStr = JRequest::getVar('amount', '', 'get');
        $cardnumberStr = JRequest::getVar('cardnumberStr', '', 'get');
        $txtccnumberStr = JRequest::getVar('txtccnumberStr', '', 'get');
        $MonthDropDownListStr = JRequest::getVar('MonthDropDownListStr', '', 'get');
        $txtNameonCardStr = JRequest::getVar('txtNameonCardStr', '', 'get');
        $YearDropDownListStr = JRequest::getVar('YearDropDownListStr', '', 'get');
        $invidkStr = JRequest::getVar('invidkStr', '', 'get');
        $qtyStr = JRequest::getVar('qtyStr', '', 'get');
        $wherhourecStr = JRequest::getVar('wherhourecStr', '', 'get');
        $CustId = JRequest::getVar('user', '', 'get');
        $consignidStr = JRequest::getVar('consignidStr', '', 'get');
        $articleStr =JRequest::getVar('articleStr', '', 'get');
        $priceStr =JRequest::getVar('priceStr', '', 'get');
        $addSerStr =JRequest::getVar('addSerStr', '', 'get');
        $addSerCostStr =JRequest::getVar('addSerCostStr', '', 'get');
        $insuranceCost =JRequest::getVar('insuranceCost', '', 'get');
        $extAddSer =JRequest::getVar('extAddSer', '', 'get');
        $ratetypeStr =JRequest::getVar('ratetypeStr', '', 'get');
        $cc =JRequest::getVar('cc', '', 'get');
        $paymentgateway =JRequest::getVar('paymentgateway', '', 'get');
        $Conveniencefees =JRequest::getVar('Conveniencefees', '', 'get');
        $paypalinvoice =JRequest::getVar('paypalinvoice', '', 'get');
        $invf='';
        $filenameStr='';
        $shipservtStr = JRequest::getVar('shipservtStr', '', 'get');
        
        $elfe=explode(",",$invidkStr);
        $mgsr='';
        $invf='';
        $filenameStr='';
        $nameStr = "";
        $extStr = "";
        $i=0;
        
        $eew=explode(",",$paypalinvoice);
        
        foreach($eew as $res){
            
            $mgsr = substr($res,strlen($elfe[$i])+1);
            $imageNameArr=explode("/",$mgsr);
            $filenameStr.= $imageNameArr[1].',';
           
            if($res){
                $mgsr = substr($res,strlen($elfe[$i])+1);
                    $photodest = JPATH_SITE. "/media/com_userprofile/".$mgsr;
                    $image1 = file_get_contents($photodest);
                    $imageByteStream = base64_encode($image1);
                    $invf .= $imageByteStream.',';
            }else{
                $invf .= '0,';
            } 
            $i++;
        }
        
        $status=Controlbox::submitpayment($amtStr,$cardnumberStr,$txtccnumberStr,$MonthDropDownListStr,$txtNameonCardStr,$YearDropDownListStr,$invidkStr,$qtyStr,$wherhourecStr,$CustId,$specialinstructionStr,$cc,$paymentgateway,$shipservtStr,$consignidStr,$invf,$filenameStr,$articleStr,$priceStr,'','','',$ratetypeStr,$Conveniencefees,$addSerStr,$addSerCostStr,$companyId,$insuranceCost,$extAddSer);
        $input = JFactory::getApplication()->input;
        $input->set('invoice', $status);
        
        if(strpos($status, ':')===false){
               echo str_replace("##",":",$status);
        }else{
               $statusArr = explode(":",$status);
               echo $statusArr[2].':'.base64_encode($statusArr[0]);
               
        }    
            exit;
        }
        
        $paymentgatewayshopflag = JRequest::getVar('paymentgatewayshopflag', '', 'get');
        if($paymentgatewayshopflag == 1){
            
            $cardnumberStr = JRequest::getVar('cardnumberStr', '', 'get');
            $txtccnumberStr = JRequest::getVar('txtccnumberStr', '', 'get');
            $MonthDropDownListStr = JRequest::getVar('MonthDropDownListStr', '', 'get');
            $YearDropDownListStr = JRequest::getVar('YearDropDownListStr', '', 'get');
            $CustId = JRequest::getVar('user', '', 'get');
            $txtSpecialIn = JRequest::getVar('txtSpecialIn', '', 'get');
            $txtTaxesStr = JRequest::getVar('hiddentxtTaxes', '', 'get');
            $txtShippChargesStr = JRequest::getVar('hiddentxtShippCharges', '', 'get');
            $ItemIdsStr = JRequest::getVar('hiddenItemIds', '', 'get');
            $ItemQuantityStr = JRequest::getVar('hiddenItemQuantity', '', 'get');
            $ItemSupplierIdStr = JRequest::getVar('hiddenItemSupplierId', '', 'get');
            $txtPaymentMethod = "PPD"; 
            $paymentgateway = JRequest::getVar('paymentgateway', '', 'get');
            $amount = JRequest::getVar('amount', '', 'get');
            
            $status=Controlbox::submitpayshopperassist($CustId,$amount,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$txtSpecialIn,$txtTaxesStr,$txtShippChargesStr,$ItemIdsStr,$ItemQuantityStr,$ItemSupplierIdStr,$txtPaymentMethod,$paymentgateway,'','','');
            
            $input = JFactory::getApplication()->input;
            $input->set('invoice', $status);
            if(strpos($status, ':')===false){
                echo $status;
            }else{
                   $statusArr = explode(":",$status);
                   echo $statusArr[2].':'.base64_encode($statusArr[0]);
            } 
            exit;
            
        }
        
        $inscustdataflag = JRequest::getVar('inscustdataflag', '', 'post');
        $item_name = JRequest::getVar('item_name', '', 'post');
        $item_number = JRequest::getVar('item_number', '', 'post');
        $user = JRequest::getVar('user', '', 'post');
         if($inscustdataflag == 1){
              $res = Controlbox::insertcustdata($user,$item_name,$item_number);
              echo $res;
             exit;
         }
         
         $getaddserflag = JRequest::getVar('getaddserflag', '', 'get');
        $getaddsereditflag = JRequest::getVar('getaddsereditflag', '', 'get');
        $shiptype = JRequest::getVar('shiptype', '', 'get');
        $user = JRequest::getVar('user', '', 'get');
        if($getaddserflag==1){
          echo Controlbox::getnewservices1($user,$shiptype);
        
        exit;
        }
        if($getaddsereditflag==1){
            echo Controlbox::getnewservicesedit($user,$shiptype);
        //echo 'test';
        exit;
        }
        
          //** business type **//
       $getbusinesstypeflag = JRequest::getVar('getbusinesstypeflag', '', 'get');
        if($getbusinesstypeflag){
            $businessTypes = Controlbox::GetBusinessTypes($user);
            $typeArr = array();
            foreach($businessTypes as $type){
                $typeArr[$type->id_vals]=$type->is_visible;
            }
            
            //var_dump($typeArr['RAR']);exit;
            
            if(($typeArr['IPS'] == "false" || $typeArr['IPS'] == NULL) && ($typeArr['RAR']=="flase" || $typeArr['RAR'] == NULL) && $typeArr['AIR']=="true"){
                echo 0;
            }else{
                echo 1;
            }
            exit;
        }
        


	} // get ajax data end	

    function mydocumentsdownload(){
          
       $file=JRequest::getVar('downfile', '', 'post');
       $getdownfilenameone = JRequest::getVar('downfilenameone', '', 'post');
       $getdownfilenametwo = JRequest::getVar('downfilenametwo', '', 'post');
       $getdownfilenamethree = JRequest::getVar('downfilenamethree', '', 'post');
       $getdownfilenamefour = JRequest::getVar('downfilenamefour', '', 'post');
       $readfile=explode(":",$file);
       if($readfile[0]==1){$path=$getdownfilenameone;}
       if($readfile[0]==2){$path=$getdownfilenametwo;}
       if($readfile[0]==3){$path=$getdownfilenamethree;}
       if($readfile[0]==4){$path=$getdownfilenamefour;}
       $filecontent=base64_decode($readfile[1]); 
       //var_dump($filepath);exit;
       if(isset($filecontent)){
            // Get parameters
            //$filepath =  //$_SERVER['DOCUMENT_ROOT'].'/'.substr($file,strlen(JURI::base()));
            // Process download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' .filesize($filecontent));
            flush(); // Flush system output buffer
            echo stripslashes($filecontent);
            exit;
            
       }
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
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function projectrequestform()
	{
	   
	    $app = JFactory::getApplication();
        $customerid = JRequest::getVar('user', '', 'post');
        $txtAccountnumber = JRequest::getVar('txtAccountnumber', '', 'post');
        $txtInventory = JRequest::getVar('txtInventory', '', 'post');
        $txtAccountname = JRequest::getVar('txtAccountname', '', 'post');
        $txtProjectname = JRequest::getVar('txtProjectname', '', 'post');
        $dateTxt = JRequest::getVar('dateTxt', '', 'post');
        $projectid = JRequest::getVar('projectid', '', 'post');
        $txtProductTitle = JRequest::getVar('txtProductTitle', '', 'post');
        
        $labelsTxt="";
        $file = JRequest::getVar('uploadFiles', null, 'files', 'array');
        
        $TARGET=$this->GUIDv4();
        for($i=0;$i<count($file['name']);$i++){
            
            jimport('joomla.filesystem.file');
            $filename = JFile::makeSafe($file['name'][$i]);
            $src = $file['tmp_name'][$i];
            $dest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$filename;
            $dest1 = $TARGET.'/'.$filename;
            //Redirect to a page of your choice
            if($src){
                if(JFile::upload($src, $dest)){
                    $labelsTxt.=$dest1.',';
                } 
            }    
        }
        $txtFnsku = JRequest::getVar('txtFnsku', '', 'post');
        $txtFnskuquanity = JRequest::getVar('txtFnskuquanity', '', 'post');
        $txtUPC = JRequest::getVar('txtUPC', '', 'post');
        $txtSKU = JRequest::getVar('txtSKU', '', 'post');
        $txtFnsku=implode(",",$txtFnsku);
        $txtFnskuquanity=implode(",",$txtFnskuquanity);
        $txtUPC=implode(",",$txtUPC);
        $txtSKU=implode(",",$txtSKU);
        
        $txtServices = JRequest::getVar('txtService', '', 'post');
        foreach($txtServices as $serv){
            //echo '<br>'.$serv;
            $txtServicen=explode(":",$serv);
            $txtService[]=$txtServicen[1];
        }
        //Redirect to a page of your choice
        if($customerid!=""){
           $statuse=Controlbox::postprojectrequest($customerid,$projectid,$txtAccountnumber,$txtInventory,$txtAccountname,$txtProjectname,$txtFnsku,$txtFnskuquanity,$dateTxt,$txtProductTitle,$txtUPC,$txtSKU);
           //response code 200 for submit additional services
           $statusr=explode(":",$statuse);
           if($statusr[0]==200)
           $status=Controlbox::postprojectservicesrequest($customerid,$projectid,implode(",",$txtService));
           Controlbox::postprojectlabelsrequest($projectid,$labelsTxt);
         }
       
        if($status==" "){
            $app->enqueueMessage($statusr[1], 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=projectrequest', false));
        }else{
            $app->enqueueMessage($statusr[1], 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=projectrequest', false));
        }    
   
	} 
	
	
		/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function userupdateprojectrequest()
	{
	    $app = JFactory::getApplication();
        $itemid = JRequest::getVar('txtItemId', '', 'post');
        $customerid = JRequest::getVar('user', '', 'post');
        
        $AccountNumberTxt = JRequest::getVar('AccountNumberTxt', '', 'post');
        $AccountNameTxt = JRequest::getVar('AccountNameTxt', '', 'post');
        $InventoryTxt = JRequest::getVar('InventoryTxt', '', 'post');
        $ProjectnameTxt = JRequest::getVar('ProjectnameTxt', '', 'post');
        $OrderDateTxt = JRequest::getVar('OrderDateTxt', '', 'post');
        $productnameTxt = JRequest::getVar('productnameTxt', '', 'post');

        $idk = JRequest::getVar('idkTxt', '', 'post');
        $FnskuTxt = JRequest::getVar('FnskuTxt', '', 'post');
        $FnskuquanityTxt = JRequest::getVar('FnskuquanityTxt', '', 'post');
        $upcTxt = JRequest::getVar('upcTxt', '', 'post');
        $skuTxt = JRequest::getVar('skuTxt', '', 'post');
        $ServiceTxt = JRequest::getVar('ServiceTxt', '', 'post');
        $existlabels = JRequest::getVar('existlabels', '', 'post');
        $existlabel='';
        
         $TARGET=$this->GUIDv4();
         
        foreach($existlabels as $labels){
            
           
            $lb=explode("/",$labels);
            $lt=end($lb);
            $path=$lb[count($lb)-2];
            
            
                    jimport('joomla.filesystem.file');
                    JFolder::create(JPATH_SITE."/media/com_userprofile/".$TARGET);
                    
                    $file=JPATH_SITE."/media/com_userprofile/".$path."/".$lt;
                    $newfile=JPATH_SITE."/media/com_userprofile/".$TARGET."/".$lt;

                    
                    if(JFolder::exists(JPATH_SITE."/media/com_userprofile/".$path)){
                        JFile::copy ( $file , $newfile );
                    }else{
                        
                            $url = "http:".$labels;
                            
                            $data = file_get_contents($url);
                            
                            $new = JPATH_SITE."/media/com_userprofile/".$TARGET."/".$lt;
                            
                            file_put_contents($new, $data);
                    }
                   
            $ltm=count($lb)-2;
            $existlabel.=$TARGET.'/'.$lt.",";
        }
        
       
        
      
        
        $idk=implode(",",$idk);
        $FnskuTxt=implode(",",$FnskuTxt);
        $FnskuquanityTxt=implode(",",$FnskuquanityTxt);
        $upcTxt=implode(",",$upcTxt);
        $skuTxt=implode(",",$skuTxt);
        $ServiceTxt=implode(",",$ServiceTxt);

        $labelsTxts="";
        $file = JRequest::getVar('editUploadFiles', null, 'files', 'array');
        
        $TARGET=$this->GUIDv4();
        for($i=0;$i<count($file['name']);$i++){
            jimport('joomla.filesystem.file');
            $filename = JFile::makeSafe($file['name'][$i]);
            $src = $file['tmp_name'][$i];
            $dest = JPATH_SITE. "/media/com_userprofile/".$TARGET.'/'.$filename;
            $dest1 = $TARGET.'/'.$filename;
            //Redirect to a page of your choice
            if($src){
               if(JFile::upload($src, $dest)){
                 $labelsTxts.=$dest1.',';
                }
            }    
        }
        $labelsTxt=$labelsTxts.$existlabel;
       
        //Redirect to a page of your choice
        if($customerid!=""){
           $status=Controlbox::updateprojectrequest($itemid,$customerid,$idk,$AccountNumberTxt,$AccountNameTxt,$InventoryTxt,$ProjectnameTxt,$OrderDateTxt,$productnameTxt,$FnskuTxt,$FnskuquanityTxt,$upcTxt,$skuTxt,$ServiceTxt);
           Controlbox::postprojectlabelsrequest($itemid,$labelsTxt);
        }
        if($status==" "){
            $app->enqueueMessage("Please try later", 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=projectrequest', false));
        }else{
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=projectrequest', false));
        }    



	} 
	
		/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function inventoryalertsform()
	{
	    
	    $app = JFactory::getApplication();
        $customerid = JRequest::getVar('user', '', 'post');
        $inventoryTxt = JRequest::getVar('inventoryTxt', '', 'post');
        if($inventoryTxt == '')
        {
            $inventoryTxt = JRequest::getVar('txtInventory', '', 'post');
        }
        
   
        
        $pnameTxt = JRequest::getVar('pnameTxt', '', 'post');
        $cnameTxt = JRequest::getVar('cnameTxt', '', 'post');
        //$fnskuTxt = JRequest::getVar('fnskuTxt', '', 'post');
        //$quantityTxt = JRequest::getVar('quantityTxt', '', 'post');
        
        $file = JRequest::getVar('addinvoiceTxt', null, 'files', 'array');
        $proid=explode(":",$pnameTxt);
        if (strpos($pnameTxt,':') === false) {
            $proid='';
        }   
        if($file!=""){
            jimport('joomla.filesystem.file');
            $filename = "import.csv";
            $src = $file['tmp_name'];
            $dest = JPATH_SITE. "/media/com_userprofile/".$filename;
            JFile::upload($src, $dest);
            chmod($dest,0777);
            $handle = fopen($dest, "r");
            $i=0; 
            $status=0;
            while (($column = fgetcsv($handle, 10000, ",")) !== FALSE ) {
                if($i==0){
                    if(strcasecmp("request-date",$column[0])==0 && strcasecmp("order-id",$column[1])==0 && strcasecmp("shipment-date",$column[2])==0 && strcasecmp("sku",$column[3])==0 && strcasecmp("fnsku",$column[4])==0 && strcasecmp("disposition",$column[5])==0 && strcasecmp("shipped-quantity",$column[6])==0 && strcasecmp("carrier",$column[7])==0 && strcasecmp("tracking-number",$column[8])==0 && strcasecmp("upc",$column[9])==0 && strcasecmp("rma-value",$column[10])==0 ){
                    }else
                    $status=1;
                    
                }else{
                    if($status==0){
                        $rqdate[]=$column[0];       
                        if(strlen($column[1]) <= 100){
                            $orderid[]=$column[1];
                        }else{
                            $status=6;
                        }       
                        $shipmentdate[]=$column[2];       
                        $sku[]=$column[3];       
                        $fnsku[]=$column[4];
                        $rmavalue[]=$column[10];
                        $disposition[]=$column[5];       
                        if($column[6]>0){
                            $shippedquantity[]=$column[6];       
                        }else{
                            $status=4;
                        }
                        $carrier[]=$column[7];    
                        $upc[]=$column[9];    
                        if(strlen(Controlbox::getExistTracking($column[8]))==11){
                            $trackingnumber[]=$column[8];
                        }else{
                            if($column[8]=="" && $column[0]!=""){
                            $status=3;
                            }else{
                            $trackingnumberd=$column[8];
                            $status=2;
                            }
                        }
                
                        
                    }    
                }    
             $i++;    
            }  
        }
        $slength=array_unique( array_diff_assoc( $trackingnumber, array_unique( $trackingnumber ) ) );
        if(count($slength)>0){
            $status=5;
        }
        chmod($dest,0644);
        //Redirect to a page of your choice

        if($status==1){
		    $app->enqueueMessage('CSV file Format Not Valid', 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        }
        elseif($status==2){
		    $app->enqueueMessage($trackingnumberd.'---Trackng Id already existed', 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        }
        elseif($status==3){
		    $app->enqueueMessage('Trackng Id empty in upoaded file', 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        }
        //elseif($status==22){
		    //$app->enqueueMessage($fnskunumberd.'---Fnsku already existed', 'error');
		    //$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        //}
        //elseif($status==33){
		    //$app->enqueueMessage('Fnsku empty in upoaded file', 'error');
		    //$this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        //}
        elseif($status==4){
		    $app->enqueueMessage('Please check Quantity numbers only allowed in csv file', 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        }
        elseif($status==5){
		    $app->enqueueMessage('Please check Tracking id is repeated in csv file', 'error');
		    $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        }
        else{
            $status=Controlbox::inventoryalertsform($customerid, $rqdate, $orderid,$shipmentdate,$sku,$fnsku,$disposition,$shippedquantity,$carrier,$trackingnumber,$upc,$proid[0],$rmavalue,$inventoryTxt);
            $app->enqueueMessage($status, 'notice');
            $this->setRedirect(JRoute::_('index.php?option=com_userprofile&view=user&layout=inventoryalerts', false));
        }    
   
	}
	
	 /**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
   
    function downloadfile(){
        $file=JRequest::getVar('val', '', 'get');
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv; charset=UTF-8');
        if($file=="air"){
           $path='air.csv';
           $data= "Article Name,Quantity,Declared Value,Order Id,RMA Value\n";
           header('Content-Disposition: attachment; filename="'.basename($path).'"');
        }else{
           $path='examle.csv';
           $data= "request-date,order-id,shipment-date,sku,fnsku,disposition,shipped-quantity,carrier,tracking-number,upc,rma-value\n";
           header('Content-Disposition: attachment; filename="'.basename($path).'"');
        }
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        flush(); // Flush system output buffer
        echo $data;
        die();
    } 
    
    
    	public function getClientConfig()
	{
	        require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
	        
            $clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
            $clientConf = json_decode($clientConfigObj, true);
            $clients = $clientConf['ClientList'];
            
            $domainDetails = ModProjectrequestformHelper::getDomainDetails();
            $domainName =  $domainDetails[0]->Domain;
            
            $res='';
            foreach($clients as $client){ 
                if(strtolower($client['Domain']) == strtolower($domainName) ){   
                    $prealerts_text=$client['Prealert_success_text'];
                }
            }
            
            return $prealerts_text;
	    
	}
	
	public function addnewticket()
	{
	    
	    $app = JFactory::getApplication();
	    
	    $custId = JRequest::getVar('custId', '', 'post');
	    $ticketId = JRequest::getVar('ticketId', '', 'post');
	    $email = JRequest::getVar('email', '', 'post');
	    $tktDesc = JRequest::getVar('tktDesc', '', 'post');
	    $tktStatus = JRequest::getVar('tktStatus', '', 'post');
	    $tktCmnts = JRequest::getVar('tktCmnts', '', 'post');
	    $cmdtype = JRequest::getVar('cmdtype', '', 'post');
	    $trackingId = JRequest::getVar('trackingId', '', 'post');
	    
	    $statusStr=Controlbox::createnewticket($custId,$ticketId,$email,$tktDesc,$tktStatus,$tktCmnts,$cmdtype,$trackingId);
	    $statusArr = explode(":",$statusStr);
	    $status = $statusArr[0];
	    $statusDesc = $statusArr[1];
	    
	    if($status==1){
		    $app->enqueueMessage($statusDesc, 'notice');
		    $this->setRedirect(JRoute::_('index.php/en/user?layout=support_ticket', false));
        }else{
            $app->enqueueMessage($statusDesc, 'error');
		    $this->setRedirect(JRoute::_('index.php/en/user?layout=crate_ticket', false));
        }
	    
	}
    
  
}
