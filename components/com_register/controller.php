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

/**
 * Class RegisterController
 *
 * @since  1.6
 */
class RegisterController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   mixed   $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
        //$this->_sendMail('madan.chunchu@iblesoft.com','strusername','strpassword','strcustid','straddressone','straddresstwo','strcity','strstate','strcountry','strpostalcode');
        $app  = JFactory::getApplication();
        $view = $app->input->getCmd('view', 'registers');
		$app->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
	

    
    
    
	public function _sendMail($strEmail,$strusername,$strpassword,$strcustid,$straddressone,$straddresstwo,$strcity,$strstate,$strcountry,$strpostalcode)
	{
		$mainframe =& JFactory::getApplication();
		
		$db		=& JFactory::getDBO();
		
		$sitename 		= $mainframe->getCfg('sitename');
		
		$mailfrom 		= $mainframe->getCfg('mailfrom');
		
		$fromname 		= $mainframe->getCfg('fromname');
		
		$siteURL		= JURI::base();
				
		$query = "Select * FROM #__angkor_emails Where code='SEND_MSG_ACTIVATE'";
		//$activationURL = $siteURL."index.php?option=com_registration&task=activate&activation=".$strActivation;
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();		
		
		$patterns = array('/{wp_username}/', '/{wp_custid}/', '/{wp_password}/', '/{wp_addressone}/',
		'/{sitename}/', '/{siteurl}/', '/{wp_addresstwo}/', '/{wp_city}/',
		'/{wp_state}/', '/{wp_country}/', '/{wp_zip}/'
		);
		
		$replacements_subject = array($strusername, $strcustid, $strpassword,$straddressone,		
		$sitename, $siteURL, $straddresstwo, $strcity,
		$strstate, $strcountry,$strpostalcode);
		
		$replacements_body = array($strusername, $strcustid, $strpassword,$straddressone,		
		$sitename, $siteURL,$straddresstwo, $strcity,
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

		$this->sendHTMLMail($mailfrom, $fromname,$strEmail, $subject, $message, 1);
		

	}	
	public function sendHTMLMail($from, $fromname, $recipient, $subject, $body, $mode = 0, $cc = null, $bcc = null, $attachment = null, $replyto = null, $replytoname = null)
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

}



