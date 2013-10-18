{% block header_javascript %}
	{{ javascript_include('js/jquery-1.9.1.js') }}
	{{ javascript_include('js/jquery_ui_1.10.3.js') }}
	{{ javascript_include('bootstrap/js/bootstrap.min.js') }}
	{{ stylesheet_link('bootstrap/css/bootstrap.css') }}
	{{ stylesheet_link('bootstrap/css/bootstrap-min.css') }}
	{{ javascript_include('redactor/redactor.js')}}
	{{ stylesheet_link('redactor/redactor.css') }}
	{{ stylesheet_link('css/styles.css') }}

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
	<div class="span3">
		<div id="toolbar">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#layouts" data-toggle="tab">Layouts</a>
				</li>
				<li class="">
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
						<div class="handle-tool icon-move tool"></div>
						<div class="edit-tool icon-pencil tool"></div>
						<div class="remove-tool icon-trash tool"></div>
						<div class="save-tool icon-ok"></div>
						<div class="content">
							<div class="content-text">
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
							</div>
						</div>
					</div>
					<div class="module module-image-only">
						<div class="handle-tool icon-move tool"></div>
						<div class="edit-image-tool icon-picture tool"></div>
						<div class="remove-tool icon-trash tool"></div>
						<div class="save-tool icon-ok"></div>
						<div class="content">
							<div class="content-image">
								<img class="media-object" src="{{url('images/image')}}" alt="64x64" />
							</div>
						</div>
					</div>
					<div class="module module-text-image">
						<div class="handle-tool icon-move tool"></div>
						<div class="edit-tool icon-pencil tool"></div>
						<div class="edit-image-tool icon-picture tool"></div>
						<div class="remove-tool icon-trash tool"></div>
						<div class="save-tool icon-ok"></div>
						<div class="content clearfix">
							<div class="content-image pull-right">
								<img class="media-object" src="{{url('images/image')}}" alt="64x64" />
							</div>
							<div class="content-text">
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
							</div>
						</div>
					</div>
					<div class="module module-image-text">
						<div class="handle-tool icon-move tool"></div>
						<div class="edit-tool icon-pencil tool"></div>
						<div class="remove-tool icon-trash tool"></div>
						<div class="save-tool icon-ok"></div>
						<div class="content clearfix">
							<div class="content-image pull-left">
								<img class="media-object" src="{{url('images/image')}}" alt="64x64" />
							</div>
							<div class="content-text">
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
							</div>
						</div>
					</div>
					<div class="module module-separator">
						<div class="handle-tool icon-move tool"></div>
						<div class="remove-tool icon-trash tool"></div>
						<div class="content">
							<hr />
						</div>
					</div>
				</div>
				<div class="tab-pane" id="images">
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