function getQueryValue(url,query_name){
	var queries=url.split("&");	
	for(var i=0;i<queries.length;i++){
		var query = queries[i].split('=');
		if(query[0]==query_name)
			return query[1];
	}
}
function resizeEmailsList(){
	jQuery('#emailTemplate').width(jQuery('.angkorTempate').width()-jQuery('#emailsList').width()-50);
	jQuery('#emailTemplate .current').width(jQuery('.angkorTempate').width()-jQuery('#emailsList').width()-50);
	if(jQuery('#emailsList').height()<jQuery('#emailTemplate').height()){			
		jQuery('#emailsList').height(jQuery('#emailTemplate').height());
		jQuery('#emails').height(jQuery('#emailTemplate').height() - jQuery('#languageArea').height()-7);		
	}
}
jQuery(window).ready(
					function(){							
						if(window.location.hash){
							var hash = window.location.hash.replace('#','');	
							
							var url ='index.php?option=com_angkor&'+hash;
							ajaxGetEmail(url);						
																			
							var selected_code=getQueryValue(hash,'code');
							jQuery('.email-links').each(
											    	function(index){
													jQuery(this).removeClass('selected-email');													
													var url = jQuery(this).attr('href') ;
													var code = getQueryValue(url,'code');
													if(code==selected_code)
														jQuery(this).addClass('selected-email');				
												}	
											);
							var selected_lang=getQueryValue(hash,'lang');
							jQuery('#language').val(selected_lang);
						}
						
						
						jQuery('#toolbar-apply').hide();
						jQuery('#toolbar-save').hide();
						jQuery('#toolbar-cancel').hide();	
						
						jQuery('#angkorNavigator').click(function(){
															resizeEmailsList();
															if(jQuery('#language').val()!='' &&  jQuery('#lang').val()!='')
																jQuery('#toolbar-apply').show();
															else
																jQuery('#toolbar-apply').hide();
														}
													);
						jQuery('#cssNavigator').click(function(){jQuery('#toolbar-apply').show();});
						
						jQuery('#adminForm').change(
												function(){
													if(jQuery('#code').val()!='')	
														jQuery('#changed').val(1);
												}
											);											
						jQuery('.tab-2 a').click(function(){
													showPreviewArea();	
												}
										);
						jQuery('.email-links').each(	
							function(index){
								jQuery(this).click(function(){													
													jQuery('.email-links').each(function(index){jQuery(this).removeClass('selected-email');});									
													jQuery(this).addClass('selected-email');													
													
													var url =jQuery(this).attr('href') + '&task=ajax&lang='+jQuery('#language').val();									
													window.location.hash=url.replace('index.php?option=com_angkor&','').replace('&view=angkor','');
													
													ajaxGetEmail(url);
													return false;
												}
											);
							
							}
						);
						
						jQuery('#language').change(
												function(){													
													if(jQuery('#code').val()!=''){
														var hash = 'view=angkor&code=' + jQuery('#code').val() + '&task=ajax&lang='+jQuery('#language').val();
														var url ='index.php?option=com_angkor&'+hash;
														window.location.hash=hash;
														
														ajaxGetEmail(url);
													}													
												}
											);
					}
				);

