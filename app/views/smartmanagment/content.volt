{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('vendors/redactor/redactor.js')}}
	{{ stylesheet_link('vendors/redactor/redactor.css') }}
	<script type="text/javascript">
		function iframeResize() {
			var iFrame = document.getElementById('iframeEditor');
			//iFrame.height = '';
			iFrame.height = iFrame.contentWindow.document.body.scrollHeight + "px";
		};
		
		var objMail = {{smart.content}};
		
		function verHTML() {
			var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
			$.ajax({
				url: "{{url('smartmanagment/content')}}/" + {{smart.idSmartmanagment}},
				type: "POST",			
				data: { editor: editor},
				error: function(msg){
					$.gritter.add({class_name: 'error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Atención', text: msg, sticky: false, time: 7000});
				},
				success: function() {
					$("#modal-body-preview").empty();
					$('#modal-body-preview').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('mail/previewdata')}}"/>'));

				}
			});
			document.getElementById('iframeEditor').contentWindow.RecreateEditor();
			return false;
		}
		
		function saveAndExit() {
			//verHTML();
			var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
			$.ajax({
				url: "{{url('smartmanagment/content')}}/" + {{smart.idSmartmanagment}},
				type: "POST",			
				data: {editor: editor},
				error: function(msg){
					$.gritter.add({class_name: 'gritter-error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Error', text: msg.message, sticky: false, time: 7000});
					document.getElementById('iframeEditor').contentWindow.RecreateEditor();
					return false;
				},
				success: function(msg){
					$(location).attr('href', "{{url('smartmanagment/edit')}}/{{smart.idSmartmanagment}}");
				}
			});
		}

		function saveData() {
			var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
			$.ajax({
				url: "{{url('smartmanagment/content')}}/" + {{smart.idSmartmanagment}},
				type: "POST",			
				data: { editor: editor},
				error: function(msg){
					$.gritter.add({class_name: 'gritter-error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Atención', text: msg.message, sticky: false, time: 7000});
				},
				success: function(msg){
					$.gritter.add({class_name: 'gritter-success', title: '<i class="glyphicon glyphicon-ok"></i> Operación exitosa', text: msg.message, sticky: false, time: 3000});
				}
			});
			document.getElementById('iframeEditor').contentWindow.RecreateEditor();
			return false;
		}
	</script>
{% endblock %}
{% block content %}
	<div class="space-small"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			{{ flashSession.output()}}
		</div>
	</div>
	
	<div class="clearfix"></div><div class="space"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<a href="{{url('smartmanagment/edit')}}/{{smart.idSmartmanagment}}" class="btn btn-sm btn-danger">Regresar sin guardar</a>
			<button onclick="verHTML();" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#preview-modal">Visualizar</button>
			<button onclick="saveAndExit();" type="button" class="btn btn-sm btn-success">Guardar y regresar</button>
			<button onclick="saveData();" class="btn btn-sm btn-success">Guardar</button>
		</div>
	</div>
	
	<div class="clearfix"></div><div class="space-small"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" frameborder="0" onload="iframeResize();" seamless></iframe>
		</div>
	</div>		
{% endblock %}
