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
	{{ javascript_include('redactor/plugins/clips.js') }}
	{{ javascript_include('redactor/plugins/fontcolor.js') }}
	{{ javascript_include('redactor/plugins/fontfamily.js') }}
	{{ javascript_include('redactor/plugins/fontsize.js') }}
	{{ stylesheet_link('css/styles.css') }}
	{{ stylesheet_link('javascripts/dropzone/css/dropzone.css') }}

	{{ javascript_include('js/gallery.js') }}

	<script>
		var config = {sendUrl : "{{url('mail/editor')}}/{{idMail}}",
			uploadUrl: "{{url('asset/show')}}"};
		
		var mediaGallery = [
		{%for asset in assets%}
			new Gallery("{{asset['thumb']}}", "{{asset['image']}}", "{{asset['title']}}", {{asset['id']}}),
		{%endfor%}
		];
	</script>
	
	{{ javascript_include('js/block.js') }}
	{{ javascript_include('js/dropzone.js') }}
	{{ javascript_include('js/layout.js') }}
	{{ javascript_include('js/editor.js') }}


	
{% endblock %}
{% block content %}
	<br /><br />
<div class="row-fluid">
	<div class="span7 offset1">
		<div id="edit-area" class="module-cont clearfix">
		</div>
	</div>
	<div class="span4">
		<div id="toolbar">
			<ul class="nav nav-tabs">
				<li id="tablayouts" class="active">
					<a href="#layouts" data-toggle="tab">Layouts</a>
				</li>
				<li id="tabcomponents" class="">
					<a href="#components" data-toggle="tab">Componentes</a>
				</li>
				<li id="tabimages" class="">
					<a href="#images" data-toggle="tab">Imagenes</a>
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
								<div class="content-text">
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
								<div class="edit-image-tool icon-picture tool"></div>
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content">
								<div class="content-image">
									<a href="#" class="edit-image-tool"><img class="media-object" src="{{url('images/image')}}" alt="64x64" /></a>
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
								<div class="edit-image-tool icon-picture tool"></div>
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content clearfix">
								<div class="content-image pull-right">
									<a href="#" class="edit-image-tool"><img class="media-object" src="{{url('images/image')}}" alt="64x64" /></a>
								</div>
								<div class="content-text">
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
								</div>
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
								<div class="edit-image-tool icon-picture tool"></div>
								<div class="remove-tool icon-trash tool"></div>
							</div>
							<div class="content clearfix">
								<div class="content-image pull-left">
									<a href="#" class="edit-image-tool"><img class="media-object" src="{{url('images/image')}}" alt="64x64" /></a>
								</div>
								<div class="content-text">
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
								</div>
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
							<div class="content">
								<hr />
							</div>
						</div>
						<div class="module-information">
							<p>Separador</p>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="images">
					<div id="oneimage" class="well">
						
						<table>
							<tr>
							<td>
								<div id="imagedisplayer"></div>
							</td>
							<td>
								<div id="widthImg"></div>
								<div id="heightImg"></div>								
							</td>
							</tr>
						</table>
						<div id="imageslider">
						
						</div>
					</div>
					<div id="gallery">
						
					</div>
					<div id="modal-upload-image" class="">
						<a class="btn btn-default" data-toggle="modal" href="#uploadimage">Cargar Imagen</a>
					</div>
					<div id="uploadimage" class="modal hide fade well">
						<h2 class="text-center">Cargar Imagen</h2>
						<form action="{{url('asset/upload')}}" class="dropzone">
							<div class="dz-message"><span>Suelte su Imagen Aqui! <br/><br/>(o Click)</span></div>
						</form>
						<button id="close-modal-upload"class="btn btn-default" data-dismiss="modal">Regresar</button>
					</div>
				</div>	
			</div>	
		</div>
	</div>
</div>
<div>
	<div class="span2 offset2">
		<input id="guardar" type="submit" class="btn btn-blue" value="Guardar">
	</div>
</div>
{% endblock %}