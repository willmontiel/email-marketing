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

	function verHTML() {
		
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		
		$.ajax(
			{
			url: "{{url('mail/previeweditor')}}",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function(response) {
				win = open("", "DisplayWindow", "toolbar=0, titlebar=yes , status=1, directories=yes, menubar=0, location=yes, directories=yes, width=700, height=650, left=1, top=0");
				win.document.write("" + response.response + "");
			}
		});
		
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
	}
	
	$(function() {
		if( '{{ userObject.userrole }}' == 'ROLE_SUDO') {
		
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
		<div class="btnoptions">
			<div class="box span12 btnoptions">
				<div class="templateName pull-left">
					<label>Nombre de la plantilla: 
					<input type="text" name="name" id="name"></label>
				</div>
				<div class="templateCategory pull-left">
					<label class="selectcategory" >Categoría: 
					<select class="uniform" name="categoria" id="category">
						<option value="Mis Templates">Mis Templates</option>
						{%for category in categories%}
							<option value="{{category}}">{{category}}</option>
						{%endfor%}
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
					<a href="{{url('mail/index')}}" class="btn btn-default">Cancelar</a>
					<input type="submit" class="btn btn-blue" value="Guardar" onclick="sendData()">
				</div>
				<div class="globalTemplateOpt pull-left">
					<label><input type="checkbox" name="isglobal" id="isglobal"> Plantilla Global</label>
				</div>
				<div class="templatePreview pull-right">
					<button class="btn btn-default" onclick="verHTML()">Visualizar</button>
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
{% endblock %}
