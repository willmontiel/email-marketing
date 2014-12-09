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
					
					UploadComplete: function(up, err) {
						$('#next').show('slow');
					},
					
					Error: function(up, err) {
						var message = JSON.parse(err.response);
						document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + message.error;
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
			<h1 class="sectiontitle">Cargar los archivos pdf</h1>
			<div class="bs-callout bs-callout-info">
				El siguiente paso es cargar todos los archivos PDF, para que el sistema los empareje con sus respectivo contacto
			</div>
		</div>
	</div>
	
	<div class="row header-background" id="next" style="display: none;">
		<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
			Se han cargado los PDF's exitosamente, para continuar con el proceso haga clic en continuar
		</div>
		<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10 text-right">
			<a href="{{url('pdfmail/structurename')}}/{{mail.idMail}}" class="btn btn-sm btn-success">Continuar</a>
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
			<pre id="console"></pre>
			
		</div>
	</div>
{% endblock %}