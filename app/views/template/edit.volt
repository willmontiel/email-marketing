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

	var objMail = {{template.content}};
	
	function verHTML() {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax({
			url: "{{url('template/previewtemplate')}}",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function() {
				$("#preview-modal").empty();
				$('#preview-modal').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('template/previewdata')}}"/>'))
			}
		});
		
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
		return false;
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
			url: "{{url('template/edit')}}/{{template.idTemplate}}",
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
	<div class="row">
		<div class="col-sm-12">
			{{ partial('mail/partials/small_buttons_nav_partial', ['activelnk': 'template']) }}
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
		<h4 class="sectiontitle">Editar Plantillas</h4>
		<div class="bs-callout bs-callout-info">
			Edite el contenido de plantillas predeterminadas
		</div>
		</div>
	</div>
	
	<div class="row">
		{{ flashSession.output()}}
	</div>

	<div class="row">
		<div class="col-sm-2">
			<label for="name">Nombre de la plantilla:</label>
			<input type="text" value="{{template.name}}" name="name" id="name" required="required" class="form-control">
		</div>
		<div class="col-sm-3">
			<div class="selectcategory form-group">
				<label for="categoria">Categoría: </label>
				<select class="form-control" name="categoria" id="category">
					{%if categories%}
						{%for category in categories%}
							<option value="{{category}}" {% if category == template.category %}selected{% endif %}>{{category}}</option>
						{%endfor%}
					{%else%}
						<option value="Mis Templates">Mis Templates</option>
					{%endif%}
				</select>
			</div>
				
			<div class="newcategory form-group" id="new-category" style="display: none;">
				<label for="category" class="">Nueva Categoria: </label>
				<input type="text" name="categoria" id="category" class="newcategory form-control">
			</div>
		</div>
			
		<div class="col-sm-1">
			<a class="btnNewCategory btn btn-sm btn-default extra-padding" onclick="writenewcategory()" ><span class="glyphicon glyphicon-pencil"></span></a>
			<a class="btnSelectCategory btn btn-sm btn-default extra-padding" onclick="selectcategory()" style="display: none;"><span class="glyphicon glyphicon-check"></span></a>
		</div>
			
		<div class="col-sm-2">
			<input type="checkbox" name="isglobal" id="isglobal" {% if template.idAccount == ''%}checked{% endif %}> 
			<label for="isglobal">Plantilla Global</label>
		</div>
			
		
		
		<div class="col-sm-4 text-right">
			<a href="{{url('template/index')}}" class="btn btn-default extra-padding">Salir</a>
			<button class="btn btn-default extra-padding" data-toggle="modal" data-target="#myModal" onClick="verHTML()">
				<span class="glyphicon glyphicon-search"></span> Previsualizar
			</button>
			<a class="btn btn-default extra-padding" onclick="sendData()"><span class="glyphicon glyphicon-floppy-saved"></i></a>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" onload="iframeResize()" seamless></iframe>
		</div>
	</div>
	
	<br />
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Previsualización</h4>
				</div>
				<div class="modal-body" id="preview-modal">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
