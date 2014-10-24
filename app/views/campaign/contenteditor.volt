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
	
	{% if objMail is defined %}
		var objMail = {{objMail}};
	{% endif %}
	var idAutoresponder = 'null';
	
	{% if autoresponder is defined %}
		var idAutoresponder = {{autoresponder.idAutoresponder}};
	{% endif %}
	
	function sendData() {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax({
			url: "{{url('campaign/contenteditor')}}/" + idAutoresponder,
			type: "POST",			
			data: { editor: editor },
			error: function(msg){
				var obj = $.parseJSON(msg.responseText);
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.msg, sticky: false, time: 10000});
				document.getElementById('iframeEditor').contentWindow.RecreateEditor();
				return false;
			},
			success: function(msg){
				$(location).attr('href', "{{url('campaign/')}}" + msg.status + "/" + idAutoresponder);
			}
		});
	}
	
	function verHTML() {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax({
				url: "{{url('campaign/preview')}}",
				type: "POST",			
				data: { 
					type: 'editor',
					content: editor
				},
				error: function(msg){
					var txt = JSON.parse(msg.responseText);
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: txt.status, sticky: false, time: 2000});
				},
				success: function() {
					$('#preview-modal-content').empty();
					$('#preview-auto-send-modal').modal('show');
					$('#preview-modal-content').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('campaign/previewframe')}}"/>'));
				}
			});
			
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
		return false;
	}
	
	function cancelProcess() {
		$.ajax({
			url: "{{url('campaign/getcancel')}}/" + idAutoresponder,
			type: "GET",			
			success: function(msg) {
				$(location).attr('href', "{{url('campaign/')}}" + msg.status + "/" + idAutoresponder);
			}
		});
	}
</script>
{% endblock %}
{% block content %}
	{{ flashSession.output()}}
	<div class="row">
		<div class="col-md-12">
			<div class="box span12 padding-top">
				<div class="pull-right">
					<a onclick="cancelProcess()" class="btn btn-sm btn-default extra-padding">Cancelar</a>
					<button onclick="sendData()" type="button" class="btn btn-sm btn-primary extra-padding">Guardar y regresar</button>
				</div>
				<button onclick="verHTML()" class="btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#preview-modal">Visualizar</button>	
				{% if mail is defined %}
					<button onclick="saveContent()" type="button" value="Guardar" class="btn btn-sm btn-primary extra-padding">Guardar</button>
				{% endif %}
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-12">
			<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" frameborder="0" onload="iframeResize()" seamless></iframe>
		</div>
	</div>
	<div id="preview-auto-send-modal" class="modal fade">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Previsualización</h4>
				</div>
				<div class="modal-body modal-prevew-body" id="preview-modal-content"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}