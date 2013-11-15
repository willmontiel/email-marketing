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
	
	function sendData() {
		
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		console.log(editor)
		$.ajax(
			{
			url: "{{url('mail/editor')}}/{{mail.idMail}}",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function(){
				$(location).attr('href', "{{url('mail/plaintext')}}/{{mail.idMail}}"); 
			}
		});
	}
	
	function verHTML() {
		
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		
		$.ajax(
			{
			url: "{{url('mail/previeweditor')}}",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function(response) {
				win = open("", "DisplayWindow", "toolbar=0, titlebar=yes , status=1, directories=yes, menubar=0, location=yes, directories=yes, width=700, height=650, left=1, top=0");
				win.document.write("" + response.response + "");
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
		<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}/{{mail.idMail}}" width="100%" onload="iframeResize()" seamless></iframe>
	</div>
	<br />
	<div class="row-fluid">
		<div class="btnoptions">
			<div class="span1 offset2">
				<input onclick="sendData()" type="button" value="Guardar" class="btn btn-blue">
			</div>
			<div class="span1">
				<input onclick="verHTML()" type="button" value="Visualizar" class="btn btn-black">
			</div>
			<div class="span1">
				<input onclick="createTemplate()" type="button" value="Guardar como Plantilla" class="btn btn-black">
			</div>
		</div>
	</div>
{% endblock %}
