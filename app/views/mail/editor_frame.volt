{% block header_javascript %}
	{{ javascript_include('js/jquery-1.9.1.js') }}
	{{ javascript_include('js/jquery_ui_1.10.3.js') }}
	{{ javascript_include('vendors/bootstrap_v2/js/bootstrap.js') }}
	{{ javascript_include('vendors/bootstrap_v2/slider/js/bootstrap-slider.js') }}
	{{ javascript_include('vendors/bootstrap_v2/pick-a-color/1.1.5/js/tinycolor-0.9.14.min.js') }}
	{{ javascript_include('vendors/bootstrap_v2/pick-a-color/1.1.5/js/pick-a-color-1.1.5.min.js') }}
	{{ javascript_include('vendors/bootstrap_v2/spectrum/js/spectrum.js') }}
	{{ stylesheet_link('css/jquery-ui.css') }}
	{{ stylesheet_link('vendors/bootstrap_v2/css/bootstrap.css') }}
	{{ stylesheet_link('vendors/bootstrap_v2/pick-a-color/1.1.5/css/pick-a-color-1.1.5.css') }}
	{{ stylesheet_link('vendors/bootstrap_v2/spectrum/css/spectrum.css') }}
	{{ stylesheet_link('vendors/bootstrap_v2/css/bootstrap-min.css') }}
	{{ stylesheet_link('vendors/bootstrap_v2/slider/css/slider.css') }}
	{{ javascript_include('vendors/redactor/redactor.js')}}
	{{ stylesheet_link('vendors/redactor/redactor.css') }}
	{{ javascript_include('js/pluggins-editor/dropzone/dropzone.js')}}
	{{ javascript_include('js/pluggins-editor/colorpicker/js/bootstrap-colorpicker.js')}}
	{{ javascript_include('js/pluggins-editor/gritter/js/jquery.gritter.js') }}
	{{ javascript_include('vendors/redactor/plugins/clips.js') }}
	{{ javascript_include('vendors/redactor/plugins/fontcolor.js') }}
	{{ javascript_include('vendors/redactor/plugins/fontfamily.js') }}
	{{ javascript_include('vendors/redactor/plugins/fontsize.js') }}
	{{ stylesheet_link('css/styles.css') }}
	{{ stylesheet_link('js/pluggins-editor/dropzone/css/dropzone.css') }}
	{{ stylesheet_link('js/pluggins-editor/colorpicker/css/colorpicker.css') }}
	{{ stylesheet_link('js/pluggins-editor/gritter/css/jquery.gritter.css') }}
	{{ javascript_include('js/editor/forms_text_editor.js') }}
	{{ javascript_include('js/editor/gallery.js') }}
	{{ javascript_include('js/editor/media_displayer.js') }}

<script type="text/javascript">
		var objMail = {{objMail}};
		var footer = {%if footer.editor is defined %} {{footer.editor}} {% else %} '' {% endif %};
		var footerhtml = '{{footer.html}}';
		var footereditable = "{{footer.editable}}";
	
		var config = {imagesUrl: "{{url('images')}}", templateUrl : "{{url('template/create')}}"};
		
		var mediaGallery = [
			{%for asset in assets%}
				new Gallery("{{asset['thumb']}}", "{{asset['image']}}", "{{asset['title']}}", {{asset['id']}}),
			{%endfor%}
		];
		

		function catchEditorData() {
			editor.serializeDZ();
			var editorToSend = JSON.stringify(editor);
			
			return editorToSend;
		}
		
		
		function RecreateEditor() {
			editor.objectExists(editor);
		}

	</script>
	
	{{ javascript_include('js/editor/row_zone.js') }}
	{{ javascript_include('js/editor/block_text.js') }}
	{{ javascript_include('js/editor/block_image.js') }}
	{{ javascript_include('js/editor/block_separator.js') }}
	{{ javascript_include('js/editor/block_social_share.js') }}
	{{ javascript_include('js/editor/block_social_follow.js') }}
	{{ javascript_include('js/editor/block_button.js') }}
	{{ javascript_include('js/editor/toolbar.js') }}
	{{ javascript_include('js/editor/dropzone.js') }}
	{{ javascript_include('js/editor/layout.js') }}
	{{ javascript_include('js/editor/editor.js') }}

