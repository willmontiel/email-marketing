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
				$("#modal-body-preview").empty();
				$('#modal-body-preview').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('template/previewdata')}}"/>'));
			
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
		$('#all-category').hide();
		$('#btnNewCategory').hide();
		$('#category').find('#category').removeAttr('id');
		
		$('#new-category').show();
		$('#btnSelectCategory').show();
		$('#new-category').find('input').attr('id', "category");
	};
	
	function selectcategory() {
		$('#new-category').hide();
		$('#btnSelectCategory').hide();
		$('#new-category').find('input').removeAttr('id');
		
		$('#all-category').show();
		$('#btnNewCategory').show();
		$('#category').find('select').attr('id', "category");
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
	    	<div class="form-group" id="all-category" style="display: inline;">
				<label for="categoria">Categoría: </label>
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
			<div class="form-group" id="new-category" style="display: none;">
				<label for="category" class="">Nueva Categoria: </label>
				<input type="text" name="categoria" id="category" 	 class="form-control">
			</div>
			<div class="form-group">
				<a id="btnNewCategory" class="btn btn-default extra-padding" onclick="writenewcategory()" ><span class="glyphicon glyphicon-pencil"></span></a>
				<a id="btnSelectCategory" class="btn btn-default extra-padding" onclick="selectcategory()" style="display: none;"><span class="glyphicon glyphicon-check"></span></a>
			</div>
			<div class="form-group">
				<label><input type="checkbox" name="isglobal" id="isglobal"> Plantilla Global</label>
			</div>
			<div class="col-xs-12 col-sm-9 col-md-10 col-lg-4 pull-right">
				<div class="form-group">
					<a class="btn btn-default extra-padding" data-toggle="modal" data-target="#preview-modal" onClick="verHTML()"><span class="glyphicon glyphicon-search"></span> Previsualizar</a>
				</div>
				<div class="form-group">
					<a href="{{url('template/index')}}" class="btn btn-default extra-padding">Salir</a>
					<a class="btn btn-default extra-padding" onclick="sendData()"><span class="glyphicon glyphicon-floppy-saved"></i></a>
				</div>
			</div>
		</form>
	</div>
	<div class="row">
		{{ flashSession.output()}}
	</div>
	<div class="row">
		<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" onload="iframeResize()" seamless></iframe>
	</div>
	

	<div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
		      </div>
	      <div class="modal-body" id="modal-body-preview">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>
{% endblock %}
