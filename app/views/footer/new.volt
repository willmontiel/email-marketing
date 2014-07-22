{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('vendors/redactor/redactor.js')}}
	{{ stylesheet_link('vendors/redactor/redactor.css') }}
	<script type="text/javascript">
		objMail = "Footer";
		
		function iframeResize() {
			var iFrame = document.getElementById('iframeEditor');
			iFrame.height = (iFrame.contentWindow.document.body.scrollHeight < 600) ? "600px" : ( iFrame.contentWindow.document.body.scrollHeight + 80 ) + "px";
		};
		
		function verHTML() {
			var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
			$.ajax({
				url: "{{url('footer/previeweditor')}}",
				type: "POST",			
				data: { editor: editor},
				error: function(msg){
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
				},
				success: function() {
					$( "#preview-modal-content" ).empty();
					$('#preview-modal-content').append($('<iframe frameborder="0" id="footer-preview" width="100%" height="100%" src="{{url('footer/previewdata')}}"/>'));
				}
			});
			document.getElementById('iframeEditor').contentWindow.RecreateEditor();
		};
		
		function sendData() {
			var name = $("#name").val();
			if (name.length === 0) {
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: 'El nombre que ha enviado es inválido o esta vacío, por favor verifique la información', sticky: false, time: 10000});
			}
			else {
				var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
				var objeditor = JSON.parse(editor);
				var footer = JSON.stringify(objeditor.dz.footer.content);
				$.ajax({
					url: "{{url('footer/new')}}",
					type: "POST",			
					data: { 
						name: name,
						content: footer
					},
					statusCode: {
						400: function() {
							$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: 'El nombre que ha enviado es inválido o esta vacío, por favor verifique la información', sticky: false, time: 10000});
						},
						500: function() {
							$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: 'Ha ocurrido un error, contacta al administrador', sticky: false, time: 10000});
						}
					},
					success: function(res){
						$.gritter.add({class_name: 'success', title: '<i class="icon-save"></i> Atención', text: 'Se ha guardado el footer exitosamente', sticky: false, time: 10000});
						$(location).attr('href', "{{url('footer')}}"); 
					}
				});
				
				document.getElementById('iframeEditor').contentWindow.RecreateEditor();
			}
		};

	</script>
{% endblock %}
{% block content %}
	
	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'footer']) }}
	
	<div class="row">
		<h4 class="sectiontitle">Crear un footer</h4>
	
		<div class="bs-callout bs-callout-info">	  
			<p>Cree un footer para asignar a cualquier cuenta como base</p>
	    </div>

	    <form class="form-inline" role="form">
	    	<div class="form-group">
				<label for="" class="" >Nombre del footer:</label>
				<input type="text" name="name" id="name" required="required" class="form-control">
			</div>
			<div class="col-xs-12 col-sm-9 col-md-10 col-lg-4 pull-right">
				<div class="form-group">
					<a class="btn btn-default extra-padding" data-toggle="modal" data-target="#preview-footer-modal" onClick="verHTML();"><span class="glyphicon glyphicon-search"></span> Previsualizar</a>
				</div>
				<div class="form-group">
					<a class="btn btn-default extra-padding" onclick="sendData();"><span class="glyphicon glyphicon-floppy-saved"></i></a>
				</div>
				<div class="form-group">
					<a href="{{url('footer')}}" class="btn btn-default extra-padding">Salir</a>
				</div>
			</div>
		</form>
	</div>
	<div class="row">
		<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" onload="iframeResize();" frameborder="0" seamless></iframe>
	</div>
	
	<div id="preview-footer-modal" class="modal fade">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Footer</h4>
				</div>
				<div class="modal-body modal-preview-body-footer" id="preview-modal-content"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}