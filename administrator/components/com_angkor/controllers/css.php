<?php
// no direct access
noDirectAccess();

class AngkorControllerCSS extends angkorController
{
	function apply()
	{			
		$model = $this->getModel('css');
		$model->saveCSS();
		
		$data = array();
		$data['result']=1;
		$data['message']=JText::_('CSS_STORED');
		ob_clean();
		echo json_encode($data);
		exit(0);
	}
	
}
?>