function ajaxGetEmail(url){
	if(jQuery('#changed').val()==1){
		var process = confirm('Do you want to leave the page unsaved?');

	}else
		var process = true;
	
	if(process==false)
		return false;
	
	jQuery.ajax(
	{
		url: url
		,success: function (data) {		
			jQuery('#lang').val(jQuery('#language').val());
				
			jQuery('#code').val(data.code);																		
			jQuery('#id').val(data.id);
			jQuery('#sender_name').val(data.sender_name);
			jQuery('#sender_email').val(data.sender_email);
			jQuery('#subject').val(data.subject);
			
			document.adminForm.embed_image.checked=data.embed_image;
						
			setEditorBody(data);
			jQuery('#parameterArea').html(data.parameters);
			jQuery('#changed').val(0);
			
			resizeEmailsList();
		}
		,error: function (data) {			
			window.location.reload();
		}
		,dataType:'json'
	}		
);

}
function getEditorBody(){
	var bodyHTML = document.getElementById('body').value.replace(/&lt;/g,'<').replace(/&gt;/g,'>');
	//jQuery('#body').html();	
	
	if(typeof(tinyMCE)!='undefined'){
		var bodyHTML = tinyMCE.get('body').getContent();
	}
	if(typeof(WFEditor)!='undefined'){
		var bodyHTML = WFEditor.getContent('body');
	}
	
	if(typeof(Joomla.editors.instances['body'])!='undefined'){ // Code Mirrors Editor													
		var bodyHTML = Joomla.editors.instances['body'].getCode();
	}	
	
	return bodyHTML;
}
function setEditorBody(data){
	data.body = data.body.replace(/&lt;/g,'<').replace(/&gt;/g,'>');
	document.getElementById('body').value = data.body;
	jQuery('#body').html(data.body);		
	
	if(typeof(tinyMCE)!='undefined'){ // TinyMCE Editor
		tinyMCE.execInstanceCommand('body', 'mceSetContent',true,data.body);																			
	}

	if(typeof(WFEditor)!='undefined'){ // JCE Editor
		WFEditor.setContent('body',data.body);
	}

	if(typeof(Joomla.editors.instances['body'])!='undefined'){ // Code Mirrors Editor
		Joomla.editors.instances['body'].setCode(data.body);
	}	
	showPreviewArea();	
	
	jQuery('#toolbar-apply').show();
	jQuery('#toolbar-save').show();
	jQuery('#toolbar-cancel').show();
}
function showPreviewArea(){
	var frame = document.getElementById("previewArea"),
	frameDoc = frame.contentDocument || frame.contentWindow.document;
	frameDoc.documentElement.innerHTML = "";
	
	var frm = document.adminForm;
	
	frm.mycss.value= getCSS();
	frm.task.value='preview';
	frm.target='previewArea';
	frm.submit(); 
	frm.task.value='';
	frm.target='_self';
}
function ajaxSubmitEmailForm(){	
	var hash =window.location.hash;
	var frm = jQuery('#adminForm');
	jQuery('#task').val('apply');
	
	var embed_image=0;
	if(document.adminForm.embed_image.checked)
		embed_image=1;
	jQuery.ajax(
		{
			type: frm.attr('method'),
			url: frm.attr('action'),
			data: {
					option:'com_angkor',
					view:'angkor',
					task:'apply',
					code:jQuery('#code').val(),
					lang:jQuery('#lang').val(),
					id:jQuery('#id').val(),
					sender_name:jQuery('#sender_name').val(),
					sender_email:jQuery('#sender_email').val(),		
					subject:jQuery('#subject').val(),
					body:getEditorBody(),					
					embed_image:embed_image
				}
				/*frm.serialize() - There is problem with HTML editor is not getting refresh*/,
			success: function (data) {
				jQuery('#id').val(data.id);
				jQuery('#task').val('');
				jQuery('#changed').val(0);
				
				window.location.hash = hash;
				
				var tmp_hash = window.location.hash.replace('#','');	
							
				var url ='index.php?option=com_angkor&'+tmp_hash;
				//alert(url);
				ajaxGetEmail(url);		
				
				jQuery('#angkorMessage').html('');
				jQuery('#angkorMessage').html(data.message);
				jQuery('#angkorMessage').show();
				jQuery('#angkorMessage').fadeOut(3000);
				
				
				
			}
			,dataType:'json'
		}
	);		
}
function getCSS(){
	var css = jQuery('#css').html();
	if(typeof(CodeMirrorEditor)!='undefined')
		css = CodeMirrorEditor.getValue();
	return css;
}
function ajaxSubmitCSSForm(){	
	var frm = jQuery('#adminCSSForm');
	
	//CodeMirrorEditor.save();
	
	
	var css = getCSS();
	jQuery.ajax(
		{
			type: frm.attr('method'),
			url: frm.attr('action'),
			data: {
					option:'com_angkor',
					view:'css',
					task:'apply',
					css:css					
				},
			success: function (data) {
				jQuery('#angkorMessage').html('');
				jQuery('#angkorMessage').html(data.message);
				jQuery('#angkorMessage').show();
				jQuery('#angkorMessage').fadeOut(3000);
				
				window.location.hash = 'view=angkor&code=' + jQuery('#code').val() + '&task=ajax&lang='+jQuery('#language').val();
			}
			,dataType:'json'
		}
	);		
}