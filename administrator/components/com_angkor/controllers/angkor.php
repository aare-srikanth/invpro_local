<?php

// no direct access
noDirectAccess();

class AngkorControllerAngkor extends angkorController
{
	function preview(){		
		ob_clean();
		
		$body = JRequest::getString('body','','default',JREQUEST_ALLOWHTML);				
		if(trim($body)=='')
			exit(0);
		$email = JFactory::getMailer();
		$email->Body = $body;
		//$body = angkor_Helper::parsingEmailCSS($email,true); //Embed Image
		$mycss = JRequest::getString('mycss','','default',JREQUEST_ALLOWHTML);			
		
		$body = angkor_Helper::parsingEmailCSS($email,false,$mycss); //Direct Link Image
		echo $email->Body;
		exit(0);
	}
	function save()
	{
		$model = $this->getModel('angkor');
		$model->save_mail();	
		$this->redirect('index.php?option=com_angkor',JText::_('SAVE_SUCCESS')); 
	}
	function apply()
	{
		$model = $this->getModel('angkor');
		$email = $model->save_mail();		
		$data=array();		
		$data['result']=1;
		$data['message']=JText::_('SAVE_SUCCESS');
		$data['id']=$email->id;
		$data['body']=$email->body;
		$data['embed_image']=$email->embed_image .'-'.JRequest::getString('embed_image');
		ob_clean();
		echo json_encode($data);
		exit(0);
	}
	function cancel()
	{
		$app = JFactory::getApplication();
		$app->redirect('index.php?option=com_angkor'); 
	}
}
?>