{% extends "templates/index_b3.volt" %}
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
	var idMail = '';
	
	{% if mail is defined %}
		var idMail = {{mail.idMail}};
	{% endif %}
	
	function sendData() {
		//verHTML();
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax({
			url: "{{url('mail/contenteditor')}}/" + idMail,
			type: "POST",			
			data: { editor: editor },
			error: function(msg){
				var obj = $.parseJSON(msg.responseText);
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.msg, sticky: false, time: 10000});
				document.getElementById('iframeEditor').contentWindow.RecreateEditor();
				return false;
			},
			success: function(msg){
				$(location).attr('href', "{{url('mail/compose')}}/" + msg.msg);
			}
		});
	}
	
	function verHTML() {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$('#preview-modal').modal('show');
		$.ajax({
			url: "{{url('mail/previeweditor')}}/" + idMail,
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function() {
				$("#modal-body-preview").empty();
				$('#modal-body-preview').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('mail/previewdata')}}"/>'));
				
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
			url: "{{url('mail/savecontent')}}/" + idMail,
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function(){
				$.gritter.add({class_name: 'success', text: '<i class="icon-save"></i> Se ha guardado el contenido', sticky: false, time: 3000});
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
	<div class="row">
		{{ flashSession.output()}}
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="btnoptions">
				<div class="box span12 optionsEditor">
					<div class="pull-right NextFromEditor">
						{% if mail is defined%}
							<a href="{{url('mail/compose')}}/{{mail.idMail}}" class="btn btn-default">Regresar sin guardar</a>
						{% endif %}
						<button onclick="sendData()" type="button" class="btn btn-primary">Guardar y regresar</button>
					</div>
					<div class="pull-left VisualizeEditor">
						<button onclick="verHTML()" class="btn btn-default" data-toggle="modal" data-target="#preview-modal">Visualizar</button>
					</div>
					<div class="pull-left SaveTemplate">
						<button onclick="createTemplate()" type="button" value="Guardar como Plantilla" class="btn btn-default">Guardar como Plantilla</button>
					</div>
					<div class="pull-left SaveContent">
						{% if mail is defined %}
							<button onclick="saveContent()" type="button" value="Guardar" class="btn btn-primary">Guardar</button>
						{% endif %}
					</div>
					{#
					<div class="pull-left SaveTemplate">
						<a href="#modal-simple" onclick="addGoogleAnalytics()" type="button" class="btn btn-default">Seguimiento con Google Analytics <i class="icon-google-plus-sign"></i> <span id="analytics-active" style="color: #acd954; display: none;">Activo</span> <span id="analytics-inactive" style="color: #e8a397;">Inactivo</span></a>
					</div>
					#}
				</div>
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-12">
			<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" onload="iframeResize()" seamless></iframe>
		</div>
	</div>
	<div id="preview-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Previsualización</h4>
				</div>
				<div class="modal-body" id="modal-body-preview">

				</div>
			</div>
		</div>
	</div>
{% endblock %}