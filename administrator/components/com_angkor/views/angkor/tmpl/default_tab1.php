<?php 
noDirectAccess(); 
?>
<form method="post" action="index.php?option=com_angkor" name="adminForm" id="adminForm">
	<div class="angkorTempate">
		<div class="angkorTempate-inner">
			<div id="emailsList">
				<div class="languages" id="languageArea">
				<?php echo $this->languages_list;?>
				</div>
				<div class="emails" id="emails">
				<?php
					$option = JRequest::getCmd('option');
					echo JHtml::_('sliders.start','sliders-'.$option);
					$i=0;
					foreach($this->emailslist as $key=>$emails){
						$i++;
						$label = JText::_($key);
						
						echo JHtml::_('sliders.panel',$label, 'slider-' . $i);
						?>
						<table class="emailsList">
							<thead>
							<tr>
								<th><?php echo JText::_('COLUMN_ID');?></th>
								<th><?php echo JText::_('COLUMN_EVENT');?></th>
								<th><?php echo JText::_('COLUMN_TO');?></th>
								<th><?php echo JText::_('COLUMN_SPECIFIC');?></th>
							</tr>
							</thead>
						<?php
						$i=0;
						foreach($emails as $email){
							$i++;
							?>
							<tr>
								<td align="center"><?php echo $i;?></td>
								<td><?php echo '<a href="index.php?option=com_angkor&code='.$email['code'].'&view=angkor"class="email-links"><span>'.$email['title']. '</span></a>';?></td>
								<td><?php echo $email['to'];?></td>
								<td><?php echo $email['description'];?></td>
							</tr>
							<?php
						}			
						echo '</table>';
					}
					echo JHtml::_('sliders.end');
				?>
				</div>
			</div>
			<div id="emailTemplate">
				<?php echo $this->loadTemplate('email');?>
				<div class="clear"></div>
			</div>			
		</div>
		<div class="clear"></div>
		<br />
	</div>
	<input type="hidden" name="view" value="angkor">
	<input type="hidden" name="task" id="task" value="">
	<input type="hidden" name="option" value="com_angkor">
	<input type="hidden" name="mycss" id="mycss" value="" />
</form>

<script language="javascript">
	window.addEvent('load',function(){resizeEmailsList();});
</script>