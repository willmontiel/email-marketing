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
	
	function sendData() {
		var name = $("#name").val();
		var category = $("#category").val();
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax(
			{
			url: "{{url('template/new')}}",
			type: "POST",			
			data: { 
				name: name,
				category: category,		
				editor: editor
			},
			statusCode: {
				400: function() {
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: 'Ha enviado campos inválidos o vacíos, por favor verifique la información', sticky: false, time: 10000});
				},
				500: function() {
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: 'Ha ocurrido un error, contacta al administrador', sticky: false, time: 10000});
				}
			},
			success: function(){
				$(location).attr('href', "{{url('mail/index')}}"); 
			}
		});
	}
</script>
{% endblock %}
{% block content %}
    <div class="row-fluid">
		<div class="area-top clearfix">
			<div class="pull-left header">
				<h3 class="title">
				  <i class="icon-magic"></i>
				  Crear una plantilla
				</h3>
				<h5>
				  Cree una plantilla global, que cualquier cuenta podrá usar como base
				</h5>
			</div>
		</div>
    </div>
	<div class="row-fluid">
		<div class="box span6 offset3">
			<div class="box-header">
				<div class="title">
					Crear una nueva plantilla
				</div>
			</div>
			<div class="box-content">
				<div class="padded">
					<form class="fill-up">
						<label>Nombre de la plantilla</label>
						<input type="text" name="name" id="name">

						<label>Categoría</label>
						<input type="text" name="categoria" id="categoria">
						{#
						<select class="chzn-select" name="categoria" id="category">
							<option value="Magazine">Magazine</option>
							<option value="Newsletter">Newsletter</option>
							<option value="Newspaper">Newspaper</option>
							<option value="Book">Book</option>
						</select>
						#}
					</form>
				</div>
			</div>
			<div class="form-actions">
				<a href="{{url('mail/index')}}" class="btn btn-default">Cancelar</a>
				<input type="submit" class="btn btn-blue" value="Guardar" onclick="sendData()">
			</div>
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<iframe id="iframeEditor" src="{{url('template/editor_frame')}}" width="100%" onload="iframeResize()" seamless></iframe>
	</div>
	<br />
{% endblock %}
