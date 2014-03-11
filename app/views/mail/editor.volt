{% extends "templates/editor_template.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('redactor/redactor.js')}}
	{{ stylesheet_link('redactor/redactor.css') }}
<script type="text/javascript">
	function iframeResize() {
		var iFrame = document.getElementById('iframeEditor');
		//iFrame.height = '';
		iFrame.height = iFrame.contentWindow.document.body.scrollHeight + "px";
	};
	var objMail = {{objMail}};
	
	function sendData(value) {
		//verHTML();
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax(
			{
			url: "{{url('mail/editor')}}/{{mail.idMail}}",
			type: "POST",			
			data: { editor: editor },
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function(){
				if(value == 'Next') {
					$(location).attr('href', "{{url('mail/plaintext')}}/{{mail.idMail}}"); 
				}
				else {
					$(location).attr('href', "{{url('mail/setup')}}/{{mail.idMail}}"); 
				}
			}
		});
		
	}
	
	function verHTML() {
		
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$('#preview-modal').modal('show');
		$.ajax({
			url: "{{url('mail/previeweditor')}}/{{mail.idMail}}/editor",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function(response) {
				//var a = response.script;
				//var x = response.response;
				$("#preview-modal").empty();
				$('#preview-modal').append('<span class="close-preview icon-remove icon-2x" data-dismiss="modal"></span>')
				$('<iframe frameborder="0" width="100%" height="100%" src="{{url('mail/previewdata')}}"/>').appendTo('#preview-modal');
				//$("#my-iframe").contents().find("head").append(a);
				//$("#my-iframe").contents().find("body").append(x);
			}
		});
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
		return false;
	}
	
	function createTemplate() {
		document.getElementById('iframeEditor').contentWindow.$('#newtemplatename').modal();
	}
	
	function saveContent() {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax({
			url: "{{url('mail/savecontent')}}/{{mail.idMail}}",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			}
		});
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
		return false;
	}
	
	function addGoogleAnalytics() {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		
		$.ajax({
			url: "{{url('mail/analytics')}}",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function(response) {
				$('#modal-simple').modal('show');
				$("#modal-body").empty();
				$('<input type="checkbox" class="add-google-analytics"' + analytics + '> Agregar seguimiento de Google Analytics a los siguientes enlaces: <br /><br />').appendTo('#modal-body');
				
				for (var i = 0; i < response.links.length; i++) {
					var checked = '';
					for(var j = 0; j < GA_links.length; j++) {
						if(GA_links[j] === response.links[i]) {
							checked = 'checked';
						}
					}
					$('<div class="google_analytics_container">&nbsp;&nbsp;&nbsp;<input type="checkbox" class="google_analytics_checkbox" ' + checked + '><span class="google_analytics_link">' + response.links[i] + '</span></div><br />').appendTo('#modal-body');
				}
				G_A_Select_Link();
				//$("#my-iframe").contents().find("head").append(a);
				//$("#my-iframe").contents().find("body").append(x);
			}
		});
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
		return false;
	};
	
	function G_A_Select_Link() {
		$(".add-google-analytics").on("click", function () {
			if (analytics === '') {
				analytics = 'checked';
				$("#analytics-active").show(); 
				$("#analytics-inactive").hide();
			}
			else {
				analytics = '';
				$("#analytics-inactive").show(); 
				$("#analytics-active").hide();
			}
		});
		
		$(".google_analytics_checkbox").off("click");
		$(".google_analytics_checkbox").on("click", function () {
			if(!$(this)[0].checked) {
				for(var i = 0; i < GA_links.length; i++) {
					if(GA_links[i] === $(this).parent().find('.google_analytics_link').text()) {
						GA_links.splice(i, 1);
					}
				}
			}
			else {
				GA_links.push($(this).parent().find('.google_analytics_link').text())
			}
		});
	};
</script>
{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span8 offset2">
			{{partial('partials/wizard_partial')}}
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<div class="btnoptions">
			<div class="box span12 optionsEditor">
				<div class="pull-right NextFromEditor">
					<button onclick="sendData('Next')" type="button" value="Siguiente" class="btn btn-blue">Siguiente <i class="icon-circle-arrow-right"></i></button>
				</div>
				<div class="pull-right BeforeFromEditor">
					<button onclick="sendData('Previous')" type="button" value="Anterior" class="btn btn-default"><i class="icon-circle-arrow-left"></i> Anterior</button>
				</div>
				<div class="pull-left VisualizeEditor">
					<a href="#preview-modal" onclick="verHTML(); return false;" class="btn btn-default">Visualizar  <i class="icon-eye-open"></i></a>
				</div>
				<div class="pull-left SaveTemplate">
					<button onclick="createTemplate()" type="button" value="Guardar como Plantilla" class="btn btn-black">Guardar como Plantilla <i class="icon-picture"></i></button>
				</div>
				<div class="pull-left SaveContent">
					<button onclick="saveContent()" type="button" value="Guardar" class="btn btn-blue">Guardar  <i class="icon-save"></i></button>
				</div>
				{#
				<div class="pull-left SaveTemplate">
					<a href="#modal-simple" onclick="addGoogleAnalytics()" type="button" class="btn btn-default">Seguimiento con Google Analytics <i class="icon-google-plus-sign"></i> <span id="analytics-active" style="color: #acd954; display: none;">Activo</span> <span id="analytics-inactive" style="color: #e8a397;">Inactivo</span></a>
				</div>
				#}
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}/{{mail.idMail}}" width="100%" onload="iframeResize()" seamless></iframe>
	</div>
	<div id="preview-modal" class="modal hide fade preview-modal">
	</div>
	
	<div id="modal-simple" class="modal hide fade" aria-hidden="false">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h6 id="modal-tablesLabel">Google Analytics</h6>
		</div>
		<div class="modal-body" id="modal-body">
			

		</div>
		<div class="modal-footer">
			<button class="btn btn-blue" data-dismiss="modal">Aceptar</button>
		</div>
	</div>
	<br />
{% endblock %}
