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
		verHTML();
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax(
			{
			url: "{{url('mail/editor')}}/{{mail.idMail}}",
			type: "POST",			
			data: { editor: editor},
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
		
		$.ajax({
			url: "{{url('mail/previeweditor')}}/{{mail.idMail}}/mail",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function(response) {
				var e = document.createElement('div');
				e.innerHTML = response.response;
				$( "#content-template" ).empty();
				$('<iframe frameborder="0" width="100%" height="390px"/>').appendTo('#content-template').contents().find('body').append(e);
			}
		});
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
	}
	
	function createTemplate() {
		
		document.getElementById('iframeEditor').contentWindow.$('#newtemplatename').modal();
	}
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
					<a href="#preview-modal" data-toggle="modal" onclick="verHTML()" class="btn btn-default">Visualizar  <i class="icon-eye-open"></i></a>
				</div>
				<div class="pull-left SaveTemplate">
					<button onclick="createTemplate()" type="button" value="Guardar como Plantilla" class="btn btn-black">Guardar como Plantilla <i class="icon-picture"></i></button>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}/{{mail.idMail}}" width="100%" onload="iframeResize()" seamless></iframe>
	</div>
	<div id="preview-modal" class="modal hide fade preview-modal">
		<div class="modal-header">
			Previsualización de plantilla
		</div>
		<div class="modal-body">
			<div id="content-template">
				
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-black" data-dismiss="modal">x</button>
		</div>
	</div>
	<br />
{% endblock %}
