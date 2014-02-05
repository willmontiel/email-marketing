{% block header_javascript %}
	{{ javascript_include('js/jquery-1.9.1.js') }}
	{{ javascript_include('js/jquery_ui_1.10.3.js') }}
	{{ javascript_include('bootstrap/js/bootstrap.js') }}
	{{ javascript_include('bootstrap/slider/js/bootstrap-slider.js') }}
	{{ javascript_include('bootstrap/pick-a-color/1.1.5/js/tinycolor-0.9.14.min.js') }}
	{{ javascript_include('bootstrap/pick-a-color/1.1.5/js/pick-a-color-1.1.5.min.js') }}
	{{ stylesheet_link('css/jquery-ui.css') }}
	{{ stylesheet_link('bootstrap/css/bootstrap.css') }}
	{{ stylesheet_link('bootstrap/pick-a-color/1.1.5/css/pick-a-color-1.1.5.css') }}
	{{ stylesheet_link('bootstrap/css/bootstrap-min.css') }}
	{{ stylesheet_link('bootstrap/slider/css/slider.css') }}
	{{ javascript_include('redactor/redactor.js')}}
	{{ stylesheet_link('redactor/redactor.css') }}
	{{ javascript_include('javascripts/dropzone/dropzone.js')}}
	{{ javascript_include('javascripts/colorpicker/js/bootstrap-colorpicker.js')}}
	{{ javascript_include('redactor/plugins/clips.js') }}
	{{ javascript_include('redactor/plugins/fontcolor.js') }}
	{{ javascript_include('redactor/plugins/fontfamily.js') }}
	{{ javascript_include('redactor/plugins/fontsize.js') }}
	{{ stylesheet_link('css/styles.css') }}
	{{ stylesheet_link('javascripts/dropzone/css/dropzone.css') }}
	{{ stylesheet_link('javascripts/colorpicker/css/colorpicker.css') }}
	{{ javascript_include('js/editor/gallery.js') }}

