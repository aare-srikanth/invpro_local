<?php 
noDirectAccess(); 

JHtml::_('behavior.switcher');
JHtml::_('behavior.tooltip');
angkor_Helper::loadAssets();
$this->document->setBuffer($this->loadTemplate('navigation'), 'modules', 'submenu');

?>

<div id="config-document">
	<div id="angkorMessage">test</div>
	<div id="page-angkorNavigator" class="tab">
		<div class="noshow">
			<?php include('default_tab1.php'); ?>
		</div>
	</div>
	<div id="page-cssNavigator" class="tab">
		<div class="noshow">
			<?php include('default_tab2.php'); ?>
		</div>
	</div>
</div>
<script language="javascript">
	Joomla.submitbutton = function(task){
		if(task=='apply'){
			if(jQuery('#page-angkorNavigator').css('display')!='none')
				ajaxSubmitEmailForm();
				
			if(jQuery('#page-cssNavigator').css('display')!='none')
				ajaxSubmitCSSForm();
		}
	}
</script>