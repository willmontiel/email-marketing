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
			
			$("input:file").change(function (){
				$("#edit-file").val(1);
			});
		});
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<h1 class="sectiontitle">Editar plantilla <strong>{{pdf.name}}</strong></h1>
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
				<form method="POST" action="{{url('pdf/edit')}}/{{pdf.idPdftemplate}}" class="form-horizontal" enctype="multipart/form-data">
					<div class="form-group">
						<label for="name" class="col-sm-2 control-label">Nombre</label>
						<div class="col-sm-6">
							<span class="mail-detail">Recuerde que si cambia el nombre, deberá cambiar el archivo de nuevo.</span>
							<input type="text" value="{{pdf.name}}" autofocus required class="form-control" id="name" name="name" placeholder="Nombre">
							<input type="hidden" value="0"  id="edit-file" name="edit-file">
						</div>
					</div>
					<div class="form-group">
						<label for="accounts" class="col-sm-2 control-label">Seleccionar cuentas</label>
						<div class="col-sm-6">
							<select class="select2" name="accounts[]" multiple id="accounts" style="width: 100%;">
								{% if accounts|length > 0%}
									{% for account in accounts %}
									<option value="{{account.idAccount}}" {% for id in ids %} {% if id == account.idAccount %}selected="selected"{% endif %} {% endfor %}>{{account.companyName}}</option>
									{% endfor %}
								{% endif %}	
							</select>
						</div>
					</div>
						
					<div class="form-group">
						<div class="col-sm-2 control-label"></div>
						<div class="col-sm-6">
							{% if file == 1 %}
								<strong>Archivos encontrados:</strong> {{name}} <br />
								<span class="mail-detail">Recuerde que si carga otro archivo el anterior se sobreescribirá.</span>
							{% else %}
								No se encontró el archivo de template, por favor cargue uno
							{% endif %}
						</div>
					</div>		
						
					<div class="form-group">
						<label for="file" class="col-sm-2 control-label">Seleccionar archivo</label>
						<div class="col-sm-6">
							<input type="file" id="file" name="file">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<a href="{{url('pdf')}}" class="btn btn-sm btn-default">Cancelar</a>
							<button type="submit" class="btn btn-sm btn-primary">Editar</button>
						</div>
					</div>
				  </form>
			</div>
		</div>
	</div>	
{% endblock %}