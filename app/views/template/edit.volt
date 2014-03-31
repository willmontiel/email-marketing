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

	var objMail = {{template.content}};
	
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
		<div class="btnoptions">
			<div class="box span12 btnoptions">
				<div class="templateName pull-left">
					<label>Nombre de la plantilla: 
					<input type="text" name="name" id="name" value="{{template.name}}"></label>
				</div>
				<div class="templateCategory pull-left">
					<label class="selectcategory" >Categoría: 
					<select class="uniform" name="categoria" id="category">
						{%if categories%}
							{%for category in categories%}
								<option value="{{category}}" {% if category == template.category %}selected{% endif %}>{{category}}</option>
							{%endfor%}
						{%else%}
							<option value="Mis Templates">Mis Templates</option>
						{%endif%}
					</select></label>
					<label class="newcategory" style="margin-left: -40px;">Nueva Categoria: 
					<input type="text" name="categoria" id="category" style="width: 124px;">
					</label>
				</div>
				<div class="btnNewCategory pull-left">
					<button class="btn btn-default" onclick="writenewcategory()"><i class="icon-pencil"></i></button>
				</div>
				<div class="btnSelectCategory pull-left">
					<button class="btn btn-default" onclick="selectcategory()"><i class="icon-th-list"></i></button>
				</div>
				<div class="templateBtns pull-left">
					<a href="{{url('template/index')}}" class="btn btn-default">Salir</a>
					<a type="submit" class="btn btn-blue" value="Guardar" onclick="sendData()"><i class="icon-2x icon-save"></i></a>
				</div>
				<div class="globalTemplateOpt pull-left">
					<label><input type="checkbox" name="isglobal" id="isglobal" {% if template.idAccount == ''%}checked{% endif %}> Plantilla Global</label>
				</div>
				<div class="templatePreview pull-right">
					<a class="btn btn-default" onClick="verHTML()"><i class="icon-search"></i> Previsualizar</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<iframe id="iframeEditor" src="{{url('mail/editor_frame')}}" width="100%" onload="iframeResize()" seamless></iframe>
	</div>
	<br />
	<div id="preview-modal" class="modal hide fade preview-modal"></div>
{% endblock %}
