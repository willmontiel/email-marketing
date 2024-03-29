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
				url: "{{url('smartmanagment/previewcontent')}}/" + {{smart.idSmartmanagment}},
				type: "POST",			
				data: {editor: editor},
				error: function(msg){
					$.gritter.add({class_name: 'error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Atención', text: msg, sticky: false, time: 7000});
				},
				success: function() {
					$("#modal-body-preview").empty();
					$('#modal-body-preview').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('smartmanagment/previewdata')}}"/>'));

				}
			});
			document.getElementById('iframeEditor').contentWindow.RecreateEditor();
			return false;
		}
		
		function saveAndExit() {
			//verHTML();
			var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
			var subject = $("#subject").val();
			var fromName = $("#fromName").val();
			var fromEmail = $("#fromEmail").val();
			var replyTo = $("#replyTo").val();
			
			$.ajax({
				url: "{{url('smartmanagment/content')}}/" + {{smart.idSmartmanagment}},
				type: "POST",			
				data: {
					subject: subject,
					fromName: fromName,
					fromEmail: fromEmail,
					replyTo: replyTo,
					editor: editor		
				},
				error: function(msg){
					$.gritter.add({class_name: 'gritter-error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Error', text: msg.statusText, sticky: false, time: 7000});
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
			var subject = $("#subject").val();
			var fromName = $("#fromName").val();
			var fromEmail = $("#fromEmail").val();
			var replyTo = $("#replyTo").val();
			
			$.ajax({
				url: "{{url('smartmanagment/content')}}/" + {{smart.idSmartmanagment}},
				type: "POST",			
				data: {
					subject: subject,
					fromName: fromName,
					fromEmail: fromEmail,
					replyTo: replyTo,
					editor: editor		
				},
				error: function(msg){
					$.gritter.add({class_name: 'gritter-error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Atención', text: msg.statusText, sticky: false, time: 7000});
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
		<div class="col-xs-10 col-xs-offset-1  col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 header-background">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">*Asunto</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="subject" value="{{smart.subject}}" placeholder="Asunto">
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-2 control-label">*De</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="fromName" value="{{smart.fromName}}" placeholder="Nombre del remitente">
					</div>
					<div class="col-sm-5">
						<input type="email" class="form-control" id="fromEmail" value="{{smart.fromEmail}}" placeholder="Correo del remitente">
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-2 control-label">Responder a</label>
					<div class="col-sm-10">
						<input type="email" class="form-control" id="replyTo" value="{{smart.replyTo}}" placeholder="Responder a">
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="clearfix"></div><div class="space-small"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" frameborder="0" onload="iframeResize();" seamless></iframe>
		</div>
	</div>		
	
	<div id="preview-modal" class="modal fade">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content modal-prevew-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h1 class="modal-title">Previsualización</h1>
				</div>
				<div class="modal-body modal-prevew-body" id="modal-body-preview"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
