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

	function verHTML() {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$('#preview-modal').modal('show');
		$.ajax({
			url: "{{url('template/previewtemplate')}}",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function() {
				$("#preview-modal").empty();
				$('#preview-modal').append('<span class="close-preview icon-remove icon-2x" data-dismiss="modal"></span>')
				$('<iframe frameborder="0" width="100%" height="100%" src="{{url('template/previewdata')}}"/>').appendTo('#preview-modal');
			}
		});
		
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
	}
	
	$(function() {
		if( '{{ userObject.userrole }}' === 'ROLE_SUDO') {
			$('.globalTemplateOpt').show();
		}
	});	

	
	function writenewcategory() {
		$('.selectcategory').hide();
		$('.btnNewCategory').hide();
		$('.selectcategory').find('#category').removeAttr('id');
		
		$('.newcategory').show();
		$('.btnSelectCategory').show();
		$('.newcategory').find('input').attr('id', "category");
	};
	
	function selectcategory() {
		$('.newcategory').hide();
		$('.btnSelectCategory').hide();
		$('.newcategory').find('input').removeAttr('id');
		
		$('.selectcategory').show();
		$('.btnNewCategory').show();
		$('.selectcategory').find('select').attr('id', "category");
	};
	
	function sendData() {
		var name = $("#name").val();
		var category = $("#category").val();
		var global = $("#isglobal")[0].checked;
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax(
			{
			url: "{{url('template/new')}}",
			type: "POST",			
			data: { 
				name: name,
				category: category,
				global: global,
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
				$.gritter.add({class_name: 'success', title: '<i class="icon-save"></i> Atención', text: 'Se ha guardado la plantilla exitosamente', sticky: false, time: 10000});
			}
		});
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
	}
</script>
{% endblock %}
{% block content %}

{# Botones de navegacion #}
{{ partial('mail/partials/small_buttons_nav_partial', ['activelnk': 'list']) }}

    <div class="row">
		<h4 class="sectiontitle">Crear una plantilla</h4>
	
		<div class="bs-callout bs-callout-info">	  
			<p>Cree una plantilla global, que cualquier cuenta podrá usar como base</p>
	    </div>
	    <form class="form-inline" role="form">
	    	<div class="form-group">
				<label for="" class="" >Nombre de la plantilla:</label>
				<input type="text" name="name" id="name" required="required" class="form-control">
			</div>
	    	<div class="form-group">
				<label for="">Categoría: </label>
				<select class="form-control" name="categoria" id="category">
					{%if categories%}
						{%for category in categories%}
							<option value="{{category}}">{{category}}</option>
						{%endfor%}
					{%else%}
						<option value="Mis Templates">Mis Templates</option>
					{%endif%}
				</select>
			</div>
			<div class="form-group">
				<label for="" class="">Nueva Categoria: </label>
				<input type="text" name="categoria" id="category" required="required" class="form-control">
			</div>
			<div class="form-group">
				<button class="btn btn-default" onclick="writenewcategory()"><span class="glyphicon glyphicon-pencil"></span></button>
				<button class="btn btn-default" onclick="selectcategory()"><span class="glyphicon glyphicon-check"></span></button>
			</div>
			<div class="form-group">
				<a href="{{url('template/index')}}" class="btn btn-default">Salir</a>
				<a class="btn btn-default" onclick="sendData()"><span class="glyphicon glyphicon-floppy-saved"></i></a>
			</div>
			<div class="form-group">
				<label><input type="checkbox" name="isglobal" id="isglobal"> Plantilla Global</label>
				<a class="btn btn-default" onClick="verHTML()"><span class="glyphicon glyphicon-search"></span> Previsualizar</a>
			</div>
		</form>
	</div>
	<div class="row">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row">
		<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" onload="iframeResize()" seamless></iframe>
	</div>
	<br />
	<div id="preview-modal" class="modal hide fade preview-modal"></div>
{% endblock %}
