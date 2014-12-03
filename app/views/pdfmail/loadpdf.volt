{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ stylesheet_link('vendors/uploadfy/uploadify.css') }}
	{{ javascript_include('vendors/uploadfy/jquery.uploadify.min.js')}}
	<script type="text/javascript">
		$(function() {
			$('#file_upload').uploadify({
				'requeueErrors' : true,
				'auto' : false,
				'method': 'Post',
				'buttonText' : '<i class="glyphicon glyphicon-plus-sign"></i> Seleccionar',
				'fileSizeLimit' : '2048KB',
				'fileTypeExts' : '*.pdf',
				'swf'      : '{{url('vendors/uploadfy/uploadify.swf')}}',
				'uploader' : '{{url('pdfmail/loadpdf')}}/{{mail.idMail}}',
				'removeTimeout' : 20,
				'onQueueComplete' : function(queueData) {
					$('#next').show('slow');
					$.gritter.add({class_name: 'success', title: '<i class="glyphicon glyphicon-ok"></i> Atención', text: queueData.uploadsSuccessful + " Archivo(s) fueron cargado(s) exitosamente", sticky: false, time: 5000});
				},
				'onSelectError' : function() {
					$.gritter.add({class_name: 'error', title: '<i class="glyphicon glyphicon-warning"></i> Atención', text: 'El archivo ' + file.name + ' contiene errores, por favor valide la información', sticky: false, time: 5000});
				}
			});
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
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-right">
			<a href="{{url('pdfmail/structurename')}}/{{mail.idMail}}" class="btn btn-sm btn-success">Siguiente</a>
		</div>
	</div>
	
	<div class="small-space"></div>
	
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<a href="javascript:$('#file_upload').uploadify('cancel','*');" class="btn btn-sm btn-danger">Remover todo</a>
			<a href="javascript:$('#file_upload').uploadify('upload','*')" class="btn btn-sm btn-primary">Subir archivos</a>
			<div class="space"></div>
			<input type="file" name="file_upload" id="file_upload" />
		</div>
	</div>
{% endblock %}