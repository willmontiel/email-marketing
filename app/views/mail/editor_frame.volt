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
	{{ stylesheet_link('css/styles.css') }}

	{{ javascript_include('js/gallery.js') }}

	<script>
		var mediaGallery = [
			new Gallery("/emarketing/images/108_thumb.jpeg", "/emarketing/images/108.png", 1),
			new Gallery("/emarketing/images/109_thumb.jpeg", "/emarketing/images/109.jpg", 2),
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
	<div class="span6 offset1">
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
					<div class="module module-separator">
						<div class="tools">
							<div class="handle-tool icon-move tool"></div>
							<div class="remove-tool icon-trash tool"></div>
						</div>
						<div class="content">
							<hr />
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