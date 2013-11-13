{% block header_javascript %}
	{{ javascript_include('js/jquery-1.9.1.js') }}
	{{ javascript_include('js/jquery_ui_1.10.3.js') }}
	{{ javascript_include('bootstrap/js/bootstrap.min.js') }}
	{{ javascript_include('bootstrap/slider/js/bootstrap-slider.js') }}
	{{ stylesheet_link('bootstrap/css/bootstrap.css') }}
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
	{{ javascript_include('js/gallery.js') }}
	{{ javascript_include('js/block.js') }}
	{{ javascript_include('js/social_block.js') }}
	{{ javascript_include('js/dropzone.js') }}
	{{ javascript_include('js/layout.js') }}
	{{ javascript_include('js/editor.js') }}

<script>
		var config = {sendUrl : "{{url('mail/editor')}}/{{idMail}}", imagesUrl: "{{url('images')}}", previewUrl: "{{url('mail/previeweditor')}}", templateUrl : "{{url('template/create')}}"};
		
		var mediaGallery = [
		{%for asset in assets%}
			new Gallery("{{asset['thumb']}}", "{{asset['image']}}", "{{asset['title']}}", {{asset['id']}}),
		{%endfor%}
		];
		
		function verHTML(form) {
			editor.serializeDZ();
			var editorToSend = JSON.stringify(editor);
			
			$.ajax(
				{
				url: config.previewUrl,
				type: "POST",			
				data: { editor: editorToSend},
				success: function(response) {
					win = open("", "DisplayWindow", "toolbar=0, titlebar=yes , status=1, directories=yes, menubar=0, location=yes, directories=yes, width=700, height=650, left=1, top=0");
					win.document.write("" + response.response + "");
				}
			});
			
			editor.objectExists(editor);
		}
	</script>

{% endblock %}
{% block content %}
	<br /><br />
<div class="row-fluid">
	<div class="span9">
		<div id="edit-area" class="module-cont clearfix">
		</div>
	</div>
	<div class="span3">
		<div id="toolbar">
			<ul class="nav nav-tabs">
				<li id="tablayouts" class="active">
					<a href="#layouts" data-toggle="tab">Layouts</a>
				</li>
				<li id="tabcomponents" class="">
					<a href="#components" data-toggle="tab">Componentes</a>
				</li>
				<li id="tabstyles" class="">
					<a href="#styles" data-toggle="tab">Estilos</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="layouts">
				</div>
				
				<div class="tab-pane" id="components">
					<div class="module-container">
						<div class="module module-text-only">
							<div class="tools">
								<div class="handle-tool icon-move tool"></div>
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content">
								<div class="content-text full-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
								</div>
							</div>
						</div>
						<div class="module-information">
							<p>Texto</p>
						</div>
					</div>
					<div class="module-container">
						<div class="module module-image-only">
							<div class="tools">
								<div class="handle-tool icon-move tool"></div>
								{#<div class="edit-image-tool icon-picture tool"></div>#}
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content clearfix">
								<div class="content-image full-content pull-left">
									<img data-toggle="modal" href="#images" class="media-object" src="{{url('images/image')}}" alt="64x64" />
								</div>
							</div>
						</div>
						<div class="module-information">
							<p>Imagen</p>
						</div>
					</div>
					<div class="module-container">
						<div class="module module-text-image">
							<div class="tools">
								<div class="handle-tool icon-move tool"></div>
								{#<div class="edit-image-tool icon-picture tool"></div>#}
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content clearfix">
								<table>
									<tr>
										<td>
											<div class="content-text">
												<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
											</div>
										</td>
										<td>
											<div class="content-image pull-left">
												<img data-toggle="modal" href="#images" class="media-object" src="{{url('images/image')}}" alt="64x64" />
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="module-information">
							<p>Texto - Imagen</p>
						</div>
					</div>
					<div class="module-container">
						<div class="module module-image-text">
							<div class="tools">
								<div class="handle-tool icon-move tool"></div>
								{#<div class="edit-image-tool icon-picture tool"></div>#}
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content clearfix">
								<table>
									<tr>
										<td>
											<div class="content-image pull-left">
												<img data-toggle="modal" href="#images" class="media-object" src="{{url('images/image')}}" alt="64x64" />
											</div>
										</td>
										<td>
											<div class="content-text">
												<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
											</div>
										</td>
									</tr>
								</table>
								
								
							</div>
						</div>
						<div class="module-information">
							<p>Imagen - Texto</p>
						</div>
					</div>
					<div class="module-container">
						<div class="module module-separator">
							<div class="tools">
								<div class="handle-tool icon-move tool"></div>
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content full-content">
								<hr />
							</div>
						</div>
						<div class="module-information">
							<p>Separador</p>
						</div>
					</div>
					<div class="module-container">
						<div class="module module-social-follow">
							<div class="tools">
								<div class="handle-tool icon-move tool"></div>
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content clearfix">
								<div class="sub_social_content content_facebook"></div>
								<div class="sub_social_content content_twitter"></div>
								<div class="sub_social_content content_linkedin"></div>
								<div class="sub_social_content content_google_plus"></div>
							</div>
						</div>
						<div class="module-information">
							<p>Social Follow</p>
						</div>
					</div>
					<div class="module-container">
						<div class="module module-social-share">
							<div class="tools">
								<div class="handle-tool icon-move tool"></div>
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content clearfix">
								<div class="sub_social_content content_facebook"></div>
								<div class="sub_social_content content_twitter"></div>
								<div class="sub_social_content content_linkedin"></div>
								<div class="sub_social_content content_google_plus"></div>
							</div>
						</div>
						<div class="module-information">
							<p>Social Share</p>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="styles">
					<div class="panel-group" id="accordion">
						
					</div>
				</div>
			</div>	
		</div>
			
		<div id="images" class="modal hide fade">
			<div class="modal-header">
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
						<div class="pull-left">
							<div id="widthImg"></div>
							<div id="heightImg"></div>	

							<div id="align_image">
								<div class="btn-group">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									  Alinear <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li class="chose_align" data-dropdown="left"><a href="#">Izquierda</a></li>
										<li class="chose_align" data-dropdown="center"><a href="#">Centro</a></li>
										<li class="chose_align" data-dropdown="right"><a href="#">Derecha</a></li>
									</ul>
								</div>
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
			
		<div id="socialnetwork" class="modal hide fade">
			<div class="modal-header">
				<div id="social_title"></div>
			</div>
			<div class="modal-body">
				<div id="socialData">

				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal">Aceptar</a>
			</div>
		</div>
	</div>
</div>
<div>
	<div class="span2 offset2">
		<input id="guardar" type="submit" class="btn btn-blue" value="Guardar">
	</div>
	<div class="span2 offset2">
		<input onclick="verHTML(this.form)" type="button" value="Visualizar" class="btn btn-black">
	</div>
	<div class="span2">
		<input id="createTemplate" type="submit" value="Guardar como Plantilla" class="btn btn-black">
		<div id="newtemplatename">
			<input type="text">
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
			
			{%for cf in cfs%}
				<li>
					<a href="#" class="redactor_clip_link">{{cf['originalName']}}</a>

					<div class="redactor_clip" style="display: none;">
						%%{{cf['linkName']}}%%
					</div>
				</li>
			{%endfor%}
			
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