<script>
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
	
	{{ javascript_include('js/editor/block.js') }}
	{#{{ javascript_include('js/editor/social_block.js') }}
	{{ javascript_include('js/editor/button_block.js') }}
	{{ javascript_include('js/editor/boxed_text_block.js') }}#}
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
	<br /><br />
<div class="row-fluid">
	<div class="span12">
		<div id="edit-area" class="module-cont clearfix">
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

<div id="buttonaction" class="modal hide fade button-modal">
	<div class="modal-header button-header">
		<h3>Botón</h3>
	</div>
	<div class="modal-body">
		<div class="btn-text-area clearfix">
			<div class="btnmodalleft">
				<label>Texto</label><input id="btntext" type="text">
			</div>
			<div class="btnmodalright">
				<label>Color de Texto</label>
				<div class='input-append color' data-color='' data-color-format='hex' id='btntextcolor'>
					<input id="field-btntextcolor" type='text' class='span3' value='#ffffff'>
					<span class='add-on'><i style='background-color: rgb(255, 255, 255)'></i></span>
				</div>
			</div>
		</div>
		<div class="btn-text-area clearfix">
			<div class="btnmodalleft">
				<label>Fuente de Letra</label>
				<select id="btnfontfamily">
					<option value="arial" selected>Arial</option>
					<option value="helvetica">Helvetica</option>
					<option value="georgia">Georgia</option>
					<option value="times new roman">Times New Roman</option>
					<option value="monospace">Monospace</option>
				</select>
			</div>
			<div class="btnmodalright">
				<label>Tamaño de Letra</label>
				<select id="btnfontsize">
					<option value="10">10</option><option value="11">11</option><option value="12">12</option>
					<option value="14" selected>14</option><option value="16">16</option><option value="18">18</option>
					<option value="20">20</option><option value="24">24</option><option value="28">28</option>
					<option value="30">30</option>
				</select>
			</div>
		</div>
		<div class="btn-color-area clearfix">
			<div class="btnmodalleft">
				<label>Degradado <input type="checkbox" id="withbgimage" checked></label>
				<select id="btnbgimage">
					<option value="blue" selected>Azul</option>
					<option value="bluelight">Azul Claro</option>
					<option value="red">Rojo</option>
					<option value="redlight">Rojo Claro</option>
					<option value="black">Negro</option>
					<option value="yellow">Amarillo</option>
					<option value="orange">Naranja</option>
					<option value="gray">Gris</option>
				</select>
			</div>
			<div class="btnmodalright">
				<label>Color de Fondo</label>
				<div class='input-append color' data-color='' data-color-format='hex' id='btnbgcolor'>
					<input id="field-btnbgcolor" type='text' class='span3' value='#556270'>
					<span class='add-on'><i style='background-color: rgb(255, 146, 180)'></i></span>
				</div>
			</div>
		</div>
		<div class="btn-border-area clearfix">
			<div class="btnmodalleft">
				<label>Borde Redondeado <input type="checkbox" id="withborderradius" checked></label>
				<select id="btnradius">
					<option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4" selected>4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option>
					<option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
				</select> px
			</div>
			<div class="btnmodalright">
				<label>Color de Borde <input type="checkbox" id="withbordercolor" checked></label>
				<div class='input-append color' data-color='' data-color-format='hex' id='btnbordercolor'>
					<input id="field-btnbordercolor" type='text' class='span3' value='#1e3650'>
					<span class='add-on'><i style='background-color: rgb(255, 146, 180)'></i></span>
				</div>
			</div>
		</div>
		<div class="btn-size-area clearfix">
			<div class="btnmodalleft">
				<label>Ancho</label><input id="btnwidth" type="text"> px 
			</div>
			<div class="btnmodalright">
				<label>Alto</label><input id="btnheight" type="text"> px 
			</div>
		</div>
		<div class="btn-link-area clearfix">
			<div class="btnmodalleft">
				<label>Hipervinculo</label><input id="btnlink" type="text">
			</div>
			<div class="btnmodalright">
				<label>Alinear</label>
				<select id="btnalign">
					<option value="left">Izquierda</option>
					<option value="center" selected>Centro</option>
					<option value="right">Derecha</option>
				</select>
			</div>
		</div>	
	</div>
	<div class="modal-footer">
		<a id="savebtndata" href="#" class="btn btn-default" data-dismiss="modal">Aceptar</a>
		<a id="cancelbtndata" href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
	</div>
</div>

<div id="images" class="modal hide fade gallery-modal">
	<div class="modal-header gallery-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Imagenes</h3>
	</div>

	<ul class="nav nav-tabs nav-tabs-in-modal">
		<li id="taboneimage" class="active">
			<a href="#oneimage" data-toggle="tab">Editar</a>
		</li>
		<li id="tabgallery" class="">
			<a href="#gallery" data-toggle="tab">Galeria</a>
		</li>
		<li id="tabuploadimage" class="">
			<a href="#uploadimage" data-toggle="tab">Cargar</a>
		</li>
	</ul>

	<div class="modal-body">
		<div class="tab-content imagesbody">
			<div id="oneimage" class="tab-pane active well clearfix">
				<div class="pull-left">
					<div id="imagedisplayer"></div>
					<div id="imageslider">

					</div>
				</div>
				<div class="pull-left image-options">
					<div id="widthImg" style="float: right;"></div>
					<div id="heightImg"></div>	

					<div id="align_image">
						<div class="btn-group">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="width: 175px; text-align: right;">
							  Alineación Horizontal <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li class="chose_align" data-dropdown="left"><a href="#">Izquierda</a></li>
								<li class="chose_align" data-dropdown="center"><a href="#">Centro</a></li>
								<li class="chose_align" data-dropdown="right"><a href="#">Derecha</a></li>
							</ul>
						</div>
					</div>
					<div id="align_vertical_image">
						<div class="btn-group">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="width: 175px; text-align: right;">
							  Alineación Vertical <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li class="chose_vertical_align" data-dropdown="top"><a href="#">Arriba</a></li>
								<li class="chose_vertical_align" data-dropdown="middle"><a href="#">Centro</a></li>
								<li class="chose_vertical_align" data-dropdown="bottom"><a href="#">Abajo</a></li>
							</ul>
						</div>
					</div>
					<div id="link_image">
						{#<label>Ingrese un Link
							<br/>
						<input id="link_to_image" type="text"></label>#}
					</div>
				</div>
			</div>

			<div id="gallery" class="tab-pane">

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
					<a href="#%%UNSUBSCRIBE%%">Para des-suscribirse haga clic aqui</a>
					
				</div>
			</li>
		</ul>
	</section>
	<footer>
		<a href="#" class="redactor_modal_btn redactor_btn_modal_close">Close</a>
	</footer>
</div>
{% endblock %}