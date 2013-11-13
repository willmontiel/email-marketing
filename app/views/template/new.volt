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
			}
		});
		console.log(editor);
	}
</script>
{% endblock %}
{% block content %}
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<iframe id="iframeEditor" src="{{url('template/editor_frame')}}" width="100%" onload="iframeResize()" seamless></iframe>
	</div>
	<br />
	<div class="row-fluid">
		<div class="box span6 offset3">
			<div class="box-header">
				<div class="title">
					Crear una nueva plantilla
				</div>
			</div>
			<div class="box-content">
				<div class="padded">
					<label>Nombre de la plantilla</label>
					<input type="text" name="name" id="name">

					<label>Categoría</label>
					<select class="uniform" name="categoría" id="category">
						<option value="Magazine">Magazine</option>
						<option value="Newsletter">Newsletter</option>
						<option value="Newspaper">Newspaper</option>
						<option value="Book">Book</option>
					</select>
				</div>
			</div>
			<div class="form-actions">
				<a href="{{url('mail/index')}}" class="btn btn-default">Cancelar</a>
				<input type="submit" class="btn btn-blue" value="Guardar" onclick="sendData()">
			</div>
		</div>
	</div>
{% endblock %}