{% endblock %}
{% block content %}
<body class="editor-bg-color">
	<div class="row">
		<div class="span12">
			<div class="edit-area-container">
				<div id="edit-area" class="module-cont clearfix">
				</div>
			</div>
		</div>
		<div id="newtemplatename" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Nuevo Template</h3>
			</div>
			<div class="modal-body">
				<label>Escriba un nombre para el nuevo template</label>
				<input id="templatename" type="text">
				<br />
				<label>Categoria</label>
				<input id="templatecategory" type="text" value="Mis Templates" readonly>
			</div>
			<div class="modal-footer">
				<a id="saveTemplate" href="#" class="btn btn-default" data-dismiss="modal">Aceptar</a>
				<a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
			</div>
		</div>

		<div id="boxedtext" class="modal hide fade">
			<div class="modal-header">
				<h3>Editar Caja</h3>
			</div>
			<div class="modal-body">
				<div class="span6">
					<label>Color de Fondo</label>
					<div class='input-append color' data-color='' data-color-format='hex' id='boxbgcolor'>
						<input id="field-boxbgcolor" type='text' value='#556270' style="width: 90px; height: 30px;">
						<span class='add-on'><i style='background-color: rgb(255, 146, 180)'></i></span>
					</div>
				</div>
				<div class="span6">
					<label for="boxborder">Borde</label>
					<label>
						<input type="text" id="boxborderwidth" value="0"> px
						<select id="boxborderstyle" class="span2">
							<option value="solid" selected>Solid</option>
							<option value="dotted">Dotted</option>
							<option value="dashed">Dashed</option>
							<option value="double">Double</option>
							<option value="groove">Groove</option>
							<option value="ridge">Ridge</option>
							<option value="inset">Inset</option>
							<option value="outset">Outset</option>
						</select>
						<div class='input-append color' data-color='' data-color-format='hex' id='boxbordercolor'>
							<input id="field-boxbordercolor" type='text' value='#556270' style="width: 90px; height: 30px;">
							<span class='add-on'><i style='background-color: rgb(255, 146, 180)'></i></span>
						</div>
					</label>
				</div>
				<div class="span3">
					<label for="boxradius">Borde Redondeado</label>
					<input type="text" id="boxborderradius" value="0"> px
				</div>
			</div>
			<div class="modal-footer">
				<a id="acceptboxtext" href="#" class="btn btn-default" data-dismiss="modal">Aceptar</a>
				<a id="cancelboxtext" href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
			</div>
		</div>


		<div id="add-element-block" class="modal hide fade">
			<div class="modal-header">
				Seleccione Elemento
			</div>
			<div class="modal-body">
				<div class="basic-elements clearfix">

				</div>
				<hr />
				<div class="compounds-elements clearfix">

				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
			</div>
		</div>

		<div id="select-layout" class="modal hide fade">
			<div class="modal-header">
				Seleccione Layout
			</div>
			<div class="modal-body">
				<div class="layout-list clearfix">

				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
			</div>
		</div>


		<div class="component-toolbar" style="display:none"></div>
		<div class="component-toolbar-text" style="display:none"></div>

		<div id="images" class="modal hide fade gallery-modal">
			<div class="modal-header gallery-header">
				<h3>Imagenes</h3>
			</div>

			<ul class="nav nav-tabs nav-tabs-in-modal">
				<li id="tabgallery" class="active">
					<a href="#gallery" data-toggle="tab">Galeria</a>
				</li>
				<li id="tabuploadimage" class="">
					<a href="#uploadimage" data-toggle="tab">Cargar</a>
				</li>
			</ul>

			<div class="modal-body">
				<div class="tab-content imagesbody">
					<div id="gallery" class="tab-pane active">

					</div>

					<div id="uploadimage" class="tab-pane well">
						<h2 class="text-center">Cargar Imagen</h2>
						<form action="{{url('asset/upload')}}" class="dropzone" id="my-dropzone">
							<div class="dz-message"><span>Suelte su Imagen Aqui! <br/><br/>(o Click)</span></div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div id="accept_cancel_image">
					<a href="#" class="btn btn-default" id="accept_change" data-dismiss="modal">Aplicar</a>
					<a href="#" class="btn btn-default" id="cancel_change" data-dismiss="modal">Cancelar</a>
				</div>
			</div>
		</div>

		<div id="clipsmodal" style="display: none;">
			<section>
				<ul class="redactor_clips_box">
					<li>
						<a href="#" class="redactor_clip_link">Email</a>

						<div class="redactor_clip" style="display: none;">
							%%EMAIL%%
						</div>
					</li>
					<li>
						<a href="#" class="redactor_clip_link">Nombre</a>

						<div class="redactor_clip" style="display: none;">
							%%NOMBRE%%
						</div>
					</li>
					<li>
						<a href="#" class="redactor_clip_link">Apellido</a>

						<div class="redactor_clip" style="display: none;">
							%%APELLIDO%%
						</div>
					</li>
					<li>
						<a href="#" class="redactor_clip_link">Fecha de nacimiento</a>

						<div class="redactor_clip" style="display: none;">
							%%FECHA_DE_NACIMIENTO%%​
						</div>
					</li>
					{%if cfs is defined %}
						{%for cf in cfs%}
							<li>
								<a href="#" class="redactor_clip_link">{{cf['originalName']}}</a>

								<div class="redactor_clip" style="display: none;">
									%%{{cf['linkName']}}%%
								</div>
							</li>
						{%endfor%}
					{%endif%}

					<li>
						<a href="#" class="redactor_clip_link">Enlace de des-suscripcion</a>

						<div class="redactor_clip" style="display: none;">
							<a href="%%UNSUBSCRIBE%%">Para des-suscribirse haga clic aqui</a>

						</div>
					</li>
					<li>
						<a href="#" class="redactor_clip_link">Enlace de version web</a>

						<div class="redactor_clip" style="display: none;">
							<a href="%%WEBVERSION%%">Version web</a>

						</div>
					</li>
				</ul>
			</section>
			<footer>
				<a href="#" class="redactor_modal_btn redactor_btn_modal_close">Close</a>
			</footer>
		</div>


		<div id="forms-update" style="display: none;">
			<section>
				<ul>
				{%if forms is defined %}
					{%for form in forms%}
						<li>
							<a href="#" name="chk_group" class="selected-form-class" value="%%FORM_{{form['idForm']}}%%">{{form['name']}}</a><br />
						</li>
					{%endfor%}
				{%endif%}
				</ul>
			</section>
			<footer>
				<button class="redactor_modal_btn redactor_btn_modal_close">Close</button>
			</footer>
		</div>
	</div>
</body>
{% endblock %}