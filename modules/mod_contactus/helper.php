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
class ModContactusHelper {

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */

    public static function getMailContact()
    {
        //var_dump($_POST);
        $strName=JRequest::getVar('txtName', '', 'post');
        $strEmail=JRequest::getVar('txtEmail', '', 'post');
        $strPhone=JRequest::getVar('txtPhone', '', 'post');
        $strMessage=JRequest::getVar('txtMessage', '', 'post');
        ModContactusHelper::_sendMail($strName,$strEmail,$strPhone,$strMessage);
        $mainframe =& JFactory::getApplication();
		$mainframe->enqueueMessage(JText::_('YOUR_CONTACT_FORM_IS_SUCCESSFULLY_SUBMITTED'), 'success');
    }
        
    /**
	* Email Sending Method 
	*
	* @access		  public
	*
	* @param string   $name
	* @param string   $email
	* @param string   $phone
	* @param string   $message
	*
	*/
	function _sendMail($strName,$strEmail,$strPhone,$strMessage)
	{
		$mainframe =& JFactory::getApplication();
		$db		=& JFactory::getDBO();
		
		//$strEmail='madan.chunchu@iblesoft.com';
		$sitename 		= $mainframe->getCfg('sitename');
		$mailfrom 		= $mainframe->getCfg('mailfrom');
		$fromname 		= $mainframe->getCfg('fromname');
		$siteURL		= JURI::base();
				
		$query = "Select * FROM #__angkor_emails Where code='SEND_CONTACT_MESSAGE'";
		$db->setQuery($query);
		$rows = $db->loadObjectList();		
		
		$patterns = array('/{fullname}/', '/{email}/', '/{phone}/', '/{message}/');
		$replacements_subject = array($strName, $strEmail, $strPhone,$strMessage,$sitename, htmlLink($siteURL));
		$replacements_body = array($strName, $strEmail, $strPhone,$strMessage,$sitename, htmlLink($siteURL));
		
		foreach($rows as $row)
		{			
			$subject 	= preg_replace($patterns, $replacements_subject, $row->subject);
			
			$subject 	= html_entity_decode($subject, ENT_QUOTES);
			
			$message 	= preg_replace($patterns, $replacements_body, $row->body);
			
			$message	= html_entity_decode($message, ENT_QUOTES);
			
			$sender_name = $row->sender_name;
			
			$sender_name = html_entity_decode($strName, ENT_QUOTES);
			
			$sender_email = $row->sender_email;
			
			$sender_email = html_entity_decode($strEmail, ENT_QUOTES);
		}
		//-----------------------------End Added-----------------------------
		
		$fromname = $sender_name;
		
		$mailfrom = $sender_email;
        
      
		sendHTMLMail($mailfrom, $fromname,$strEmail, $subject, $message, 1);
		
		$query = "Select * FROM #__angkor_emails Where code='SEND_CONTACT_ADMIN_MESSAGE'";
		$db->setQuery($query);
		$rows = $db->loadObjectList();		
		
		$patterns = array('/{fullname}/', '/{email}/', '/{phone}/', '/{message}/','/sitename/','/sendername/');
		$replacements_subject = array($strName, $strEmail, $strPhone,$strMessage,$sitename, htmlLink($siteURL));
		$replacements_body = array($strName, $strEmail, $strPhone,$strMessage,$sitename, htmlLink($siteURL));
		
		foreach($rows as $row)
		{			
			$subject 	= preg_replace($patterns, $replacements_subject, $row->subject);
			
			$subject 	= html_entity_decode($subject, ENT_QUOTES);
			
			$message 	= preg_replace($patterns, $replacements_body, $row->body);
			
			$message	= html_entity_decode($message, ENT_QUOTES);
			
			$sender_name = $row->sender_name;
			
			$sender_name = html_entity_decode($strName, ENT_QUOTES);
			
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