{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# Select2 master#}
	{{ stylesheet_link('vendors/select2-master/select2.css') }}
	{{ javascript_include('vendors/select2-master/select2.js')}}

	<script type="text/javascript">
		$(function () {
			$('.select2').select2({
				placeholder: 'Clic aqui para seleccionar'
			});
		});
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<h1 class="sectiontitle">Crear un lote de archivos PDF basado en una plantilla.</h1>
			<div class="bs-callout bs-callout-info" style="font-size: 1.1em;">
				<p>
				Aqui se cargan los archivos <strong>PDF:</strong>
				</p>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			{{flashSession.output()}}
		</div>
	</div>		
			
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<div class="header-background">
				<form method="POST" action="{{url('pdf/createbatch')}}" class="form-horizontal" enctype="multipart/form-data">
					<div class="form-group">
						<label for="name" class="col-sm-3 control-label">Nombre</label>
						<div class="col-sm-6">
							<input type="text" autofocus required class="form-control" id="name" name="name" placeholder="Nombre">
						</div>
					</div>
					<div class="form-group">
						<label for="template" class="col-sm-3 control-label">Seleccionar template</label>
						<div class="col-sm-6">
							<select class="select2" name="template" id="template" style="width: 100%;">
								{% if templates|length > 0%}
									{% for template in templates %}
										<option value="{{template.idPdftemplate}}">{{template.name}}</option>
									{% endfor %}
								{% endif %}	
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="file" class="col-sm-3 control-label">Seleccionar archivo CSV</label>
						<div class="col-sm-6">
							<input type="file" id="file" name="file">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<a href="{{url('tools')}}" class="btn btn-sm btn-default">Cancelar</a>
							<button type="submit" class="btn btn-sm btn-primary">Procesar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}