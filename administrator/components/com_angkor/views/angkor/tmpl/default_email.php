<?php 
noDirectAccess(); 
					$option = JRequest::getCmd('option');
	echo JHtml::_('tabs.start','tabs-'.$option);
		$label = JText::_('EMAIL_TAB1');
		echo JHtml::_('tabs.panel',$label, 'tab-1');
		include('email_tab1.php');
		
		$label = JText::_('EMAIL_TAB2');
		echo JHtml::_('tabs.panel',$label, 'tab-2');
		include('email_tab2.php');
		
		$label = JText::_('EMAIL_TAB3');
		echo JHtml::_('tabs.panel',$label, 'tab-3');
		include('email_tab3.php');	
		
	echo JHtml::_('tabs.end');	
?>
<input type="hidden" name="lang"  id="lang" value="" />
<input type="hidden" name="code"  id="code" value="" />
<input type="hidden" name="id"  id="id" value="" />
<input type="hidden" name="changed"  id="changed" value="" />