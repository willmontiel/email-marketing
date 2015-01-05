{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('vendors/plupload-2.1.2/js/plupload.full.min.js')}}
	<script type="text/javascript">
		$(function() {
			var uploader = new plupload.Uploader({
				browse_button: 'browse',
				container: document.getElementById('container'),
				url: "{{url('pdfmail/loadpdf')}}/{{mail.idMail}}",
				file_data_name: "file",
				filters: {
					mime_types : [
						{title : "Zip files", extensions : "zip"}
					],
					max_file_size: "500mb"
				},
				multi_selection: false,
				rename: true,
				sortable: true,
				dragdrop: true,
				
				views: {
					list: false,
					thumbs: false
				},
	
				init: {
					 PostInit: function() {
						document.getElementById('filelist').innerHTML = '';

						document.getElementById('start-upload').onclick = function() {
							uploader.start();
							return false;
						};
					},
					
					FilesAdded: function(up, files) {
						var html = '';
						plupload.each(files, function(file) {
							html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
						});
						document.getElementById('filelist').innerHTML += html;
					},

					UploadProgress: function(up, file) {
						document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
					},
					
					FileUploaded: function(up, err) {
						$('#next').show('slow');
						$('#buttons').hide('slow');
					},
					
					Error: function(up, err) {
						var message;
						if (err.response !== undefined) {
							var msg = JSON.parse(err.response);
							message = msg.error;
						}
						else {
							message = err.message;
						}
						
						$('#console').empty('slow');
						document.getElementById('console').innerHTML += "\n<strong>Error:</strong> " + message;
					}
				}
			});
			
			uploader.init();
		});
		
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<h1 class="sectiontitle">Cargar los archivos <strong>PDF</strong></h1>
			<div class="bs-callout bs-callout-info" style="font-size: 1.1em;">
				<p>
				Aqui se cargan los archivos <strong>PDF:</strong>
				</p>
				<ol>
					<li>Se deben comprimir todos los <strong>PDF's</strong> en un archivo <strong>ZIP.</strong></li>
					<li>Hacer clic en el botón <strong>Seleccionar</strong> archivo y seleccionar el archivo <strong>ZIP</strong> con los <strong>PDF's.</strong></li>
					<li>Hacer clic en el botón <strong>Cargar</strong> y esperar a que finalice el proceso.</li>
				</ol>
				si todo esta bien aparecerá un botón que dice <strong>Continuar</strong>, haga clic en él para seguir con el 
				proceso.
			</div>
		</div>
	</div>
	
	{% if files is defined %}
		<div class="row">
			<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									Archivos encontrados en el servidor: <strong>{{total}}</strong>, clic aqui para ampliar la información
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
								<ol>
									{% for file in files%}
										<li>{{file}}</li>
									{% endfor %}
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div>
				
			<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-right" id="buttons">
				<a href="{{url('pdfmail/compose')}}/{{mail.idMail}}" class="btn btn-sm btn-default">Atrás</a>
				<a href="{{url('pdfmail/structurename')}}/{{mail.idMail}}" class="btn btn-sm btn-success">Siguiente</a>
			</div>
		</div>
	{% endif %}
	
	<div class="row" id="next" style="display: none;">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<div class="header-background">
				<p style="font-size: 1.4em;color: #5cb85c;font-weight: 600;">
					Se han cargado los PDF's exitosamente, para continuar con el proceso haga clic en continuar
				</p>
				<p class="text-right">
					<a href="{{url('pdfmail/compose')}}/{{mail.idMail}}" class="btn btn-sm btn-default">Atrás</a>
					<a href="{{url('pdfmail/structurename')}}/{{mail.idMail}}" class="btn btn-sm btn-success">Continuar</a>
				</p>
			</div>
		</div>
	</div>
	
	<div class="small-space"></div>
	
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<ul id="filelist"></ul>
			<br />
			<div id="container">
				<a id="browse" href="javascript:;" class="btn btn-sm btn-primary">Selecciona archivo</a>
				<a id="start-upload" href="javascript:;" class="btn btn-sm btn-success">Cargar</a>
			</div>
			<br />
			<pre id="console" style="color: #d9534f;font-size: 1.2em;background-color: white;"></pre>
		</div>
	</div>
{% endblock %}